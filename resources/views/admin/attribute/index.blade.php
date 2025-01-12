@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Values')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody class="list">
                                @forelse($attributes as $attribute)
                                    <tr>
                                        <td>{{ __($attribute->name) }}</td>
                                        <td>
                                            @if ($attribute->type == Status::ATTRIBUTE_TYPE_TEXT)
                                                @lang('Text')
                                            @elseif ($attribute->type == Status::ATTRIBUTE_TYPE_COLOR)
                                                @lang('Color')
                                            @else
                                                @lang('Image')
                                            @endif
                                        </td>
                                        <td>{{ $attribute->attribute_values_count }}</td>

                                        <td>@php echo $attribute->statusBadge; @endphp</td>

                                        <td>
                                            <div class="button-group">
                                                <button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Edit Attribute Type')" data-resource="{{ $attribute }}">
                                                    <i class="la la-pencil"></i> @lang('Edit')
                                                </button>

                                                <a href="{{ route('admin.attribute.values', $attribute->id) }}" class="btn btn-sm btn-outline--info addValuesBtn" data-resource="{{ $attribute }}" data-has_status="1">
                                                    <i class="la la-eye"></i> @lang('Values')
                                                </a>

                                                @if ($attribute->status == Status::ENABLE)
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.attribute.status', $attribute->id) }}" data-question="@lang('Are you sure to disable this attribute?')">
                                                        <i class="las la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{ route('admin.attribute.status', $attribute->id) }}" data-question="@lang('Are you sure to enable this attribute?')">
                                                        <i class="las la-eye"></i> @lang('Enable')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($attributes->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($attributes) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div id="cuModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Attribute Type')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.attribute.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Name for Admin')</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Type')</label>
                            <select name="type" class="form-control" required>
                                <option value="" disabled selected>@lang('Select One')</option>
                                <option value="1">@lang('Text')</option>
                                <option value="2">@lang('Color')</option>
                                <option value="3">@lang('Image')</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex justify-content-end align-items-center gap-2">
        <x-search-form></x-search-form>
        <button type="button" class="btn btn-outline--primary cuModalBtn h-45 flex-shrink-0" data-modal_title="@lang('Add New Attribute Type')">
            <i class="las la-plus"></i> @lang('Add New')
        </button>
    </div>
@endpush
