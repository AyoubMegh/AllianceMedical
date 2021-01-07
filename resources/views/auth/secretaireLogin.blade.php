@extends('auth.LayoutLogin')
@section('form-login')
<form action="{{route('secretaire.login.submit')}}" method="POST" class="user">
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <input type="text" name="login" class="form-control form-control-user"
            id="exampleInputLogin" aria-describedby="LoginHelp"
            placeholder="Enter votre Login" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
    </div>
    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <input type="password" name="password" class="form-control form-control-user"
            id="exampleInputPassword" placeholder="Mot De Passe" required>
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
    </div>
    <div class="form-group">
        <div class="custom-control custom-checkbox small">
            <input type="checkbox" class="custom-control-input" id="customCheck" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="custom-control-label" for="customCheck">Se Souvenir de moi</label>
        </div>
    </div>
    <input type="submit" class="btn btn-primary btn-user btn-block" value="Connexion">                         
</form>
@endsection

@section('end-section')
<hr>
<div class="text-center">
    <a class="small" href="{{url('/forgot-password')}}">Mot de passe oublié?</a>
</div>
@endsection