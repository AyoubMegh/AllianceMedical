@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
                        <center>
                            <h1>Reprendre Rendez-vous</h1>
                        </center>
                        <form action="{{route('secretaire.ajouterAutreRDV')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group mt-4">
                                <label for="date_rdv">Date du Rendez-vous :</label>
                                <input class="form-control" type="date" name="date_rdv" value="{{ old('date_rdv') }}" id="date_rdv" required>
                            </div>
                            <div class="form-group">
                                <label for="heure_Deb_Fin">Heure Debut et Fin :</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="time" class="form-control" name="heure_deb" value="{{ old('heure_deb') }}" id="heure_deb" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="time" class="form-control" name="heure_fin" value="{{ old('heure_fin') }}" id="heure_fin" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_med">Nom d Medecin :</label>
                                <select name="id_med" id="id_med"  class="form-control" required>
                                    <option value="" disabled selected>Choisissez une Medecin</option>
                                    @foreach($medecins as $medecin)
                                        <option value="{{$medecin->id_med}}">DR.{{$medecin->nom}} {{$medecin->prenom}} ({{$medecin->specialite}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="motif">Motif :</label>
                                <input type="text" class="form-control" name="motif" value="{{ old('motif') }}" id="motif" placeholder="Ecrivez votre motif ici !" required>
                            </div>
                            <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
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
                                <center> {{ session()->get('success') }} </center>
                                </div>
                            @endif
                            <div class="form-group mt-5">
                                <button type="submit" class="btn btn-success">Ajouter</button>
                                <button type="reset" class="btn btn-dark">Vider</button>
                            </div>
                        </form>
                        <hr>
    <div class="row mt-5 mb-2">
        <div class="col-md-9"></div>
        <div class="col-md-3">
            <form action="{{route('secretaire.detailsPatient')}}" method="GET">
                <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                <button type="submit" class="btn btn-primary">Retour Vers Fiche Du Patient ?</button>
            </form>
        </div>
    </div>
</div>
@endsection