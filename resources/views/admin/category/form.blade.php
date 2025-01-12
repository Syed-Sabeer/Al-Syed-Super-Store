@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center gy-4">
        <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-5">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex  flex-wrap gap-2 mb-3">
                        <button type="reset" class="category-tree-btn active addRootCategory flex-grow-1"> <i class="las la-plus"></i> @lang('Add Root Category')</button>

                        <button type="button" class="category-tree-btn flex-grow-1" id="addChildBtn" disabled><i class="la la-plus"></i> @lang('Add Child ')</button>

                        <button type="button" class="btn btn-sm btn--danger confirmationBtn flex-grow-1" id="deleteChildBtn" disabled data-action="" data-question="@lang('Are you sure to delete this category?')"><i class="las la-trash"></i> @lang('Delete Selected')</button>

                    </div>

                    <input type="text" class="form-control" value="" id="treeSearch" placeholder="@lang('Search')" />
                    <button type="button" class="expand-collapse-btn text-muted text-sm" data-state="close_all">@lang('Expand All')</button>
                    <div id="categoryTree">
                        <ul>
                            @include('admin.category.tree')
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-8 col-xl-7 col-lg-6 col-md-7 col-sm-6">
            <div class="card right-sticky">
                <div class="card-body">
                    <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data" id="addForm">
                        @csrf
                        <input type="hidden" name="parent_id">
                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>
                                    @lang('Image')
                                    <i class="la la-question-circle" title="@lang('This image will be displayed as thumbnails of this category')"></i>
                                </label>
                            </div>
                            <div class="col-xxl-10 col-xl-9 category-thumb">
                                <x-image-uploader type="category" :required="false" :showInfo="false" />
                                <div class="mt-1 text--small text-muted">
                                    @lang('Image will be resized into') <b>{{ getFileSize('category') }}</b> @lang('px')</b>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <div class="col-xxl-2 col-xl-3">
                                <label>@lang('Icon')</label>
                            </div>
                            <div class="col-xxl-10 col-xl-9 category-icon">
                                <x-image-uploader type="categoryIcon" name="icon" id="icon" class="w-100" :showInfo="false" :required="false" />
                                <div class="mt-1 mb-3 text--small text-muted">
                                    @lang('Image will be resized into') <b>{{ getFileSize('categoryIcon') }}</b> @lang('px')</b>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>@lang('Name')</label>
                            </div>
                            <div class="col-xxl-10 col-xl-9">
                                <input type="text" class="form-control" value="{{ old('name') }}" name="name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>@lang('Slug')</label>
                            </div>
                            <div class="col-xxl-10 col-xl-9">
                                <input type="text" class="form-control" value="{{ old('slug') }}" name="slug" required>

                                <div class="d-flex justify-content-between flex-wrap gap-2">
                                    <span class="text--small cursor-pointer italic text-muted" id="makeSlugBtn">@lang('Use Category Name in Slug')</span>
                                    <span class="text--small text--danger italic slugMsg d-none">@lang('This slug already exists')</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>@lang('Meta Title')</label>
                            </div>
                            <div class="col-xxl-10 col-xl-9">
                                <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>@lang('Meta Description')</label>
                            </div>

                            <div class="col-xxl-10 col-xl-9">
                                <textarea name="meta_description" rows="3" class="form-control">{{ old('meta_description') }} </textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>@lang('Meta Keywords')</label>
                            </div>
                            <div class="col-xxl-10 col-xl-9">
                                <select name="meta_keywords[]" class="form-control select2-auto-tokenize" multiple="multiple"></select>
                                <small class="form-text text-muted">
                                    <i class="las la-info-circle"></i>
                                    @lang('Type , or hit enter to separate keywords')
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>
                                    @lang('Is Featured')
                                </label>
                                <small class="tex-muted" title="@lang('The category will be displayed on the category section of home page if this option is enabled.')">
                                    <i class="la la-info-circle"></i>
                                </small>
                            </div>
                            <div class="col-xxl-10 col-xl-9">
                                <x-toggle-switch name="is_featured" value="1" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xxl-2 col-xl-3">
                                <label>
                                    @lang('Feature In Banner')
                                </label>
                                <small class="tex-muted" title="@lang('The category will be displayed on the banner section of home page if this option is enabled.')">
                                    <i class="la la-info-circle"></i>
                                </small>
                            </div>
                            <div class="col-xxl-10 col-xl-9">
                                <x-toggle-switch name="feature_in_banner" value="1" />
                            </div>
                        </div>

                        <div class="d-flex gap-1">
                            <button type="submit" class="btn btn--primary h-45 flex-grow-1" id="submitButton">@lang('Submit')</button>
                            <button type="reset" class="btn btn--dark addRootCategory" title="@lang('Clear Form')"> <i class="las la-redo-alt"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal>
        <div class="form-check mb-0 d-flex align-items-cneter gap-2 mt-3">
            <input class="form-check-input" type="checkbox" name="delete_child" id="delete_child" value="1">
            <label class="form-check-label mb-0" for="delete_child">@lang('Delete child categories')</label>
        </div>
    </x-confirmation-modal>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.category.trashed') }}" class="btn btn-outline--danger"><i class="las la-trash-alt"></i>
        @lang('Trashed')</a>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/jsTree/style.css') }}">
