<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photo Frame Editor</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fabric.js -->
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
</head>
<body>

<div class="container py-5">

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
                               title="Text Color">
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

</div>

<script>
/* ===============================
   CANVAS SETUP
================================ */
const canvas = new fabric.Canvas('canvas', {
    preserveObjectStacking: true
});

const CENTER = 200;

let frameOuter = null;
let frameInner = null;
let currentImage = null;
let currentFrameData = null;
let currentText = null;


/* ===============================
   LOAD FRAMES (THUMBNAILS)
================================ */
fetch('/api/frames')
  .then(res => res.json())
  .then(result => {
    const container = document.getElementById('frameList');
    container.innerHTML = '';

    result.data.forEach(frame => {
      const col = document.createElement('div');
      col.className = 'col-md-3 mb-3';

      col.innerHTML = `
        <div class="card frame-card">
          <img src="/storage/${frame.thumbnail}" class="card-img-top">
          <div class="card-body text-center">
            <strong>${frame.name}</strong>
          </div>
        </div>
      `;

      col.onclick = () => {
        currentFrameData = frame;
        renderFrame(frame);

        if (currentImage) {
          fitImageToFrame(currentImage);
          applyClip();
        }
      };

      container.appendChild(col);
    });
  });





  
/* ===============================
   RENDER FRAME (SAFE)
================================ */
function renderFrame(frame) {

    // ✅ REMOVE ONLY FRAME OBJECTS
    if (frameOuter) canvas.remove(frameOuter);
    if (frameInner) canvas.remove(frameInner);

    frameOuter = null;
    frameInner = null;

    const bw = frame.border_width;
    const color = frame.border_color;

    /* ---------- RECTANGLE ---------- */
  if (frame.shape === 'rectangle') {

    const ratioValue = frame.aspect_ratio || '1:1';
    const { w: rw, h: rh } = parseAspectRatio(ratioValue);

    const maxSize = 300;

    const scale = Math.min(
        maxSize / rw,
        maxSize / rh
    );

    const w = rw * scale;
    const h = rh * scale;

    const radius = Math.min(
        frame.border_radius || 0,
        Math.min(w, h) / 2
    );

    frameOuter = new fabric.Rect({
        left: CENTER,
        top: CENTER,
        originX: 'center',
        originY: 'center',
        width: w,
        height: h,
        rx: radius,
        ry: radius,
        fill: 'transparent',
        stroke: color,
        strokeWidth: bw,
        selectable: false,
        evented: false
    });

    frameInner = new fabric.Rect({
        left: CENTER,
        top: CENTER,
        originX: 'center',
        originY: 'center',
        width: w - (bw * 2),
        height: h - (bw * 2),
        rx: Math.max(radius - bw, 0),
        ry: Math.max(radius - bw, 0),
        absolutePositioned: true
    });

        
}


    /* ---------- CIRCLE ---------- */
    if (frame.shape === 'circle') {
        const radius = 150;

        frameOuter = new fabric.Circle({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            radius: radius,
            fill: 'transparent',
            stroke: color,
            strokeWidth: bw,
            selectable: false,
            evented: false
        });

        frameInner = new fabric.Circle({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            radius: radius - bw,
            absolutePositioned: true
        });
    }


    /* ---------- POLYGON ---------- */
    if (frame.shape === 'polygon') {
        const sides = parseInt(frame.polygon_sides || 6);
        const radius = 150;
        const bw = frame.border_width;

        const outerPoints = generatePolygonPoints(radius, sides);

        frameOuter = new fabric.Polygon(outerPoints, {
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            fill: 'transparent',
            stroke: frame.border_color,
            strokeWidth: bw,
            selectable: false,
            evented: false
        });

        const innerPoints = generatePolygonPoints(radius - bw, sides);

        frameInner = new fabric.Polygon(innerPoints, {
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            absolutePositioned: true
        });
    }

    canvas.add(frameOuter);
    frameOuter.sendToBack();

    if (currentImage) applyClip();

    if (currentText) {
        currentText.clipPath = frameInner;
    }


    canvas.renderAll();
}


/* ===============================
   IMAGE UPLOAD
================================ */
document.getElementById('imageUpload').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file || !frameInner) return;

    const reader = new FileReader();
    reader.onload = () => {
        fabric.Image.fromURL(reader.result, (img) => {

            if (currentImage) canvas.remove(currentImage);

            img.set({
                originX: 'center',
                originY: 'center',
                selectable: true,
                hasControls: true,
                hasBorders: true,
                cornerStyle: 'circle',
                cornerColor: '#ffffff',
                borderColor: '#2563eb',
                transparentCorners: false
            });

            img.setControlsVisibility({
                mt: false, mb: false, ml: false, mr: false, mtr: false
            });

            currentImage = img;
            canvas.add(currentImage);
            canvas.setActiveObject(currentImage);

            fitImageToFrame(img);
            applyClip();
        });
    };

    reader.readAsDataURL(file);
});

