
  /* =========================================================
   🔥 HARD CANVAS BASELINE FIX (ROOT CAUSE SOLUTION)
   ========================================================= */
(() => {
    const desc = Object.getOwnPropertyDescriptor(
        CanvasRenderingContext2D.prototype,
        'textBaseline'
    );

    if (desc && desc.set) {
        Object.defineProperty(CanvasRenderingContext2D.prototype, 'textBaseline', {
            set(value) {
                if (value === 'alphabetical') {
                    value = 'alphabetic';
                }
                desc.set.call(this, value);
            }
        });
    }
})();

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
            col.className = 'col-4';
            col.style.cursor = 'pointer';

            col.innerHTML = `
                <div class="card frame-card h-100">
                    <div class="frame-thumb-wrapper">
                        <img src="/storage/${frame.thumbnail}" class="frame-thumb">
                    </div>
                    <div class="card-body text-center p-2">
                        <strong>${frame.name}</strong>
                    </div>
                </div>
            `;

            col.onclick = () => openEditor(frame);
            container.appendChild(col);
        });
    });


/* =========================================================
   OPEN EDITOR (IMPORTANT RESET)
========================================================= */
function openEditor(frame) {
    resetCanvas();      // 🔥 FULL RESET
    loadFrame(frame);  // render new frame
    modal.show();
}

/* ===============================
   CANVAS SETUP
================================ */
const canvas = new fabric.Canvas('canvas', {
    preserveObjectStacking: true
});

const CENTER = 200;

/* ===============================
   STATE
================================ */
let frameOuter = null;
let frameInner = null;
let matLayer = null;

let svgFrameOuter = null;
let svgClipPath = null;

let currentImage = null;
// let currentText = null;
let frameTextureEl = null;

let textObjects = [];
let currentText = null;


/* ===============================
   OPEN MODAL + LOAD FRAME
================================ */

const modalEl = document.getElementById('frameEditorModal');
const modal = new bootstrap.Modal(modalEl);

// ✅ FIX: Fabric + modal visibility
modalEl.addEventListener('shown.bs.modal', () => {
    canvas.setWidth(400);
    canvas.setHeight(400);
    canvas.calcOffset();
    canvas.renderAll();
});

// ✅ Optional: silence ARIA warning
modalEl.addEventListener('hidden.bs.modal', () => {
    document.activeElement?.blur();
});

function openEditor(frame) {
    resetCanvas();        // clear old objects
    loadFrame(frame);     // render selected frame
    modal.show();         // open modal
}


/* ===============================
   RESET CANVAS
================================ */
function resetCanvas() {
    canvas.clear();

    // 🔥 Fabric state
    frameOuter = null;
    frameInner = null;
    svgFrameOuter = null;
    svgClipPath = null;
    frameTextureEl = null;

    // 🔥 User content
    currentImage = null;
    currentText = null;  

    let originalImageBase64 = null;


    // 🔥 Reset file input (VERY IMPORTANT)
    const fileInput = document.getElementById('imageUpload');
    if (fileInput) fileInput.value = '';

    // 🔥 Reset text UI controls
    const colorPicker = document.getElementById('textColorPicker');
    if (colorPicker) colorPicker.value = '#000000';

    const fontSelect = document.getElementById('fontFamilySelect');
    if (fontSelect) fontSelect.value = '';
}

/* ===============================
   HELPERS
================================ */
function sizeFromAspect(aspect, max = 320) {
    if (!aspect || !aspect.includes(':')) return { w: max, h: max };
    const [aw, ah] = aspect.split(':').map(Number);
    return aw >= ah
        ? { w: max, h: max * ah / aw }
        : { w: max * aw / ah, h: max };
}

function thicknessFromAPI(v, w, h) {
    const n = parseInt(v);
    if (isNaN(n)) return 12;
    if (n === 0) return 0;
    return Math.min(Math.max(n * 3, 12), Math.min(w, h) * 0.18);
}

function polygonPoints(radius, sides) {
    const pts = [];
    const step = (Math.PI * 2) / sides;
    for (let i = 0; i < sides; i++) {
        const a = step * i - Math.PI / 2;
        pts.push({
            x: radius * Math.cos(a),
            y: radius * Math.sin(a)
        });
    }
    return pts;
}

