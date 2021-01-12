@extends('auth.LayoutLogin')
@section('image')
<div class="col-lg-6 d-none d-lg-block bg-login-image-entre" style="margin-right: -75px;"></div>
@endsection
@section('slogan')
<h1 class="h4 text-gray-900 mb-4"><i>"  Qui jouit d'une santé parfaite possède un trésor.  "</i></h1>
@endsection
@section('form-login')

<div class="btn-grp">
    <center>
     <h4 class="font-weight-light ">Veuillez choisir votre rôle à la clinique</h4>
    <center>
    <a class="btn btn-primary btn-user btn-block" href="{{route('medecin.login')}}">Medecin</a>
    <a class="btn btn-primary btn-user btn-block" href="{{route('secretaire.login')}}">Secretaire</a>
    @if(session()->has('success'))
    <center>
        <div class="alert alert-success mt-3">
            {{ session()->get('success') }}
        </div>
    </center>
@endif
</div>
@endsection

@section('end-section')
@endsection