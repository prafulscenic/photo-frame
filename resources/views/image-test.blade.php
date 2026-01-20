
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wall Image Test</title>
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"    rel="stylesheet">
       <style>
        .frame {
    position: absolute;  /* Position relative to wall */
    top: 30px;          /* Adjust this to move down from top of wall */
    left: 200px;         /* Adjust this to move from left of wall */
    width: 150px;        /* Adjust frame size */
    box-shadow: 0 12px 24px 4px rgba(0, 0, 0, 0.5);
}

.frame2 {
    position: absolute;
    top: 30px;
    left: 200px;
    width: 150px;

    /* REMOVE box-shadow */
    filter: drop-shadow(0 12px 24px rgba(0, 0, 0, 0.5));
}


</style>
</head>
<body>
    @include('header')
   <div class="container py-5 text-center">

<div class="position-relative d-inline-block">
    <img src="{{ asset('wall.jpg') }}" alt="Wall Image" class="img-fluid">
    <img src="{{ asset('baby.png') }}" alt="Frame" class="frame">
</div>
<br>
<div class="position-relative d-inline-block">
    <img src="{{ asset('wall.jpg') }}" alt="Wall Image" class="img-fluid">
    <img src="{{ asset('baby-pic.png') }}" alt="Frame" class="frame2">
</div>

<br>
<div class="position-relative d-inline-block">
    <img src="{{ asset('wall.jpg') }}" alt="Wall Image" class="img-fluid">
    <img src="{{ asset('heart.png') }}" alt="Frame" class="frame2">
</div>


     </div>
</body>
</html>


