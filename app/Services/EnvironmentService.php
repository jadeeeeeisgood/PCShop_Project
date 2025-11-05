<?php

namespace App\Services;

class EnvironmentService
{
    /**
     * Detect if running on AWS Elastic Beanstalk
     */
    public static function isAWS(): bool
    {
        return !empty($_SERVER['AWS_EXECUTION_ENV']) ||
            !empty($_SERVER['EB_NODE_COMMAND']) ||
            file_exists('/opt/elasticbeanstalk');
    }

    /**
     * Detect if running locally
     */
    public static function isLocal(): bool
    {
        return in_array(request()->ip(), ['127.0.0.1', '::1', 'localhost']) ||
            str_contains(request()->getHost(), 'localhost') ||
            str_contains(request()->getHost(), '127.0.0.1');
    }

    /**
     * Get appropriate database configuration
     */
    public static function getDatabaseConfig(): array
    {
        if (self::isAWS()) {
            return [
                'host' => 'awseb-e-p8pbdcmmah-stack-awsebrdsdatabase-acahlduhby1v.cc9wku8828oa.us-east-1.rds.amazonaws.com',
                'database' => 'ebdb',
                'username' => 'admin',
                'password' => '0356572215t',
            ];
        }

        return [
            'host' => '127.0.0.1',
            'database' => 'pcshop',
            'username' => 'root',
            'password' => '',
        ];
    }

    /**
     * Get app URL based on environment
     */
    public static function getAppUrl(): string
    {
        if (self::isAWS()) {
            return 'https://pcshop-final.eba-gm3xqw32.us-east-1.elasticbeanstalk.com';
        }

        return 'http://127.0.0.1:8000';
    }

    /**
     * Get VNPay return URL
     */
    public static function getVNPayReturnUrl(): string
    {
        if (self::isAWS()) {
            return 'https://pcshop-final.eba-gm3xqw32.us-east-1.elasticbeanstalk.com/payment/vnpay/callback';
        }

        return 'http://127.0.0.1:8000/payment/vnpay/callback';
    }

    /**
     * Check if should use HTTPS
     */
    public static function shouldUseHttps(): bool
    {
        return self::isAWS();
    }

    /**
     * Get debug mode
     */
    public static function isDebugMode(): bool
    {
        return !self::isAWS();
    }
}