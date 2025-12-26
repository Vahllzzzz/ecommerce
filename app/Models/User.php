<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     * Ini mencegah vulnerability mass-assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'google_id',
        'phone',
        'address',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi ke JSON/array.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data otomatis.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * User memiliki satu keranjang aktif.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlists()
    {
        // Relasi User ke Product melalui tabel wishlists
        return $this->belongsToMany(Product::class, 'wishlists')
                    ->withTimestamps(); // Agar created_at/updated_at di pivot terisi
    }

// Helper untuk cek apakah user sudah wishlist produk tertentu
public function hasInWishlist(Product $product)
{
    return $this->wishlists()->where('product_id', $product->id)->exists();
}
    /**
     * User memiliki banyak pesanan.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi many-to-many ke products melalui wishlists.
     */
    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
                    ->withTimestamps();
    }

    // ==================== HELPER METHODS ====================

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    
public function getAvatarUrlAttribute(): string
{
    if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
        return asset('storage/' . $this->avatar);
    }
    if (str_starts_with($this->avatar ?? '', 'http')) {
        return $this->avatar;
    }
    $hash = md5(strtolower(trim($this->email)));
    return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
}

/**
 * Get initials from name for avatar fallback.
 * Contoh: "Agung Wahyudi" -> "AW"
 * Berguna jika kita ingin membuat UI avatar berupa inisial huruf teks.
 */
public function getInitialsAttribute(): string
{
    $words = explode(' ', $this->name);
    $initials = '';

    foreach ($words as $word) {
        // Ambil huruf pertama tiap kata dan kapitalkan
        $initials .= strtoupper(substr($word, 0, 1));
    }

    return substr($initials, 0, 2);
}
}