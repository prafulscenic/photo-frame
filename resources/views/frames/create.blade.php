<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Frame</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"    rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
   <style>
    /* container */
    .texture-item {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }

    /* hide default radio */
    .texture-radio {
        display: none;
    }

    /* thumbnail box */
    .texture-thumb {
        width: 50px;
        height: 50px;
        border: 2px solid #ddd;
        border-radius: 6px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        transition: all 0.2s ease;
    }

    /* image */
    .texture-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* hover */
    .texture-item:hover .texture-thumb {
        border-color: #999;
    }

    /* SELECTED STATE */
    .texture-radio:checked + .texture-thumb {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25);
    }

    /* OPTIONAL checkmark */
    .texture-radio:checked + .texture-thumb::after {
        content: "✓";
        position: absolute;
        top: 2px;
        right: 4px;
        font-size: 14px;
        font-weight: bold;
        color: #2563eb;
        background: #fff;
        border-radius: 50%;
    }
    </style>



</head>
<body>

    @include('header')
   <div class="container py-5">
      <h3 class="mb-4">Create Frame</h3>

        @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- <div class="row">
            <div class="col-md-6">
                
                <form method="POST" action="{{ route('admin.frames.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Frame Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Shape</label>
                        <select name="shape" id="shapeSelect" class="form-select" required>
                            <option value="">Select Shape</option>
                            <option value="rectangle">Rectangle</option>
                            <option value="circle">Circle</option>
                            <option value="polygon">Polygon</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="polygonSidesWrapper">
                        <label class="form-label">Polygon Type</label>
                        <select name="polygon_sides" id="polygonSides" class="form-select">
                            <option value="6">Hexagon (6 sides)</option>
                            <option value="8">Octagon (8 sides)</option>
                        </select>
                    </div>


                    <div class="mb-3 d-none" id="aspectRatioWrapper">
                        <label class="form-label">Aspect Ratio</label>
                        <select name="aspect_ratio" id="aspectRatio" class="form-select">
                            <option value="1:1">Square (1:1)</option>
                            <option value="4:3">Landscape (4:3)</option>
                            <option value="3:4">Portrait (3:4)</option>
                            <option value="16:9">Wide (16:9)</option>
                            <option value="9:16">Story (9:16)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Border Width (px)</label>
                        <select name="border_width" class="form-select" required>
                            <option value="3">3 px</option>
                            <option value="5">5 px</option>
                            <option value="6">6 px</option>
                            <option value="10">10 px</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Border Color</label>
                        <input type="color" name="border_color" class="form-control form-control-color" value="#d4af37">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Frame Material / Texture</label>
                        <input type="file"
                            name="frame_texture"
                            class="form-control"
                            accept="image/*"
                            required>

                        <small class="text-muted">
                            Upload material image (wood / metal / marble).  
                            Recommended: seamless texture, JPG or PNG.
                        </small>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Border Radius (px)</label>
                        <input type="number" name="border_radius" class="form-control" value="0" min="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Frame Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        <small class="text-muted">
                            Recommended: square image (300×300)
                        </small>
                    </div>

            
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                        <label class="form-check-label">Active</label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Frame
                    </button>
                </form>
            </div>

            <div class="col-md-6">
                <div class="mt-4">
                    <label class="form-label">Live Frame Preview (300×300)</label>
                    <canvas id="previewCanvas" width="300" height="300"
                            style="border:2px dashed #ccc;"></canvas>
                </div>

            </div>


        </div> --}}

        <div class="row">
            <div class="row">
            <div class="col-md-6">

                <form method="POST" action="{{ route('admin.frames.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Frame Name --}}
                    <div class="mb-3">
                        <label class="form-label">Frame Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    {{-- Shape --}}
                    <div class="mb-3">
                        <label class="form-label">Shape</label>
                        <select name="shape" id="shapeSelect" class="form-select" required>
                            <option value="">Select Shape</option>
                            <option value="rectangle">Rectangle</option>
                            <option value="circle">Circle</option>
                            <option value="polygon">Polygon</option>
                        </select>
                    </div>

                    {{-- Polygon --}}
                    <div class="mb-3 d-none" id="polygonSidesWrapper">
                        <label class="form-label">Polygon Type</label>
                        <select name="polygon_sides" id="polygonSides" class="form-select">
                            <option value="6">Hexagon (6)</option>
                            <option value="8">Octagon (8)</option>
                        </select>
                    </div>

                    {{-- Aspect Ratio --}}
                    <div class="mb-3 d-none" id="aspectRatioWrapper">
                        <label class="form-label">Aspect Ratio</label>
                        <select name="aspect_ratio" id="aspectRatio" class="form-select">
                            <option value="1:1">Square (1:1)</option>
                            <option value="4:3">Landscape (4:3)</option>
                            <option value="3:4">Portrait (3:4)</option>
                            <option value="16:9">Wide (16:9)</option>
                            <option value="9:16">Story (9:16)</option>
                        </select>
                    </div>

                    {{-- Border Width --}}
                    <div class="mb-3">
                        <label class="form-label">Border Width</label>
                        <select name="border_width" class="form-select" required>
                            <option value="0">0 px</option>
                            <option value="3">3 px</option>
                            <option value="5">5 px</option>
                            <option value="6">6 px</option>
                            <option value="10">10 px</option>
                        </select>
                    </div>

                    {{-- Border Radius --}}
                    <div class="mb-3">
                        <label class="form-label">Border Radius</label>
                        <input type="number" name="border_radius" class="form-control" value="0" min="0">
                    </div>

                    {{-- FRAME STYLE --}}
                    <div class="mb-3">
                        <label class="form-label">Frame Style</label>
                        <select name="frame_style_type" id="frameStyleType" class="form-select" required>
                            <option value="">Select Style</option>
                            <option value="color">Solid Color</option>
                            <option value="texture">Texture / Material</option>
                        </select>
                    </div>

                    {{-- COLOR OPTION --}}
                    <div class="mb-3 d-none" id="borderColorWrapper">
                        <label class="form-label">Border Color</label>
                        <input type="color"
                            name="border_color"
                            id="borderColorInput"
                            class="form-control form-control-color"
                            value="#d4af37">
                    </div>

                    {{-- TEXTURE SELECTION --}}
                <div class="mb-3 d-none" id="textureWrapper">
                        <label class="form-label">Select Texture</label>

                        <div class="d-flex flex-wrap gap-3">

                            @foreach($textures as $texture)
                                <label class="texture-item">
                                    <input type="radio"
                                        name="frame_texture_id"
                                        value="{{ $texture->id }}"
                                        class="texture-radio">

                                    <span class="texture-thumb">
                                        <img src="{{ asset('storage/' . $texture->texture_path) }}"
                                            alt="Texture"
                                            width="50"
                                            height="50">
                                    </span>
                                </label>
                            @endforeach

                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Frame Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        <small class="text-muted">
                            Recommended: square image (300×300)
                        </small>
                    </div>


                    {{-- ACTIVE --}}
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                        <label class="form-check-label">Active</label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Frame
                    </button>
                </form>
            </div>

            {{-- PREVIEW --}}
            <div class="col-md-6">
                <label class="form-label">Live Frame Preview</label>
                <canvas id="previewCanvas" width="300" height="300"
                        style="border:2px dashed #ccc"></canvas>
            </div>
        </div>

        </div>

    </div> 


<script>
document.getElementById('frameStyleType').addEventListener('change', function () {
    document.getElementById('borderColorWrapper')
        .classList.toggle('d-none', this.value !== 'color');

    document.getElementById('textureWrapper')
        .classList.toggle('d-none', this.value !== 'texture');
});
</script>


<script src="{{asset('js/new-create.js')}}"></script>
</body>
</html>