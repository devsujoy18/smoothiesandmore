<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smoothies & More - Your Trusted Companion</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="img/favicon.ico" sizes="512x512" type="image/x-icon" />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;700;800&display=swap"
        rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @livewireStyles
    @laravelPWA
</head>

<body>

    <x-navbar />

    {{ $slot }}

    <x-footer-section />


    {{-- Order Modal --}}
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg" style="border-radius: var(--border-radius-lg);">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title w-100 text-center fw-bold" id="orderModalLabel"
                        style="font-size: 1.35rem; margin-top: 10px;">Customize & Add to Bag</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <livewire:order-modal-cart />

                    {{--
                    <p class="text-muted mb-4">Scan the QR code to complete your payment</p>

                    <div class="qr-code-box mx-auto mb-4" style="width: 200px; height: 200px;">
                        <img src="{{ asset('img/qr.png') }}" alt="QR Code" class="img-fluid rounded">
                    </div>

                    <div class="phone-number mb-2">
                        <a href="tel:+919147759811"
                            class="text-decoration-none d-flex align-items-center justify-content-center gap-2"
                            style="font-size: 1.5rem; font-weight: 700; color: var(--primary-color);">
                            <i class="bi bi-telephone-fill"></i>
                            +91 9147759811
                        </a>
                    </div>
                    <p class="small text-muted">Tap the number to call directly</p>
                    --}}
                </div>
                <div class="modal-footer border-0 justify-content-end pt-0 pb-4">
                    <button type="button" class="btn btn-outline-dark px-4 py-2" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Cart Modal --}}
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg" style="border-radius: var(--border-radius-lg);">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title w-100 text-center fw-bold" id="cartModalLabel"
                        style="font-size: 1.35rem; margin-top: 10px;">Your Shopping Bag</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <livewire:cart-modal />
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkoutAuthModal" tabindex="-1" aria-labelledby="checkoutAuthModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg" style="border-radius: var(--border-radius-lg);">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="checkoutAuthModalLabel">Verify to Continue</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div id="checkoutAuthAlert" class="alert d-none py-2"></div>
                    <form id="sendOtpForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="sendOtpBtn">Send OTP</button>
                    </form>

                    <form id="verifyOtpForm" class="mt-3 d-none">
                        @csrf
                        <input type="hidden" name="email">
                        <input type="hidden" name="phone">
                        <div class="mb-3">
                            <label class="form-label">Enter OTP</label>
                            <input type="text" maxlength="6" name="otp" class="form-control" required>
                            <div class="form-text">OTP expires in 5 minutes.</div>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="verifyOtpBtn">Verify OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Install pwa button-->
        <button id="installPwaBtn" style="display: none;">
            <i class="fa fa-download"></i> Install App
        </button>
        
        <div id="ios-install-banner" class="ios-banner hidden">
            <div class="ios-banner-content">
                <div class="ios-banner-header">
                    <img src="/images/icons/icon-192x192.png" alt="App Icon" class="app-icon-mini">
                    <div class="text-group">
                        <span class="app-title">Install Sweton Speaker</span>
                        <span class="app-subtitle">Add to your home screen</span>
                    </div>
                    <button id="close-ios-banner" class="close-btn">&times;</button>
                </div>
                <div class="ios-banner-body">
                    <p>1. Tap the <strong>Share</strong> button <img src="https://img.icons8.com" class="inline-icon"> in the browser bar.</p>
                    <p>2. Scroll down and select <strong>"Add to Home Screen"</strong> <img src="https://img.icons8.com" class="inline-icon">.</p>
                </div>
            </div>
        </div>

        <style>
            #installPwaBtn {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1000;
                padding: 8px 20px;
                border-radius: 10px;
                background: rgb(220, 38, 38);
                color: white;
                border: none;
                box-shadow: 0 3px 8px rgba(0,0,0,0.3);
                font-weight: 500;
            }
            @media (max-width: 768px) {
                #installPwaBtn {
                    bottom: 15px;
                    right: 15px;
                    font-size: 14px;
                    padding: 10px 16px;
                }
            }
        </style>
        <style>
            /* Global Install Button */
            #installPwaBtn {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1000;
                padding: 12px 24px;
                border-radius: 50px;
                background: #dc2626; /* Your brand color */
                color: white;
                border: none;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                font-weight: 600;
            }
        
            /* iOS Instructions Banner */
            .ios-banner {
                position: fixed;
                bottom: 30px;
                left: 50%;
                transform: translateX(-50%);
                width: 90%;
                max-width: 400px;
                background: #ffffff;
                border-radius: 18px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                z-index: 9999;
                padding: 16px;
                border: 1px solid #e5e7eb;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica;
            }
            .ios-banner.hidden { display: none; }
            .ios-banner-header { display: flex; align-items: center; margin-bottom: 12px; position: relative; }
            .app-icon-mini { width: 45px; height: 45px; border-radius: 10px; margin-right: 12px; }
            .app-title { display: block; font-weight: 700; color: #111; font-size: 16px; }
            .app-subtitle { display: block; font-size: 13px; color: #6b7280; }
            .close-btn { position: absolute; right: 0; top: 0; background: none; border: none; font-size: 24px; color: #9ca3af; cursor: pointer; }
            .ios-banner-body p { font-size: 14px; color: #374151; margin: 8px 0; display: flex; align-items: center; }
            .inline-icon { width: 20px; height: 20px; margin: 0 4px; vertical-align: middle; }
            
            /* Arrow pointing to Safari share button */
            .ios-banner::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                border-width: 10px 10px 0;
                border-style: solid;
                border-color: #ffffff transparent transparent;
            }
        </style>

    @livewireScripts

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-order-modal', () => {
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('orderModal'));
                modal.show();
            });

            Livewire.on('show-cart-modal-js', () => {
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('cartModal'));
                modal.show();
            });

            Livewire.on('open-checkout-auth-modal', () => {
                const sendForm = document.getElementById('sendOtpForm');
                const verifyForm = document.getElementById('verifyOtpForm');
                const alertBox = document.getElementById('checkoutAuthAlert');

                if (sendForm && verifyForm) {
                    sendForm.reset();
                    verifyForm.reset();
                    sendForm.classList.remove('d-none');
                    verifyForm.classList.add('d-none');
                }
                if (alertBox) {
                    alertBox.className = 'alert d-none py-2';
                    alertBox.textContent = '';
                }

                const authModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('checkoutAuthModal'));
                authModal.show();
            });
        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || document.querySelector('#sendOtpForm input[name="_token"]')?.value;
            const sendForm = document.getElementById('sendOtpForm');
            const verifyForm = document.getElementById('verifyOtpForm');
            const alertBox = document.getElementById('checkoutAuthAlert');
            const sendBtn = document.getElementById('sendOtpBtn');
            const verifyBtn = document.getElementById('verifyOtpBtn');

            const showAlert = (message, type = 'info') => {
                alertBox.className = `alert alert-${type} py-2`;
                alertBox.textContent = message;
                alertBox.classList.remove('d-none');
            };

            const hideAlert = () => {
                alertBox.classList.add('d-none');
                alertBox.textContent = '';
            };

            if (sendForm) {
                sendForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    hideAlert();
                    sendBtn.disabled = true;

                    const formData = new FormData(sendForm);
                    const payload = {
                        email: String(formData.get('email') || '').trim().toLowerCase(),
                        phone: String(formData.get('phone') || '').trim(),
                    };

                    try {
                        const response = await fetch('{{ route('checkout.otp.send') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                            },
                            body: JSON.stringify(payload),
                        });

                        const result = await response.json();
                        if (!response.ok) {
                            throw new Error(result?.message || 'Unable to send OTP.');
                        }

                        verifyForm.querySelector('input[name="email"]').value = payload.email;
                        verifyForm.querySelector('input[name="phone"]').value = payload.phone;
                        sendForm.classList.add('d-none');
                        verifyForm.classList.remove('d-none');
                        showAlert(result.message || 'OTP sent successfully.', 'success');
                    } catch (error) {
                        showAlert(error.message || 'Unable to send OTP.', 'danger');
                    } finally {
                        sendBtn.disabled = false;
                    }
                });
            }

            if (verifyForm) {
                verifyForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    hideAlert();
                    verifyBtn.disabled = true;

                    const formData = new FormData(verifyForm);
                    const payload = {
                        email: String(formData.get('email') || '').trim().toLowerCase(),
                        phone: String(formData.get('phone') || '').trim(),
                        otp: String(formData.get('otp') || '').trim(),
                    };

                    try {
                        const response = await fetch('{{ route('checkout.otp.verify') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                            },
                            body: JSON.stringify(payload),
                        });

                        const result = await response.json();
                        if (!response.ok) {
                            throw new Error(result?.message || 'OTP verification failed.');
                        }

                        showAlert(result.message || 'OTP verified.', 'success');
                        window.location.href = result.redirect || '{{ route('checkout.addresses.index') }}';
                    } catch (error) {
                        showAlert(error.message || 'OTP verification failed.', 'danger');
                    } finally {
                        verifyBtn.disabled = false;
                    }
                });
            }
        });
    </script>

    <!--Install PWA Script-->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const installBtn = document.getElementById('installPwaBtn');
            const iosBanner = document.getElementById('ios-install-banner');
            const closeIosBtn = document.getElementById('close-ios-banner');
            let deferredPrompt;
    
            // 1. Detection Functions
            const isIos = () => {
                const userAgent = window.navigator.userAgent.toLowerCase();
                // Detect iPhone, iPad, or iPod
                return /iphone|ipad|ipod/.test(userAgent);
            };
            
            const isStandalone = () => {
                // Check if the app is already running as an installed PWA
                return ('standalone' in window.navigator && window.navigator.standalone) 
                       || window.matchMedia('(display-mode: standalone)').matches;
            };
    
            // 2. iOS Logic
            // This will show EVERY TIME the page loads if it's an iPhone and not installed
            if (isIos() && !isStandalone()) {
                iosBanner.classList.remove('hidden');
            }
    
            // Close button just hides it for this specific page view
            closeIosBtn.onclick = () => {
                iosBanner.classList.add('hidden');
            };
    
            // 3. Android/Chrome Logic (beforeinstallprompt)
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                // Only show the Android button if we aren't on iOS
                if(!isIos()) {
                    installBtn.style.display = 'block';
                }
    
                installBtn.addEventListener('click', async () => {
                    if (deferredPrompt) {
                        deferredPrompt.prompt();
                        const { outcome } = await deferredPrompt.userChoice;
                        deferredPrompt = null;
                        installBtn.style.display = 'none';
                    }
                });
            });
        });
