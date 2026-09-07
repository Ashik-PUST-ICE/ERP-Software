@extends('auto_posts.super_admin.layouts.app')

@push('title')
{{ __('Edit Page') }}
@endpush

@section('content')
<div class="p-30">
    <div class="row gy-4">
        <div class="col-12">
            <div class="section-title">
                <h2 class="title">{{ __('Edit Page') }}</h2>
                <a href="{{ route('super_admin.setting.page.index') }}" class="primary-btn">{{ __('Back') }}</a>
            </div>

            <form action="{{route('super_admin.setting.page.update', $page->uuid)}}" enctype="multipart/form-data"
                method="post" class="ajax reset" data-handler="commonResponseRedirect"
                data-redirect-url="{{ route('super_admin.setting.page.index') }}">
                @csrf

                <div class="row">
                    <div class="col-lg-8">
                        <div class="section-wrap">
                            <div class="primary-form">
                                <div class="row gy-4">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">{{__('Title')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="title" value="{{ $page->en_title }}"
                                                placeholder="{{__('Title')}}" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Description') }} <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="en_description" class="summernote"
                                                id="summernote">{{  $page->en_description }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Meta Title') }}</label>
                                            <input type="text" name="meta_title"
                                                value="{{ old('meta_title', $page->meta_title) }}"
                                                placeholder="{{ __('Meta title') }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Meta Keywords') }}</label>
                                            <input type="text" name="meta_keywords"
                                                value="{{ old('meta_keywords',  $page->meta_keywords) }}"
                                                placeholder="{{ __('meta keywords') }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Meta Description') }}</label>
                                            <input type="text" name="meta_description"
                                                value="{{ old('meta_description', $page->meta_description) }}"
                                                placeholder="{{ __('meta description') }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('OG Image') }}</label>
                                            <div class="zImage-upload-details mw-100">
                                                <div class="upload-img-box zImage-inside upload-image-box-new">
                                                    @if($page->og_image)
                                                    <img src="{{ is_numeric($page->og_image) ? getFileUrl($page->og_image) : asset($page->og_image) }}"
                                                        alt="{{ $page->en_title }}" class="preview-image">
                                                    @else
                                                    <img src="{{ asset('assets/images/no-image.jpg') }}"
                                                        alt="{{ $page->en_title }}" class="preview-image">
                                                    @endif
                                                    <input type="file" class="form-control" name="og_image"
                                                        accept="image/*" onchange="previewFile(this)">
                                                </div>
                                            </div>
                                            <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG, JPG <br>
                                                <!-- <span class="text-black">{{ __('Recommend Size') }}:</span> 1200 x 627 -->
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="section-wrap my-plan-area h-100">
                            <div class="plan-head"
                                style="padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #ebedf0;">
                                <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">
                                    {{ __('Page Actions') }}</h3>
                            </div>
                            <div class="plan-body" style="padding-top: 10px;">
                                <ul class="plan-features" style="gap: 8px; margin: 0; padding: 0; list-style: none;">
                                    <li
                                        style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                        <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                                stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        {{ __('URL') }}: <strong>{{ url($page->slug) }}</strong>
                                    </li>
                                    <li
                                        style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                        <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                                stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        {{ __('Slug') }}: <strong>{{ $page->slug }}</strong>
                                    </li>
                                </ul>
                            </div>
                            <div class="plan-footer"
                                style="padding-top: 15px; margin-top: 15px; border-top: 1px solid #ebedf0;">
                                <button type="submit" class="primary-btn w-100">{{ __('Update Page') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection