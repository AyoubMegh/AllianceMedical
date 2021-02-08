@extends('Medecin.layouts.masterDetails')
@section('activation4')
class="active"
@endsection
@section('details_content')
<center><h5>Veuillez choisir un type de lettre :</h5></center>

<ul class="mt-5">
                            <li class="mt-2">
                                <form action="{{route('medecin.certificatBonneSanteForm')}}" method="get" id='cbs'>
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <a href="javascript:$('#cbs').submit()">Certificat de Bonne santé</a>
                                </form>
                            </li>
                            <li class="mt-2">
                                <form action="{{route('medecin.certificatPneumoPhtisiologieForm')}}" method="get" id="cpp">
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <a href="javascript:$('#cpp').submit()">Certificat de Pneumo phtisiologie</a>
                                </form>
                            </li>
                            <li class="mt-2">
                                <form action="{{route('medecin.certificatRepriseTravailForm')}}" method="get" id="crt">
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <a href="javascript:$('#crt').submit()">Certificat de Reprise</a>
                                </form>
                            </li>
                            <li class="mt-2">
                                <form action="{{route('medecin.certificatArretTravailForm')}}" method="get" id="cat">
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <a href="javascript:$('#cat').submit()">Certificat d'Arret de travail</a>
                                </form>
                            </li>
                            <li class="mt-2">
                                <form action="{{route('medecin.lettreOrientationForm')}}" method="get" id='lo'>
                                    <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                    <a href="javascript:$('#lo').submit()">Lettre d'Orientation</a>
                                </form>
                            </li>
                            </ul>
@endsection