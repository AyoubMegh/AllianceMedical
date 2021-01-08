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

Route::get('/', function () {
    return view('auth/preLogin');
})->name('/');

Route::get('/forgot-password',function(){
    return view('forgot-password');
})->name('forgot-password');

Route::prefix('medecin')->group(function(){
    Route::get('/login','Auth\MedecinLoginContoller@showLoginForm')->name('medecin.login');
    Route::post('/login','Auth\MedecinLoginContoller@login')->name('medecin.login.submit');
    Route::get('/logout','Auth\MedecinLoginContoller@logout')->name('medecin.logout');
    Route::get('/dashboard','MedecinController@index')->name('medecin.dashboard');
});
Route::prefix('secretaire')->group(function(){
    Route::get('/login','Auth\SecretaireLoginContoller@showLoginForm')->name('secretaire.login');
    Route::post('/login','Auth\SecretaireLoginContoller@login')->name('secretaire.login.submit');
    Route::get('/logout','Auth\SecretaireLoginContoller@logout')->name('secretaire.logout');
    Route::get('/dashboard','SecretaireController@index')->name('secretaire.dashboard');
    
});

        /*Route::get('admin/statistiques', function () {
            return view('MedecinAdmin.statistiques');
        });
        Route::get('admin/ListePatients', function () {
            return view('MedecinAdmin.ListePatients');
        });
        Route::get('admin/EtablireDossierPatient', function () {
            return view('MedecinAdmin.EtablireDossierPatient');
        });
        Route::get('admin/PrendreRDV', function () {
            return view('MedecinAdmin.PrendreRDV');
        });
        Route::get('admin/EtablireOrdonnance', function () {
            return view('MedecinAdmin.EtablireOrdonnance');
        });
        Route::get('admin/VisualisationPatient', function () {
            return view('MedecinAdmin.VisualisationPatient');
        });
        Route::get('admin/VisualisationPrescription', function () {
            return view('MedecinAdmin.VisualisationPrescription');
        });
        Route::get('admin/AjouterMedecin', function () {
            return view('MedecinAdmin.AjouterMedecin');
        });
        Route::get('admin/ModifierMedecin', function () {
            return view('MedecinAdmin.ModifierMedecin');
        });
        Route::get('admin/SupprimerMedecin', function () {
            return view('MedecinAdmin.SupprimerMedecin');
        });
        Route::get('admin/AjouterSecretaire', function () {
            return view('MedecinAdmin.AjouterSecretaire');
        });
        Route::get('admin/ModifierSecretaire', function () {
            return view('MedecinAdmin.ModifierSecretaire');
        });
        Route::get('admin/SupprimerSecretaire', function () {
            return view('MedecinAdmin.SupprimerSecretaire');
        });

        //Route::get('/medecin/dashboard', function () {
          //  return view('Medecin.dash');
        //});

        Route::get('medecin/ListePatients', function () {
            return view('Medecin.ListePatients');
        });
        Route::get('medecin/EtablireDossierPatient', function () {
            return view('Medecin.EtablireDossierPatient');
        });
        Route::get('medecin/PrendreRDV', function () {
            return view('Medecin.PrendreRDV');
        });
        Route::get('medecin/EtablireOrdonnance', function () {
            return view('Medecin.EtablireOrdonnance');
        });
        Route::get('medecin/VisualisationPatient', function () {
            return view('Medecin.VisualisationPatient');
        });
        Route::get('medecin/VisualisationPrescription', function () {
            return view('Medecin.VisualisationPrescription');
        });
*/
        



