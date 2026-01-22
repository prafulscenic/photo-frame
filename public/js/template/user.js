document.addEventListener('DOMContentLoaded', () => {

    const canvasEl = document.getElementById('userCanvas');
    if (!canvasEl) return;
    if (!window.templateJson) {
        console.error('Template JSON not found');
        return;
    }

    const canvas = new fabric.Canvas('userCanvas', {
        selection: false,
        preserveObjectStacking: true
    });

    // Load template
    canvas.loadFromJSON(window.templateJson, () => {
        canvas.getObjects().forEach(obj => {
            obj.selectable = false;
            obj.evented = false;

            if (obj.isPhotoSlot) {
                obj.set({
                    stroke: '#2563eb',
                    strokeWidth: 2,
                    strokeDashArray: [6, 4]
                });
                createUploadInput(obj);
            }
        });
        canvas.requestRenderAll();
    }, (o, object) => {
        object.isPhotoSlot = o.isPhotoSlot;
        object.slotKey = o.slotKey;
    });

    /* -------------------------------
       Upload input per slot
    -------------------------------- */
    function createUploadInput(slotObj) {
        const container = document.getElementById('photoUploadList');
        if (!container) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'mb-3';

        const label = document.createElement('label');
        label.className = 'form-label small fw-semibold';
        label.innerText = 'Upload Photo';

        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.className = 'form-control form-control-sm';

        input.addEventListener('change', (e) => {
            placeImageInSlot(e.target.files[0], slotObj);
        });

        wrapper.appendChild(label);
        wrapper.appendChild(input);
        container.appendChild(wrapper);
    }

    /* -------------------------------
       Place image into slot (replace previous)
    -------------------------------- */
    function placeImageInSlot(file, slot) {
        if (!file) return;

        // ✅ Remove any existing image for this slot
        canvas.getObjects().forEach(obj => {
            if (obj.slotKey === slot.slotKey && obj.isUserImage) {
                canvas.remove(obj);
            }
        });

        const reader = new FileReader();
        reader.onload = () => {
            fabric.Image.fromURL(reader.result, img => {

                // Clone slot as clipPath
                slot.clone(clone => {
                    clone.set({
                        absolutePositioned: true,
                        originX: 'center',
                        originY: 'center'
                    });

                    // Set image properties
                    img.set({
                        left: slot.left,
                        top: slot.top,
                        originX: 'center',
                        originY: 'center',
                        clipPath: clone,
                        selectable: true,
                        hasControls: true,
                        lockRotation: true,
                        lockSkewingX: true,
                        lockSkewingY: true,
                        isUserImage: true,
                        slotKey: slot.slotKey
                    });

                    // Scale image to roughly fill slot
                    const slotBounds = slot.getBoundingRect();
                    const scaleX = slotBounds.width / img.width;
                    const scaleY = slotBounds.height / img.height;
                    const scale = Math.max(scaleX, scaleY);
                    img.scale(scale);

                    canvas.add(img);
                    canvas.moveTo(img, canvas.getObjects().indexOf(slot) + 1);
                    canvas.setActiveObject(img);
                    canvas.requestRenderAll();

                });

            }, { crossOrigin: 'anonymous' });
        };

        reader.readAsDataURL(file);
    }

});
