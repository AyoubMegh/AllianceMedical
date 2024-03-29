@extends('Medecin.layouts.master')
@section('content')
<div class="container center-div">
<!-- action="{{route('medecin.ajouterMedecin')}}" -->
    <center><h1>Ajouter Medecin</h1></center>
    <form action="{{route('medecin.ajouterMedecin')}}" method="post">
    {{ csrf_field() }}
        <div class="form-group mt-2">
            <label for="NomEtPrenom">Nom et Prenom :</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" placeholder="Nom" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom') }}" placeholder="Prenom" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="login">Login :</label>
            <input type="text" name="login" id="login" class="form-control" value="{{ old('login') }}" placeholder="Login" required>
        </div>
        <div class="form-group">
            <label for="password">Password :</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Mot de Passe" minlength="8" required>
                </div>
                <div class="col-md-6">
                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="Confirmation du Mot de Passe" minlength="8" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Votre Adresse Email" required>
        </div>
        <div class="form-group">
            <label for="tel">Téléphone :</label>
            <input type="tel" name="tel" id="tel" class="form-control" value="{{ old('tel') }}" placeholder="Votre Numéro de Téléphone" required>
        </div>
        <div class="form-group">
            <label for="specialite">Spécialité :</label>
            <select name="specialite"  id="specialite" class="form-control" required>
                <option value="" disabled selected>choisissez une specialité</option>
                <option value="allergologie">allergologie</option>
                <option value="andrologie">andrologie</option>
                <option value="anesthésiologie">anesthésiologie</option>
                <option value="cardiologie">cardiologie</option>
                <option value="chirurgie cardiaque">chirurgie cardiaque</option>
                <option value="chirurgie plastique">chirurgie plastique</option>
                <option value="chirurgie générale">chirurgie générale</option>
                <option value="chirurgie gynécologique">chirurgie gynécologique</option>
                <option value="chirurgie maxillo-faciale">chirurgie maxillo-faciale</option>
                <option value="chirurgie oculaire">chirurgie oculaire</option>
                <option value="chirurgie orale">chirurgie orale</option>
                <option value="chirurgie pédiatrique">chirurgie pédiatrique</option>
                <option value="chirurgie thoracique">chirurgie thoracique</option>
                <option value="chirurgie vasculaire">chirurgie vasculaire</option>
                <option value="chirurgie viscérale">chirurgie viscérale</option>
                <option value="neurochirurgie">neurochirurgie</option>
                <option value="dermatologie">dermatologie</option>
                <option value="endocrinologie">endocrinologie</option>
                <option value="gastro-entérologie">gastro-entérologie</option>
                <option value="gériatrie">gériatrie</option>
                <option value="gynécologie">gynécologie</option>
                <option value="hématologie">hématologie</option>
                <option value="hépatologie">hépatologie</option>
                <option value="immunologie">immunologie</option>
                <option value="infectiologie">infectiologie</option>
                <option value="médecine aiguë">médecine aiguë</option>
                <option value="médecine du travail">médecine du travail</option>
                <option value="médecine d'urgence">médecine d'urgence</option>
                <option value="médecine générale">médecine générale</option>
                <option value="médecine interne">médecine interne</option>
                <option value="médecine nucléaire">médecine nucléaire</option>
                <option value="médecine palliative">médecine palliative</option>
                <option value="médecine physique et de réadaptation">médecine physique et de réadaptation</option>
                <option value="médecine préventive">médecine préventive</option>
                <option value="néonatologie">néonatologie</option>
                <option value="néphrologie">néphrologie</option>
                <option value="neurologie">neurologie</option>
                <option value="obstétrique">obstétrique</option>
                <option value="odontologie">odontologie</option>
                <option value="oncologie">oncologie</option>
                <option value="ophtalmologie">ophtalmologie</option>
                <option value="orthopédie">orthopédie</option>
                <option value="otorhinolaryngologie">otorhinolaryngologie</option>
                <option value="pédiatrie">pédiatrie</option>
                <option value="pneumologie">pneumologie</option>
                <option value="podologie">podologie</option>
                <option value="psychiatrie">psychiatrie</option>
                <option value="radiologie">radiologie</option>
                <option value="radiothérapie">radiothérapie</option>
                <option value="rhumatologie">rhumatologie</option>
                <option value="urologie">urologie</option>
              </select>
        </div>
        @if($errors->any())
            <div class="alert alert-danger" id="warningSubmit" role="alert">
            <center> <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
                <ul></center>
            </div>
         @endif
         @if(session()->has('success'))
            <div class="alert alert-success">
            <center> {{ session()->get('success') }}</center>
            </div>
        @endif
        <div class="form-group mt-5">
            <button type="submit" class="btn btn-success">Ajouter</button>
            <button type="reset" class="btn btn-dark">Vider</button>
        </div>
    </form>
</div>
@endsection