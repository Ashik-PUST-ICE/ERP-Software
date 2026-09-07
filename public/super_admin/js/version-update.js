document.addEventListener('DOMContentLoaded', function () {
    var previewNode = document.querySelector("#template");
    if (previewNode) {
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: versionUpdateStoreUrl, // Set the url
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

        myDropzone.on("success", function (file, response) {
            // File uploaded successfully
            console.log('File uploaded successfully');
        });

        myDropzone.on("error", function (file, response) {
            var errorMessage = 'An error occurred while uploading the file.';

            if (response && typeof response === 'string') {
                try {
                    response = JSON.parse(response);
                } catch (e) {
                    errorMessage = response;
                }
            }

            if (response && typeof response === 'object') {
                if (response.errors && response.errors.update_file && Array.isArray(response.errors.update_file)) {
                    errorMessage = response.errors.update_file[0];
                } else if (response.message) {
                    errorMessage = response.message;
                } else if (response.error) {
                    errorMessage = response.error;
                }
            }

            var errorMessageElement = document.querySelector('#previews .error-message');
            if (errorMessageElement) {
                errorMessageElement.textContent = errorMessage;
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

        // Delete button click
        document.addEventListener('click', function (e) {
            var deleteBtn = e.target.closest('.delete[data-url]');
            if (deleteBtn) {
                e.preventDefault();
                var url = deleteBtn.getAttribute('data-url');
                var reload = deleteBtn.getAttribute('data-reload');

                if (confirm('Are you sure? You want to delete this file?')) {
                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (reload) {
                                location.reload();
                            } else {
                                alert('File has been deleted.');
                            }
                        })
                        .catch(error => {
                            alert('Something went wrong.');
                        });
                }
            }
        });

        // Update execute button click
        document.addEventListener('click', function (e) {
            var updateExecuteBtn = e.target.closest('.update-execute-btn');
            if (updateExecuteBtn) {
                e.preventDefault();
                if (confirm('Version Update Execute\n\nDo not click update now button if the application is customized. Your changes will be lost.\nTake backup all the files and database before updating.')) {
                    location.replace(updateExecuteBtn.getAttribute('data-url'));
                }
            }
        });
    }
});
