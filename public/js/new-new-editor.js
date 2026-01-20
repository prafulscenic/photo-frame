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
let frameTextureEl = null;

let textObjects = [];
let currentText = null;

let svgFrameOuter = null;
let svgClipPath = null;


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

function thicknessFromAPI(v, w, h) {
    const n = parseInt(v);
    if (isNaN(n)) return Math.min(12, Math.min(w, h) * 0.18);
    if (n === 0) return 0;
    const base = Math.max(n * 3, 12);
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
    if (!frameInner) return null;
    const c = fabric.util.object.clone(frameInner);
    c.set({
        left: frameInner.left,
        top: frameInner.top,
        originX: frameInner.originX,
        originY: frameInner.originY,
        absolutePositioned: true
    });
    return c;
}

/* ===============================
   LAYER CONTROL (CRITICAL)
================================ */
function enforceLayerOrder() {
    if (frameOuter) frameOuter.sendToBack();
    if (matLayer) matLayer.sendToBack();
    if (currentImage) currentImage.bringToFront();
    textObjects.forEach(t => t.bringToFront());
    canvas.requestRenderAll();
}

function reclipAllTexts() {
    textObjects.forEach(t => {
        const clip = cloneClip();
        t.set({
            clipPath: clip
        });

        // 🔥 CRITICAL: force Fabric to refresh controls
        t.initDimensions();
        t.setCoords();
    });
}

/* ===============================
   CANVAS SELECTION (REGISTER ONCE)
================================ */
canvas.on('selection:created', e => {
    if (e.target && e.target.type === 'textbox') {
        currentText = e.target;
        enforceLayerOrder();
    }
});

canvas.on('selection:updated', e => {
    if (e.target && e.target.type === 'textbox') {
        currentText = e.target;
        enforceLayerOrder();
    }
});

canvas.on('selection:cleared', () => {
    currentText = null;
});

/* ===============================
   LOAD FRAME
================================ */
function loadFrame(frame) {

    // RESET CANVAS STATE
    canvas.clear();
    frameOuter = frameInner = matLayer = null;
    currentImage = null;

    // 🔥 SVG FRAME (DISPLAY ONLY)
    if (frame.frame_type === 'svg') {
        renderSvgFrame(frame);
        return;
    }

    frameTextureEl = null;

    if (frame.frame_texture_id && frame.texture?.texture_path) {
        fabric.Image.fromURL('/storage/' + frame.texture.texture_path, img => {
            frameTextureEl = img.getElement();
            renderFrame(frame);
        });
        return;
    }

    renderFrame(frame);
}

