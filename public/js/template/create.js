/* =========================================================
   🎨 FABRIC CANVAS INITIALIZATION
   ========================================================= */

const canvas = new fabric.Canvas('templateCanvas', {
    preserveObjectStacking: true,
    selection: true
});

canvas.setBackgroundColor('#f3f4f6', canvas.renderAll.bind(canvas));


/* =========================================================
   🔗 DOM ELEMENT CACHE (Single Source of Truth)
   ========================================================= */

const DOM = {
    addRectBtn: document.getElementById('addRectBtn'),
    addCircleBtn: document.getElementById('addCircleBtn'),
    addHexagonalBtn: document.getElementById('addHexagonalBtn'),
    addOcatagonalBtn: document.getElementById('addOcatagonalBtn'),
    addPentagonBtn: document.getElementById('addPentagonBtn'),
    addTriangleBtn: document.getElementById('addTriangleBtn'),
    duplicateBtn: document.getElementById('duplicateBtn'),
    deleteObjBtn: document.getElementById('deleteObjBtn'),
    bringFrontBtn: document.getElementById('bringFrontBtn'),
    sendBackBtn: document.getElementById('sendBackBtn'),
    markPhotoSlotBtn: document.getElementById('markPhotoSlotBtn'),
    saveTemplateBtn: document.getElementById('saveTemplateBtn'),
    svgUploadInput: document.getElementById('svgUploadInput'),
};


/* =========================================================
   🧰 HELPER FUNCTIONS (REUSABLE)
   ========================================================= */

const Helpers = {
    centerX: () => canvas.width / 2,
    centerY: () => canvas.height / 2,

    getActive: () => canvas.getActiveObject(),

    generatePhotoSlotKey: () => 'photo_' + Date.now(),

    /**
     * Apply universal selectable behavior
     */
    makeSelectable(obj, options = {}) {
        const defaults = {
            selectable: true,
            hasControls: true,
            hasBorders: true,
            lockRotation: false,
            lockScalingFlip: true,
            cornerStyle: 'circle',
            cornerColor: '#2563eb',
            transparentCorners: false,
            borderColor: '#2563eb',
            padding: 5,
        };

        obj.set({ ...defaults, ...options });

        if (!canvas.getObjects().includes(obj)) {
            canvas.add(obj);
        }

        canvas.setActiveObject(obj);
        canvas.requestRenderAll();
        return obj;
    },

    /**
     * Polygon point generator
     */
    polygonPoints(radius, sides) {
        const pts = [];
        const step = (Math.PI * 2) / sides;

        for (let i = 0; i < sides; i++) {
            const angle = step * i - Math.PI / 2;
            pts.push({
                x: radius * Math.cos(angle),
                y: radius * Math.sin(angle)
            });
        }
        return pts;
    }
};


/* =========================================================
   📦 TEMPLATE JSON LOADING (SAFE)
   ========================================================= */

if (window.templateData?.json) {
    try {
        const parsed = JSON.parse(window.templateData.json);

        canvas.loadFromJSON(parsed, () => {
            canvas.getObjects().forEach(obj => {
                if (obj.isPhotoSlot) {
                    obj.set({
                        fill: 'transparent',
                        stroke: '#2563eb',
                        strokeWidth: 2,
                        strokeDashArray: [6, 4]
                    });

                    createUploadInput(obj);
                }
            });

            canvas.requestRenderAll();
        });

    } catch (err) {
        console.error('Template JSON load failed', err);
    }
}


/* =========================================================
   ➕ SHAPE ADDERS (REUSABLE)
   ========================================================= */

function addRect() {
    Helpers.makeSelectable(new fabric.Rect({
        left: Helpers.centerX(),
        top: Helpers.centerY(),
        width: 200,
        height: 150,
        fill: '#e5e7eb',
        stroke: '#111',
        strokeWidth: 2,
        originX: 'center',
        originY: 'center'
    }));
}

function addCircle() {
    Helpers.makeSelectable(new fabric.Circle({
        left: Helpers.centerX(),
        top: Helpers.centerY(),
        radius: 110,
        fill: '#e5e7eb',
        stroke: '#111',
        strokeWidth: 2,
        originX: 'center',
        originY: 'center'
    }));
}

