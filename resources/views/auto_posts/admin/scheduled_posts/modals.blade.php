<!-- HashtagModal -->
<div class="modal fade primary-modal" id="HashtagModal" tabindex="-1" aria-labelledby="HashtagModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="HashtagModalLabel">Add Hashtag</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="primary-form">
                    <form action="{{ route('admin.hashtag.store') }}" method="POST" id="hashtagStoreForm">
                        @csrf
                        <div class="row gy-4">
                            <!-- Custom Hashtag Creation -->
                            <div class="col-lg-12">
                                <div class="tag-wrapper">
                                    <div class="tag-box">
                                        <input type="text" class="tag-input" id="customHashtagInput"
                                            placeholder="Type hashtag and press Enter to create...">
                                        <div class="suggestion-box" id="hashtagSuggestionBox"></div>
                                    </div>
                                    <div class="bottom-tags" id="bottomTags">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="hashtagName" name="name" value="">
                            <input type="hidden" id="selectedHashtags" name="hashtags" value="">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="primary-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="primary-btn" id="saveHashtagBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- InsertLinkModal -->
<div class="modal fade primary-modal" id="InsertLinkModal" tabindex="-1" aria-labelledby="InsertLinkModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="InsertLinkModalLabel">Insert Link</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="primary-form">
                    <form action="#">
                        <div class="row gy-4">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="URL" class="form-label">URL<span class="required">*</span></label>
                                    <input type="text" class="form-control" id="URL" name="URL"
                                        placeholder="https://example.com" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="primary-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="primary-btn" id="insertLinkBtn">Insert Link</button>
            </div>
        </div>
    </div>
</div>

