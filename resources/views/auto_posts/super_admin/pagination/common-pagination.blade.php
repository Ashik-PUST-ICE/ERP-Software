@php
    $totalPages = ceil($total / $perPage);
    $start = max(1, $page - 2); // show 2 pages before current
    $end = min($totalPages, $page + 2); // show 2 pages after current
    $useLinks = isset($paginationUrl) && $paginationUrl;
    $baseUrl = $useLinks ? $paginationUrl : '#';
@endphp

@if($totalPages > 1)
    <div class="d-flex justify-content-center mt-20 tablePagi">
        <div class="dataTables_paginate paging_simple_numbers">
            {{-- Prev Button --}}
            @if($useLinks)
                <a class="paginate_button previous {{ $page == 1 ? 'disabled' : '' }}"
                   href="{{ $page > 1 ? $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=' . ($page - 1) : '#' }}"
                   @if($page == 1) aria-disabled="true" @endif role="link">
                    <i class="fa-solid fa-angles-left"></i>
                </a>
            @else
                <a class="paginate_button previous {{ $page == 1 ? 'disabled' : 'ajax-page' }}"
                   data-page="{{ $page > 1 ? $page - 1 : '' }}" role="link">
                    <i class="fa-solid fa-angles-left"></i>
                </a>
            @endif

            {{-- First Page + Dots --}}
            @if($start > 1)
                @if($useLinks)
                    <a class="paginate_button" href="{{ $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=1' }}" role="link">1</a>
                @else
                    <a class="paginate_button ajax-page" data-page="1" role="link">1</a>
                @endif
                @if($start > 2)
                    <span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>
                @endif
            @endif

            {{-- Page Numbers --}}
            @for($p = $start; $p <= $end; $p++)
                @if($page == $p)
                    <span>
                    <a class="paginate_button current" aria-current="page" role="link" @if($useLinks) href="{{ $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=' . $p }}" @else data-page="{{ $p }}" @endif>{{ $p }}</a>
                </span>
                @else
                    @if($useLinks)
                        <a class="paginate_button" href="{{ $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=' . $p }}" role="link">{{ $p }}</a>
                    @else
                        <a class="paginate_button ajax-page" data-page="{{ $p }}" role="link">{{ $p }}</a>
                    @endif
                @endif
            @endfor

            {{-- Last Page + Dots --}}
            @if($end < $totalPages)
                @if($end < $totalPages - 1)
                    <span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>
                @endif
                @if($useLinks)
                    <a class="paginate_button" href="{{ $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=' . $totalPages }}" role="link">{{ $totalPages }}</a>
                @else
                    <a class="paginate_button ajax-page" data-page="{{ $totalPages }}" role="link">{{ $totalPages }}</a>
                @endif
            @endif

            {{-- Next Button --}}
            @if($useLinks)
                <a class="paginate_button next {{ $page == $totalPages ? 'disabled' : '' }}"
                   href="{{ $page < $totalPages ? $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . 'page=' . ($page + 1) : '#' }}"
                   @if($page == $totalPages) aria-disabled="true" @endif role="link">
                    <i class="fa-solid fa-angles-right"></i>
                </a>
            @else
                <a class="paginate_button next {{ $page == $totalPages ? 'disabled' : 'ajax-page' }}"
                   data-page="{{ $page < $totalPages ? $page + 1 : '' }}" role="link">
                    <i class="fa-solid fa-angles-right"></i>
                </a>
            @endif
        </div>
    </div>
@endif