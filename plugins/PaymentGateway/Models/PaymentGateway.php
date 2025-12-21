<?php

namespace Plugins\PaymentGateway\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $table = 'payment_gateways';

    protected $fillable = [
        'name',
        'slug',
        'provider',
        'credentials',
        'config',
        'fee_percentage',
        'fee_fixed',
        'supports_quick_links',
        'supports_product_checkout',
        'is_active',
        'test_mode',
        'webhook_url',
        'callback_url',
    ];

    protected $casts = [
        'credentials' => 'array', // Changed from encrypted:array to avoid decryption issues
        'config' => 'array',
        'fee_percentage' => 'decimal:2',
        'fee_fixed' => 'decimal:2',
        'supports_quick_links' => 'boolean',
        'supports_product_checkout' => 'boolean',
        'is_active' => 'boolean',
        'test_mode' => 'boolean',
    ];

    /**
     * Get the decrypted credentials
     */
    public function getCredentialsAttribute($value)
    {
        return $this->castAttribute('credentials', $value);
    }

    /**
     * Calculate total fee for an amount
     */
    public function calculateFee(float $amount): float
    {
        $percentageFee = ($amount * $this->fee_percentage) / 100;
        return round($percentageFee + $this->fee_fixed, 2);
    }

    /**
     * Get total amount including fees
     */
    public function getTotalWithFees(float $amount): float
    {
        return round($amount + $this->calculateFee($amount), 2);
    }

    /**
     * Scope for active gateways
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for gateways supporting quick links
     */
    public function scopeQuickLinks($query)
    {
        return $query->where('supports_quick_links', true);
    }

    /**
     * Scope for gateways supporting product checkout
     */
    public function scopeProductCheckout($query)
    {
        return $query->where('supports_product_checkout', true);
    }
}
