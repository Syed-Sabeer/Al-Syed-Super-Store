@extends($activeTemplate . 'layouts.master')

@section('content')
    @php
        $content = getContent('contact_page.content', true);
        $elements = getContent('contact_page.element', false, orderById: true);
    @endphp

    <div class="contact-area pt-60 pb-60">
        <div class="container">
            <div class="row g-4 gy-4 @if($elements->count() || @$content->data_values->address_heading || @$content->data_values->description) justify-content-xl-between flex-wrap-reverse @else justify-content-center @endif">

                @if($elements->count() || @$content->data_values->address_heading || @$content->data_values->description)
                <div class="col-lg-6 col-xxl-4 col-xl-5">
                    @if (@$content->data_values->address_heading || @$content->data_values->description)
                        <div class="mb-4">
                            <h5 class="mb-3">{{ __(@$content->data_values->address_heading) }}</h5>
                            <p>
                                {{ __(@$content->data_values->description) }}
                            </p>
                        </div>
                    @endif
                    @if ($elements->count())
                        <ul class="list">
                            @foreach ($elements as $element)
                                <li>
                                    <div class="contact align-items-center">
                                        <div class="contact__icon">
                                            @php echo $element->data_values->icon @endphp
                                        </div>
                                        <div class="contact__content">
                                            <h6 class="contact__title">{{ __($element->data_values->title) }}</h6>
                                            <p class="mb-0 sm-text">
                                                {{ __($element->data_values->value) }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                @endif

                <div class="col-lg-6 col-xxl-8 col-xl-7 ps-xxl-5">
                    <h5 class="mb-4">{{ __($content->data_values->form_heading) }}</h5>
                    <div class="contact-form">
                        <form action="{{ route('contact.submit') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="form--label" for="fullname">@lang('Full Name')</label>
                                <input type="text" class="form-control form--control" id="fullname" name="name"value="{{ old('name', @$user->fullname) }}" @if ($user) readonly @endif required>
                            </div>
                            <div class="mb-3">
                                <label class="form--label" for="email">@lang('Email')</label>
                                <input type="email" class="form-control form--control" id="email" name="email" value="{{ old('email', @$user->email) }}" @if ($user) readonly @endif required>
                            </div>
                            <div class="mb-3">
                                <label class="form--label" for="subject">@lang('Subject')</label>
                                <input type="text" class="form-control form--control" id="subject" name="subject" value="{{ old('subject') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form--label" for="message">@lang('Message')</label>
                                <textarea class="form-control form--control" name="message" rows="3" id="message" required>{{ old('message') }}</textarea>
                            </div>
                            <x-captcha />
                            <div class="mb-3">
                                <button class="btn btn--base h-45" type="submit">@lang('Send Your Message')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
