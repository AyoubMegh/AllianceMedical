<?php

namespace App\Http\Controllers;

use Validator;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Rendezvous;
use App\Clinique;
use App\Medecin;
use App\Patient;
use App\Secretaire;
use App\Image;
use App\Ligneprescription;
use App\Prescription;
use App\Lettre;
use App\Notification;
use Auth;
use Mail;
use Hash;


class MedecinController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth:medecin');
    }
    public function index(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.dash',['isAdmin'=>$isAdmin]);
    }
    public function statistiques(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        /*------------------------------- */
        $query_rdv_year = 'SELECT COUNT(*) as NOMBRE ,MONTH(date_rdv) as MOIS FROM rendezvouss WHERE YEAR(date_rdv)=YEAR(NOW()) GROUP BY MONTH(date_rdv) ORDER BY MONTH(date_rdv) ';
        $rdvs_year = DB::select($query_rdv_year);
        $res_rdv_year= array_fill(0,12,0);
        for($i=0;$i<12;$i++){
            if($i<count($rdvs_year)){
                $res_rdv_year[$rdvs_year[$i]->MOIS-1]=$rdvs_year[$i]->NOMBRE;
            }
        }
        /*------------------------------- */
        $query_pres_year = 'SELECT COUNT(*) as NOMBRE ,MONTH(date_pres) as MOIS FROM prescriptions WHERE YEAR(date_pres)=YEAR(NOW()) GROUP BY MONTH(date_pres) ORDER BY MONTH(date_pres) ';
        $pres_year = DB::select($query_pres_year);
        $pres_rdv_year= array_fill(0,12,0);
        for($i=0;$i<12;$i++){
            if($i<count($pres_year)){
                $pres_rdv_year[$pres_year[$i]->MOIS-1]=$pres_year[$i]->NOMBRE;
            }
        }
        /*------------------------------- */
        $query_med_rdv_mois ="SELECT M.nom as NOM , M.prenom as PRENOM , count(*) as NOMBRE FROM rendezvouss R,medecins M WHERE R.id_med=M.id_med AND R.date_rdv >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH,'%Y-%m-01') AND R.date_rdv < DATE_FORMAT(CURRENT_DATE,'%Y-%m-01')    GROUP BY NOM,PRENOM"; 
        $med_rdv_mois = DB::select($query_med_rdv_mois);
        $nom_meds_rdv_mois = array();
        $nombre_meds_rdv_mois = array();
        for($i=0;$i<count($med_rdv_mois);$i++){
            array_push($nom_meds_rdv_mois,$med_rdv_mois[$i]->NOM." ".$med_rdv_mois[$i]->PRENOM);
            array_push($nombre_meds_rdv_mois,$med_rdv_mois[$i]->NOMBRE);
        }
        //dd($nom_meds_rdv_mois);
        return view('Medecin.Statistiques',['isAdmin'=>$isAdmin,'stats_rdv'=>$res_rdv_year,'stats_pres'=>$pres_rdv_year,'nom_meds'=>$nom_meds_rdv_mois,'nombre_meds'=>$nombre_meds_rdv_mois]);
    }
    public function listeNotifications(){
        $mes_notifs = Notification::where('id_med',Auth::user()->id_med)->orderBy('created_at','DESC')->get();
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.Notifications',['isAdmin'=>$isAdmin,'notifs'=>$mes_notifs]);
    }
    public function suppimerNotification(Request $request){
        $validator = Validator::make($request->all(),[
           'id_notif' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $notif = Notification::find($request->input('id_notif'));
            if($notif->delete()){
                return redirect()->back()->with('success', 'Notification Supprimée !');
            }else{
                return redirect(route('medecin.notifications'))->withErrors(['Impossible de Supprimer La notification !']);
            }
        }
        
    }
    public function listePatients(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(is_null($request->input('nom'))){
            $patients = Patient::all();
        }else{
            $patients = Patient::all()->where('nom',$request->input('nom'));
            if(count($patients)==0){
                return redirect(route('medecin.listePatients'))->withErrors(['Patient Introuvable !']);
            }
        }
        return view('Medecin.ListePatients',['isAdmin'=>$isAdmin,'patients'=>$patients]);
    }

    public function listeMedecins(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        if(is_null($request->input('nom_med'))){
            $medecins = Medecin::all()->where('enService',1)->whereNotIn('id_med',Auth::user()->id_med);
        }else{
            $medecins = Medecin::all()->where('enService',1)->where('nom',$request->input('nom_med'));
            if(count($medecins)==0){
                return redirect(route('medecin.listeMedecins'))->withErrors(['Medecin Introuvable !']);
            }
        }
        return view('Medecin.ListeMedecins',['isAdmin'=>$isAdmin,'medecins'=>$medecins]);
    }
    public function listeSecretaires(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        if(is_null($request->input('nom_sec'))){
            $secretaires = Secretaire::all();
        }else{
            $secretaires = Secretaire::all()->where('nom',$request->input('nom_sec'));
            if(count($secretaires)==0){
                return redirect(route('medecin.listeSecretaires'))->withErrors(['Secretaire Introuvable !']);
            }
        }
        return view('Medecin.ListeSecretaires',['isAdmin'=>$isAdmin,'secretaires'=>$secretaires]);
    }
    public function MesRendezVous(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(is_null($request->input('num_ss'))){
            $rdvs  =  DB::table('rendezvouss')
            ->join('medecins','rendezvouss.id_med','=','medecins.id_med')
            ->join('patients','rendezvouss.id_pat','=','patients.id_pat')
            ->select('rendezvouss.date_rdv','rendezvouss.heure_debut','rendezvouss.heure_fin','rendezvouss.motif','patients.nom','patients.prenom','patients.num_ss','patients.id_pat')
            ->where('medecins.id_med',Auth::user()->id_med)
            ->orderBy('rendezvouss.date_rdv', 'desc')
            ->get();
        }else{
            $patients = Patient::all()->where('num_ss',$request->input('num_ss'));
            $rdvs  =  DB::table('rendezvouss')
            ->join('medecins','rendezvouss.id_med','=','medecins.id_med')
            ->join('patients','rendezvouss.id_pat','=','patients.id_pat')
            ->select('rendezvouss.date_rdv','rendezvouss.heure_debut','rendezvouss.heure_fin','rendezvouss.motif','patients.nom','patients.prenom','patients.num_ss','patients.id_pat')
            ->where('medecins.id_med',Auth::user()->id_med)
            ->where('patients.num_ss',$request->input('num_ss'))
            ->orderBy('rendezvouss.date_rdv', 'desc')
            ->get();
            if(count($patients)==0){
                return redirect(route('medecin.mesRendezVous'))->withErrors(['Patient Introuvable !']);
            }
        }
        return view('Medecin.MesRendezVous',['isAdmin'=>$isAdmin,'rdvs'=>$rdvs]);
    }
    public function MesOrdonnances(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(is_null($request->input('num_ss'))){
            $ordonnances = DB::table('prescriptions')
            ->join('medecins','prescriptions.id_med','=','medecins.id_med')
            ->join('patients','prescriptions.id_pat','=','patients.id_pat')
            ->select('patients.nom','patients.prenom','patients.num_ss','patients.id_pat','prescriptions.created_at','prescriptions.date_pres')
            ->where('prescriptions.id_med',Auth::user()->id_med)
            ->orderBy('prescriptions.date_pres', 'desc')
            ->get();
        }else{
            $patients = Patient::all()->where('num_ss',$request->input('num_ss'));
            $ordonnances = DB::table('prescriptions')
            ->join('medecins','prescriptions.id_med','=','medecins.id_med')
            ->join('patients','prescriptions.id_pat','=','patients.id_pat')
            ->select('patients.nom','patients.prenom','patients.num_ss','patients.id_pat','prescriptions.created_at','prescriptions.date_pres')
            ->where('prescriptions.id_med',Auth::user()->id_med)
            ->where('patients.num_ss',$request->input('num_ss'))
            ->orderBy('prescriptions.date_pres', 'desc')
            ->get();
            if(count($patients)==0){
                return redirect(route('medecin.mesOrdonnances'))->withErrors(['Patient Introuvable !']);
            }
        }
        return view('Medecin.MesOrdonnances',['isAdmin'=>$isAdmin,'ords'=>$ordonnances]);
    }

    public function ajouterMedecinForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        return view('Medecin.AjouterMedecin',['isAdmin'=>$isAdmin]);
    }

    public function ajouterSecretaireForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        return view('Medecin.AjouterSecretaire',['isAdmin'=>$isAdmin]);
    }

    public function ajouterMedecin(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        $validator = Validator::make($request->all(),[
            'nom' => 'required|min:3|max:255',
            'prenom' => 'required|min:3|max:255',
            'login' => 'required|unique:medecins|unique:secretaires|max:255',
            'email' => 'required|unique:medecins|unique:secretaires|max:255',
            'password' => 'min:8|required_with:confirmPassword|same:confirmPassword',
            'confirmPassword' => 'min:8',
            'specialite'=> 'required|min:3|max:255',
            'tel' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $medecin = new Medecin;
            $medecin->nom = $request->input('nom');
            $medecin->prenom = $request->input('prenom');
            $medecin->login = $request->input('login');
            $medecin->email = $request->input('email');
            $medecin->num_tel = $request->input('tel');
            $medecin->specialite = $request->input('specialite');
            $medecin->enService = 1;
            $medecin->password = bcrypt($request->input('password'));
            $medecin->id_clq = Auth::user()->id_clq;
            $medecin->save();
            /*Envoie Email---------------*/
            $date = date('Y/m/d H:i:s');
            $message =  "Bonjour DR.".$request->input('nom')." ".$request->input('prenom')."\n";
            $message .=  "Bienvenue dans Alliance Medical Votre Compte a été créé le : ".$date."\n";
            $message .= "Voici Votre Login : ".$request->input('login')."\n";
            $message .= "Voici Votre Mot de Passe : ".$request->input('password')."\n";
            $message .= "Important : Nous vous prions de bien vouloir Changer votre Mot de Passe une fois connecté ! \n";
            $message .= "Merci";
            $query = Medecin::all()->where('email',$request->input('email'))->first();
            Mail::raw($message,function($mail) use ($query){
                $mail->from('Alliance.Medical.Mail@gmail.com','Alliance Medicale');
                $mail->to($query->email,$query->nom.' '.$query->prenom)->subject('Bienvenue dans Alliance Medical');
            });
            /*---------------------------*/
            return redirect()->back()->with('success', 'Medecin Bien Ajouté !');
        }
    }
    public function supprimerMedecin(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        $validator = Validator::make($request->all(),[
            'id_med' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $medecin = Medecin::find($request->input('id_med'));
            $medecin->enService = 0;
            $medecin->save();
            return redirect(route('medecin.listeMedecins'))->with('success', 'Medecin Bien Supprimé !');
        }
    }
    public function MettreAjourMedecinForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        $validator = Validator::make($request->all(),[
            'id_med' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $medecin = Medecin::find($request->input('id_med'));
            if(is_null($medecin)){
                return Redirect::back()->withErrors(['Medecin Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                 return view('Medecin.MettreAjourMedecin',['isAdmin'=>$isAdmin,'medecin'=>$medecin]);
            }
        }
    }
    public function mettreAjourMedecin(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        $id_med=$request->input('id_med');
        $validator = Validator::make($request->all(),[
            'id_med'=>'required',
            'nom' => 'required|min:3|max:255',
            'prenom' => 'required|min:3|max:255',
            'login' => 'required|max:255|unique:medecins,login,'.$id_med.','.(New Medecin)->getKeyName(),
            'email' => 'required|max:255|unique:medecins,email,'.$id_med.','.(New Medecin)->getKeyName(),
            'tel' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $medecin = Medecin::find($request->input('id_med'));
            $medecin->nom = $request->input('nom');
            $medecin->prenom = $request->input('prenom');
            $medecin->login = $request->input('login');
            $medecin->email = $request->input('email');
            $medecin->num_tel = $request->input('tel');
            $medecin->save();
            return redirect()->back()->with('success', 'Medecin Mis à jour !');
        }
    }

    public function ajouterSecretaire(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        $validator = Validator::make($request->all(),[
            'nom' => 'required|min:3|max:255',
            'prenom' => 'required|min:3|max:255',
            'login' => 'required|unique:secretaires|unique:medecins|max:255',
            'email' => 'required|unique:secretaires|unique:medecins|max:255',
            'password' => 'min:8|required_with:confirmPassword|same:confirmPassword',
            'confirmPassword' => 'min:8',
            'tel' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $secretaire = new Secretaire;
            $secretaire->nom = $request->input('nom');
            $secretaire->prenom = $request->input('prenom');
            $secretaire->login = $request->input('login');
            $secretaire->email = $request->input('email');
            $secretaire->num_tel = $request->input('tel');
            $secretaire->password = bcrypt($request->input('password'));
            $secretaire->id_clq = Auth::user()->id_clq;
            $secretaire->save();
            /*Envoie Email---------------*/
            $date = date('Y/m/d H:i:s');
            $message =  "Bonjour ".$request->input('nom')." ".$request->input('prenom')."\n";
            $message .= "Bienvenue dans Alliance Medical Votre Compte a été créé le : ".$date."\n";
            $message .= "Voici Votre Login : ".$request->input('login')."\n";
            $message .= "Voici Votre Mot de Passe : ".$request->input('password')."\n";
            $message .= "Important : Nous vous prions de bien vouloir Changer votre Mot de Passe une fois connecté ! \n";
            $message .= "Merci";
            $query = Secretaire::all()->where('email',$request->input('email'))->first();
            Mail::raw($message,function($mail) use ($query){
                $mail->from('Alliance.Medical.Mail@gmail.com','Alliance Medicale');
                $mail->to($query->email,$query->nom.' '.$query->prenom)->subject('Bienvenue dans Alliance Medical');
            });
            /*---------------------------*/
            return redirect()->back()->with('success', 'Secretaire Bien Ajouté !');
        }
    }
    public function supprimerSecretaire(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        $validator = Validator::make($request->all(),[
            'id_sec' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $secretaire = Secretaire::find($request->input('id_sec'));
            $secretaire->delete();
            return redirect()->back()->with('success', 'Secretaire Bien Supprimé !');
        }
    }
    public function MettreAjourSecretaireForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        $validator = Validator::make($request->all(),[
            'id_sec' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $secretaire = Secretaire::find($request->input('id_sec'));
            if(is_null($secretaire)){
                return Redirect::back()->withErrors(['Secretaire Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                return view('Medecin.MettreAjourSecretaire',['isAdmin'=>$isAdmin,'secretaire'=>$secretaire]);
            }
        }
    }
    public function mettreAjourSecretaire(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        $id_sec=$request->input('id_sec');
        $validator = Validator::make($request->all(),[
            'id_sec'=>'required',
            'nom' => 'required|min:3|max:255',
            'prenom' => 'required|min:3|max:255',
            'login' => 'required|max:255|unique:secretaires,login,'.$id_sec.','.(New Secretaire)->getKeyName(),
            'email' => 'required|max:255|unique:secretaires,email,'.$id_sec.','.(New Secretaire)->getKeyName(),
            'tel' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $secretaire = Secretaire::find($request->input('id_sec'));
            $secretaire->nom = $request->input('nom');
            $secretaire->prenom = $request->input('prenom');
            $secretaire->login = $request->input('login');
            $secretaire->email = $request->input('email');
            $secretaire->num_tel = $request->input('tel');
            $secretaire->save();
            return redirect()->back()->with('success', 'Secretaire Mis à jour !');
        }
    }
    public function mettreAjourProfil(){
        $id=Auth::user()->id_med;
        $medecin= Medecin::find($id);
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.MettreAjourProfil',['medecin'=>$medecin, 'isAdmin'=>$isAdmin]);
    }

    public function update(Request $request){
        $id_med=$request->input('id_med');
        $validator = Validator::make($request->all(),[
            'id_med'=>'required',
            'nom' => 'required|min:3|max:255',
            'prenom' => 'required|min:3|max:255',
            'email' => 'required|max:255|unique:medecins,email,'.$id_med.','.(New Medecin)->getKeyName(),
            'tel' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $medecin = Medecin::find($request->input('id_med'));
            $medecin->nom = $request->input('nom');
            $medecin->prenom = $request->input('prenom');
            $medecin->email = $request->input('email');
            $medecin->num_tel = $request->input('tel');
            $medecin->save();
            return redirect()->back()->with('success', 'Profil Mis à jour !');
        }
    }

    public function mettreAjourMDPSForm(){
        $id=Auth::user()->id_med;
        $medecin= Medecin::find($id);
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.MettreAjourMDPS',['medecin'=>$medecin, 'isAdmin'=>$isAdmin]);
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

    public function detailsPatient(Request $request){
        $id_pat=$request->input('id_pat');
        //dd($id_pat);
        $validator = Validator::make($request->all(),[
            'id_pat'=>'required',
            'nom' => 'required|min:3|max:255',
            'prenom' => 'required|min:3|max:255',
            'num_ss' => 'required',
            'date_naissance' => 'required',
            'email' => 'required|max:255|unique:patients,email,'.$id_pat.','.(New Patient)->getKeyName(),
            'tel' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            $patient->nom = $request->input('nom');
            $patient->prenom = $request->input('prenom');
            $patient->num_ss = $request->input('num_ss');
            $patient->date_naissance = $request->input('date_naissance');
            $patient->email = $request->input('email');
            $patient->num_tel = $request->input('tel');
            $patient->save();
            return redirect()->back()->with('success', 'Patient Mis à jour !');
        }
    }

    public function detailsPatientForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                return view('Medecin.DetailsPatient',['isAdmin'=>$isAdmin,'patient'=>$patient]);
            }
        }
    }

    public function dossierMedical(Request $request){
        $id_pat=$request->input('id_pat');
        $validator = Validator::make($request->all(),[
            'id_pat'=>'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            $patient->maladies = $request->input('maladies');
            $patient->allergies = $request->input('allergies');
            $patient->antecedents = $request->input('antecedents');
            $patient->commentaires = $request->input('commentaires');
            $patient->save();
            return redirect()->back()->with('success', 'Dossier du Patient Mis à jour !');
        }
    }

    public function dossierMedicalForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                return view('Medecin.DossierMedical',['isAdmin'=>$isAdmin,'patient'=>$patient]);
            }
        }
    }

    public function imageriesForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                $images = Image::all()->where('id_pat',$request->input('id_pat'))->sortByDesc('created_at')->values();
                return view('Medecin.Imageries',['isAdmin'=>$isAdmin,'patient'=>$patient,'images'=>$images]);
            }
        }
    }
    public function ordonnancesForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                $liste_pres_patient = Prescription::all()->where('id_pat',$request->input('id_pat'))->pluck('id_pres');
                $liste_ligne_pres = Ligneprescription::all()->whereIn('id_pres',$liste_pres_patient);
                $prescriptions =  Prescription::orderBy('created_at','DESC')->where('id_pat',$request->input('id_pat'))->get();
                return view('Medecin.Ordonnances',['isAdmin'=>$isAdmin,'patient'=>$patient,'liste_pres'=>$liste_pres_patient,'ligne_pres'=>$liste_ligne_pres,'prescriptions'=>$prescriptions]);
            }
        }
    }
    public function lettresForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                return view('Medecin.Lettres',['isAdmin'=>$isAdmin,'patient'=>$patient]);
            }
        }
    }
    public function reprendreRDVForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                $rdv = Rendezvous::all()->where('id_pat',$request->input('id_pat'));
                $medecins = Medecin::all();
                $res1=[];
                foreach($medecins as $med){
                    $rdv  =  DB::table('rendezvouss')
                        ->join('medecins','rendezvouss.id_med','=','medecins.id_med')
                        ->join('patients','rendezvouss.id_pat','=','patients.id_pat')
                        ->select('rendezvouss.date_rdv','rendezvouss.heure_debut','rendezvouss.heure_fin','rendezvouss.motif')
                        ->where('medecins.id_med',$med->id_med)
                        ->where('rendezvouss.id_pat',$request->input('id_pat'))
                        ->orderBy('rendezvouss.date_rdv', 'asc')
                        ->get();
                    array_push($res1,[$rdv,$med['attributes']]);
                } 
               //dd($res1);
                return view('Medecin.ReprendreRDV',['isAdmin'=>$isAdmin,'patient'=>$patient,'meds'=>$res1,'medecins'=>$medecins]);
            }
        }
    }
    public function reprendreRDV(Request $request){
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
            $RDV = Rendezvous::all()->where('id_med',Auth::user()->id_med);
            $patient = Patient::find($request->input('id_pat'));
            if(count($RDV)==0){//Aucun RDV pour le Medecin Specifié
                $RDV = new Rendezvous();
                $RDV->date_rdv =  $request->input('date_rdv');
                $RDV->heure_debut =  $request->input('heure_deb');
                $RDV->heure_fin =  $request->input('heure_fin');
                $RDV->motif = $request->input('motif');
                $RDV->id_med = Auth::user()->id_med;
                $RDV->id_pat = $request->input('id_pat');
                $RDV->save();
                return redirect()->back()->with('success', 'Rendez-vous Bien Ajouté ');
            }else{
                $RDV = Rendezvous::all()->where('id_med',Auth::user()->id_med)->where('date_rdv',$request->input('date_rdv'))->values();
                if(count($RDV)==0){//Aucun RDV pour Le Medecin Specifié dans la date Specifié
                    $RDV = new Rendezvous();
                    $RDV->date_rdv =  $request->input('date_rdv');
                    $RDV->heure_debut =  $request->input('heure_deb');
                    $RDV->heure_fin =  $request->input('heure_fin');
                    $RDV->motif = $request->input('motif');
                    $RDV->id_med = Auth::user()->id_med;
                    $RDV->id_pat = $request->input('id_pat');
                    $RDV->save();
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
                        $RDV->id_med = Auth::user()->id_med;
                        $RDV->id_pat = $request->input('id_pat');
                        $RDV->save();
                        return redirect()->back()->with('success', 'Rendez-vous Bien Ajouté ');
                    }else{
                        $medecin_rdv = Medecin::find($request->input('id_med'));
                        return Redirect::back()->withErrors(['Vous avez dejà un Rendez-vous le '.$request->input('date_rdv').' de '.$RDV->get($idRdvNonChevauchement)->heure_debut.' à '.$RDV->get($idRdvNonChevauchement)->heure_fin])->withInput();
                    }
                }
            }
        }
    }
    public function ajouterOrdonnance(Request $request){
        $validator = Validator::make($request->all(),[
            'nbr_ligne' => 'required',
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
           $nombre_ligne = $request->input('nbr_ligne')+1;
           $id_patient = $request->input('id_pat');
           /*Ajouter une nouvelle prescription */
           $pres = new Prescription();
           $pres->date_pres = date('Y-m-d');
           $pres->id_med = Auth::user()->id_med;
           $pres->id_pat = $id_patient;
           $pres->save();
           /* Prendre Cette Dernier de cet patient */
           $pres = Prescription::all()->where('date_pres',date('Y-m-d'))->where('id_pat',$id_patient)->last();

           for($i=1;$i<=$nombre_ligne;$i++){
                    if(!is_null($request->input('medicament_'.$i))&&
                        !is_null($request->input('dose_'.$i))&&
                        !is_null($request->input('moment_'.$i))&&
                        !is_null($request->input('duree_'.$i))
                    ){
                        $ligne_pres = new Ligneprescription();
                        $ligne_pres->medicament = $request->input('medicament_'.$i);
                        $ligne_pres->dose = $request->input('dose_'.$i);
                        $ligne_pres->moment = $request->input('moment_'.$i);
                        $ligne_pres->duree = $request->input('duree_'.$i);
                        $ligne_pres->id_pres = $pres->id_pres;
                        $ligne_pres->save();
                    }
           }
            return redirect()->back()->with('success', 'Ordonnance Bien Ajoutée !');
        }
    }
    public function certificatBonneSanteForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                $lettres_bs = Lettre::orderBy('created_at','DESC')->where('type_lettre','certificat de bonne sante')->get();
                return view('Medecin.CertificatBonneSante',['isAdmin'=>$isAdmin,'patient'=>$patient,'lettres'=>$lettres_bs]);
            }
        }
    }
    public function certificatBonneSante(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required',
            'date_lettre'=>'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $nom_med =Auth::user()->nom;
                $prenom_med =Auth::user()->prenom;
                $lettre = new Lettre();
                $lettre->date_lettre = $request->input('date_lettre');
                $lettre->type_lettre = "certificat de bonne sante";
                $lettre->contenu = "Je certifie docteur en Medecine  ".$nom_med." ".$prenom_med." avoir examiné le patient  ".$patient->nom." ".$patient->prenom." née ".$patient->date_naissance.".lequel ne presente aucune alteration de l'etat generale ni aucun symptome cliniquement decelable. il est actuellement en bon état de santé apparante.";
                $lettre->id_med = Auth::user()->id_med;
                $lettre->id_pat = $request->input('id_pat');
                $lettre->save();
                return redirect()->back()->with('success', 'Certificat de bonne santé Bien Ajoutée !');
            }
        }
    }

    public function lettreOrientationForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                $lettres_or = Lettre::orderBy('created_at','DESC')->where('type_lettre','lettre orientation')->get();
                $medecins = Medecin::all()->where('enService',1)->whereNotIn('id_med',Auth::user()->id_med);
                return view('Medecin.LettreOrientation',['isAdmin'=>$isAdmin,'patient'=>$patient,'lettres'=>$lettres_or,'medecins'=>$medecins]);
            }
        }
    }
    public function lettreOrientation(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required',
            'id_med' => 'required',
            'lettre' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $lettre = new Lettre();
                $lettre->date_lettre = date('Y-m-d');
                $lettre->type_lettre = "lettre orientation";
                $lettre->contenu = $request->input('lettre');
                $lettre->id_med = Auth::user()->id_med;
                $lettre->id_pat = $request->input('id_pat');
                $lettre->save();
                if(is_numeric($request->input('id_med'))){
                    /*-Notification--------------------- */
                    $notif = new Notification();
                    $notif->titre = "Nouvelle  Lettre !" ;
                    $notif->contenu = "Votre Camarade Dr.".Auth::user()->nom." ".Auth::user()->prenom." Vous a Envoyé une Lettre d'orientation<br>Concernant le patient : ".Patient::find($request->input('id_pat'))->nom." ".Patient::find($request->input('id_pat'))->prenom."<br>Qui a comme N° Sécurité Sociale : ".Patient::find($request->input('id_pat'))->num_ss;
                    $notif->id_med = $request->input('id_med');
                    $notif->save();
                    /*---------------------------------- */
                }
                return redirect()->back()->with('success', 'Lettre d\'orientation Bien Ajoutée !');
            }
        }
    }
    public function certificatArretTravailForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                $lettres_or = Lettre::orderBy('created_at','DESC')->where('type_lettre','certificat arret travail')->get();
                $medecins = Medecin::all()->whereNotIn('id_med',Auth::user()->id_med);
                return view('Medecin.CertificatArretTravail',['isAdmin'=>$isAdmin,'patient'=>$patient,'lettres'=>$lettres_or,'medecins'=>$medecins]);
            }
        }
    }
    public function certificatArretTravail(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            if(strtotime($request->input('date_debut'))>strtotime($request->input('date_fin'))){
                return Redirect::back()->withErrors(['Incohérence dans les dates !']);
            }else{
                $patient = Patient::find($request->input('id_pat'));
                if(is_null($patient)){
                    return Redirect::back()->withErrors(['Patient Intouvable']);
                }else{
                    $nom_med =Auth::user()->nom;
                    $prenom_med =Auth::user()->prenom;
                    $lettre = new Lettre();
                    $lettre->date_lettre = date('Y-m-d');
                    $lettre->type_lettre = "certificat arret travail";
                    $lettre->contenu = "Je certifie docteur en Medecine  ".$nom_med." ".$prenom_med." avoir examiné le patient  ".$patient->nom." ".$patient->prenom." née ".$patient->date_naissance.", qui présente un(e) et qui nécessite un repos de sauf complication du ".$request->input('date_debut')." au ".$request->input('date_fin');
                    $lettre->id_med = Auth::user()->id_med;
                    $lettre->id_pat = $request->input('id_pat');
                    $lettre->save();
                    return redirect()->back()->with('success', 'Certificat d\'arret de travail Bien Ajoutée !');
                }
            }
        }
    }
    public function certificatPneumoPhtisiologieForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                $lettres_or = Lettre::orderBy('created_at','DESC')->where('type_lettre','certificat pneumo phtisiologie')->get();
                return view('Medecin.CertificatPneumoPhtisiologie',['isAdmin'=>$isAdmin,'patient'=>$patient,'lettres'=>$lettres_or]);
            }
        }

    }
    public function certificatPneumoPhtisiologie(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required',
            'date_lettre'=>'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $nom_med =Auth::user()->nom;
                $prenom_med =Auth::user()->prenom;
                $lettre = new Lettre();
                $lettre->date_lettre = $request->input('date_lettre');
                $lettre->type_lettre = "certificat pneumo phtisiologie";
                $lettre->contenu = "Je certifie docteur en Medecine  ".$nom_med." ".$prenom_med." avoir examiné le patient  ".$patient->nom." ".$patient->prenom." née ".$patient->date_naissance.". lequel ne presente aucun signe de tuberculose pulmonaire et radiologique evolutive.";
                $lettre->id_med = Auth::user()->id_med;
                $lettre->id_pat = $request->input('id_pat');
                $lettre->save();
                return redirect()->back()->with('success', 'Certificat de Pneumo Phtisiologie Bien Ajoutée !');
            }
        }
    }

    public function certificatRepriseTravailForm(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                $lettres_or = Lettre::orderBy('created_at','DESC')->where('type_lettre','certificat reprise travail')->get();
                return view('Medecin.CertificatRepriseTravail',['isAdmin'=>$isAdmin,'patient'=>$patient,'lettres'=>$lettres_or]);
            }
        }

    }
    public function certificatRepriseTravail(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required',
            'date_lettre'=>'required',
            'date_rep'=>'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            if(is_null($patient)){
                return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                $nom_med =Auth::user()->nom;
                $prenom_med =Auth::user()->prenom;
                $lettre = new Lettre();
                $lettre->date_lettre = $request->input('date_lettre');
                $lettre->type_lettre = "certificat reprise travail";
                $lettre->contenu = "Je certifie docteur en Medecine  ".$nom_med." ".$prenom_med." avoir examiné le patient  ".$patient->nom." ".$patient->prenom." née ".$patient->date_naissance.". lui permet de reprendre son travail a compter du ".$request->input('date_rep')." Fait le ".$request->input('date_lettre');
                $lettre->id_med = Auth::user()->id_med;
                $lettre->id_pat = $request->input('id_pat');
                $lettre->save();
                return redirect()->back()->with('success', 'Certificat de reprise Bien Ajoutée !');

            }
        }
    }
    public function nombreDeNotification(){
        $nbr_notif = count(Notification::all()->where('id_med',Auth::user()->id_med));
        $notifs = Notification::where('id_med',Auth::user()->id_med)->orderBy('created_at','DESC')->limit(3)->get();
        $result = array($nbr_notif,$notifs);
        return $result;
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
            $notif->contenu = "Le Rendez-vous du ".$RDV_Sauvegarde->date_rdv." de ".$RDV_Sauvegarde->heure_debut." a ".$RDV_Sauvegarde->heure_debut." est Annulé<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
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

    public function detailsMedecin(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(!$isAdmin){
            return redirect(route('medecin.dashboard'));
        }
        $validator = Validator::make($request->all(),[
            'id_med' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $medecin = Medecin::find($request->input('id_med'));
            if(is_null($medecin)){
                return redirect(route('medecin.listeMedecins'))->withErrors(['Medecin Introuvable !']);
            }else{
                $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
                $rdvs = Rendezvous::all()->where('id_med',$request->input('id_med'));
                $medecins = Medecin::all();
                return view('Medecin.DetailsMedecin',['isAdmin'=>$isAdmin,'medecin'=>$medecin,'rdvs'=>$rdvs,'medecins'=>$medecins]);
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
            if(count($RDV)==0){//Aucun RDV pour le Medecin Specifié
                $RDV = new Rendezvous();
                $RDV->date_rdv =  $request->input('date_rdv');
                $RDV->heure_debut =  $request->input('heure_deb');
                $RDV->heure_fin =  $request->input('heure_fin');
                $RDV->motif = $request->input('motif');
                $RDV->id_med = $request->input('id_med');
                $RDV->id_pat = (Patient::all()->where('num_ss',$request->input('num_ss'))->first())->id_pat;
                $RDV->save();
                /*-Notification--------------------- */
                $notif = new Notification();
                $notif->titre = "Nouveau Rendez-vous !" ;
                $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Medecin Responsable : ".Auth::user()->nom." ".Auth::user()->prenom;
                $notif->id_med = $request->input('id_med');
                $notif->save();
                /*---------------------------------- */
                return redirect()->back()->with('success', 'Patient avec son Rendez-vous Bien Ajoutés ');
            }else{
                $RDV = Rendezvous::all()->where('id_med',$request->input('id_med'))->where('date_rdv',$request->input('date_rdv'))->values();
                if(count($RDV)==0){//Aucun RDV pour Le Medecin Specifié dans la date Specifié
                    $RDV = new Rendezvous();
                    $RDV->date_rdv =  $request->input('date_rdv');
                    $RDV->heure_debut =  $request->input('heure_deb');
                    $RDV->heure_fin =  $request->input('heure_fin');
                    $RDV->motif = $request->input('motif');
                    $RDV->id_med = $request->input('id_med');
                    $RDV->id_pat = (Patient::all()->where('num_ss',$request->input('num_ss'))->first())->id_pat;
                    $RDV->save();
                    /*-Notification--------------------- */
                    $notif = new Notification();
                    $notif->titre = "Nouveau Rendez-vous !";
                    $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Medecin Responsable : ".Auth::user()->nom." ".Auth::user()->prenom;
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
                        $RDV->id_pat = (Patient::all()->where('num_ss',$request->input('num_ss'))->first())->id_pat;
                        $RDV->save();
                        /*-Notification--------------------- */
                        $notif = new Notification();
                        $notif->titre = "Nouveau Rendez-vous !";
                        $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." à ".$request->input('heure_fin')."<br>Medecin Responsable : ".Auth::user()->nom." ".Auth::user()->prenom;
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
    public function MAJLignePres(Request $request){
        $validator = Validator::make($request->all(),[
           'id_ligne_pres'=>'required',
           'medicament'=>'required',
           'dose'=>'required',
           'moment'=>'required',
           'duree'=>'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $ligne_pres =  Ligneprescription::find($request->input('id_ligne_pres'));
            if(Prescription::find($ligne_pres->id_pres)->id_med != Auth::user()->id_med){
                return Redirect::back()->withErrors(['Vous n\'etes pas autorisé a effectuer cette action !']);
            }else{
                $ligne_pres->medicament = $request->input('medicament');
                $ligne_pres->dose = $request->input('dose');
                $ligne_pres->moment =$request->input('moment');
                $ligne_pres->duree = $request->input('duree');
                $ligne_pres->save();
                return Redirect::back()->with('success', 'Ligne Prescription Bien Modifiée!');
            }
        }
    }
    public function SuppLignePres(Request $request){
        $validator = Validator::make($request->all(),[
            'id_ligne_pres'=>'required',
         ]);
         if ($validator->fails()) {
             return Redirect::back()->withErrors($validator);
         }else{
            $ligne_pres =  Ligneprescription::find($request->input('id_ligne_pres'));
            if(Prescription::find($ligne_pres->id_pres)->id_med != Auth::user()->id_med){
                return Redirect::back()->withErrors(['Vous n\'etes pas autorisé a effectuer cette action !']);
            }else{
                if($ligne_pres->delete()){
                    return Redirect::back()->with('success', 'Ligne Prescription Bien Supprimée!');
                }else{
                    return Redirect::back()->withErrors(['Impossible de Supprimer La ligne']);
                }
            }
         }
    }
    public function AjouterLignePres(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pres'=>'required',
            'medicament'=>'required',
            'dose'=>'required',
            'moment'=>'required',
            'duree'=>'required'
         ]);
         if ($validator->fails()) {
             return Redirect::back()->withErrors($validator);
         }else{
             $pres = Prescription::find($request->input('id_pres'));
             if(is_null($pres)){
                return Redirect::back()->withErrors(['Prescription Intouvable']);
             }else{
                if($pres->id_med != Auth::user()->id_med){
                    return Redirect::back()->withErrors(['Vous n\'etes pas autorisé a effectuer cette action !']);
                }else{
                    $ligne = new Ligneprescription();
                    $ligne->medicament = $request->input('medicament');
                    $ligne->dose = $request->input('dose');
                    $ligne->moment = $request->input('moment');
                    $ligne->duree = $request->input('duree');
                    $ligne->id_pres = $request->input('id_pres');
                    $ligne->save();
                    return Redirect::back()->with('success', 'Ligne Prescription Bien Ajoutée!');
                }
             }
         }
    }
    public function SupprimerLettre(Request $request){
        $validator = Validator::make($request->all(),[
            'id_lettre'=>'required',
         ]);
         if ($validator->fails()) {
             return Redirect::back()->withErrors($validator);
         }else{
            $lettre = Lettre::find($request->input('id_lettre'));
            if(!is_null($lettre)){
                if(Auth::user()->id_med==$lettre->id_med){
                    if($lettre->delete()){
                        return Redirect::back()->with('success', 'Lettre Bien Supprimée!');
                    }else{
                        return Redirect::back()->withErrors(['Impossible de Supprimer La Lettre !']);
                    }
                }else{
                    return Redirect::back()->withErrors(['Vous n\'avez par l\'autorisation d\'effectuer cette action !']);
                }
            }else{
                return Redirect::back()->withErrors(['Lettre Intouvable!']);
            }
         }
    }
    public function MAJCertificatBS(Request $request){
        $validator = Validator::make($request->all(),[
            'id_lettre'=>'required',
            'date_lettre'=>'required'
         ]);
         if ($validator->fails()) {
             return Redirect::back()->withErrors($validator);
         }else{
            $lettre = Lettre::find($request->input('id_lettre'));
            if(!is_null($lettre)){
                if(Auth::user()->id_med==$lettre->id_med){
                   $lettre->date_lettre = $request->input('date_lettre');
                   $lettre->save();
                   return Redirect::back()->with('success', 'Certificat de Bonne Santé Bien Modifiée !');
                }else{
                    return Redirect::back()->withErrors(['Vous n\'avez par l\'autorisation d\'effectuer cette action !']);
                }
            }else{
                return Redirect::back()->withErrors(['Lettre Intouvable!']);
            }
         }
    }
    public function MAJCertificatPP(Request $request){
        $validator = Validator::make($request->all(),[
            'id_lettre'=>'required',
            'date_lettre'=>'required'
         ]);
         if ($validator->fails()) {
             return Redirect::back()->withErrors($validator);
         }else{
            $lettre = Lettre::find($request->input('id_lettre'));
            if(!is_null($lettre)){
                if(Auth::user()->id_med==$lettre->id_med){
                   $lettre->date_lettre = $request->input('date_lettre');
                   $lettre->save();
                   return Redirect::back()->with('success', 'Certificat de Pneumo Phtisiologie Bien Modifiée !');
                }else{
                    return Redirect::back()->withErrors(['Vous n\'avez par l\'autorisation d\'effectuer cette action !']);
                }
            }else{
                return Redirect::back()->withErrors(['Lettre Intouvable!']);
            }
         }
    }
    public function MAJCertificatRT(Request $request){
        $validator = Validator::make($request->all(),[
            'id_lettre'=>'required',
            'date_lettre'=>'required',
            'contenu'=>'required'
         ]);
         if ($validator->fails()) {
             return Redirect::back()->withErrors($validator);
         }else{
            $lettre = Lettre::find($request->input('id_lettre'));
            if(!is_null($lettre)){
                if(Auth::user()->id_med==$lettre->id_med){
                   $lettre->date_lettre = $request->input('date_lettre');
                   $lettre->contenu = $request->input('contenu');
                   $lettre->save();
                   return Redirect::back()->with('success', 'Certificat de Reprise Bien Modifiée !');
                }else{
                    return Redirect::back()->withErrors(['Vous n\'avez par l\'autorisation d\'effectuer cette action !']);
                }
            }else{
                return Redirect::back()->withErrors(['Lettre Intouvable!']);
            }
         }
        
    }
    public function MAJCertificatAT(Request $request){
        $validator = Validator::make($request->all(),[
            'id_lettre'=>'required',
            'contenu'=>'required'
         ]);
         if ($validator->fails()) {
             return Redirect::back()->withErrors($validator);
         }else{
            $lettre = Lettre::find($request->input('id_lettre'));
            if(!is_null($lettre)){
                if(Auth::user()->id_med==$lettre->id_med){
                   $lettre->date_lettre = date('Y-m-d');
                   $lettre->contenu = $request->input('contenu');
                   $lettre->save();
                   return Redirect::back()->with('success', 'Certificat d\'arret de Travail Bien Modifiée !');
                }else{
                    return Redirect::back()->withErrors(['Vous n\'avez par l\'autorisation d\'effectuer cette action !']);
                }
            }else{
                return Redirect::back()->withErrors(['Lettre Intouvable!']);
            }
         }
        
    }
    public function MAJLettreOrientation(Request $request){
        $validator = Validator::make($request->all(),[
            'id_lettre'=>'required',
            'contenu'=>'required'
         ]);
         if ($validator->fails()) {
             return Redirect::back()->withErrors($validator);
         }else{
            $lettre = Lettre::find($request->input('id_lettre'));
            if(!is_null($lettre)){
                if(Auth::user()->id_med==$lettre->id_med){
                   $lettre->date_lettre =date('Y-m-d');
                   $lettre->contenu = $request->input('contenu');
                   $lettre->save();
                   return Redirect::back()->with('success', 'Lettre Orientation Bien Modifiée !');
                }else{
                    return Redirect::back()->withErrors(['Vous n\'avez par l\'autorisation d\'effectuer cette action !']);
                }
            }else{
                return Redirect::back()->withErrors(['Lettre Intouvable!']);
            }
         }
        
    }
    public function SupprimerOrdonnance(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pres'=>'required',
         ]);
         if ($validator->fails()) {
             return Redirect::back()->withErrors($validator);
         }else{
            $pres = Prescription::find($request->input('id_pres'));
            if(!is_null($pres)){
                if(Auth::user()->id_med==$pres->id_med){
                    if($pres->delete()){
                        return Redirect::back()->with('success', 'Ordonnance Bien Supprimée!');
                    }else{
                        return Redirect::back()->withErrors(['Impossible de Supprimer L\'Ordonnance !']);
                    }
                }else{
                    return Redirect::back()->withErrors(['Vous n\'avez par l\'autorisation d\'effectuer cette action !']);
                }
            }else{
                return Redirect::back()->withErrors(['Ordonnance Intouvable!']);
            }
         }
    }
    public function suppimg(Request $request){
        $validator = Validator::make($request->all(),[
            'id_img'=>'required',
            'id_pat'=>'required',
         ]);
         if ($validator->fails()) {
             return Redirect::back()->withErrors($validator);
         }else{
            $patient = Patient::find($request->input('id_pat'));
            $image = Image::find($request->input('id_img'));
            if($image->delete()){
               return Redirect::back()->with('success', 'Image Bien Supprimée !');
            }else{
               return Redirect::back()->withErrors(['Impossible de supprimer l\'image !']);
            }
         }   
    }
}
