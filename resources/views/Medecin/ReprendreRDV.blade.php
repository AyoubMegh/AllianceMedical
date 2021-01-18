@extends('Medecin.layouts.masterDetails')
@section('activation5')
class="active"
@endsection
@section('details_content')

<form action="#" method="post">
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

@endsection