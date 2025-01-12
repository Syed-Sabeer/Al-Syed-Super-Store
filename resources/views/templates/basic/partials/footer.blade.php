@php
    $footer = getContent('footer.content', true);
    $footer = @$footer->data_values;
    $socials = getContent('social_icon.element', orderById: true);
    $menus = \App\Models\Frontend::where('data_keys', 'footer_menu.content')->first()->data_values;

@endphp

<!-- Footer Section Starts Here -->
<footer class="footer-area footer-bg ">
    <div class="container">
        @if (@$footer->logo || @$footer->footer_note || gs('subscriber_module') == Status::YES || @$footer->contact_address || @$footer->cell_number || @$footer->email)
            <div class="footer-top">
                <div class="footer-widget widget-about">
                    @if (@$footer->logo)
                        <img src="{{ getImage(null) }}" data-src="{{ getImage('assets/images/frontend/footer/' . @$footer->logo) }}" class="lazyload footer-logo" alt="footer-logo">
                    @endif

                    @if (@$footer->footer_note)
                        <p class="mb-0">{{ __($footer->footer_note) }}</p>
                    @endif
                </div>

                @include('Template::partials.newsletter')

                @if (@$footer->contact_address || @$footer->cell_number || @$footer->email)
                    <div class="widget-contact">
                        @if (@$footer->contact_heading)
                            <h6 class="title">{{ __(@$footer->contact_heading) }}</h6>
                        @endif
                        <ul>
                            @if ($footer->contact_address)
                                <li>
                                    <i class="las la-map-marker"></i> {{ __(@$footer->contact_address) }}
                                </li>
                            @endif

                            @if (@$footer->cell_number)
                                <li>
                                    <a href="tel:{{ @$footer->cell_number }}"><i class="las la-phone"></i>{{ @$footer->cell_number }}</a>
                                </li>
                            @endif
                            @if (@$footer->email)
                                <li>
                                    <a href="mailto:{{ @$footer->email }}"><i class="las la-envelope"></i>{{ @$footer->email }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
        @endif
        @if ($menus)
            <div class="footer-middle">
                @foreach ($menus as $menu)
                    <div class="footer-widget widget-link">
                        <h6 class="title">{{ __($menu->title) }}</h6>
                        <ul>
                            @foreach ($menu->links as $link)
                                <li><a href="{{ url($link->url) }}">{{ __($link->name) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @endif
        @if (@$footer->copyright_text || $socials->count() > 0 || @$footer->payment_methods)
            <div class="footer-copyright">
                <div class="copyright-area d-flex flex-wrap align-items-center @if ($socials->count() == 0 && !@$footer->payment_methods) justify-content-center @else justify-content-between @endif gap-4 flex-wrap-reverse">

                    @if (@$footer->copyright_text)
                        <div class="left">
                            @php
                                $copyrightText = str_replace('{year}', date('Y'), $footer->copyright_text);
                                $copyrightText = str_replace('{site_name}', gs('site_name'), $copyrightText);
                            @endphp
                            <p>{{ __(@$copyrightText) }}</p>
                        </div>
                    @endif

                    @if ($socials->count() > 0)
                        <ul class="social-icons d-flex gap-2 flex-wrap mt-0">
                            @foreach ($socials as $item)
                                <li>
                                    <a href="{{ $item->data_values->url }}" target="_blank">
                                        @php
                                            echo $item->data_values->social_icon;
                                        @endphp
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if (@$footer->payment_methods)
                        <div class="right">
                            <img src="{{ getImage(null) }}" data-src="{{ getImage('assets/images/frontend/footer/' . @$footer->payment_methods, '250x30') }}" class="lazyload" alt="@lang('footer')">
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</footer>
<!-- Footer Section Ends Here -->

<div class="modal fade" id="quickView">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <button type="button" class="close modal-close-btn " data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
            <div class="modal-body">
                <div class="ajax-loader-wrapper d-flex align-items-center justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">@lang('Loading')...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
