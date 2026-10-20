<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    protected $fillable = ['project_id', 'path', 'alt'];

    protected function casts(): array
    {
        return [];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
