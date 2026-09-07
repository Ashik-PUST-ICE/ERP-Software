/**
 * Campaign schedule date & time picker (inline calendar + time input).
 * When user selects date/time and clicks "Schedule", the time is saved and will appear on the calendar.
 * Requires: flatpickr (global)
 */
document.addEventListener('DOMContentLoaded', function () {
    const pickerBox = document.getElementById('campaign-custom-picker-box');
    const trigger = document.getElementById('campaignDatePickerTrigger');
    const displayDate = document.getElementById('campaign-display-date');
    const timeInput = document.getElementById('campaign-custom-time');
    const inlineCalendar = document.getElementById('campaign-inline-calendar');
    const scheduledTimeField = document.getElementById('campaign_scheduled_time_field');
    const closePickerBtn = document.getElementById('campaign-closePicker');
    const confirmScheduleBtn = document.getElementById('campaign-confirm-schedule');

    if (!inlineCalendar || !trigger || !timeInput) {
        return;
    }

    if (typeof flatpickr === 'undefined') {
        console.error('flatpickr is not loaded, campaign date picker will not work.');
        return;
    }

    // Parse saved date/time from edit page (format: "Y-m-d H:i" or "Y-m-d H:i:s")
    var savedDate = null;
    var savedTime = '';
    if (scheduledTimeField && scheduledTimeField.value && scheduledTimeField.value.trim()) {
        var parts = scheduledTimeField.value.trim().split(/\s+/);
        if (parts[0]) savedDate = parts[0]; // Y-m-d
        if (parts[1]) savedTime = parts[1].substring(0, 5); // HH:mm
    }

    const fp = flatpickr('#campaign-inline-calendar', {
        inline: true,
        monthSelectorType: 'static',
        showMonths: 1,
        defaultDate: savedDate || undefined,
        onChange: function () {
            updateCampaignDisplay();
        }
    });

    // Pre-fill time input on edit page when saved date/time exists
    if (savedTime && timeInput) {
        timeInput.value = savedTime;
    }

    /** Only update the display text; do not set hidden fields until "Schedule" is clicked */
    function updateCampaignDisplay() {
        const selectedDate = fp.selectedDates[0];
        const rawTime = timeInput.value;

        if (!selectedDate) {
            if (displayDate) displayDate.innerHTML = 'Select a date';
            return;
        }

        const formattedDate = fp.formatDate(selectedDate, 'l, F j, Y');
        if (!rawTime) {
            if (displayDate) displayDate.innerHTML = '<strong>' + formattedDate + '</strong> - Select a time';
            return;
        }

        const [hours, minutes] = rawTime.split(':');
        let h = parseInt(hours, 10);
        const m = (minutes || '00').substring(0, 2);
        if (isNaN(h) || h < 0 || h > 23) {
            if (displayDate) displayDate.innerHTML = formattedDate + ' - Invalid time';
            return;
        }
        const ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        const formattedTime = h + ':' + m + ' ' + ampm;
        if (displayDate) {
            displayDate.innerHTML = '<strong>' + formattedDate + ' at ' + formattedTime + '</strong>';
        }
    }

    /** On "Schedule" click: set scheduled_time, close picker, show toast. Campaign will show on calendar. */
    if (confirmScheduleBtn) {
        confirmScheduleBtn.addEventListener('click', function () {
            const selectedDate = fp.selectedDates[0];
            const rawTime = timeInput.value;

            if (!selectedDate) {
                if (typeof toastr !== 'undefined') toastr.error('Please select a date');
                else alert('Please select a date');
                return;
            }
            if (!rawTime) {
                if (typeof toastr !== 'undefined') toastr.error('Please select a time');
                else alert('Please select a time');
                return;
            }

            const dateStr = fp.formatDate(selectedDate, 'Y-m-d');
            const timeStr = rawTime.substring(0, 5); // HH:mm
            const scheduledDateTime = dateStr + ' ' + timeStr;

            if (scheduledTimeField) {
                scheduledTimeField.value = scheduledDateTime;
            }

            updateCampaignDisplay();
            if (pickerBox) pickerBox.style.display = 'none';
            if (typeof toastr !== 'undefined') {
                toastr.success('Date and time set. Click "Schedule" to save as scheduled posts (they will appear on the calendar).');
            }
        });
    }

    if (closePickerBtn) {
        closePickerBtn.addEventListener('click', function () {
            fp.clear();
            timeInput.value = '';
            if (displayDate) displayDate.innerHTML = 'Select a date';
            if (pickerBox) pickerBox.style.display = 'none';
        });
    }

    trigger.addEventListener('click', function (e) {
        e.preventDefault();
        if (!pickerBox) return;
        pickerBox.style.display = pickerBox.style.display === 'block' ? 'none' : 'block';
        updateCampaignDisplay();
    });

    timeInput.addEventListener('input', updateCampaignDisplay);
    timeInput.addEventListener('change', updateCampaignDisplay);
    updateCampaignDisplay();
});