@endpush

@push('script-lib')
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/vendor/jstree.min.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            const form = $('#addForm');
            const submitButton = $('#submitButton');
            const formAction = `{{ route('admin.category.store', '') }}`;
            const addRootCategoryButton = $('.addRootCategory');
            const addChildButton = $('#addChildBtn');
            const deleteChildButton = $('#deleteChildBtn');

            const makeSlugButton = $('#makeSlugBtn');
            const slugField = $('[name=slug]');

            let selectedNode;
            let timeout = false;

            $('#categoryTree').jstree({
                core: {
                    check_callback: (operation, node, parent) => {
                        if (operation === "move_node" && node.parent === parent) {
                            return true;
                        }

                        if (operation === "move_node" && node.id !== parent.id) {
                            return !$(parent).find(`#${node.id}`).length;
                        }
                    }
                },
                plugins: ["dnd", "search", "unique", "types"]
            });


            const treeSearchKeyupHandler = () => {
                if (timeout) {
                    clearTimeout(timeout);
                }

                timeout = setTimeout(function() {
                    $('#categoryTree').jstree(true).search($('#treeSearch').val());
                }, 250);
            }

            const selectNodeHandler = (e, data) => {
                selectedNode = data.node;
                addChildButton.removeAttr('disabled');
                deleteChildButton.removeAttr('disabled');
                deleteChildButton.attr('data-action', `{{ route('admin.category.delete', '') }}/${selectedNode.id}`);
                form.find('.image-upload-input').val('');
                $('.slugMsg').addClass('d-none');
                form.find('.select2-auto-tokenize').empty();
                const fieldMappings = ['parent_id', 'name', 'slug', 'meta_title', 'meta_description',
                    'is_featured', 'feature_in_banner'
                ];

                form.parents('.card').showPreloader();

                $('.category-tree-btn').removeClass('active');

                $.get(`{{ route('admin.category.get.single', '') }}/${selectedNode.id}`).done((response) => {
                    if (response.category) {
                        const data = response.category;
                        fieldMappings.forEach(field => {
                            const value = data[field];
                            const inputField = form.find(`[name=${field}]`)[0];

                            if (inputField.type === 'checkbox') {
                                $(inputField).prop('checked', value == 1 ? true : false);
                            } else {
                                $(inputField).val(value);
                            }
                        });

                        if (data.meta_keywords) {
                            data.meta_keywords.forEach(item => {
                                form.find('.select2-auto-tokenize').append($('<option>', {
                                    value: item,
                                    text: item,
                                }));
                            });
                        }

                        form.find('.select2-auto-tokenize').val(data.meta_keywords);
                        form.find('.category-thumb .image-upload-preview').css('background-image',
                            `url(${data.image_path})`);
                        form.find('.category-icon .image-upload-preview').css('background-image',
                            `url(${data.icon_path})`);
                        form.attr('action', `${formAction}/${data.id}`);
                    }

                    form.parents('.card').removePreloader();
                });
            }

            const moveNodeHandler = (e, data) => {
                const draggedNode = data.node;
                const newParent = data.parent;

                $('.slugMsg').addClass('d-none');
                // Validate new parent
                if (!newParent || draggedNode.id === newParent.id) {
                    return;
                }

                // Update category model in database using AJAX
                $.post("{{ route('admin.category.update.position') }}", {
                    _token: `{{ csrf_token() }}`,
                    category_id: draggedNode.id,
                    parent_id: newParent === "#" ? null : newParent,
                    old_position: data.old_position,
                    position: data.position,
                });
            }

            const submitAddCategoryForm = function(e) {
                e.preventDefault();
                form.parents('.card').showPreloader();
                $.ajax({
                    url: this.action,
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: new FormData(this),
                    success: function(response) {
                        if (response.status == 'error') {
                            notify('error', response.message);
                        } else {
                            if (response.action === 'updated') {
                                $('#categoryTree').jstree('rename_node', response.categoryId, response
                                    .name);
                            } else {
                                $('#categoryTree').jstree('create_node', response.parentId, {
                                    "id": response.categoryId,
                                    "text": response.name,
                                });
                            }
                            notify('success', response.message);
                            $('.category-tree-btn').removeClass('active');
                        }
                        form.parents('.card').removePreloader();
                    }
                });
            }

            const clearFormFields = () => {
                form.find('.slugMsg').addClass('d-none');
                form.attr('action', `${formAction}/0`);
                form.find("input[type=text], textarea, select").val("");
                form.find('input[type=checkbox]').prop('checked', false);
                form.find('.select2-auto-tokenize').empty();
                form.find('.category-thumb .image-upload-preview').css('background-image',
                    `url('{{ getImage(null, getFileSize('category')) }}')`);
                form.find('.category-icon .image-upload-preview').css('background-image',
                    `url('{{ getImage(null, getFileSize('categoryIcon')) }}')`);
            }

            const addChildButtonClickHandler = function() {
                clearFormFields();
                form.find("[name=parent_id]").val(selectedNode.id);
            }

            const addRootCategoryButtonClickHandler = (button) => {

                $('#categoryTree').jstree('deselect_all');
                clearFormFields();

                form.find("[name=parent_id]").val('');
            }



            const expandCollapseBtnClickHandler = function() {
                const currentState = $(this).data('state');
                const newState = currentState == 'open_all' ? 'close_all' : 'open_all';
                $(this).text(newState == 'open_all' ? 'Collapse All' : 'Expand All');
                $(this).data('state', newState);
                $('#categoryTree').jstree(newState)

            }

            const deSelectAllNodeHandler = (e, data) => {
                addChildButton.attr('disabled', true);
                deleteChildButton.attr('disabled', true);
                $('.slugMsg').addClass('d-none');
            }

            const checkSlug = () => {
                let slug = slugField.val();

                if (slug) {
                    $.get(`{{ route('admin.category.check.slug', '') }}/${selectedNode?.id??0}`, {
                            slug: slug,
                        },
                        function(response) {
                            if (response.status) {
                                $('.slugMsg').removeClass('d-none');
                            } else {
                                $('.slugMsg').addClass('d-none');
                            }
                        });
                }

            }

            const handleMakeSlugBtnClick = () => setSlugField($('[name=name]').val());
            const setSlugField = (value) => {

                if (!value) return false;
                slugField.val(createSlug(value));
                checkSlug();
            };

            makeSlugButton.on('click', handleMakeSlugBtnClick);

            $('#categoryTree').on('deselect_all.jstree', deSelectAllNodeHandler);
            $('#categoryTree').on('move_node.jstree', moveNodeHandler);
            $('#categoryTree').on('select_node.jstree', selectNodeHandler);
            $('#treeSearch').on('keyup', treeSearchKeyupHandler);
            $('.expand-collapse-btn').on('click', expandCollapseBtnClickHandler);
            form.on('submit', submitAddCategoryForm);
            addChildButton.on('click', addChildButtonClickHandler);
            addRootCategoryButton.on('click', (e) => addRootCategoryButtonClickHandler(e.currentTarget));

            slugField.on('focusout', function() {
                var text = createSlug($(this).val());
                $(this).val(text);
                checkSlug();
            });

            $('.category-tree-btn').on('click', function(e) {
                $('.category-tree-btn').removeClass('active');
                $(this).addClass('active');
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .select2-container {
            z-index: 0 !important;
        }

        .category-icon .image--uploader,
        .category-icon .image-upload-wrapper {
            height: 45px;
        }

        .jstree-default .jstree-themeicon-custom {
            color: #1c1f26;
        }

        .category-icon .image-upload-wrapper {
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        .category-icon .image--uploader .image-upload-preview {
            position: absolute;
            height: 43px;
            width: 43px;
            border-radius: 5px;
            border: none;
        }

        .card.right-sticky {
            position: sticky;
            top: 30px;
        }

        .btn.expand-collapse-btn {
            background: transparent !important;
        }

        .expand-collapse-btn:focus,
        .btn.expand-collapse-btn:active {
            border: none !important;
        }

        .expand-collapse-btn:focus-within {
            background-color: transparent !important;
        }

        .expand-collapse-btn:hover {
            color: #6c757d !important;
        }

        button.expand-collapse-btn.text-muted {
            background: transparent;
            padding: 10px;
        }

        .category-tree-btn {
            background: transparent;
            border: 1px solid;
            font-size: 0.875rem;
            border: 1px solid #ebebeb;
            color: #474747;
            border-radius: 3.2px;
        }

        .category-tree-btn:hover {
            background: #f7f7f7 !important;
            color: #444 !important
        }

        .category-tree-btn:disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .category-tree-btn.active {
            background-color: #4634ff;
            color: #fff;
        }

        .category-tree-btn.active:hover {
            background-color: #4634ff !important;
            color: #fff !important;
        }

        .jstree-default .jstree-clicked {
            background: #4634ffcf;
            border-radius: 2px;
            box-shadow: inset 0 0 1px #999999;
            color: #ffffff;
        }
    </style>
@endpush
