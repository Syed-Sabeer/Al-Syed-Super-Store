<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('Extra Descriptions') <span class="color--small">(@lang('Optional'))</span></h5>
    </div>
    <div class="card-body">
        <div class="extras">
            @if (isset($product) && $product->extra_descriptions != null)
                @foreach ($product->extra_descriptions as $item)
                    <div class="extra">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn btn-outline--danger remove-extra"><i class="la la-minus"></i></button>
                        </div>

                        <div class="form-group row">
                            <div class="col-xl-4 col-md-3">
                                <label>@lang('Name')</label>
                            </div>
                            <div class="col-xl-8 col-md-9">
                                <input type="text" class="form-control extra_description-field" name="extra_description[{{ $loop->iteration }}][key]" value="{{ $item['key'] }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xl-4 col-md-3">
                                <label>@lang('Value')</label>
                            </div>
                            <div class="col-xl-8 col-md-9">
                                <textarea class="form-control nicEdit extra_description-field" name="extra_description[{{ $loop->iteration }}][value]" rows="3"> @php echo $item['value'] @endphp</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="row">
            <div class="col-md-11">
                <p class="extra-info p-2">@lang('Add more descriptions as you want by clicking the (+) button on the right side.')</p>
            </div>
            <div class="col-md-1">
                <div class="d-flex justify-content-end">

                    <button type="button" class="btn btn-outline--success add-extra"><i class="la la-plus me-0"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        (function($) {
            'use strict';
            const extrasContainer = $('.extras');
            const addExtraDescriptionBtn = $('.add-extra');
            let extrasCount = Number(@json(count($product->extra_descriptions ?? [])));

            const buildExtraHTML = (count) => {
                return `<div class="extra">
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn btn-outline--danger remove-extra"><i class="la la-minus me-0"></i></button>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-4 col-md-3">
                                    <label>Name</label>
                                </div>
                                <div class="col-xl-8 col-md-9">
                                    <input type="text" class="form-control extra_description-field" name="extra_description[${count}][key]" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xl-4 col-md-3">
                                    <label>Value</label>
                                </div>
                                <div class="col-xl-8 col-md-9">
                                    <textarea class="form-control appended-extra-description extra_description-field" name="extra_description[${count}][value]" rows="3" id="extraDescription${count}" data-id="${count}"></textarea>
                                </div>
                            </div>
                        </div>`;
            }

            const handleAddExtraClick = () => {
                $(".submitBtn").attr('disabled', true);
                extrasCount++;
                var nicEditorInstance = new nicEditor({
                    fullPanel: true
                });
                let content = buildExtraHTML(extrasCount);
                let textarea = appendAndShowElement(extrasContainer, content).find('textarea');
                nicEditorInstance.panelInstance(textarea[0]);
                $(".submitBtn").removeAttr('disabled');
            }

            const updateExtrasCount = () => extrasCount = $('.extra').length;

            const handleRemoveExtraClick = function() {
                $(".submitBtn").attr('disabled', true);
                let container = $(this).closest('.extra');
                container.slideUp('slow', function() {
                    container.remove();
                    updateExtrasCount();
                    $(".submitBtn").removeAttr('disabled');
                });
            }

            addExtraDescriptionBtn.on('click', handleAddExtraClick);
            extrasContainer.on('click', '.remove-extra', handleRemoveExtraClick);

        })(jQuery);
    </script>
@endpush
