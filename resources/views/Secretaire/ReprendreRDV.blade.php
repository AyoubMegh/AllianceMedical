@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
                        <center>
                            <h1>Reprendre Rendez-vous</h1>
                        </center>
                        <form action="#" method="post">

                            <div class="form-group mt-4">
                                <label for="date_rdv">Date du Rendez-vous :</label>
                                <input class="form-control" type="date" name="date_rdv" id="date_rdv" required>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="heure_Deb_Fin">Heure Debut et Fin :</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="time" class="form-control" name="heure_deb" id="heure_deb" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="time" class="form-control" name="heure_fin" id="heure_fin" required>
                                    </div>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="motif">Nom du Medecin :</label>
                                <input type="text" class="form-control" name="nom_med" id="nom_med" placeholder="Nom du medecin" required>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="motif">Motif :</label>
                                <input type="text" class="form-control" name="motif" id="motif" placeholder="Ecrivez votre motif ici !" required>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group mt-5">
                                <button type="submit" class="btn btn-success">Ajouter</button>
                                <button type="reset" class="btn btn-dark">Vider</button>
                            </div>
                            <div class="form-group">
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </form>
                    </div>
@endsection