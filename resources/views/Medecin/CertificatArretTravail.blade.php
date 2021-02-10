@extends('Medecin.layouts.masterDetails')
@section('activation4')
class="active"
@endsection
@section('details_content')
<div class="container-fluid">
    <div class="container center-div">
        <center><h3>Certificat d'Arret de Travail</h3></center>
        <form action="{{route('medecin.certificatArretTravail')}}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="date_cert">Date Debut d'Arret:</label>
                <input type="date" class="form-control" name="date_debut" id="date_debut" required>
            </div>
            <div class="form-group">
                <label for="date_cert">Date Fin d'Arret:</label>
                <input type="date" class="form-control" name="date_fin" id="date_fin" required>
            </div>
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
                <center> {{ session()->get('success') }}</center>
                </div>
            @endif
            <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
            <div class="form-group mt-1">
                <div class="row">
                    <div class="col-sm-1 mt-1">
                        <button type="submit" id="btn_submit" class="btn btn-success">Ajouter</button>
                    </div>
                    <!--<div class="col-sm-1 mt-1"></div>
                    <div class="col-sm-8 mt-1">
                        <button class="btn btn-dark" id="btn_imprimer">Imprimer</button>
                    </div>-->
                </div>
            </div>
        </form>
    </div>
    <div id="imprimable" class="row d-none" style="position:relative;" > <!--class="d-none"-->
        <div class="col-12">
            <table  width="100%" >
                <tr >
                    <td width="25%" colspan="2"><center>Clinique  {{App\Clinique::find(1)->nom}}<br> <span id="date_lettre_affichage"></span></center></td>
                    <td width="25%" colspan="2"><center>{{Auth::user()->nom}} {{Auth::user()->prenom}} <br> {{Auth::user()->specialite}} </center></td>
                </tr>
                <tr >
                    <td colspan="4" width="100%">
                        <hr style="border: 1px solid black;">
                    </td>
                </tr>
                <tr id="pour_lignes" width="100%">
                    <td colspan="4" width="100%" >
                        <center><h3 style="color: black;margin-top:50px;margin-bottom:50px"><b><u>Certificat d'arret de travail</u></b></h3></center>
                    </td>
                </tr>
                <tr width="100%">
                    <td colspan="4" width="100%">
                        <center class="mt-5">
                            <div style="margin-top: 30%">
                                <h4>Je certifie <b>{{App\Medecin::find(Auth::user()->id_med)->nom}} {{App\Medecin::find(Auth::user()->id_med)->prenom}}</b> docteur en Medecine
                                avoir examiné le patient <b>{{App\Patient::find($patient->id_pat)->nom}} {{App\Patient::find($patient->id_pat)->prenom}}</b> née
                                {{App\Patient::find($patient->id_pat)->date_naissance}}.<br>
                                qui présente un(e) et qui nécessite un repos de sauf complication du <div id="range_date"></div>
                                </h4>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-12" style="position:absolute; bottom:0;width:100%;">
            <center>
                <hr style="width:100%;border: 1px solid black;">
                {{App\Clinique::find(1)->adresse}} <br>
                {{App\Clinique::find(1)->num_tel}} 
            </center>
        </div>
    </div>
    <hr>
