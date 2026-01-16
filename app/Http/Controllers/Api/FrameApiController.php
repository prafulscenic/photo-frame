<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Frame;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FrameApiController extends Controller
{
    public function index(): JsonResponse
    {
        $frames = Frame::with([
                'texture:id,texture_path'
            ])
            ->where('is_active', true)
            ->orderBy('id', 'desc')
            ->get([
                'id',
                'name',
                'shape',
                'aspect_ratio',
                'frame_width',
                'frame_height',
                'polygon_sides',
                'border_width',
                'border_color',
                'thumbnail',
                'frame_texture',
                'frame_texture_id',
                'border_radius',
            ]);

        return response()->json([
            'status' => true,
            'data' => $frames
        ]);
    }

}
