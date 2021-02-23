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
    Route::post('/login','Auth\MedecinLoginContoller@login')->name('medecin.login.submit')->middleware('isEnService');
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
    Route::put('/SupprimerMedecin','MedecinController@supprimerMedecin')->name('medecin.supprimerMedecin');
    Route::delete('/SupprimerSecretaire','MedecinController@supprimerSecretaire')->name('medecin.supprimerSecretaire');
    /*PUT*/
    Route::put('/MettreAjourMedecin','MedecinController@mettreAjourMedecin')->name('medecin.mettreAjourMedecin');
    Route::put('/MettreAjourSecretaire','MedecinController@mettreAjourSecretaire')->name('medecin.mettreAjourSecretaire');
    Route::put('/MettreAjourMDPS','MedecinController@mettreAjourMDPS')->name('medecin.mettreAjourMDPS');
    Route::put('/MettreAjourProfil','MedecinController@update')->name('medecin.update');
    Route::put('/DossierMedical','MedecinController@dossierMedical')->name('medecin.dossierMedical');
    Route::put('/DetailsPatient','MedecinController@detailsPatient')->name('medecin.detailsPatient');
    /* Images */
    Route::post('/Imageries','ImageController@AjouterImages')->name('image.medecin.ajouterImages');
    /*RDV*/
    Route::post('/ReprendreRDV','MedecinController@reprendreRDV')->name('medecin.reprendreRDV');
    /*Ordonnances*/
    Route::post('/Ordonnances','MedecinController@ajouterOrdonnance')->name('medecin.ordonnances');
    /*Get Lettre et autres */
    Route::get('/CertificatBonneSanté','MedecinController@certificatBonneSanteForm')->name('medecin.certificatBonneSanteForm');
    Route::post('/CertificatBonneSanté','MedecinController@certificatBonneSante')->name('medecin.certificatBonneSante');

    Route::get('/LettreOrientation', 'MedecinController@lettreOrientationForm')->name('medecin.lettreOrientationForm');
    Route::post('/LettreOrientation', 'MedecinController@lettreOrientation')->name('medecin.lettreOrientation');

    Route::get('/CertificatArretTravail', 'MedecinController@certificatArretTravailForm')->name('medecin.certificatArretTravailForm');
    Route::post('/CertificatArretTravail', 'MedecinController@certificatArretTravail')->name('medecin.certificatArretTravail');

    Route::get('/CertificatPneumoPhtisiologie', 'MedecinController@certificatPneumoPhtisiologieForm')->name('medecin.certificatPneumoPhtisiologieForm');
    Route::post('/CertificatPneumoPhtisiologie', 'MedecinController@certificatPneumoPhtisiologie')->name('medecin.certificatPneumoPhtisiologie');

    Route::get('/CertificatRepriseTravail', 'MedecinController@certificatRepriseTravailForm')->name('medecin.certificatRepriseTravailForm');
    Route::post('/CertificatRepriseTravail', 'MedecinController@certificatRepriseTravail')->name('medecin.certificatRepriseTravail');

    /* Notifications */
    Route::get('/Notifications','MedecinController@listeNotifications')->name('medecin.notifications');
    Route::delete('/Notifications','MedecinController@suppimerNotification')->name('medecin.suppNotification');

    /*Details Medecin*/
    Route::get('/DetailsMedecin','MedecinController@detailsMedecin')->name('medecin.detailsMedecin');

    /*Calander */
    Route::get('/EventsMed/{id_med}',function($id_med){
    $events = App\Rendezvous::all()->where('id_med',$id_med)->values();
    $data = collect($events)->map(function($event){
        return [
            "id" => $event->id_rdv."",
            "title" => "Rendez-vous Avec le patient : ".App\Patient::find($event->id_pat)->nom." ".App\Patient::find($event->id_pat)->prenom,
            "start"=> $event->date_rdv.' '.$event->heure_debut,
            "end" => $event->date_rdv.' '.$event->heure_fin,
            "allDay" => false,
            "date_rdv"=>$event->date_rdv,
            "heure_deb"=>$event->heure_debut,
            "heure_fin"=>$event->heure_fin,
            "motif"=>$event->motif,
            "num_ss"=>App\Patient::find($event->id_pat)->num_ss,
            "nom_prenom"=>App\Patient::find($event->id_pat)->nom." ".App\Patient::find($event->id_pat)->prenom
        ];
    });
    
    $result = [];
    for($i=0;$i<$data->count();$i++){
        array_push($result,$data[$i]);
    }
    return $result;
})->name('medecin.eventsMed');

