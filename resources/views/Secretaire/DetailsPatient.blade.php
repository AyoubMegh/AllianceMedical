@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
    <div class="container center-div">
        <center>
            <h1>Details Patient Avec Divers Rendez-vous</h1>
        </center>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="col-12 mt-1 mb-0" id="dataTable_length">
                    <div class="row mt-3">
                        <div class="col-md-3"><label for="NomPrenom">Nom et Prenom :</label></div>
                        <div class="col-md-9">
                            <h5>{{$patient->nom}} {{$patient->prenom}}</h5>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3"><label for="NumSS">Date Naisssance :</label></div>
                        <div class="col-md-9">
                            <h5>{{$patient->date_naissance}}</h5>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3"><label for="NumSS">Numéro de sécurité sociale :</label></div>
                        <div class="col-md-9">
                            <h5>{{$patient->num_ss}}</h5>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3"><label for="NumSS">Numéro de Télephone :</label></div>
                        <div class="col-md-9">
                            <h5>{{$patient->num_tel}}</h5>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3"><label for="NumSS">Email :</label></div>
                        <div class="col-md-9">
                            <h5>{{$patient->email}}</h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-md-12"><center><h3><u>Liste des Rendez-vous du Patient :</u></h3></center></div>
                    </div>
                </div>
                <div class="row">
                @if($errors->any())
                    <div class="alert alert-danger col-12 mt-1 mb-0" id="warningSubmit" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        <ul>
                    </div>
                @endif
                @if(session()->has('success'))
                    <div class="alert alert-success col-12 mt-1 mb-0" style="width:100%">
                           <center> 
                                {{ session()->get('success') }}
                           </center>
                    </div>
                @endif
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                                <form action="{{route('secretaire.reprendreRDV')}}" method="get">
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <input class="btn btn-primary" type="submit" value="Prendre un Nouveaux Rendez-vous">
                                </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date Rendez-vous</th>
                                    <th>Heure du Rendez-vous</th>
                                    <th>Nom Du Medecin</th>
                                    <th>Mettre a Jour</th>
                                    <th>Annuler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rdvs as $rdv)
                                    <tr>
                                        <td>{{$rdv->date_rdv}}</td>
                                        <td>{{$rdv->heure_debut}}</td>
                                        <td>DR. {{App\Medecin::find($rdv->id_med)->nom}} {{App\Medecin::find($rdv->id_med)->prenom}}</td>
                                        <td>
                                            <center>
                                                <form action="{{route('secretaire.MAJRDV')}}" method="get">
                                                    <input type="hidden" name="id_rdv" value="{{$rdv->id_rdv}}">
                                                    <button type="submit" class="btn">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                </form>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <form action="{{route('secretaire.supprimerRDV')}}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input type="hidden" name="id_rdv" value="{{$rdv->id_rdv}}">
                                                    <button type="submit" class="btn">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                </form>
                                            </center>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>   
@endsection