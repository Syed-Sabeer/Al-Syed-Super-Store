@extends('Template::layouts.master')

@section('content')
    @if ($sections != null)
        @foreach (json_decode($sections) as $sec)
            @include('Template::sections.' . $sec)
        @endforeach
    @endif
@endsection
