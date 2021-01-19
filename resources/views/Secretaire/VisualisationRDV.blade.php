@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
                        <center>
                            <h1>Liste des Rendez-vous</h1>
                        </center>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">

                                <div class="row">
                                    <div class="col-sm-12 col-md-6" id="dataTable_length"></div>
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

                            <!-- début de la table -->
                            <section id="tabs" class="project-tab">
                                    <div class="container" >
                                         <div class="row">
                                             <div class="col-md-13" style="width:100%"> 
                                                 <nav>
                                                 <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                                   <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Affichage par Patient</a>
                                                   
                                                   <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Affichage par Medecin</a>
                                                   </div>
                                                 </nav>
                                                 <div class="tab-content" id="nav-tabContent">
                                                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                                             <!-- début de la première colonne -->
                                                         <div class="card-body">
                                                              <div class="table-responsive">
                                                             <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                  <thead>
                                                                      <tr>
                                                                        <th>Nom et Prenom</th>
                                                                        <th>Date Naissance</th>
                                                                        <th>N° Téléphone</th>
                                                                        <th>Jour de consultation</th>
                                                                        <th>Heure de consultation</th>
                                                                        <th>Nom du Medecin</th>
                                                                        
                                                                       </tr>
                                                                 </thead>
                                                                 <tbody>
                                                                   @foreach($rdvs as $rdv)
                                                                    <tr>
                                                                      <td>{{$rdv[1]['nom']}} {{$rdv[1]['prenom']}}</td>
                                                                      <td>{{$rdv[1]['date_naissance']}}</td>
                                                                      <td>{{$rdv[1]['num_tel']}}</td>
                                                                      <td>
                                                                        @foreach($rdv[0] as $r)
                                                                          {{$r->date_rdv}}
                                                                          @if(! $loop->last)
                                                                            <hr>
                                                                          @endif
                                                                        @endforeach
                                                                      </td>
                                                                      <td>
                                                                        @foreach($rdv[0] as $r)
                                                                          {{$r->heure_debut}}
                                                                          @if(! $loop->last)
                                                                            <hr>
                                                                          @endif
                                                                        @endforeach
                                                                      </td>
                                                                      <td>
                                                                        @foreach($rdv[0] as $r)
                                                                          {{$r->nom}} {{$r->prenom}}
                                                                          @if(! $loop->last)
                                                                            <hr>
                                                                          @endif
                                                                        @endforeach
                                                                      </td>
                                                                    </tr>
                                                                   @endforeach
                                                                      
                                                                    </tbody>
                                                               </table>
                                                             </div>
                                                        </div>
                                                </div>
                                                <!-- fin de la première colonne -->

                                                

                                                <!-- debut de la deuxième colonne -->
                                                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                                <div class="card-body">
                                                              <div class="table-responsive">
                                                              @foreach($meds as $med)
                                                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                    <thead>
                                                                      <div class="row mb-2">
                                                                        <div class="col col-9 mt-2">
                                                                          <h4>DR. {{$med[1]['nom']}} {{$med[1]['prenom']}}</h4>
                                                                        </div>
                                                                        <div class="col col-3 mb-1">
                                                                          <form action="{{route('secretaire.prendreRDV')}}" method="get">
                                                                              <input type="hidden" name="id_med" value="{{$med[1]['id_med']}}">
                                                                              <button type="submit" class="btn btn-success">Ajouter un Rendez Vous</button>
                                                                          </form>
                                                                        </div>
                                                                      </div>
                                                                      <tr>
                                                                        <th>Nom et Prenom</th>
                                                                        <th>Date Naissance</th>
                                                                        <th>N° Téléphone</th>
                                                                        <th>Jour de consultation</th>
                                                                        <th>Heure de consultation</th>
                                                                        <th>fin de consultation</th>
                                                                      </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($med[0] as $m)
                                                                            <tr>
                                                                                <td>{{$m->nom}} {{$m->prenom}}</td>
                                                                                <td>{{$m->date_naissance}}</td>
                                                                                <td>{{$m->num_tel}}</td>
                                                                                <td>{{$m->date_rdv}}</td>
                                                                                <td>{{$m->heure_debut}}</td>
                                                                                <td>{{$m->heure_fin}}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>  
                                                              @endforeach
                                                             
                                                             </div>
                                                        </div>
                                                </div>
                                                <!-- fin de la deuxième colonne -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                </section>
                                    <!-- fin de la table -->
                            
                        </div>
                    </div> 
        
@endsection