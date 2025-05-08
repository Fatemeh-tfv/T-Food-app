<nav id="navmenu" class="navmenu">
    @auth
        @if(auth()->user()->role === 'customer')
            <ul>
                <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ url('/about') }}" class="{{ request()->is('about') ? 'active' : '' }}">About</a></li>
                <li><a href="{{ url('/contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a></li>
            </ul>
        @endif
    @else
        <ul>
            <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ url('/about') }}" class="{{ request()->is('about') ? 'active' : '' }}">About</a></li>
            <li><a href="{{ url('/contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a></li>
        </ul>
    @endauth
    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
</nav>
