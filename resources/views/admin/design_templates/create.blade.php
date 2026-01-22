<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Frame Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"    rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>

</head>
<body>

    @include('header')

    <div class="container">

        <div class="container-fluid py-4">

            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold">Create Design Template</h4>

                <a href="{{ route('admin.design-templates.index') }}"
                class="btn btn-outline-secondary btn-sm">
                    Back
                </a>
            </div>

            {{-- Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <form method="POST" action="{{ route('admin.design-templates.store') }}">
                        @csrf

                        <div class="row">

                            {{-- Template Name --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Template Name</label>
                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="e.g. Birthday Card, Double Frame"
                                    required>
                            </div>

                            {{-- Template Type --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Template Type</label>
                                <select name="type"
                                        class="form-select"
                                        required>
                                    <option value="frame">Frame</option>
                                    <option value="card">Card</option>
                                </select>
                            </div>

                            {{-- Category --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Category <span class="text-muted">(optional)</span>
                                </label>
                                <input type="text"
                                    name="category"
                                    class="form-control"
                                    placeholder="birthday, anniversary, collage">
                            </div>

                            {{-- Canvas Width --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Canvas Width (px)</label>
                                <input type="number"
                                    name="canvas_width"
                                    class="form-control"
                                    value="600"
                                    min="100"
                                    required>
                            </div>

                            {{-- Canvas Height --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Canvas Height (px)</label>
                                <input type="number"
                                    name="canvas_height"
                                    class="form-control"
                                    value="600"
                                    min="100"
                                    required>
                            </div>

                            {{-- Template JSON (TEMP for now) --}}
                            <div class="col-12 mb-4">
                                <label class="form-label fw-semibold">
                                    Template JSON
                                    <span class="text-muted">(temporary)</span>
                                </label>

                                <textarea name="template_json"
                                        class="form-control"
                                        rows="6"
                                        placeholder="Paste template JSON here for now"
                                        ></textarea>

                                <small class="text-muted">
                                    This will be auto-generated later from the editor.
                                </small>
                            </div>

                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.design-templates.index') }}"
                            class="btn btn-light">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                Save Template
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>

</body>
</html>