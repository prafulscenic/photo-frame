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
            col.className = 'col-6';
            col.style.cursor = 'pointer';

            col.innerHTML = `
        
                <div class="card frame-card">
                    <img src="/storage/${frame.thumbnail}" class="card-img-top">
                    <div class="card-body text-center p-2">
                        <strong>${frame.name}</strong>
                    </div>
                </div>
              
            `;

            col.onclick = () => loadFrame(frame);
            container.appendChild(col);
        });
    });

/* ===============================
   CANVAS SETUP
================================ */
const canvas = new fabric.Canvas('canvas', {
    preserveObjectStacking: true
});

const CENTER = 200;

let frameOuter = null;
let frameInner = null;
let matLayer = null;
let currentImage = null;
let currentFrameData = null;
let frameTextureEl = null;
let currentText = null;


/* ===============================
   HELPERS
================================ */
function sizeFromAspect(aspect, max = 320) {
    if (!aspect || !aspect.includes(':')) return { w: max, h: max };
    const [aw, ah] = aspect.split(':').map(Number);
    return aw >= ah
        ? { w: max, h: Math.round(max * ah / aw) }
        : { w: Math.round(max * aw / ah), h: max };
}

// function thicknessFromAPI(v, w, h) {
//     const base = Math.max((parseInt(v) || 5) * 3, 12);
//     return Math.min(base, Math.min(w, h) * 0.18);
// }


function thicknessFromAPI(v, w, h) {
    const n = parseInt(v);

    // fallback (invalid / null)
    if (isNaN(n)) return Math.min(12, Math.min(w, h) * 0.18);

    // 👈 real zero border
    if (n === 0) return 0;

    // base thickness (same rule as admin)
    const base = Math.max(n * 3, 12);

    // clamp so border never eats frame
    return Math.min(base, Math.min(w, h) * 0.18);
}

function polygonPoints(radius, sides) {
    const pts = [];
    const step = (Math.PI * 2) / sides;
    for (let i = 0; i < sides; i++) {
        const a = step * i - Math.PI / 2;
        pts.push({ x: radius * Math.cos(a), y: radius * Math.sin(a) });
    }
    return pts;
}

function cloneClip() {
    const c = fabric.util.object.clone(frameInner);
    c.absolutePositioned = true;
    return c;
}

/* ===============================
   LOAD FRAME (COLOR OR TEXTURE)
================================ */
function loadFrame(frame) {
    currentFrameData = frame;

    // RESET EVERYTHING FIRST
    frameTextureEl = null;

    // TEXTURE FRAME
    if (frame.frame_texture_id && frame.texture?.texture_path) {
        fabric.Image.fromURL('/storage/' + frame.texture.texture_path, img => {
            frameTextureEl = img.getElement();
            renderFrame(frame);
            if (currentImage) {
                fitImage(currentImage);
                applyClip();
            }
        });
        return;
    }

    // COLOR FRAME (clean state)
    renderFrame(frame);
    if (currentImage) {
        fitImage(currentImage);
        applyClip();
    }
}


