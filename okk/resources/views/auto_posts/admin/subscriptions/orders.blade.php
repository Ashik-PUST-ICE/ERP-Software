@extends('auto_posts.admin.layouts.admin')
@push('title')
{{ $title }}
@endpush
@section('content')
<div class="p-30">
    <h4 class="fs-24 fw-500 lh-34 text-black pb-16">{{ __($title) }}</h4>
    <div class="row bd-c-ebedf0 bd-half bd-ra-25 bg-white h-100 p-30">
        <div class="col-lg-12">
            <div class="customers__area bg-style mb-30">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button class="border fs-15 fw-500 lh-25 text-1b1c17 py-10 px-26 bd-ra-12 bg-primary orderStatusTab"
                        data-bs-toggle="tab" data-bs-target="#allTabPane" type="button" data-status="All" role="tab"
                        aria-controls="allTabPane" aria-selected="true">{{ __('All') }}
                    </button>
                    <button class="border fs-15 fw-500 lh-25 text-1b1c17 py-10 px-26 bd-ra-12 bg-white orderStatusTab"
                        data-bs-toggle="tab" data-bs-target="#paidTabPane" type="button" role="tab" data-status="Paid"
                        aria-controls="paidTabPane" aria-selected="false" tabindex="-1">{{ __('Paid') }}
                    </button>
                    <button class="border fs-15 fw-500 lh-25 text-1b1c17 py-10 px-26 bd-ra-12 bg-white orderStatusTab"
                        data-bs-toggle="tab" data-status="Pending" data-bs-target="#pendingTabPane" type="button"
                        role="tab" aria-controls="pendingTabPane" aria-selected="false"
                        tabindex="-1">{{ __('Pending') }}
                    </button>
                    <button class="border fs-15 fw-500 lh-25 text-1b1c17 py-10 px-26 bd-ra-12 bg-white orderStatusTab"
                        data-bs-toggle="tab" data-bs-target="#cancelTabPane" type="button" role="tab"
                        aria-controls="cancelTabPane" data-status="Cancelled" aria-selected="false"
                        tabindex="-1">{{ __('Cancelled') }}
                    </button>
                </div>
                <div class="tab-content" id="orderTabContent">
                    <div class="tab-pane fade show active" id="allTabPane" role="tabpanel" aria-labelledby="all-tab"
                        tabindex="0">
                        <div class="table-responsive zTable-responsive">
                            <table class="table zTable" id="orderDataTableAll" aria-describedby="orderDataTableall">
                                <thead>
                                    <tr>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Date') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Transaction Id') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('User Name') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('User Email') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Package') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Gateway') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Amount') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Status') }}
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="paidTabPane" role="tabpanel" aria-labelledby="paid-tab" tabindex="0">
                        <div class="table-responsive zTable-responsive">
                            <table class="table zTable" id="orderDataTablePaid" aria-describedby="orderDataTablepaid">
                                <thead>
                                    <tr>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Date') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Transaction Id') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('User Name') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('User Email') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Package') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Gateway') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Amount') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Payment Info') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Status') }}
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pendingTabPane" role="tabpanel" aria-labelledby="pending-tab"
                        tabindex="0">
                        <div class="table-responsive zTable-responsive">
                            <table class="table zTable" id="orderDataTablePending"
                                aria-describedby="orderDataTablepending">
                                <thead>
                                    <tr>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Date') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Transaction Id') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('User Name') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('User Email') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Package') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Gateway') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Amount') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Status') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Payment Info') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Action') }}
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cancelTabPane" role="tabpanel" aria-labelledby="cancel-tab"
                        tabindex="0">
                        <div class="table-responsive zTable-responsive">
                            <table class="table zTable" id="orderDataTableCancelled"
                                aria-describedby="orderDataTablecancelled">
                                <thead>
                                    <tr>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Date') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Transaction Id') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('User Name') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('User Email') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Package') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Gateway') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Amount') }}
                                        </th>
                                        <th scope="col" class="sorting_disabled text-nowrap" rowspan="1" colspan="1">
                                            {{ __('Status') }}
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Table -->

