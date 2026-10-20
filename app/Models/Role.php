<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    protected function casts(): array
    {
        return [];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
