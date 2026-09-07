document.addEventListener('DOMContentLoaded', function () {
    // Function to handle execute message response
    window.getExecuteMessage = function (response) {
        var errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(function (el) {
            el.remove();
        });

        var invalidElements = document.querySelectorAll('.is-invalid');
        invalidElements.forEach(function (el) {
            el.classList.remove('is-invalid');
        });

        if (response['status'] == 200 || response['status'] == true) {
            if (typeof toastr !== 'undefined') {
                toastr.success('Addon Installed Successfully');
            } else {
                alert('Addon Installed Successfully');
            }
            window.location.href = window.location.origin;
        } else {
            if (typeof commonHandler !== 'undefined') {
                commonHandler(response);
            }
        }
    };

    var previewNode = document.querySelector("#template");
    if (previewNode) {
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: addonStoreUrl, // Set the url
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            paramName: 'update_file',
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 1,
            acceptedFiles: '.zip',
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: "#dz-clickable" // Define the element that should be used as click trigger to select files.
        });

        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("code", addonCode);
        });

        myDropzone.on("addedfile", function (file) {
            // Hookup the start button
            if (file.previewElement.querySelector(".start")) {
                file.previewElement.querySelector(".start").onclick = function () {
                    myDropzone.enqueueFile(file);
                };
            }
            var dzClickable = document.getElementById('dz-clickable');
            if (dzClickable) {
                dzClickable.classList.add('d-none');
            }
        });

        myDropzone.on("totaluploadprogress", function (progress) {
            var progressbar = document.querySelector("#total-progress .progress-bar");
            if (typeof progressbar != 'undefined' && progressbar != null) {
                document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
            }
        });

        myDropzone.on("error", function (file, response) {
            var errorMessageElement = document.querySelector('#previews .error-message');
            if (errorMessageElement) {
                if (typeof response.errors != 'undefined') {
                    errorMessageElement.textContent = response.errors?.update_file ? response.errors.update_file[0] : response.message;
                } else {
                    errorMessageElement.textContent = response.message;
                }
            }
        });

        // Cancel button click
        document.addEventListener('click', function (e) {
            if (e.target && e.target.id === 'cancel-btn') {
                myDropzone.removeAllFiles(true);
                var dzClickable = document.getElementById('dz-clickable');
                if (dzClickable) {
                    dzClickable.classList.remove('d-none');
                }
            }
        });

        // Update execute button click
        document.addEventListener('click', function (e) {
            var updateExecuteBtn = e.target.closest('.update-execute-btn');
            if (updateExecuteBtn) {
                e.preventDefault();
                var modal = document.getElementById('addonUpdateModal');
                if (modal) {
                    var bootstrapModal = new bootstrap.Modal(modal);
                    bootstrapModal.show();
                }
            }
        });
    }
});
