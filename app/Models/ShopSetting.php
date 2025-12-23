<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopSetting extends Model
{
    protected $fillable = [
        'currency', 'currency_symbol', 'currency_position', 'tax_enabled', 'tax_rate',
        'shipping_enabled', 'payment_gateways', 'terms_and_conditions', 'privacy_policy',
        'return_policy', 'order_prefix', 'inventory_management', 'low_stock_alert', 'low_stock_threshold'
    ];

    protected $casts = [
        'tax_enabled' => 'boolean',
        'shipping_enabled' => 'boolean',
        'inventory_management' => 'boolean',
        'low_stock_alert' => 'boolean',
        'payment_gateways' => 'array',
        'tax_rate' => 'decimal:2',
        'low_stock_threshold' => 'integer',
    ];

    public static function get()
    {
        return self::first() ?? self::create([]);
    }
}
