<div class="predefined-widgets d-flex gap-2 align-items-center py-2">
    @if ($headerOne->language_option == 'on')
        <div class="d-none d-lg-block">
            @include('Template::partials.menu.language_menu')
        </div>
    @endif


    @if ($headerOne->user_option == 'on')
        @include('Template::partials.user_auth_options')
    @endif
</div>
