<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'description', 'content', 'meta_title', 'meta_description', 'meta_keywords'];

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'content',
        'sku', 'price', 'sale_price', 'stock', 'images',
        'attributes', 'meta', 'is_featured', 'is_active',
        'meta_title', 'meta_description', 'meta_keywords'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'images' => 'array',
        'attributes' => 'array',
        'meta' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function getDiscountPercent(): ?float
    {
        if ($this->sale_price && $this->sale_price < $this->price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100, 2);
        }
        return null;
    }

    public function getCurrentPrice(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function isOnSale(): bool
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    /**
     * Get price in selected currency
     */
    public function getPriceInCurrency(?string $currency = null): float
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        $targetCurrency = $currency ?? $currencyService->getCurrentCurrency();
        $baseCurrency = $this->base_currency ?? 'RON';
        
        return $currencyService->convert($this->price, $baseCurrency, $targetCurrency);
    }

    /**
     * Get sale price in selected currency
     */
    public function getSalePriceInCurrency(?string $currency = null): ?float
    {
        if (!$this->sale_price) {
            return null;
        }

        $currencyService = app(\App\Services\CurrencyService::class);
        $targetCurrency = $currency ?? $currencyService->getCurrentCurrency();
        $baseCurrency = $this->base_currency ?? 'RON';
        
        return $currencyService->convert($this->sale_price, $baseCurrency, $targetCurrency);
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPrice(?string $currency = null): string
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        $targetCurrency = $currency ?? $currencyService->getCurrentCurrency();
        $price = $this->getPriceInCurrency($targetCurrency);
        
        return $currencyService->formatPrice($price, $targetCurrency);
    }

    /**
     * Get formatted sale price with currency
     */
    public function getFormattedSalePrice(?string $currency = null): ?string
    {
        $salePrice = $this->getSalePriceInCurrency($currency);
        if (!$salePrice) {
            return null;
        }

        $currencyService = app(\App\Services\CurrencyService::class);
        $targetCurrency = $currency ?? $currencyService->getCurrentCurrency();
        
        return $currencyService->formatPrice($salePrice, $targetCurrency);
    }
}

