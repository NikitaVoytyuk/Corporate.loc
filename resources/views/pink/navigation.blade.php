

@if($menu)
    <div class="menu classic">
        <ul id="nav" class="menu">
            @include(env("THEME"). '.customMenuItems', ['items'=>$menu->roots()])

            {{--<form action="{{ route('search') }}" method="post">
                <input type="text" name="search" placeholder="Search" style="position: absolute; right: 15px;">
                {{ csrf_field() }}
                <input name="submit" type="submit" id="submit" value="Search" style="position: absolute; right: 15px;"/>
            </form>--}}

            {{--<form method="get" action="{{ url('search-autocomplete') }}">

                <input id="q" placeholder="Search articles" name="q" type="text" value="">
                {{ csrf_field() }}
                <input class="btn btn-default" type="submit" value="Search">
            </form>--}}
        </ul>
    </div>
    {{--<div>
        <li class="auth-menu"><a href="{{route('login')}}">Авторизация</a></li>
        <li class="auth-menu"><a href="{{route('register')}}">Регистрация</a></li>
    </div>--}}
@endif
