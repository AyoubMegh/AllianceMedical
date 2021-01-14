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
    Route::get('/EtablireDossierPatient','MedecinController@etablireDossierPatient')->name('medecin.etablireDossierPatient');
    Route::get('/PrendreRDV','MedecinController@prendreRDV')->name('medecin.prendreRDV');
    Route::get('/EtablireOrdonnance','MedecinController@etablireOrdonnance')->name('medecin.etablireOrdonnance');
    Route::get('/VisualisationPatient','MedecinController@visualisationPatient')->name('medecin.visualisationPatient');
    Route::get('/VisualisationPrescription','MedecinController@visualisationPatient')->name('medecin.visualisationPrescription');
    /*CRUD MEDECIN*/
    Route::get('/AjouterMedecin','MedecinController@ajouterMedecinForm')->name('medecin.ajouterMedecinForm');
    Route::get('/ModifierMedecin','MedecinController@modifierMedecinForm')->name('medecin.modifierMedecinForm');
    Route::get('/SupprimerMedecin','MedecinController@supprimerMedecinForm')->name('medecin.supprimerMedecinForm');
    Route::get('/MettreAjourMedecin','MedecinController@MettreAjourMedecinForm')->name('medecin.mettreAjourMedecinForm');
    /*CRUD SECRETAIRE*/
    Route::get('/AjouterSecretaire','MedecinController@ajouterSecretaireForm')->name('medecin.ajouterSecretaireForm');
    Route::get('/ModifierSecretaire','MedecinController@modifierSecretaireForm')->name('medecin.modifierSecretaireForm');
    Route::get('/SupprimerSecretaire','MedecinController@supprimerSecretaireForm')->name('medecin.supprimerSecretaireForm');
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
    /*maj*/
    Route::get('/MettreAjourProfil','MedecinController@mettreAjourProfil')->name('medecin.mettreAjourProfil');
    Route::put('/MettreAjourProfil','MedecinController@update')->name('medecin.update');
    /*password*/
    Route::get('/MettreAjourMDPS','MedecinController@mettreAjourMDPSForm')->name('medecin.mettreAjourMDPSForm');
    Route::put('/MettreAjourMDPS','MedecinController@mettreAjourMDPS')->name('medecin.mettreAjourMDPS');
});
Route::prefix('secretaire')->group(function(){
    Route::get('/login','Auth\SecretaireLoginContoller@showLoginForm')->name('secretaire.login');
    Route::post('/login','Auth\SecretaireLoginContoller@login')->name('secretaire.login.submit');
    Route::get('/logout','Auth\SecretaireLoginContoller@logout')->name('secretaire.logout');

    Route::get('/dashboard','SecretaireController@index')->name('secretaire.dashboard');
    Route::get('/ListePatients','SecretaireController@listePatients')->name('secretaire.listePatients');
    Route::get('/PrendreRDV','SecretaireController@prendreRDV')->name('secretaire.prendreRDV');
    Route::get('/MettreAjourRDV','SecretaireController@mettreAjourRDV')->name('secretaire.mettreAjourRDV');
    Route::get('/AnnulerRDV','SecretaireController@annulerRDV')->name('secretaire.annulerRDV');
    Route::get('/VisualisationRDV','SecretaireController@visualisationRDV')->name('secretaire.visualisationRDV');
    Route::get('/ReprendreRDV','SecretaireController@reprendreRDV')->name('secretaire.reprendreRDV');
    Route::get('/DetailsPatient','SecretaireController@detailsPatient')->name('secretaire.detailsPatient');
    /*maj*/
    Route::get('/MettreAjourProfil','SecretaireController@mettreAjourProfil')->name('secretaire.mettreAjourProfil');
    Route::put('/MettreAjourProfil','SecretaireController@update')->name('secretaire.update');
    /*password*/
    Route::get('/MettreAjourMDPS','SecretaireController@mettreAjourMDPSForm')->name('secretaire.mettreAjourMDPSForm');
    Route::put('/MettreAjourMDPS','SecretaireController@mettreAjourMDPS')->name('secretaire.mettreAjourMDPS');
    
});




