@extends('Template::layouts.user')

@section('panel')
    @include('Template::user.orders.orders_table')

    @if ($orders->hasPages())
        <div class="mt-4">
            {{ paginateLinks($orders) }}
        </div>
    @endif
@endsection