<!-- AIAssistantModal -->
<div class="modal fade primary-modal" id="AIAssistantModal" tabindex="-1" aria-labelledby="AIAssistantModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="AIAssistantModalLabel">AI Assistant</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="assistant-wrap text-center">
                    <img class="icon" src="{{ asset('assets/images/ai-assistant.png') }}" alt="ai-assistant">
                    <h2>AI Content Assistant</h2>
                    <p>Generate high-quality content for your social media posts using AI Assistant.</p>
                </div>
                <div class="d-flex justify-content-center mb-4">
                    <ul class="nav nav-tabs post-tabs" id="AssistantTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="ai-Text-tab" data-bs-toggle="tab"
                                data-bs-target="#ai-Text-tab-pane" type="button" role="tab" aria-controls="ai-Text-tab-pane"
                                aria-selected="true">Text</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ai-Image-tab" data-bs-toggle="tab"
                                data-bs-target="#ai-Image-tab-pane" type="button" role="tab" aria-controls="ai-Image-tab-pane"
                                aria-selected="false">Image</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ai-Video-tab" data-bs-toggle="tab"
                                data-bs-target="#ai-Video-tab-pane" type="button" role="tab" aria-controls="ai-Video-tab-pane"
                                aria-selected="false">Video</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="AssistantTabContent">
                    <div class="tab-pane fade show active" id="ai-Text-tab-pane" role="tabpanel" aria-labelledby="ai-Text-tab"
                        tabindex="0">
                        <div class="primary-form">
                            <form action="#">
                                <div class="row gy-4">
                                    <div class="col-lg-12">
                                        <div class="form-group assistant-box-wrap">
                                            <textarea class="form-control assistant-text-box" name="aicontent"
                                                id="aicontent-text" placeholder="Write Something..."></textarea>
                                            <button type="submit" class="primary-btn generate-btn"
                                                data-content-type="text"><svg width="15" height="15" viewBox="0 0 15 15"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M13.1548 1.90808C11.7936 0.442101 1.55405 4.03325 1.56251 5.34437C1.57209 6.83119 5.56131 7.28856 6.667 7.59881C7.33194 7.78531 7.51 7.97656 7.66331 8.67381C8.35769 11.8316 8.70631 13.4022 9.50087 13.4372C10.7674 13.4932 14.4833 3.33875 13.1548 1.90808Z"
                                                        stroke="white" stroke-width="1.5" />
                                                    <path d="M7.1875 7.8125L9.375 5.625" stroke="white"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ai-Image-tab-pane" role="tabpanel" aria-labelledby="ai-Image-tab"
                        tabindex="0">
                        <div class="primary-form">
                            <form action="#">
                                <div class="row gy-4">
                                    <div class="col-lg-12">
                                        <div class="form-group assistant-box-wrap">
                                            <textarea class="form-control assistant-text-box" name="aicontent"
                                                id="aicontent-image"
                                                placeholder="Describe the image you want to generate..."></textarea>
                                            <button type="submit" class="primary-btn generate-btn"
                                                data-content-type="image"><svg width="15" height="15"
                                                    viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M13.1548 1.90808C11.7936 0.442101 1.55405 4.03325 1.56251 5.34437C1.57209 6.83119 5.56131 7.28856 6.667 7.59881C7.33194 7.78531 7.51 7.97656 7.66331 8.67381C8.35769 11.8316 8.70631 13.4022 9.50087 13.4372C10.7674 13.4932 14.4833 3.33875 13.1548 1.90808Z"
                                                        stroke="white" stroke-width="1.5" />
                                                    <path d="M7.1875 7.8125L9.375 5.625" stroke="white"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ai-Video-tab-pane" role="tabpanel" aria-labelledby="ai-Video-tab"
                        tabindex="0">
                        <div class="primary-form">
                            <form action="#">
                                <div class="row gy-4">
                                    <div class="col-lg-12">
                                        <div class="form-group assistant-box-wrap">
                                            <textarea class="form-control assistant-text-box" name="aicontent"
                                                id="aicontent-video"
                                                placeholder="Describe the video you want to generate..."></textarea>
                                            <button type="submit" class="primary-btn generate-btn"
                                                data-content-type="video"><svg width="15" height="15"
                                                    viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M13.1548 1.90808C11.7936 0.442101 1.55405 4.03325 1.56251 5.34437C1.57209 6.83119 5.56131 7.28856 6.667 7.59881C7.33194 7.78531 7.51 7.97656 7.66331 8.67381C8.35769 11.8316 8.70631 13.4022 9.50087 13.4372C10.7674 13.4932 14.4833 3.33875 13.1548 1.90808Z"
                                                        stroke="white" stroke-width="1.5" />
                                                    <path d="M7.1875 7.8125L9.375 5.625" stroke="white"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- AI Generated Preview Section -->
                <div id="ai-preview-section" class="ai-preview-section mt-4" style="display: none;">
                    <hr>
                    <div class="preview-header d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Generated Content Preview</h5>
                        <button type="button" class="btn-close btn-sm" id="close-ai-preview"></button>
                    </div>
                    <div class="preview-content">
                        <!-- Loading Spinner -->
                        <div id="ai-loading-spinner" class="ai-loading-spinner text-center py-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Generating content...</p>
                        </div>
                        <!-- Text Preview -->
                        <div id="ai-text-preview" class="ai-preview-item" style="display: none;">
                            <div class="generated-text-box p-3 bg-light rounded mb-3">
                                <p id="generated-text-content" class="mb-0"></p>
                            </div>
                            <div class="preview-actions d-flex gap-2">
                                <button type="button" class="primary-btn btn-sm" id="save-text-btn">Save</button>
                                <button type="button" class="primary-btn btn-outline-secondary btn-sm"
                                    id="use-text-btn">Use This Text</button>
                            </div>
                        </div>
                        <!-- Image Preview -->
                        <div id="ai-image-preview" class="ai-preview-item" style="display: none;">
                            <div class="generated-media-box text-center mb-3">
                                <img id="generated-image" src="" alt="AI Generated Image" class="img-fluid rounded"
                                    style="max-height: 200px;">
                            </div>
                            <div class="preview-actions d-flex gap-2">
                                <button type="button" class="primary-btn btn-sm" id="save-image-btn">Save to
                                    Gallery</button>
                                <button type="button" class="primary-btn btn-outline-secondary btn-sm"
                                    id="use-image-btn">Use This Image</button>
                            </div>
                        </div>
                        <!-- Video Preview -->
                        <div id="ai-video-preview" class="ai-preview-item" style="display: none;">
                            <div class="generated-media-box text-center mb-3">
                                <video id="generated-video" src="" controls class="img-fluid rounded"
                                    style="max-height: 200px;"></video>
                            </div>
                            <div class="preview-actions d-flex gap-2">
                                <button type="button" class="primary-btn btn-sm" id="save-video-btn">Save to
                                    Gallery</button>
                                <button type="button" class="primary-btn btn-outline-secondary btn-sm"
                                    id="use-video-btn">Use This Video</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>