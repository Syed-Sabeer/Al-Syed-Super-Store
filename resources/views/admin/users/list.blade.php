@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Customer')</th>
                                    <th>@lang('Email-Mobile')</th>
                                    <th>@lang('Country')</th>
                                    <th>@lang('Orders')</th>
                                    <th>@lang('Total Shopping')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>

                                        <td>
                                            <span class="fw-bold">{{ $user->fullname }}</span>
                                            <br>
                                            <a class="text--small" href="{{ route('admin.users.detail', $user->id) }}">{{ $user->username }}</a>
                                        </td>

                                        <td>
                                            {{ $user->email }}<br>{{ $user->mobileNumber }}
                                        </td>

                                        <td>
                                            <span class="fw-bold" title="{{ @$user->country_name }}">{{ $user->country_code }}</span>
                                        </td>

                                        <td>{{ $user->orders_count }}</td>

                                        <td>{{ showAmount($user->orders_sum_subtotal) }}</td>

                                        <td>
                                            {{ showDateTime($user->created_at) }} <br> {{ diffForHumans($user->created_at) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="las la-desktop"></i> @lang('Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($users->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($users) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush
