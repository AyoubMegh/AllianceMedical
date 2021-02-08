@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
                        <center>
                            <h1>Liste des Medecins</h1>
                        </center>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                <div class="col-sm-12 col-md-6" id="dataTable_length">
                                    <div class="text-left py-4">
                                                <a class= "btn btn-outline-primary" href="{{route('secretaire.listeMedecins')}}" > << </a>
                                                </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div id="dataTable_filter" class="dataTables_filter">
                                            <label style="width: 100%;">
                                                Recherche Medecin :
                                                <form action="{{route('secretaire.listeMedecins')}}" method="GET">
                                                    <div class="row">
                                                        <div class="col-10 mt-1">
                                                            <input type="search" name="nom" class="form-control form-control-sm" placeholder="Nom Medecin" aria-controls="dataTable">
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
                                    <center><ul>
                                            @foreach($errors->all() as $error)
                                            <li>{{$error}}</li>
                                            @endforeach
                                        <ul></center>
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
                                                <th>Numéro téléphone</th>
                                                <th>Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($medecins as $medecin)
                                                <tr>
                                                    <th>DR. {{$medecin->nom}} {{$medecin->prenom}}</th>
                                                    <th>{{$medecin->num_tel}}</th>
                                                    <th>
                                                        <center> 
                                                            <form method="GET" action="{{route('secretaire.detailsMedecin')}}">
                                                                <input type="hidden" name="id_med" value="{{$medecin->id_med}}">
                                                                <button type="submit" class="btn">
                                                                    <i class="far fa-eye"></i>
                                                                </button>
                                                            </form>
                                                        </center>
                                                    </th>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>   
@endsection