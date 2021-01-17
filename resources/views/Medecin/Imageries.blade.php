@extends('Medecin.layouts.masterDetails')
@section('activation2')
class="active"
@endsection
@section('details_content')

         <div class="container mt-1">
                            <div class="row">
                                <div class="col-lg-12"> <label for="Images"><u>Images :</u></label></div>
                                <div class="col-lg-12">
                                    <!--Ne pas Oublier de les ordonner Last First -->
                                    <div class="gallery">
                                        <a target="_blank" href="../img/undraw_profile_1.svg">
                                            <img src="../img/undraw_profile_1.svg" width="600" height="400">
                                        </a>
                                        <div class="desc">Details de l'image ICI</div>
                                    </div>
                                    <div class="gallery">
                                        <a target="_blank" href="../img/undraw_profile_2.svg">
                                            <img src="../img/undraw_profile_2.svg" width="600" height="400">
                                        </a>
                                        <div class="desc">Details de l'image ICI</div>
                                    </div>
                                    <div class="gallery">
                                        <a target="_blank" href="../img/undraw_profile_3.svg">
                                            <img src="../img/undraw_profile_3.svg" width="600" height="400">
                                        </a>
                                        <div class="desc">Details de l'image ICI</div>
                                    </div>
                                </div>
                            </div>
                            
         </div>
                
                    <div class="row">
                      <div class="col-sm-1 mt-5">
                         <button type="submit" class="btn btn-success">Ajouter</button>
                      </div>
                    </div>
@endsection