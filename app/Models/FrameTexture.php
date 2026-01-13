<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrameTexture extends Model
{
    protected $fillable = [
        'name',
        'texture_path',
        'material_type',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function frames()
    {
        return $this->hasMany(Frame::class, 'frame_texture_id');
    }

}
