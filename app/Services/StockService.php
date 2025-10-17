<?php

namespace App\Services;

use App\Models\Product;
use App\Events\StockUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StockService
{
    /**
     * Reserve stock for a product (during checkout process)
     */
    public function reserveStock(int $productId, int $quantity, string $sessionId): bool
    {
        return DB::transaction(function () use ($productId, $quantity, $sessionId) {
            $product = Product::lockForUpdate()->find($productId);

            if (!$product || $product->stock < $quantity) {
                return false;
            }

            // Reserve stock for 15 minutes
            $reservationKey = "stock_reserved_{$productId}_{$sessionId}";
            $reservedQuantity = Cache::get($reservationKey, 0);

            // Check if we can reserve more
            $totalReserved = $this->getTotalReservedStock($productId);
            if (($product->stock - $totalReserved - $quantity) < 0) {
                return false;
            }

            // Reserve the stock
            Cache::put($reservationKey, $quantity, now()->addMinutes(15));

            // Broadcast stock update
            $availableStock = $product->stock - $totalReserved - $quantity;
            broadcast(new StockUpdated($productId, $availableStock))->toOthers();

            return true;
        });
    }

    /**
     * Release reserved stock
     */
    public function releaseStock(int $productId, string $sessionId): void
    {
        $reservationKey = "stock_reserved_{$productId}_{$sessionId}";
        Cache::forget($reservationKey);

        // Broadcast updated available stock
        $product = Product::find($productId);
        if ($product) {
            $availableStock = $product->stock - $this->getTotalReservedStock($productId);
            broadcast(new StockUpdated($productId, $availableStock))->toOthers();
        }
    }

    /**
     * Confirm stock reduction (when order is placed)
     */
    public function confirmStockReduction(int $productId, int $quantity, string $sessionId): bool
    {
        return DB::transaction(function () use ($productId, $quantity, $sessionId) {
            $product = Product::lockForUpdate()->find($productId);

            if (!$product || $product->stock < $quantity) {
                return false;
            }

            // Reduce actual stock
            $product->decrement('stock', $quantity);

            // Release reservation
            $this->releaseStock($productId, $sessionId);

            // Broadcast final stock update
            $availableStock = $product->fresh()->stock - $this->getTotalReservedStock($productId);
            broadcast(new StockUpdated($productId, $availableStock))->toOthers();

            return true;
        });
    }

    /**
     * Get total reserved stock for a product
     */
    private function getTotalReservedStock(int $productId): int
    {
        $pattern = "stock_reserved_{$productId}_*";
        $keys = Cache::get('laravel_cache:' . $pattern, []);

        // For file cache, we need to check all possible reservations
        $totalReserved = 0;
        for ($i = 0; $i < 1000; $i++) { // Check reasonable number of sessions
            $key = "stock_reserved_{$productId}_session_{$i}";
            $reserved = Cache::get($key, 0);
            $totalReserved += $reserved;
        }

        return $totalReserved;
    }

    /**
     * Get available stock (actual stock - reserved stock)
     */
    public function getAvailableStock(int $productId): int
    {
        $product = Product::find($productId);
        if (!$product) {
            return 0;
        }

        $reservedStock = $this->getTotalReservedStock($productId);
        return max(0, $product->stock - $reservedStock);
    }

    /**
     * Clean up expired reservations
     */
    public function cleanupExpiredReservations(): void
    {
        // This would typically be run by a scheduled job
        // Cache keys expire automatically, so no manual cleanup needed for file cache
    }
}