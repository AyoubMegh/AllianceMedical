@extends('Medecin.layouts.master')
@section('content')
<div class="container-fluid">
    <center><h1>Statistiques</h1></center>
    <div class="row">

        <div class="col-xl-7 col-lg-7">

            <!-- Area Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Nombre Rendez-vous et Prescription Année {{date("Y")}}</h6>
                </div>
                <div class="card-body">
                    <div  class="wrapper" style="height: 300px !important">
                        <canvas id="histogramme"></canvas>
                    </div>
            
                </div>
            </div>

        </div>

        <!-- Donut Chart -->
        <div class="col-xl-5 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Nombre de RDV Par Medecin du Mois Précédent</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="wrapper" style="height: 300px !important">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
@section('scripts')
<script>
     document.addEventListener('DOMContentLoaded', function() {  
       var ctx_histo = document.getElementById('histogramme');
       var ctx_pie = document.getElementById('pieChart');
       var graph_histo = new Chart(ctx_histo,{
        type:'bar',
        data:{
            labels:['JANVIER','FEVRIER','MARS','AVRIL','MAI','JUIN','JUILLET','AOUT','SEPTEMBRE','OCTOBRE','NOVEMBRE','DECEMBRE'],
            datasets:[
                {
                    label: "Nombre de Rendez-vous",
                    backgroundColor : 'rgb(129,183,83)',
                    data: {{json_encode($stats_rdv)}}
                },
                {
                    label: "Nombre de Prescriptions",
                    backgroundColor : 'rgb(39,130,191)',
                    data: {{json_encode($stats_pres)}}
                },
            ],
        }
       });
       var gestionCouleurs = [];
       for($i=0;$i<{{count($nom_meds)}};$i++){
            r = Math.floor(Math.random() * 200);
            g = Math.floor(Math.random() * 200);
            b = Math.floor(Math.random() * 200);
            color = 'rgb(' + r + ', ' + g + ', ' + b + ')';
            gestionCouleurs.push(color);
       }
       var graph_pie = new Chart(ctx_pie,{
            type:'pie',
            data:{
                datasets:[{
                    data: {{json_encode($nombre_meds)}},
                    backgroundColor : gestionCouleurs,
                }],
                labels: {!!json_encode($nom_meds)!!}
            },
            options:{
                legend:{
                    position:'bottom',
                    align:'start',
                }
            }
       });
    });
</script>
@endsection