</script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initAutocomplete&libraries=places&v=weekly" defer></script>
    <script>
        function initAutocomplete() {
            window.autocomplete = new google.maps.places.Autocomplete(
                document.getElementById('address-search'),
                {
                    types: ['address'],
                    fields: ['address_components', 'formatted_address'],
                    componentRestrictions: { country: 'in' }
                }
            );
            autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            const place = autocomplete.getPlace();
            const components = place.address_components;
            
            let addressLine1 = '';
            let addressLine2 = '';
            let city = '';
            let state = '';
            let postalCode = '';
            let country = 'India';

            for (const component of components) {
                const types = component.types;
                if (types.includes('street_number')) {
                    addressLine1 = component.long_name + ' ' + addressLine1;
                } else if (types.includes('route')) {
                    addressLine1 += component.long_name;
                } else if (types.includes('sublocality_level_1') || types.includes('sublocality')) {
                    addressLine2 = component.long_name;
                } else if (types.includes('locality')) {
                    city = component.long_name;
                } else if (types.includes('administrative_area_level_1')) {
                    state = component.long_name;
                } else if (types.includes('postal_code')) {
                    postalCode = component.long_name;
                } else if (types.includes('country')) {
                    country = component.long_name;
                }
            }

            // Validate Kolkata pincodes (7000XX)
            if (!postalCode || !postalCode.startsWith('7000')) {
                alert('Please select an address within Kolkata (pincode starting with 7000)');
                return;
            }

            document.getElementById('address_line_1').value = addressLine1.trim();
            document.getElementById('address_line_2').value = addressLine2.trim();
            document.getElementById('city').value = city;
            document.getElementById('state').value = state;
            document.getElementById('postal_code').value = postalCode;
            document.getElementById('country').value = country;
        }
    </script>

    
</body>

</html>

