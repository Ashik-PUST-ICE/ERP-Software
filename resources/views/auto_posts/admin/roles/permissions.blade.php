@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ $title }}
@endpush

@push('style')
<style>
    .rp-page { --rp-primary:var(--primary-color, #FF4F02); --rp-ink:#172033; --rp-muted:#718096; --rp-line:#e9eef5; --rp-soft:#f6f8fc; }
    .rp-page .rp-hero { position:relative; overflow:hidden; padding:25px 28px; border:1px solid #f5e5df; border-radius:18px; background:linear-gradient(115deg,#ffffff 0%,#fff8f5 100%); box-shadow:0 8px 28px rgba(31,45,61,.05); }
    .rp-page .rp-hero:after { content:""; position:absolute; width:210px; height:210px; border-radius:50%; right:-75px; top:-105px; background:rgba(255,79,2,.07); }
    .rp-page .rp-icon { width:48px; height:48px; flex:0 0 48px; display:grid; place-items:center; color:#fff; border-radius:14px; background:linear-gradient(135deg,#FF4F02,#ff8a5c); font-size:20px; box-shadow:0 8px 18px rgba(255,79,2,.24); }
    .rp-page .rp-eyebrow { color:var(--rp-primary); font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
    .rp-page .rp-title { margin:3px 0 0; color:#111827; font-size:22px; font-weight:700; }
    .rp-page .rp-subtitle { color:#4b5563; font-size:13px; }
    .rp-page .rp-stat { min-width:112px; padding:10px 14px; border-left:1px solid #e2e9f3; }
    .rp-page .rp-stat strong { display:block; color:var(--rp-ink); font-size:20px; line-height:1.1; }
    .rp-page .rp-stat span { color:var(--rp-muted); font-size:11px; }
    .rp-page .rp-body { display:flex; min-height:510px; margin-top:20px; overflow:hidden; border:1px solid var(--rp-line); border-radius:18px; background:#fff; box-shadow:0 8px 28px rgba(31,45,61,.04); }
    .rp-page .rp-modules { width:245px; flex:0 0 245px; padding:20px 14px; border-right:1px solid var(--rp-line); background:#fbfcfe; }
    .rp-page .rp-modules-title { padding:0 10px 11px; color:#98a2b3; font-size:11px; font-weight:700; letter-spacing:.07em; text-transform:uppercase; }
    .rp-page .rp-module { display:flex; align-items:center; justify-content:space-between; width:100%; margin:3px 0; padding:11px 12px; border:0; border-radius:10px; color:#1f2937; background:transparent; text-align:left; font-size:13px; font-weight:500; transition:.2s; }
    .rp-page .rp-module:hover { color:var(--rp-primary); background:#fff5f1; }
    .rp-page .rp-module.active { color:var(--rp-primary); background:#fff1eb; font-weight:600; box-shadow:inset 3px 0 0 var(--rp-primary); }
    .rp-page .rp-module i { width:20px; color:#9aa7b7; }
    .rp-page .rp-module.active i { color:var(--rp-primary); }
    .rp-page .rp-module-count { min-width:24px; padding:2px 6px; border-radius:20px; color:#7d8b9b; background:#edf1f6; font-size:10px; text-align:center; }
    .rp-page .rp-module.active .rp-module-count { color:var(--rp-primary); background:#ffe0d3; }
    .rp-page .rp-content { min-width:0; flex:1; padding:24px 28px; }
    .rp-page .rp-content-head { display:flex; align-items:center; justify-content:space-between; gap:15px; padding-bottom:19px; border-bottom:1px solid var(--rp-line); }
    .rp-page .rp-content-head h3 { margin:0 0 4px; color:#111827; font-size:18px; font-weight:700; }
    .rp-page .rp-content-head p { margin:0; color:#4b5563; font-size:12px; }
    .rp-page .rp-search { position:relative; width:270px; }
    .rp-page .rp-search i { position:absolute; top:12px; left:13px; color:#9aa7b7; font-size:13px; }
    .rp-page .rp-search input { height:39px; padding:0 35px; border:1px solid #e0e7f0; border-radius:9px; color:var(--rp-ink); font-size:12px; }
    .rp-page .rp-search input:focus { border-color:#ffb79d; box-shadow:0 0 0 3px rgba(255,79,2,.09); }
    .rp-page .rp-clear-search { position:absolute; top:8px; right:8px; padding:3px 6px; border:0; color:#9aa7b7; background:transparent; display:none; }
    .rp-page .rp-section { padding-top:21px; }
    .rp-page .rp-section-title { display:flex; align-items:center; justify-content:space-between; margin-bottom:13px; }
    .rp-page .rp-section-title h4 { margin:0; color:#111827; font-size:15px; font-weight:700; }
    .rp-page .rp-section-title span { color:var(--rp-muted); font-size:11px; }
    .rp-page .rp-select-all { padding:6px 10px; border:1px solid #dce6f3; border-radius:7px; color:var(--rp-primary); background:#f5f8ff; font-size:11px; }
    .rp-page .rp-select-all:hover { background:#eaf1ff; }
    .rp-page .rp-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:11px; }
    .rp-page .rp-permission { display:flex; align-items:flex-start; gap:11px; min-height:67px; padding:14px; border:1px solid #e9eef5; border-radius:11px; background:#fff; cursor:pointer; transition:.2s; }
    .rp-page .rp-permission:hover { border-color:#ffc7b0; background:#fffaf8; transform:translateY(-1px); box-shadow:0 5px 14px rgba(255,79,2,.08); }
    .rp-page .rp-permission.checked { border-color:#ffb08f; background:#fff5f0; }
    .rp-page .rp-permission input { width:17px; height:17px; flex:0 0 17px; margin:1px 0 0; accent-color:var(--rp-primary); cursor:pointer; }
    .rp-page .rp-permission span { color:#111827; cursor:pointer; font-size:13px; font-weight:600; line-height:1.45; }
    .rp-page .rp-pagination { display:flex; align-items:center; justify-content:flex-end; gap:5px; margin-top:18px; }
    .rp-page .rp-pagination button { min-width:30px; height:30px; padding:0 8px; border:1px solid #e4e9f0; border-radius:7px; color:#4b5563; background:#fff; font-size:11px; }
    .rp-page .rp-pagination button:hover, .rp-page .rp-pagination button.active { border-color:var(--rp-primary); color:#fff; background:var(--rp-primary); }
    .rp-page .rp-pagination button:disabled { opacity:.45; cursor:not-allowed; }
    .rp-page .rp-pagination-info { margin-right:auto; color:#7b8794; font-size:11px; }
    .rp-page .rp-module-pagination { display:flex; align-items:center; justify-content:center; gap:4px; margin-top:13px; padding-top:12px; border-top:1px solid #edf0f4; }
    .rp-page .rp-module-pagination button { min-width:26px; height:26px; padding:0 6px; border:1px solid #e4e9f0; border-radius:6px; color:#667085; background:#fff; font-size:10px; }
    .rp-page .rp-module-pagination button:hover, .rp-page .rp-module-pagination button.active { border-color:var(--rp-primary); color:#fff; background:var(--rp-primary); }
    .rp-page .rp-module-pagination button:disabled { opacity:.45; cursor:not-allowed; }
    .rp-page .rp-empty { padding:45px 15px; color:#98a2b3; text-align:center; }
    .rp-page .rp-footer { display:flex; align-items:center; justify-content:space-between; gap:15px; margin-top:26px; padding-top:18px; border-top:1px solid var(--rp-line); }
    .rp-page .rp-footer-note { color:var(--rp-muted); font-size:11px; }
    .rp-page .rp-save { padding:10px 18px; border:0; border-radius:8px; color:#fff; background:var(--rp-primary); font-size:13px; box-shadow:0 5px 12px rgba(37,99,235,.2); }
    .rp-page .rp-save:hover { background:#1d4ed8; }
    @media(max-width:991px) { .rp-page .rp-body{display:block}.rp-page .rp-modules{width:100%;border-right:0;border-bottom:1px solid var(--rp-line);padding:12px;display:flex;gap:7px;overflow-x:auto}.rp-page .rp-modules-title{display:none}.rp-page .rp-module{min-width:max-content;margin:0}.rp-page .rp-content{padding:20px}.rp-page .rp-stat{display:none} }
    @media(max-width:575px) { .rp-page .rp-hero{padding:18px}.rp-page .rp-content-head{display:block}.rp-page .rp-search{width:100%;margin-top:15px}.rp-page .rp-grid{grid-template-columns:1fr}.rp-page .rp-footer{display:block}.rp-page .rp-save{width:100%;margin-top:12px} }
</style>
@endpush

@section('content')
@php
    $selectedPermissions = collect($oldPermissions ?? []);
    $totalPermissions = collect($permissions)->flatten()->count();
@endphp

<div class="section-title"><h2 class="title">{{ __($title) }}</h2><a href="{{ route('admin.roles.index') }}" class="primary-btn btn-secondary"><i class="fa fa-arrow-left me-2"></i>{{ __('Back to Roles') }}</a></div>

<div class="rp-page mt-4">
    <div class="rp-hero d-flex align-items-center justify-content-between gap-4">
        <div class="d-flex align-items-center gap-3 position-relative" style="z-index:1"><div class="rp-icon"><i class="fa-solid fa-shield-halved"></i></div><div><div class="rp-eyebrow">{{ __('Access Control') }}</div><h3 class="rp-title">{{ $role->display_name }}</h3><div class="rp-subtitle">{{ __('Configure permissions for this role') }}</div></div></div>
        <div class="d-flex position-relative" style="z-index:1"><div class="rp-stat"><strong id="selected-permission-count">{{ $selectedPermissions->count() }}</strong><span>{{ __('Selected permissions') }}</span></div><div class="rp-stat"><strong>{{ $totalPermissions }}</strong><span>{{ __('Available permissions') }}</span></div></div>
    </div>

    <form data-handler="commonResponse" action="{{ route('admin.roles.update.permissions', [$role->id]) }}" method="POST" class="ajax">
        @method('put') @csrf
        <div class="rp-body">
            <aside class="rp-modules"><div class="rp-modules-title">{{ __('Permission Modules') }}</div>
                <div class="rp-module-list">
                    @forelse($permissions as $module => $modulePermissions)
                        <button type="button" class="rp-module {{ $loop->first ? 'active' : '' }}" data-module="{{ $module }}"><span><i class="fa-solid fa-layer-group"></i>{{ moduleName($module) }}</span><span class="rp-module-count">{{ collect($modulePermissions)->count() }}</span></button>
                    @empty <div class="rp-empty">{{ __('No modules found.') }}</div> @endforelse
                </div>
                <div class="rp-module-pagination"></div>
            </aside>
            <main class="rp-content">
                <div class="rp-content-head"><div><h3>{{ __('Role permissions') }}</h3><p>{{ __('Choose what this role can access in the admin panel.') }}</p></div><div class="rp-search"><i class="fa-solid fa-magnifying-glass"></i><input type="search" id="permission-search" placeholder="{{ __('Search permissions...') }}"><button type="button" class="rp-clear-search" id="clear-permission-search"><i class="fa-solid fa-xmark"></i></button></div></div>
                @forelse($permissions as $module => $modulePermissions)
                    <section class="rp-section module-content {{ $loop->first ? '' : 'd-none' }}" data-module="{{ $module }}"><div class="rp-section-title"><div><h4>{{ moduleName($module) }}</h4><span>{{ collect($modulePermissions)->count() }} {{ __('permissions available') }}</span></div><button type="button" class="rp-select-all module-select-all" data-module="{{ $module }}">{{ __('Select all') }}</button></div><div class="rp-grid">
                        @foreach($modulePermissions as $permission)
                            <label class="rp-permission permission-item {{ $selectedPermissions->contains($permission->id) ? 'checked' : '' }}" for="permission-{{ $permission->id }}"><input {{ $selectedPermissions->contains($permission->id) ? 'checked' : '' }} class="permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission-{{ $permission->id }}"><span>{{ $permission->display_name }}</span></label>
                        @endforeach
                    </div><div class="rp-pagination"></div></section>
                @empty <div class="rp-empty">{{ __('No permissions available.') }}</div> @endforelse
                <div class="rp-footer"><span class="rp-footer-note"><i class="fa-solid fa-circle-info me-1"></i>{{ __('Changes apply to users assigned to this role.') }}</span><button type="submit" class="rp-save"><i class="fa-solid fa-check me-2"></i>{{ __('Save Permissions') }}</button></div>
            </main>
        </div>
    </form>
</div>
@endsection

@push('script')
<script>
$(function () {
    const pageSize = 10;
    const pages = {};
    let modulePage = 1;

    function renderModuleList(requestedPage) {
        const modules = $('.rp-module');
        const totalPages = Math.max(1, Math.ceil(modules.length / pageSize));
        modulePage = Math.min(Math.max(parseInt(requestedPage || 1, 10), 1), totalPages);
        modules.hide().slice((modulePage - 1) * pageSize, modulePage * pageSize).show();
        const pagination = $('.rp-module-pagination');
        if (modules.length <= pageSize) { pagination.empty(); return; }
        let html = '<button type="button" class="rp-module-page-number" data-page="' + (modulePage - 1) + '" ' + (modulePage === 1 ? 'disabled' : '') + '><i class="fa-solid fa-angle-left"></i></button>';
        for (let number = 1; number <= totalPages; number++) {
            html += '<button type="button" class="rp-module-page-number ' + (number === modulePage ? 'active' : '') + '" data-page="' + number + '">' + number + '</button>';
        }
        html += '<button type="button" class="rp-module-page-number" data-page="' + (modulePage + 1) + '" ' + (modulePage === totalPages ? 'disabled' : '') + '><i class="fa-solid fa-angle-right"></i></button>';
        pagination.html(html);
    }

    function renderPagination(section, total, page) {
        const pagination = section.find('.rp-pagination');
        const totalPages = Math.max(1, Math.ceil(total / pageSize));
        if (total <= pageSize) { pagination.empty(); return; }
        let html = '<span class="rp-pagination-info">' + total + ' {{ __('results') }}</span>';
        html += '<button type="button" class="rp-page-number" data-page="' + (page - 1) + '" ' + (page === 1 ? 'disabled' : '') + '><i class="fa-solid fa-angle-left"></i></button>';
        for (let number = 1; number <= totalPages; number++) {
            html += '<button type="button" class="rp-page-number ' + (number === page ? 'active' : '') + '" data-page="' + number + '">' + number + '</button>';
        }
        html += '<button type="button" class="rp-page-number" data-page="' + (page + 1) + '" ' + (page === totalPages ? 'disabled' : '') + '><i class="fa-solid fa-angle-right"></i></button>';
        pagination.html(html);
    }

    function renderModule(module, requestedPage) {
        const section = $('.module-content').filter(function () { return String($(this).data('module')) === module; });
        const term = $('#permission-search').val().toLowerCase().trim();
        const items = section.find('.permission-item').filter(function () { return !term || $(this).find('span').text().toLowerCase().includes(term); });
        const totalPages = Math.max(1, Math.ceil(items.length / pageSize));
        const page = Math.min(Math.max(parseInt(requestedPage || 1, 10), 1), totalPages);
        pages[module] = page;
        section.find('.permission-item').hide();
        items.slice((page - 1) * pageSize, page * pageSize).show();
        renderPagination(section, items.length, page);
    }

    function updateCount() {
        const count = $('.permission-checkbox:checked').length;
        $('#selected-permission-count').text(count);
        $('.permission-checkbox').each(function () { $(this).closest('.rp-permission').toggleClass('checked', this.checked); });
        $('.module-content:visible').each(function () {
            const boxes = $(this).find('.permission-checkbox');
            const button = $(this).find('.module-select-all');
            button.text(boxes.length && boxes.filter(':checked').length === boxes.length ? @json(__('Clear all')) : @json(__('Select all')));
        });
    }
    $('.rp-module').on('click', function () {
        const module = String($(this).data('module'));
        $('.rp-module').removeClass('active'); $(this).addClass('active');
        $('.module-content').addClass('d-none').filter(function () { return String($(this).data('module')) === module; }).removeClass('d-none');
        $('#permission-search').val('').trigger('input');
        renderModule(module, 1);
    });
    $('#permission-search').on('input', function () {
        const value = $(this).val().toLowerCase().trim();
        $('#clear-permission-search').toggle(!!value);
        const active = String($('.rp-module.active').data('module'));
        renderModule(active, 1);
    });
    $('#clear-permission-search').on('click', function () { $('#permission-search').val('').trigger('input').focus(); });
    $(document).on('click', '.rp-module-page-number:not(:disabled)', function () { renderModuleList($(this).data('page')); });
    $(document).on('click', '.rp-page-number:not(:disabled)', function () { const module = String($(this).closest('.module-content').data('module')); renderModule(module, $(this).data('page')); });
    $('.module-select-all').on('click', function () { const module = String($(this).data('module')); const boxes = $('.module-content').filter(function () { return String($(this).data('module')) === module; }).find('.permission-checkbox'); boxes.prop('checked', boxes.filter(':checked').length !== boxes.length); updateCount(); renderModule(module, pages[module] || 1); });
    $(document).on('change', '.permission-checkbox', updateCount); updateCount();
    renderModuleList(1);
    const firstModule = String($('.rp-module.active').data('module') || ''); if (firstModule) renderModule(firstModule, 1);
});
</script>
@endpush
