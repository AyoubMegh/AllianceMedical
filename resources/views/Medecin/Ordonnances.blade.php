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
                                <div class="form-group">
                                    <label for="date_pres">Date de Prescription :</label>
                                    <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <input type="date" name="date_pres" id="date_pres" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <button class="btn btn-secondary" id="today_date">date d'aujourd'hui</button>
                                            </div>
                                    </div>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
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
                                        <div class="col-sm-1 mt-1">
                                            <button type="reset" class="btn btn-dark">Vider</button>
                                        </div>
                                        <div class="col-sm-1 mt-1"></div>
                                        <div class="col-sm-9 mt-1">
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
                        
                        <div id="imprimable" class="d-none">
                            <table  width="100%">
                                <tr>
                                    <td><center>ALLIANCE MEDICAL <br> <i class="fa fa-user-md"></i> </center></td>
                                    <td><center>NOM PRENOM MEDECIN <br> Spécialite </center></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                   
                </div>
@endsection