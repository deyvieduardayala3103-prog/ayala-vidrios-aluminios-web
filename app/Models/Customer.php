<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'address'];

    protected function casts(): array
    {
        return [];
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
