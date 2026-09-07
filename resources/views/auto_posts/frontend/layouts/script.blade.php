<!-- js file  -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>
<script src="https://assets.codepen.io/16327/ScrollSmoother.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>



@stack('script')

<script>
    var currencySymbol = "{{ getCurrencySymbol() }}";
    var currencyPlacement = "{{ getCurrencyPlacement() }}";

    @if (Session::has('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @if (Session::has('error'))
        toastr.error("{{ session('error') }}");
    @endif
    @if (Session::has('info'))
        toastr.info("{{ session('info') }}");
    @endif
    @if (Session::has('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    @if (@$errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>
