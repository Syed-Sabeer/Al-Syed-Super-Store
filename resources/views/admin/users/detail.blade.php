@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="row gy-4">

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="primary" icon="la la-shopping-cart" link="{{ route('admin.order.index') }}?search={{ $user->username }}" value="{{ $widget['totalOrders'] }}" title="Total Orders" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="danger" icon="la la-times-circle" link="{{ route('admin.order.pending') }}?search={{ $user->username }}" value="{{ $widget['canceledOrders'] }}" title="Canceled Orders" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="warning" icon="la la-pause-circle" link="{{ route('admin.order.pending') }}?search={{ $user->username }}" value="{{ $widget['pendingOrders'] }}" title="Pending Orders" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="info" icon="la la-play" link="{{ route('admin.order.pending') }}?search={{ $user->username }}" value="{{ $widget['processingOrders'] }}" title="On Processing Orders" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="green" icon="la la-truck" link="{{ route('admin.order.pending') }}?search={{ $user->username }}" value="{{ $widget['dispatchedOrders'] }}" title="Dispatched Orders" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="red" icon="la la-undo" link="{{ route('admin.order.pending') }}?search={{ $user->username }}" value="{{ $widget['returnedOrders'] }}" title="Returned Orders" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="success" icon="la la-check-circle" link="{{ route('admin.order.delivered') }}?search={{ $user->username }}" value="{{ $widget['deliveredOrders'] }}" title="Delivered Orders" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" color="success" icon="la la-shopping-bag" value="{{ showAmount($widget['totalOrderAmount']) }}" title="Total Shopping" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" link="{{ route('admin.deposit.successful') }}?search={{ $user->username }}" cover_cursor="1" color="success" icon="la la-wallet" value="{{ $widget['successfulPayment'] }}" title="Successful Payment" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="warning" icon="la la-wallet" link="{{ route('admin.deposit.pending') }}?search={{ $user->username }}" value="{{ $widget['pendingPayments'] }}" title="Pending Payments" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="danger" icon="la la-wallet" link="{{ route('admin.deposit.rejected') }}?search={{ $user->username }}" value="{{ $widget['rejectedPayments'] }}" title="Rejected Payments" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" color="dark" icon="la la-money-bill" value="{{ showAmount($widget['totalPaymentCharge']) }}" title="Total Payment Charge" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="warning" icon="la la-ticket" link="{{ route('admin.ticket.pending') }}?user={{ $user->username }}" value="{{ $widget['openedTickets'] }}" title="Opened Tickets" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="dark" icon="la la-ticket" link="{{ route('admin.ticket.answered') }}?user={{ $user->username }}" value="{{ $widget['answeredTickets'] }}" title="Answered Tickets" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="success" icon="la la-ticket" link="{{ route('admin.ticket.closed') }}?user={{ $user->username }}" value="{{ $widget['closedTickets'] }}" title="Closed Tickets" icon_style="solid" overlay_icon="0" />
                </div>

                <div class="col-xl-3 col-sm-6">
                    <x-widget style="2" cover_cursor="1" color="info" icon="la la-ticket" link="{{ route('admin.ticket.index') }}?user={{ $user->username }}" value="{{ $widget['openedTickets'] + $widget['answeredTickets'] + $widget['closedTickets'] }}" title="Total Tickets" icon_style="solid" overlay_icon="0" />
                </div>
                <!-- dashboard-w1 end -->
            </div>

            <div class="d-flex flex-wrap-reverse justify-content-between align-items-end gap-3 mt-5 mb-3">
                <h4>@lang('Update Customer Info')</h4>
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <div>
                        <a href="{{ route('admin.report.login.history') }}?search={{ $user->username }}" class="btn btn--primary btn--shadow btn-lg">
                            <i class="las la-list-alt"></i>@lang('View Login History')
                        </a>
                    </div>

                    <div>
                        <a href="{{ route('admin.users.notification.log', $user->id) }}" class="btn btn--secondary btn--shadow btn-lg">
                            <i class="las la-bell"></i>@lang('View Notification Log')
                        </a>
                    </div>

                    <div>
                        @if ($user->status == Status::USER_ACTIVE)
                            <button type="button" class="btn btn--warning btn--shadow btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                                <i class="las la-ban"></i>@lang('Ban This Customer')
                            </button>
                        @else
                            <button type="button" class="btn btn--success btn--shadow btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                                <i class="las la-undo"></i>@lang('Unban This Customer')
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.update', [$user->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('First Name')</label>
                                    <input class="form-control" type="text" name="firstname" required value="{{ $user->firstname }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Last Name')</label>
                                    <input class="form-control" type="text" name="lastname" required value="{{ $user->lastname }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input class="form-control" type="email" name="email" value="{{ $user->email }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Mobile Number')</label>
                                    <div class="input-group ">
                                        <span class="input-group-text mobile-code">+{{ $user->dial_code }}</span>
                                        <input type="number" name="mobile" value="{{ $user->mobile }}" id="mobile" class="form-control checkUser" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label>@lang('Address')</label>
                                    <input class="form-control" type="text" name="address" value="{{ @$user->address }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('City')</label>
                                    <input class="form-control" type="text" name="city" value="{{ @$user->city }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('State')</label>
                                    <input class="form-control" type="text" name="state" value="{{ @$user->state }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('Zip/Postal')</label>
                                    <input class="form-control" type="text" name="zip" value="{{ @$user->zip }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label>@lang('Country') <span class="text--danger">*</span></label>
                                    <select name="country" class="form-control select2">
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}" @selected($user->country_code == $key)>
                                                {{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Email Verification')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev" @if ($user->ev) checked @endif>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Mobile Verification')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv" @if ($user->sv) checked @endif>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($user->status == Status::USER_ACTIVE)
                            @lang('Ban User')
                        @else
                            @lang('Unban User')
                        @endif
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.users.status', $user->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if ($user->status == Status::USER_ACTIVE)
                            <h6 class="mb-2">@lang('If you ban this user he/she won\'t able to access his/her dashboard.')</h6>
                            <div class="form-group">
                                <label>@lang('Reason')</label>
                                <textarea class="form-control" name="reason" rows="4" required></textarea>
                            </div>
                        @else
                            <p><span>@lang('Ban reason was'):</span></p>
                            <p>{{ $user->ban_reason }}</p>
                            <h4 class="text-center mt-3">@lang('Are you sure to unban this user?')</h4>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if ($user->status == Status::USER_ACTIVE)
                            <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                        @else
                            <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.users.login', $user->id) }}" target="_blank" class="btn btn-sm btn-outline--primary"><i class="las la-sign-in-alt"></i>@lang('Login as Customer')</a>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            let mobileElement = $('.mobile-code');
            $('select[name=country]').on('change', function() {
                mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
            });

        })(jQuery);
    </script>
@endpush
