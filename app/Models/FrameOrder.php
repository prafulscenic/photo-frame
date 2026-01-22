<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrameOrder extends Model
{
  
    protected $fillable = [
        'name',
        'email',
        'frame_id',
        'frame_size',
        'frame_thickness',
        'uploaded_image',
        'final_frame_image',
        'canvas_json',
        'price',
        'phone',
        'address',
        'status',
    ];

    /**
     * Each order belongs to a frame
     */
    public function frame()
    {
        return $this->belongsTo(Frame::class);
    }
}
