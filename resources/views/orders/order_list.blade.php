<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"    rel="stylesheet">
</head>

<body>

@include('header')

    <div class="container py-5">
        <h3 class="mb-4">Order List</h3>

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
                    <th>Frame</th>
                    <th>Image</th>
                    <th>Frame image</th>
                    <th>Size</th>
                    <th>Thickness</th>
                    <th>Price (₹) </th>
                    {{-- <th>Action</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->frame->name}}</td>
                        <td>
                            @if($order->uploaded_image)
                                <img src="{{ asset('storage/' . $order->uploaded_image) }}" 
                                    style="max-width: 100px; height: auto;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                             @if($order->final_frame_image)
                                <img src="{{ asset('storage/' . $order->final_frame_image) }}"
                                    style="max-width: 100px; height: auto;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>{{ $order->frame_size }}</td>
                        <td>{{ $order->frame_thickness }}</td>
                        <td>{{ $order->price }}</td>
                        {{-- <td>
                            <a href="" class="btn btn-primary">Edit</a>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
    </div>
</body>

</html>