<div class="row gy-4">

    {{-- Card 1: Total Posts --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#02BCFF"/>
                    <path d="M25.0319 13.0354C23.5799 11.4717 12.6577 15.3022 12.6668 16.7007C12.677 18.2867 16.9321 18.7745 18.1115 19.1055C18.8208 19.3044 19.0107 19.5084 19.1743 20.2521C19.9149 23.6204 20.2868 25.2957 21.1343 25.3331C22.4853 25.3929 26.4489 14.5614 25.0319 13.0354Z" stroke="white" stroke-width="1.5"/>
                    <path d="M18.6665 19.3333L20.9998 17" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ number_format($postCount) }}</h2>
                <h3>{{ __('Total Posts (last 30 days)') }}</h3>
            </div>
            <span class="card-status up">
                {{ $postCount > 0 ? '+' . $postCount : '0' }}
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

    {{-- Card 2: Total Reach --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#0D0D0D"/>
                    <path d="M16.3333 18.9999C16.3333 15.318 17.5272 12.3333 18.9999 12.3333C20.4727 12.3333 21.6666 15.318 21.6666 18.9999C21.6666 22.6818 20.4727 25.6666 18.9999 25.6666C17.5272 25.6666 16.3333 22.6818 16.3333 18.9999Z" stroke="white" stroke-width="1.5"/>
                    <path d="M17.6502 16.7461C20.8782 14.9052 24.0993 14.4468 24.8449 15.7222C25.5903 16.9977 23.5778 19.524 20.3498 21.3649C17.1218 23.2059 13.9006 23.6643 13.1552 22.3889C12.4097 21.1134 14.4222 18.5871 17.6502 16.7461Z" stroke="white"/>
                    <path d="M20.3498 16.7461C23.5778 18.5871 25.5903 21.1134 24.8449 22.3889C24.0993 23.6643 20.8782 23.2059 17.6502 21.3649C14.4222 19.524 12.4097 16.9977 13.1552 15.7222C13.9006 14.4468 17.1218 14.9052 20.3498 16.7461Z" stroke="white" stroke-width="1.5"/>
                    <path d="M20 19C20 19.5523 19.5523 20 19 20C18.4477 20 18 19.5523 18 19C18 18.4477 18.4477 18 19 18C19.5523 18 20 18.4477 20 19Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ isset($accountStats[$platform]) ? number_format($accountStats[$platform]->total) : number_format($totalAccounts) }}</h2>
                <h3>{{ __('Connected Accounts') }}</h3>
            </div>
            <span class="card-status up">
                {{ isset($accountStats[$platform]) ? $accountStats[$platform]->total : $totalAccounts }}
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

    {{-- Card 3: Total Engagement --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#0FA958"/>
                    <path d="M25.6666 15.5001H23.8073C23.4066 15.5001 23.2062 15.5001 23.0173 15.4429C22.8283 15.3857 22.6616 15.2745 22.3281 15.0522C21.828 14.7188 21.2574 14.3384 20.9739 14.2526C20.6905 14.1667 20.3899 14.1667 19.7888 14.1667C18.9713 14.1667 18.4444 14.1667 18.0769 14.319C17.7093 14.4712 17.4203 14.7603 16.8422 15.3383L16.3335 15.847C16.2032 15.9773 16.1381 16.0425 16.0979 16.1068C15.9488 16.3452 15.9653 16.6514 16.1392 16.8724C16.1861 16.932 16.2578 16.9898 16.4014 17.1053C16.9319 17.5322 17.6967 17.4896 18.1771 17.0064L18.9999 16.1787H19.6666L23.6666 20.2025C24.0348 20.5729 24.0348 21.1733 23.6666 21.5437C23.2984 21.9141 22.7015 21.9141 22.3333 21.5437L21.9999 21.2084M21.9999 21.2084L19.9999 19.1965M21.9999 21.2084C22.3681 21.5788 22.3681 22.1793 21.9999 22.5497C21.6317 22.9201 21.0348 22.9201 20.6666 22.5497L19.9999 21.8791M19.9999 21.8791C20.3681 22.2494 20.3681 22.8499 19.9999 23.2203C19.6317 23.5907 19.0348 23.5907 18.6666 23.2203L17.6666 22.2143M19.9999 21.8791L18.6666 20.5457M17.6666 22.2143L17.3333 21.8791M17.6666 22.2143C18.0348 22.5847 18.0348 23.1853 17.6666 23.5557C17.2984 23.926 16.7014 23.926 16.3333 23.5557L14.4508 21.634C14.064 21.2391 13.8706 21.0417 13.6228 20.9375C13.375 20.8334 13.0986 20.8334 12.5459 20.8334H12.3333" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.6667 20.8333H24" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M16.6666 15.5H12.3333" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="card-info">
                @php
                    $activeCount = isset($accountStats[$platform])
                        ? (int) $accountStats[$platform]->active
                        : $activeAccounts;
                @endphp
                <h2>{{ number_format($activeCount) }}</h2>
                <h3>{{ __('Active Accounts') }}</h3>
            </div>
            <span class="card-status {{ $activeCount > 0 ? 'up' : 'down' }}">
                {{ $activeCount }}
                <span class="arrow"><i class="fa-solid fa-arrow-{{ $activeCount > 0 ? 'up' : 'down' }}"></i></span>
            </span>
        </div>
    </div>

    {{-- Card 4: Scheduled / AVG Rate --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FF4F02"/>
                    <path d="M13.1277 24.182L24.1822 13.1274" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M16.0763 13.6333C16.7509 14.3079 16.7509 15.4015 16.0763 16.0761C15.4018 16.7506 14.3081 16.7506 13.6336 16.0761C12.9591 15.4015 12.9591 14.3079 13.6336 13.6333C14.3081 12.9588 15.4018 12.9588 16.0763 13.6333Z" stroke="white" stroke-width="1.5"/>
                    <path d="M23.6762 21.2332C24.3508 21.9077 24.3508 23.0014 23.6762 23.676C23.0017 24.3505 21.908 24.3505 21.2334 23.676C20.5589 23.0014 20.5589 21.9077 21.2334 21.2332C21.908 20.5587 23.0017 20.5587 23.6762 21.2332Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                @php
                    $pendingCount = 0;
                @endphp
                <h2>{{ number_format($pendingCount) }}</h2>
                <h3>{{ __('Scheduled Posts') }}</h3>
            </div>
            <span class="card-status up">
                {{ $pendingCount }}
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

</div>
