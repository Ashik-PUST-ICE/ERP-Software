@if(session('payment_form_html'))
<div id="virtualPaymentForm" style="display: none;">
    {!! session('payment_form_html') !!}
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Find the form inside the hidden div and submit it automatically
    var virtualForm = document.getElementById('virtualPaymentForm').querySelector('form');
    if (virtualForm) {
        virtualForm.submit();
    }
});
</script>
@endif

<!-- js file  -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins.js') }}"></script>
@if(file_exists(public_path('assets/js/lc_select.min.js')))
<script src="{{ asset('assets/js/lc_select.min.js') }}"></script>
@endif
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('common/js/common.js') }}"></script>

@stack('script')

<style>
{!! getOption('custom_css') !!}
</style>

<script>
var currencySymbol = "{{ getCurrencySymbol() }}";
var currencyPlacement = "{{ getCurrencyPlacement() }}";

@if(Session::has('success'))
toastr.success("{{ session('success') }}");
@endif
@if(Session::has('error'))
toastr.error("{{ session('error') }}");
@endif
@if(Session::has('info'))
toastr.info("{{ session('info') }}");
@endif
@if(Session::has('warning'))
toastr.warning("{{ session('warning') }}");
@endif

@if(@$errors->any())
@foreach($errors->all() as $error)
toastr.error("{{ $error }}");
@endforeach
    @endif
</script>
