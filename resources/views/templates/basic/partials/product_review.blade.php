@forelse($reviews as $item)
    <div class="review-item d-flex flex-wrap">
        <div class="thumb">
            <img src="{{ getImage(null) }}" data-src="{{ getAvatar('assets/images/user/profile/' . $item->user->image) }}" class="lazyload" alt="@lang('review')">
        </div>
        <div class="content">
            <div class="entry-meta">
                <h6 class="posted-by">{{ @$item->user->fullname }} <span class="posted-on fs-14">{{ diffForHumans($item->created_at) }}</span></h6>
                <div class="ratings">@php echo displayRating($item->rating) @endphp</div>
            </div>
            <p class="mb-0">{{ $item->review }}</p>
        </div>
    </div>
@empty
    <h6 class="text-muted text-center">
        @lang('No reviews yet for this product')
    </h6>
@endforelse

@if ($reviews->currentPage() != $reviews->lastPage())
    <button type="button" class="load-more-btn" id="loadMoreBtn" data-url="{{ $reviews->nextPageUrl() }}">@lang('See More Reviews')</button>
@endif
