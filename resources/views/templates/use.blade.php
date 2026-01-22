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
    <div class="row">

        <!-- Canvas -->
        <div class="col-md-8">
            <canvas id="userCanvas"
                    width="600"
                    height="600"
                    class="border rounded shadow-sm"></canvas>
        </div>

        <!-- Upload panel -->
        <div class="col-md-4">
            <h6 class="fw-semibold mb-3">Upload Photos</h6>

            <div id="photoUploadList"></div>
        </div>

    </div>
</div>




</div>


<script>
 window.templateJson = @json($template->template_json);
</script>


    <script src="{{ asset('js/template/user.js') }}"></script>


</body>
</html>