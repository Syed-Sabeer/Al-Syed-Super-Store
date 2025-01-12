@extends('Template::layouts.user')
@section('panel')
    <table class="table table--responsive--lg">
        <thead>
            <tr>
                <th>@lang('Transaction ID')</th>
                <th>@lang('Gateway')</th>
                <th>@lang('Amount')</th>
                <th>@lang('Status')</th>
                <th>@lang('Time')</th>
                <th>@lang('View')</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($deposits as $deposit)
                <tr>
                    <td>#{{ $deposit->trx }}</td>
                    <td>{{ $deposit->gateway ? __($deposit->gateway->name) : '---' }}</td>
                    <td>
                        {{ getAmount($deposit->amount) }} {{ gs('cur_text') }}
                    </td>
                    <td>
                        @php echo $deposit->statusBadge @endphp
                        @if ($deposit->admin_feedback != null)
                            <button class="btn--base details_info_btn detailBtn" data-admin_feedback="{{ $deposit->admin_feedback }}"><i class="fa fa-info"></i></button>
                        @endif
                    </td>
                    <td>
                        {{ showDateTime($deposit->created_at, 'd M, Y H:iA') }}</span>
                    </td>

                    @php
                        $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                    @endphp

                    <td>
                        <button type="button" class="btn btn-outline--light approveBtn" data-info="{{ $details }}" data-id="{{ $deposit->id }}" data-amount="{{ getAmount($deposit->amount) }}" data-charge="{{ getAmount($deposit->charge) }}"\ data-after_charge="{{ getAmount($deposit->amount + $deposit->charge) }} {{ gs('cur_text') }}" data-rate="{{ getAmount($deposit->rate) }} {{ $deposit->method_currency }}" data-payable="{{ getAmount($deposit->final_amount) }} {{ $deposit->method_currency }}">
                            <i class="las la-desktop"></i> @lang('View')
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center text-muted">@lang('No payment records found')</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($deposits->hasPages())
        <div class="mt-4">
            {{ paginateLinks($deposits) }}
        </div>
    @endif
@endsection

@push('modal')
    {{-- APPROVE MODAL --}}
    <div id="approveModal" class="modal custom--modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between rounded-0">@lang('Amount') <span class="deposit-amount"></span></li>
                        <li class="list-group-item d-flex justify-content-between rounded-0">@lang('Charge') <span class="deposit-charge"></span></li>
                        <li class="list-group-item d-flex justify-content-between rounded-0">@lang('After Charge') <span class="deposit-after_charge"></span></li>
                        <li class="list-group-item d-flex justify-content-between rounded-0">@lang('Conversion Rate') <span class="deposit-rate"></span></li>
                        <li class="list-group-item d-flex justify-content-between rounded-0">@lang('Payable Amount') : <span class="deposit-payable"></span></li>
                    </ul>

                    <div class="otherInfo d-none mt-3">
                        <h6 class="mb-0">@lang('Others Information')</h6>
                        <ul class="list-group list-group-flush d-flex deposit-detail mt-1"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail MODAL --}}
    <div id="detailModal" class="modal custom--modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="deposit-detail"></div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.approveBtn').on('click', function() {
                let modal = $('#approveModal');
                modal.find('.deposit-amount').text($(this).data('amount'));
                modal.find('.deposit-charge').text($(this).data('charge'));
                modal.find('.deposit-after_charge').text($(this).data('after_charge'));
                modal.find('.deposit-rate').text($(this).data('rate'));
                modal.find('.deposit-payable').text($(this).data('payable'));

                let userData = $(this).data('info');
                let html = '';

                if (userData) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                                <li class="list-group-item d-flex justify-content-between rounded-0">
                                    <span> ${element.name}</span> <span>${element.value}</span>
                                </li>`;
                        }
                    });
                }

                if (html) {
                    modal.find('.otherInfo').removeClass('d-none');
                    modal.find('.deposit-detail').html(html);
                } else {
                    modal.find('.otherInfo').addClass('d-none');
                    modal.find('.deposit-detail').html('');
                }
                modal.modal('show');
            });
            $('.detailBtn').on('click', function() {
                let modal = $('#detailModal');
                let feedback = $(this).data('admin_feedback');
                modal.find('.deposit-detail').html(`<p> @lang('${feedback}') </p>`);
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush
