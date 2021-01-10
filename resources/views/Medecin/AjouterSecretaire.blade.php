@extends('Medecin.layouts.master')
@section('content')
<div class="container center-div">
    <center><h1>Ajouter Secretaire</h1></center>
    <form action="{{route('medecin.ajouterSecretaire')}}" method="post">
            {{ csrf_field() }}
            <div class="form-group mt-2">
            <label for="NomEtPrenom">Nom et Prenom :</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" placeholder="Nom" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom') }}" placeholder="Prenom" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="login">Login :</label>
            <input type="text" name="login" id="login" class="form-control" value="{{ old('login') }}" placeholder="Login" required>
        </div>
        <div class="form-group">
            <label for="password">Password :</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="password" name="password" id="password" class="form-control"  placeholder="Mot de Passe" minlength="8" required>
                </div>
                <div class="col-md-6">
                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="Confirmation du Mot de Passe" minlength="8" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Votre Adresse Email" required>
        </div>
        <div class="form-group">
            <label for="tel">Téléphone :</label>
            <input type="tel" name="tel" id="tel" class="form-control" value="{{ old('tel') }}" placeholder="Votre Numéro de Téléphone" required>
        </div>
        @if($errors->any())
            <div class="alert alert-danger" id="warningSubmit" role="alert">
                <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
                <ul>
            </div>
         @endif
         @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <div class="form-group mt-5">
            <button type="submit" class="btn btn-success">Ajouter</button>
            <button type="reset" class="btn btn-dark">Vider</button>
        </div>
    </form>
</div>
@endsection