@extends('Medecin.layouts.masterDetails')
@section('activation1')
class="active"
@endsection
@section('details_content')

<form action="{{route('medecin.dossierMedical')}}" method="post">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
        
        <div class="form-group">
            <label for="maladies">Maladies chroniques :</label>
            <input type="text" name="maladies" id="maladies" value="{{$patient->maladies}}" class="form-control" placeholder="maladies" >
        </div>
        <div class="form-group">
            <label for="allergies">Allergies :</label>
            <input type="text" name="allergies" id="allergies" value="{{$patient->allergies}}" class="form-control" placeholder="allergies" >
        </div>
        <div class="form-group">
            <label for="antecedents">Antecedents :</label>
            <input type="text" name="antecedents" id="antecedents" value="{{$patient->antecedents}}" class="form-control" placeholder="antecedents" >
        </div>
        <div class="form-group">
            <label for="commentaires">Commentaires :</label>
            <input type="text" name="commentaires" id="commentaires" value="{{$patient->commentaires}}" class="form-control" placeholder="commentaires" >
        </div>

        <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
        @if($errors->any())
            <div class="alert alert-danger" id="warningSubmit" role="alert">
            <center><ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
                <ul></center>
            </div>
         @endif
         @if(session()->has('success'))
            <div class="alert alert-success">
            <center>{{ session()->get('success') }}</center>
            </div>
        @endif
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-success">Mettre à Jour</button>
        </div>
    </form>
      
@endsection