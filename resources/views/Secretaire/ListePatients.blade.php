@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
                        <center>
                            <h1>Liste des Patients</h1>
                        </center>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                <div class="col-sm-12 col-md-6" id="dataTable_length">
                                    <div class="text-left py-4">
                                                <a class= "btn btn-outline-primary" href="{{route('secretaire.listePatients')}}" > << </a>
                                                </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div id="dataTable_filter" class="dataTables_filter">
                                            <label style="width: 100%;">
                                                Recherche Patient :
                                                <form action="{{route('secretaire.listePatients')}}" method="GET">
                                                    <div class="row">
                                                        <div class="col-10 mt-1">
                                                            <input type="search" name="num_ss" class="form-control form-control-sm" placeholder="N° Sécurité Sociale" aria-controls="dataTable">
                                                        </div>
                                                        <div class="col-2">
                                                            <button type="submit" class="btn btn-submit" style="background-color: #2146b7"><i class="fa fa-search-plus" style="color: white"></i></button>
                                                        </div>
                                                    </div>

                                                </form>
                                            </label>
                                        </div>
                                    </div>
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
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Nom et Prenom</th>
                                                <th>N° Sécurité Sociale</th>
                                                <th>Détails</th>
                                                <th>Prendre RDV</th>
                                                <th>Divers RDV</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($patients as $patient)
                                                <tr>
                                                    <td>{{$patient->nom}} {{$patient->prenom}}</td>
                                                    <td>{{$patient->num_ss}}</td>
                                                    <td>
                                                        <center> 
                                                            <form method="GET" action="{{route('secretaire.detailsPatient')}}">
                                                                <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                                                <button type="submit" class="btn">
                                                                    <i class="far fa-eye"></i>
                                                                </button>
                                                            </form>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <form method="GET" action="{{route('secretaire.reprendreRDV')}}">
                                                                <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                                                <button type="submit" class="btn">
                                                                    <i class="far fa-calendar-check"></i>
                                                                </button>
                                                            </form>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <form method="GET" action="{{route('secretaire.listeRendezVous')}}">
                                                                <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                                                <button type="submit" class="btn">
                                                                    <i class="far fa-calendar-alt"></i>
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
@endsection