/* ===============================
   RENDER FRAME
================================ */
function renderFrame(frame) {

    [frameOuter, frameInner, matLayer].forEach(o => o && canvas.remove(o));
    frameOuter = frameInner = matLayer = null;

const useTexture = !!frameTextureEl && frame.frame_texture_id;

    let frameFill = useTexture
    ? new fabric.Pattern({ source: frameTextureEl, repeat: 'repeat' })
    : (frame.border_color || '#cfcfcf');


    const outerShadow = new fabric.Shadow({
        color: 'rgba(0,0,0,0.25)',
        blur: 18,
        offsetY: 8
    });

    /* ================= RECTANGLE ================= */
    if (frame.shape === 'rectangle') {

        const { w: W, h: H } = sizeFromAspect(frame.aspect_ratio, 320);
        const thickness = thicknessFromAPI(frame.border_width, W, H);
        const radius = Math.min(frame.border_radius || 0, Math.min(W, H) / 2);

        const outer = new fabric.Rect({
            width: W,
            height: H,
            rx: radius,
            ry: radius,
            originX: 'center',
            originY: 'center',
            fill: frameFill
        });

        const hole = new fabric.Rect({
            width: W - thickness * 2,
            height: H - thickness * 2,
            rx: Math.max(radius - thickness, 0),
            ry: Math.max(radius - thickness, 0),
            originX: 'center',
            originY: 'center',
            globalCompositeOperation: 'destination-out'
        });

        frameOuter = new fabric.Group([outer, hole], {
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            selectable: false,
            shadow: outerShadow
        });

        matLayer = new fabric.Rect({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            width: W - thickness * 1.4,
            height: H - thickness * 1.4,
            rx: Math.max(radius - thickness * 0.7, 0),
            ry: Math.max(radius - thickness * 0.7, 0),
            fill: '#f4f4f0',
            selectable: false
        });

        frameInner = new fabric.Rect({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            width: W - thickness * 2.8,
            height: H - thickness * 2.8,
            rx: Math.max(radius - thickness * 1.4, 0),
            ry: Math.max(radius - thickness * 1.4, 0),
            absolutePositioned: true
        });
    }

    /* ================= CIRCLE ================= */
    if (frame.shape === 'circle') {

        const R = 160;
        const thickness = thicknessFromAPI(frame.border_width, R * 2, R * 2);


        const outer = new fabric.Circle({
            radius: R,
            originX: 'center',
            originY: 'center',
            fill: frameFill
        });

        const hole = new fabric.Circle({
            radius: R - thickness,
            originX: 'center',
            originY: 'center',
            globalCompositeOperation: 'destination-out'
        });

        frameOuter = new fabric.Group([outer, hole], {
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            selectable: false,
            shadow: outerShadow
        });

        matLayer = new fabric.Circle({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            radius: R - thickness * 0.7,
            fill: '#f4f4f0',
            selectable: false
        });

          
        frameInner = new fabric.Circle({
                    left: CENTER,
                    top: CENTER,
                    originX: 'center',
                    originY: 'center',
                    radius: R,
                    absolutePositioned: true
                });

      
    }

    /* ================= POLYGON ================= */
    if (frame.shape === 'polygon') {

        const sides = frame.polygon_sides || 6;
        const R = 160;
        const thickness = thicknessFromAPI(frame.border_width, R * 2, R * 2);

        const outer = new fabric.Polygon(
            polygonPoints(R, sides),
            { originX: 'center', originY: 'center', fill: frameFill }
        );

        const hole = new fabric.Polygon(
            polygonPoints(R - thickness, sides),
            { originX: 'center', originY: 'center',
              globalCompositeOperation: 'destination-out' }
        );

        frameOuter = new fabric.Group([outer, hole], {
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            selectable: false,
            shadow: outerShadow
        });

        matLayer = new fabric.Polygon(
            polygonPoints(R - thickness * 0.7, sides),
            {
                left: CENTER,
                top: CENTER,
                originX: 'center',
                originY: 'center',
                fill: '#f4f4f0',
                selectable: false
            }
        );

        frameInner = new fabric.Polygon(
            polygonPoints(R - thickness * 1.4, sides),
            {
                left: CENTER,
                top: CENTER,
                originX: 'center',
                originY: 'center',
                absolutePositioned: true
            }
        );
    }

    canvas.add(frameOuter);
    canvas.add(matLayer);

    // Always push frame parts to back
    frameOuter.sendToBack();
    matLayer.sendToBack();

    // 🔴 IMPORTANT: bring image back above mat
    if (currentImage) {
        currentImage.bringToFront();
    }

    // 🔴 IMPORTANT: text always top
    if (currentText) {
        currentText.bringToFront();
        currentText.clipPath = cloneClip();
    }

    addInnerShadow(frame);

    canvas.renderAll();
}