function addPolygon(sides, radius = 110) {
    Helpers.makeSelectable(new fabric.Polygon(
        Helpers.polygonPoints(radius, sides),
        {
            left: Helpers.centerX(),
            top: Helpers.centerY(),
            fill: '#e5e7eb',
            stroke: '#111',
            strokeWidth: 2,
            originX: 'center',
            originY: 'center'
        }
    ));
}


/* =========================================================
   🧩 OBJECT ACTIONS
   ========================================================= */

function duplicateActiveObject() {
    const obj = Helpers.getActive();
    if (!obj) return alert('Select an object to duplicate');

    obj.clone(clone => {
        clone.set({
            left: obj.left + 20,
            top: obj.top + 20
        });

        canvas.add(clone);
        canvas.setActiveObject(clone);
        canvas.requestRenderAll();
    });
}


/* =========================================================
   🖱 BUTTON EVENT BINDINGS
   ========================================================= */

DOM.addRectBtn.onclick = addRect;
DOM.addCircleBtn.onclick = addCircle;
DOM.addHexagonalBtn.onclick = () => addPolygon(6);
DOM.addOcatagonalBtn.onclick = () => addPolygon(8, 100);
DOM.addPentagonBtn.onclick = () => addPolygon(5);
DOM.addTriangleBtn.onclick = () => addPolygon(3);

DOM.duplicateBtn.onclick = duplicateActiveObject;

DOM.deleteObjBtn.onclick = () => {
    const obj = Helpers.getActive();
    if (!obj) return alert('Select an object first');
    canvas.remove(obj);
    canvas.discardActiveObject();
    canvas.requestRenderAll();
};

DOM.bringFrontBtn.onclick = () => {
    const obj = Helpers.getActive();
    if (obj) obj.bringToFront();
    canvas.requestRenderAll();
};

DOM.sendBackBtn.onclick = () => {
    const obj = Helpers.getActive();
    if (obj) obj.sendToBack();
    canvas.requestRenderAll();
};


/* =========================================================
   📸 MARK PHOTO SLOT
   ========================================================= */

DOM.markPhotoSlotBtn.onclick = () => {
    const obj = Helpers.getActive();
    if (!obj) return alert('Select a shape or SVG first');
    if (obj.isPhotoSlot) return;

    obj.isPhotoSlot = true;
    obj.slotKey = Helpers.generatePhotoSlotKey();

    obj.set({
        stroke: '#16a34a',
        strokeWidth: 3,
        strokeDashArray: [6, 4]
    });

    canvas.requestRenderAll();
};


/* =========================================================
   💾 SAVE TEMPLATE
   ========================================================= */

DOM.saveTemplateBtn.onclick = async () => {
    const json = canvas.toJSON(['isPhotoSlot', 'slotKey']);

    const res = await fetch(window.saveTemplateLayoutUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            template_json: JSON.stringify(json)
        })
    });

    const data = await res.json();
    alert(data.success ? 'Template saved successfully!' : 'Save failed');
};


/* =========================================================
   🖼 SVG UPLOAD & ADD
   ========================================================= */

DOM.svgUploadInput.onchange = async (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('svg', file);

    const res = await fetch('/admin/design-templates/upload-svg', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    });

    const data = await res.json();
    if (!data.success) return alert('SVG upload failed');

    fabric.loadSVGFromURL(data.url, (objects, options) => {
        const svg = fabric.util.groupSVGElements(objects, options);

        const maxW = canvas.width * 0.6;
        const maxH = canvas.height * 0.6;
        const bounds = svg.getBoundingRect(true);
        const scale = Math.min(maxW / bounds.width, maxH / bounds.height);

        svg.set({
            left: Helpers.centerX(),
            top: Helpers.centerY(),
            originX: 'center',
            originY: 'center',
            scaleX: scale,
            scaleY: scale
        });

        svg.setCoords();
        Helpers.makeSelectable(svg);
    }, null, { crossOrigin: 'anonymous' });

    e.target.value = '';
};

//its not working
document.getElementById('lockBtn').addEventListener('click', () => {
    const obj = canvas.getActiveObject();
    if (!obj) return;
    obj.set({
        selectable: false,
        evented: false
    });
    canvas.requestRenderAll();
});

document.getElementById('unlockBtn').addEventListener('click', () => {
    const obj = canvas.getActiveObject();
    if (!obj) return;
    obj.set({
        selectable: true,
        evented: true
    });
    canvas.requestRenderAll();
});