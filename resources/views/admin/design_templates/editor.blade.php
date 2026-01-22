<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editor </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"    rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>

</head>
<body>

    @include('header')

    <div class="containefr">
       <div class="container-fluid px-4 py-3 bg-light min-vh-100">

    {{-- Top Bar --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.design-templates.index') }}"
               class="btn btn-light shadow-sm rounded-circle"
               data-bs-toggle="tooltip"
               title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>

            <div>
                <div class="fw-semibold">Design Template</div>
                <div class="text-muted small">
                    {{ $template->name }} • {{ ucfirst($template->type) }}
                </div>
            </div>
        </div>

        <button id="saveTemplateBtn"
                class="btn btn-dark d-flex align-items-center gap-2 px-4 shadow-sm"
                data-bs-toggle="tooltip"
                title="Save Layout">
            <i class="bi bi-cloud-check"></i>
            <span class="d-none d-md-inline">Save</span>
        </button>
    </div>

    <div class="d-flex gap-4">

        {{-- LEFT FLOATING TOOL RAIL --}}
        <div class="d-flex flex-column align-items-center gap-2
                    bg-white rounded-4 shadow-sm p-2"
             style="width:56px">

            <button id="addRectBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Rectangle">
                <i class="bi bi-square"></i>
            </button>

            <button id="addCircleBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Circle">
                <i class="bi bi-circle"></i>
            </button>

            <button id="addPentagonBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Pentagon">
                <i class="bi bi-pentagon"></i>
            </button>

            <button id="addHexagonalBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Hexagon">
                <i class="bi bi-hexagon"></i>
            </button>

            <button id="addOcatagonalBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Octagon">
                <i class="bi bi-octagon"></i>
            </button>

            <button id="addTriangleBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Triangle">
                <i class="bi bi-triangle"></i>
            </button>

            <div class="border-top w-100 my-2"></div>

            <label class="btn btn-light rounded-3 mb-0"
                   data-bs-toggle="tooltip"
                   title="Upload SVG">
                <i class="bi bi-upload"></i>
                <input type="file"
                       id="svgUploadInput"
                       accept=".svg"
                       hidden>
            </label>

            <div class="border-top w-100 my-2"></div>

            <button id="markPhotoSlotBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Mark Photo Slot">
                <i class="bi bi-image"></i>
            </button>

            <button id="duplicateBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Duplicate">
                <i class="bi bi-files"></i>
            </button>

            <div class="border-top w-100 my-2"></div>

            <button id="deleteObjBtn"
                    class="btn btn-light rounded-3 text-danger"
                    data-bs-toggle="tooltip"
                    title="Delete">
                <i class="bi bi-trash"></i>
            </button>

            <button id="bringFrontBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Bring Forward">
                <i class="bi bi-layer-forward"></i>
            </button>

            <button id="sendBackBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Send Backward">
                <i class="bi bi-layer-backward"></i>
            </button>

            {{-- //currently not working --}}
             {{-- <button id="lockBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Bring Forward">
                lock
            </button>

            <button id="unlockBtn"
                    class="btn btn-light rounded-3"
                    data-bs-toggle="tooltip"
                    title="Send Backward">
                unlock
            </button> --}}
        </div>

        {{-- CANVAS STAGE --}}
        <div class="flex-grow-1 d-flex justify-content-center">
            <div class="bg-white rounded-4 shadow-sm p-4">

                <canvas id="templateCanvas"
                        width="{{ $template->canvas_width }}"
                        height="{{ $template->canvas_height }}"
                        class="border rounded-3"
                        style="border-style:dashed;">
                </canvas>

            </div>
        </div>

    </div>
</div>


    </div>





<script>
    window.saveTemplateLayoutUrl =
        "{{ route('admin.design-templates.saveLayout', $template->id) }}";
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.querySelectorAll('[data-bs-toggle="tooltip"]')
    .forEach(el => new bootstrap.Tooltip(el));
</script>


<script>
    window.templateData = {
        id: {{ $template->id }},
        json: @json($template->template_json)
    };
</script>

<script src="{{ asset('js/template/create.js') }}"></script>

</body>
</html>

