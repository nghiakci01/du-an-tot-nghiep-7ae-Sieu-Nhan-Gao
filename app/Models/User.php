<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';

    const ROLE_STAFF = 'staff';

    const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'avatar',
        'cart_data',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'cart_data'          => 'array',
        ];
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public static function getAdmins()
    {
        return self::where('role', self::ROLE_ADMIN)->get();
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function claimedCoupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_user')
            ->withPivot('claimed_at', 'source', 'source_id')
            ->withTimestamps();
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class)->orderByDesc('is_default');
    }

    public function defaultAddress()
    {
        return $this->hasOne(UserAddress::class)->where('is_default', true);
    }

    public function bankAccounts()
    {
        return $this->hasMany(UserBankAccount::class)->orderByDesc('is_default');
    }


    public function orderReturnRequests()
    {
        return $this->hasMany(OrderReturnRequest::class)->latest();
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class)->latest();
    }

    public function walletTopupRequests()
    {
        return $this->hasMany(WalletTopupRequest::class)->latest();
    }

    public function walletWithdrawRequests()
    {
        return $this->hasMany(WalletWithdrawRequest::class)->latest();
    }

    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return asset('assets/images/default-avatar.png');
        }

        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        return asset('storage/'.$this->avatar);
    }
}
