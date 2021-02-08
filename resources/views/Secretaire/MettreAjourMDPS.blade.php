@extends('Secretaire.layouts.master')
@section('content')

<div class="container center-div">
    <center><h1>Modifier votre Mot de passe</h1></center>
    <form action="{{route('secretaire.mettreAjourMDPS')}}" method="post"  >   
    {{ csrf_field() }}
    {{ method_field('PUT') }}
                <div class="form-group">
                    <div class="form-group">
                    <label for="password">Ancien mot de passe  :</label>
                    <input type="password" class="form-control" name="apassword" id="aPassword" minlength="8" size="50" placeholder="veuillez saisir l'ancien mot de passe" required> 
                    </div>
                    <div class="form-group">
                    <label for="password">Nouveau mot de passe :</label>
                    <input type="password" class="form-control" name="npassword" id="nPassword" minlength="8" placeholder="veuillez saisir le nouveau mot de passe" required> 
                    </div>
                    <div class="form-group">
                    <label for="password">Confirmation du nouveau mot de passe :</label>
                    <input type="password" class="form-control" name="cpassword" id="cNPassword" minlength="8" placeholder="veuillez confirmer le nouveau mot de passe" required> 
                    </div>
                </div>
                @if($errors->any())
            <div class="alert alert-danger" id="warningSubmit" role="alert">
            <center><ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
                <ul></center>
            </div>
         @endif
         @if(session()->has('success'))
            <div class="alert alert-success">
            <center> {{ session()->get('success') }}</center>
            </div>
        @endif
                <div class="form-group mt-5">
                    <button type="submit" class="btn btn-success">Modifier</button>
             </div>
            </form>
</div>
@endsection