function cloneClip(obj) {
    const c = fabric.util.object.clone(obj);
    c.absolutePositioned = true;
    return c;
}

function syncActiveText() {
    const obj = canvas.getActiveObject();
    if (obj && obj.type === 'textbox') {
        currentText = obj;
    } else {
        currentText = null;
    }
}


/* ===============================
   LOAD FRAME (SVG / GEOMETRY)
================================ */
function loadFrame(frame) {

    if (frame.frame_type === 'svg') {
        renderSvgFrame(frame);
        return;
    }

    if (frame.frame_texture_id && frame.texture?.texture_path) {
        fabric.Image.fromURL('/storage/' + frame.texture.texture_path, img => {
            frameTextureEl = img.getElement();
            renderGeometryFrame(frame);
        });
        return;
    }

    renderGeometryFrame(frame);
}

/* ===============================
   GEOMETRY FRAME
================================ */
function renderGeometryFrame(frame) {

    const fill = frameTextureEl
        ? new fabric.Pattern({ source: frameTextureEl, repeat: 'repeat' })
        : (frame.border_color || '#cfcfcf');

    const shadow = new fabric.Shadow({
        color: 'rgba(0,0,0,0.25)',
        blur: 18,
        offsetY: 8
    });

    if (frame.shape === 'rectangle') {

        const { w, h } = sizeFromAspect(frame.aspect_ratio, 320);
        const t = thicknessFromAPI(frame.border_width, w, h);
        const r = Math.min(frame.border_radius || 0, Math.min(w, h) / 2);

        const outer = new fabric.Rect({
            width: w,
            height: h,
            rx: r,
            ry: r,
            fill,
            originX: 'center',
            originY: 'center'
        });

        const hole = new fabric.Rect({
            width: w - t * 2,
            height: h - t * 2,
            rx: Math.max(r - t, 0),
            ry: Math.max(r - t, 0),
            originX: 'center',
            originY: 'center',
            globalCompositeOperation: 'destination-out'
        });

        frameOuter = new fabric.Group([outer, hole], {
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            shadow,
            selectable: false
        });

        frameInner = new fabric.Rect({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            width: w - t * 2.8,
            height: h - t * 2.8,
            rx: Math.max(r - t * 1.4, 0),
            ry: Math.max(r - t * 1.4, 0),
            absolutePositioned: true
        });
    }

    if (frame.shape === 'circle') {

        const R = 160;
        const t = thicknessFromAPI(frame.border_width, R * 2, R * 2);

        const outer = new fabric.Circle({
            radius: R,
            fill,
            originX: 'center',
            originY: 'center'
        });

        const hole = new fabric.Circle({
            radius: R - t,
            originX: 'center',
            originY: 'center',
            globalCompositeOperation: 'destination-out'
        });

        frameOuter = new fabric.Group([outer, hole], {
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            shadow,
            selectable: false
        });

        frameInner = new fabric.Circle({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            radius: R - t * 1.4,
            absolutePositioned: true
        });
    }


/* ================= POLYGON ================= */
if (frame.shape === 'polygon') {

    const sides = frame.polygon_sides || 6;
    const R = 160;
    const t = thicknessFromAPI(frame.border_width, R * 2, R * 2);

    const outer = new fabric.Polygon(
        polygonPoints(R, sides),
        {
            fill,
            originX: 'center',
            originY: 'center'
        }
    );

    const hole = new fabric.Polygon(
        polygonPoints(R - t, sides),
        {
            originX: 'center',
            originY: 'center',
            globalCompositeOperation: 'destination-out'
        }
    );

    frameOuter = new fabric.Group([outer, hole], {
        left: CENTER,
        top: CENTER,
        originX: 'center',
        originY: 'center',
        shadow,
        selectable: false
    });

    frameInner = new fabric.Polygon(
        polygonPoints(R - t * 1.4, sides),
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
    canvas.renderAll();
}
// Focus hidden textarea when entering text edit mode
canvas.on('text:editing:entered', e => {
    setTimeout(() => {
        e.target.hiddenTextarea?.focus();
    }, 0);
});

/* ===============================
   SVG FRAME
================================ */
function renderSvgFrame(frame) {

    if (svgFrameOuter) {
        canvas.remove(svgFrameOuter);
    }

    fabric.loadSVGFromURL('/storage/' + frame.svg_path, (objects, options) => {

        const svg = fabric.util.groupSVGElements(objects, options);
        const scale = 320 / Math.max(svg.width, svg.height);

        svg.set({
            left: CENTER,
            top: CENTER,
            originX: 'center',
            originY: 'center',
            scaleX: scale,
            scaleY: scale,
            selectable: false,
            evented: false,
            objectCaching: false
        });

        svg.clone(cloned => {
            cloned.set({
                left: CENTER,
                top: CENTER,
                originX: 'center',
                originY: 'center',
                scaleX: scale,
                scaleY: scale,
                absolutePositioned: true
            });

            svgClipPath = cloned;

            
        });

        svgFrameOuter = svg;
        canvas.add(svgFrameOuter);
        canvas.requestRenderAll();

    }, null, { crossOrigin: 'anonymous' });
}

/* ===============================
   IMAGE UPLOAD
================================ */
document.getElementById('imageUpload')?.addEventListener('change', e => {
    const file = e.target.files[0];
    if (!file || (!frameInner && !svgClipPath)) return;

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
                selectable: true
            });

            canvas.add(img);
            fitImage(img);
            applyClip();
            img.bringToFront();
        });
    };
    reader.readAsDataURL(file);
});



