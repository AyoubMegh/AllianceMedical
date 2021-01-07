@extends('auth.LayoutLogin')
@section('form-login')
<a class="btn btn-primary btn-user btn-block" href="{{route('medecin.login')}}">Medecin</a>
<a class="btn btn-primary btn-user btn-block" href="{{route('secretaire.login')}}">Secretaire</a>
@endsection

@section('end-section')
@endsection