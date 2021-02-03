@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
    <div class="form-group">
        <label for="">Medecin :</label>
        <select name="id_med" id="id_med" class="form-control">
            @foreach($meds as $med)
                <option value="{{$med->id_med}}">DR. {{$med->nom}} {{$med->prenom}}</option>
            @endforeach
        </select>
    </div>
    <div  class="wrapper">
        <div class="card shadow">
            <div class="card-header">
                Rendez-Vous Du Medecin
            </div>
            <div class="card-body">
               <div id="calendrier"></div>
            </div>
        </div>
    </div>
</div>
@foreach ($meds as $med)
<div id="imprimable_{{$med->id_med}}" class="d-none">
    <center><h1>Liste Des Rendez-Vous</h1></center>
    <table width="100%" border="1">
        <thead>
            <tr>
                <td>Nom et Prenom Patient</td>
                <td>N° Sécurité Sociale</td>
                <td>Debut RDV</td>
                <td>Fin RDV</td>
                <td>Rendez-Vous Terminé ?</td>
            </tr>
        </thead>
        <tbody id="table_body">
            @foreach ($rdvs->where('id_med',$med->id_med) as $rdv)
                <tr>
                    <td>{{$rdv->nom}} {{$rdv->prenom}}</td>
                    <td>{{$rdv->num_ss}}</td>
                    <td>{{$rdv->heure_debut}}</td>
                    <td>{{$rdv->heure_fin}}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endforeach
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendrierDiv = document.getElementById('calendrier');
        var cal_settings = {
            customButtons:{
                imprimer:{
                    text:'Imprimer',
                    click:function(info){
                        var id_selected = document.getElementById('id_med').value;
                        var frame = document.getElementById('imprimable_'+id_selected);
                        var data = frame.innerHTML;
                        var win = window.open('', '', 'height=500,width=900');
                        win.document.write('<style>@page{size: A4 }</style><html><head><title></title>');
                        win.document.write('</head><body >');
                        win.document.write(data);
                        win.document.write('</body></html>');
                        win.print();
                        win.close();
                    }
                }
            },
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'imprimer'
            },
            initialView : 'timeGridDay',
            locale:'fr',
            allDaySlot:false,
            selectable:false,
            events:"/secretaire/EventsMed/"+document.getElementById('id_med').value, 
            eventColor:'#200540'
        };
        var calendrier = new FullCalendar.Calendar(calendrierDiv,cal_settings);
        calendrier.render();
        document.getElementById('id_med').addEventListener('change',function(){
            cal_settings.events = "/secretaire/EventsMed/"+document.getElementById('id_med').value;
            var calendrier = new FullCalendar.Calendar(calendrierDiv,cal_settings);
            calendrier.render();
        });
    });
</script>
@endsection