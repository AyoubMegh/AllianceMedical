@extends('Medecin.layouts.master')
@section('content')
<div class="container center-div">
    <center><h1>Liste des Secretaires</h1></center>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                    <div class="col-sm-12 col-md-6" id="dataTable_length">
                    <div class="text-left py-4">
                                                <a class= "btn btn-outline-primary" href="{{route('medecin.listeSecretaires')}}" > << </a>
                                                </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="dataTable_filter" class="dataTables_filter">
                            <label style="width: 100%;">
                                Recherche Secretaire :
                                <form action="{{route('medecin.listeSecretaires')}}" method="GET">
                                    <div class="row">
                                        <div class="col-10 mt-1">
                                            <input type="search" name="nom_sec" class="form-control form-control-sm" placeholder="Nom du Secretaire" aria-controls="dataTable">
                                        </div>
                                        <div class="col-2">
                                            <button type="submit" class="btn btn-submit" style="background-color: #2146b7"><i class="fa fa-search-plus" style="color: white"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </label>
                        </div>
                    </div>
            </div>
            <div class="row">
                @if($errors->any())
                    <div class="alert alert-danger col-12 mt-1 mb-0" id="warningSubmit" role="alert">
                    <center> <ul>
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
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Mettre a Jour</th>
                            <th>Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach($secretaires as $secretaire)
                        <tr>
                            <td>{{$secretaire->nom}} {{$secretaire->prenom}}</td>
                            <td>{{$secretaire->num_tel}}</td>
                            <td>{{$secretaire->email}}</td>
                            <td>
                                <center>
                                    <form action="{{route('medecin.mettreAjourSecretaireForm')}}" method="get">
                                        <input type="hidden" name="id_sec" value="{{$secretaire->id_sec}}">
                                        <button type="submit" class="btn">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <form action="{{route('medecin.supprimerSecretaire')}}" id="delete_form" method="post">
                                        <input type="hidden" name="id_sec" value="{{$secretaire->id_sec}}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" id="annuler" class="btn">
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
@section('scripts')
<script>
     $('#annuler').click(function(e){
        e.preventDefault();
        if(confirm('Voulez Vous Vraiment Effectuer Cette Action ?')){
            $('#delete_form').submit();
        }else{
            return false;
        }
    });
</script>
@endsection