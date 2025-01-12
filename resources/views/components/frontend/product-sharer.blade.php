@props(['product'])

<div class="product-share">
    <b>@lang('Share :')</b>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="blank" title="@lang('Share on Facebook')">
        <i class="fab fa-facebook-f"></i>
    </a>
    <a href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ __($product->name) }}&media={{ getImage('assets/images/product/' . @$product->main_image) }}" title="@lang('Share on Pinterest')" target="blank">
        <i class="fab fa-pinterest-p"></i>
    </a>
    <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $product->name }}&amp;summary={{ $product->summary }}" title="@lang('Share on Linkedin')" target="blank">
        <i class="fab fa-linkedin-in"></i>
    </a>
    <a href="https://twitter.com/intent/tweet?text={{ __($product->name) }}%0A{{ url()->current() }}" title="@lang('Share on Twitter')" target="blank">
        <i class="fab fa-x-twitter"></i>
    </a>
</div>
