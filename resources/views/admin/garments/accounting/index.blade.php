@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <button class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i>{{ __('Add Entry') }}
    </button>
</div>
<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <input type="hidden" id="accounting-route" value="{{ route('admin.garments.accounting.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="garmentAccountingDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __('SL') }}</th>
                            <th>{{ __('Order') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Account') }}</th>
                            <th>{{ __('Debit') }}</th>
                            <th>{{ __('Credit') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="keep-show">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            @include('admin.garments.accounting.form', ['entry' => null, 'orders' => $orders])
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content"></div>
    </div>
</div>
@endsection
@push('script')
<script>
$(function () {
    var t = $('#garmentAccountingDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: true,
        dom: 't',
        ajax: $('#accounting-route').val(),
        columns: [
            { data: 'sl' },
            { data: 'order_number' },
            { data: 'type_display' },
            { data: 'account_name' },
            { data: 'debit' },
            { data: 'credit' },
            { data: 'status' },
            { data: 'action' }
        ],
        columnDefs: [{ targets: 'keep-show', className: 'all' }]
    });
});
</script>
@endpush
