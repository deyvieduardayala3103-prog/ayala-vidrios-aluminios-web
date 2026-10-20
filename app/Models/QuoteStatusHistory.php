<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteStatusHistory extends Model
{
    protected $fillable = ['quote_id', 'quote_status_id', 'user_id', 'note', 'amount'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public function status()
    {
        return $this->belongsTo(QuoteStatus::class, 'quote_status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
