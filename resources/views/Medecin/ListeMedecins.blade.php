@extends('Medecin.layouts.master')
@section('content')
<div class="container center-div">
    <center><h1>Liste des Medecins</h1></center>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                    <div class="col-sm-12 col-md-6" id="dataTable_length">
                    
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="dataTable_filter" class="dataTables_filter">
                            <label style="width: 100%;">
                                Recherche Medecin :
                                <input type="search" class="form-control form-control-sm" placeholder="Nom du Medecin" aria-controls="dataTable">
                            </label>
                        </div>
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nom et Prenom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Specialité</th>
                            <th>Mettre a Jour</th>
                            <th>Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach($medecins as $medecin)
                        <tr>
                            <td>DR. {{$medecin->nom}} {{$medecin->prenom}}</td>
                            <td>{{$medecin->num_tel}}</td>
                            <td>{{$medecin->email}}</td>
                            <td>{{$medecin->specialite}}</td>
                            <td>
                                <center>
                                    <form action="{{route('medecin.mettreAjourMedecinForm')}}" method="get">
                                        <input type="hidden" name="id_med" value="{{$medecin->id_med}}">
                                        <button type="submit" class="btn">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <form action="{{route('medecin.supprimerMedecin')}}" method="post">
                                        <input type="hidden" name="id_med" value="{{$medecin->id_med}}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
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
@endsection