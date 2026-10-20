<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'message', 'consented_at', 'read_at'];

    protected function casts(): array
    {
        return ['consented_at' => 'datetime', 'read_at' => 'datetime'];
    }
}
