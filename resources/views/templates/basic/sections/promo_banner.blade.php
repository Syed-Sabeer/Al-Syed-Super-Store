@php
    $promoImages = $data->images()->get();
@endphp
@if ($promoImages->count() > 0)
    <div class="site-section my-60">
        <div class="container">
            <div class="row gy-3 gx-3 justify-content-center">
                @php
                    if ($promoImages->count() == 1) {
                        $class = 'col-12';
                        $size = getFileSize('singlePromoBanner');
                        $path = getFilePath('singlePromoBanner');
                    } elseif ($promoImages->count() == 2) {
                        $class = 'col-6';
                        $size = getFileSize('doublePromoBanner');
                        $path = getFilePath('doublePromoBanner');
                    } else {
                        $class = 'col-4';
                        $size = getFileSize('triplePromoBanner');
                        $path = getFilePath('triplePromoBanner');
                    }
                @endphp
                @foreach ($promoImages as $promoImage)
                    <div class="{{ $class }}">
                        <a href="{{ $promoImage->link??'javascript:void(0)' }}" class="d-block overlay-effects rounded--5">
                            <img src="{{ getImage(null, $size) }}" data-src="{{ getImage($path . '/' . $promoImage->image, $size) }}" class="w-100 lazyload" alt="">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
