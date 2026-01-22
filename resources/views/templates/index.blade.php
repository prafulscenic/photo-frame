<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editor </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"    rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>

</head>
<body>

    @include('header')
<div class="container">
<div class="container py-4">
    <h3 class="mb-4">Choose a Template</h3>

    <div class="row g-4">
        @foreach($templates as $template)
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    {{-- <img src="{{ asset('storage/'.$template->thumbnail) }}"
                         class="card-img-top"
                         alt="{{ $template->name }}"> --}}

                    <div class="card-body text-center">
                        <h6 class="fw-semibold">{{ $template->name }}</h6>

                        <a href="{{ route('templates.use', $template->id) }}"
                           class="btn btn-primary btn-sm mt-2">
                            Use Template
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>
<script src="{{ asset('js/template/user.js') }}"></script>
</body>
</html>
