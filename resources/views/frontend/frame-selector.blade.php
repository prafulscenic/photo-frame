<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photo Frame Editor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fabric.js -->
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">


<style>
    .frame-thumb-wrapper {
    width: 100%;
    height: 300px;       /* SAME HEIGHT FOR ALL */
    display: flex;
    align-items: center;
    justify-content: center;
}

.frame-thumb {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;  /* no crop */
}

</style>
</head>
<body>

{{-- <div class="container py-5">

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
                               title="Text Color" value="#000000">
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

</div> --}}
@include('header')
{{-- <div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h3 class="fw-bold">Create Your Photo Frame</h3>
        <p class="text-muted">Upload an image, add text, and download your custom frame</p>
    </div>

    <!-- Main Layout: Left (Preview + Controls) + Right (Frames) -->
    <div class="row g-4" style="height: 80vh;">

        <!-- Left Column: Preview + Controls -->
        <div class="col-md-6 d-flex flex-column">

            <!-- Preview Card -->
            <div class="card shadow-sm mb-4 flex-shrink-0">
                <div class="card-body d-flex flex-column align-items-center">

                    <h6 class="fw-semibold text-center mb-3">Preview</h6>

                    <canvas id="canvas"
                            width="400"
                            height="400"
                            class="border rounded mb-3"
                            style="border:2px solid #333"></canvas>

                </div>
            </div>

            <!-- Controls Card -->
            <div class="card shadow-sm flex-shrink-0">
                <div class="card-body">

                    <div class="row g-3 align-items-end">

                        <!-- Upload -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Upload Image</label>
                            <input type="file" id="imageUpload" class="form-control" accept="image/*">
                        </div>

                        <!-- Add Text -->
                       <div class="col-12">
                            <label class="form-label fw-semibold mb-2">Text Options</label>

                            <div class="d-flex flex-wrap align-items-center gap-2">

                                <!-- Add Text Button -->
                                <button id="addTextBtn"
                                        class="btn btn-primary px-4">
                                    <i class="bi bi-plus-lg me-1"></i> Add Text
                                </button>

                                <!-- Text Color Picker -->
                                <div class="d-flex align-items-center gap-1">
                                    <small class="text-muted">Color</small>
                                    <input type="color"
                                        id="textColorPicker"
                                        class="form-control form-control-color"
                                        title="Text Color"
                                        value="#000000">
                                </div>

                                <!-- Font Family Select -->
                                <div class="flex-grow-1">
                                    <select id="fontFamilySelect" class="form-select">
                                        <option value="">Font Family</option>
                                        <option value="Poppins">Poppins</option>
                                        <option value="Playfair Display">Playfair Display</option>
                                        <option value="Dancing Script">Dancing Script</option>
                                    </select>
                                </div>

                            </div>
                        </div>


                        <!-- Download -->
                        <div class="col-12">
                            <button id="downloadBtn" class="btn btn-success w-100">
                                Download PNG
                            </button>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        <!-- Right Column: Frames Section -->
        <div class="col-md-6 d-flex flex-column" style="height: 80vh;"> <!-- constrain height -->

            <div class="card shadow-sm flex-grow-1 d-flex flex-column" style="height: 100%;">
                <div class="card-body d-flex flex-column p-3" style="height: 100%;">

                    <h6 class="fw-semibold mb-3">Choose a Frame</h6>

                    <!-- Scrollable Frames -->
                    <div id="frameList" class="row g-3" style="overflow-y: auto; flex-grow: 1; height: 0;">
                        <!-- JS will populate frames here -->
                    </div>

                </div>
            </div>

        </div>




    </div>

</div> --}}
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-4">
        <h3 class="fw-bold">Choose Your Frame</h3>
        <p class="text-muted">Select a frame to start customizing</p>
    </div>

    <!-- Frames Grid -->
    <div class="row g-3" id="frameList">
        <!-- JS will populate frames here -->
    </div>