</div>
<div class="container center-div">
    <center><h5><u>Divers Certificat d'Arret de Travail</u></h5></center>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>                                        
                        <tr>
                            <th>Nom et Prenom Medecin</th>
                            <th>Date et Heure du Certificat</th>
                            <th>Modifier</th>
                            <th>Supprimer</th>
                            <th>Imprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lettres as $lettre)
                        <tr>
                            <td>DR. {{App\Medecin::find($lettre->id_med)->nom}} {{App\Medecin::find($lettre->id_med)->prenom}}</td>
                            <td>{{$lettre->created_at}}</td>
                            <td>
                                <center><button class="btn" id="modifier_{{$lettre->id_lettre}}" data-toggle="modal" data-target="#lettre_{{$lettre->id_lettre}}"><i class="fas fa-edit"></i></button></center>
                                        <div class="modal fade" id="lettre_{{$lettre->id_lettre}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                              <div class="modal-content">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="exampleModalLabel">Modifier Certificat Arret de Travail</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="date_cert">Contenu  :</label>
                                                        <textarea class="form-control" name="contenu" id="contenu" cols="30" rows="10">{{$lettre->contenu}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                   <button type="button" class="btn btn-primary">Modifier</button>
                                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                            </td>
                            <td>
                                <form action="{{route('medecin.supprimerLettre')}}" id="delete_form_{{$lettre->id_lettre}}" method="post">
                                    {{ csrf_field() }}
                                    {{method_field('DELETE')}}
                                    <input type="hidden" name="id_lettre" value="{{$lettre->id_lettre}}">
                                    <center><button type="submit" class="btn btn_supp" id="annuler_{{$lettre->id_lettre}}"><i class="fas fa-trash-alt"></i></button></center>
                                </form>
                            </td>
                            <td>
                                <center><button class="btn btn_imp" id="{{$lettre->id_lettre}}"><i class="fas fa-print"></i></button></center>
                                <div id="imprimable_divers_{{$lettre->id_lettre}}" class="row d-none" style="position:relative;" > <!--class="d-none"-->      
                                    <div class="col-12">
                                        <table  width="100%" >
                                            <tr >
                                                <td width="25%" colspan="2"><center>Clinique  {{App\Clinique::find(1)->nom}}<br> {{$lettre->date_lettre}}</center></td>
                                                <td width="25%" colspan="2"><center>{{Auth::user()->nom}} {{Auth::user()->prenom}} <br> {{Auth::user()->specialite}} </center></td>
                                            </tr>
                                            <tr >
                                                <td colspan="4" width="100%">
                                                    <hr style="border: 1px solid black;">
                                                </td>
                                            </tr>
                                            <tr id="pour_lignes" width="100%">
                                                <td colspan="4" width="100%" >
                                                    <center><h3 style="color: black;margin-top:50px;margin-bottom:50px"><b><u>Certificat de bonne santé</u></b></h3></center>
                                                </td>
                                            </tr>
                                            <tr width="100%">
                                                <td colspan="4" width="100%">
                                                    <center class="mt-5">
                                                        <div style="margin-top: 30%">
                                                            <h4>{{$lettre->contenu}}</h4>
                                                        </div>
                                                    </center>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-12" style="position:absolute; bottom:0;width:100%;">
                                        <center>
                                            <hr style="width:100%;border: 1px solid black;">
                                            {{App\Clinique::find(1)->adresse}} <br>
                                            {{App\Clinique::find(1)->num_tel}} 
                                        </center>
                                    </div>
                                </div> 
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    window.onload=function(){
        /*document.getElementById('btn_imprimer').addEventListener('click',function(e){
            e.preventDefault();
            if(document.getElementById('date_debut').value=="" || document.getElementById('date_fin').value==""){
                return false;
            }else{
                document.getElementById('range_date').innerText = document.getElementById('date_debut').value+" au "+document.getElementById('date_fin').value;
                var frame = document.getElementById('imprimable');
                var data = frame.innerHTML;
                var win = window.open('', '', 'height=500,width=900');
                win.document.write('<style>@page{size: A4 }</style><html><head><title></title>');
                win.document.write('</head><body >');
                win.document.write(data);
                win.document.write('</body></html>');
                win.print();
                win.close();
            }
        });*/
        document.getElementById('btn_submit').addEventListener('click',function(e){
            if(!document.getElementById('date_debut').value == "" && !document.getElementById('date_fin').value==""){
                var date_deb = document.getElementById('date_debut').value;
                    var date_fin = document.getElementById('date_fin').value;
                    if(new Date(date_deb)>new Date(date_fin)){
                        alert("Incohérence dans les dates !");
                        document.getElementById('date_fin').value="";
                        return false;
                    }else{
                    if(confirm("Voulez-vous d'abord Imprimer le certificat ?")){
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); 
                        var yyyy = today.getFullYear();
                        document.getElementById('date_lettre_affichage').innerHTML = yyyy+"-"+mm+"-"+dd;
                        document.getElementById('range_date').innerText = document.getElementById('date_debut').value+" au "+document.getElementById('date_fin').value;
                        var frame = document.getElementById('imprimable');
                        var data = frame.innerHTML;
                        var win = window.open('', '', 'height=500,width=900');
                        win.document.write('<style>@page{size: A4 }</style><html><head><title></title>');
                        win.document.write('</head><body >');
                        win.document.write(data);
                        win.document.write('</body></html>');
                        win.print();
                        win.close();
                        
                        return true;
                    }else{
                        return false
                    }
            }}
        });
        var btn_imp = document.getElementsByClassName('btn_imp');
        for(var i=0;i<btn_imp.length;i++){
            (function(index) {
                btn_imp[index].addEventListener("click", function() {
                    var frame = document.getElementById('imprimable_divers_'+btn_imp[index].getAttribute('id'));
                    var data = frame.innerHTML;
                    var win = window.open('', '', 'height=500,width=900');
                    win.document.write('<style>@page{size: A4 }</style><html><head><title></title>');
                    win.document.write('</head><body >');
                    win.document.write(data);
                    win.document.write('</body></html>');
                    win.print();
                    win.close();
                })
            })(i);
        }
        var btn_supp = document.getElementsByClassName('btn_supp');
        for(var i=0;i<btn_imp.length;i++){
            (function(index) {
                btn_supp[index].addEventListener("click", function(e) {
                    e.preventDefault();
                    val = this.getAttribute('id');
                    var id_form_delete = val.split('_').pop();
                    if(confirm('Voulez Vous Vraiment Effectuer Cette Action ?')){
                        document.getElementById('delete_form_'+id_form_delete).submit();
                    }else{
                        return false;
                    }
                })
            })(i);
        }
    }
</script>
@endsection