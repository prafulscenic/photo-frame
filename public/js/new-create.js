
/* ===============================
   ADMIN FRAME PREVIEW (FIXED)
================================ */
const previewCanvas = new fabric.Canvas('previewCanvas', {
    selection: false
});

const PREVIEW_CENTER = 150;
let textureCache = {};

/* ===============================
   INPUT REFERENCES
================================ */
const shapeSelect        = document.getElementById('shapeSelect');
const polygonWrapper    = document.getElementById('polygonSidesWrapper');
const polygonSidesInput = document.getElementById('polygonSides');
const aspectRatioWrapper= document.getElementById('aspectRatioWrapper');
const aspectRatioSelect = document.getElementById('aspectRatio');

const styleSelect       = document.getElementById('frameStyleType');
const colorInput        = document.getElementById('borderColorInput');
const textureRadios     = document.querySelectorAll('.texture-radio');

/* ===============================
   HELPERS
================================ */
function thicknessFromInput(v) {
    return Math.max((parseInt(v) || 5) * 3, 12);
}

function sizeFromAspect(aspect, max = 260) {
    if (!aspect || !aspect.includes(':')) return { w: max, h: max };
    const [aw, ah] = aspect.split(':').map(Number);
    return aw >= ah
        ? { w: max, h: max * ah / aw }
        : { w: max * aw / ah, h: max };
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

/* ===============================
   EVENTS
================================ */
shapeSelect.addEventListener('change', () => {
    polygonWrapper.classList.toggle('d-none', shapeSelect.value !== 'polygon');
    aspectRatioWrapper.classList.toggle('d-none', shapeSelect.value !== 'rectangle');
    renderPreview();
});

[
    polygonSidesInput,
    aspectRatioSelect,
    styleSelect,
    colorInput,
    ...document.querySelectorAll('[name="border_width"], [name="border_radius"]')
].forEach(el => el && el.addEventListener('change', renderPreview));

textureRadios.forEach(r => r.addEventListener('change', renderPreview));

/* ===============================
   LOAD TEXTURE FROM RADIO
================================ */
function loadTextureFromRadio(callback) {
    const selected = document.querySelector('.texture-radio:checked');
    if (!selected) return;

    const img = selected.nextElementSibling.querySelector('img');
    if (!img) return;

    if (textureCache[selected.value]) {
        callback(textureCache[selected.value]);
        return;
    }

    fabric.Image.fromURL(img.src, fimg => {
        textureCache[selected.value] = fimg.getElement();
        callback(textureCache[selected.value]);
    });
}

/* ===============================
   RENDER PREVIEW
================================ */
function renderPreview() {
    previewCanvas.clear();

    const shape = shapeSelect.value;
    if (!shape) return;

    const thickness = thicknessFromInput(
        document.querySelector('[name="border_width"]').value
    );

    const radiusInput = parseInt(
        document.querySelector('[name="border_radius"]').value || 0
    );

    const OUTER_SHADOW = new fabric.Shadow({
        color: 'rgba(0,0,0,0.30)',
        blur: 14,
        offsetY: 6
    });

    const INNER_FILL_COLOR = '#e5e7eb';

    const style = styleSelect.value;

    if (style === 'color') {
        drawFrame(colorInput.value || '#ccc');
    }

    if (style === 'texture') {
        loadTextureFromRadio(el => {
            drawFrame(new fabric.Pattern({ source: el, repeat: 'repeat' }));
        });
    }

    /* ----------------------------
       DRAW FRAME
    ---------------------------- */
    function drawFrame(fill) {

        /* ---------- RECTANGLE ---------- */
        if (shape === 'rectangle') {

            const { w, h } = sizeFromAspect(aspectRatioSelect.value, 260);
            const radius = Math.min(radiusInput, Math.min(w, h) / 2);

            previewCanvas.add(new fabric.Rect({
                left: PREVIEW_CENTER,
                top: PREVIEW_CENTER,
                originX: 'center',
                originY: 'center',
                width: w - thickness * 2,
                height: h - thickness * 2,
                rx: Math.max(radius - thickness, 0),
                ry: Math.max(radius - thickness, 0),
                fill: INNER_FILL_COLOR,
                selectable: false
            }));

            const outer = new fabric.Rect({
                left: 0, top: 0,
                originX: 'center', originY: 'center',
                width: w,
                height: h,
                rx: radius,
                ry: radius,
                fill
            });

            const hole = new fabric.Rect({
                left: 0, top: 0,
                originX: 'center', originY: 'center',
                width: w - thickness * 2,
                height: h - thickness * 2,
                rx: Math.max(radius - thickness, 0),
                ry: Math.max(radius - thickness, 0),
                globalCompositeOperation: 'destination-out'
            });

            previewCanvas.add(new fabric.Group([outer, hole], {
                left: PREVIEW_CENTER,
                top: PREVIEW_CENTER,
                originX: 'center',
                originY: 'center',
                shadow: OUTER_SHADOW,
                selectable: false
            }));
        }


       /* ---------- CIRCLE (FIXED) ---------- */
    if (shape === 'circle') {

        const R = 120;

        // INNER PLACEHOLDER (PHOTO AREA)
        const innerFill = new fabric.Circle({
            left: PREVIEW_CENTER,
            top: PREVIEW_CENTER,
            originX: 'center',
            originY: 'center',
            radius: R - thickness,
            fill: '#e5e7eb',
            selectable: false,
            evented: false
        });

        // OUTER FRAME
        const outerFrame = new fabric.Circle({
            left: PREVIEW_CENTER,
            top: PREVIEW_CENTER,
            originX: 'center',
            originY: 'center',
            radius: R,
            fill: fill,
            selectable: false,
            shadow: OUTER_SHADOW
        });

        previewCanvas.add(outerFrame);
        previewCanvas.add(innerFill);
    }


        /* ---------- POLYGON ---------- */
        if (shape === 'polygon') {

            const sides = parseInt(polygonSidesInput.value || 6);
            const R = 120;

            previewCanvas.add(new fabric.Polygon(
                polygonPoints(R - thickness, sides),
                {
                    left: PREVIEW_CENTER,
                    top: PREVIEW_CENTER,
                    originX: 'center',
                    originY: 'center',
                    fill: INNER_FILL_COLOR,
                    selectable: false
                }
            ));

            const outer = new fabric.Polygon(
                polygonPoints(R, sides),
                { fill }
            );

            const hole = new fabric.Polygon(
                polygonPoints(R - thickness, sides),
                { globalCompositeOperation: 'destination-out' }
            );

            previewCanvas.add(new fabric.Group([outer, hole], {
                left: PREVIEW_CENTER,
                top: PREVIEW_CENTER,
                originX: 'center',
                originY: 'center',
                shadow: OUTER_SHADOW,
                selectable: false
            }));
        }

        previewCanvas.renderAll();
    }
}
