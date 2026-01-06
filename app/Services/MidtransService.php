<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;
use Illuminate\Support\Str;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    public function createSnapToken(Order $order): string
    {
        if (!$order->relationLoaded('items') || !$order->relationLoaded('user')) {
            throw new Exception('Order items atau user belum di-load.');
        }

        if ($order->items->isEmpty()) {
            throw new Exception('Order tidak memiliki item.');
        }

        $phone = $order->shipping_phone ?? $order->user->phone ?? '';
        $phone = preg_replace('/[^0-9]/', '', $phone); // bersihkan format, hanya angka
        if (empty($phone)) {
            throw new Exception('Nomor telepon wajib diisi.');
        }
        // Tambah +62 jika mulai dengan 08 (opsional tapi direkomendasikan)
        if (str_starts_with($phone, '08')) {
            $phone = '+62' . substr($phone, 1);
        }

        $grossAmount = (int) round($order->total_amount);

        // Validasi minimal amount
        if ($grossAmount < 1) {
            throw new Exception(
                'Total pembayaran tidak valid. ' .
                'Nilai transaksi harus minimal Rp1. ' .
                "(Saat ini: Rp{$grossAmount})"
            );
        }

        $transactionDetails = [
            'order_id'     => $order->order_number . '-' . time(), // unik setiap generate
            'gross_amount' => $grossAmount,
        ];

        $customerDetails = [
            'first_name'       => $order->user->name ?? 'Customer',
            'email'            => $order->user->email ?? 'no-reply@example.com',
            'phone'            => $phone,
            'billing_address'  => $this->buildAddress($order),
            'shipping_address' => $this->buildAddress($order),
        ];

        $itemDetails = $order->items->map(function ($item) {
            return [
                'id'       => (string) $item->product_id,
                'price'    => (int) round($item->price),        // pastikan integer
                'quantity' => (int) $item->quantity,
                'name'     => Str::limit($item->product_name ?? 'Produk', 50),
            ];
        })->toArray();

        // Tambahkan shipping sebagai item jika > 0
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) round($order->shipping_cost),
                'quantity' => 1,
                'name'     => 'Biaya Pengiriman',
            ];
        }

        // Optional: tambahkan discount/fee lain jika ada di model Order

        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details'    => $customerDetails,
            'item_details'        => $itemDetails,
            // 'enabled_payments' => ['shopeepay', 'gopay', 'bca_va', 'credit_card'],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Optional: simpan ke database jika belum ada kolom
            // $order->update(['snap_token' => $snapToken]);

            return $snapToken;
        } catch (Exception $e) {
            logger()->error('Midtrans Snap Token Error', [
                'order_id'       => $order->order_number,
                'gross_amount'   => $grossAmount,
                'item_total'     => $this->calculateItemTotal($itemDetails), // untuk debug
                'error'          => $e->getMessage(),
                'params'         => $params,
            ]);

            throw new Exception('Gagal membuat transaksi pembayaran: ' . $e->getMessage());
        }
    }

    private function buildAddress(Order $order): array
    {
        $name = $order->shipping_name ?? $order->user->name ?? 'Customer';
        $phone = preg_replace('/[^0-9]/', '', $order->shipping_phone ?? $order->user->phone ?? '');

        return [
            'first_name' => Str::limit($name, 50, ''),
            'phone'      => $phone,
            'address'    => $order->shipping_address ?? 'Alamat tidak lengkap',
            'city'       => '', // optional, tambahkan jika ada di model
            'postal_code'=> '', // optional
            'country_code' => 'IDN',
        ];
    }

    // Helper untuk debug mismatch gross_amount vs item_details
    private function calculateItemTotal(array $items): int
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}