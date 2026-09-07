@php
    $faqItems = [];
    $faqRaw   = getOption('landing_faq_items');
    if (!empty($faqRaw)) {
        foreach (explode(PHP_EOL, $faqRaw) as $line) {
            $parts = explode('|||', trim($line), 2);
            if (count($parts) === 2 && trim($parts[0]) !== '' && trim($parts[1]) !== '') {
                $faqItems[] = ['q' => trim($parts[0]), 'a' => trim($parts[1])];
            }
        }
    }
    $faqTitle = trim((string) getOption('landing_faq_title', ''));
@endphp
@if(getOption('landing_faq_status', 1) == 1 && count($faqItems) > 0)
<section class="faq-section lp-section-padding" id="faq-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                @if($faqTitle !== '')
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    {{ $faqTitle }}
                </h2>
                @endif

                @if(count($faqItems) > 0)
                <div class="accordion accordion-flush custom-faq" id="faqAccordion">
                    @foreach($faqItems as $index => $item)
                        @php
                            $collapseId = 'collapseFaq' . $index;
                            $isFirst    = $index === 0;
                        @endphp
                        <div class="accordion-item"
                             data-aos="fade-up"
                             data-aos-delay="{{ 100 + ($index * 100) }}"
                             data-aos-duration="1000">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $isFirst ? '' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#{{ $collapseId }}"
                                        aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                        aria-controls="{{ $collapseId }}">
                                    {{ $item['q'] }}
                                </button>
                            </h2>
                            <div id="{{ $collapseId }}"
                                 class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {{ $item['a'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif

            </div>
        </div>
    </div>
</section>
@endif
