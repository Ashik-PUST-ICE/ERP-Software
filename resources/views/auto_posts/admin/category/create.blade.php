<div class="modal-header">
    <h5 class="modal-title">{{ __('Create Category') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('admin.category.store') }}" method="POST" class="ajax reset" data-handler="settingCommonHandler">
    @csrf
    <div class="modal-body">
        <div class="primary-form">
            <div class="row gy-4">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="Title" class="form-label">{{ __('Title') }}<span class="required">*</span></label>
                        <input type="text" class="form-control" id="Title" name="title"
                            placeholder="{{ __('Enter category title') }}" value="{{ old('title') }}" required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="Type" class="form-label">{{ __('Type') }}</label>
                        <select class="form-control select wide" id="Type" name="type">
                            <option value="">{{ __('Select Type') }}</option>
                            @foreach(getPostTypes() as $key => $value)
                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="Status" class="form-label">{{ __('Status') }}</label>
                        <select class="form-control select wide" id="Status" name="status">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                {{ __('Active') }}</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="ShortDescription" class="form-label">{{ __('Short Description') }}</label>
                        <textarea id="ShortDescription" class="summernoteOne" name="short_description"
                            rows="3">{{ old('short_description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="primary-btn">{{ __('Create Category') }}</button>
    </div>
</form>