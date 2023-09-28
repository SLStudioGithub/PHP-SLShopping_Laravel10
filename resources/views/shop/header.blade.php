@section('header')
<header class="header">
    <div class="header-log">
        <a href="{{ route('shop.top') }}"><img class="shop-logo" src="{{ asset('image/slstudio_logomark_white.svg') }}" alt="SLスタジオロゴ"></a>
    </div>
    <div>
        <nav class="header-nav">
            <ul>
                @if (Auth::check())
                    <li><a class="logout-anchor" href="#"><ruby><i class="bi bi-door-open"></i><rt>Logout</rt></ruby></a></li>
                    <form id="logout-form" action="{{ route('shop.logout') }}" method="POST">
                        @csrf
                    </form>
                @endif
                <li><a href="#"><ruby><i class="bi bi-cart"></i><rt>Cart</rt></ruby></a></li>
                <li><a href="{{ route('shop.my_page') }}"><ruby><i class="bi bi-person"></i><rt>MyPage</rt></ruby></a></a></li>
            </ul>
        </nav>
    </div>
</header>
@endsection