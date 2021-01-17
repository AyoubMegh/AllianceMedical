@extends('Medecin.layouts.master')
@section('content')
<div class="container center-div">
    <center><h1>Liste des Patients</h1></center>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-sm-12 col-md-6" id="dataTable_length">
                
                </div>
                <div class="col-sm-12 col-md-6">
                    <div id="dataTable_filter" class="dataTables_filter">
                        <label style="width: 100%;">
                            Recherche Patient :
                            <input type="search" class="form-control form-control-sm" placeholder="Nom du Patient" aria-controls="dataTable">
                        </label>
                    </div>
                </div>
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
                            <th>Ordonnances</th>
                            <th>Dossier Medicale</th>
                            <th>Lettres et Autres</th>
                            <th>Divers RDV</th>
                            <th>Imageries</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td>{{$patient->nom}} {{$patient->prenom}}</td>
                                <td>{{$patient->num_ss}}</td>
                                <td>
                                    <center>
                                        <form  method="GET" action="{{route('medecin.detailsPatientForm')}}">
                                            <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                            <button type="submit" class="btn">
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </form>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <form  method="GET" action="#">
                                            <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                            <button type="submit" class="btn">
                                                <i class="far fa-file-alt"></i>
                                            </button>
                                        </form>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <form  method="GET" action="{{route('medecin.dossierMedicalForm')}}">
                                            <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                            <button type="submit" class="btn">
                                                <i class="fas fa-file-medical-alt"></i>
                                            </button>
                                        </form>
                                    </center>   
                                </td>
                                <td>
                                    <center>
                                        <form  method="GET" action="#">
                                            <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                            <button type="submit" class="btn">
                                                <i class="far fa-envelope-open"></i>
                                            </button>
                                        </form>
                                </center>
                                </td>
                                <td>
                                    <center>
                                        <form  method="GET" action="#">
                                            <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                            <button type="submit" class="btn">
                                                <i class="far fa-calendar-check"></i>
                                            </button>
                                        </form>
                                </center>
                                </td>
                                <td>
                                    <center>
                                        <form  method="GET" action="#">
                                            <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                            <button type="submit" class="btn">
                                                <i class="fas fa-x-ray"></i>
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