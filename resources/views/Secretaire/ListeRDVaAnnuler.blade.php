@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
    <center>
        <h1>Annuler Rendez-Vous</h1>
    </center>
    <div class="card shadow mt-5 mb-4">
        <div class="card-header py-3">
            <div class="row">
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
                            <th>Date</th>
                            <th>Heure Debut</th>
                            <th>Heure Fin</th>  
                            <th>Nom du Medecin</th>                                  
                            <th>Motif</th>
                            <th>Annuler</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($details as $data)
                           <tr>
                                <td>{{$data->date_rdv}}</td>
                                <td>{{$data->heure_debut}}</td>
                                <td>{{$data->heure_fin}}</td>
                                <td>{{$data->nom}} {{$data->prenom}}</td>
                                <td>{{$data->motif}}</td>
                                <td>
                                    <center>
                                        <form action="{{route('secretaire.supprimerRDV')}}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <input type="hidden" name="id_rdv" value="{{$data->id_rdv}}">
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