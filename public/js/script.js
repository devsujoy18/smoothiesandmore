const navbar = document.querySelector('.navbar');

document.addEventListener('DOMContentLoaded', () => {

    const cardSwiperConfig = {
        slidesPerView: 1.2,
        spaceBetween: 20,
        grabCursor: true,
        breakpoints: {
            576: { slidesPerView: 2.2 },
            768: { slidesPerView: 3 },
            1200: { slidesPerView: 3 }
        }
    };

    const wideCardSwiperConfig = {
        slidesPerView: 1.2,
        spaceBetween: 20,
        grabCursor: true,
        breakpoints: {
            576: { slidesPerView: 2.2 },
            768: { slidesPerView: 3 },
            1200: { slidesPerView: 3 }
        }
    };

    // 🔥 Auto detect all swipers on page
    document.querySelectorAll('.swiper').forEach(swiperEl => {

        const isWide = swiperEl.dataset.type === "wide"; // optional if you want type based config

        const config = isWide ? { ...wideCardSwiperConfig } : { ...cardSwiperConfig };

        new Swiper(swiperEl, config);
    });


    // Initialize Swipers for individual categories with scoped navigation
    const categories = ['juices', 'smoothies', 'wraps', 'teas', 'coffee', 'sandwich', 'pasta', 'soup', 'salad', 'toast', 'burger', 'paratha', 'maggi', 'mocktails'];

    categories.forEach(cat => {
        const isWide = ['wraps', 'teas', 'coffee', 'sandwich', 'pasta', 'soup', 'salad'].includes(cat);
        const config = isWide ? { ...wideCardSwiperConfig } : { ...cardSwiperConfig };

        // Scope navigation to this specific swiper
        config.navigation = {
            nextEl: `#${cat}-swiper .swiper-button-next`,
            prevEl: `#${cat}-swiper .swiper-button-prev`,
        };

        new Swiper(`#${cat}-swiper`, config);
    });



    // Initialize Swiper for Testimonials
    new Swiper('#testimonials-swiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        grabCursor: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });

    // Navbar Scroll Effect
    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Feature Tabs Logic
    const tabsContainer = document.getElementById('feature-tabs-container');
    const tabs = document.querySelectorAll('.feature-tab');
    const contents = document.querySelectorAll('.feature-content');

    if (tabsContainer) {
        tabs.forEach(tab => {
            tab.addEventListener('mouseenter', () => { // Change on hover
                const featureKey = tab.getAttribute('data-feature');
                const targetContent = document.getElementById(`content-${featureKey}`);

                if (targetContent) {
                    // Update active tab state
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    // process transition
                    // First fade out all visible
                    contents.forEach(content => {
                        if (!content.classList.contains('d-none')) {
                            content.style.opacity = 0; // fade out
                            setTimeout(() => {
                                content.classList.add('d-none');
                                content.classList.remove('d-flex');
                            }, 200);
                        }
                    });

                    // Fade in target after brief delay for smoothness
                    setTimeout(() => {
                        contents.forEach(c => {
                            if (c.id !== `content-${featureKey}`) {
                                c.classList.add('d-none');
                                c.classList.remove('d-flex');
                            }
                        });

                        targetContent.classList.remove('d-none');
                        targetContent.classList.add('d-flex');

                        // Force reflow
                        void targetContent.offsetWidth;

                        targetContent.style.opacity = 1; // fade in
                    }, 200);
                }
            });
        });
    }
});
