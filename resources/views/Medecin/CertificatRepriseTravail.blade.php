@extends('Medecin.layouts.masterDetails')
@section('activation4')
class="active"
@endsection
@section('details_content')
<div class="container-fluid">
    <div class="container center-div">
        <center><h3>Certificat de reprise</h3></center>
        <div class="conteiner mt-4">
            <form action="{{route('medecin.certificatRepriseTravail')}}" method="post">
                {{ csrf_field() }}
                <div class="form-group" id="form_certificat">
                    <label for="date_cert">Date Du Certificat:</label>
                    <div class="row" >
                        <div class="col-md-10">
                            <input type="date" class="form-control" name="date_lettre" id="date_lettre" required>
                        </div>
                        <div class="col-md-2">
                            <input type="button" id="btn_date" class="btn btn-secondary" value="Date Aujourd'hui"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_cert">Date de Reprise :</label>
                    <input type="date" class="form-control" name="date_rep" id="date_rep" required>
                </div>
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
    </div>
    <div class="container center-div">
        <center><h5><u>Certificat de reprise</u></h5></center>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>                                        
                            <tr>
                                <th>Nom et Prenom Medecin</th>
                                <th>Date et Heure du Certificat</th>
                                <th>Imprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lettres as $lettre)
                            <tr>
                                <td>DR. {{App\Medecin::find($lettre->id_med)->nom}} {{App\Medecin::find($lettre->id_med)->prenom}}</td>
                                <td>{{$lettre->created_at}}</td>
                                <td>
                                    <center><button class="btn btn_imp" id="{{$lettre->id_lettre}}"><i class="fas fa-print"></i></button></center>
                                    <div id="imprimable_divers_{{$lettre->id_lettre}}" class="row d-none" style="position:relative;" > <!--class="d-none"-->      
                                        <div class="col-12">
                                            <table  width="100%" >
                                                <tr >
                                                    <td width="25%" colspan="2"><center>Clinique  {{App\Clinique::find(1)->nom}}<br> {{$lettre->created_at}}</center></td>
                                                    <td width="25%" colspan="2"><center>{{Auth::user()->nom}} {{Auth::user()->prenom}} <br> {{Auth::user()->specialite}} </center></td>
                                                </tr>
                                                <tr >
                                                    <td colspan="4" width="100%">
                                                        <hr style="border: 1px solid black;">
                                                    </td>
                                                </tr>
                                                <tr id="pour_lignes" width="100%">
                                                    <td colspan="4" width="100%" >
                                                        <center><h3 style="color: black;margin-top:50px;margin-bottom:50px"><b><u>Certificat de reprise</u></b></h3></center>
                                                    </td>
                                                </tr>
                                                <tr width="100%">
                                                    <td colspan="4" width="100%">
                                                        <center class="mt-5">
                                                            <div style="margin-top: 30%">
                                                                <h4>
                                                                    {{$lettre->contenu}}
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
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="imprimable" class="row d-none" style="position:relative;" > <!--class="d-none"-->
    <div class="col-12">
        <table  width="100%" >
            <tr >
                <td width="25%" colspan="2"><center>Clinique  {{App\Clinique::find(1)->nom}}<br> {{date('d-m-Y')}}</center></td>
                <td width="25%" colspan="2"><center>{{Auth::user()->nom}} {{Auth::user()->prenom}} <br> {{Auth::user()->specialite}} </center></td>
            </tr>
            <tr >
                <td colspan="4" width="100%">
                    <hr style="border: 1px solid black;">
                </td>
            </tr>
            <tr id="pour_lignes" width="100%">
                <td colspan="4" width="100%" >
                    <center><h3 style="color: black;margin-top:50px;margin-bottom:50px"><b><u>Certificat de reprise</u></b></h3></center>
                </td>
            </tr>
            <tr width="100%">
                <td colspan="4" width="100%" >
                    <center class="mt-5">
                        <div style="margin-top: 30%">
                            <h4>Je certifie <b>{{App\Medecin::find(Auth::user()->id_med)->nom}} {{App\Medecin::find(Auth::user()->id_med)->prenom}}</b> docteur en Medecine
                            avoir examiné le patient <b>{{App\Patient::find($patient->id_pat)->nom}} {{App\Patient::find($patient->id_pat)->prenom}}</b> née
                            {{App\Patient::find($patient->id_pat)->date_naissance}}.<br>
                            lui permet de reprende son travail a compter du 
                             <div id=content_lettre>

                             </div>
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
<script>
    window.onload=function(){
        document.getElementById('btn_date').addEventListener('click',function(e){
            e.preventDefault();
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;
            document.getElementById('date_lettre').value=today;
        });
        document.getElementById('btn_submit').addEventListener('click',function(e){
            if(!document.getElementById('date_lettre').value == "" && ! document.getElementById('date_rep').value == ""){
                if(confirm("Voulez-vous d'abord Imprimer le certificat ?")){
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
                }
            }else{
                return false
            }
        });
        /*document.getElementById('btn_imprimer').addEventListener('click',function(e){
            e.preventDefault();
            if(document.getElementById('date_lettre').value == "" || document.getElementById('date_rep').value == ""){
                return false;
            }else{
                document.getElementById('content_lettre').innerText = " "+document.getElementById('date_rep').value+" Fait le "+document.getElementById('date_lettre').value
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
    }
</script>
@endsection