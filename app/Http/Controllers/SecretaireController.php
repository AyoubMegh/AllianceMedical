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
use Auth;


class SecretaireController extends Controller
{
    public function __construct(){
        $this->middleware('auth:secretaire');
    }
    public function index(){
        return view('Secretaire.dash');
    }
    public function listePatients(Request $request){
        $patients = Patient::all();
        return view('Secretaire.ListePatients',['patients'=>$patients]);
    }
    public function prendreRDV(Request $request){
        $medecins = Medecin::all();
        return view('Secretaire.PrendreRDV',['medecins'=>$medecins]);
    }
    public function mettreAjourRDV(Request $request){
        return view('Secretaire.MettreAjourRDV');
    }
    public function annulerRDV(Request $request){
        $patients = Patient::all();
        return view('Secretaire.AnnulerRDV',['patients'=>$patients]);
    }
    public function visualisationRDV(Request $request){
        return view('Secretaire.VisualisationRDV');
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
               return Redirect::back()->withErrors(['Patient Intouvable']);
            }else{
                return view('Secretaire.DetailsPatient',['patient'=>$patient]);
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
                $medecins = Medecin::all();
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
                    $RDV->id_sec = $id_sec;
                    $RDV->id_pat = (Patient::all()->where('num_ss',$request->input('num_ss'))->first())->id_pat;
                    $RDV->save();
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
                        $RDV->id_sec = $id_sec;
                        $RDV->id_pat = (Patient::all()->where('num_ss',$request->input('num_ss'))->first())->id_pat;
                        $RDV->save();
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
    public function ajouterAutreRDV(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required',
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
                return redirect()->back()->with('success', 'R.D.V Bien Ajouté ');
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
                    return redirect()->back()->with('success', 'R.D.V Bien Ajouté ');
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
                        return redirect()->back()->with('success', 'R.D.V Bien Ajouté ');
                    }else{
                        $medecin_rdv = Medecin::find($request->input('id_med'));
                        return Redirect::back()->withErrors(['Dr '.$medecin_rdv->nom.' '.$medecin_rdv->prenom.' a deja un Rendez-vous le '.$request->input('date_rdv').' de '.$RDV->get($idRdvNonChevauchement)->heure_debut.' a '.$RDV->get($idRdvNonChevauchement)->heure_fin])->withInput();
                    }
                }
            }
        }
    }
    public function listeRDVaAnnuler(Request $request){
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
                $rdv_medecin_sec = DB::table('rendezvouss')
                                    ->join('medecins','rendezvouss.id_med','=','medecins.id_med')
                                    ->select('rendezvouss.*','medecins.nom','medecins.prenom')
                                    ->orderBy('rendezvouss.date_rdv', 'desc')
                                    ->get();
                return view('Secretaire.ListeRDVaAnnuler',['details'=>$rdv_medecin_sec]);
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
            $RDV->delete();
            return redirect()->back()->with('success', 'RDV Bien Supprimé !');
        }
    }
}
