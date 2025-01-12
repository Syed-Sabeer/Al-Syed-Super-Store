@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Id')</th>
                                <th>@lang('Product')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Rating')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('View Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                                <tr>
                                    <td>{{ ($review->currentPage - 1) * $review->perPage + $loop->iteration }}</td>
                                    <td>{{ $review->product->name }}</td>
                                    <td>{{ $review->user->username }}</td>
                                    <td>{{ $review->rating }}</td>
                                    <td>{{ diffForHumans($review->created_at) }}</td>
                                    <td>@php echo $review->viewStatusBadge @endphp</td>
                                    <td>
                                        <button type="button" class="btn btn-outline--primary btn-sm me-1 view-btn" data-id="{{ $review->id }}" data-user="{{ __($review->user->username) }}" data-rating="{{ $review->rating }}" data-review="{{ $review->review }}" data-user_link="{{ route('admin.users.detail', $review->user->id) }}">
                                            <i class="la la-desktop"></i>@lang('View')
                                        </button>

                                        <button type="button" class="btn btn-sm btn-outline--{{ $review->trashed() ? 'success' : 'danger' }} confirmationBtn" data-action="{{ route('admin.products.reviews.delete', $review->id) }}" data-question="@lang($review->trashed() ? 'Are you sure to restore this review?' : 'Are you sure to delete this review?')" data-type="{{ $review->trashed() ? 'restore' : 'delete' }}" data-id='{{ $review->id }}'>
                                            <i class="la la-{{ $review->trashed() ? 'redo' : 'trash' }}"></i>@lang($review->trashed() ? 'Restore' : 'Delete')
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($reviews->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($reviews) }}
                </div>
            @endif

        </div>
    </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Product Review')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body p-0">

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between py-3">
                            <span>@lang('Reviewer')</span>
                            <a href="" id="user-detail">
                                <span id="name"></span>
                            </a>
                        </div>

                        <div class="list-group-item d-flex justify-content-between py-3">
                            <span>@lang('Rating')</span>
                            <span id="rating"></span>
                        </div>

                       <div class="list-group-item d-flex flex-column justify-content-between py-3">
                            <span>@lang('Review')</span>
                            <p class="h-auto" id="review"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {

            $('.view-btn').on('click', function() {
                var modal = $('#viewModal');
                modal.find('#name').text($(this).data('user'));
                modal.find('#rating').text($(this).data('rating'));
                modal.find('#review').text($(this).data('review'));
                modal.find('#user-detail').attr('href', $(this).data('user_link'));

                let id = $(this).data('id');
                $.get("{{ route('admin.products.reviews.view', '') }}/" + id,
                    function(data, textStatus, jqXHR) {
                    },
                    "JSON"
                );
                modal.modal('show');
            });

            $('.isViewed').on('change', function() {
                $('#search-form').submit();
            });

        })(jQuery);
    </script>
@endpush

@push('breadcrumb-plugins')
    @if (!request()->routeIs('admin.products.reviews.index'))
        @if (request()->routeIs('admin.products.trashed.search'))
            <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center">
                <x-back route="{{ route('admin.products.reviews.trashed') }}"></x-back>
            </div>
        @else
            <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center">
                <x-back route="{{ route('admin.products.reviews.index') }}"></x-back>
            </div>
        @endif
    @endif

    @if (request()->routeIs('admin.products.reviews.index'))
        <div class="d-flex justify-content-end align-items-center flex-wrap gap-2 has-search-form">
            <select name="is_viewed" class="bg--white isViewed" form="search-form">
                <option value="">@lang('All')</option>
                <option value="0" @selected(request()->is_viewed === '0')>@lang('Not Viewed')</option>
                <option value="1" @selected(request()->is_viewed == 1)>@lang('Viewed')</option>
            </select>
            <x-search-form></x-search-form>
            <a href="{{ route('admin.products.reviews.trashed') }}" class="btn btn-sm btn-outline--danger"><i class="las la-trash-alt"></i>@lang('Trashed')</a>
        </div>
    @endif
@endpush
