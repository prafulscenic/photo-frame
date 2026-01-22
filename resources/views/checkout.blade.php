<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout Page</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fabric.js -->
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>

    @include('header')
<div class="container py-5">

          @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

<div class="container my-5">
  <div class="row g-4">

    <!-- LEFT: CUSTOMER DETAILS -->
    <div class="col-lg-7">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">

          <h4 class="fw-bold mb-4">
            <i class="bi bi-person-check me-2 text-success"></i>
            Billing Details
          </h4>

          <form method="POST" action="{{ route('checkout.store') }}" id="placeOrderForm">
            @csrf

            <!-- Hidden Fields -->
            <input type="hidden" name="frame_id" value="{{ $frame_id }}">
            <input type="hidden" name="frame_size" value="{{ $frame_size }}">
            <input type="hidden" name="frame_thickness" value="{{ $frame_thickness }}">
            <input type="hidden" name="quantity" value="{{ $quantity }}">
            <input type="hidden" name="price" value="{{ $price }}">
            <input type="hidden" name="uploaded_image_base64" id="uploaded_image_base64">
            <input type="hidden" name="final_image_base64" id="final_image_base64">

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Full Name</label>
                <input name="name" class="form-control" placeholder="John Doe" required>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Phone</label>
                <input name="phone" class="form-control" placeholder="+91 XXXXXXXX" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input name="email" type="email" class="form-control" placeholder="you@example.com" required>
            </div>

            <div class="mb-4">
              <label class="form-label">Shipping Address</label>
              <textarea name="address" class="form-control" rows="3" placeholder="Full delivery address" required></textarea>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">
              <i class="bi bi-bag-check me-2"></i>
              Place Order
            </button>
          </form>

        </div>
      </div>
    </div>

    <!-- RIGHT: ORDER SUMMARY -->
    <div class="col-lg-5">
      <div class="card shadow-sm border-0 position-sticky" style="top: 80px;">
        <div class="card-body p-4 bg-light">

          <h5 class="fw-bold mb-3">
            <i class="bi bi-receipt me-2 text-success"></i>
            Order Summary
          </h5>

          <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item d-flex justify-content-between">
              <span>Frame Size</span>
              <strong>{{ $frame_size }}</strong>
            </li>

            <li class="list-group-item d-flex justify-content-between">
              <span>Thickness</span>
              <strong>{{ $frame_thickness }} inch</strong>
            </li>

            <li class="list-group-item d-flex justify-content-between">
              <span>Quantity</span>
              <strong>{{ $quantity }}</strong>
            </li>
          </ul>

          <hr>

          <div class="d-flex justify-content-between fs-5 fw-bold text-success">
            <span>Total</span>
            <span>₹{{ $price }}</span>
          </div>

          <p class="text-muted small mt-2 mb-0">
            Inclusive of all taxes
          </p>

        </div>
      </div>
    </div>

  </div>
</div>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
{{-- <script src="{{asset('js/editor.js')}}"></script> --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('placeOrderForm');
    if (!form) return;

    form.addEventListener('submit', function () {

        const uploaded = sessionStorage.getItem('uploaded_image_base64');
        const finalImg = sessionStorage.getItem('final_image_base64');

        if (!uploaded || !finalImg) {
            alert('Design data missing. Please go back and re-edit.');
            return false;
        }

        document.getElementById('uploaded_image_base64').value = uploaded;
        document.getElementById('final_image_base64').value = finalImg;
    });
});
</script>


</body>
</html>
