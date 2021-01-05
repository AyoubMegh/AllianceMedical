@extends('MedecinAdmin.layouts.master')
@section('content')
<div class="container center-div">
    <center><h1>Prendre un Rendez-vous</h1></center>
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
                            <th>Date Naissance</th>
                            <th>Numéro Téléphone</th>
                            <th>Détails</th>
                            <th>Prendre RDV</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Flen BenFlen</td>
                            <td>16-04-1999</td>
                            <td>0555000000</td>
                            <td>
                                <center>
                                    <form  method="GET" action="DetailsPatient.html">
                                        <input type="hidden" name="id" value="1">
                                        <button type="submit" class="btn">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </form>
                                 </center>
                            </td>
                            
                            <td>
                                <center>
                                    <form  method="GET" action="RDV.html">
                                        <input type="hidden" name="id" value="1">
                                        <button type="submit" class="btn">
                                            <i class="far fa-calendar-check"></i>
                                        </button>
                                    </form>
                                </center>                                              
                            </td> 
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection