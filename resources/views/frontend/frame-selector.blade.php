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


<style>
    .frame-thumb-wrapper {
    width: 100%;
    height: 300px;       /* SAME HEIGHT FOR ALL */
    display: flex;
    align-items: center;
    justify-content: center;
}

.frame-thumb {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;  /* no crop */
}

</style>
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
{{-- <div class="container py-5">

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

</div> --}}
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-4">
        <h3 class="fw-bold">Choose Your Frame</h3>
        <p class="text-muted">Select a frame to start customizing</p>
    </div>

    <!-- Frames Grid -->
    <div class="row g-3" id="frameList">
        <!-- JS will populate frames here -->
    </div>

</div>
<div class="modal fade"
     id="frameEditorModal"
     tabindex="-1"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     data-bs-focus="false">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- Modal Header -->
            <div class="modal-header px-4 py-3 border-bottom bg-white">
                <div>
                    <h5 class="modal-title fw-bold mb-0">
                        Customize Your Frame
                    </h5>
                    <small class="text-muted">
                        Upload image, add text, and export
                    </small>
                </div>

                <button type="button"
                        class="btn-close ms-auto"
                        data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-0">

                <div class="row g-0">

                    <!-- LEFT: Preview -->
                    <div class="col-md-7 bg-light">

                        <div class="h-100 d-flex flex-column align-items-center justify-content-center p-4">

                            <span class="badge bg-secondary-subtle text-secondary mb-3">
                                Live Preview
                            </span>

                            <div class="bg-white p-4 rounded-4 shadow-sm border">

                                <canvas id="canvas"
                                        width="400"
                                        height="400"
                                        class="border rounded-3"
                                        style="border:2px solid #333">
                                </canvas>

                            </div>

                        </div>

                    </div>

                    <!-- RIGHT: Controls -->
                    <div class="col-md-5">

                        <div class="h-100 d-flex flex-column p-4">

                            <!-- Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                                    Image
                                </label>

                                <input type="file"
                                       id="imageUpload"
                                       class="form-control"
                                       accept="image/*">
                            </div>

                            <!-- Text Options -->
                            <div class="mb-4">

                                <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                                    Text Customization
                                </label>

                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-body d-grid gap-3">

                                        <button id="addTextBtn"
                                                class="btn btn-primary w-100">
                                            Add Text
                                        </button>

                                        <div class="d-flex gap-2">

                                            <input type="color"
                                                   id="textColorPicker"
                                                   class="form-control form-control-color">

                                            <select id="fontFamilySelect"
                                                    class="form-select">
                                                <option value="">Font Family</option>
                                                <option value="Poppins">Poppins</option>
                                                <option value="Playfair Display">
                                                    Playfair Display
                                                </option>
                                                <option value="Dancing Script">
                                                    Dancing Script
                                                </option>
                                            </select>

                                        </div>

                                    </div>
                                </div>

                            </div>

                            <!-- Spacer -->
                            <div class="flex-grow-1"></div>

                            <!-- Download -->
                            <div class="pt-3 border-top">

                                <button id="downloadBtn"
                                        class="btn btn-success btn-lg w-100">
                                    Download PNG
                                </button>

                                <small class="text-muted d-block text-center mt-2">
                                    High-quality export
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('js/new-editor.js')}}"></script>


</body>
</html>
