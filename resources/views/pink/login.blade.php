@extends(env('THEME').'.layouts.site')
{{--@section('content')
    <div id="content-home" class="content group">
        <div class="hentry group">
            <form id="contact-form-contact-us" class="contact-form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <fieldset>
                    <ul>
                        <li class="text-field">
                            <label for="login">
                                <span class="label">Login</span>
                                <br />					<span class="sublabel">This is the name</span><br />
                            </label>
                            <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input type="text" name="name" id="login" class="required" value="" /></div>
                            @if ($errors->has('login'))
                                <span class="help-block">
				                                        <strong>{{ $errors->first('login') }}</strong>
				                                    </span>
                            @endif
                        </li>
                        <li class="text-field">
                            <label for="password">
                                <span class="label">Password</span>
                                <br />					<span class="sublabel">This is field for password</span><br />
                            </label>
                            <div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span><input type="password" name="password"  class="required" value="" /></div>
                            @if ($errors->has('name'))
                                <span class="help-block">
				                                        <strong>{{ $errors->first('name') }}</strong>
				                                    </span>
                            @endif
                        </li>
                        <li class="submit-button">
                            <input type="submit" name="yit_sendmail" value="Отправить" class="sendmail alignright" />
                        </li>
                    </ul>
                </fieldset>
            </form>
        </div>
    </div>
@endsection--}}
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">Введите логин</label>

                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control" name="login" value="{{ old('login') }}" required autofocus>

                                    @if ($errors->has('login'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('login') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Введите пароль</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    {{--<a class="btn btn-link" href="">
                                        Forgot Your Password?
                                    </a>--}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
