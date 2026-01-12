<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frame List</title>
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


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Shape</th>
                <th>Border Width</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($frames as $frame)
            <tr>
                <td>{{ $frame->id }}</td>
                <td>{{ $frame->name }}</td>
                <td>{{ $frame->shape }}</td>
                <td>{{ $frame->border_width }} px</td>
                <td>
                    <a href="{{ route('admin.frames.edit', $frame->id) }}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
</div> 
</body>
</html>