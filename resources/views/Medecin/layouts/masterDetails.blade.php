@extends('Medecin.layouts.master')
@section('content')

<div class="container center-div">
    <center><h1>Détails du Patient</h1></center>
    <ul class="nav nav-tabs1 mt-5">
                        <li @yield('activation') ><a href="{{route('medecin.detailsPatient')}}" >Informations Personnelle</a></li>
                        <li @yield('activation1')><a href="{{route('medecin.dossierMedicalForm')}}" >Dossier Medical</a></li>
                        <li @yield('activation2')><a href="{{route('medecin.imageries')}}">Imageries</a></li>
                        <li @yield('activation3')><a href="{{route('medecin.ordonnances')}}">Ordonnances</a></li>
                        <li class="dropdown" @yield('activation4')>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="{{route('medecin.lettres')}}">Lettres et autres
                            <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                            <li><a href="#">Certificat de Bonne santé</a></li>
                            <li><a href="#">Certificat de Pneumo phtisiologie</a></li>
                            <li><a href="#">Certificat de Reprise</a></li>
                            <li><a href="#">Certificat d'Arret de travail'</a></li>
                            <li><a href="#">Demande de Billan</a></li>
                            <li><a href="#">Lettre d'Orientation</a></li>
                            </ul>
                        </li>
                        <li @yield('activation5')><a href="{{route('medecin.reprendreRDV')}}">Prendre RDV</a></li>
                    </ul>
    <div class="card shadow mb-4">
        <div class="card-body">
            @yield('details_content')
        </div>
    </div>
</div>
@endsection