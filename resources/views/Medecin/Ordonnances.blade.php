@extends('Medecin.layouts.masterDetails')
@section('activation3')
class="active"
@endsection
@section('details_content')
<div class="container-fluid">
                    <!--Contenu principale de la page-->
                    <div class="container center-div">
                        <div class="conteiner mt-4">
                            <form action="{{route('medecin.ordonnances')}}" method="post" id="form_ord">
                                {{ csrf_field() }}
                                <div class="form-group" id="lignes_prescription">
                                    <label for="ligne_pres">Ligne Prescription: (<label for="nombre_ligne" id="nombre_ligne">1</label> Ligne)</label>
                                    <div class="row">
                                        <div class="col-md-3 mb-1">
                                            <input class="form-control" placeholder="Nom Medicamant" type="text" name="medicament_1" id="medicament_1" required>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <input class="form-control" placeholder="Dose" type="text" name="dose_1" id="dose_1" required>
                                        </div>
                                        <div class="col-md-2 mb-1">
                                            <input class="form-control" placeholder="Moment" type="text" name="moment_1" id="moment_1" required>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <input class="form-control" placeholder="Durée traitment" type="text" name="duree_1" id="duree_1" required>
                                        </div>
                                        <div class="col-md-1 mb-1" id="button_div">
                                            <button class="btn btn-secondary" id="ajouter_Ligne">+</button>
                                        </div>
                                    </div>
                                    <hr style="width:50%">
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
                                <input type="hidden" name="nbr_ligne" id="nbr_ligne" value="1">
                                <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                <div class="form-group mt-1">
                                    <div class="row">
                                        <div class="col-sm-2 mt-1">
                                            <button type="submit" id="btn_submit" class="btn btn-success">Ajouter</button>
                                        </div>
                                        <div class="col-sm-4 ml-3 mt-1">
                                            <button type="reset" class="btn btn-dark">Vider</button>
                                        </div>
                                        <!--<div class="col-sm-1 mt-1"></div>
                                        <div class="col-sm-8 mt-1">
                                            <button class="btn btn-dark" id="btn_imprimer">Imprimer</button>
                                        </div>-->
                                    </div>
                                    
                                </div>
                                <div class="form-group">
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </form>
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
                                    <tr>
                                        <td width="50%" colspan="2">
                                            Nom et Prenom : {{App\Patient::find($patient->id_pat)->nom}} {{App\Patient::find($patient->id_pat)->prenom}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" colspan="2">
                                            Age :   <?php 
                                                        $date1 = strtotime(date('y-m-d'));
                                                        $date = strtotime(App\Patient::find($patient->id_pat)->date_naissance);
                                                        $age = floor(($date1-$date)/(365*60*60*24));
                                                        echo $age;
                                                    ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" width="100%">
                                            <hr style="border: 1px solid black;">
                                        </td>
                                    </tr>
                                    <tr id="pour_lignes" width="100%">
                                        <td colspan="4" width="100%" >
                                            <div style="margin-bottom: 30% ">
                                                <center><h3 style="color: black;margin-top:50px;margin-bottom:50px"><b><u>ORDONNANCE</u></b></h3></center>
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
                    </div>
                </div>
                <hr>
                <hr>
                <div class="container center-div mt-3">
                    <center><h5><u>Divers Ordonnances</u></h5></center>
                    <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>                                        
                                            <tr>
                                                <th>Nom et Prenom Medecin</th>
                                                <th>Date et Heure Ordonnance</th>
                                                <th>Details Prescription</th>
                                                <th>Imprimer</th>
                                                <th>Supprimer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($prescriptions as $pres)
                                            <tr>
                                                <td>DR. {{App\Medecin::find($pres->id_med)->nom}} {{App\Medecin::find($pres->id_med)->prenom}}</td>
                                                <td> {{$pres->created_at}} </td>
                                                <td>
                                                    <center><button class="btn btn_pres_details" id="{{$pres->id_pres}}" data-toggle="modal" data-target="#modal_pres_{{$pres->id_pres}}"><i class="far fa-file-alt"></i></button></center>
                                                    <div class="modal fade" id="modal_pres_{{$pres->id_pres}}" tabindex="-1" role="dialog" aria-labelledby="modal_pres_{{$pres->id_pres}}" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Lignes Prescriptions</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @foreach($ligne_pres->where('id_pres',$pres->id_pres) as $ligne)
                                                                    <form action="{{route('medecin.MAJLignePres')}}" method="post">
                                                                    <div class="row">
                                                                            {{ csrf_field() }}
                                                                            {{method_field('PUT')}}
                                                                            <div class="col-md-3 mb-1">
                                                                                <input class="form-control" value="{{$ligne->medicament}}" placeholder="Nom Medicamant" type="text" name="medicament" id="medicament" required>
                                                                            </div>
                                                                            <div class="col-md-2 mb-1">
                                                                                <input class="form-control" value="{{$ligne->dose}}" placeholder="Dose" type="text" name="dose" id="dose" required>
                                                                            </div>
                                                                            <div class="col-md-2 mb-1">
                                                                                <input class="form-control" value="{{$ligne->moment}}" placeholder="Moment" type="text" name="moment" id="moment" required>
                                                                            </div>
                                                                            <div class="col-md-3 mb-1">
                                                                                <input class="form-control" value="{{$ligne->duree}}" placeholder="Durée traitment" type="text" name="duree" id="duree" required>
                                                                            </div>
                                                                            <input type="hidden" name="id_ligne_pres" id="id_ligne_pres" value="{{$ligne->id_ligne_pres }}">
                                                                            <div class="col-md-1 mb-1" id="button_div">
                                                                                <button type="submit" class="btn btn-secondary" id="modifer_Ligne"><i class="fas fa-edit"></i></button>
                                                                            </div>
                                                                        </form>
                                                                        <form action="{{route('medecin.SuppLignePres')}}" method="post">
                                                                            {{ csrf_field() }}
                                                                            {{method_field('DELETE')}}
                                                                            <div class="col-md-1 mb-1" id="button_div">
                                                                            
                                                                                    <input type="hidden" name="id_ligne_pres" id="id_ligne_pres" value="{{$ligne->id_ligne_pres }}">
                                                                                    <button class="btn btn-secondary" id="supprimer_Ligne"><i class="fas fa-trash-alt"></i></button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="modal-footer">
                                                                    <button type="button" class="btn btn-primary add"  data-toggle="modal" id="{{$pres->id_pres}}" data-target="AjouterLigne_{{$pres->id_pres}}">Ajouter Ligne ?</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="AjouterLigne_{{$pres->id_pres}}" tabindex="-1" role="dialog" aria-labelledby="AjouterLigne_{{$pres->id_pres}}" aria-hidden="true">
                                                        <div class="modal-dialog  modal-lg" role="document">
                                                          <div class="modal-content">
                                                            <div class="modal-header">
                                                              <h5 class="modal-title" id="exampleModalLabel">Ajouter Une Ligne</h5>
                                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                              </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{route('medecin.ajouterLignePres')}}" method="post">
                                                                    {{csrf_field()}}
                                                                <div class="row">
                                                                    <div class="col-md-3 mb-1">
                                                                        <input class="form-control" placeholder="Nom Medicamant" type="text" name="medicament" id="medicament" required>
                                                                    </div>
                                                                    <div class="col-md-3 mb-1">
                                                                        <input class="form-control"  placeholder="Dose" type="text" name="dose" id="dose" required>
                                                                    </div>
                                                                    <div class="col-md-3 mb-1">
                                                                        <input class="form-control"  placeholder="Moment" type="text" name="moment" id="moment" required>
                                                                    </div>
                                                                    <div class="col-md-3 mb-1">
                                                                        <input class="form-control"  placeholder="Durée traitment" type="text" name="duree" id="duree" required>
                                                                    </div>
                                                                    <input type="hidden" name="id_pres" id="id_pres" value="{{$pres->id_pres}}">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Ajouter</button>
                                                            </form>
                                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                </td>
                                                <td><center><button class="btn btn_imp" id="{{$pres->id_pres}}"><i class="fas fa-print"></i></button></center>

                                                    <div id="imprimable_divers_{{$pres->id_pres}}" class="row d-none" style="position:relative;" > <!--class="d-none"-->      
                                                        <div class="col-12">
                                                            <table  width="100%" >
                                                                <tr >
                                                                    <td width="25%" colspan="2"><center>Clinique  {{App\Clinique::find(1)->nom}}<br> {{$pres->created_at}}</center></td>
                                                                    <td width="25%" colspan="2"><center>{{App\Medecin::find($pres->id_med)->nom}} {{App\Medecin::find($pres->id_med)->prenom}} <br> {{App\Medecin::find($pres->id_med)->specialite}} </center></td>
                                                                </tr>
                                                                <tr >
                                                                    <td colspan="4" width="100%">
                                                                        <hr style="border: 1px solid black;">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%" colspan="2">
                                                                        Nom et Prenom : {{App\Patient::find($patient->id_pat)->nom}} {{App\Patient::find($patient->id_pat)->prenom}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%" colspan="2">
                                                                        Age :   <?php 
                                                                                    $date1 = strtotime($pres->created_at);
                                                                                    $date = strtotime(App\Patient::find($patient->id_pat)->date_naissance);
                                                                                    $age = floor(($date1-$date)/(365*60*60*24));
                                                                                    echo $age;
                                                                                ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4" width="100%">
                                                                        <hr style="border: 1px solid black;">
                                                                    </td>
                                                                </tr>
                                                                <tr id="pour_lignes" width="100%">
                                                                    <td colspan="4" width="100%" >
                                                                        <div style="margin-bottom: 30% ">
                                                                            <center><h3 style="color: black;margin-top:50px;margin-bottom:50px"><b><u>ORDONNANCE</u></b></h3></center>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @foreach(App\Ligneprescription::all()->where('id_pres',$pres->id_pres) as $prs)
                                                                <tr width="100%">
                                                                    <td>{{$prs->medicament}}</td>
                                                                    <td>{{$prs->dose}}</td>
                                                                    <td>{{$prs->moment}}</td>
                                                                    <td>{{$prs->duree}}</td>
                                                                </tr>
                                                                @endforeach
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
                                                <td>
                                                    <form action="{{route('medecin.supprimerOrdonnance')}}" id="delete_form_{{$prs->id_pres}}" method="post">
                                                        {{ csrf_field() }}
                                                        {{method_field('DELETE')}}
                                                        <input type="hidden" name="id_pres" value="{{$prs->id_pres}}">
                                                        <center><button type="submit" class="btn btn_supp" id="annuler_{{$prs->id_pres}}"><i class="fas fa-trash-alt"></i></button></center>
                                                    </form>
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
                    document.getElementById('ajouter_Ligne').addEventListener('click',function(e){
                        e.preventDefault();
                        var nombre_ligne = parseInt(document.getElementById('nombre_ligne').innerHTML);
                        document.getElementById('nbr_ligne').value = nombre_ligne;
                        if(  document.getElementById('medicament_'+nombre_ligne).value.localeCompare("")!==0 &&
                            document.getElementById('dose_'+nombre_ligne).value.localeCompare("")!==0 &&
                            document.getElementById('moment_'+nombre_ligne).value.localeCompare("")!==0 &&
                            document.getElementById('duree_'+nombre_ligne).value.localeCompare("")!==0 ){

                            nombre_ligne += 1;
                            document.getElementById('nombre_ligne').innerHTML=nombre_ligne;
                            var mainParentDiv = document.getElementById('lignes_prescription');
                            var parentDiv = document.createElement("div");
                            parentDiv.setAttribute("class","row");
                    
                            var div_1 = document.createElement("div");
                            div_1.setAttribute("class","col-md-3 mb-1");
                            var div_1_child = document.createElement("INPUT");
                            div_1_child.setAttribute("type","text");
                            div_1_child.setAttribute("placeholder","Nom Medicamant");
                            div_1_child.setAttribute("class","form-control");
                            div_1_child.setAttribute("name","medicament_"+nombre_ligne);
                            div_1_child.setAttribute("id","medicament_"+nombre_ligne);
                            div_1_child.setAttribute("required", "");
                            div_1.appendChild(div_1_child);

                            var div_2 = document.createElement("div");
                            div_2.setAttribute("class","col-md-3 mb-1");
                            var div_2_child = document.createElement("INPUT");
                            div_2_child.setAttribute("type","text");
                            div_2_child.setAttribute("placeholder","Dose");
                            div_2_child.setAttribute("class","form-control");
                            div_2_child.setAttribute("name","dose_"+nombre_ligne);
                            div_2_child.setAttribute("id","dose_"+nombre_ligne);
                            div_2_child.setAttribute("required", "");
                            div_2.appendChild(div_2_child);

                            var div_3 = document.createElement("div");
                            div_3.setAttribute("class","col-md-2 mb-1");
                            var div_3_child = document.createElement("INPUT");
                            div_3_child.setAttribute("type","text");
                            div_3_child.setAttribute("placeholder","Moment");
                            div_3_child.setAttribute("class","form-control");
                            div_3_child.setAttribute("name","moment_"+nombre_ligne);
                            div_3_child.setAttribute("id","moment_"+nombre_ligne);
                            div_3_child.setAttribute("required", "");
                            div_3.appendChild(div_3_child);

                            var div_4 = document.createElement("div");
                            div_4.setAttribute("class","col-md-3 mb-1");
                            var div_4_child = document.createElement("INPUT");
                            div_4_child.setAttribute("type","text");
                            div_4_child.setAttribute("placeholder","Durée traitment");
                            div_4_child.setAttribute("class","form-control");
                            div_4_child.setAttribute("name","duree_"+nombre_ligne);
                            div_4_child.setAttribute("id","duree_"+nombre_ligne);
                            div_4_child.setAttribute("required", "");
                            div_4.appendChild(div_4_child);

                            var div_5 = document.createElement("div");
                            div_5.setAttribute("class","col-md-1 mb-1");
                            var div_5_child = document.getElementById('button_div');
                            div_5.appendChild(div_5_child);

                            var div_6 = document.createElement("hr");
                            div_6.setAttribute("style","width:50%");
                        
                            parentDiv.appendChild(div_1);
                            parentDiv.appendChild(div_2);
                            parentDiv.appendChild(div_3);
                            parentDiv.appendChild(div_4);
                            parentDiv.appendChild(div_5);
                            parentDiv.appendChild(div_6);
                            mainParentDiv.appendChild(parentDiv);
                        }else{
                            alert("Tout les Champs Doivent etre Remplit pour passer a la ligne suivante !");
                        }

                    
                    });
                    // impression
                   /* document.getElementById('btn_imprimer').addEventListener('click',function(e){
                        e.preventDefault();
                        var nombre_ligne = parseInt(document.getElementById('nombre_ligne').innerHTML);
                        console.log("Nombre Ligne : "+nombre_ligne);
                        for(let i=nombre_ligne;i>=1;i--){
                            var tr = document.createElement('tr');
                            var td_1 = document.createElement('td');
                            td_1.innerHTML = document.getElementById('medicament_'+i).value;
                            td_1.setAttribute("width","15%");
                            td_1.setAttribute("style","margin-bottom:25px;");
                            var  td_2 = document.createElement('td');
                            td_2.innerHTML = document.getElementById('dose_'+i).value;
                            td_2.setAttribute("width","15%");
                            td_2.setAttribute("style","margin-bottom:25px;");
                            var td_3 = document.createElement('td');
                            td_3.innerHTML = document.getElementById('moment_'+i).value;
                            td_3.setAttribute("width","15%");
                            td_3.setAttribute("style","margin-bottom:25px;");
                            var td_4 = document.createElement('td');
                            td_4.innerHTML = document.getElementById('duree_'+i).value;
                            td_4.setAttribute("width","15%");
                            td_4.setAttribute("style","margin-bottom:25px;");
                            tr.appendChild(td_1);
                            tr.appendChild(td_2);
                            tr.appendChild(td_3);
                            tr.appendChild(td_4);
                            console.log(tr);
                            var list = document.getElementById("pour_lignes");
                            list.insertAdjacentElement("afterend",tr)
                        }

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
                    document.getElementById('btn_submit').addEventListener('click',function(e){
                        if( !(document.getElementById('medicament_1').value=="" ||
                            document.getElementById('dose_1').value==""||
                            document.getElementById('moment_1').value==""||
                            document.getElementById('duree_1').value=="")){
                                if(confirm("Voulez-vous d'abord Imprimer l'ordonnance ?")){
                                        /*Logique d'affichage--------------------*/
                                        var nombre_ligne = parseInt(document.getElementById('nombre_ligne').innerHTML);
                                        console.log("Nombre Ligne : "+nombre_ligne);
                                        for(let i=nombre_ligne;i>=1;i--){
                                        var tr = document.createElement('tr');
                                        var td_1 = document.createElement('td');
                                        td_1.innerHTML = document.getElementById('medicament_'+i).value;
                                        td_1.setAttribute("width","15%");
                                        td_1.setAttribute("style","margin-bottom:25px;");
                                        var  td_2 = document.createElement('td');
                                        td_2.innerHTML = document.getElementById('dose_'+i).value;
                                        td_2.setAttribute("width","15%");
                                        td_2.setAttribute("style","margin-bottom:25px;");
                                        var td_3 = document.createElement('td');
                                        td_3.innerHTML = document.getElementById('moment_'+i).value;
                                        td_3.setAttribute("width","15%");
                                        td_3.setAttribute("style","margin-bottom:25px;");
                                        var td_4 = document.createElement('td');
                                        td_4.innerHTML = document.getElementById('duree_'+i).value;
                                        td_4.setAttribute("width","15%");
                                        td_4.setAttribute("style","margin-bottom:25px;");
                                        tr.appendChild(td_1);
                                        tr.appendChild(td_2);
                                        tr.appendChild(td_3);
                                        tr.appendChild(td_4);
                                        console.log(tr);
                                        var list = document.getElementById("pour_lignes");
                                        list.insertAdjacentElement("afterend",tr)
                                    }
                                    /*---------------------------------------*/
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
                            return false;
                        }
                    });
                }   
                var userSelection = document.getElementsByClassName('add');
                for(var i = 0; i < userSelection.length; i++) {
                (function(index) {
                    userSelection[index].addEventListener("click", function(event) {
                        var id = event.target.id;
                        $('#modal_pres_'+id).modal('hide').on('hidden.bs.modal', function (e) {
                                    $('#AjouterLigne_'+id).modal('show');
                                    $(this).off('hidden.bs.modal'); // Remove the 'on' event binding
                         });
                    })
                })(i);
                }
                var btn_supp = document.getElementsByClassName('btn_supp');
                for(var i=0;i<btn_supp.length;i++){
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
                </script>
@endsection