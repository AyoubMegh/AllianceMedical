@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
                        <center>
                            <h1>Prendre Rendez-vous</h1>
                        </center>
                        <form action="{{route('secretaire.ajouterRDV')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="nom">Nom & Prénom :</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="nom" value="{{ old('nom') }}" id="nom" required placeholder="Nom">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="prenom" value="{{ old('prenom') }}" id="prenom" required placeholder="Prénom">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="date_naissance">Date de Naissance :</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="form-control" type="date" name="date_naissance" value="{{ old('date_naissance') }}" id="date_de_naissance" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="date_de_naissance">N° Sécurité Sociale :</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="form-control" type="text" name="num_ss" value="{{ old('num_ss') }}" id="num_ss" required placeholder="ex: 99 333 000 22">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="num_tel">N° Téléphone :</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="tel" class="form-control" name="num_tel" value="{{ old('num_tel') }}" id="num_tel" required placeholder="ex: 0576009999">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="email">Adresse Email :</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="email" required placeholder="ex: exemple@mail.com">
                                    </div>
                                </div>
                            </div>

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
                                @if($id_med != -1)
                                    <input type="hidden" name="id_med" id="id_med" value="{{$id_med}}">
                                @else
                                    <label for="id_med">Nom d Medecin :</label>
                                    <select name="id_med" id="id_med"  class="form-control" required>
                                        <option value="" disabled selected>Choisissez une Medecin</option>
                                        @foreach($medecins as $medecin)
                                            <option value="{{$medecin->id_med}}" {{($medecin->id_med==$id_med)? "selected" : "" }} >DR.{{$medecin->nom}} {{$medecin->prenom}} ({{$medecin->specialite}})</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="motif">Motif :</label>
                                <input type="text" class="form-control" name="motif" id="motif" value="{{ old('motif') }}" placeholder="Ecrivez votre motif ici !" required>
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