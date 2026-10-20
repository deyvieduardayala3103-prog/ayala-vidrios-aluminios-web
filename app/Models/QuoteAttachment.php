<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteAttachment extends Model
{
    protected $fillable = ['quote_id', 'path', 'original_name'];

    protected function casts(): array
    {
        return [];
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}
