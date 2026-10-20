<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteStatus extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    protected function casts(): array
    {
        return [];
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
