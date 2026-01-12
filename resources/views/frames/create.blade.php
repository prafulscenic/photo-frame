<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Frame</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>

</head>
<body>
   <div class="container">
    <h3 class="mb-4">Create Frame</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



    <div class="row">
    <div class="col-md-6">
        
    <form method="POST" action="{{ route('admin.frames.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Frame Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Shape</label>
            <select name="shape" id="shapeSelect" class="form-select" required>
                <option value="">Select Shape</option>
                <option value="rectangle">Rectangle</option>
                <option value="circle">Circle</option>
                <option value="polygon">Polygon</option>
            </select>
        </div>

        <div class="mb-3 d-none" id="polygonSidesWrapper">
            <label class="form-label">Polygon Type</label>
            <select name="polygon_sides" id="polygonSides" class="form-select">
                <option value="6">Hexagon (6 sides)</option>
                <option value="8">Octagon (8 sides)</option>
            </select>
        </div>


        <div class="mb-3 d-none" id="aspectRatioWrapper">
            <label class="form-label">Aspect Ratio</label>
            <select name="aspect_ratio" id="aspectRatio" class="form-select">
                <option value="1:1">Square (1:1)</option>
                <option value="4:3">Landscape (4:3)</option>
                <option value="3:4">Portrait (3:4)</option>
                <option value="16:9">Wide (16:9)</option>
                <option value="9:16">Story (9:16)</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Border Width (px)</label>
            <select name="border_width" class="form-select" required>
                <option value="3">3 px</option>
                <option value="5">5 px</option>
                <option value="6">6 px</option>
                <option value="10">10 px</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Border Color</label>
            <input type="color" name="border_color" class="form-control form-control-color" value="#d4af37">
        </div>

        <div class="mb-3">
            <label class="form-label">Border Radius (px)</label>
            <input type="number" name="border_radius" class="form-control" value="0" min="0">
        </div>

        <div class="mb-3">
            <label class="form-label">Frame Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control" accept="image/*">
            <small class="text-muted">
                Recommended: square image (300×300)
            </small>
        </div>


        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
            <label class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">
            Save Frame
        </button>
    </form>
    </div>

    <div class="col-md-6">
        <div class="mt-4">
            <label class="form-label">Live Frame Preview (300×300)</label>
            <canvas id="previewCanvas" width="300" height="300"
                    style="border:2px dashed #ccc;"></canvas>
        </div>

    </div>
</div>

</div> 



<script>
/* ===============================
   ADMIN FRAME PREVIEW
================================ */
const previewCanvas = new fabric.Canvas('previewCanvas', {
    selection: false
});

const PREVIEW_CENTER = 150;
let previewFrame = null;

/* ===============================
   INPUT REFERENCES
================================ */
const shapeSelect = document.getElementById('shapeSelect');
const polygonWrapper = document.getElementById('polygonSidesWrapper');
const polygonSidesInput = document.getElementById('polygonSides');
const aspectRatioWrapper = document.getElementById('aspectRatioWrapper');
const aspectRatioSelect  = document.getElementById('aspectRatio');


/* ===============================
   SHAPE CHANGE HANDLER
================================ */
shapeSelect.addEventListener('change', () => {

    polygonWrapper.classList.toggle(
        'd-none',
        shapeSelect.value !== 'polygon'
    );

    aspectRatioWrapper.classList.toggle(
        'd-none',
        shapeSelect.value !== 'rectangle'
    );

    renderPreview();
});




['border_width', 'border_color', 'border_radius'].forEach(name => {
    document.querySelector(`[name="${name}"]`)
        .addEventListener('input', renderPreview);
});

polygonSidesInput.addEventListener('change', renderPreview);

/* ===============================
   POLYGON POINT GENERATOR
================================ */
function generatePolygonPoints(cx, cy, radius, sides) {
    const points = [];
    const step = (Math.PI * 2) / sides;

    for (let i = 0; i < sides; i++) {
        const angle = step * i - Math.PI / 2;
        points.push({
            x: cx + radius * Math.cos(angle),
            y: cy + radius * Math.sin(angle)
        });
    }
    return points;
}

    /* ===============================
    RENDER PREVIEW
    ================================ */
    function renderPreview() {
        previewCanvas.clear();
        previewFrame = null;

        const shape = shapeSelect.value;
        if (!shape) return;

        const bw = parseInt(document.querySelector('[name="border_width"]').value || 5);
        const color = document.querySelector('[name="border_color"]').value || '#000000';
        const radiusInput = parseInt(document.querySelector('[name="border_radius"]').value || 0);

        /* ---------- RECTANGLE ---------- */
        if (shape === 'rectangle') {

        const ratioValue = document.getElementById('aspectRatio').value || '1:1';
        const { w: rw, h: rh } = parseAspectRatio(ratioValue);

        const maxSize = 260;

        const scale = Math.min(
            maxSize / rw,
            maxSize / rh
        );

        const w = rw * scale;
        const h = rh * scale;

        // border_radius as %
        const maxRadius = Math.min(w, h) / 2;
        const radius = Math.min(
            (radiusInput / 100) * Math.min(w, h),
            maxRadius
        );
        
        previewFrame = new fabric.Rect({
            left: PREVIEW_CENTER,
            top: PREVIEW_CENTER,
            originX: 'center',
            originY: 'center',
            width: w,
            height: h,
            rx: radius,
            ry: radius,
            fill: 'transparent',
            stroke: color,
            strokeWidth: bw,
            selectable: false
        });
    }




    /* ---------- CIRCLE ---------- */
    if (shape === 'circle') {
        previewFrame = new fabric.Circle({
            left: PREVIEW_CENTER,
            top: PREVIEW_CENTER,
            originX: 'center',
            originY: 'center',
            radius: 110,
            fill: 'transparent',
            stroke: color,
            strokeWidth: bw,
            selectable: false
        });
    }

    /* ---------- POLYGON ---------- */
    if (shape === 'polygon') {
        const sides = parseInt(polygonSidesInput.value || 6);
        const radius = 110;

        const points = generatePolygonPoints(
            PREVIEW_CENTER,
            PREVIEW_CENTER,
            radius,
            sides
        );

        previewFrame = new fabric.Polygon(points, {
            fill: 'transparent',
            stroke: color,
            strokeWidth: bw,
            selectable: false
        });
    }

    if (previewFrame) {
        previewCanvas.add(previewFrame);
        previewCanvas.renderAll();
    }
}

function parseAspectRatio(ratio) {
    const [w, h] = ratio.split(':').map(Number);
    return { w, h };
}

aspectRatioSelect.addEventListener('change', renderPreview);


</script>


</body>
</html>