@extends('Medecin.layouts.masterDetails')
@section('activation5')
class="active"
@endsection
@section('details_content')

    <form action="{{route('medecin.reprendreRDV')}}" method="post"> 
        {{ csrf_field() }}
        <div class="form-group mt-4">
            <label for="date_rdv">Date du Rendez-vous :</label>
            <input class="form-control" type="date" name="date_rdv" value="{{ old('date_rdv') }}" id="date_rdv" required>
        </div>
        <div class="form-group">
            <label for="heure_Deb_Fin">Heure Debut et Fin :</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="time" class="form-control" name="heure_deb" value="{{ old('heure_deb') }}" id="heure_deb" required>
                </div>
                <div class="col-md-6">
                    <input type="time" class="form-control" name="heure_fin" value="{{ old('heure_fin') }}" id="heure_fin" required>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="motif">Motif :</label>
            <input type="text" class="form-control" name="motif" value="{{ old('motif') }}" id="motif" placeholder="Ecrivez votre motif ici !" required>
        </div>
        <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
        @if($errors->any())
            <div class="alert alert-danger" id="warningSubmit" role="alert">
                <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
                <ul>
            </div>
        @endif
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <div class="form-group mt-5">
            <button type="submit" class="btn btn-success">Ajouter</button>
            <button type="reset" class="btn btn-dark">Vider</button>
        </div>
    </form>
    <hr>
    <div class="container center-div">
        <center><h5><u>Liste de ces Rendez-vous</u></h5></center>
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
                        <label for="id_med">Nom du Medecin :</label>
                        <select name="id_med" id="id_med"  class="form-control" disabled required>
                            <option value="" disabled selected>Choisissez une Medecin</option>
                            @foreach($medecins as $medecin)
                                <option value="{{$medecin->id_med}}">DR.{{$medecin->nom}} {{$medecin->prenom}} ({{$medecin->specialite}})</option>
                            @endforeach
                        </select>
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
    <div class="modal" tabindex="-1" role="dialog" id="ajouter_rdv_modal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Prendre Rendez-Vous</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{route('medecin.reprendreRDV')}}" method="post">
            <div class="modal-body">
                    {{ csrf_field() }}
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
                    <input type="hidden" name="id_med" id="id_med" value="{{Auth::user()->id_med}}">
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
                console.log(info.event.extendedProps);
                $('#date_rdv_show').attr('value',info.event.extendedProps.date_rdv);
                $('#heure_deb_show').attr('value',info.event.extendedProps.heure_deb);
                $('#heure_fin_show').attr('value',info.event.extendedProps.heure_fin);
                $('#motif_show').attr('value',info.event.extendedProps.motif);
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