/* ===============================
   FIT IMAGE INTO FRAME
================================ */
function fitImageToFrame(img) {
    let fw, fh;

    if (frameInner.type === 'circle') {
        fw = fh = frameInner.radius * 2;
    } 
    else if (frameInner.type === 'polygon') {
        fw = fh = (150 - currentFrameData.border_width) * 2;
    } 
    else {
        fw = frameInner.width;
        fh = frameInner.height;
    }

    const scale = Math.min(fw / img.width, fh / img.height);

    img.set({
        scaleX: scale,
        scaleY: scale,
        left: frameInner.left,
        top: frameInner.top
    });
}


/* ===============================
   APPLY CLIP
================================ */
function applyClip() {
    if (!currentImage || !frameInner) return;
    currentImage.clipPath = frameInner;
    canvas.renderAll();
}

function generatePolygonPoints(radius, sides) {
    const points = [];
    const step = (Math.PI * 2) / sides;

    for (let i = 0; i < sides; i++) {
        const angle = step * i - Math.PI / 2;
        points.push({
            x: radius * Math.cos(angle),
            y: radius * Math.sin(angle)
        });
    }

    return points;
}

/* ===============================
   Add Text
================================ */

document.getElementById('addTextBtn').addEventListener('click', function () {
    if (!frameInner) {
        alert('Please select a frame first');
        return;
    }

    // Remove old text if needed (optional)
    if (currentText) {
        canvas.remove(currentText);
    }

    const text = new fabric.Textbox('Your Text', {
        left: frameInner.left,
        top: frameInner.top,
        originX: 'center',
        originY: 'center',
        width: frameInner.type === 'circle'
            ? frameInner.radius * 1.5
            : frameInner.width * 0.8,

        fontSize: 32,
        fill: '#000000',
        textAlign: 'center',
        editable: true,

        cornerStyle: 'circle',
        cornerColor: '#ffffff',
        borderColor: '#2563eb',
        transparentCorners: false
    });

    // Only 4 corner resize
    text.setControlsVisibility({
        mt: false,
        mb: false,
        ml: false,
        mr: false,
        mtr: false
    });

    // 🔥 IMPORTANT: clip text inside frame
    text.clipPath = frameInner;

    currentText = text;
    canvas.add(currentText);
    canvas.setActiveObject(currentText);

    // Ensure text is above image
    if (currentImage) currentImage.sendToBack();
    frameOuter.sendToBack();

    canvas.renderAll();
});

document.getElementById('textColorPicker').addEventListener('input', function () {
    if (!currentText) return;

    currentText.set('fill', this.value);
    canvas.renderAll();
});




/* ===============================
   DOWNLOAD PNG
================================ */
document.getElementById('downloadBtn').addEventListener('click', function () {
    if (!frameOuter || !currentFrameData) {
        alert('Please select a frame');
        return;
    }

    canvas.discardActiveObject();
    canvas.renderAll();

    const bw = currentFrameData.border_width;
    let left, top, width, height;

    /* ---------- RECTANGLE ---------- */
    if (currentFrameData.shape === 'rectangle') {
        const bounds = frameOuter.getBoundingRect(true, true);
        left = bounds.left - bw;
        top = bounds.top - bw;
        width = bounds.width + (bw * 2);
        height = bounds.height + (bw * 2);
    }

    /* ---------- CIRCLE ---------- */
    else if (currentFrameData.shape === 'circle') {
        left = frameOuter.left - frameOuter.radius - bw;
        top = frameOuter.top - frameOuter.radius - bw;
        width = (frameOuter.radius * 2) + (bw * 2);
        height = width;
    }

    /* ---------- POLYGON ---------- */
    else if (currentFrameData.shape === 'polygon') {
        const bounds = frameOuter.getBoundingRect(true, true);
        left = bounds.left - bw;
        top = bounds.top - bw;
        width = bounds.width + (bw * 2);
        height = bounds.height + (bw * 2);
    }

    const dataURL = canvas.toDataURL({
        format: 'png',
        left,
        top,
        width,
        height,
        multiplier: 2
    });

    const a = document.createElement('a');
    a.href = dataURL;
    a.download = 'photo-frame.png';
    a.click();
});



function parseAspectRatio(ratio) {
    const [w, h] = ratio.split(':').map(Number);
    return { w, h };
}



</script>


</body>
</html>
