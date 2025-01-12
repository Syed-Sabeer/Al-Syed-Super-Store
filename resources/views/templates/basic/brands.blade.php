@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="pt-60 pb-60">
        <div class="container">
            @if ($brands->count())
                <div class="small-card">
                    @foreach ($brands as $brand)
                        <div class="small-card-item text-center">
                            <x-dynamic-component :component="frontendComponent('brand-card')" :brand="$brand" />
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center">
                    <x-dynamic-component :component="frontendComponent('empty-message')" />
                </div>
            @endif
        </div>
    </div>
@endsection
