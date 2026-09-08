@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@section('content')
<div class="section-title"><h2 class="title">{{ __($title) }}</h2></div>
<div class="section-wrap p-4"><form id="material-scanner-form" class="row g-3"><div class="col-md-8"><label class="form-label">{{ __('Scan or enter barcode / item code') }}</label><input class="form-control" id="material-scanner-code" autocomplete="off" autofocus required></div><div class="col-md-2 align-self-end"><button class="primary-btn" type="submit">{{ __('Lookup') }}</button></div></form><div id="material-scanner-result" class="mt-4"></div></div>
@endsection
@push('script')
<script>
document.getElementById('material-scanner-form').addEventListener('submit', async function (event) { event.preventDefault(); const result = document.getElementById('material-scanner-result'); const response = await fetch('{{ route('admin.garments.materials.scanner.lookup') }}', { method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'}, body: JSON.stringify({code: document.getElementById('material-scanner-code').value}) }); const data = await response.json(); result.innerHTML = response.ok ? `<div class="alert alert-success"><strong>${data.material.item_name}</strong><br>{{ __('Code') }}: ${data.material.item_code}<br>{{ __('Stock') }}: ${data.material.current_stock} ${data.material.unit}</div>` : `<div class="alert alert-danger">${data.message}</div>`; });
</script>
@endpush
