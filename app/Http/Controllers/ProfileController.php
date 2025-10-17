<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('dashboard')->with('status', 'Thông tin cá nhân đã được cập nhật thành công!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display user orders
     */
    public function orders(Request $request): View
    {
        $query = $request->user()->orders()
            ->with(['orderItems.product'])
            ->latest();

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('profile.orders', compact('orders'));
    }

    /**
     * Show specific order details
     */
    public function showOrder(Request $request, Order $order): View
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== $request->user()->id) {
            abort(404);
        }

        $order->load(['orderItems.product']);

        return view('profile.order-detail', compact('order'));
    }

    /**
     * Cancel an order
     */
    public function cancelOrder(Request $request, Order $order): RedirectResponse
    {
        // Ensure user can only cancel their own orders
        if ($order->user_id !== $request->user()->id) {
            abort(404);
        }

        // Only allow cancelling pending orders
        if ($order->status !== 'pending') {
            return back()->with('error', 'Không thể hủy đơn hàng này.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('profile.orders')->with('success', 'Đơn hàng đã được hủy thành công.');
    }
}
