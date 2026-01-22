<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frame Template List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"    rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>

</head>
<body>

    @include('header')
    <div class="container">

        <div class="container-fluid py-4">

            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Design Templates</h4>

                <a href="{{ route('admin.design-templates.create') }}"
                class="btn btn-primary btn-sm">
                    + Create Template
                </a>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Table --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">

                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Canvas Size</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td>{{ $template->id }}</td>

                                <td class="fw-semibold">
                                    {{ $template->name }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($template->type) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $template->category ?? '-' }}
                                </td>

                                <td>
                                    {{ $template->canvas_width }} × {{ $template->canvas_height }}
                                </td>

                                <td>
                                    @if($template->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $template->created_at->format('d M Y') }}
                                </td>
                               <td>
                                    <a href="{{ route('admin.design-templates.editor', $template->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                        Design Layout
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No templates found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

    </div>


</body>
</html>