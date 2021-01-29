@extends('Medecin.layouts.master')
@section('content')
<div class="container center-div">
    <div class="container center-div">
        <center>
            <h1>Details Medecin Avec Divers Rendez-vous</h1>
        </center>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="col-12 mt-1 mb-0" id="dataTable_length">
                    <div class="row mt-3">
                        <div class="col-md-3"><label for="NomPrenom">Nom et Prenom :</label></div>
                        <div class="col-md-9">
                            <h5>Dr. {{$medecin->nom}} {{$medecin->prenom}}</h5>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3"><label for="NumSS">Specialité :</label></div>
                        <div class="col-md-9">
                            <h5>{{$medecin->specialite}}</h5>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3"><label for="NumSS">Numéro de téléphone :</label></div>
                        <div class="col-md-9">
                            <h5>{{$medecin->num_tel}}</h5>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3"><label for="NumSS">Email :</label></div>
                        <div class="col-md-9">
                            <h5>{{$medecin->email}}</h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-md-12"><center><h3><u>Liste des Rendez-vous du Medecin :</u></h3></center></div>
                    </div>
                </div>
                <div class="row">
                @if($errors->any())
                    <div class="alert alert-danger col-12 mt-1 mb-0" id="warningSubmit" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        <ul>
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
                <div id="calendrier">

                </div>
            </div>
        </div>
    </div>   
</div>
<div class="modal" tabindex="-1" role="dialog" id="details">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{route('medecin.MAJRDV')}}" method="post">
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
                    <label for="motif">Motif :</label>
                    <input type="text" class="form-control" name="motif" value="" id="motif" placeholder="Ecrivez votre motif ici !" required>
                </div>
                <input type="hidden" name="id_rdv" id="id_rdv" value="">
                <input type="hidden" name="id_med" id="id_med" value="{{$medecin->id_med}}">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Mettre A Jour Le Rendez-Vous</button>
        </form>
          <form action="{{route('medecin.supprimerRDV')}}" method="post">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit" class="btn btn-danger" >Annuler Le Rendez-Vous</button>
            <input type="hidden" name="id_rdv" id="id_rdv_supp" value="">
          </form>
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
            events:"/medecin/EventsMed/"+{{$medecin->id_med}}, 
            eventColor:'#200540',
            eventClick:function(info){
                console.log({{$rdvs->get(0)}});
                $('#date_rdv').attr('value',info.event.extendedProps.date_rdv);
                $('#heure_deb').attr('value',info.event.extendedProps.heure_deb);
                $('#heure_fin').attr('value',info.event.extendedProps.heure_fin);
                $('#motif').attr('value',info.event.extendedProps.motif);
                $('#id_rdv').attr('value',info.event.id);
                $('#id_rdv_supp').attr('value',info.event.id);
                $('#details').modal("show");
            }

        });
        calendrier.render();
    });
   
</script>
@endsection