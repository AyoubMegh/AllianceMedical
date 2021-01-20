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
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-sm-12 col-md-6" id="dataTable_length">
                    
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="dataTable_filter" class="dataTables_filter">
                            <label style="width: 100%;">
                                Recherche Medecin :
                                <input type="search" class="form-control form-control-sm" placeholder="Nom du Medecin" aria-controls="dataTable">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                @foreach($meds as $med)
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <div class="row mb-2">
                          <div class="col col-9 mt-2">
                            <h4>DR. {{$med[1]['nom']}} {{$med[1]['prenom']}}</h4>
                          </div>
                        </div>
                        <tr>
                          <th>Jour de consultation</th>
                          <th>Heure de consultation</th>
                          <th>Fin de consultation</th>
                          <th>Motif</th>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach ($med[0] as $m)
                              <tr>
                                <td>{{$m->date_rdv}}</td>
                                <td>{{$m->heure_debut}}</td>
                                <td>{{$m->heure_fin}}</td>
                                <td>{{$m->motif}}</td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>  
                @endforeach
               
               </div>
          </div>
  </div>   
</div>
@endsection