<div class="container">
    <h2 class="section-title">Our Menu</h2>
    <p class="section-subtitle">Freshly prepared,  just for you.</p>

    @foreach($categories as $category)
        <h3 style="margin-bottom: 0;" class="category-title text-primary-custom mt-5">{{ $category->name }}</h3>
        @if( $category->name == "Smoothies" )
        <p class="text-center">( Add Protein, Vitamin and other boosters for Muscle Repair and Whole Day Energy )</p>
        @endif

        <div class="swiper" id="{{ $category->slug }}-swiper">
            <div class="swiper-wrapper" id="{{ $category->slug }}-wrapper">
                @foreach($category->products as $product)
                <div class="swiper-slide">
                    <livewire:menu-item :product="$product" :key="$product->slug"  />
                </div>
                @endforeach
            </div>
            
            <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
        </div>
    @endforeach
</div>