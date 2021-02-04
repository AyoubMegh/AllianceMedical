@extends('Medecin.layouts.master')
@section('content')
<div class="container center-div">
    <center><h1>Mes Rendez-Vous</h1></center>
    <div class="card-body">
        <div id="calendrier"></div>
    </div>
</div>
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
                <div class="form-group ">
                    <label for="date_rdv">Nom et Prenom :</label>
                    <input class="form-control" type="text" name="nom_prenom_show" value="" id="nom_prenom_show" disabled required>
                </div>
                <div class="form-group mt-4">
                    <label for="date_rdv">N° Sécurité Sociale :</label>
                    <input class="form-control" type="text" name="num_ss" value="" id="num_ss_show" disabled required>
                </div>
                <div class="form-group mt-4">
                    <label for="date_rdv">Date du Rendez-vous :</label>
                    <input class="form-control" type="date" name="date_rdv" value="" id="date_rdv_show" disabled required>
                </div>
                <div class="form-group">
                    <label for="heure_Deb_Fin">Heure Debut et Fin :</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="time" class="form-control" name="heure_deb" value="" id="heure_deb_show" disabled required>
                        </div>
                        <div class="col-md-6">
                            <input type="time" class="form-control" name="heure_fin" value="" id="heure_fin_show" disabled required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="motif">Motif :</label>
                    <input type="text" class="form-control" name="motif_show" value="" id="motif_show" placeholder="Ecrivez votre motif ici !" disabled required>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
</div>
@endsection
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
            events:"/medecin/EventsMed/"+{{Auth::user()->id_med}}, 
            eventColor:'#200540',
            eventClick:function(info){
                $('#date_rdv_show').attr('value',info.event.extendedProps.date_rdv);
                $('#heure_deb_show').attr('value',info.event.extendedProps.heure_deb);
                $('#heure_fin_show').attr('value',info.event.extendedProps.heure_fin);
                $('#nom_prenom_show').attr('value',info.event.extendedProps.nom_prenom);
                $('#num_ss_show').attr('value',info.event.extendedProps.num_ss);
                $('#motif_show').attr('value',info.event.extendedProps.motif);
                $('#details').modal("show");
            },
        });
        calendrier.render();
    });
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