<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['customer_id', 'quote_id', 'amount', 'sold_at', 'notes'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'sold_at' => 'date'];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}
