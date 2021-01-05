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
    return view('login');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/forgot-password',function(){
    return view('forgot-password');
});
/* Routes Medecin Admin */
Route::group(
    [
        'prefix'     => 'admin',
        'middleware' => [
            /*'auth',*/
        ],
    ],      
    function() {
        /*Sous-lien degree 1 */
        Route::get('/dashboard', function () {
            return view('MedecinAdmin.dash');
        });
        Route::get('/statistiques', function () {
            return view('MedecinAdmin.statistiques');
        });
        Route::get('/ListePatients', function () {
            return view('MedecinAdmin.ListePatients');
        });
        Route::get('/EtablireDossierPatient', function () {
            return view('MedecinAdmin.EtablireDossierPatient');
        });
        Route::get('/PrendreRDV', function () {
            return view('MedecinAdmin.PrendreRDV');
        });
        Route::get('/EtablireOrdonnance', function () {
            return view('MedecinAdmin.EtablireOrdonnance');
        });
        Route::get('/VisualisationPatient', function () {
            return view('MedecinAdmin.VisualisationPatient');
        });
        Route::get('/VisualisationPrescription', function () {
            return view('MedecinAdmin.VisualisationPrescription');
        });
        Route::get('/AjouterMedecin', function () {
            return view('MedecinAdmin.AjouterMedecin');
        });
        Route::get('/ModifierMedecin', function () {
            return view('MedecinAdmin.ModifierMedecin');
        });
        Route::get('/SupprimerMedecin', function () {
            return view('MedecinAdmin.SupprimerMedecin');
        });
        Route::get('/AjouterSecretaire', function () {
            return view('MedecinAdmin.AjouterSecretaire');
        });
        Route::get('/ModifierSecretaire', function () {
            return view('MedecinAdmin.ModifierSecretaire');
        });
        Route::get('/SupprimerSecretaire', function () {
            return view('MedecinAdmin.SupprimerSecretaire');
        });
        /*Sous-lien degree 2*/
    }
);
/* Routes Medecin Normal */
Route::group(
    [
        'prefix'     => 'medecin',
        'middleware' => [
            /*'auth',*/
        ],
    ],      
    function() {
        /*Sous-lien degree 1 */
        Route::get('/dashboard', function () {
            return view('Medecin.dash');
        });
        Route::get('/ListePatients', function () {
            return view('Medecin.ListePatients');
        });
        Route::get('/EtablireDossierPatient', function () {
            return view('Medecin.EtablireDossierPatient');
        });
        Route::get('/PrendreRDV', function () {
            return view('Medecin.PrendreRDV');
        });
        Route::get('/EtablireOrdonnance', function () {
            return view('Medecin.EtablireOrdonnance');
        });
        Route::get('/VisualisationPatient', function () {
            return view('Medecin.VisualisationPatient');
        });
        Route::get('/VisualisationPrescription', function () {
            return view('Medecin.VisualisationPrescription');
        });
        /*Sous-lien degree 2*/
    }
);

/* Routes Secretaire */
Route::group(
    [
        'prefix'     => 'secretaire',
        'middleware' => [
            /*'auth',*/
        ],
    ],      
    function() {
        /*Sous-lien degree 1 */
        Route::get('/dashboard', function () {
            return view('Secretaire.dash');
        });
        /*Sous-lien degree 2*/
    }
);