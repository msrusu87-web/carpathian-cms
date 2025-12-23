<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number', 'user_id', 'order_id',
        'client_name', 'client_email', 'client_company', 'client_company_reg',
        'client_vat_number', 'client_address', 'client_city', 'client_postal_code',
        'client_country', 'client_phone',
        'invoice_date', 'due_date', 'status', 'payment_status',
        'subtotal', 'tax_rate', 'tax_amount', 'discount_amount', 'total',
        'paid_at', 'payment_method', 'payment_reference',
        'notes', 'terms', 'footer_text'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function calculateTotals(): void
    {
        $subtotal = 0;
        $taxAmount = 0;

        foreach ($this->items as $item) {
            $itemSubtotal = $item->quantity * $item->unit_price;
            $itemTax = $itemSubtotal * ($item->tax_rate / 100);
            
            $subtotal += $itemSubtotal;
            $taxAmount += $itemTax;
            
            $item->update([
                'tax_amount' => $itemTax,
                'total' => $itemSubtotal + $itemTax
            ]);
        }

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $subtotal + $taxAmount - $this->discount_amount
        ]);
    }

    public static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $lastInvoice = self::whereYear('created_at', $year)->latest()->first();
        $number = $lastInvoice ? (int) substr($lastInvoice->invoice_number, -5) + 1 : 1;
        return 'INV-' . $year . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function isOverdue(): bool
    {
        return $this->payment_status !== 'paid' && $this->due_date->isPast();
    }

    public function markAsPaid(string $paymentMethod = null, string $reference = null): void
    {
        $this->update([
            'payment_status' => 'paid',
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $paymentMethod,
            'payment_reference' => $reference
        ]);
    }
}
