@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title d-flex justify-content-between align-items-center"><h2 class="title">{{ $title }}</h2><button class="primary-btn" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa fa-plus me-2"></i>{{ __('Add Gateway') }}</button></div>
<div class="settings-page-area">
    <div class="settings-page-right w-100">
        <div class="section-wrap">
            <div class="section-inner-title"><h3 class="title">{{ __('Payment Gateways') }}</h3></div>
            <div class="row gy-4">
                @forelse($gateways as $gateway)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-payment">
                            <div class="dropdown options-area">
                                <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item gateway-edit" href="javascript:void(0)" data-gateway='@json($gateway)'>
                                            <i class="fa-solid fa-pen me-2"></i>{{ __('Edit') }}
                                        </a>
                                    </li>
                                    <li>
                                        <form method="post" action="{{ route('admin.garments.payment-gateways.destroy', $gateway->id) }}">
                                            @csrf
                                            @method('delete')
                                            <button class="dropdown-item delete-gateway" type="submit" onclick="return confirm('{{ __('Delete this gateway?') }}')">
                                                <i class="fa-solid fa-trash me-2"></i>{{ __('Delete') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            @if($gateway->image)
                                <img class="logo" src="{{ asset($gateway->image) }}" alt="{{ $gateway->title }}">
                            @else
                                <div class="gateway-placeholder"><i class="fa-solid fa-credit-card"></i></div>
                            @endif
                            <div class="bottom-status">
                                <span class="status {{ $gateway->status == ACTIVE ? 'active' : 'deactivate' }}">
                                    {{ $gateway->status == ACTIVE ? __('Active') : __('Deactivate') }}
                                </span>
                                @if($gateway->slug != 'bank' && $gateway->mode)
                                    <span class="status mode-status {{ $gateway->mode == GATEWAY_MODE_LIVE ? 'active' : 'deactivate' }}">
                                        {{ $gateway->mode == GATEWAY_MODE_LIVE ? __('Live') : __('Sandbox') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><p class="text-muted text-center">{{ __('No Garments gateways configured.') }}</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modal fade zModalTwo" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
        <form method="post" action="{{ route('admin.garments.payment-gateways.store') }}">
            @csrf
            <input type="hidden" name="id" id="gateway-id">
            <div class="modal-body zModalTwo-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0" id="editModalLabel">{{ __('Edit Gateway') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="primary-form">
                    <div class="row gy-3">
                        <div class="col-12 text-center mb-3">
                            <div class="form-group mb-0">
                                <div class="upload-profile-photo-box">
                                    <div class="profile-user position-relative d-inline-block">
                                        <img src="" class="image gateway-logo-preview" alt="{{ __('Gateway Logo') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12"><h6 class="text-muted mb-3 fw-600">{{ __('Basic Information') }}</h6></div>
                        <div class="col-md-6"><div class="form-group"><label for="gateway-title" class="form-label">{{ __('Title') }}</label><input name="title" id="gateway-title" class="form-control title" required></div></div>
                        <div class="col-md-6"><div class="form-group"><label for="gateway-slug" class="form-label">{{ __('Slug') }}</label><input name="slug" id="gateway-slug" class="form-control slug" required></div></div>
                        <div class="col-md-6"><div class="form-group"><label for="status" class="form-label">{{ __('Status') }}</label><select name="status" id="status" class="select form-control wide"><option value="0">{{ __('Deactivate') }}</option><option value="1">{{ __('Active') }}</option></select></div></div>
                        <div class="col-md-6 mode-div"><div class="form-group"><label for="mode" class="form-label">{{ __('Mode') }}</label><select name="mode" id="mode" class="select form-control wide"><option value="1">{{ __('Live') }}</option><option value="2">{{ __('Sandbox') }}</option></select></div></div>
                        <div class="col-12 url-div key-secret-div">
                            <hr class="my-3"><h6 class="text-muted mb-3 fw-600">{{ __('API Configuration') }}</h6>
                            <div class="form-group gateway-input" id="gateway-url"><label class="form-label">{{ __('Url') }}/{{ __('Hash') }}</label><input class="form-control" type="text" name="url" id="gateway-url-input" placeholder="{{ __('Enter API URL or Hash') }}"></div>
                            <div class="form-group gateway-input" id="gateway-key"><label class="form-label">{{ __('Key') }}</label><input class="form-control" type="text" name="key" id="gateway-key-input" placeholder="{{ __('Enter API Key') }}"></div>
                            <div class="form-group gateway-input" id="gateway-secret"><label class="form-label">{{ __('Secret') }}</label><input class="form-control" type="password" name="secret" id="gateway-secret-input" placeholder="{{ __('Enter API Secret') }}"></div>
                        </div>
                        <div class="col-12">
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center mb-3"><h6 class="text-muted mb-0 fw-600">{{ __('Conversion Rate') }}</h6><button type="button" class="primary-btn btn-outline add-currency" title="{{ __('Add Currency') }}"><i class="fa fa-plus me-1"></i>{{ __('Add Currency') }}</button></div>
                            <div id="currencyConversionRateSection"></div>
                        </div>
                    </div>
                </div>
                <div class="btn-list mt-4 pt-3 border-top"><button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button><button class="primary-btn" type="submit">{{ __('Update') }}</button></div>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('super_admin/css/gateway.css') }}">
<style>
.single-payment .dropdown-menu { min-width: 9rem; padding: .5rem 0; }
.single-payment .dropdown-menu form { margin: 0; }
.single-payment .dropdown-menu .dropdown-item { width: 100%; border: 0; background: transparent; text-align: left; }
.single-payment .dropdown-menu .delete-gateway { color: #dc3545; }
.gateway-placeholder{width:5rem;height:5rem;margin:auto;display:flex;align-items:center;justify-content:center;background:#fff;color:#808080;font-size:2rem}.gateway-logo-preview{max-width:120px;max-height:60px;object-fit:contain}.currency-conversation-rate{display:flex;align-items:center}.currency-conversation-rate .form-control{min-width:0}.currency-conversation-rate .currency{flex:1}.currency-conversation-rate .conversion-rate{flex:1}
</style>
@endpush

@push('script')
<script>
$(function () {
    const modal = $('#editModal');
    const rows = $('#currencyConversionRateSection');
    let currencyIndex = 0;
    function addCurrency(currency = '', rate = '1') {
        const index = currencyIndex++;
        rows.append('<div class="input-group mb-3 currency-conversation-rate"><input name="currencies[' + index + '][currency]" class="form-control currency" maxlength="8" placeholder="USD" value="' + currency + '" required><span class="input-group-text">1 =</span><input name="currencies[' + index + '][conversion_rate]" class="form-control conversion-rate" type="number" step="0.000001" min="0.000001" value="' + rate + '" required><span class="input-group-text append_currency">' + (currency || 'USD') + '</span><button type="button" class="bg-white border-0 font-24 mr-5 ms-3 removedItem text-danger" title="{{ __('Remove') }}">&times;</button></div>');
    }
    $('.add-currency').on('click', function () { addCurrency(); });
    $(document).on('click', '.removedItem', function () { $(this).closest('.currency-conversation-rate').remove(); });
    $(document).on('change keyup', '.currency', function () { $(this).closest('.currency-conversation-rate').find('.append_currency').text($(this).val().toUpperCase()); });
    $('[data-bs-target="#editModal"]').on('click', function () {
        if (!$(this).hasClass('gateway-edit')) {
            modal.find('form')[0].reset(); $('#gateway-id').val(''); $('.gateway-logo-preview').attr('src', ''); rows.empty(); currencyIndex = 0; addCurrency();
        }
    });
    $('.gateway-edit').on('click', function () {
        const gateway = $(this).data('gateway');
        $('#gateway-id').val(gateway.id);
        $('#gateway-title').val(gateway.title); $('#gateway-slug').val(gateway.slug); $('#gateway-status').val(gateway.status ? 1 : 0); $('#gateway-mode').val(gateway.mode || 2);
        $('#gateway-url').val(gateway.url || ''); $('#gateway-key').val(gateway.key || ''); $('#gateway-secret').val(gateway.secret || '');
        $('.gateway-logo-preview').attr('src', gateway.image ? '{{ asset('') }}' + gateway.image : '');
        rows.empty(); currencyIndex = 0; (gateway.currencies || []).forEach(currency => addCurrency(currency.currency, currency.conversion_rate)); if (!gateway.currencies || !gateway.currencies.length) addCurrency();
        modal.modal('show');
    });
    addCurrency();
});
</script>
@endpush
