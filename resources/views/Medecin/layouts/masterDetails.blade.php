@extends('Medecin.layouts.master')
@section('content')

<div class="container center-div">
    <center><h1>Détails du Patient</h1></center>
    <ul class="nav nav-tabs1 mt-3">
                        <li @yield('activation') >
                            <form action="{{route('medecin.detailsPatient')}}" id="infoPers">
                                <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                <a href="javascript:$('#infoPers').submit()" >Informations Personnelle</a>
                            </form>
                        </li>
                        <li @yield('activation1')>
                            <form action="{{route('medecin.dossierMedicalForm')}}" id="dosMed">
                                <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                 <a href="javascript:$('#dosMed').submit()" >Information Medicale</a>
                            </form>     
                        </li>
                        <li @yield('activation2')>
                            <form action="{{route('medecin.imageriesForm')}}" id="imgs">
                                <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                <a href="javascript:$('#imgs').submit()">Imageries</a>
                            </form>
                        </li>
                        <li @yield('activation3')>
                            <form action="{{route('medecin.ordonnancesForm')}}" id="ords">
                                <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                <a href="javascript:$('#ords').submit()">Ordonnances</a>
                            </form>
                        </li>
                        <li class="dropdown" @yield('activation4')>
                            <a class="dropdown-toggle" class="sansForm" data-toggle="dropdown" href="#">Lettres et autres
                            <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                            <li>
                                <form action="{{route('medecin.certificatBonneSanteForm')}}" method="get" id='cbs'>
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <a href="javascript:$('#cbs').submit()">Certificat de Bonne santé</a>
                                </form>
                            </li>
                            <li>
                                <form action="{{route('medecin.certificatPneumoPhtisiologieForm')}}" method="get" id="cpp">
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <a href="javascript:$('#cpp').submit()">Certificat de Pneumo phtisiologie</a>
                                </form>
                            </li>
                            <li>
                                <form action="{{route('medecin.certificatRepriseTravailForm')}}" method="get" id="crt">
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <a href="javascript:$('#crt').submit()">Certificat de Reprise</a>
                                </form>
                            </li>
                            <li>
                                <form action="{{route('medecin.certificatArretTravailForm')}}" method="get" id="cat">
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <a href="javascript:$('#cat').submit()">Certificat d'Arret de travail</a>
                                </form>
                            </li>
                            <li>
                                <form action="{{route('medecin.lettreOrientationForm')}}" method="get" id='lo'>
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <a href="javascript:$('#lo').submit()">Lettre d'Orientation</a>
                                </form>
                            </li>
                            </ul>
                        </li>
                        <li @yield('activation5')>
                            <form action="{{route('medecin.reprendreRDVForm')}}" id="rdvs">
                                <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                <a href="javascript:$('#rdvs').submit()">Prendre RDV</a>
                            </form>
                        </li>
                    </ul>
    <div class="card shadow mb-4">
        <div class="card-body">
            @yield('details_content')
        </div>
    </div>
</div>
@endsection