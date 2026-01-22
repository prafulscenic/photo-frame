<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignTemplate extends Model
{
  protected $fillable = [
        'name',
        'type',
        'category',
        'canvas_width',
        'canvas_height',
        'template_json',
        'preview_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
