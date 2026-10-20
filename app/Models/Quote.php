<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = ['reference', 'customer_id', 'specialty_id', 'quote_status_id', 'description', 'zone', 'amount', 'observations', 'consented_at'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'consented_at' => 'datetime'];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function status()
    {
        return $this->belongsTo(QuoteStatus::class, 'quote_status_id');
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function attachments()
    {
        return $this->hasMany(QuoteAttachment::class);
    }

    public function histories()
    {
        return $this->hasMany(QuoteStatusHistory::class)->latest('id');
    }
}
