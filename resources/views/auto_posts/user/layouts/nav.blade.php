<header class="header-area">
        <div class="header-left">
          <button class="mobileMenu"><i class="fa-solid fa-bars"></i></button>
          <h3 class="bredcumbs-page d-none d-sm-block">{{ __('Home') }} / {{ __('Settings') }} / {{ __('App Settings') }}</h3>
        </div>
        <div class="header-right">
          <div class="dropdown profile-dropdown">
            <button class="profile-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="profile-avatar">
                <img src="{{ asset(getFileUrl(auth()->user()->image)) }}" alt="{{ auth()->user()->name }}">
              </div>
              <div class="profile-info">
                <h4>{{ __('Welcome') }}</h4>
                <h3>{{ auth()->user()->name }} <i class="fa-solid fa-angle-down"></i></h3>
              </div>
            </button>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="{{ auth()->user()->role == USER_ROLE_SUPER_ADMIN ? route('super_admin.profile.index') : route('admin.profile.index') }}">
                  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M14.1666 7.08341C14.1666 4.78223 12.3011 2.91675 9.99992 2.91675C7.69874 2.91675 5.83325 4.78223 5.83325 7.08341C5.83325 9.38458 7.69874 11.2501 9.99992 11.2501C12.3011 11.2501 14.1666 9.38458 14.1666 7.08341Z"
                      stroke="#0D0D0D" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                      d="M15.8334 17.0833C15.8334 13.8617 13.2217 11.25 10.0001 11.25C6.77842 11.25 4.16675 13.8617 4.16675 17.0833"
                      stroke="#0D0D0D" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  {{ __('Profile') }}
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M3.66048 3.33414C3.33325 3.84672 3.33325 4.5098 3.33325 5.83595V14.1642C3.33325 15.4903 3.33325 16.1534 3.66048 16.666C3.71891 16.7576 3.78442 16.8444 3.85639 16.9257C4.25945 17.3811 4.89714 17.5632 6.1725 17.9276C7.45109 18.2928 8.09039 18.4754 8.55325 18.2015C8.63359 18.154 8.70825 18.0977 8.776 18.0336C9.16659 17.6638 9.16659 16.9991 9.16659 15.6696V4.3306C9.16659 3.0011 9.16659 2.33634 8.776 1.9666C8.70825 1.90248 8.63359 1.84614 8.55325 1.79864C8.09039 1.52471 7.45109 1.70733 6.1725 2.07257C4.89714 2.4369 4.25945 2.61906 3.85639 3.07445C3.78442 3.15578 3.71891 3.2426 3.66048 3.33414Z"
                      stroke="#0D0D0D" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                      d="M9.16675 3.33325H10.8477C12.4324 3.33325 13.2247 3.33325 13.7171 3.82141C13.9916 4.09357 14.113 4.4582 14.1667 4.99992M9.16675 16.6666H10.8477C12.4324 16.6666 13.2247 16.6666 13.7171 16.1784C13.9916 15.9063 14.113 15.5417 14.1667 14.9999"
                      stroke="#0D0D0D" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                      d="M17.5001 10.0001H11.6667M16.2501 7.91675C16.2501 7.91675 18.3334 9.45113 18.3334 10.0001C18.3334 10.5491 16.2501 12.0834 16.2501 12.0834"
                      stroke="#0D0D0D" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  {{ __('Logout') }}
                </a>
              </li>
            </ul>
          </div>
        </div>
      </header>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
      </form>