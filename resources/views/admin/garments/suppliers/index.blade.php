@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@section('content')
<div class="section-title"><h2 class="title">{{ __($title) }}</h2><button class="primary-btn" data-bs-toggle="modal" data-bs-target="#supplier-modal"><i class="fa fa-plus me-2"></i>{{ __('Add Supplier') }}</button></div>
<div class="section-wrap p-4"><input type="hidden" id="supplier-data-route" value="{{ route('admin.garments.suppliers.index') }}"><table class="display primary-table dataTable" id="supplierDataTable"><thead><tr><th>{{ __('SL') }}</th><th>{{ __('Code') }}</th><th>{{ __('Company') }}</th><th>{{ __('Contact') }}</th><th>{{ __('Category') }}</th><th>{{ __('Status') }}</th><th>{{ __('Action') }}</th></tr></thead><tbody></tbody></table></div>
<div class="modal fade" id="supplier-modal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">@include('admin.garments.suppliers.form',['supplier'=>null])</div></div></div>
<div class="modal fade" id="supplier-edit-modal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"></div></div></div>
@endsection
@push('script')<script src="{{ asset('admin/js/garment-suppliers.js') }}"></script>@endpush
