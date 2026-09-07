<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Pusher Configuration') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<form class="ajax" action="{{route('super_admin.setting.common.settings.update')}}" method="post"
      class="form-horizontal" data-handler="commonResponseForModal">
    @csrf
    
    <div class="primary-form">
        <div class="row gy-3">
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Pusher App Id') }}</label>
                    <input type="text" name="pusher_app_id" id="pusher_app_id"
                           value="{{getOption('pusher_app_id')}}" class="form-control" placeholder="{{ __('Pusher App Id') }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Pusher App Key') }}</label>
                    <input type="text" name="pusher_app_key" id="pusher_app_key"
                           value="{{getOption('pusher_app_key')}}" class="form-control" placeholder="{{ __('Pusher App Key') }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Pusher App Secret') }}</label>
                    <input type="text" name="pusher_app_secret" id="pusher_app_secret"
                           value="{{getOption('pusher_app_secret')}}" class="form-control" placeholder="{{ __('Pusher App Secret') }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Pusher Cluster') }}</label>
                    <input type="text" name="pusher_cluster" id="pusher_cluster"
                           value="{{getOption('pusher_cluster')}}" class="form-control" placeholder="{{ __('Pusher Cluster') }}">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Buttons -->
    <div class="btn-list mt-4 pt-3 border-top">
        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="primary-btn">{{__('Update')}}</button>
    </div>
</form>
