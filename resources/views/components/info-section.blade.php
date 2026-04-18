<section class="info-section">
        <div class="container">
            <div class="text-center mb-5">
                <!-- Icon Placeholder -->
                <div
                    style="width: 40px; height: 40px; background: #f7cde4; opacity: 1; border-radius: 50%; margin: 0 auto 10px;">
                    <i style="color: #9333ea; line-height: 39px;" class="bi bi-star"></i>
                </div>
                <h2 class="section-title">Keep everything <br>in <span class="highlight-text">one place</span></h2>
                <p class="section-subtitle">Everything you need for a healthy lifestyle, delivered fast to your door.
                </p>

                <div class="feature-tabs" id="feature-tabs-container">
                    <div class="feature-tab active" data-feature="offer"><i class="bi bi-tag me-2"></i>Offer</div>
                    <div class="feature-tab" data-feature="ingredients"><i class="bi bi-basket me-2"></i>Fresh
                        Ingredients</div>
                    <div class="feature-tab" data-feature="delivery"><i class="bi bi-truck me-2"></i>Quick Delivery
                    </div>
                    <div class="feature-tab" data-feature="blends"><i class="bi bi-cup-straw me-2"></i>Custom Blends
                    </div>
                    <div class="feature-tab" data-feature="nutrition"><i class="bi bi-heart-pulse me-2"></i>Nutritional
                        Info</div>
                    <div class="feature-tab" data-feature="eco"><i class="bi bi-recycle me-2"></i>Eco-Friendly</div>

                </div>
            </div>

            <div class="info-card position-relative">
                <!-- 6. Offer -->
                <div class="feature-content  d-flex flex-column flex-md-row gap-5 align-items-center w-100"
                    id="content-offer">
                    <div class="info-icon text-left w-100 w-md-50">
                        <i class="bi bi-tag text-danger" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Special 20% Off</h4>
                        <div class="text-muted small">
                            <p>Combo Lunch Specials Available</p>
                            <p>Free Home Delivery on Order of 299/- & Above</p>
                        </div>
                        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#orderModal">Order
                            Now</button>
                    </div>
                    <div class="flex-grow-1 w-100 w-md-50">
                        <a data-bs-toggle="modal" data-bs-target="#orderModal" href="#"><img src="{{ asset('img/off.jpg') }}"
                                alt="Special Offer" class="img-fluid rounded-4 w-100"
                                style="object-fit: cover; height: 300px;"></a>
                    </div>
                </div>

                <!-- 1. Fresh Ingredients -->
                <div class="feature-content d-none flex-column flex-md-row gap-5 align-items-center w-100"
                    id="content-ingredients">
                    <div class="info-icon text-left w-100 w-md-50">
                        <i class="bi bi-basket text-danger" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Fresh Ingredients</h4>
                        <div class="text-muted small">
                            <p>We source the freshest fruits and vegetables to ensure every sip is full of flavor and
                                nutrition.</p>
                        </div>
                    </div>
                    <div class="flex-grow-1 w-100 w-md-50">
                        <img src="{{ asset('img/300.webp') }}" alt="Fresh Ingredients" class="img-fluid rounded-4 w-100"
                            style="object-fit: cover; height: 300px;">
                    </div>
                </div>

                <!-- 2. Quick Delivery -->
                <div class="feature-content d-none d-flex flex-column flex-md-row gap-5 align-items-center w-100"
                    id="content-delivery">
                    <div class="info-icon text-left w-100 w-md-50">
                        <i class="bi bi-truck text-danger" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Quick Delivery</h4>
                        <div class="text-muted small">
                            <p>Your order arrives fresh and fast�because healthy choices should never wait!</p>
                        </div>
                    </div>
                    <div class="flex-grow-1 w-100 w-md-50">
                        <img src="{{ asset('img/300 (1).webp') }}" alt="Quick Delivery" class="img-fluid rounded-4 w-100"
                            style="object-fit: cover; height: 300px;">
                    </div>
                </div>

                <!-- 3. Custom Blends -->
                <div class="feature-content d-none d-flex flex-column flex-md-row gap-5 align-items-center w-100"
                    id="content-blends">
                    <div class="info-icon text-left w-100 w-md-50">
                        <i class="bi bi-cup-straw text-danger" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Custom Blends</h4>
                        <div class="text-muted small">
                            <p>Build your smoothie just the way you like it�choose your base, fruits, and toppings.</p>
                        </div>
                    </div>
                    <div class="flex-grow-1 w-100 w-md-50">
                        <img src="{{ asset('img/300 (2).webp') }}" alt="Custom Blends" class="img-fluid rounded-4 w-100"
                            style="object-fit: cover; height: 300px;">
                    </div>
                </div>

                <!-- 4. Nutritional Info -->
                <div class="feature-content d-none d-flex flex-column flex-md-row gap-5 align-items-center w-100"
                    id="content-nutrition">
                    <div class="info-icon text-left w-100 w-md-50">
                        <i class="bi bi-heart-pulse text-danger" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Nutritional Info</h4>
                        <div class="text-muted small">
                            <p>Complete nutritional breakdown for every item. Track your health goals with confidence.
                            </p>
                        </div>
                    </div>
                    <div class="flex-grow-1 w-100 w-md-50">
                        <img src="{{ asset('img/300 (3).webp') }}" alt="Nutritional Info" class="img-fluid rounded-4 w-100"
                            style="object-fit: cover; height: 300px;">
                    </div>
                </div>

                <!-- 5. Eco-Friendly -->
                <div class="feature-content d-none d-flex flex-column flex-md-row gap-5 align-items-center w-100"
                    id="content-eco">
                    <div class="info-icon text-left w-100 w-md-50">
                        <i class="bi bi-recycle text-danger" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Eco-Friendly</h4>
                        <div class="text-muted small">
                            <p>We use biodegradable cups, eco-safe packaging, and sustainable sourcing practices.</p>
                        </div>
                    </div>
                    <div class="flex-grow-1 w-100 w-md-50">
                        <img src="{{ asset('img/300 (4).webp') }}" alt="Eco-Friendly" class="img-fluid rounded-4 w-100"
                            style="object-fit: cover; height: 300px;">
                    </div>
                </div>



            </div>
        </div>
    </section>