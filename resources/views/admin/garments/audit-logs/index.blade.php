@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@section('content')
<div class="section-title"><h2 class="title">{{ __($title) }}</h2></div>
<div class="section-wrap p-4">
    <form class="row g-3 mb-4">
        <div class="col-md-4"><select name="module" class="form-control"><option value="">{{ __('All Modules') }}</option><option value="Supplier" @selected(request('module') === 'Supplier')>{{ __('Supplier') }}</option><option value="Purchase Order" @selected(request('module') === 'Purchase Order')>{{ __('Purchase Order') }}</option></select></div>
        <div class="col-md-4"><select name="action" class="form-control"><option value="">{{ __('All Actions') }}</option>@foreach(['created','updated','deleted'] as $action)<option value="{{ $action }}" @selected(request('action') === $action)>{{ __(ucfirst($action)) }}</option>@endforeach</select></div>
        <div class="col-md-2"><button class="primary-btn" type="submit">{{ __('Filter') }}</button></div>
    </form>
    <div class="table-responsive"><table class="table primary-table"><thead><tr><th>{{ __('Date') }}</th><th>{{ __('Action') }}</th><th>{{ __('Module') }}</th><th>{{ __('Description') }}</th><th>{{ __('IP Address') }}</th></tr></thead><tbody>@forelse($logs as $log)<tr><td>{{ $log->created_at->format('d M Y, h:i A') }}</td><td>{{ ucfirst($log->action) }}</td><td>{{ $log->module }}</td><td>{{ $log->description }}</td><td>{{ $log->ip_address ?: '-' }}</td></tr>@empty<tr><td colspan="5" class="text-center">{{ __('No audit logs found') }}</td></tr>@endforelse</tbody></table></div>{{ $logs->links() }}
</div>
@endsection
