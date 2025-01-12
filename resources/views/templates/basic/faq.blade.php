@extends($activeTemplate . 'layouts.master')

@section('content')
    @php
        $content = getContent('faq_page.content', true);
    @endphp

    <div class="py-60">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @php echo $content->data_values->description; @endphp
                </div>
            </div>
        </div>
    </div>
@endsection
