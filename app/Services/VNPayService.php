<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class VNPayService
{
    private $vnp_TmnCode;
    private $vnp_HashSecret;
    private $vnp_Url;
    private $vnp_ReturnUrl;

    public function __construct()
    {
        $this->vnp_TmnCode = config('services.vnpay.tmn_code');
        $this->vnp_HashSecret = config('services.vnpay.hash_secret');
        $this->vnp_Url = config('services.vnpay.url');
        $this->vnp_ReturnUrl = config('services.vnpay.return_url');
    }

    /**
     * Create VNPay payment URL
     */
    public function createPaymentUrl(Order $order, string $ipAddress): string
    {
        Log::info('VNPayService createPaymentUrl started', [
            'order_id' => $order->id,
            'order_total' => $order->total,
            'ip_address' => $ipAddress,
            'tmn_code' => $this->vnp_TmnCode,
            'return_url' => $this->vnp_ReturnUrl
        ]);

        $vnp_TxnRef = $order->id . '_' . time();
        $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $order->id . ' - PC Shop';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = (int) ($order->total * 100); // VNPay yêu cầu amount tính bằng xu (VND * 100)
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $ipAddress;

        // Validate required parameters
        if (empty($this->vnp_TmnCode) || empty($this->vnp_HashSecret) || empty($this->vnp_Url)) {
            Log::error('VNPay configuration missing', [
                'tmn_code' => $this->vnp_TmnCode,
                'has_hash_secret' => !empty($this->vnp_HashSecret),
                'vnp_url' => $this->vnp_Url
            ]);
            throw new \Exception('VNPay configuration is incomplete');
        }

        if ($vnp_Amount < 5000 || $vnp_Amount >= 1000000000000) { // 50 VND - 100 triệu VND (tính bằng xu)
            Log::error('VNPay amount out of range', ['amount' => $vnp_Amount, 'order_total' => $order->total]);
            throw new \Exception('Payment amount must be between 50 and 100,000,000 VND');
        }

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $this->vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->vnp_Url . "?" . $query;
        if (isset($this->vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        Log::info('VNPay URL generated', [
            'vnp_url' => $vnp_Url,
            'url_length' => strlen($vnp_Url),
            'amount' => $vnp_Amount,
            'txn_ref' => $vnp_TxnRef,
            'hashdata' => $hashdata,
            'secure_hash' => substr($vnpSecureHash ?? '', 0, 10) . '...'
        ]);

        // Store transaction reference for verification
        $order->update([
            'vnpay_txn_ref' => $vnp_TxnRef,
            'status' => 'pending'
        ]);

        return $vnp_Url;
    }

    /**
     * Verify VNPay callback
     */
    public function verifyCallback(array $data): array
    {
        Log::info('VNPay callback received', ['data' => $data]);

        $vnp_SecureHash = $data['vnp_SecureHash'] ?? '';
        unset($data['vnp_SecureHash']);
        unset($data['vnp_SecureHashType']);

        ksort($data);
        $hashdata = "";
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);

        Log::info('VNPay hash verification', [
            'hashdata' => $hashdata,
            'received_hash' => $vnp_SecureHash,
            'calculated_hash' => $secureHash,
            'hash_match' => $secureHash == $vnp_SecureHash,
            'response_code' => $data['vnp_ResponseCode'] ?? 'unknown'
        ]);

        $result = [
            'success' => false,
            'message' => '',
            'order_id' => null,
            'amount' => 0,
            'transaction_id' => '',
        ];

        if ($secureHash == $vnp_SecureHash) {
            if ($data['vnp_ResponseCode'] == '00') {
                // Payment successful
                $vnp_TxnRef = $data['vnp_TxnRef'];
                $orderId = explode('_', $vnp_TxnRef)[0];

                $order = Order::find($orderId);
                Log::info('VNPay order verification', [
                    'vnp_txn_ref' => $vnp_TxnRef,
                    'extracted_order_id' => $orderId,
                    'order_found' => $order ? true : false,
                    'order_txn_ref' => $order ? $order->vnpay_txn_ref : null,
                    'txn_ref_match' => $order ? ($order->vnpay_txn_ref == $vnp_TxnRef) : false
                ]);

                if ($order && $order->vnpay_txn_ref == $vnp_TxnRef) {
                    $result = [
                        'success' => true,
                        'message' => 'Thanh toán thành công',
                        'order_id' => $orderId,
                        'amount' => $data['vnp_Amount'] / 100, // Chuyển từ xu về VND
                        'transaction_id' => $data['vnp_TransactionNo'],
                    ];
                } else {
                    $result['message'] = 'Đơn hàng không tồn tại hoặc không khớp';
                }
            } else {
                $result['message'] = $this->getResponseMessage($data['vnp_ResponseCode']);
            }
        } else {
            $result['message'] = 'Chữ ký không hợp lệ';
        }

        return $result;
    }

    /**
     * Get response message from VNPay response code
     */
    private function getResponseMessage(string $responseCode): string
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP).',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
            '75' => 'Ngân hàng thanh toán đang bảo trì.',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định.',
            '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)',
        ];

        return $messages[$responseCode] ?? 'Lỗi không xác định';
    }
}
