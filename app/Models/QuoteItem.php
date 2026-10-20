<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    protected $fillable = ['quote_id', 'work_type', 'width', 'height', 'unit', 'quantity'];

    protected function casts(): array
    {
        return [];
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}
