@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
                        <center>
                            <h1>Liste des Rendez-vous</h1>
                        </center>
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
                                                                      <tr >
                                                                        <td>Flen BenFlen</td>
                                                                        <td>16-04-1999</td>
                                                                        <td>05-56-99-87-00</td>
                                                                        <td> 07-01-2021
                                                                          <hr> 07-01-2021
                                                                        </td>
                                                                        <td>14:30
                                                                          <hr> 15:30
                                                                        </td>
                                                                        <td> docteur flen
                                                                          <hr> docteur flena
                                                                       </td>
                                                                        
                                                                       </tr>
                                                                       <tr >
                                                                        <td>dek siyed</td>
                                                                        <td>20-01-1999</td>
                                                                        <td>05-56-99-87-00</td>
                                                                        <td> 09-01-2021
                                                                          <hr> 14-01-2021
                                                                          <hr> 14-10-2021
                                                                        </td>
                                                                        <td>10:30
                                                                          <hr> 08:00
                                                                          <hr> 13:45
                                                                        </td>
                                                                        <td> docteur flen2
                                                                          <hr> docteur flena3
                                                                          <hr> docteur alphabet
                                                                       </td>
                                                                        
                                                                       </tr>
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
                                                             <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                  <thead>
                                                                  <h5>docteur A</h5>
                                                                      <tr>
                                                                        <th>Nom et Prenom</th>
                                                                        <th>Date Naissance</th>
                                                                        <th>N° Téléphone</th>
                                                                        <th>Jour de consultation</th>
                                                                        <th>Heure de consultation</th>
                                                                        
                                                                       </tr>
                                                                 </thead>
                                                                 <tbody>
                                                                      <tr>
                                                                        <td>Flen BenFlen</td>
                                                                        <td>16-04-1999</td>
                                                                        <td>05-56-99-87-00</td>
                                                                        <td>07-01-2021</td>
                                                                        <td>14:30</td>
                                                                        
                                                                       </tr>
                                                                    </tbody>
                                                               </table>
                                                              
                                                                    <h5>docteur B</h5>
                                                               <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                  <thead>
                                                                      <tr>
                                                                        <th>Nom et Prenom</th>
                                                                        <th>Date Naissance</th>
                                                                        <th>N° Téléphone</th>
                                                                        <th>Jour de consultation</th>
                                                                        <th>Heure de consultation</th>
                                                                       </tr>
                                                                 </thead>
                                                                 <tbody>
                                                                      <tr>
                                                                        <td>Flen BenFlen</td>
                                                                        <td>16-04-1999</td>
                                                                        <td>05-56-99-87-00</td>
                                                                        <td>07-01-2021</td>
                                                                        <td>14:30</td>
                                                                       </tr>
                                                                       <tr>
                                                                        <td>Flena BentFlen</td>
                                                                        <td>16-04-1999</td>
                                                                        <td>05-56-99-87-00</td>
                                                                        <td>06-01-2021</td>
                                                                        <td>14:30</td>
                                                                       </tr>
                                                                    </tbody>
                                                               </table>

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