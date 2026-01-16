<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photo Frame Editor</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fabric.js -->
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">



</head>
<body>

{{-- <div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h3 class="fw-bold">Create Your Photo Frame</h3>
        <p class="text-muted">Upload an image, add text, and download your custom frame</p>
    </div>

    <!-- Controls Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <div class="row g-3 align-items-end">

                <!-- Upload -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Upload Image</label>
                    <input type="file" id="imageUpload" class="form-control" accept="image/*">
                </div>
    

                <!-- Add Text -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Text Options</label>
                    <div class="d-flex gap-2">
                        <button id="addTextBtn" class="btn btn-primary flex-grow-1">
                            Add Text
                        </button>

                        <input type="color"
                               id="textColorPicker"
                               class="form-control form-control-color"
                               title="Text Color" value="#000000">
                    </div>
                </div>

                <!-- Download -->
                <div class="col-md-4">
                    <button id="downloadBtn" class="btn btn-success w-100">
                        Download PNG
                    </button>
                </div>

            </div>

        </div>
    </div>

        <!-- Canvas Preview -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">

                <h6 class="fw-semibold text-center mb-3">Preview</h6>

                <!-- Center wrapper -->
                <div class="d-flex justify-content-center align-items-center">
                    <canvas id="canvas"
                            width="400"
                            height="400"
                            class="border"
                            style="border:2px solid #333"></canvas>
                           

                </div>

            

            </div>
        </div>



    <!-- Frames Section -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Choose a Frame</h6>
            <div class="row" id="frameList"></div>
        </div>
    </div>

</div> --}}
@include('header')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h3 class="fw-bold">Create Your Photo Frame</h3>
        <p class="text-muted">Upload an image, add text, and download your custom frame</p>
    </div>

    <!-- Main Layout: Left (Preview + Controls) + Right (Frames) -->
    <div class="row g-4" style="height: 80vh;">

        <!-- Left Column: Preview + Controls -->
        <div class="col-md-6 d-flex flex-column">

            <!-- Preview Card -->
            <div class="card shadow-sm mb-4 flex-shrink-0">
                <div class="card-body d-flex flex-column align-items-center">

                    <h6 class="fw-semibold text-center mb-3">Preview</h6>

                    <canvas id="canvas"
                            width="400"
                            height="400"
                            class="border rounded mb-3"
                            style="border:2px solid #333"></canvas>

                </div>
            </div>

            <!-- Controls Card -->
            <div class="card shadow-sm flex-shrink-0">
                <div class="card-body">

                    <div class="row g-3 align-items-end">

                        <!-- Upload -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Upload Image</label>
                            <input type="file" id="imageUpload" class="form-control" accept="image/*">
                        </div>

                        <!-- Add Text -->
                       <div class="col-12">
                            <label class="form-label fw-semibold mb-2">Text Options</label>

                            <div class="d-flex flex-wrap align-items-center gap-2">

                                <!-- Add Text Button -->
                                <button id="addTextBtn"
                                        class="btn btn-primary px-4">
                                    <i class="bi bi-plus-lg me-1"></i> Add Text
                                </button>

                                <!-- Text Color Picker -->
                                <div class="d-flex align-items-center gap-1">
                                    <small class="text-muted">Color</small>
                                    <input type="color"
                                        id="textColorPicker"
                                        class="form-control form-control-color"
                                        title="Text Color"
                                        value="#000000">
                                </div>

                                <!-- Font Family Select -->
                                <div class="flex-grow-1">
                                    <select id="fontFamilySelect" class="form-select">
                                        <option value="">Font Family</option>
                                        <option value="Poppins">Poppins</option>
                                        <option value="Playfair Display">Playfair Display</option>
                                        <option value="Dancing Script">Dancing Script</option>
                                    </select>
                                </div>

                            </div>
                        </div>


                        <!-- Download -->
                        <div class="col-12">
                            <button id="downloadBtn" class="btn btn-success w-100">
                                Download PNG
                            </button>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        <!-- Right Column: Frames Section -->
        <div class="col-md-6 d-flex flex-column" style="height: 80vh;"> <!-- constrain height -->

            <div class="card shadow-sm flex-grow-1 d-flex flex-column" style="height: 100%;">
                <div class="card-body d-flex flex-column p-3" style="height: 100%;">

                    <h6 class="fw-semibold mb-3">Choose a Frame</h6>

                    <!-- Scrollable Frames -->
                    <div id="frameList" class="row g-3" style="overflow-y: auto; flex-grow: 1; height: 0;">
                        <!-- JS will populate frames here -->
                    </div>

                </div>
            </div>

        </div>




    </div>

</div>



{{-- <script src="{{asset('js/new-editor.js')}}"></script> --}}
<script src="{{asset('js/new-editor.js')}}"></script>

</body>
</html>
