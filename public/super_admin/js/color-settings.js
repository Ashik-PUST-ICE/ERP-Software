document.addEventListener('DOMContentLoaded', function () {
    // Validate hex color code
    function isValidHexColor(hex) {
        return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(hex);
    }

    // Normalize hex color (convert 3-digit to 6-digit)
    function normalizeHexColor(hex) {
        if (!hex) return '';
        hex = hex.trim();
        if (hex.charAt(0) !== '#') {
            hex = '#' + hex;
        }
        if (hex.length === 4) {
            // Convert #RGB to #RRGGBB
            hex = '#' + hex.charAt(1) + hex.charAt(1) +
                hex.charAt(2) + hex.charAt(2) +
                hex.charAt(3) + hex.charAt(3);
        }
        return hex.toUpperCase();
    }

    // Update color display from picker
    function updateColorFromPicker(pickerId) {
        var picker = document.getElementById(pickerId);
        if (picker) {
            var previewId = pickerId + '_preview';
            var valueId = pickerId + '_value';
            var preview = document.getElementById(previewId);
            var valueInput = document.getElementById(valueId);

            var colorValue = normalizeHexColor(picker.value);

            if (preview) {
                preview.style.backgroundColor = colorValue;
            }
            if (valueInput) {
                valueInput.value = colorValue;
                valueInput.setCustomValidity('');
            }
        }
    }

    // Update color from text input
    function updateColorFromInput(inputId) {
        var valueInput = document.getElementById(inputId);
        if (!valueInput) return;

        var pickerId = inputId.replace('_value', '');
        var previewId = pickerId + '_preview';
        var picker = document.getElementById(pickerId);
        var preview = document.getElementById(previewId);

        var inputValue = valueInput.value.trim();

        // Add # if missing
        if (inputValue && inputValue.charAt(0) !== '#') {
            inputValue = '#' + inputValue;
        }

        // Normalize the color
        var normalizedColor = normalizeHexColor(inputValue);

        if (isValidHexColor(normalizedColor)) {
            // Valid color - update picker and preview
            if (picker) {
                picker.value = normalizedColor;
            }
            if (preview) {
                preview.style.backgroundColor = normalizedColor;
            }
            valueInput.value = normalizedColor;
            valueInput.setCustomValidity('');
            valueInput.classList.remove('is-invalid');
            valueInput.classList.add('is-valid');
        } else if (inputValue.length > 0) {
            // Invalid color
            valueInput.setCustomValidity('Please enter a valid hex color code (e.g., #FF4F02 or #FFF)');
            valueInput.classList.remove('is-valid');
            valueInput.classList.add('is-invalid');
        } else {
            valueInput.setCustomValidity('');
            valueInput.classList.remove('is-invalid', 'is-valid');
        }
    }

    // Initialize all color pickers
    var colorPickerIds = [
        'app_primary_color',
        'app_hover_color',
        'app_text_color',
        'app_text_secondary_color',
        'app_sidebar_bg_color',
        'app_sidebar_text_color'
    ];

    colorPickerIds.forEach(function (pickerId) {
        var picker = document.getElementById(pickerId);
        var valueInput = document.getElementById(pickerId + '_value');

        if (picker) {
            // Initial update
            updateColorFromPicker(pickerId);

            // Update on picker change
            picker.addEventListener('input', function () {
                updateColorFromPicker(pickerId);
            });

            picker.addEventListener('change', function () {
                updateColorFromPicker(pickerId);
            });
        }

        if (valueInput) {
            // Update on text input
            valueInput.addEventListener('input', function () {
                updateColorFromInput(pickerId + '_value');
            });

            valueInput.addEventListener('blur', function () {
                updateColorFromInput(pickerId + '_value');
            });

            valueInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    updateColorFromInput(pickerId + '_value');
                    this.blur();
                }
            });
        }
    });

    // Click on preview to trigger color picker
    colorPickerIds.forEach(function (pickerId) {
        var previewId = pickerId + '_preview';
        var preview = document.getElementById(previewId);
        var picker = document.getElementById(pickerId);

        if (preview && picker) {
            preview.addEventListener('click', function () {
                picker.click();
            });
        }
    });

    // Sync form values before submission
    var colorForm = document.getElementById('color-settings-form');
    if (colorForm) {
        colorForm.addEventListener('submit', function () {
            colorPickerIds.forEach(function (pickerId) {
                var valueInput = document.getElementById(pickerId + '_value');
                var picker = document.getElementById(pickerId);

                if (valueInput && picker && isValidHexColor(valueInput.value)) {
                    // Ensure picker has the latest value from text input
                    picker.value = normalizeHexColor(valueInput.value);
                }
            });
        });
    }

    // Color design type change handler with smooth transition
    var colorDesignType = document.getElementById('app_color_design_type');
    if (colorDesignType) {
        colorDesignType.addEventListener('change', function () {
            var customBlock = document.getElementById('custom-color-block');
            if (customBlock) {
                if (this.value == '1') { // DEFAULT_COLOR
                    customBlock.style.display = 'none';
                    customBlock.classList.add('d-none');
                } else {
                    customBlock.classList.remove('d-none');
                    customBlock.style.display = '';
                }
            }
        });
    }

    // Initialize CodeMirror for CSS editor
    var cssEditor = document.getElementById("custom-css-editor");
    if (cssEditor && typeof CodeMirror !== 'undefined') {
        CodeMirror.fromTextArea(cssEditor, {
            mode: "css",
            theme: "monokai"
        });
    }

    // Initialize CodeMirror for JS editor
    var jsEditor = document.getElementById("custom-js-editor");
    if (jsEditor && typeof CodeMirror !== 'undefined') {
        CodeMirror.fromTextArea(jsEditor, {
            mode: "javascript",
            theme: "monokai",
        });
    }
});