/* ===============================
   RENDER FRAME
================================ */
function renderFrame(frame) {

    [frameOuter, frameInner, matLayer].forEach(o => o && canvas.remove(o));
    frameOuter = frameInner = matLayer = null;

    const useTexture = !!frameTextureEl && frame.frame_texture_id;
    const frameFill = useTexture
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

    /* ===== ZERO BORDER ===== */
    if (thickness === 0) {

        frameOuter = new fabric.Rect({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            width: W,
            height: H,
            rx: radius,
            ry: radius,
            fill: 'transparent',
            selectable: false
        });

        matLayer = new fabric.Rect({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            width: W,
            height: H,
            rx: radius,
            ry: radius,
            fill: '#f4f4f0',
            selectable: false
        });

        frameInner = new fabric.Rect({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            width: W,
            height: H,
            rx: radius,
            ry: radius,
            absolutePositioned: true
        });

    } else {
        /* ===== NORMAL BORDER (UNCHANGED LOGIC) ===== */

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
}

    /* ================= CIRCLE ================= */
if (frame.shape === 'circle') {

    const R = 160;
    const thickness = thicknessFromAPI(frame.border_width, R * 2, R * 2);

    if (thickness === 0) {

        frameOuter = new fabric.Circle({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            radius: R,
            fill: 'transparent',
            selectable: false
        });

        matLayer = new fabric.Circle({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            radius: R,
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

    } else {

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
            radius: R - thickness * 1.4,
            absolutePositioned: true
        });
    }
}

    /* ================= POLYGON ================= */
if (frame.shape === 'polygon') {

    const sides = frame.polygon_sides || 6;
    const R = 160;
    const thickness = thicknessFromAPI(frame.border_width, R * 2, R * 2);

    if (thickness === 0) {

        frameOuter = new fabric.Polygon(
            polygonPoints(R, sides),
            {
                left: CENTER,
                top: CENTER,
                originX: 'center',
                originY: 'center',
                fill: 'transparent',
                selectable: false
            }
        );

        matLayer = new fabric.Polygon(
            polygonPoints(R, sides),
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
            polygonPoints(R, sides),
            {
                left: CENTER,
                top: CENTER,
                originX: 'center',
                originY: 'center',
                absolutePositioned: true
            }
        );

    } else {

        const outer = new fabric.Polygon(
            polygonPoints(R, sides),
            { originX: 'center', originY: 'center', fill: frameFill }
        );

        const hole = new fabric.Polygon(
            polygonPoints(R - thickness, sides),
            { originX: 'center', originY: 'center', globalCompositeOperation: 'destination-out' }
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
}

    canvas.add(frameOuter);
    canvas.add(matLayer);

    frameOuter.sendToBack();
    matLayer.sendToBack();

    if (currentImage) {
        currentImage.bringToFront();
        applyClip();
    }

// 🔥 force canvas to settle objects first
canvas.renderAll();

// 🔥 now safely re-apply clips
reclipAllTexts();

// 🔥 enforce correct z-index
enforceLayerOrder();

}

/* ===============================
   IMAGE UPLOAD
================================ */
document.getElementById('imageUpload')?.addEventListener('change', e => {
    const file = e.target.files[0];
    // if (!file || !frameInner) return;
    if (!file || (!frameInner && !svgClipPath)) return;


    const reader = new FileReader();
    reader.onload = () => {
        fabric.Image.fromURL(reader.result, img => {
            if (currentImage) canvas.remove(currentImage);
            currentImage = img;
            img.set({ left: CENTER, top: CENTER, originX: 'center', originY: 'center', selectable: true });
            canvas.add(img);
            fitImage(img);
            applyClip();
            enforceLayerOrder();
        });
    };
    reader.readAsDataURL(file);
});


function fitImage(img) {

    let bounds = null;

    if (frameInner) {
        bounds = frameInner.getBoundingRect();
    }

    if (svgClipPath) {
        bounds = svgClipPath.getBoundingRect();
    }

    if (!bounds) return;

    const scale = Math.max(
        bounds.width / img.width,
        bounds.height / img.height
    );

    img.set({ scaleX: scale, scaleY: scale });
}



function applyClip() {

    if (!currentImage) return;

    // 🔵 Geometry frame
    if (frameInner) {
        currentImage.clipPath = cloneClip();
    }

    // 🟣 SVG frame
    if (svgClipPath) {
        currentImage.clipPath = fabric.util.object.clone(svgClipPath);
    }
}


// Get currently active text object
function getActiveText() {
    const obj = canvas.getActiveObject();
    if (obj && obj.type === 'textbox') {
        return obj;
    }
    return null;
}


/* ===============================
   ADD TEXT (MULTI TEXT SAFE)
================================ */
document.getElementById('addTextBtn')?.addEventListener('click', () => {
    if (!frameInner) return alert('Select frame first');

    const text = new fabric.Textbox('Your Text', {
        left: CENTER,
        top: CENTER,
        originX: 'center',
        originY: 'center',
        width: frameInner.getBoundingRect().width * 0.8,
        fontSize: 32,
        fill: '#000',
        fontFamily: 'Poppins',
        textAlign: 'center',
        selectable: true
    });

    text.lockUniScaling = true;
    text.clipPath = cloneClip();

    textObjects.push(text);
    currentText = text;

    canvas.add(text);
    canvas.setActiveObject(text);
    enforceLayerOrder();
});

/* ===============================
   TEXT STYLING
================================ */
document.getElementById('textColorPicker')?.addEventListener('input', function () {
    const text = getActiveText();
    if (!text) return;

    text.set('fill', this.value);
    canvas.requestRenderAll();
});


document.getElementById('fontFamilySelect')?.addEventListener('change', async function () {
    const text = getActiveText();
    if (!text) return;

    const font = this.value;
    await document.fonts.load(`32px "${font}"`);

    text.set({ fontFamily: font });
    text.initDimensions();
    text.setCoords();

    canvas.requestRenderAll();
});

/* ===============================
   DELETE TEXT
================================ */
document.addEventListener('keydown', e => {
    const text = getActiveText();
    if (e.key === 'Delete' && text) {
        canvas.remove(text);
        textObjects = textObjects.filter(t => t !== text);
        canvas.requestRenderAll();
    }
});

// render SVG frame only (for SVG type frames)
function renderSvgFrame(frame) {

    fabric.loadSVGFromURL('/storage/' + frame.svg_path, (objects, options) => {

        const svgGroup = fabric.util.groupSVGElements(objects, options);

        const maxSize = 320;
        const scale = maxSize / Math.max(svgGroup.width, svgGroup.height);

        svgGroup.set({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            scaleX: scale,
            scaleY: scale,
            selectable: false,
            evented: false
        });

        // 🔵 Visible SVG frame
        svgFrameOuter = svgGroup;

        // 🔥 Clone SVG for clipPath
        svgClipPath = fabric.util.object.clone(svgGroup);
        svgClipPath.set({
            absolutePositioned: true
        });

        canvas.add(svgFrameOuter);
        canvas.renderAll();
    });
}



/* ===============================
   DOWNLOAD
================================ */
document.getElementById('downloadBtn').addEventListener('click', () => {
    // const b = frameOuter.getBoundingRect(true, true);

    const target = frameOuter || svgFrameOuter;
    if (!target) return;

    const b = target.getBoundingRect(true, true);


    const prevShadow = frameOuter.shadow;
    frameOuter.shadow = null;

    const url = canvas.toDataURL({
        format: 'png',
        left: b.left,
        top: b.top,
        width: b.width,
        height: b.height,
        multiplier: 2
    });

    frameOuter.shadow = prevShadow;

    const a = document.createElement('a');
    a.href = url;
    a.download = 'photo-frame.png';
    a.click();
});
