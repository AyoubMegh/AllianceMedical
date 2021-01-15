@extends('Medecin.layouts.master')
@section('content')

<div class="container center-div">
    <center><h1>Modifier vos informations</h1></center>
    <form action="{{route('medecin.update')}}" method="post">
    
    {{ csrf_field() }}
    {{ method_field('PUT') }}
        <div class="form-group mt-2">
            <label for="NomEtPrenom">Nom et Prenom :</label>
            <div class="row">
                <div class="col-md-6">
                <input type="text" name="nom" id="nom" value="{{$medecin->nom}}" class="form-control" placeholder="Nom" required>
                   
                </div>
                <div class="col-md-6">
                <input type="text" name="prenom" id="prenom" value="{{$medecin->prenom}}" class="form-control" placeholder="Prenom" required>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" value="{{$medecin->email}}" class="form-control" placeholder="Votre Adresse Email" required>
        </div>
        <div class="form-group">
            <label for="tel">Téléphone :</label>
            <input type="tel" name="tel" id="tel" value="{{$medecin->num_tel}}" class="form-control" placeholder="Votre Numéro de Téléphone" required>
        </div>
        <input type="hidden" name="id_med" value="{{$medecin->id_med}}">
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
            <button type="submit" class="btn btn-success">Modifier</button>
        </div>
    </form>
    
@endsection