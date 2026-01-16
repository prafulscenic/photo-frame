
/* ===============================
   ADMIN FRAME PREVIEW
================================ */
const previewCanvas = new fabric.Canvas('previewCanvas', {
    selection: false
});

const PREVIEW_CENTER = 150;
let previewFrame = null;
let previewFrameImage = null;

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


// ---for upload frame image---
function renderFrameTexturePreview(file) {
    previewCanvas.clear();
    previewFrameImage = null;

    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        fabric.Image.fromURL(e.target.result, (img) => {

            const maxSize = 260;
            const scale = Math.min(
                maxSize / img.width,
                maxSize / img.height
            );

            img.set({
                left: PREVIEW_CENTER,
                top: PREVIEW_CENTER,
                originX: 'center',
                originY: 'center',
                scaleX: scale,
                scaleY: scale,
                selectable: false,
                evented: false
            });

            previewFrameImage = img;
            previewCanvas.add(previewFrameImage);
            previewCanvas.renderAll();
        });
    };

    reader.readAsDataURL(file);
}

document.querySelector('[name="frame_texture"]')
    ?.addEventListener('change', function (e) {
        const file = e.target.files[0];
        renderFrameTexturePreview(file);
    });


if (file) {
    document.querySelectorAll(
        '[name="border_width"], [name="border_color"], [name="border_radius"]'
    ).forEach(el => el.disabled = true);
}
