<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand text-primary-custom" href="#"><img src="{{ asset('img/logo2.png') }}"></a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#menu">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact Us</a>
                </li>

            </ul>
            <!-- Desktop Buttons (Hidden on Mobile) -->
            <div class="d-md-flex align-items-center gap-3 ms-3 mob-center">
                <a href="https://maps.app.goo.gl/tLpiSSsnSAwdQzYW8" target="_blank"><i style="font-size: 24px;" class="bi bi-geo-alt-fill text-white me-1"></i></a>
                <a href="tel:+919147759811" class="btn btn-primary rounded-pill px-4 fw-bold"><i
                        class="bi bi-telephone-fill text-white me-1"></i>+91 9147759811</a>
                <livewire:cart-bag-icon />
                <img style="width:60px" src="{{ asset('img/qr.png') }}" alt="QR Code" class="img-fluid rounded">
            </div>
        </div>
    </div>
</nav>
