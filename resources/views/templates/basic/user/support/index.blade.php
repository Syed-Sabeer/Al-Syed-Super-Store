@extends('Template::layouts.user')

@section('panel')
    <div class="text-end mb-4">
        <a href="{{ route('ticket.open') }}" class="btn btn-outline--light">
            <i class="la la-plus"></i> @lang('New Ticket')
        </a>
    </div>

        <table class="table table--responsive--lg">
            <thead>
                <tr>
                    <th>@lang('Ticket No.')</th>
                    <th>@lang('Subject')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Priority')</th>
                    <th>@lang('Last Reply')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($supports as $support)
                    <tr>
                        <td>#{{ $support->ticket }}</td>
                        <td>{{ $support->subject }}</td>
                        <td>@php echo $support->statusBadge; @endphp</td>
                        <td>@php echo $support->priorityBadge() @endphp</td>
                        <td>{{ diffForHumans($support->last_reply) }} </td>
                        <td><a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn-outline--light"> <i class="la la-desktop"></i> @lang('View')</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center">@lang('No support ticket created yet')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($supports->hasPages())
            <div class="mt-4">
                {{ paginateLinks($supports) }}
            </div>
        @endif

@endsection