/* ===============================
   INNER SHADOW (NO CIRCLE BUG)
================================ */
function addInnerShadow(frame) {
    if (!frameInner) return;
    if (frame.shape === 'circle') return;

    const innerShadow = fabric.util.object.clone(frameInner);
    innerShadow.fill = 'transparent';
    innerShadow.shadow = new fabric.Shadow({
        color: 'rgba(0,0,0,0.22)',
        blur: 12,
        offsetY: 3
    });
    innerShadow.selectable = false;
    innerShadow.evented = false;

    canvas.add(innerShadow);
}

/* ===============================
   IMAGE UPLOAD
================================ */
document.getElementById('imageUpload').addEventListener('change', e => {
    const file = e.target.files[0];
    if (!file || !frameInner) return;

    const reader = new FileReader();
    reader.onload = () => {
        fabric.Image.fromURL(reader.result, img => {

            if (currentImage) canvas.remove(currentImage);

            currentImage = img;
            img.set({
                left: CENTER,
                top: CENTER,
                originX: 'center',
                originY: 'center',
                selectable: true,
                cornerStyle: 'circle',
                cornerSize: 12,
                cornerColor: '#ffffff',
                borderColor: '#2563eb',
                transparentCorners: false
            });

            img.setControlsVisibility({
                mt: false, mb: false, ml: false, mr: false, mtr: false
            });

            img.lockUniScaling = true;

            canvas.add(img);
            canvas.setActiveObject(img);

            fitImage(img);
            applyClip();
        });
    };
    reader.readAsDataURL(file);
});

/* ===============================
   IMAGE FIT & CLIP
================================ */
function fitImage(img) {
    const b = frameInner.getBoundingRect();
    const scale = Math.max(b.width / img.width, b.height / img.height);
    img.set({ scaleX: scale, scaleY: scale });
}

function applyClip() {
    currentImage.clipPath = cloneClip();
    canvas.renderAll();
}

/* ===============================
   DOWNLOAD
// ================================ */


document.getElementById('downloadBtn').addEventListener('click', () => {
    const b = frameOuter.getBoundingRect(true, true);

    const url = canvas.toDataURL({
        format: 'png',
        left: b.left,
        top: b.top,
        width: b.width,
        height: b.height,
        multiplier: 2,
    });

    const a = document.createElement('a');
    a.href = url;
    a.download = 'photo-frame.png';
    a.click();
});




document.getElementById('addTextBtn')?.addEventListener('click', () => {

    if (!frameInner) {
        alert('Please select a frame first');
        return;
    }

    // remove old text (optional – keeps single text)
    if (currentText) {
        canvas.remove(currentText);
    }

    const text = new fabric.Textbox('Your Text', {
        left: CENTER,
        top: CENTER,
        originX: 'center',
        originY: 'center',
        width: frameInner.getBoundingRect().width * 0.8,
        fontSize: 32,
        fill: '#000000',
        textAlign: 'center',
        editable: true,

        // UX
        cornerStyle: 'circle',
        cornerSize: 12,
        cornerColor: '#ffffff',
        borderColor: '#2563eb',
        transparentCorners: false,
        selectable: true
    });

    // ONLY 4 CORNERS
    text.setControlsVisibility({
        mt: false,
        mb: false,
        ml: false,
        mr: false,
        mtr: false
    });
    

    // KEEP ASPECT
    text.lockUniScaling = true;

    // 🔥 CLIP TEXT INSIDE FRAME
    text.clipPath = cloneClip();

    currentText = text;
    canvas.add(text);
    canvas.setActiveObject(text);

    // Keep order: frame → image → text
    if (frameOuter) frameOuter.sendToBack();
    if (matLayer) matLayer.sendToBack();
    if (currentImage) currentImage.bringToFront();
   if (currentText) currentText.bringToFront();


    canvas.renderAll();
});

document.getElementById('textColorPicker')?.addEventListener('input', function () {
    if (!currentText) return;
    currentText.set('fill', this.value);
    canvas.renderAll();
});