<div class="modal fade" id="payStatusChangeModal" tabindex="-1" aria-labelledby="payStatusChangeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-18 fw-600 lh-24 text-1b1c17" id="payStatusChangeModalLabel">
                    {{ __('Payment Status Change') }}
                </h4>
                <button type="button" class="w-30 h-30 rounded-circle bd-one bd-c-e4e6eb p-0 bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
            </div>
            <form class="ajax reset" action="{{ route('admin.subscriptions.order.payment.status.change') }}"
                method="post" data-handler="commonResponseForModal">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="primary-form-group">
                            <div class="primary-form-group-wrap">
                                <label class="form-label">{{ __('Status') }}</label>
                                <select class="form-select flex-shrink-0" name="payment_status">
                                    <option selected value="{{PAYMENT_STATUS_PAID}}">{{ __('Paid') }}</option>
                                    <option value="{{PAYMENT_STATUS_CANCELLED}}">{{ __('Cancelled') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit"
                        class="m-0 fs-15 border-0 fw-500 lh-25 py-10 px-26 bg-7f56d9 bd-ra-12">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@push('script')
<script>
(function($) {
    ("use strict");

    $(document).on('click', '.orderPayStatus', function() {
        commonAjax('GET', "{{ route('admin.subscriptions.orders.get.info') }}", getInfoRes, getInfoRes, {
            'id': $(this).data('id')
        });
    });

    function getInfoRes(response) {
        if (!response.status) {
            toastr.error(response.message);
            return;
        }
        const selector = $('#payStatusChangeModal');
        selector.find('input[name=id]').val(response.data.id)
        selector.find('select[name=status]').val(response.data.payment_status)
        selector.modal('show')
    }

    $(document).ready(function() {
        allOrderDataTable('All')
    });

    $(document).on('click', '.orderStatusTab', function(e) {
        $('.orderStatusTab').removeClass('bg-primary').addClass('bg-white');
        $(this).removeClass('bg-white').addClass('bg-primary');

        var status = $(this).data('status');
        allOrderDataTable(status)
    });

    function allOrderDataTable(status) {
        var dataTableColumns = [{
                "data": "date",
                name: "created_at"
            },
            {
                "data": "tnxId",
                name: "payments.tnxId",
                orderable: false
            },
            {
                "data": "userName",
                name: "users.name"
            },
            {
                "data": "userEmail",
                name: "users.email"
            },
            {
                "data": "package",
                orderable: false,
                name: "paymentable.name"
            },
            {
                "data": "gateway",
                orderable: false
            },
            {
                "data": "amount",
                orderable: false
            },
            {
                "data": "status",
                orderable: false
            }
        ]

        if (status == 'Pending') {
            dataTableColumns.push({
                "data": "payment_info",
                responsivePriority: 2,
                searchable: false,
                orderable: false
            });
            dataTableColumns.push({
                "data": "action",
                responsivePriority: 2,
                searchable: false,
                orderable: false
            });
        }

        if (status == 'Paid') {
            dataTableColumns.splice(7, 0, {
                "data": "payment_info",
                responsivePriority: 2,
                searchable: false,
                orderable: false
            });
        }

        $("#orderDataTable" + status).DataTable({
            pageLength: 10,
            ordering: true,
            serverSide: true,
            processing: true,
            order: [
                [0, 'desc']
            ],
            searching: true,
            responsive: {
                breakpoints: [{
                        name: "desktop",
                        width: Infinity
                    },
                    {
                        name: "tablet",
                        width: 1400
                    },
                    {
                        name: "fablet",
                        width: 768
                    },
                    {
                        name: "phone",
                        width: 480
                    },
                ],
            },
            ajax: {
                url: "{{ route('admin.subscriptions.orders.payment.status') }}",
                data: function(data) {
                    data.status = status;
                }
            },
            language: {
                paginate: {
                    previous: "<i class='fa-solid fa-angles-left'></i>",
                    next: "<i class='fa-solid fa-angles-right'></i>",
                },
                searchPlaceholder: "Search here....",
                search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
            },
            dom: '<"tableTop"<"row align-items-center"<"col-sm-6"<"tableSearch float-start"f>><"col-sm-6"<"tableLengthInput float-end"l>>>>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',

            columns: dataTableColumns,
            stateSave: true,
            "bDestroy": true
        });
    }
})(jQuery);
</script>
@endpush