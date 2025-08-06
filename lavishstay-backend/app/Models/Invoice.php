<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';
    public $timestamps = false; // Table only has issued_at, not created_at/updated_at

    protected $fillable = [
        'booking_id',
        'total_amount_vnd',
        'issued_at',
        'status'
    ];

    protected $casts = [
        'total_amount_vnd' => 'decimal:2',
        'issued_at' => 'datetime'
    ];

    /**
     * Relationship with booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount_vnd, 0, ',', '.') . ' ₫';
    }

    /**
     * Scope for draft invoices
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'Draft');
    }

    /**
     * Scope for sent invoices
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'Sent');
    }

    /**
     * Scope for paid invoices
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'Paid');
    }

    /**
     * Mark invoice as sent
     */
    public function markAsSent()
    {
        $this->update(['status' => 'Sent']);
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid()
    {
        $this->update(['status' => 'Paid']);
    }

    /**
     * Check if invoice is draft
     */
    public function isDraft()
    {
        return $this->status === 'Draft';
    }

    /**
     * Check if invoice is sent
     */
    public function isSent()
    {
        return $this->status === 'Sent';
    }

    /**
     * Check if invoice is paid
     */
    public function isPaid()
    {
        return $this->status === 'Paid';
    }

    /**
     * Get invoice number (formatted)
     */
    public function getInvoiceNumberAttribute()
    {
        return 'INV-' . str_pad($this->invoice_id, 6, '0', STR_PAD_LEFT);
    }
}