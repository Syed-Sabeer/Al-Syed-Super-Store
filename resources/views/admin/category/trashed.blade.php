@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Thumbnail')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Icon')</th>
                                    <th>@lang('Deleted At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($categories as $category)
                                    <tr>
                                        <td>
                                            {{ $categories->firstItem() + $loop->index }}
                                        </td>
                                        <td>
                                            <div class="table-thumb">
                                                <a href="{{ getImage('assets/images/category/' . @$category->image) }}" class="image-popup">
                                                    <img src="{{ getImage('assets/images/category/' . @$category->image) }}" alt="@lang('profile-image')">
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ __($category->name) }}</td>
                                        <td>@php echo $category->icon @endphp </td>
                                        <td>{{ showDateTime($category->deleted_at, 'd M, Y h:i A') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary confirmationBtn" data-id='{{ $category->id }}' data-action="{{ route('admin.category.delete', $category->id) }}" data-question="@lang('Are you sure to restore this category?')">
                                                <i class="las la-trash-restore"></i> @lang('Restore')
                                            </button>
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

                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex justify-content-end align-items-center flex-wrap gap-2 has-search-form">
        <x-search-form placeholder="Name"></x-search-form>
        <x-back route="{{ route('admin.category.all') }}"></x-back>
    </div>
@endpush
