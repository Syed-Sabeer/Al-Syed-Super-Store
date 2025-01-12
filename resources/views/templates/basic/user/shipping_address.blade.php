@extends('Template::layouts.user')

@section('panel')
    <button class="btn btn-outline--light float-end mb-4 newAddress">
        <i class="las la-plus"></i> @lang('New Address')
    </button>
    <table class="table table--responsive--lg">
        <thead>
            <tr>
                <th>@lang('S.N.')</th>
                <th>@lang('Title')</th>
                <th>@lang('Name')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($shippingAddresses as $address)
                <tr>
                    <td>{{ $loop->index + $shippingAddresses->firstItem() }}</td>
                    <td>{{ __($address->label) }}</td>
                    <td>{{ __($address->fullname) }}</td>
                    <td>
                        <div class="d-flex gap-2 flex-wrap justify-content-end">
                            <button class="btn btn-outline--light editAddress me-1" data-resource="{{ $address }}">
                                <i class="las la-pencil-alt me-0"></i> @lang('Edit')
                            </button>

                            <button class="btn btn-outline--light confirmationBtn" data-action="{{ route('user.shipping.address.delete', $address->id) }}" data-question="@lang('Are you sure to delete this shipping address?')"><i class="las la-trash-alt me-0"></i> @lang('Delete')</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center text-muted">@lang('No shipping address added yet')</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($shippingAddresses->hasPages())
        <div class="mt-4">
            {{ paginateLinks($shippingAddresses) }}
        </div>
    @endif
@endsection

@push('modal')
    <x-dynamic-component :component="frontendComponent('shipping-address-modal')" :countries="$countries" />
    <x-confirmation-modal />
@endpush
