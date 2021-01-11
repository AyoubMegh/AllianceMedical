@extends('Medecin.layouts.master')
@section('content')
<div class="container center-div">
    <center><h1>Supprimer Secretaire</h1></center>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-sm-12 col-md-6" id="dataTable_length">
                            <label>
                                Show 
                                <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </label>
                    
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="dataTable_filter" class="dataTables_filter">
                            <label style="width: 100%;">
                                Recherche Secretaire :
                                <input type="search" class="form-control form-control-sm" placeholder="Nom du Secretaire" aria-controls="dataTable">
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
                            <th>Adresse Email</th>
                            <th>Numéro Téléphone</th>
                            <th>Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($secretaires as $sec)
                        <tr>
                            <td>{{$sec->nom}} {{$sec->prenom}}</td>
                            <td>{{$sec->email}}</td>
                            <td>{{$sec->num_tel}}</td>
                            <td>
                                <form action="{{route('medecin.supprimerSecretaire')}}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <input type="hidden" name="id_sec" value="{{$sec->id_sec}}">
                                    <center>
                                            <button type="submit" class="btn">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                    </center>
                                </form>
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