<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
     protected $fillable = [
        'name',
        'shape',
        'frame_type',
        'frame_width',
        'frame_height',
        'aspect_ratio',
        'polygon_sides',
        'border_width',
        'border_color',
        'border_radius',
        'thumbnail',
        'frame_texture',
        'frame_texture_id',
        'svg_path',
        'is_active',

    ];
    protected $casts = [
        'is_active' => 'boolean',
        'border_width' => 'integer',
        'border_radius' => 'integer',
    ];
   public function texture()
    {
        return $this->belongsTo(FrameTexture::class, 'frame_texture_id');
    }

    public function orders()
    {
        return $this->hasMany(FrameOrder::class);
    }

}
