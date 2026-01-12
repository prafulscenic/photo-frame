<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Frame</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
   <div class="container">
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


    <form method="POST" action="{{ route('admin.frames.update', $frame->id) }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Frame Name</label>
            <input type="text" name="name" class="form-control" value="{{ $frame->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Shape</label>
            <select name="shape" class="form-select" required>
                <option value="">Select Shape</option>
on>

                {{-- Basic shapes --}}
                <option value="rectangle" {{ $frame->shape == 'rectangle' ? 'selected' : '' }}>Rectangle</option>
                <option value="circle" {{ $frame->shape == 'circle' ? 'selected' : '' }}>Circle</option>

                {{-- Future shapes (already supported by DB) --}}
                <option value="rounded_rectangle" {{ $frame->shape == 'rounded_rectangle' ? 'selected' : '' }}>Rounded Rectangle</option>
                <option value="oval" {{ $frame->shape == 'oval' ? 'selected' : '' }}>Oval</option>
                <option value="polygon" {{ $frame->shape == 'polygon' ? 'selected' : '' }}>Polygon</option>
                <option value="custom_svg" {{ $frame->shape == 'custom_svg' ? 'selected' : '' }}>Custom SVG</option>
            </select>
        </div>


        <div class="mb-3">
            <label class="form-label">Border Width (px)</label>
            <select name="border_width" class="form-select" required>
                <option value="3" {{ $frame->border_width == 3 ? 'selected' : '' }}>3 px</option>
                <option value="5" {{ $frame->border_width == 5 ? 'selected' : '' }}>5 px</option>
                <option value="6" {{ $frame->border_width == 6 ? 'selected' : '' }}>6 px</option>
                <option value="10" {{ $frame->border_width == 10 ? 'selected' : '' }}>10 px</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Border Color</label>
            <input type="color" name="border_color" class="form-control form-control-color" value="{{ $frame->border_color }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Border Radius (px)</label>
            <input type="number" name="border_radius" class="form-control" value="{{ $frame->border_radius }}" min="0">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $frame->is_active ? 'checked' : '' }}>
            <label class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">
            Save Frame
        </button>
    </form>
</div> 
</body>
</html>