<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use App\Models\FrameTexture;
use Illuminate\Http\Request;

class FrameController extends Controller
{
 

    public function create()
    {
        // Get all textures with only id and texture_path
        $textures = FrameTexture::select('id', 'texture_path')->get();

        return view('frames.create', compact('textures'));
    }

    /**
     * Store frame
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name'          => 'required|string|max:255',
    //         'shape'         => 'required|string|max:100',
    //         'polygon_sides' => 'nullable',
    //         'frame_width'   => 'nullable|integer|min:1',
    //         'frame_height'  => 'nullable|integer|min:1',
    //         'aspect_ratio'  => 'nullable|string|max:100',
    //         'border_width'  => 'required|integer|min:1',
    //         // 'border_color'  => 'required|string|max:20',
    //         'border_radius' => 'nullable|integer|min:0',
    //          'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    //         'is_active'     => 'nullable|boolean',
    //        'frame_texture' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',

    //     ]);

    //       $thumbnailPath = null;

    //         if ($request->hasFile('thumbnail')) {
    //             $thumbnailPath = $request->file('thumbnail')
    //                 ->store('frames', 'public');
    //         }

    //     $frameTexturePath = null;

    //     if ($request->hasFile('frame_texture')) {
    //         $frameTexturePath = $request->file('frame_texture')
    //             ->store('frame-textures', 'public');
    //     }



    //     Frame::create([
    //         'name'          => $request->name,
    //         'shape'         => $request->shape,
    //         'polygon_sides' => $request->polygon_sides,
    //         'aspect_ratio'  => $request->aspect_ratio,
    //         'border_width'  => $request->border_width,
    //         // 'border_color'  => $request->border_color,
    //         'border_radius' => $request->border_radius ?? 0,
    //         'thumbnail'     => $thumbnailPath,
    //         'is_active'     => $request->has('is_active'),
    //         'frame_texture' => $frameTexturePath,

    //         'photo_slots'   => $request->photo_slots,
    //     ]);

    //     return redirect()
    //         ->route('admin.frames.create')
    //         ->with('success', 'Frame created successfully.');
    // }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name'              => 'required|string|max:255',
            'shape'             => 'nullable|in:rectangle,circle,polygon',

            'frame_type'        => 'nullable|string|max:100',
            'svg_path'          => 'nullable|string|max:255',

            'polygon_sides'     => 'nullable|integer|min:3',
            'aspect_ratio'      => 'nullable|string|max:10',

            'border_width'      => 'required|integer',
            'border_radius'     => 'nullable|integer|min:0',

            // STYLE TYPE
            'frame_style_type'  => 'nullable|in:color,texture',

            // COLOR MODE
            'border_color'      => 'required_if:frame_style_type,color|nullable|string|max:20',

            // TEXTURE MODE
            'frame_texture_id'  => 'required_if:frame_style_type,texture|nullable|exists:frame_textures,id',

            'thumbnail'     => 'nullable|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'is_active'         => 'nullable|boolean',
        ]);

        /* ----------------------------
        Thumbnail upload
        ---------------------------- */
            $thumbnailPath = null;

            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')
                    ->store('frames', 'public');
            }

            if ($request->file('svg_file')) {
                $svg_path = $request->file('svg_file')->store('frames/svg', 'public');
            }

        /* ----------------------------
        Prepare data
        ---------------------------- */
        $data = [
            'name'              => $request->name,
            'shape'             => $request->shape,
            'polygon_sides'     => $request->polygon_sides,
            'aspect_ratio'      => $request->aspect_ratio,

            'border_width'      => $request->border_width,
            'border_radius'     => $request->border_radius ?? 0,

            'frame_type'        => $request->frame_type ?? 'geometry',
            'svg_path'          => $svg_path ?? null,
             'thumbnail'     => $thumbnailPath,
            'is_active'         => $request->boolean('is_active'),
        ];

        /* ----------------------------
        Apply style mode
        ---------------------------- */
        if ($request->frame_style_type === 'color') {
            $data['border_color'] = $request->border_color;
            $data['frame_texture_id'] = null;
        }

        if ($request->frame_style_type === 'texture') {
            $data['frame_texture_id'] = $request->frame_texture_id;
            $data['border_color'] = null;
        }

        Frame::create($data);

        return redirect()
            ->route('admin.frames.create')
            ->with('success', 'Frame created successfully.');
    }


    public function list()
    {
        $frames = Frame::all();
        return view('frames.frame-list', compact('frames'));
    }

    public function edit($id)
    {
        $frame = Frame::findOrFail($id);
        return view('frames.edit', compact('frame'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'shape'         => 'required|string|max:100',
            'border_width'  => 'required|integer|min:1',
            'border_color'  => 'required|string|max:20',
            'border_radius' => 'nullable|integer|min:0',
            'is_active'     => 'nullable|boolean',
        ]);

        $frame = Frame::findOrFail($id);
        $frame->update([
            'name'          => $request->name,
            'shape'         => $request->shape,
            'border_width'  => $request->border_width,
            'border_color'  => $request->border_color,
            'border_radius' => $request->border_radius ?? 0,
            'is_active'     => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.frames.edit', $frame->id)
            ->with('success', 'Frame updated successfully.');
    }

    // -----------------------------Textures Management-----------------------------


    public function createTextures()
    {
        return view('textures.textures_form');
    }
    public function texturesList()
    {
        $frameTextures = FrameTexture::all();
        return view('textures.textures_list', compact('frameTextures'));
    }
    public function storeTextures(Request $request)
    {
        $request->validate([
            'name'          => 'nullable|string|max:255',
            'material_type' => 'required|string|in:wood,metal,glass',
            'frame_texture' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:4096',
            'is_default'    => 'nullable|boolean',
            'is_active'     => 'nullable|boolean',
        ]);

        $frameTexturePath = null;

        if ($request->hasFile('frame_texture')) {
            $frameTexturePath = $request->file('frame_texture')
                ->store('frame-textures', 'public');
        }

       FrameTexture::create([
            'name'          => $request->name,
            'texture_path'  => $frameTexturePath,
            'material_type' => $request->material_type,
            'is_default'    => $request->has('is_default'),
            'is_active'     => $request->has('is_active'),
        ]);

        return redirect()
            ->route('textures.list')
            ->with('success', 'Frame texture added successfully.');
    }


}
