@extends('Medecin.layouts.masterDetails')
@section('activation2')
class="active"
@endsection
@section('details_content')

         <div class="container mt-1">
                            <div class="row">
                                <div class="col-lg-12"> <label for="Images"><u>Images :</u></label></div>
                                <div class="col-lg-12" >
                                    <!--Ne pas Oublier de les ordonner Last First -->
                                    @if(count($images)!=0)
                                            <center>
                                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                <ol class="carousel-indicators">
                                                   @for ($i = 0; $i < count($images); $i++)
                                                        @if ($i==0)
                                                            <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" class="active" ></li>
                                                        @else
                                                            <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" ></li>
                                                        @endif
                                                   @endfor
                                                </ol>
                                                <div class="carousel-inner">
                                                    @for($i = 0; $i < count($images); $i++)
                                                        @if ($i==0)
                                                            <div class="carousel-item active" >
                                                                <a target="_blank" href="{{url('storage/images/imageries_patient/'.$patient->num_ss.'/'.$images->get($i)->nom.'.'.$images->get($i)->format)}}">
                                                                    <img src="{{url('storage/images/imageries_patient/'.$patient->num_ss.'/'.$images->get($i)->nom.'.'.$images->get($i)->format)}}" width="600" height="400">
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="carousel-item" >
                                                                <a target="_blank" href="{{url('storage/images/imageries_patient/'.$patient->num_ss.'/'.$images->get($i)->nom.'.'.$images->get($i)->format)}}">
                                                                    <img src="{{url('storage/images/imageries_patient/'.$patient->num_ss.'/'.$images->get($i)->nom.'.'.$images->get($i)->format)}}" width="600" height="400">
                                                                </a>
                                                                <div class="carousel-caption d-none d-md-block">
                                                                    <h5 style="color: black;">{{$images->get($i)->nom}}</h5>
                                                                    <h5 style="color: black;">{{$images->get($i)->date_img}}</h5>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <a class="carousel-control-prev" style="filter: invert(100%);" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" style="filter: invert(100%);" href="#carouselExampleIndicators" role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                            </center>
                                    @else
                                        <center><h5>Aucune Image Du Patient</h5></center>
                                    @endif
                                </div>
                            </div>       
           </div>
           <hr>
           <div class="container center-div">
               <center><h5><u>Ajouter Des Imageries</u></h5></center>
               <form action="{{Route('image.medecin.ajouterImages')}}" method="POST" enctype="multipart/form-data" >
                    {{csrf_field() }}
                   <div class="form-group mt-4">
                        <label for="Ajouter">Ajouter Des Images :</label>
                        <input type="hidden" name="id_pat" value="{{$patient->id_pat}}">
                        <div class="img-show">
                            <input type="file" class="form-control" name="imageries[]" id="imageries" accept="image/*" required multiple>
                            <label for="selectedfiles" id="selectedfiles"></label>
                        </div>
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
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-success">Ajouter</button>
                    </div>
               </form>
           </div>
          
@endsection