
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
    <div class="card shadow-sm my-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Frame Textures List</h5>

            <div>
                <a href="{{ route('textures.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Add New
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Preview</th>
                        <th>Name</th>
                        <th>Material</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($frameTextures as $texture)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            {{-- Small Image Preview --}}
                            <td>
                                @if($texture->texture_path)
                                    <img src="{{ asset('storage/' . $texture->texture_path) }}"
                                        alt="{{ $texture->name ?? 'Texture' }}"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>

                            <td>{{ $texture->name ?? '-' }}</td>
                            <td>{{ ucfirst($texture->material_type) }}</td>
                          
                            <td>
                                @if($texture->is_active)
                                    <span class="badge bg-primary">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td>
                                <a href="" 
                                class="btn btn-sm btn-warning">Edit</a>

                                <form action="" 
                                    method="POST" class="d-inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this texture?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No textures found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


</div>
</body>
</html>


