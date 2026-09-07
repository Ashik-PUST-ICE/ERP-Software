<form class="ajax reset" action="{{ route('super_admin.setting.blogs.update', $blog->id) }}" method="post"
    enctype="multipart/form-data" data-handler="commonResponseWithPageLoad">
    @csrf
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Edit Blog') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Title') }}<span class="required">*</span></label>
                        <input type="text" class="form-control" name="title" value="{{ $blog->title }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Date') }}</label>
                        <input type="date" class="form-control" name="date" value="{{ $blog->date }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('URL') }}</label>
                        <input type="text" class="form-control" name="url" value="{{ $blog->url }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select name="status" class="select form-control wide sf-select-without-search">
                            <option value="1" {{ (int) $blog->status === 1 ? 'selected' : '' }}>
                                {{ __('Active') }}
                            </option>
                            <option value="0" {{ (int) $blog->status === 0 ? 'selected' : '' }}>
                                {{ __('Inactive') }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Description') }}</label>
                        <textarea name="description" class="form-control summernote" rows="3"
                            placeholder="{{ __('Enter blog description') }}">{{ $blog->description }}</textarea>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label d-block">{{ __('Image') }}</label>
                        <div class="zImage-upload-details mw-100">
                            <div class="upload-img-box upload-image-box-new">
                                <img src="{{ $blog->image ? getFileUrl($blog->image) : asset('assets/images/no-image.jpg') }}"
                                    alt="{{ $blog->title }}" class="preview-image" style="max-height: 60px;">
                                <input type="file" class="form-control" name="image" accept="image/*"
                                    onchange="previewFile(this)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ __('Update') }}</button>
        </div>
    </div>
</form>


<script>
    /*-------------------------------------------
    summernote active
    --------------------------------------------- */
    $('.summernote').summernote({
    height: 270,
        toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['picture']], // removed 'video'
        // ['view', ['codeview']] // removed 'fullscreen' & 'help'
    ]
    });

</script>