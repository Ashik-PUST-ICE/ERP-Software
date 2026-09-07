<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('FAQ Section Configuration') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="primary-form">
    <form class="ajax" action="{{ route('super_admin.setting.frontend.landing-page.update') }}" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseForModal">
        @csrf
        @php
        $faqItemsRaw = getOption('landing_faq_items');
        $existingFaqs = [];
        if (!empty($faqItemsRaw)) {
        foreach (explode(PHP_EOL, $faqItemsRaw) as $line) {
        $parts = explode('|||', $line, 2);
        if (count($parts) === 2) {
        $existingFaqs[] = ['q' => trim($parts[0]), 'a' => trim($parts[1])];
        }
        }
        }
        @endphp

        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Section Title') }}</label>
                    <input type="text" name="landing_faq_title" class="form-control"
                        value="{{ getOption('landing_faq_title') }}" placeholder="{{ __('Enter FAQ section title') }}">
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">{{ __('FAQ Items') }}</h5>
                    <button type="button" class="primary-btn" onclick="addFaqItemRow()">
                        {{ __('Add FAQ') }}
                    </button>
                </div>
            </div>

            <div class="col-12">
                <div id="faq-items-wrapper" data-label-faq="{{ __('FAQ') }}" data-label-question="{{ __('Question') }}"
                    data-label-answer="{{ __('Answer') }}" data-placeholder-question="{{ __('Enter question') }}"
                    data-placeholder-answer="{{ __('Enter answer') }}">
                    @foreach($existingFaqs as $index => $item)
                    <div class="faq-item border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">{{ __('FAQ') }} #{{ $index + 1 }}</h6>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeFaqItemRow(this)">
                                &times;
                            </button>
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label">{{ __('Question') }} ({{ __('FAQ') }} {{ $index + 1 }})</label>
                            <input type="text" name="landing_faq_questions[]" class="form-control"
                                value="{{ $item['q'] }}" placeholder="{{ __('Enter question') }}">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">{{ __('Answer') }} ({{ __('FAQ') }} {{ $index + 1 }})</label>
                            <textarea name="landing_faq_answers[]" class="form-control" rows="2"
                                placeholder="{{ __('Enter answer') }}">{{ $item['a'] }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="primary-btn">{{ __('Save') }}</button>
        </div>
    </form>
</div>

<script src="{{ asset('super_admin/js/landing/faq-config.js') }}"></script>