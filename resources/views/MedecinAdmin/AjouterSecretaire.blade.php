@extends('MedecinAdmin.layouts.master')
@section('content')
<div class="container center-div">
    <center><h1>Ajouter Secretaire</h1></center>
    <form action="#" method="post">
        <div class="form-group mt-2">
            <label for="NomEtPrenom">Nom et Prenom :</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="nom" id="nom" class="form-control" placeholder="Nom" required>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Prenom" required>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="login">Login :</label>
            <input type="text" name="login" id="login" class="form-control" placeholder="Login" required>
            <div class="valid-feedback"></div>
            <div class="invalid-feedback"></div>
        </div>
        <div class="form-group">
            <label for="password">Password :</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Mot de Passe" required>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="Confirmation du Mot de Passe" required>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Votre Adresse Email" required>
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