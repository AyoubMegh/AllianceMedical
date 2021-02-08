@extends('Medecin.layouts.masterDetails')
@section('activation4')
class="active"
@endsection
@section('details_content')
<div class="container-fluid">
    <div class="container center-div">
        <center><h3>Lettre d'orientation</h3></center>
    </div>
    <div class="conteiner mt-4">
        <form action="{{route('medecin.lettreOrientation')}}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="id_med">Nom du Medecin :</label>
                <select name="id_med" id="id_med"  class="form-control" required>
                    <option value="" disabled selected>Choisissez Un Medecin</option>
                        <option value="HorsClinique">Medecin Hors Clinique ?</option>
                    @foreach($medecins as $medecin)
                        <option value="{{$medecin->id_med}}" >DR.{{$medecin->nom}} {{$medecin->prenom}} ({{$medecin->specialite}})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="lettre">Lettre :</label>
                <textarea name="lettre" id="lettre" cols="30" rows="10" class="form-control" required></textarea>
            </div>
            @if($errors->any())
                <div class="alert alert-danger" id="warningSubmit" role="alert">
                <center> <ul>
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
    <hr>
    <div class="container center-div">
        <center><h5><u>Divers Lettre d'orientation</u></h5></center>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>                                        
                            <tr>
                                <th>Nom et Prenom Medecin</th>
                                <th>Date et Heure de la Lettre</th>
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
                                                    <center><h3 style="color: black;margin-top:50px;margin-bottom:50px"><b><u>Lettre d'orientation</u></b></h3></center>
                                                </td>
                                            </tr>
                                            <tr width="100%">
                                                <td colspan="4" width="100%">
                                                    <center class="mt-5" style="margin-top: 20%">
                                                       <h4>
                                                            <div name="content_lettre" id="content_lettre">
                                                                {{$lettre->contenu}}
                                                            </div>
                                                       </h4>
                                                    </center>
                                                </td>
                                            </tr>
                                            <tr width="100%">
                                                <td colspan="4" width="100%">
                                                    <div class="mt-2" style="margin-top: 30%">
                                                       <b>Antecédent : @if(App\Patient::find($patient->id_pat)->antecedents != "") {{App\Patient::find($patient->id_pat)->antecedents}} @else Aucun @endif </b>
                                                    </div>
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
                    <center><h3 style="color: black;margin-top:50px;margin-bottom:50px"><b><u>Lettre d'orientation</u></b></h3></center>
                </td>
            </tr>
            <tr width="100%">
                <td colspan="4" width="100%">
                    <center class="mt-5" style="margin-top: 20%">
                       <h4>
                            <div name="content_lettre_imp" id="content_lettre_imp">
                                
                            </div>
                       </h4>
                    </center>
                </td>
            </tr>
            <tr width="100%">
                <td colspan="4" width="100%">
                    <div class="mt-2" style="margin-top: 30%">
                       <b>Antecédent : @if(App\Patient::find($patient->id_pat)->antecedents != "") {{App\Patient::find($patient->id_pat)->antecedents}} @else Aucun @endif </b>
                    </div>
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
       /* document.getElementById('btn_imprimer').onclick = function(e){
            e.preventDefault();
            document.getElementById('content_lettre_imp').innerText = document.getElementById('lettre').value;
            if(document.getElementById('lettre').value==""){
                return false;
            }else{
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
        }*/
        document.getElementById('id_med').addEventListener('change',function(){
            if(document.getElementById('id_med').value=="HorsClinique"){
                var input = document.createElement("input");
                input.setAttribute("class","form-control");
                input.setAttribute("name","id_med");
                input.setAttribute("id","id_med");
                input.setAttribute("placeholder","Nom et Prenom Du Medecin");
                input.required = true;
                var button_options = document.createElement("button");
                button_options.setAttribute("class","btn btn-primary mt-2");
                button_options.setAttribute("id","btn_opt");
                button_options.innerText="Un Medecin Locale ?";
                document.getElementById('id_med').replaceWith(input);
                document.getElementById('id_med').insertAdjacentElement('afterend',button_options);
            }
        });
        document.addEventListener('click',function(e){
            if(e.target && e.target.id == 'btn_opt'){
                    e.preventDefault();
                    $('#id_med').replaceWith("<select class=\"form-control\" name=\"id_med\" id=\"id_med\" required>"
                                                                    +"<option value=\"\" disabled selected >Choisissez un Medecin</option>"
                                                                    +"<option value=\"HorsClinique\">Medecin Hors Clinique ?</option>"
                                                                    +"@foreach($medecins as $medecin)"
                                                                    +"<option value=\"{{$medecin->id_med}}\">Dr. {{$medecin->nom}} {{$medecin->nom}}</option>"
                                                                    +"@endforeach"
                                                                    +"</select>");
                    $('#id_med').on('change',function(){
                        if(document.getElementById('id_med').value=="HorsClinique"){
                        var input = document.createElement("input");
                        input.setAttribute("class","form-control");
                        input.setAttribute("name","id_med");
                        input.setAttribute("id","id_med");
                        input.setAttribute("placeholder","Nom et Prenom Du Medecin");
                        input.required = true;
                        var button_options = document.createElement("button");
                        button_options.setAttribute("class","btn btn-primary mt-2");
                        button_options.setAttribute("id","btn_opt");
                        button_options.innerText="Un Medecin Locale ?";
                        document.getElementById('id_med').replaceWith(input);
                        document.getElementById('id_med').insertAdjacentElement('afterend',button_options);
                    }
                });
                document.getElementById('btn_opt').remove();
            }
        });
        document.getElementById('btn_submit').addEventListener('click',function(e){
            if(!document.getElementById('lettre').value == "" && !!document.getElementById('id_med').value){
                if(confirm("Voulez-vous d'abord Imprimer la lettre ?")){
                    document.getElementById('content_lettre_imp').innerText = document.getElementById('lettre').value;
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