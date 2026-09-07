(function ($) {
    "use strict";

    $(document).ready(function () {
        var planHistoryUrl = $('#plan-history-route').val();
        if (planHistoryUrl && $('#packageHistory').length) {
            if ($.fn.DataTable.isDataTable('#packageHistory')) {
                $('#packageHistory').DataTable().destroy();
            }
            $('#packageHistory').DataTable({
                pageLength: 10,
                ordering: false,
                serverSide: true,
                processing: true,
                responsive: true,
                searching: true,
                paging: false,
                info: false,
                ajax: { url: planHistoryUrl },
                language: {
                    paginate: {
                        previous: "<i class='fa fa-chevron-left'></i>",
                        next: "<i class='fa fa-chevron-right'></i>"
                    }
                },
                dom: 't',
                columnDefs: [{ targets: 'keep-show', className: 'all' }],
                columns: [
                    { "data": "plan_name", responsivePriority: 1 },
                    { "data": "subscription_type" },
                    { "data": "start_date" },
                    { "data": "end_date" },
                    { "data": "status", searchable: false, responsivePriority: 2 },
                ]
            });
        }

        var transactionHistoryUrl = $('#transaction-history-route').val();
        if (transactionHistoryUrl && $('#transactionHistory').length) {
            if ($.fn.DataTable.isDataTable('#transactionHistory')) {
                $('#transactionHistory').DataTable().destroy();
            }
            $('#transactionHistory').DataTable({
                pageLength: 10,
                ordering: false,
                serverSide: true,
                processing: true,
                responsive: true,
                searching: true,
                paging: false,
                info: false,
                ajax: { url: transactionHistoryUrl },
                language: {
                    paginate: {
                        previous: "<i class='fa fa-chevron-left'></i>",
                        next: "<i class='fa fa-chevron-right'></i>"
                    }
                },
                dom: 't',
                columnDefs: [{ targets: 'keep-show', className: 'all' }],
                columns: [
                    { "data": "tnxId", responsivePriority: 1 },
                    { "data": "amount" },
                    { "data": "purpose" },
                    { "data": "payment_time" },
                    { "data": "payment_method" },
                ]
            });
        }

        // --- Pricing modal: monthly/yearly toggle ---
        var pricingToggle = document.getElementById('pricing-toggle');
        var pricingModalEl = document.getElementById('pricingModal');

        // Function to update plan buttons based on current subscription
        function updatePlanButtons() {
            if (!pricingToggle || !pricingModalEl) return;

            var currentSubType = parseInt(pricingModalEl.getAttribute('data-current-subscription-type'));
            var monthlyType = parseInt(pricingToggle.getAttribute('data-monthly-type')) || 1;
            var yearlyType = parseInt(pricingToggle.getAttribute('data-yearly-type')) || 2;
            var isYearly = pricingToggle.checked;
            var selectedType = isYearly ? yearlyType : monthlyType;

            document.querySelectorAll('.pricing-card').forEach(function (card) {
                var btn = card.querySelector('button');
                if (!btn) return;

                var isCurrentCard = card.classList.contains('current-plan');

                if (isCurrentCard && currentSubType == selectedType) {
                    // Same package AND same billing period - show Current Plan
                    btn.textContent = 'Current Plan';
                    btn.classList.add('btn-outline');
                    btn.classList.remove('btn-subscribe');
                    btn.disabled = true;
                } else if (isCurrentCard) {
                    // Same package but different billing period - show Subscribe Now
                    btn.textContent = 'Subscribe Now';
                    btn.classList.remove('btn-outline');
                    btn.classList.add('btn-subscribe');
                    btn.disabled = false;
                }
                // Other packages keep their default button
            });
        }

        if (pricingToggle) {
            var amounts = document.querySelectorAll('.pricing-grid .amount');
            var cycles = document.querySelectorAll('.pricing-grid .cycle');
            var monthlyLabel = pricingToggle.getAttribute('data-monthly-label') || 'Monthly';
            var yearlyLabel = pricingToggle.getAttribute('data-yearly-label') || 'Yearly';

            pricingToggle.addEventListener('change', function () {
                var isYearly = pricingToggle.checked;
                amounts.forEach(function (span) {
                    span.textContent = isYearly ? span.dataset.yearly : span.dataset.monthly;
                });
                cycles.forEach(function (span) {
                    span.textContent = isYearly ? yearlyLabel : monthlyLabel;
                });
                // Update button states based on toggle
                updatePlanButtons();
            });

            // Auto-select billing period based on current subscription when modal opens
            if (pricingModalEl) {
                pricingModalEl.addEventListener('show.bs.modal', function () {
                    var currentSubType = pricingModalEl.getAttribute('data-current-subscription-type');
                    var monthlyType = parseInt(pricingToggle.getAttribute('data-monthly-type')) || 1;
                    var yearlyType = parseInt(pricingToggle.getAttribute('data-yearly-type')) || 2;

                    if (currentSubType == yearlyType) {
                        // Current is yearly - check the toggle
                        if (!pricingToggle.checked) {
                            pricingToggle.checked = true;
                            pricingToggle.dispatchEvent(new Event('change'));
                        }
                    } else if (currentSubType == monthlyType) {
                        // Current is monthly - uncheck the toggle
                        if (pricingToggle.checked) {
                            pricingToggle.checked = false;
                            pricingToggle.dispatchEvent(new Event('change'));
                        }
                    }
                    // Initial button update
                    setTimeout(updatePlanButtons, 100);
                });
            }
        }

        // --- Subscribe: open payment modal and set details ---
        var pricingModalEl = document.getElementById('pricingModal');
        var paymentModalEl = document.getElementById('paymentModal');
        var defaultSymbol = $('.pricing-grid .currency-symbol').first().text().trim() || '$';
        var currencyPlacement = $('#currencyPlacement').val() || 'before';

        function formatPrice(amount, symbol) {
            var s = symbol || defaultSymbol;
            var amt = parseFloat(amount).toFixed(2);
            return currencyPlacement === 'after' ? amt + ' ' + s : s + ' ' + amt;
        }

        $(document).on('click', '.pricing-grid .btn-subscribe', function () {
            var card = $(this).closest('.pricing-card');
            var planName = card.data('package-name');
            var priceSpan = card.find('.amount');
            var price = priceSpan.text().trim();
            var cycle = card.find('.cycle').text().trim();
            var packageId = card.data('package-id');
            var isYearly = pricingToggle && pricingToggle.checked;

            $('#payment-package-id').val(packageId);
            $('#amount').val(price);
            $('#det-plan').text(planName);
            $('#det-type').text(cycle);
            $('#det-amount').text(formatPrice(price));
            $('#footer-amt').text('(' + formatPrice(price) + ')');

            if (pricingToggle) {
                var monthlyType = pricingToggle.getAttribute('data-monthly-type');
                var yearlyType = pricingToggle.getAttribute('data-yearly-type');
                $('#payment-subscription-type').val(isYearly ? yearlyType : monthlyType);
            }

            if (typeof bootstrap !== 'undefined' && pricingModalEl && paymentModalEl) {
                var pricingInstance = bootstrap.Modal.getInstance(pricingModalEl);
                if (pricingInstance) {
                    pricingInstance.hide();
                }
                new bootstrap.Modal(paymentModalEl).show();
            }

            // Reset gateway/currency state when opening payment modal
            $('.gateway-item').removeClass('active');
            $('.gateway-item .select-gate-btn').each(function () {
                var btn = $(this);
                var label = btn.data('select-label') || 'Select';
                btn.text(label);
            });
            $('#currencyAppend').html('').hide();
            $('#bankAppend').addClass('d-none');
            $('#det-amount').text('-');
            $('#payment-bank-select').find('option:not(:first)').remove().prop('required', false);
            $('#deposit-slip-input').val('').prop('required', false);
            $('#bank-details-box').addClass('d-none');
        });

        // --- Gateway selection and currency loading (dynamic currency) ---
        var getCurrencyRoute = $('#getCurrencyByGatewayRoute').val();

        $(document).on('click', '.gateway-item', function () {
            var item = $(this);
            $('.gateway-item').removeClass('active');
            $('.gateway-item .select-gate-btn').each(function () {
                var btn = $(this);
                var label = btn.data('select-label') || 'Select';
                btn.text(label);
            });

            item.addClass('active');
            var activeBtn = item.find('.select-gate-btn');
            var selectedLabel = activeBtn.data('selected-label') || 'Selected';
            activeBtn.text(selectedLabel);


            var radio = item.find('input[type="radio"]');
            radio.prop('checked', true);

            var gatewayId = radio.val();
            var gatewaySlug = item.data('gateway-slug') || '';

            // Bank section: show for bank gateway, hide for others
            if (gatewaySlug === 'bank') {
                $('#bankAppend').removeClass('d-none');
                $('#payment-bank-select').prop('required', true);
                $('#deposit-slip-input').prop('required', true);
            } else {
                $('#bankAppend').addClass('d-none');
                $('#payment-bank-select').prop('required', false).val('');
                $('#deposit-slip-input').prop('required', false).val('');
                $('#bank-details-box').addClass('d-none');
            }

            if (!getCurrencyRoute || !gatewayId) return;

            $.ajax({
                url: getCurrencyRoute,
                type: 'GET',
                data: { id: gatewayId },
                success: function (response) {
                    var data = response.data || {};
                    var currencies = data.currencies || [];
                    var banks = data.banks || [];

                    if (currencies.length > 0) {
                        var baseAmount = parseFloat($('#amount').val()) || 0;
                        var currencyHtml = '';
                        currencies.forEach(function (currency, idx) {
                            var rate = parseFloat(currency.conversion_rate) || 1;
                            var symbol = currency.symbol || currency.currency || '';
                            var converted = (baseAmount * rate).toFixed(2);
                            var label = currency.currency + (currency.symbol ? ' (' + currency.symbol + ')' : '');
                            var calcText = baseAmount.toFixed(2) + ' * ' + rate + ' = ' + converted;
                            var checked = idx === 0 ? ' checked' : '';
                            currencyHtml += '<div class="rate-row payment-currency-row" data-symbol="' + (symbol || '').replace(/"/g, '&quot;') + '" data-rate="' + rate + '" data-id="' + currency.id + '">';
                            currencyHtml += '<label class="radio-container"><input type="radio" name="currency" value="' + currency.id + '"' + checked + '><span class="checkmark"></span></label>';
                            currencyHtml += '<span class="currency-label">' + label + '</span>';
                            currencyHtml += '<span class="calc-line">' + calcText + '</span>';
                            currencyHtml += '</div>';
                        });
                        $('#currencyAppend').html(currencyHtml).show();
                        updateFooterAmount();
                    } else {
                        $('#currencyAppend').html('').hide();
                    }

                    // Bank gateway: populate bank dropdown
                    if (banks.length > 0) {
                        var bankSelect = $('#payment-bank-select');
                        bankSelect.find('option:not(:first)').remove();
                        banks.forEach(function (bank) {
                            if (bank.status == 1) {
                                var details = (bank.details || '').replace(/"/g, '&quot;').replace(/\n/g, '<br>');
                                bankSelect.append('<option value="' + bank.id + '" data-details="' + details + '">' + (bank.name || '') + '</option>');
                            }
                        });
                        $('#bankAppend').data('banks', banks);
                    } else {
                        $('#payment-bank-select').find('option:not(:first)').remove();
                        $('#bankAppend').removeData('banks');
                    }
                },
                error: function () {
                    $('#currencyAppend').html('').hide();
                    $('#payment-bank-select').find('option:not(:first)').remove();
                }
            });
        });

        // Bank select change: show bank details
        $(document).on('change', '#payment-bank-select', function () {
            var selected = $(this).find('option:selected');
            var details = selected.data('details') || '';
            var box = $('#bank-details-box');
            if (details) {
                box.find('#bank-details-content').html(details);
                box.removeClass('d-none');
            } else {
                box.find('#bank-details-content').html('');
                box.addClass('d-none');
            }
        });

        function updateFooterAmount() {
            var baseAmount = parseFloat($('#amount').val()) || 0;
            var checkedRow = $('.payment-currency-row input[name="currency"]:checked').closest('.payment-currency-row');
            if (!checkedRow.length) {
                var defAmt = formatPrice(baseAmount);
                $('#footer-amt').text('(' + defAmt + ')');
                $('#det-amount').text(defAmt);
                return;
            }
            var rate = parseFloat(checkedRow.data('rate')) || 1;
            var symbol = checkedRow.data('symbol') || defaultSymbol;
            var converted = (baseAmount * rate).toFixed(2);
            var formatted = currencyPlacement === 'after' ? converted + ' ' + symbol : symbol + ' ' + converted;
            $('#footer-amt').text('(' + formatted + ')');
            $('#det-amount').text(formatted);
        }

        $(document).on('change', 'input[name="currency"]', updateFooterAmount);
    });

    window.cancelSubscriptionModal = function () {
        var form = document.getElementById('cancelSubscriptionForm');
        if (!form) return;
        if (typeof Swal === 'undefined') {
            form.submit();
            return;
        }
        var title = form.getAttribute('data-confirm-title') || 'Sure! You want to cancel subscription?';
        var text = form.getAttribute('data-confirm-text') || "You won't be able to revert this!";
        var confirmBtn = form.getAttribute('data-confirm-btn') || 'Yes, Cancel It!';
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmBtn
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    };
})(jQuery);
