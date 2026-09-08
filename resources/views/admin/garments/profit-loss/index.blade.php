@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@section('content')
<div class="section-title"><h2 class="title">{{ __($title) }}</h2><button class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-profit-loss-modal"><i class="fa fa-plus me-2"></i>{{ __('Add P&L Record') }}</button></div>
<div class="settings-page-area"><div class="settings-page-right"><div class="section-wrap"><div class="table-waraper"><input type="hidden" id="profit-loss-route" value="{{ route('admin.garments.profit-loss.index') }}"><table class="display primary-table dataTable dtr-inline" id="garmentProfitLossDataTable"><thead><tr><th class="keep-show">{{ __('SL') }}</th><th>{{ __('Order') }}</th><th>{{ __('Revenue') }}</th><th>{{ __('Total Cost') }}</th><th>{{ __('Profit / Margin') }}</th><th>{{ __('Status') }}</th><th class="keep-show">{{ __('Action') }}</th></tr></thead><tbody></tbody></table></div></div></div></div>
<div class="modal fade zModalTwo" id="add-profit-loss-modal" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content zModalTwo-content">@include('admin.garments.profit-loss.form',['profitLoss'=>null,'orders'=>$orders])</div></div></div><div class="modal fade zModalTwo" id="edit-profit-loss-modal" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content zModalTwo-content"></div></div></div>
@endsection
@push('style')
<style>
    #add-profit-loss-modal .modal-dialog,#edit-profit-loss-modal .modal-dialog{max-width:980px;height:calc(100% - 2rem);min-height:0}#add-profit-loss-modal .modal-content,#edit-profit-loss-modal .modal-content{height:100%;max-height:100%;overflow:hidden}#add-profit-loss-modal form,#edit-profit-loss-modal form{display:flex;flex-direction:column;height:100%}#add-profit-loss-modal .modal-body,#edit-profit-loss-modal .modal-body{flex:1;min-height:0;overflow-y:auto;padding:30px}.modal-form-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px}.modal-form-header h4{font-size:20px;font-weight:500;line-height:28px;color:#1b1c17;margin:0 0 4px}.modal-form-header p{font-size:13px;color:#808080;margin:0}.profit-form-section{border:1px solid #eee8e5;border-radius:10px;background:#fff;padding:20px}.profit-form-section+.profit-form-section{margin-top:16px}.profit-section-title{display:flex;align-items:center;gap:12px;border-bottom:1px solid #f0ecea;padding-bottom:14px;margin-bottom:18px}.profit-section-title>span{width:36px;height:36px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;color:#ff4f02;background:#fff2ec}.profit-section-title h5{font-size:15px;font-weight:600;color:#1b1c17;margin:0 0 3px}.profit-section-title p{font-size:12px;color:#808080;margin:0}.profit-loss-form .form-group{margin-bottom:0}.profit-loss-form .form-control{min-height:44px}
</style>
@endpush
@push('script')
<script>
$(function(){var t=$('#garmentProfitLossDataTable').DataTable({pageLength:10,ordering:false,serverSide:true,processing:true,responsive:true,dom:'t',ajax:$('#profit-loss-route').val(),columns:[{data:'sl'},{data:'order_number'},{data:'sales_revenue'},{data:'total_cost'},{data:'profit_display'},{data:'status'},{data:'action'}],columnDefs:[{targets:'keep-show',className:'all'}]});});
</script>
@endpush
