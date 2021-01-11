@extends('Secretaire.layouts.master')
@section('content')
<div class="container center-div">
                        <center>
                            <h1>Prendre Rendez-vous</h1>
                        </center>
                        <form action="#" method="post">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="nom">Nom & Prénom :</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="nom_pat" id="nom" required placeholder="Nom">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="prenom_pat" id="prenom" required placeholder="Prénom">
                                    </div>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="date_de_naissance">Date de Naissance :</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="form-control" type="date" name="date_de_naissance" id="date_de_naissance" required>
                                    </div>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="date_de_naissance">N° Sécurité Sociale :</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="form-control" type="text" name="num_ss" id="num_ss" required placeholder="ex: 99 333 000 22">
                                    </div>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="num_tel">N° Téléphone :</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="tel" class="form-control" name="num_tel" id="num_tel" required placeholder="ex: 0576009999">
                                    </div>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="email">Adresse Email :</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="email" class="form-control" name="email" id="email" required placeholder="ex: exemple@mail.com">
                                    </div>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

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