@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
                        <center>
                            <h1>Details du Patient</h1>
                        </center>
                        <div class="container mt-5">
                            <div class="row mt-3">
                                <div class="col-md-3"><label for="NomPrenom">Nom et Prenom :</label></div>
                                <div class="col-md-9">
                                    <h5>{{$patient->nom}} {{$patient->prenom}}</h5>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3"><label for="DateNaissance">Date de Naissance :</label></div>
                                <div class="col-md-9">
                                    <h5>{{$patient->date_naissance}}</h5>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3"><label for="Telephone">Téléphone :</label></div>
                                <div class="col-md-9">
                                    <h5>{{$patient->num_tel}}</h5>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3"><label for="Email">Email :</label></div>
                                <div class="col-md-9">
                                    <h5>{{$patient->email}}</h5>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3"><label for="NumSS">Numéro de sécurité sociale :</label></div>
                                <div class="col-md-9">
                                    <h5>{{$patient->num_ss}}</h5>
                                </div>
                            </div>
                        </div>
                    </div> 
@endsection