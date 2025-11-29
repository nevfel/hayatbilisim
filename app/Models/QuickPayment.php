<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class QuickPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'amount',
        'status',
        'payment_ok',
        'gon_email',
        'gon_adsoyad',
        'gon_phone',
        'payment_info',
        'payment_extra',
        'payment_date',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_ok' => 'boolean',
        'payment_extra' => 'array',
        'payment_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quickPayment) {
            if (!$quickPayment->payment_number) {
                $quickPayment->payment_number = 'QP-' . strtoupper(Str::random(10));
            }
        });
    }

    /**
     * Ödeme linki oluştur
     */
    public function getPaymentLinkAttribute()
    {
        return route('quick-payment.show', ['payment_number' => $this->payment_number]);
    }

    /**
     * Ödeme durumu kontrolü
     */
    public function isPaid()
    {
        return $this->payment_ok == 1 && $this->status === 'completed';
    }

    /**
     * Ödeme bekliyor mu?
     */
    public function isPending()
    {
        return $this->status === 'pending' && $this->payment_ok == 0;
    }
}
