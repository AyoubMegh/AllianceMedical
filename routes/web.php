<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\MedecinController;

Route::get('/', function () {
    return view('auth/preLogin');
})->name('/');

Route::get('/forgot-password',function(){
    return view('forgot-password');
})->name('forgot-password');

Route::post('/forgot-password','ForgotPasswordController@resetPassword')->name('resetPassword');

Route::prefix('medecin')->group(function(){
    /*Auth*/
    Route::get('/login','Auth\MedecinLoginContoller@showLoginForm')->name('medecin.login');
    Route::post('/login','Auth\MedecinLoginContoller@login')->name('medecin.login.submit');
    Route::get('/logout','Auth\MedecinLoginContoller@logout')->name('medecin.logout');
    /*Get*/
    Route::get('/Dashboard','MedecinController@index')->name('medecin.dashboard');
    Route::get('/Statistiques','MedecinController@statistiques')->name('medecin.statistiques');
    Route::get('/ListePatients','MedecinController@listePatients')->name('medecin.listePatients');
    Route::get('/ListeMedecins','MedecinController@listeMedecins')->name('medecin.listeMedecins');
    Route::get('/ListeSecretaires','MedecinController@listeSecretaires')->name('medecin.listeSecretaires');
    Route::get('/MesRendezVous','MedecinController@MesRendezVous')->name('medecin.mesRendezVous');
    Route::get('/MesOrdonnances','MedecinController@MesOrdonnances')->name('medecin.mesOrdonnances');
    Route::get('/VisualisationPatient','MedecinController@visualisationPatient')->name('medecin.visualisationPatient');
    Route::get('/VisualisationPrescription','MedecinController@visualisationPatient')->name('medecin.visualisationPrescription');
    
    Route::get('/Imageries','MedecinController@imageriesForm')->name('medecin.imageriesForm');
    Route::get('/Ordonnances','MedecinController@ordonnancesForm')->name('medecin.ordonnancesForm');
    Route::get('/Lettres','MedecinController@lettresForm')->name('medecin.lettresForm');
    Route::get('/ReprendreRDV','MedecinController@reprendreRDVForm')->name('medecin.reprendreRDVForm');
    /*CRUD MEDECIN*/
    Route::get('/AjouterMedecin','MedecinController@ajouterMedecinForm')->name('medecin.ajouterMedecinForm');
    Route::get('/MettreAjourMedecin','MedecinController@MettreAjourMedecinForm')->name('medecin.mettreAjourMedecinForm');
    Route::get('/MettreAjourMDPS','MedecinController@mettreAjourMDPSForm')->name('medecin.mettreAjourMDPSForm');
    Route::get('/MettreAjourProfil','MedecinController@mettreAjourProfil')->name('medecin.mettreAjourProfil');
    Route::get('/DossierMedical','MedecinController@dossierMedicalForm')->name('medecin.dossierMedicalForm');
    Route::get('/DetailsPatient','MedecinController@detailsPatientForm')->name('medecin.detailsPatientForm');
    /*CRUD SECRETAIRE*/
    Route::get('/AjouterSecretaire','MedecinController@ajouterSecretaireForm')->name('medecin.ajouterSecretaireForm');
    Route::get('/MettreAjourSecretaire','MedecinController@MettreAjourSecretaireForm')->name('medecin.mettreAjourSecretaireForm');
    /*Post*/
    Route::post('/AjouterMedecin','MedecinController@ajouterMedecin')->name('medecin.ajouterMedecin');
    Route::post('/AjouterSecretaire','MedecinController@ajouterSecretaire')->name('medecin.ajouterSecretaire');
    /*Delete*/
    Route::delete('/SupprimerMedecin','MedecinController@supprimerMedecin')->name('medecin.supprimerMedecin');
    Route::delete('/SupprimerSecretaire','MedecinController@supprimerSecretaire')->name('medecin.supprimerSecretaire');
    /*PUT*/
    Route::put('/MettreAjourMedecin','MedecinController@mettreAjourMedecin')->name('medecin.mettreAjourMedecin');
    Route::put('/MettreAjourSecretaire','MedecinController@mettreAjourSecretaire')->name('medecin.mettreAjourSecretaire');
    Route::put('/MettreAjourMDPS','MedecinController@mettreAjourMDPS')->name('medecin.mettreAjourMDPS');
    Route::put('/MettreAjourProfil','MedecinController@update')->name('medecin.update');
    Route::put('/DossierMedical','MedecinController@dossierMedical')->name('medecin.dossierMedical');
    Route::put('/DetailsPatient','MedecinController@detailsPatient')->name('medecin.detailsPatient');

});

Route::prefix('secretaire')->group(function(){
    Route::get('/login','Auth\SecretaireLoginContoller@showLoginForm')->name('secretaire.login');
    Route::post('/login','Auth\SecretaireLoginContoller@login')->name('secretaire.login.submit');
    Route::get('/logout','Auth\SecretaireLoginContoller@logout')->name('secretaire.logout');
    Route::get('/dashboard','SecretaireController@index')->name('secretaire.dashboard');
    Route::get('/ListePatients','SecretaireController@listePatients')->name('secretaire.listePatients');
    Route::get('/ListeRendezVous','SecretaireController@listeRendezVous')->name('secretaire.listeRendezVous');
    Route::get('/PrendreRDV','SecretaireController@prendreRDV')->name('secretaire.prendreRDV');
    Route::get('/VisualisationRDV','SecretaireController@visualisationRDV')->name('secretaire.visualisationRDV');
    Route::get('/RePrendreRDV','SecretaireController@rePrendreRDV')->name('secretaire.reprendreRDV');
    Route::get('/DetailsPatient','SecretaireController@detailsPatient')->name('secretaire.detailsPatient');
    Route::get('/MettreAjourRDVForm','SecretaireController@MAJRDVForm')->name('secretaire.MAJRDV');
    Route::get('/MettreAjourMDPS','SecretaireController@mettreAjourMDPSForm')->name('secretaire.mettreAjourMDPSForm');
    Route::get('/MettreAjourProfil','SecretaireController@mettreAjourProfil')->name('secretaire.mettreAjourProfil');
    /*Post*/
    Route::Post('/PrendreRDV','SecretaireController@AjouterRDV')->name('secretaire.ajouterRDV');
    Route::Post('/RePrendreRDV','SecretaireController@ajouterAutreRDV')->name('secretaire.ajouterAutreRDV');
    /*PUT*/
    Route::put('/MettreAjourRDVForm','SecretaireController@MAJRDV')->name('secretaire.MAJRDV');
    Route::put('/MettreAjourMDPS','SecretaireController@mettreAjourMDPS')->name('secretaire.mettreAjourMDPS');
    Route::put('/MettreAjourProfil','SecretaireController@update')->name('secretaire.update');
    /*Delete*/
    Route::delete('/AnnulerUnRDV','SecretaireController@supprimerRDV')->name('secretaire.supprimerRDV');

    
});