Route::get('/DashMed/{id_med}',function($id_med){
    $events = App\Rendezvous::all()->where('id_med',$id_med)->values();
    $data = collect($events)->map(function($event){
        return [
            "id" => $event->id_rdv."",
            "title" => "Rendez-vous Avec le patient : ".App\Patient::find($event->id_pat)->nom." ".App\Patient::find($event->id_pat)->prenom,
            "start"=> $event->date_rdv.' '.$event->heure_debut,
            "end" => $event->date_rdv.' '.$event->heure_fin,
            "allDay" => false,
            "date_rdv"=>$event->date_rdv,
            "heure_deb"=>$event->heure_debut,
            "heure_fin"=>$event->heure_fin,
            "motif"=>$event->motif,
            "nom" => App\Patient::find($event->id_pat)->nom,
            "prenom" => App\Patient::find($event->id_pat)->prenom,
            "num_ss" => App\Patient::find($event->id_pat)->num_ss
        ];
    });
    $result = [];
    for($i=0;$i<$data->count();$i++){
        array_push($result,$data[$i]);
    }
    return $result;
    })->name('medecin.DashMed');


    /*AutoReload */
    Route::get('/NombreNotif','MedecinController@nombreDeNotification');

    Route::put('/MettreAjourRDVForm','MedecinController@MAJRDV')->name('medecin.MAJRDV');
    Route::delete('/AnnulerUnRDV','MedecinController@supprimerRDV')->name('medecin.supprimerRDV');
    Route::Post('/PrendreRDV','MedecinController@AjouterRDV')->name('medecin.ajouterRDV');

    /*Ligne Prescription*/
    Route::put('/MettreAjourLignePres','MedecinController@MAJLignePres')->name('medecin.MAJLignePres');
    Route::delete('/SupprimerLignePres','MedecinController@SuppLignePres')->name('medecin.SuppLignePres');
    Route::post('/AjouterLignePres','MedecinController@AjouterLignePres') ->name('medecin.ajouterLignePres');

     /*Calendrier Patient */
     Route::get('/EventsPatient/{id_pat}',function($id_pat){
        $events = App\Rendezvous::all()->where('id_pat',$id_pat)->values();
        $data = collect($events)->map(function($event){
            return [
                "id" => $event->id_rdv."",
                "title" => "Rendez-vous Avec le Medecin : ".App\Medecin::find($event->id_med)->nom." ".App\Medecin::find($event->id_med)->prenom,
                "start"=> $event->date_rdv.' '.$event->heure_debut,
                "end" => $event->date_rdv.' '.$event->heure_fin,
                "allDay" => false,
                "date_rdv"=>$event->date_rdv,
                "heure_deb"=>$event->heure_debut,
                "heure_fin"=>$event->heure_fin,
                "id_med" => $event->id_med,
                "motif"=>$event->motif
            ];
        });
        $result = [];
        for($i=0;$i<$data->count();$i++){
            array_push($result,$data[$i]);
        }
        return $result;
    })->name('medecin.eventsPatient');
    /*Supprimer mettre a jour lettre et prescription */
    Route::delete('/SupprimerLettre','MedecinController@SupprimerLettre')->name('medecin.supprimerLettre');
    Route::delete('/SupprimerOrdonnance','MedecinController@SupprimerOrdonnance')->name('medecin.supprimerOrdonnance');

    Route::put('/MAJCertificatBonneSante','MedecinController@MAJCertificatBS')->name('medecin.majCertificatBS');
    Route::put('/MAJCertificatPneumoPhtisiologie','MedecinController@MAJCertificatPP')->name('medecin.majCertificatPP');
    Route::put('/MAJCertificatRepriseTravail','MedecinController@MAJCertificatRT')->name('medecin.majCertificatRT');
    Route::put('/MAJCertificatArretTravail','MedecinController@MAJCertificatAT')->name('medecin.majCertificatAT');
    Route::put('/MAJLettreOrientation','MedecinController@MAJLettreOrientation')->name('medecin.majLettreO');

    Route::delete('/SuppImage','MedecinController@suppimg')->name('medecin.suppimg');

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
    Route::get('/ListeMedecins','SecretaireController@listeMedecins')->name('secretaire.listeMedecins');
    /*Post*/
    Route::Post('/PrendreRDV','SecretaireController@AjouterRDV')->name('secretaire.ajouterRDV');
    Route::Post('/RePrendreRDV','SecretaireController@ajouterAutreRDV')->name('secretaire.ajouterAutreRDV');
    /*PUT*/
    Route::put('/MettreAjourRDVForm','SecretaireController@MAJRDV')->name('secretaire.MAJRDV');
    Route::put('/MettreAjourMDPS','SecretaireController@mettreAjourMDPS')->name('secretaire.mettreAjourMDPS');
    Route::put('/MettreAjourProfil','SecretaireController@update')->name('secretaire.update');
    /*Delete*/
    Route::delete('/AnnulerUnRDV','SecretaireController@supprimerRDV')->name('secretaire.supprimerRDV');

    /*Calendrier Medeicn */
    Route::get('/EventsMed/{id_med}',function($id_med){
    $events = App\Rendezvous::all()->where('id_med',$id_med)->values();
    $data = collect($events)->map(function($event){
        return [
            "id" => $event->id_rdv."",
            "title" => "Rendez-vous Avec le patient : ".App\Patient::find($event->id_pat)->nom." ".App\Patient::find($event->id_pat)->prenom,
            "start"=> $event->date_rdv.' '.$event->heure_debut,
            "end" => $event->date_rdv.' '.$event->heure_fin,
            "allDay" => false,
            "date_rdv"=>$event->date_rdv,
            "heure_deb"=>$event->heure_debut,
            "heure_fin"=>$event->heure_fin,
            "motif"=>$event->motif
        ];
    });
    $result = [];
    for($i=0;$i<$data->count();$i++){
        array_push($result,$data[$i]);
    }
    return $result;
    })->name('medecin.eventsMed');

    /*Calendrier Patient */
    Route::get('/EventsPatient/{id_pat}',function($id_pat){
        $events = App\Rendezvous::all()->where('id_pat',$id_pat)->values();
        $data = collect($events)->map(function($event){
            return [
                "id" => $event->id_rdv."",
                "title" => "Rendez-vous Avec le Medecin : ".App\Medecin::find($event->id_med)->nom." ".App\Medecin::find($event->id_med)->prenom,
                "start"=> $event->date_rdv.' '.$event->heure_debut,
                "end" => $event->date_rdv.' '.$event->heure_fin,
                "allDay" => false,
                "date_rdv"=>$event->date_rdv,
                "heure_deb"=>$event->heure_debut,
                "heure_fin"=>$event->heure_fin,
                "id_med" => $event->id_med,
                "motif"=>$event->motif
            ];
        });
        $result = [];
        for($i=0;$i<$data->count();$i++){
            array_push($result,$data[$i]);
        }
        return $result;
    })->name('medecin.eventsPatient');

    /*Details Medecin*/
    Route::get('/DetailsMedecin','SecretaireController@detailsMedecin')->name('secretaire.detailsMedecin');

    /*MAJ Patient */
    Route::put('/MettreAJourPatient','SecretaireController@mettreAjourPatient')->name('secretaire.majPatient');
});






