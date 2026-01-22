<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use App\Models\FrameOrder;
use App\Models\FrameTexture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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




    public function storeOrder(Request $request)
    {
        dd($request->all());
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'frame_id' => 'required|exists:frames,id',
            'frame_size' => 'required|string',
            'frame_thickness' => 'required|string',
            'uploaded_image' => 'required|string',
            'canvas_json' => 'required|string',
            'final_frame_image' => 'required|string',
        ]);

        $uploadedPath = $this->saveBase64Image(
            $data['uploaded_image'],
            'orders/original'
        );

        $finalPath = $this->saveBase64Image(
            $data['final_frame_image'],
            'orders/final'
        );

        FrameOrder::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'frame_id' => $data['frame_id'],
            'frame_size' => $data['frame_size'],
            'frame_thickness' => $data['frame_thickness'],
            'uploaded_image' => $uploadedPath,
            'final_frame_image' => $finalPath,
            'canvas_json' => $request->canvas_json,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order saved successfully'
        ]);
    }

    // private function saveBase64Image(string $base64, string $folder): string
    // {
    //     [$meta, $content] = explode(',', $base64);

    //     $extension = str_contains($meta, 'jpeg') ? 'jpg'
    //                : (str_contains($meta, 'webp') ? 'webp' : 'png');

    //     $filename = $folder.'/'.uniqid().'.'.$extension;

    //     Storage::disk('public')->put(
    //         $filename,
    //         base64_decode($content)
    //     );

    //     return $filename;
    // }

    private function saveBase64Image($base64, $folder)
{
    $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
    $image = base64_decode($image);

    $fileName = uniqid() . '.png';
    $path = $folder . '/' . $fileName;

    \Storage::disk('public')->put($path, $image);

    return $path;
}


 

    public function orderList(Request $request)
    {
        $orders = FrameOrder::with('frame')->orderBy('created_at', 'desc')->get();

        return view('orders.order_list', compact('orders'));
    }


    /**
     * Show checkout page
     */
    public function indexCheckout(Request $request)
    {
        return view('checkout', [
            'frame_id'        => $request->frame_id,
            'frame_size'      => $request->frame_size,
            'frame_thickness' => $request->frame_thickness,
            'quantity'        => $request->quantity,
            'price'           => $request->price,
        ]);
    }

    /**
     * Save final order
     */
    public function storeCheckout(Request $request)
    {
    //   dd($request->all());
        // ✅ Validate checkout form only
        $request->validate([
            'frame_id'        => 'required|integer',
            'frame_size'      => 'required|string',
            'frame_thickness' => 'required|string',
            'quantity'        => 'required|integer|min:1',
            'price'           => 'required|integer|min:0',

            'name'            => 'required|string|max:255',
            'email'           => 'required|email',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string',

            'uploaded_image_base64' => 'required|string',
           'final_image_base64'    => 'required|string', 
        ]);

            // ✅ Save images
        $uploadedPath = $this->saveBase64Image(
            $request->uploaded_image_base64,
            'orders/original'
        );

        $finalPath = $this->saveBase64Image(
            $request->final_image_base64,
            'orders/final'
        );

        // ✅ Save directly (NO session)
        FrameOrder::create([
            'frame_id'        => $request->frame_id,
            'frame_size'      => $request->frame_size,
            'frame_thickness' => $request->frame_thickness,
            'price'           => $request->price,

            // user details
            'name'            => $request->name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'address'         => $request->address,

            'uploaded_image'    => $uploadedPath,
            'final_frame_image' => $finalPath,

            'status'          => 'pending',
        ]);

        // ✅ Redirect to success / home
        return redirect()->route('frame.order.list')
            ->with('success', 'Order placed successfully!');
    }
}


