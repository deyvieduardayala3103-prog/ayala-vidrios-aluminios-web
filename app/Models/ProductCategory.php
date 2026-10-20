<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = ['name'];

    protected function casts(): array
    {
        return [];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