function fitImage(img) {
    const bounds = frameInner
        ? frameInner.getBoundingRect()
        : svgClipPath.getBoundingRect();

    const scale = Math.max(
        bounds.width / img.width,
        bounds.height / img.height
    );

    img.set({ scaleX: scale, scaleY: scale });
}

function applyClip() {
    if (!currentImage) return;

    currentImage.clipPath = frameInner
        ? cloneClip(frameInner)
        : cloneClip(svgClipPath);

    canvas.renderAll();
}

/* ===============================
   TEXT
================================ */
document.getElementById('addTextBtn')?.addEventListener('click', () => {

    if (!frameInner && !svgClipPath) {
        alert('Select a frame first');
        return;
    }

    const text = new fabric.Textbox('Your Text', {
        left: CENTER,
        top: CENTER,
        originX: 'center',
        originY: 'center',
        width: 260,
        fontSize: 32,
        fill: '#000',
        textAlign: 'center',
        selectable: true,
        editable: true
    });

    text.clipPath = frameInner
        ? cloneClip(frameInner)
        : cloneClip(svgClipPath);

    canvas.add(text);
    canvas.setActiveObject(text);
    currentText = text;

    // 🔥 multi-text state
    textObjects.push(text);
    currentText = text;

    // 🔥 enter edit mode
    text.enterEditing();
    text.hiddenTextarea?.focus();
});

canvas.on('mouse:down', () => {
    syncActiveText();
});

canvas.on('selection:cleared', () => {
    currentText = null;
});



document.getElementById('textColorPicker')?.addEventListener('input', e => {
    if (!currentText) return;
    currentText.set('fill', e.target.value);
    canvas.renderAll();
});

document.getElementById('fontFamilySelect')?.addEventListener('change', async e => {
    if (!currentText) return;
    const font = e.target.value;
    if (!font) return;

    await document.fonts.load(`32px "${font}"`);
    currentText.set({ fontFamily: font });
    currentText.initDimensions();
    currentText.setCoords();
    canvas.renderAll();
});

document.addEventListener('keydown', e => {
    if (e.key === 'Delete') {

        const obj = canvas.getActiveObject();
        if (!obj || obj.type !== 'textbox') return;

        canvas.remove(obj);
        textObjects = textObjects.filter(t => t !== obj);
        currentText = null;

        canvas.discardActiveObject();
        canvas.requestRenderAll();
    }
});


/* ===============================
   DOWNLOAD
================================ */
document.getElementById('downloadBtn')?.addEventListener('click', () => {

    const target = frameOuter || svgFrameOuter;
    if (!target) return;

    const b = target.getBoundingRect(true, true);

    const url = canvas.toDataURL({
        format: 'png',
        left: b.left,
        top: b.top,
        width: b.width,
        height: b.height,
        multiplier: 2
    });

    const a = document.createElement('a');
    a.href = url;
    a.download = 'photo-frame.png';
    a.click();

    
});



