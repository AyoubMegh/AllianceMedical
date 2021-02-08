@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
    <div class="container center-div">
        <center>
            <h1>Details Patient Avec Divers Rendez-vous</h1>
        </center>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="col-12 mt-1 mb-0" id="dataTable_length">
                    <form action="{{route('secretaire.majPatient')}}" method="POST">
                        {{csrf_field()}}
                        {{method_field('PUT')}}
                        <div class="row mt-3">
                            <div class="col-md-4"><label for="NomPrenom"><h2>Nom et Prenom :</h2></label></div>
                            <div class="col-md-8">
                            <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="nom" id="nom" value="{{$patient->nom}}">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="prenom" id="nom" value="{{$patient->prenom}}">
                                    </div>
                            </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4"><label for="date_n"><h2>Date Naisssance :</h2></label></div>
                            <div class="col-md-8">
                                <input type="date" class="form-control" name="date_naissance" id="date_naissance" value="{{$patient->date_naissance}}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4"><label for="NumSS"><h2>Sécurité Sociale :</h2></label></div>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="num_ss" id="num_ss" value="{{$patient->num_ss}}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4"><label for="NumSS"><h2>N° Télephone  &nbsp;&nbsp;&nbsp;&nbsp;:</h2></label></div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="num_tel" id="num_tel" value="{{$patient->num_tel}}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4"><label for="NumSS"><h2>Adresse E-Mail &nbsp;:</h2></label></div>
                            <div class="col-md-8">
                                <input type="email" class="form-control" name="email" id="email" value="{{$patient->email}}">
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-success" value="Mettre à Jour">
                            </div>
                            <div class="col-md-10">
                                <input type="hidden" name="id_pat" id="id_pat" value="{{$patient->id_pat}}">
                            </div>
                            
                        </div>
                    </form>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-md-12"><center><h3><u>Liste des Rendez-vous du Patient :</u></h3></center></div>
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
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                                <form action="{{route('secretaire.reprendreRDV')}}" method="get">
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <input class="btn btn-primary" type="submit" value="Prendre un Nouveaux Rendez-vous ?">
                                </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                        <div id="calendrier"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="details">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form action="{{route('secretaire.MAJRDV')}}" method="post">
            <div class="modal-header">
              <h5 class="modal-title">Details du Rendez-Vous</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group mt-4">
                        <label for="date_rdv">Date du Rendez-vous :</label>
                        <input class="form-control" type="date" name="date_rdv" value="" id="date_rdv" required>
                    </div>
                    <div class="form-group">
                        <label for="heure_Deb_Fin">Heure Debut et Fin :</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="time" class="form-control" name="heure_deb" value="" id="heure_deb" required>
                            </div>
                            <div class="col-md-6">
                                <input type="time" class="form-control" name="heure_fin" value="" id="heure_fin" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_med">Nom du Medecin :</label>
                        <select name="id_med" id="id_med"  class="form-control" required>
                            <option value="" disabled selected>Choisissez une Medecin</option>
                            @foreach($medecins as $medecin)
                                <option value="{{$medecin->id_med}}">DR.{{$medecin->nom}} {{$medecin->prenom}} ({{$medecin->specialite}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="motif">Motif :</label>
                        <input type="text" class="form-control" name="motif" value="" id="motif" placeholder="Ecrivez votre motif ici !" required>
                    </div>
                    <input type="hidden" name="id_rdv" id="id_rdv" value="">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Mettre A Jour Le Rendez-Vous</button>
            </form>
              <form action="{{route('secretaire.supprimerRDV')}}" id="delete_form" method="post">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-danger" id="annuler" >Annuler Le Rendez-Vous</button>
                <input type="hidden" name="id_rdv"  id="id_rdv_supp" value="">
              </form>
            </div>
          </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="ajouter_rdv_modal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Prendre Rendez-Vous</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{route('secretaire.ajouterAutreRDV')}}" method="post">
            <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="id_med">Nom du Medecin :</label>
                        <select name="id_med" id="id_med"  class="form-control" required>
                            <option value="" disabled selected>Choisissez une Medecin</option>
                            @foreach($medecins as $medecin)
                                <option value="{{$medecin->id_med}}"  >DR.{{$medecin->nom}} {{$medecin->prenom}} ({{$medecin->specialite}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-4">
                        <label for="date_rdv">Date du Rendez-vous :</label>
                        <input class="form-control" type="date" name="date_rdv" value="" id="date_rdv_add" required>
                    </div>
                    <div class="form-group">
                        <label for="heure_Deb_Fin">Heure Debut et Fin :</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="time" class="form-control" name="heure_deb" value="" id="heure_deb_add" required>
                            </div>
                            <div class="col-md-6">
                                <input type="time" class="form-control" name="heure_fin" value="" id="heure_fin_add" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id_pat" id="id_pat" value="{{$patient->id_pat}}">
                    <div class="form-group">
                        <label for="motif">Motif :</label>
                        <input type="text" class="form-control" name="motif" id="motif_add" value="" placeholder="Ecrivez votre motif ici !" required>
                    </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Ajouter</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
            </form>
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
    document.addEventListener('DOMContentLoaded', function() {
        var calendrierDiv = document.getElementById('calendrier');
        var calendrier = new FullCalendar.Calendar(calendrierDiv,{
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
            initialView : 'dayGridMonth',
            locale:'fr',
            selectable:true,
            events:"/secretaire/EventsPatient/"+{{$patient->id_pat}}, 
            eventColor:'#200540',
            eventClick:function(info){
                $('#date_rdv').attr('value',info.event.extendedProps.date_rdv);
                $('#heure_deb').attr('value',info.event.extendedProps.heure_deb);
                $('#heure_fin').attr('value',info.event.extendedProps.heure_fin);
                $('#motif').attr('value',info.event.extendedProps.motif);
                $('#id_rdv').attr('value',info.event.id);
                $('#id_rdv_supp').attr('value',info.event.id);
                console.log(info.event.extendedProps.id_med);
                $('#id_med').val(info.event.extendedProps.id_med).change();
                $('#details').modal("show");
            },
            select:function(selectionInfo){
                var date_rdv = new Date(selectionInfo.start);
                var date_rdv_fin = new Date(selectionInfo.end);
                $('#date_rdv_add').attr('value',date_rdv.getFullYear()+"-"+( ((date_rdv.getMonth()+1).toString().length == 1)? "0"+(date_rdv.getMonth()+1) : (date_rdv.getMonth()+1) )+"-"+( ((date_rdv.getDate()).toString().length == 1)? "0"+(date_rdv.getDate()) : (date_rdv.getDate()) ));
                $('#heure_deb_add').attr('value',(((date_rdv.getHours()).toString().length == 1)? "0"+date_rdv.getHours() : date_rdv.getHours() )+":"+(((date_rdv.getMinutes()).toString().length == 1)? "0"+date_rdv.getMinutes() : date_rdv.getMinutes() ));
                $('#heure_fin_add').attr('value',(((date_rdv_fin.getHours()).toString().length == 1)? "0"+date_rdv_fin.getHours() : date_rdv_fin.getHours() )+":"+(((date_rdv_fin.getMinutes()).toString().length == 1)? "0"+date_rdv_fin.getMinutes() : date_rdv_fin.getMinutes() ));
                $('#motif_add').attr('value',"");
                $('#ajouter_rdv_modal').modal("show");
            }

        });
        calendrier.render();
    });
</script>
@endsection