</div>
<div class="modal fade"
     id="frameEditorModal"
     tabindex="-1"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     data-bs-focus="false">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- Modal Header -->
            <div class="modal-header px-4 py-3 border-bottom bg-white">
                <div>
                    <h5 class="modal-title fw-bold mb-0">
                        Customize Your Frame
                    </h5>
                    <small class="text-muted">
                        Upload image, add text, and export
                    </small>
                </div>

                <button type="button"
                        class="btn-close ms-auto"
                        data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-0">

                <div class="row g-0">

                    <!-- LEFT: Preview -->
                    <div class="col-md-7 bg-light">

                        <div class="h-100 d-flex flex-column align-items-center justify-content-center p-4">

                            <span class="badge bg-secondary-subtle text-secondary mb-3">
                                Live Preview
                            </span>

                            <div class="bg-white p-4 rounded-4 shadow-sm border">

                                <canvas id="canvas"
                                        width="400"
                                        height="400"
                                        class="border rounded-3"
                                        style="border:2px solid #333">
                                </canvas>

                            </div>

                        </div>

                    </div>

                    <!-- RIGHT: Controls -->
                    <div class="col-md-5">

                        <div class="h-100 d-flex flex-column p-4">

                            <!-- Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                                    Image
                                </label>

                                <input type="file"
                                       id="imageUpload"
                                       class="form-control"
                                       accept="image/*">
                            </div>

                            <!-- Text Options -->
                            <div class="mb-4">

                                <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                                    Text Customization
                                </label>

                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-body d-grid gap-3">

                                        <button id="addTextBtn"
                                                class="btn btn-primary w-100">
                                            Add Text
                                        </button>

                                        <div class="d-flex gap-2">

                                            <input type="color"
                                                   id="textColorPicker"
                                                   class="form-control form-control-color">

                                            <select id="fontFamilySelect"
                                                    class="form-select">
                                                <option value="">Font Family</option>
                                                <option value="Poppins">Poppins</option>
                                                <option value="Playfair Display">
                                                    Playfair Display
                                                </option>
                                                <option value="Dancing Script">
                                                    Dancing Script
                                                </option>
                                            </select>

                                        </div>

                                    </div>
                                </div>

                            </div>



                            <!-- Spacer -->
                            <div class="flex-grow-1"></div>

                            <!-- Download -->
                            <div class="pt-3 border-top">


                                <button id="saveOrderBtn"
                                        class="btn btn-success btn-lg w-100">
                                    Select Size & Thickness
                                </button>

                                <small class="text-muted d-block text-center mt-2">
                                    High-quality export
                                </small>

                            </div>
                            <!-- Download -->
                         


                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

{{-- preview and size & thickness selection modal --}}
{{-- <div class="modal fade" id="finalPreviewModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content rounded-4">

      <div class="modal-header">
        <h5 class="modal-title">Preview Your Frame</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row g-0">

          <!-- LEFT: Preview -->
           <div class="col-md-7 p-4 text-center border-end">
                <div id="previewWrapper" style="width:320px; margin:auto;">
                    <img id="finalPreviewImg"
                        src=""
                        alt="Final Frame Preview"
                        style="
                            width:100%;
                            height:auto;
                            display:block;
                            transition: transform 0.25s ease, filter 0.25s ease;
                        ">
                </div>
            </div>


          <!-- RIGHT: Form (UI only for now) -->
          <div class="col-md-5 p-4">

              <form id="orderForm">

                    <input name="name"
                            class="form-control mb-3"
                            placeholder="Name"
                            required>

                    <input name="email"
                            type="email"
                            class="form-control mb-3"
                            placeholder="Email"
                            required>

                    <select name="frame_size" class="form-select mb-3" required>
                        <option value="">Select Frame Size</option>
                        <option value="8x10" data-scale="0.9">8 × 10</option>
                        <option value="12x18" data-scale="1">12 × 18</option>
                        <option value="16x24" data-scale="1.1">16 × 24</option>
                    </select>

                    <select name="frame_thickness" class="form-select mb-4" required>
                        <option value="">Select Frame Thickness</option>
                        <option value="0.5" >0.5 inch</option>
                        <option value="1">1 inch</option>
                        <option value="1.5">1.5 inch</option>
                    </select>

                </form>


            <div class="d-flex gap-2">
              <button id="editDesignBtn"
                      class="btn btn-outline-secondary w-50">
                Edit Design
              </button>

             <button id="confirmSaveBtn"
                    class="btn btn-success w-50">
              Confirm (Check Data)
            </button>

            </div>

          </div>

        </div>
      </div>

    </div>
  </div>
</div> --}}

