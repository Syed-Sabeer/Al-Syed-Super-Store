@if (gs('multi_language'))
    @php
        $language = App\Models\Language::all();
        $activeLanguage = $language->where('code', session('lang'))->first();
    @endphp
    <div class="dropdown dropdown--lang">
        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img class="dropdown-flag lazyload"
                src="{{ getImage(getFilePath('language') . '/' . @$activeLanguage->image, getFileSize('language')) }}"
                alt="@lang('image')">
            <span>{{ __($activeLanguage['name']) }}</span>
        </button>

        <div class="dropdown-menu">
            @foreach ($language as $lang)
                <a class="dropdown-item" href="{{ route('lang', $lang->code) }}">
                    <img class="dropdown-flag lazyload"
                        src="{{ getImage(getFilePath('language') . '/' . @$lang->image, getFileSize('language')) }}"
                        alt="country">
                    <span>{{ __(@$lang->name) }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endif
