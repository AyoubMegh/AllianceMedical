@extends('Medecin.layouts.master')
@section('content')
<div class="container center-div">
    <center><h1>Mettre a Jour Secretaire</h1></center>
    <form action="{{route('medecin.mettreAjourSecretaire')}}" method="post">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
        <div class="form-group mt-2">
            <label for="NomEtPrenom">Nom et Prenom :</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="nom" id="nom" value="{{$secretaire->nom}}" class="form-control" placeholder="Nom" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="prenom" id="prenom" value="{{$secretaire->prenom}}" class="form-control" placeholder="Prenom" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="login">Login :</label>
            <input type="text" name="login" id="login" value="{{$secretaire->login}}" class="form-control" placeholder="Login" required>
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" value="{{$secretaire->email}}" class="form-control" placeholder="Votre Adresse Email" required>
        </div>
        <div class="form-group">
            <label for="tel">Téléphone :</label>
            <input type="tel" name="tel" id="tel" value="{{$secretaire->num_tel}}" class="form-control" placeholder="Votre Numéro de Téléphone" required>
        </div>
        <input type="hidden" name="id_sec" value="{{$secretaire->id_sec}}">
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
            <button type="submit" class="btn btn-success">Mettre à Jour</button>
            <button type="reset" class="btn btn-dark">Vider</button>
        </div>
    </form>
</div>
@endsection