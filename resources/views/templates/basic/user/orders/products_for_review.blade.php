@extends('Template::layouts.user')

@section('panel')
    <table class="table table--responsive--md">
        <thead>
            <tr>
                <th>@lang('Products')</th>
                <th class="text-end">@lang('Review')</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td class="cart-item-wrapper">
                        <a href="{{ $product->link() }}" class="cart-item">
                            <div class="cart-img">
                                <img src="{{ getImage(null) }}" data-src="{{ $product->mainImage() }}" class="lazyload" alt="@lang('cart')">
                            </div>
                            <div class="cart-cont">
                                <h6 class="title">{{ $product->name }}</h6>
                            </div>
                        </a>
                    </td>
                    <td>
                        @if ($product->userReview)
                            <button class="btn btn-outline--light review-btn reviewed-btn" data-pid="{{ $product->id }}" data-rating="{{ $product->userReview->rating }}" data-review="{{ $product->userReview->review }}"><i class="las la-star text-warning"></i> @lang('Reviewed')</button>
                        @else
                            <button data-pid="{{ $product->id }}" class="btn btn-outline--light review-btn"><i class="las la-star"></i> @lang('Review')</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center text-muted">@lang('No product purchased yet')</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if ($products->hasPages())
        <div class="mt-4">
            {{ paginateLinks($products) }}
        </div>
    @endif
@endsection

@push('modal')
    <div class="modal fade custom--modal" id="reviewModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                    <form action="{{ route('user.review.add') }}" method="POST" class="review-form">
                        @csrf
                        <input type="hidden" name="pid" value="">

                        <h5 class="modal-title text-center mb-2"></h5>
                        <div class="rating-form-group mb-2">
                            <div class="rating">
                                <input type="radio" id="star5" name="rating" value="5" />
                                <label class="star" for="star5" title="@lang('Awesome')" aria-hidden="true"></label>
                                <input type="radio" id="star4" name="rating" value="4" />
                                <label class="star" for="star4" title="@lang('Great')" aria-hidden="true"></label>
                                <input type="radio" id="star3" name="rating" value="3" />
                                <label class="star" for="star3" title="@lang('Very') good" aria-hidden="true"></label>
                                <input type="radio" id="star2" name="rating" value="2" />
                                <label class="star" for="star2" title="@lang('Good')" aria-hidden="true"></label>
                                <input type="radio" id="star1" name="rating" value="1" />
                                <label class="star" for="star1" title="@lang('Bad')" aria-hidden="true"></label>
                            </div>
                        </div>
                        <div class="review-form-group mb-20">
                            <label for="review-comments"class="fs-16">@lang('Write your feedback')</label>
                            <textarea name="review" class="form--control form-control" id="review-comments" placeholder="@lang('Say Something about This Product')" rows="4"></textarea>
                        </div>
                        <div class="review-form-group col-12 d-flex flex-wrap">
                            <button type="submit" class="submit-button rounded--5 btn--sm w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            var modal = $('#reviewModal');

            $('.review-btn').on('click', function() {
                modal.find('[name=pid]').val($(this).data('pid'));
                modal.find('.modal-title').text(`@lang('How would you rate?')`);
                modal.find(`input[name="rating"]`).prop('checked', false);
                modal.find(`textarea`).val('');
                modal.modal('show');
            });

            $('.reviewed-btn').on('click', function() {
                let data = $(this).data();
                modal.find('.modal-title').text(`@lang('Change your review')`);
                modal.find('[name=pid]').val(data.pid);
                modal.find('[name=review]').val(data.review);
                modal.find(`input[name="rating"][value="${data.rating}"]`).prop('checked', true);
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .rating {
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row-reverse;
        }

        .rating>label {
            color: hsl(var(--border));
        }

        .rating>label:before {
            margin-inline: 3px;
            font-size: 2rem;
            font-family: 'Line Awesome Free';
            content: "\f005";
            display: inline-block;
        }

        .rating>input {
            display: none;
        }

        .rating>input:checked~label,
        .rating:not(:checked)>label:hover,
        .rating:not(:checked)>label:hover~label {
            color: #ffa53e;

            &::before {
                font-weight: 900;
            }
        }

        .rating>input:checked+label:hover,
        .rating>input:checked~label:hover,
        .rating>label:hover~input:checked~label,
        .rating>input:checked~label:hover~label {
            color: #ffc363;

            &::before {
                font-weight: 900;
            }
        }

        textarea {
            resize: both;
            line-height: 1.5 !important;
            word-spacing: 2px;
        }
    </style>
@endpush
