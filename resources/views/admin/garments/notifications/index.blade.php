@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@section('content')
<div class="section-title d-flex justify-content-between align-items-center"><h2 class="title">{{ __($title) }}</h2><form method="post" action="{{ route('admin.garments.notifications.read-all') }}">@csrf<button class="primary-btn" type="submit">{{ __('Mark All Read') }}</button></form></div>
<div class="section-wrap p-4">@forelse($notifications as $notification)<div class="d-flex justify-content-between align-items-center border-bottom py-3"><div><h5 class="mb-1">{{ $notification->title }}</h5><p class="mb-0 text-muted">{{ $notification->body }}</p></div>@if(!$notification->view_status)<form method="post" action="{{ route('admin.garments.notifications.read',$notification->id) }}">@csrf<button class="primary-btn" type="submit">{{ __('Mark Read') }}</button></form>@endif</div>@empty<p class="text-muted">{{ __('No notifications found') }}</p>@endforelse{{ $notifications->links() }}</div>
@endsection
