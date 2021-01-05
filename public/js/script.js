window.onload=function(){
    document.getElementById('today_date').addEventListener('click',function(e){
        e.preventDefault();
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        document.getElementById('date_pres').value=today;
    });
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
        var frame = document.getElementById('imprimable');
        var data = frame.innerHTML;
        var win = window.open('', '', 'height=500,width=900');//body{writing-mode: tb-rl;}
        win.document.write('<style>@page{size: A5 }</style><html><head><title></title>');
        win.document.write('</head><body >');
        win.document.write(data);
        win.document.write('</body></html>');
        win.print();
        win.close();
        return true;
    });
}