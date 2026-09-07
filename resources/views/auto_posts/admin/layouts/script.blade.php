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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pickerBox = document.getElementById('custom-picker-box');
    const trigger = document.getElementById('datePickerTrigger');
    const displayDate = document.getElementById('display-date');
    const timeInput = document.getElementById('custom-time');
    const inlineCalendar = document.getElementById('inline-calendar');

    // Only run calendar/picker logic on pages that have these elements (create/edit post)
    if (!inlineCalendar || !trigger || !timeInput) {
        return;
    }

    // Pre-fill saved date/time on edit page (from hidden field – one place for all date picker behaviour)
    let savedDate = null;
    let savedTime = '';
    const scheduledTimeField = document.getElementById('scheduled_time_field');
    if (scheduledTimeField && scheduledTimeField.value && scheduledTimeField.value.trim()) {
        const parts = scheduledTimeField.value.trim().split(/\s+/);
        if (parts[0]) savedDate = parts[0];
        if (parts[1]) savedTime = parts[1].substring(0, 5);
    }

    // 1. Initialize Flatpickr
    const fp = flatpickr("#inline-calendar", {
        inline: true,
        monthSelectorType: "static",
        showMonths: 1,
        defaultDate: savedDate || undefined,
        onChange: function() {
            updateResult();
        }
    });

    if (savedTime) timeInput.value = savedTime;

    // 2. Toggle Visibility
    trigger.addEventListener('click', (e) => {
        e.preventDefault();
        pickerBox.style.display = (pickerBox.style.display === 'none') ? 'block' : 'none';
        updateResult(); // Refresh display when opened
    });

    // 3. Close on Cancel
    document.getElementById('closePicker').addEventListener('click', () => {
        pickerBox.style.display = 'none';
        console.log("closePicker clicked", displayDate.innerHTML);
        // 1️⃣ Clear flatpickr selection
        fp.clear();

        // 2️⃣ Clear time input
        timeInput.value = "";

        // 3️⃣ Reset display text
        displayDate.innerHTML = "Select a date";
        // displayDate.innerHTML = "Select a date";
    });

    // 4. The Master Update Function
    function updateResult() {
        const selectedDate = fp.selectedDates[0];
        const rawTime = timeInput.value;

        // If no date selected
        if (!selectedDate) {
            displayDate.innerHTML = "Select a date";
            return;
        }

        // Format date
        const formattedDate = fp.formatDate(selectedDate, "l, F j, Y");

        // If date selected but no time
        if (!rawTime) {
            displayDate.innerHTML = `<strong>${formattedDate}</strong>`;
            return;
        }

        // Convert 24h to 12h format
        const [hours, minutes] = rawTime.split(":");
        let h = parseInt(hours);
        const ampm = h >= 12 ? "PM" : "AM";
        h = h % 12 || 12;

        const formattedTime = `${h}:${minutes} ${ampm}`;

        displayDate.innerHTML = `<strong>${formattedDate} at ${formattedTime}</strong>`;

    }

    // 5. Handle Schedule Confirmation
    const confirmScheduleBtn = document.getElementById('confirm-schedule');
    if (confirmScheduleBtn) {
        confirmScheduleBtn.addEventListener('click', function() {
            const selectedDate = fp.selectedDates[0];
            const rawTime = timeInput.value;

            if (!selectedDate) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Please select a date');
                } else {
                    alert('Please select a date');
                }
                return;
            }

            if (!rawTime) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Please select a time');
                } else {
                    alert('Please select a time');
                }
                return;
            }

            // Format date as Y-m-d
            const dateStr = fp.formatDate(selectedDate, "Y-m-d");

            // Format time as H:i (already in 24h format from time input)
            const timeStr = rawTime; // Already in HH:mm format

            // Combine: Y-m-d H:i (e.g., 2026-02-16 14:30)
            const scheduledDateTime = `${dateStr} ${timeStr}`;

            // Set hidden field value
            const scheduledTimeField = document.getElementById('scheduled_time_field');
            if (scheduledTimeField) {
                scheduledTimeField.value = scheduledDateTime;
            }

            // Set publish_now to 0 (schedule instead of publish now)
            const publishNowField = document.getElementById('publish_now_field');
            if (publishNowField) {
                publishNowField.value = '0';
            }

            // Update display
            updateResult();

            // Close picker
            pickerBox.style.display = 'none';

            // Show success message
            if (typeof toastr !== 'undefined') {
                toastr.success('Post scheduled successfully');
            }

            console.log('Scheduled time set:', scheduledDateTime);
        });
    }

    // 6. Listen for Time Changes
    // 'input' catches typing, 'change' catches the clock picker
    timeInput.addEventListener('input', updateResult);
    timeInput.addEventListener('change', updateResult);

    // Run once on load so it's not empty
    updateResult();
});
</script>