<div class="modal fade" id="finalPreviewModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content rounded-4 shadow">

      <!-- HEADER -->
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">
          Preview Your Frame
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body pt-2">
        <div class="row g-0">

          <!-- LEFT: PREVIEW -->
          <div class="col-md-7 p-4 text-center border-end bg-light">
            <div id="previewWrapper" class="mx-auto shadow-sm rounded-3 bg-white p-3"
                 style="width:320px;">
              <img id="finalPreviewImg"
                   src=""
                   alt="Final Frame Preview"
                   class="img-fluid rounded"
                   style="
                     display:block;
                     transition: transform 0.25s ease, filter 0.25s ease;
                   ">
            </div>

            <p class="text-muted small mt-3 mb-0">
              Final preview shown with selected frame options
            </p>
          </div>

          <!-- RIGHT: OPTIONS -->
          <div class="col-md-5 p-4 d-flex flex-column">

            <!-- OPTIONS FORM -->
            <form id="orderForm" class="mb-4">

              <div class="mb-3">
                <label class="form-label fw-semibold">
                  Frame Size
                </label>
                <select name="frame_size" class="form-select" required>
                  <option value="">Select Frame Size</option>
                  <option value="8x10" data-scale="0.9">8 × 10</option>
                  <option value="12x18" data-scale="1">12 × 18</option>
                  <option value="16x24" data-scale="1.1">16 × 24</option>
                </select>
              </div>

              <div>
                <label class="form-label fw-semibold">
                  Frame Thickness
                </label>
                <select name="frame_thickness" class="form-select" required>
                  <option value="">Select Frame Thickness</option>
                  <option value="0.5">0.5 inch</option>
                  <option value="1">1 inch</option>
                  <option value="1.5">1.5 inch</option>
                </select>
              </div>

            </form>

            <!-- PRICE -->
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-semibold">Price</span>
                  <span class="fw-bold fs-4 text-success" id="framePrice">
                    ₹999
                  </span>
                </div>
                <small class="text-muted">
                  Inclusive of frame & customization
                </small>
              </div>
            </div>

            <!-- QUANTITY -->
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body py-3">

                <label class="form-label fw-semibold mb-2">
                  Quantity
                </label>

                <div class="d-flex align-items-center justify-content-between">

                  <div class="btn-group" role="group">
                    <button type="button"
                            class="btn btn-outline-secondary"
                            id="qtyMinus">
                      −
                    </button>

                    <input type="text"
                           id="frameQty"
                           class="form-control text-center fw-semibold"
                           value="1"
                           readonly
                           style="max-width:70px">

                    <button type="button"
                            class="btn btn-outline-secondary"
                            id="qtyPlus">
                      +
                    </button>
                  </div>

                  <span class="text-muted small">
                    Pieces
                  </span>

                </div>
              </div>
            </div>

            <!-- ACTION BUTTONS -->
            <form method="GET" action="{{ route('checkout') }}" id="checkoutForm" class="mt-auto">

              <input type="hidden" name="frame_id" id="checkout_frame_id">
              <input type="hidden" name="frame_size" id="checkout_frame_size">
              <input type="hidden" name="frame_thickness" id="checkout_frame_thickness">
              <input type="hidden" name="quantity" id="checkout_quantity">
              <input type="hidden" name="price" id="checkout_price">

              <div class="d-flex gap-2">
                <button type="button"
                        id="editDesignBtn"
                        class="btn btn-outline-secondary w-50">
                  Edit Design
                </button>

                <button type="submit"
                        class="btn btn-success w-50 fw-semibold">
                  Checkout
                </button>
              </div>

            </form>

          </div>
        </div>

        <!-- DESCRIPTION -->
        <div class="row border-top mt-4 pt-4">
          <div class="col">
            <h6 class="fw-bold mb-2">
              Product Description
            </h6>
            <p class="text-muted small mb-0 lh-lg">
              This premium customized photo frame is designed to preserve your
              most valuable memories with elegance and durability. Crafted with
              high-quality materials, the frame offers a clean and modern finish
              that blends seamlessly with any interior décor.
              <br><br>
              You can customize the frame by choosing the perfect size and
              thickness to match your preference. The preview shown represents
              your final design, including image placement, cropping, and text
              alignment. Each frame is carefully produced to maintain color
              accuracy and sharp image quality.
            </p>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('js/editor.js')}}"></script>


</body>
</html>
