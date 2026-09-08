@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@section('content')
<div class="section-title"><h2 class="title">{{ __($title) }}</h2><button class="primary-btn" data-bs-toggle="modal" data-bs-target="#purchase-order-modal"><i class="fa fa-plus me-2"></i>{{ __('Add Purchase Order') }}</button></div>
<div class="section-wrap p-4"><input type="hidden" id="purchase-order-data-route" value="{{ route('admin.garments.purchase-orders.index') }}"><table class="display primary-table dataTable" id="purchaseOrderDataTable"><thead><tr><th>{{ __('SL') }}</th><th>{{ __('PO Number') }}</th><th>{{ __('Supplier') }}</th><th>{{ __('Order Date') }}</th><th>{{ __('Expected Date') }}</th><th>{{ __('Amount') }}</th><th>{{ __('Status') }}</th><th>{{ __('Action') }}</th></tr></thead><tbody></tbody></table></div>
<div class="modal fade" id="purchase-order-modal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">@include('admin.garments.purchase-orders.form',['purchaseOrder'=>null,'suppliers'=>$suppliers])</div></div></div>
<div class="modal fade" id="purchase-order-edit-modal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"></div></div></div>
@endsection
@push('script')<script src="{{ asset('admin/js/garment-purchase-orders.js') }}"></script>@endpush
