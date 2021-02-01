@extends('Medecin.layouts.master')
@section('content')
    <div class="container center-div" id="calendrier">
    </div>  
@endsection
<div class="modal" tabindex="-1" role="dialog" id="details">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Details du Rendez-Vous</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
                <div class="form-group mt-4">
                    <label for="date_rdv">Date du Rendez-vous :</label>
                    <input class="form-control" type="date" name="date_rdv" value="" id="date_rdv" disabled>
                </div>
                <div class="form-group">
                    <label for="heure_Deb_Fin">Heure Debut et Fin :</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="time" class="form-control" name="heure_deb" value="" id="heure_deb" disabled>
                        </div>
                        <div class="col-md-6">
                            <input type="time" class="form-control" name="heure_fin" value="" id="heure_fin" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="heure_Deb_Fin">Nom et Prenom Patient :</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="nom_patient" value="" id="nom_patient" disabled>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="nom_patient" value="" id="prenom_patient" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="motif">N° sécurité Sociale :</label>
                    <input type="number" class="form-control" name="motif" value="" id="num_ss" placeholder="Ecrivez votre motif ici !" disabled>
                </div>
                <div class="form-group">
                    <label for="motif">Motif :</label>
                    <input type="text" class="form-control" name="motif" value="" id="motif" placeholder="Ecrivez votre motif ici !" disabled>
                </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
</div>
@section('scripts')
<script>
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
            events:"/medecin/DashMed/"+{{Auth::user()->id_med}}, 
            eventColor:'#200540',
            eventClick:function(info){
                $('#date_rdv').attr('value',info.event.extendedProps.date_rdv);
                $('#heure_deb').attr('value',info.event.extendedProps.heure_deb);
                $('#heure_fin').attr('value',info.event.extendedProps.heure_fin);
                $('#motif').attr('value',info.event.extendedProps.motif);
                $('#nom_patient').attr('value',info.event.extendedProps.nom);
                $('#prenom_patient').attr('value',info.event.extendedProps.prenom);
                $('#num_ss').attr('value',info.event.extendedProps.num_ss);
                $('#details').modal("show");
            },
        });
        calendrier.render();
        setInterval(function(){
          calendrier.refetchEvents();
        },5000);
    });
   
</script>
@endsection