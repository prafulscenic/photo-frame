<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Textures List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
   <div class="container">
    <h3 class="mb-4">Textures List</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    <form action="{{ route('textures.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="card shadow-sm p-4">

    @csrf

    <h5 class="mb-4">Add Frame Texture</h5>

    {{-- Texture Name (optional) --}}
    <div class="mb-3">
        <label class="form-label">Texture Name</label>
        <input type="text"
               name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name') }}"
               placeholder="Optional texture name">

        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Texture Image --}}
    <div class="mb-3">
        <label class="form-label">Texture Image <span class="text-danger">*</span></label>
        <input type="file"
               name="frame_texture"
               class="form-control @error('frame_texture') is-invalid @enderror"
               accept="image/*">

        @error('frame_texture')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Material Type --}}
    <div class="mb-3">
        <label class="form-label">Material Type <span class="text-danger">*</span></label>
        <select name="material_type"
                class="form-select @error('material_type') is-invalid @enderror">
            <option value="">Select material</option>
            <option value="wood"  {{ old('material_type') === 'wood' ? 'selected' : '' }}>Wood</option>
            <option value="metal" {{ old('material_type') === 'metal' ? 'selected' : '' }}>Metal</option>
            <option value="glass" {{ old('material_type') === 'glass' ? 'selected' : '' }}>Glass</option>
        </select>

        @error('material_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Active Checkbox --}}
    <div class="form-check mb-3">
        <input class="form-check-input"
               type="checkbox"
               name="is_active"
               id="is_active"
               value="1"
               {{ old('is_active', true) ? 'checked' : '' }}>

        <label class="form-check-label" for="is_active">
            Active
        </label>
    </div>

    {{-- Default Checkbox (optional) --}}
    {{-- <div class="form-check mb-4">
        <input class="form-check-input"
               type="checkbox"
               name="is_default"
               id="is_default"
               value="1"
               {{ old('is_default') ? 'checked' : '' }}>

        <label class="form-check-label" for="is_default">
            Set as Default Texture
        </label>
    </div> --}}

    {{-- Submit --}}
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            Save Texture
        </button>
    </div>

</form>

</div> 
</body>
</html>