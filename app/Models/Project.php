<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['specialty_id', 'name', 'slug', 'description', 'completed_at', 'featured', 'published'];

    protected function casts(): array
    {
        return ['completed_at' => 'date', 'featured' => 'boolean', 'published' => 'boolean'];
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class);
    }
}
