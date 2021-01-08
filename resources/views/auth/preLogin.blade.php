@extends('auth.LayoutLogin')
@section('image')
<div class="col-lg-6 d-none d-lg-block bg-login-image-entre" style="margin-right: -75px;"></div>
@endsection
@section('slogan')
<h1 class="h4 text-gray-900 mb-4"><i>" Qui n'a santé n'a rien. "</i></h1>
@endsection
@section('form-login')
<a class="btn btn-primary btn-user btn-block" href="{{route('medecin.login')}}">Medecin</a>
<a class="btn btn-primary btn-user btn-block" href="{{route('secretaire.login')}}">Secretaire</a>
@endsection

@section('end-section')
@endsection