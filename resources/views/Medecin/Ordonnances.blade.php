@extends('Medecin.layouts.masterDetails')
@section('activation3')
class="active"
@endsection
@section('details_content')
<div class="container-fluid">
                    <!--Contenu principale de la page-->
                    <div class="container center-div">
                        <div class="conteiner mt-4">
                            <form action="" method="post">
                                
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
                                <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                                <div class="form-group mt-1">
                                    <div class="row">
                                        <div class="col-sm-1 mt-1">
                                            <button type="submit" class="btn btn-success">Ajouter</button>
                                        </div>
                                        <div class="col-sm-1 ml-3 mt-1">
                                            <button type="reset" class="btn btn-dark">Vider</button>
                                        </div>
                                        <div class="col-sm-1 mt-1"></div>
                                        <div class="col-sm-8 mt-1">
                                            <button class="btn btn-dark" id="btn_imprimer">Imprimer</button>
                                        </div>
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
                                    <td width="25%" colspan="2"><center>Clinique  {{App\Clinique::find(1)->nom}}<br> {{date('d-m-y')}}</center></td>
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
                                        <center><h3 style="color: black;margin-top:50px;margin-bottom:50px"><b><u>ORDONNANCE</u></b></h3></center>
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
                <script>
                    window.onload=function(){
                    document.getElementById('ajouter_Ligne').addEventListener('click',function(e){
                        e.preventDefault();
                        var nombre_ligne = parseInt(document.getElementById('nombre_ligne').innerHTML);
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
                    document.getElementById('btn_imprimer').addEventListener('click',function(e){
                        e.preventDefault();
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
                        var win = window.open('', '', 'height=500,width=900');//body{writing-mode: tb-rl;}
                        win.document.write('<style>@page{size: A4 }</style><html><head><title></title>');
                        win.document.write('</head><body >');
                        win.document.write(data);
                        win.document.write('</body></html>');
                        win.print();
                        win.close();
                        return true;
                    });
                
                }
                </script>
@endsection