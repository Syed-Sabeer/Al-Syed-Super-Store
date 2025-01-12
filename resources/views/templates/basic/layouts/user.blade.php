@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="user-profile-section py-60">
        <div class="container">
            <div class="dashboard-wrapper">
                <aside class="dashboard-menu">
                    <ul>
                        @include($activeTemplate . 'user.partials.sidebar')
                    </ul>
                </aside>
                <div class="dashboard-content">
                    @yield('panel')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link href="{{ asset($activeTemplateTrue . 'css/user-dashboard.css') }}" rel="stylesheet">
@endpush
