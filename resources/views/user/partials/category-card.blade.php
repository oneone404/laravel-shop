<a href="{{ route('category.index', ['slug' => $category->slug]) }}" class="product-card">
  <div class="product-image-wrapper image-wrapper">
    <img src="{{ asset('images/loader.svg') }}" alt="Loading..." class="image-loader">
    <img src="{{ $category->thumbnail }}" alt="{{ $category->name }}" class="product-image" loading="lazy" decoding="async" />
  </div>

  <h2 class="product-name">{{ $category->name }}</h2>

  <div class="product-stats">
    <span class="product-badge">
      ĐÃ BÁN <span class="badge-number-sold">{{ number_format($category->soldCount + 50) }}</span>
    </span>
    <span class="divider">|</span>
    <span class="product-badge">
      CÒN LẠI <span class="badge-number-available">{{ number_format($category->availableAccount) }}</span>
    </span>
  </div>

                @if (config('app.use_image_button'))
                    <div class="product-action-img">
                        <img src="{{ asset('assets/img/button/buttonshowall.png') }}"
                            alt="XEM CHI TIẾT"
                            class="product-action-image">
                    </div>
                @else
                    <p class="product-action">XEM CHI TIẾT</p>
                @endif
</a>
