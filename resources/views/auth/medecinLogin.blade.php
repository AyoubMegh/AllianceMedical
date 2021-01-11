@extends('auth.LayoutLogin')
@section('image')
<div class="col-lg-6 d-none d-lg-block bg-login-image-medecin" style="margin-right: -75px;"></div>
@endsection
@section('slogan')
<h1 class="h4 text-gray-900 mb-4"><i>"L'exercice et la propreté entretiennent la santé."</i></h1>
@endsection
@section('form-login')
<center>
 <h6 class="font-weight-light "> Compte du Medecin</h6>
<center>
<form action="{{route('medecin.login.submit')}}" method="POST" class="user">
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
    <div class="form-group">
        <input type="text" name="login" class="form-control form-control-user"
            id="exampleInputLogin" aria-describedby="LoginHelp"
            placeholder="Enter votre Login" value="{{ old('login') }}" required autofocus>
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control form-control-user"
            id="exampleInputPassword" placeholder="Mot De Passe" required>
    </div>
    <div class="form-group">
        <div class="custom-control custom-checkbox small">
            <input type="checkbox" class="custom-control-input" id="customCheck" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="custom-control-label" for="customCheck">Se Souvenir de moi</label>
        </div>
    </div>
    <input type="submit" class="btn btn-primary btn-user btn-block" value="Connexion"> 
    @if($errors->any())
        <div class="alert alert-danger text-center mt-3" role="alert">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </div>  
    @endif                          
</form>
@endsection

@section('end-section')
<hr>
<div class="text-center">
    <a class="small" href="{{url('/forgot-password')}}">Mot de passe oublié?</a>
</div>
<div class="text-left">
<a class= "btn btn-outline-primary" href="{{route('/')}}" > << </a>
</div>
@endsection