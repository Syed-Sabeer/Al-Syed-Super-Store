<li>
    <div class="feature-card">
        <div class="feature-card__head">
            @if ($item->data_values->icon)
                <div class="feature-card__icon">
                    @php echo $item->data_values->icon; @endphp
                </div>
            @endif
            @if (@$item->data_values->title)
                <h4 class="feature-card__title">{{ __($item->data_values->title) }}</h4>
            @endif
        </div>
        @if ($item->data_values->description)
            <div class="feature-card__body">
                <p class="m-0 sm-text">{{ __($item->data_values->description) }}</p>
            </div>
        @endif
    </div>
</li>
