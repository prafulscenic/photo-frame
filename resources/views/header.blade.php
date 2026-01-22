    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div class="container">
            <!-- Logo on the left -->
            <a class="navbar-brand" href="#">
                <h1>PhotoFrame</h1>
            </a>

            <!-- Toggle button for mobile view -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Page links on the right -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active fs-5" href="{{ route('frontend.frame-selector') }}">Frame</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5" href="{{ route('admin.frames.create') }}">Create</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5" href="{{ route('admin.frames.list') }}">List</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fs-5" href="{{ route('frame.order.list') }}">Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>