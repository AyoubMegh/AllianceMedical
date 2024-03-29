<?php

namespace App\Http\Controllers;

use Validator;
use Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Medecin;
use App\Rendezvous;
use App\Patient;
use App\Secretaire;
use App\Notification;
use App\Clinique;
use Auth;
use Hash;


class SecretaireController extends Controller
{
    public function __construct(){
        $this->middleware('auth:secretaire');
    }
    public function index(){
        $liste_med = Medecin::all()->where('enService',1);
        //$rdv_today = Rendezvous::all()->where('date_rdv',date('Y')."-".date('m')."-".date('d'))->values();
        $rdv_today  =  DB::table('rendezvouss')
                ->join('patients','rendezvouss.id_pat','=','patients.id_pat')
                ->select('rendezvouss.date_rdv','rendezvouss.heure_debut','rendezvouss.id_med','rendezvouss.heure_fin','patients.nom','patients.prenom','patients.num_ss')
                ->where('rendezvouss.date_rdv',date('Y')."-".date('m')."-".date('d'))
                ->orderBy('rendezvouss.date_rdv', 'asc')
                ->orderBy('rendezvouss.heure_debut','asc')
                ->get();
        return view('Secretaire.dash',["meds"=>$liste_med,"rdvs"=>$rdv_today]);
    }
    public function listePatients(Request $request){
        if(is_null($request->input('nom'))){
            $patients = Patient::all();
        }else{
            $patients = Patient::all()->where('nom',$request->input('nom'));
            if(count($patients)==0){
                return redirect(route('secretaire.listePatients'))->withErrors(['Patient Introuvable !']);
            }
        }
        return view('secretaire.listePatients',['patients'=>$patients]);
    }
    public function listeRendezVous(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
               return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $rdv = Rendezvous::all()->where('id_pat',$request->input('id_pat'));
                return view('Secretaire.ListeRDVParPatient',['rdvs'=>$rdv,'patient'=>$patient]);
            }
        }

    }
    public function prendreRDV(Request $request){
        $medecins = Medecin::all()->where('enService',1);
        $id_med = (!is_null($request->input('id_med')))? $request->input('id_med') : -1 ;
        return view('Secretaire.PrendreRDV',['medecins'=>$medecins,'id_med'=>$id_med]);
    }


    public function visualisationRDV(Request $request){
        $res=[];
        $res1=[];
        $patients = Patient::all();
        foreach($patients as $pat){
            $rdv  =  DB::table('rendezvouss')
                    ->join('medecins','rendezvouss.id_med','=','medecins.id_med')
                    ->join('patients','rendezvouss.id_pat','=','patients.id_pat')
                    ->select('rendezvouss.date_rdv','rendezvouss.heure_debut','medecins.nom','medecins.prenom')
                    ->where('patients.id_pat',$pat->id_pat)
                    ->orderBy('rendezvouss.date_rdv', 'asc')
                    ->get();
            array_push($res,[$rdv,$pat['attributes']]);
        }
        $medecins = Medecin::all()->where('enService',1);
        foreach($medecins as $med){
            $rdv  =  DB::table('rendezvouss')
                ->join('medecins','rendezvouss.id_med','=','medecins.id_med')
                ->join('patients','rendezvouss.id_pat','=','patients.id_pat')
                ->select('rendezvouss.date_rdv','rendezvouss.heure_debut','rendezvouss.heure_fin','patients.nom','patients.prenom','patients.date_naissance','patients.num_tel')
                ->where('medecins.id_med',$med->id_med)
                ->orderBy('rendezvouss.date_rdv', 'asc')
                ->get();
            array_push($res1,[$rdv,$med['attributes']]);
        }  
        //dd($res1);
       return view('Secretaire.VisualisationRDV',['rdvs'=>$res,'meds'=>$res1]);
    }
    public function detailsPatient(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
               return redirect(route('secretaire.listePatients'))->withErrors(['Patient Intouvable']);
            }else{
                $rdv = Rendezvous::all()->where('id_pat',$request->input('id_pat'));
                $medecins = Medecin::all()->where('enService',1);
                return view('Secretaire.DetailsPatient',['patient'=>$patient,'rdvs'=>$rdv,'medecins'=>$medecins]);
            }
        }
    }
    public function rePrendreRDV(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
               return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $medecins = Medecin::all()->where('enService',1);
                return view('Secretaire.RePrendreRDV',['patient'=>$patient,'medecins'=>$medecins]);
            }
        }
    }

    public function AjouterRDV(Request $request){
        $validator = Validator::make($request->all(),[
            'nom' => 'required|min:3|max:255',
            'prenom' => 'required|min:3|max:255',
            'date_naissance' => 'required|date',
            'num_ss' => 'required|unique:patients|max:255',
            'email' => 'required|unique:patients|max:255',
            'num_tel' => 'required',
            'date_rdv' => 'required|date',
            'heure_deb' => 'required',
            'heure_fin' => 'required',
            'id_med'=> 'required',
            'motif' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            if(strtotime($request->input('date_rdv'))<strtotime(date("Y-m-d"))){
                return Redirect::back()->withErrors(['Impossible d\'effectuer un Rendez-vous Dans le Passé !'])->withInput();
            }
            if(strtotime($request->input('heure_deb'))>=strtotime($request->input('heure_fin'))){
                return Redirect::back()->withErrors(['La fin du RDV ne peut pas etre avant le Debut, Veuillez verifier vos heures !'])->withInput();
            }
            /*Phase Ajouter Patient */
            $patient = new Patient();
            $patient->nom = $request->input('nom');
            $patient->prenom = $request->input('prenom');
            $patient->num_ss = $request->input('num_ss');
            $patient->date_naissance = $request->input('date_naissance');
            $patient->num_tel = $request->input('num_tel');
            $patient->email = $request->input('email');
            $patient->save();
            /*Phase Trouver Le RDV du medecin en question et verifier s'il y a pas probleme de chevauchement */
            $RDV = Rendezvous::all()->where('id_med',$request->input('id_med'));
            $id_sec = Auth::user()->id_sec;
            if(count($RDV)==0){//Aucun RDV pour le Medecin Specifié
                $RDV = new Rendezvous();
                $RDV->date_rdv =  $request->input('date_rdv');
                $RDV->heure_debut =  $request->input('heure_deb');
                $RDV->heure_fin =  $request->input('heure_fin');
                $RDV->motif = $request->input('motif');
                $RDV->id_med = $request->input('id_med');
                $RDV->id_sec = $id_sec;
                $RDV->id_pat = (Patient::all()->where('num_ss',$request->input('num_ss'))->first())->id_pat;
                $RDV->save();
                /*-Notification--------------------- */
                $notif = new Notification();
                $notif->titre = "Nouveau Rendez-vous !" ;
                $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                $notif->id_med = $request->input('id_med');
                $notif->save();
                /*---------------------------------- */
                return redirect()->back()->with('success', 'Patient avec son Rendez-vous Bien Ajoutés');
            }else{
                $RDV = Rendezvous::all()->where('id_med',$request->input('id_med'))->where('date_rdv',$request->input('date_rdv'))->values();
                if(count($RDV)==0){//Aucun RDV pour Le Medecin Specifié dans la date Specifié
                    $RDV = new Rendezvous();
                    $RDV->date_rdv =  $request->input('date_rdv');
                    $RDV->heure_debut =  $request->input('heure_deb');
                    $RDV->heure_fin =  $request->input('heure_fin');
                    $RDV->motif = $request->input('motif');
                    $RDV->id_med = $request->input('id_med');
                    $RDV->id_sec = $id_sec;
                    $RDV->id_pat = (Patient::all()->where('num_ss',$request->input('num_ss'))->first())->id_pat;
                    $RDV->save();
                    /*-Notification--------------------- */
                    $notif = new Notification();
                    $notif->titre = "Nouveau Rendez-vous !";
                    $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                    $notif->id_med = $request->input('id_med');
                    $notif->save();
                    /*---------------------------------- */
                    return redirect()->back()->with('success', 'Patient avec son Rendez-vous Bien Ajoutés ');
                }else{
                    $pasDeChevauchement = true;
                    $idRdvNonChevauchement = 0;
                    for($i=0;$i<count($RDV);$i++){
                        if(!(
                            (strtotime($request->input('heure_deb'))<strtotime($RDV->get($i)->heure_debut) && strtotime($request->input('heure_fin'))<=strtotime($RDV->get($i)->heure_debut))
                            ||
                            (strtotime($request->input('heure_deb'))>=strtotime($RDV->get($i)->heure_fin) && (strtotime($request->input('heure_fin'))>strtotime($RDV->get($i)->heure_fin)))
                        )){
                            $pasDeChevauchement = false;
                            $idRdvNonChevauchement = $i;
                        }
                    }
                    if($pasDeChevauchement){
                        $RDV = new Rendezvous();
                        $RDV->date_rdv =  $request->input('date_rdv');
                        $RDV->heure_debut =  $request->input('heure_deb');
                        $RDV->heure_fin =  $request->input('heure_fin');
                        $RDV->motif = $request->input('motif');
                        $RDV->id_med = $request->input('id_med');
                        $RDV->id_sec = $id_sec;
                        $RDV->id_pat = (Patient::all()->where('num_ss',$request->input('num_ss'))->first())->id_pat;
                        $RDV->save();
                        /*-Notification--------------------- */
                        $notif = new Notification();
                        $notif->titre = "Nouveau Rendez-vous !";
                        $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')."à ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                        $notif->id_med = $request->input('id_med');
                        $notif->save();
                        /*---------------------------------- */
                        return redirect()->back()->with('success', 'Patient avec son Rendez-vous Bien Ajoutés ');
                    }else{
                        $patient_deja_eng = Patient::all()->where('num_ss',$request->input('num_ss'))->first()->delete();
                        $medecin_rdv = Medecin::find($request->input('id_med'));
                        return Redirect::back()->withErrors(['Dr '.$medecin_rdv->nom.' '.$medecin_rdv->prenom.' a dejà un Rendez-vous le '.$request->input('date_rdv').' de '.$RDV->get($idRdvNonChevauchement)->heure_debut.' à '.$RDV->get($idRdvNonChevauchement)->heure_fin])->withInput();
                    }
                }
            }
        }
    }
    public function ajouterAutreRDV(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required',
            'date_rdv' => 'required|date',
            'heure_deb' => 'required',
            'heure_fin' => 'required',
            'motif' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            if(strtotime($request->input('date_rdv'))<strtotime(date("Y-m-d"))){
                return Redirect::back()->withErrors(['Impossible d\'effectuer un Rendez-vous Dans le Passé !'])->withInput();
            }
            if(strtotime($request->input('heure_deb'))>=strtotime($request->input('heure_fin'))){
                return Redirect::back()->withErrors(['La fin du RDV ne peut pas etre avant le Debut, Veuillez verifier vos heures !'])->withInput();
            }
            /*--------------------------------------------------------------- */
            $RDV_Patient = Rendezvous::all()->where('id_pat',$request->input('id_pat'))->where('date_rdv',$request->input('date_rdv'))->values();
            $pasDeChevauchement_Patient = true;
            $idRdvNonChevauchement_Patient = 0;
            for($i=0;$i<count($RDV_Patient);$i++){
                if(!(
                    (strtotime($request->input('heure_deb'))<strtotime($RDV_Patient->get($i)->heure_debut) && strtotime($request->input('heure_fin'))<=strtotime($RDV_Patient->get($i)->heure_debut))
                    ||
                    (strtotime($request->input('heure_deb'))>=strtotime($RDV_Patient->get($i)->heure_fin) && (strtotime($request->input('heure_fin'))>strtotime($RDV_Patient->get($i)->heure_fin)))
                )){
                    $pasDeChevauchement_Patient = false;
                    $idRdvNonChevauchement_Patient = $i;
                }
            }
            if(!$pasDeChevauchement_Patient){
                return Redirect::back()->withErrors(['Le Patient a Dejà un Rendez-Vous  de '.$RDV_Patient->get($idRdvNonChevauchement_Patient)->heure_debut.' à '.$RDV_Patient->get($idRdvNonChevauchement_Patient)->heure_fin])->withInput();
            }
            /*--------------------------------------------------------------- */
            $RDV = Rendezvous::all()->where('id_med',$request->input('id_med'));
            $patient = Patient::find($request->input('id_pat'));
            $id_sec = Auth::user()->id_sec;
            if(count($RDV)==0){//Aucun RDV pour le Medecin Specifié
                $RDV = new Rendezvous();
                $RDV->date_rdv =  $request->input('date_rdv');
                $RDV->heure_debut =  $request->input('heure_deb');
                $RDV->heure_fin =  $request->input('heure_fin');
                $RDV->motif = $request->input('motif');
                $RDV->id_med = $request->input('id_med');
                $RDV->id_sec = $id_sec;
                $RDV->id_pat = $request->input('id_pat');
                $RDV->save();
                /*-Notification--------------------- */
                $notif = new Notification();
                $notif->titre = "Nouveau Rendez-vous !";
                $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                $notif->id_med = $request->input('id_med');
                $notif->save();
                /*---------------------------------- */
                return redirect()->back()->with('success', 'Rendez-vous Bien Ajouté ');
            }else{
                $RDV = Rendezvous::all()->where('id_med',$request->input('id_med'))->where('date_rdv',$request->input('date_rdv'))->values();
                if(count($RDV)==0){//Aucun RDV pour Le Medecin Specifié dans la date Specifié
                    $RDV = new Rendezvous();
                    $RDV->date_rdv =  $request->input('date_rdv');
                    $RDV->heure_debut =  $request->input('heure_deb');
                    $RDV->heure_fin =  $request->input('heure_fin');
                    $RDV->motif = $request->input('motif');
                    $RDV->id_med = $request->input('id_med');
                    $RDV->id_sec = $id_sec;
                    $RDV->id_pat = $request->input('id_pat');
                    $RDV->save();
                    /*-Notification--------------------- */
                    $notif = new Notification();
                    $notif->titre = "Nouveau Rendez-vous !";
                    $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                    $notif->id_med = $request->input('id_med');
                    $notif->save();
                    /*---------------------------------- */
                    return redirect()->back()->with('success', 'Rendez-vous Bien Ajouté ');
                }else{
                    $pasDeChevauchement = true;
                    $idRdvNonChevauchement = 0;
                    for($i=0;$i<count($RDV);$i++){
                        if(!(
                            (strtotime($request->input('heure_deb'))<strtotime($RDV->get($i)->heure_debut) && strtotime($request->input('heure_fin'))<=strtotime($RDV->get($i)->heure_debut))
                            ||
                            (strtotime($request->input('heure_deb'))>=strtotime($RDV->get($i)->heure_fin) && (strtotime($request->input('heure_fin'))>strtotime($RDV->get($i)->heure_fin)))
                        )){
                            $pasDeChevauchement = false;
                            $idRdvNonChevauchement = $i;
                        }
                    }
                    if($pasDeChevauchement){
                        $RDV = new Rendezvous();
                        $RDV->date_rdv =  $request->input('date_rdv');
                        $RDV->heure_debut =  $request->input('heure_deb');
                        $RDV->heure_fin =  $request->input('heure_fin');
                        $RDV->motif = $request->input('motif');
                        $RDV->id_med = $request->input('id_med');
                        $RDV->id_sec = $id_sec;
                        $RDV->id_pat = $request->input('id_pat');
                        $RDV->save();
                        /*-Notification--------------------- */
                        $notif = new Notification();
                        $notif->titre = "Nouveau Rendez-vous !" ;
                        $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                        $notif->id_med = $request->input('id_med');
                        $notif->save();
                        /*---------------------------------- */
                        return redirect()->back()->with('success', 'Rendez-vous Bien Ajouté ');
                    }else{
                        $medecin_rdv = Medecin::find($request->input('id_med'));
                        return Redirect::back()->withErrors(['Dr '.$medecin_rdv->nom.' '.$medecin_rdv->prenom.' a dejà un Rendez-vous le '.$request->input('date_rdv').' de '.$RDV->get($idRdvNonChevauchement)->heure_debut.' à '.$RDV->get($idRdvNonChevauchement)->heure_fin])->withInput();
                    }
                }
            }
        }
    }
   
    public function MAJRDVForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_rdv' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }else{
            $RDV = Rendezvous::find($request->input('id_rdv'));
            if(is_null($RDV)){
               return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $medecins = Medecin::all()->where('enService',1);
                return view('Secretaire.MettreAjourRDVForm',['rdv'=>$RDV,'medecins'=> $medecins]);
            }
        }
    }
    public function supprimerRDV(Request $request){
        $validator = Validator::make($request->all(),[
            'id_rdv' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $RDV = Rendezvous::find($request->input('id_rdv'));
            $RDV_Sauvegarde = Rendezvous::find($request->input('id_rdv'));
            $RDV->delete();
            /*-Notification--------------------- */
            $notif = new Notification();
            $notif->titre = "Rendez-vous Annuler !" ;
            $notif->contenu = "Le Rendez-vous du ".$RDV_Sauvegarde->date_rdv." de ".$RDV_Sauvegarde->heure_debut." à ".$RDV_Sauvegarde->heure_debut." est Annulé<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
            $notif->id_med = $RDV_Sauvegarde->id_med;
            $notif->save();
            /*---------------------------------- */
            return redirect()->back()->with('success', 'Rendez-vous Bien Supprimé !');
        }
    }
    public function MAJRDV(Request $request){
        $validator = Validator::make($request->all(),[
            'id_rdv'=>'required|unique:rendezvouss,id_rdv,'.$request->input('id_rdv').','.(New Rendezvous)->getKeyName(),
            'date_rdv' => 'required|date',
            'heure_deb' => 'required',
            'heure_fin' => 'required',
            'id_med'=> 'required',
            'motif' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            if(strtotime($request->input('date_rdv'))<strtotime(date("Y-m-d"))){
                return Redirect::back()->withErrors(['Impossible d\'effectuer un Rendez-vous Dans le Passé !'])->withInput();
            }
            if(strtotime($request->input('heure_deb'))>=strtotime($request->input('heure_fin'))){
                return Redirect::back()->withErrors(['La fin du RDV ne peut pas etre avant le Debut, Veuillez verifier vos heures !'])->withInput();
            }
            /*--------------------------------------------------------------- */
            $RDV_sauvegarde_patient = Rendezvous::find($request->input('id_rdv'));
            if( strtotime($RDV_sauvegarde_patient->date_rdv)!=strtotime($request->input('date_rdv')) ||
                strtotime($RDV_sauvegarde_patient->heure_debut)!=strtotime($request->input('heure_deb')) || 
                strtotime($RDV_sauvegarde_patient->heure_fin)!=strtotime($request->input('heure_fin'))

              ){
                $RDV_sauvegarde_patient_id_pat = Rendezvous::find($request->input('id_rdv'))->id_pat;
                $RDV_Patient = Rendezvous::all()->where('id_pat',$RDV_sauvegarde_patient_id_pat)->where('date_rdv',$request->input('date_rdv'))->where('id_rdv','!=',$request->input('id_rdv'))->values();
                $pasDeChevauchement_Patient = true;
                $idRdvNonChevauchement_Patient = 0;
                for($i=0;$i<count($RDV_Patient);$i++){
                    if(!(
                        (strtotime($request->input('heure_deb'))<strtotime($RDV_Patient->get($i)->heure_debut) && strtotime($request->input('heure_fin'))<=strtotime($RDV_Patient->get($i)->heure_debut))
                        ||
                        (strtotime($request->input('heure_deb'))>=strtotime($RDV_Patient->get($i)->heure_fin) && (strtotime($request->input('heure_fin'))>strtotime($RDV_Patient->get($i)->heure_fin)))
                    )){
                        $pasDeChevauchement_Patient = false;
                        $idRdvNonChevauchement_Patient = $i;
                    }
                }
                if(!$pasDeChevauchement_Patient){
                    return Redirect::back()->withErrors(['Le Patient '.Patient::find($RDV_sauvegarde_patient_id_pat)->nom.' '.Patient::find($RDV_sauvegarde_patient_id_pat)->prenom.' a Dejà un Rendez-Vous le '.$request->input('date_rdv').' de '.$RDV_Patient->get($idRdvNonChevauchement_Patient)->heure_debut.' à '.$RDV_Patient->get($idRdvNonChevauchement_Patient)->heure_fin])->withInput();
                }
            }
            /*--------------------------------------------------------------- */
            $RDV = Rendezvous::all()->where('id_med',$request->input('id_med'));
            $RDV_sauvegarde = Rendezvous::find($request->input('id_rdv'));
            if(count($RDV)==0){//Aucun RDV pour le Medecin Specifié
                $RDV = Rendezvous::find($request->input('id_rdv'));
                $RDV->date_rdv =  $request->input('date_rdv');
                $RDV->heure_debut =  $request->input('heure_deb');
                $RDV->heure_fin =  $request->input('heure_fin');
                $RDV->motif = $request->input('motif');
                $RDV->id_med = $request->input('id_med');
                $RDV->save();
                 /*-Notification--------------------- */
                 $notif = new Notification();
                 if($request->input('id_med')==$RDV_sauvegarde->id_med){
                    $notif->titre = "Rendez-vous Changé !" ;
                    $notif->contenu = "L'ancien Rendez-vous du ".$RDV_sauvegarde->date_rdv." de ".$RDV_sauvegarde->heure_debut." à ".$RDV_sauvegarde->heure_fin."<br>est fixé pour le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                 }else{
                     /* Notif pour l'autre Medecin */
                     $nv_notif = new Notification();
                     $nv_notif->titre = "Nouveau Rendez-vous !" ;
                     $nv_notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                     $nv_notif->id_med = $request->input('id_med');
                     $nv_notif->save();
                     /*--------------------------- */
                    $notif->titre = "Rendez-vous Annuler !" ;
                    $notif->contenu = "Le Rendez-vous du ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')." est Annulé<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                 }
                 $notif->id_med = $RDV_sauvegarde->id_med;
                 $notif->save();
                 /*---------------------------------- */
                return redirect()->back()->with('success', 'Rendez-Vous Mis à Jour');
            }else{
                $RDV = Rendezvous::all()->where('id_med',$request->input('id_med'))->where('date_rdv',$request->input('date_rdv'))->values();
                if(count($RDV)==0){//Aucun RDV pour Le Medecin Specifié dans la date Specifié
                    $RDV = Rendezvous::find($request->input('id_rdv'));
                    $RDV->date_rdv =  $request->input('date_rdv');
                    $RDV->heure_debut =  $request->input('heure_deb');
                    $RDV->heure_fin =  $request->input('heure_fin');
                    $RDV->motif = $request->input('motif');
                    $RDV->id_med = $request->input('id_med');
                    $RDV->save();
                    /*-Notification--------------------- */
                    $notif = new Notification();
                    if($request->input('id_med')==$RDV_sauvegarde->id_med){
                       $notif->titre = "Rendez-vous Changé !" ;
                       $notif->contenu = "L'ancien Rendez-vous du ".$RDV_sauvegarde->date_rdv." de ".$RDV_sauvegarde->heure_debut." a ".$RDV_sauvegarde->heure_fin."<br>est fixé pour le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                    }else{
                        /* Notif pour l'autre Medecin */
                        $nv_notif = new Notification();
                        $nv_notif->titre = "Nouveau Rendez-vous !" ;
                        $nv_notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                        $nv_notif->id_med = $request->input('id_med');
                        $nv_notif->save();
                        /*--------------------------- */
                       $notif->titre = "Rendez-vous Annuler !" ;
                       $notif->contenu = "Le Rendez-vous du ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')." est Annulé<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                    }
                    $notif->id_med = $RDV_sauvegarde->id_med;
                    $notif->save();
                    /*---------------------------------- */
                    return redirect()->back()->with('success', 'Rendez-Vous Mis à Jour');
                }else{
                    $pasDeChevauchement = true;
                    $idRdvNonChevauchement = 0;
                    for($i=0;$i<count($RDV);$i++){
                        if(!(
                            (strtotime($request->input('heure_deb'))<strtotime($RDV->get($i)->heure_debut) && strtotime($request->input('heure_fin'))<=strtotime($RDV->get($i)->heure_debut))
                            ||
                            (strtotime($request->input('heure_deb'))>=strtotime($RDV->get($i)->heure_fin) && (strtotime($request->input('heure_fin'))>strtotime($RDV->get($i)->heure_fin)))
                        )){
                            if($RDV->get($i)->id_rdv!=$request->input('id_rdv')){
                                $pasDeChevauchement = false;
                                $idRdvNonChevauchement = $i;
                            }
                        }
                    }
                    if($pasDeChevauchement){
                        $RDV = Rendezvous::find($request->input('id_rdv'));
                        $RDV->date_rdv =  $request->input('date_rdv');
                        $RDV->heure_debut =  $request->input('heure_deb');
                        $RDV->heure_fin =  $request->input('heure_fin');
                        $RDV->motif = $request->input('motif');
                        $RDV->id_med = $request->input('id_med');
                        $RDV->save();
                        /*-Notification--------------------- */
                        $notif = new Notification();
                        if($request->input('id_med')==$RDV_sauvegarde->id_med){
                        $notif->titre = "Rendez-vous Changé !" ;
                        $notif->contenu = "L'ancien Rendez-vous du ".$RDV_sauvegarde->date_rdv." de ".$RDV_sauvegarde->heure_debut." à ".$RDV_sauvegarde->heure_fin."<br>est fixé pour le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                        }else{
                            /* Notif pour l'autre Medecin */
                            $nv_notif = new Notification();
                            $nv_notif->titre = "Nouveau Rendez-vous !" ;
                            $nv_notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                            $nv_notif->id_med = $request->input('id_med');
                            $nv_notif->save();
                            /*--------------------------- */
                        $notif->titre = "Rendez-vous Annuler !" ;
                        $notif->contenu = "Le Rendez-vous du ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')." est Annulé<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                        }
                        $notif->id_med = $RDV_sauvegarde->id_med;
                        $notif->save();
                        /*---------------------------------- */
                        return redirect()->back()->with('success', 'Rendez-Vous Mis à Jour');
                    }else{
                        $medecin_rdv = Medecin::find($request->input('id_med'));
                        return Redirect::back()->withErrors(['Dr '.$medecin_rdv->nom.' '.$medecin_rdv->prenom.' a dejà un Rendez-vous le '.$request->input('date_rdv').' de '.$RDV->get($idRdvNonChevauchement)->heure_debut.' à '.$RDV->get($idRdvNonChevauchement)->heure_fin])->withInput();
                    }
                }
            }
        }
    }
    public function mettreAjourProfil(){
        $id=Auth::user()->id_sec;
        $secretaire= Secretaire::find($id);
        return view('secretaire.MettreAjourProfil',['secretaire'=>$secretaire]);
    }

    public function update(Request $request){
        $id_sec=$request->input('id_sec');
        $validator = Validator::make($request->all(),[
            'id_sec'=>'required',
            'nom' => 'required|min:3|max:255',
            'prenom' => 'required|min:3|max:255',
            'email' => 'required|max:255|unique:secretaires,email,'.$id_sec.','.(New Secretaire)->getKeyName(),
            'tel' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $secretaire = Secretaire::find($request->input('id_sec'));
            $secretaire->nom = $request->input('nom');
            $secretaire->prenom = $request->input('prenom');
            $secretaire->email = $request->input('email');
            $secretaire->num_tel = $request->input('tel');
            $secretaire->save();
            return redirect()->back()->with('success', 'Profil Mis à jour !');
        }
    }

    public function mettreAjourMDPSForm(){
        $id=Auth::user()->id_sec;
        $secretaire= Secretaire::find($id);
        return view('Secretaire.MettreAjourMDPS',['secretaire'=>$secretaire]);
    }

    public function mettreAjourMDPS(Request $request){
       
        if(!(Hash::check($request->get('apassword'),Auth::user()->password))){
            return back()->withErrors(['Mot de passe incorrecte']);
        } 
        if(strcmp($request->get('apassword'),$request->get('npassword'))==0){
            return back()->withErrors(['Veuillez ajouté un Mot de passe différent de l ancien']);
        }
        if(strcmp($request->get('npassword'),$request->get('cpassword'))!=0){
            return back ()-> withErrors (['Le nouveau Mot de passe et la confirmation ne sont pas identique']);
        }  
        $user= Auth::user();
        $user->password= bcrypt($request->get('npassword'));
        $user->save();
            return redirect()->back()->with('success', 'Mot de passe modifié avec success');
    }
    public function listeMedecins(Request $request){
        if(is_null($request->input('nom'))){
            $medecins = Medecin::all()->where('enService',1);
        }else{
            $medecins = Medecin::all()->where('nom',$request->input('nom'))->where('enService',1);
            if(count($medecins)==0){
                return redirect(route('secretaire.listeMedecins'))->withErrors(['Medecin Introuvable !']);
            }
        }
        return view('Secretaire.ListeMedecins',['medecins'=>$medecins]);
    }
    public function detailsMedecin(Request $request){
        $validator = Validator::make($request->all(),[
            'id_med' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $medecin = Medecin::find($request->input('id_med'));
            if(is_null($medecin)){
                return redirect(route('secretaire.listeMedecins'))->withErrors(['Medecin Introuvable !']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                $rdvs = Rendezvous::all()->where('id_med',$request->input('id_med'));
                $medecins = Medecin::all();
                return view('Secretaire.DetailsMedecin',['isAdmin'=>$isAdmin,'medecin'=>$medecin,'rdvs'=>$rdvs,'medecins'=>$medecins]);
            }
        }
    }
    public function mettreAjourPatient(Request $request){
        $id_pat = $request->input('id_pat');
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required',
            'nom'=>'required',
            'prenom'=>'required',
            'date_naissance' => 'required',
            'num_tel'=>'required',
            'num_ss'=>'required|unique:patients,num_ss,'.$id_pat.','.(New Patient)->getKeyName(),
            'email'=>'required|unique:patients,email,'.$id_pat.','.(New Patient)->getKeyName()
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Introuvable !']);
            }else{
                $patient->nom = $request->input('nom');
                $patient->prenom = $request->input('prenom');
                $patient->date_naissance = $request->input('date_naissance');
                $patient->num_ss = $request->input('num_ss');
                $patient->num_tel = $request->input('num_tel');
                $patient->email = $request->input('email');
                $patient->save();
                return Redirect::back()->with('success','Patient Bien Mis à Jour !');
            }
        }
    }

}
