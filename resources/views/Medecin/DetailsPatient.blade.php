@extends('Medecin.layouts.masterDetails')
@section('activation')
class="active"
@endsection
@section('details_content')

<form action="{{route('medecin.detailsPatient')}}" method="post">
    
    {{ csrf_field() }}
    {{ method_field('PUT') }}
        <div class="form-group mt-2">
            <label for="NomEtPrenom">Nom et Prenom :</label>
            <div class="row">
                <div class="col-md-6">
                <input type="text" name="nom" id="nom" value="{{$patient->nom}}" class="form-control" placeholder="Nom" required>
                   
                </div>
                <div class="col-md-6">
                <input type="text" name="prenom" id="prenom" value="{{$patient->prenom}}" class="form-control" placeholder="Prenom" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="date_naissance">Date Naissance :</label>
            <input type="date" name="date_naissance" id="date_naissance" value="{{$patient->date_naissance}}" class="form-control" placeholder="" required>
        </div>

        <div class="form-group">
            <label for="num_ss">N° Sécurité Social :</label>
            <input type="tel" name="num_ss" id="num_ss" value="{{$patient->num_ss}}" class="form-control" placeholder="" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" value="{{$patient->email}}" class="form-control" placeholder="Votre Adresse Email" required>
        </div>
        <div class="form-group">
            <label for="tel">Téléphone :</label>
            <input type="tel" name="tel" id="tel" value="{{$patient->num_tel}}" class="form-control" placeholder="Votre Numéro de Téléphone" required>
        </div>
        <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
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