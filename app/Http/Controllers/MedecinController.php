<?php

namespace App\Http\Controllers;

use Validator;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
        return view('Medecin.Statistiques',['isAdmin'=>$isAdmin]);
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
                return redirect()->back()->with('success', 'Notification Supprimé !');
            }else{
                return redirect(route('medecin.notifications'))->withErrors(['Impossible de Supprimer La notification !']);
            }
        }
        
    }
    public function listePatients(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(is_null($request->input('num_ss'))){
            $patients = Patient::all();
        }else{
            $patients = Patient::all()->where('num_ss',$request->input('num_ss'));
            if(count($patients)==0){
                return redirect(route('medecin.listePatients'))->withErrors(['Patient Introuvable !']);
            }
        }
        return view('Medecin.ListePatients',['isAdmin'=>$isAdmin,'patients'=>$patients]);
    }

    public function listeMedecins(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        if(is_null($request->input('nom_med'))){
            $medecins = Medecin::all()->whereNotIn('id_med',Auth::user()->id_med);
        }else{
            $medecins = Medecin::all()->where('nom',$request->input('nom_med'));
            if(count($medecins)==0){
                return redirect(route('medecin.listeMedecins'))->withErrors(['Medecin Introuvable !']);
            }
        }
        return view('Medecin.ListeMedecins',['isAdmin'=>$isAdmin,'medecins'=>$medecins]);
    }
    public function listeSecretaires(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
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
        return view('Medecin.AjouterMedecin',['isAdmin'=>$isAdmin]);
    }

    public function ajouterSecretaireForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.AjouterSecretaire',['isAdmin'=>$isAdmin]);
    }

    public function ajouterMedecin(Request $request){
        $validator = Validator::make($request->all(),[
            'nom' => 'required|min:3|max:255',
            'prenom' => 'required|min:3|max:255',
            'login' => 'required|unique:medecins|max:255',
            'email' => 'required|unique:medecins|max:255',
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
        $validator = Validator::make($request->all(),[
            'id_med' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $medecin = Medecin::find($request->input('id_med'));
            $medecin->delete();
            return redirect()->back()->with('success', 'Medecin Bien Supprimé !');
        }
    }
    public function MettreAjourMedecinForm(Request $request){
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
            return redirect()->back()->with('success', 'Medecin Mis a jour !');
        }
    }

    public function ajouterSecretaire(Request $request){
        $validator = Validator::make($request->all(),[
            'nom' => 'required|min:3|max:255',
            'prenom' => 'required|min:3|max:255',
            'login' => 'required|unique:medecins|max:255',
            'email' => 'required|unique:medecins|max:255',
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
            return redirect()->back()->with('success', 'Secretaire Mis a jour !');
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
            return redirect()->back()->with('success', 'Patient Mis a jour !');
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
                $images = Image::all()->where('id_pat',$request->input('id_pat'))->sortByDesc('created_at');
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
                $prescriptions =  Prescription::all()->where('id_pat',$request->input('id_pat'));
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
                return view('Medecin.ReprendreRDV',['isAdmin'=>$isAdmin,'patient'=>$patient,'meds'=>$res1]);
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
                return Redirect::back()->withErrors(['La fin du RDV neut peut pas etre avant le Debut, Veuillez verifier vos heures !'])->withInput();
            }
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
                        return Redirect::back()->withErrors(['Vous a deja un Rendez-vous le '.$request->input('date_rdv').' de '.$RDV->get($idRdvNonChevauchement)->heure_debut.' a '.$RDV->get($idRdvNonChevauchement)->heure_fin])->withInput();
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
            return redirect()->back()->with('success', 'Ordonnance Bien Ajouté !');
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
                $lettres_bs = Lettre::all()->where('type_lettre','certificat de bonne sante');
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
                return redirect()->back()->with('success', 'Certificat de bonne santé Bien Ajouté !');
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
                $lettres_or = Lettre::all()->where('type_lettre','lettre orientation');
                $medecins = Medecin::all()->whereNotIn('id_med',Auth::user()->id_med);
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
                return redirect()->back()->with('success', 'Lettre d\'orientation Bien Ajouté !');
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
                $lettres_or = Lettre::all()->where('type_lettre','certificat arret travail');
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
                    return redirect()->back()->with('success', 'Certificat d\'arret de travail Bien Ajouté !');
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
                $lettres_or = Lettre::all()->where('type_lettre','certificat pneumo phtisiologie');
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
                return redirect()->back()->with('success', 'Certificat de Pneumo Phtisiologie Bien Ajouté !');
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
                $lettres_or = Lettre::all()->where('type_lettre','certificat reprise travail');
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
                return redirect()->back()->with('success', 'Certificat de reprise Bien Ajouté !');

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
            return redirect()->back()->with('success', 'RDV Bien Supprimé !');
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
                return Redirect::back()->withErrors(['La fin du RDV neut peut pas etre avant le Debut, Veuillez verifier vos heures !'])->withInput();
            }
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
                    $notif->contenu = "L'ancien Rendez-vous du ".$RDV_sauvegarde->date_rdv." de ".$RDV_sauvegarde->heure_debut." a ".$RDV_sauvegarde->heure_fin."<br>est fixé pour le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                 }else{
                     /* Notif pour l'autre Medecin */
                     $nv_notif = new Notification();
                     $nv_notif->titre = "Nouveau Rendez-vous !" ;
                     $nv_notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                     $nv_notif->id_med = $request->input('id_med');
                     $nv_notif->save();
                     /*--------------------------- */
                    $notif->titre = "Rendez-vous Annuler !" ;
                    $notif->contenu = "Le Rendez-vous du ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')." est Annulé<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                 }
                 $notif->id_med = $RDV_sauvegarde->id_med;
                 $notif->save();
                 /*---------------------------------- */
                return redirect()->back()->with('success', 'Rendez-Vous Mis a Jour');
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
                        $nv_notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                        $nv_notif->id_med = $request->input('id_med');
                        $nv_notif->save();
                        /*--------------------------- */
                       $notif->titre = "Rendez-vous Annuler !" ;
                       $notif->contenu = "Le Rendez-vous du ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')." est Annulé<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                    }
                    $notif->id_med = $RDV_sauvegarde->id_med;
                    $notif->save();
                    /*---------------------------------- */
                    return redirect()->back()->with('success', 'Rendez-Vous Mis a Jour');
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
                        $notif->contenu = "L'ancien Rendez-vous du ".$RDV_sauvegarde->date_rdv." de ".$RDV_sauvegarde->heure_debut." a ".$RDV_sauvegarde->heure_fin."<br>est fixé pour le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                        }else{
                            /* Notif pour l'autre Medecin */
                            $nv_notif = new Notification();
                            $nv_notif->titre = "Nouveau Rendez-vous !" ;
                            $nv_notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')."<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                            $nv_notif->id_med = $request->input('id_med');
                            $nv_notif->save();
                            /*--------------------------- */
                        $notif->titre = "Rendez-vous Annuler !" ;
                        $notif->contenu = "Le Rendez-vous du ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')." est Annulé<br>Secretaire : ".Auth::user()->nom." ".Auth::user()->prenom;
                        }
                        $notif->id_med = $RDV_sauvegarde->id_med;
                        $notif->save();
                        /*---------------------------------- */
                        return redirect()->back()->with('success', 'Rendez-Vous Mis a Jour');
                    }else{
                        $medecin_rdv = Medecin::find($request->input('id_med'));
                        return Redirect::back()->withErrors(['Dr '.$medecin_rdv->nom.' '.$medecin_rdv->prenom.' a deja un Rendez-vous le '.$request->input('date_rdv').' de '.$RDV->get($idRdvNonChevauchement)->heure_debut.' a '.$RDV->get($idRdvNonChevauchement)->heure_fin])->withInput();
                    }
                }
            }
        }
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
                return Redirect::back()->withErrors(['La fin du RDV neut peut pas etre avant le Debut, Veuillez verifier vos heures !'])->withInput();
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
                $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')."<br>Medecin Responsable : ".Auth::user()->nom." ".Auth::user()->prenom;
                $notif->id_med = $request->input('id_med');
                $notif->save();
                /*---------------------------------- */
                return redirect()->back()->with('success', 'Patient Et R.D.V Bien Ajouté ');
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
                    $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')."<br>Medecin Responsable : ".Auth::user()->nom." ".Auth::user()->prenom;
                    $notif->id_med = $request->input('id_med');
                    $notif->save();
                    /*---------------------------------- */
                    return redirect()->back()->with('success', 'Patient Et R.D.V Bien Ajouté ');
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
                        $notif->contenu = "Vous avez un nouveau rendez-vous le ".$request->input('date_rdv')." de ".$request->input('heure_deb')." a ".$request->input('heure_fin')."<br>Medecin Responsable : ".Auth::user()->nom." ".Auth::user()->prenom;
                        $notif->id_med = $request->input('id_med');
                        $notif->save();
                        /*---------------------------------- */
                        return redirect()->back()->with('success', 'Patient Et R.D.V Bien Ajouté ');
                    }else{
                        $patient_deja_eng = Patient::all()->where('num_ss',$request->input('num_ss'))->first()->delete();
                        $medecin_rdv = Medecin::find($request->input('id_med'));
                        return Redirect::back()->withErrors(['Dr '.$medecin_rdv->nom.' '.$medecin_rdv->prenom.' a deja un Rendez-vous le '.$request->input('date_rdv').' de '.$RDV->get($idRdvNonChevauchement)->heure_debut.' a '.$RDV->get($idRdvNonChevauchement)->heure_fin])->withInput();
                    }
                }
            }
        }
    }
    
}
