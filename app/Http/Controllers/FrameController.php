<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use Illuminate\Http\Request;

class FrameController extends Controller
{
    public function create()
    {
        return view('frames.create');
    }

    /**
     * Store frame
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'shape'         => 'required|string|max:100',
            'polygon_sides' => 'nullable',
            'frame_width'   => 'nullable|integer|min:1',
            'frame_height'  => 'nullable|integer|min:1',
            'aspect_ratio'  => 'nullable|string|max:100',
            'border_width'  => 'required|integer|min:1',
            'border_color'  => 'required|string|max:20',
            'border_radius' => 'nullable|integer|min:0',
             'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'     => 'nullable|boolean',
        ]);

          $thumbnailPath = null;

            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')
                    ->store('frames', 'public');
            }


        Frame::create([
            'name'          => $request->name,
            'shape'         => $request->shape,
            'polygon_sides' => $request->polygon_sides,
            'aspect_ratio'  => $request->aspect_ratio,
            'border_width'  => $request->border_width,
            'border_color'  => $request->border_color,
            'border_radius' => $request->border_radius ?? 0,
            'thumbnail'     => $thumbnailPath,
            'is_active'     => $request->has('is_active'),
        ]);

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
}
