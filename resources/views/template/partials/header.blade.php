<header id="header" class="fixed top-0 left-0 w-full z-40 header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">
        
        {{-- Logo Link Depending on Role --}}
        @auth
            @if(Auth::user()->role == 'customer')
                <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto me-xl-0">
                    <h1 class="sitename">Yummy</h1><span>.</span>
                </a>
            @else
                <a href="#" class="logo d-flex align-items-center me-auto me-xl-0">
                    <h1 class="sitename">Yummy</h1><span>.</span>
                </a>
            @endif
        @else
            <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto me-xl-0">
                <h1 class="sitename">Yummy</h1><span>.</span>
            </a>
        @endauth

        {{-- Navigation --}}
        @include('template.partials.nav')

        {{-- Right-side Actions --}}
        <div class="d-flex align-items-center">
            @auth
                @if(Auth::user()->role == 'customer')
                    <a href="{{ route('user.profile.show') }}" class="btn-getstarted me-3">
                        {{ Auth::user()->name }}
                    </a>
                    <a href="{{ route('cart') }}" class="cart-icon">🛒</a>
                @elseif(Auth::user()->role == 'restaurant')
                    <a href="{{ route('restaurant.profile.edit') }}" class="btn-getstarted me-3">
                        {{ Auth::user()->name }}
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="ms-3">
                    @csrf
                    <button type="submit" class="btn btn-link">Logout</button>
                </form>
            @else
                @if(!Route::is('login') && !Route::is('register'))
                    <a class="btn-getstarted" href="{{ route('login.form') }}">Register / Login</a>
                @endif
            @endauth
        </div>

    </div>
</header>
