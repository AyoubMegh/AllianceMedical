<?php

namespace App\Http\Controllers;

use Validator;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use App\Clinique;
use App\Medecin;
use App\Secretaire;
use Auth;


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
    public function listePatients(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.ListePatients',['isAdmin'=>$isAdmin]);
    }
    public function etablireDossierPatient(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.etablireDossierPatient',['isAdmin'=>$isAdmin]);
    }
    public function prendreRDV(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.PrendreRDV',['isAdmin'=>$isAdmin]);
    }
    public function etablireOrdonnance(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.EtablireOrdonnance',['isAdmin'=>$isAdmin]);
    }
    public function visualisationPatient(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.VisualisationPatient',['isAdmin'=>$isAdmin]);
    }
    public function visualisationPrescription(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.VisualisationPrescription',['isAdmin'=>$isAdmin]);
    }
    public function ajouterMedecinForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.AjouterMedecin',['isAdmin'=>$isAdmin]);
    }
    public function modifierMedecinForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        $listeMedecins = Medecin::all()->whereNotIn('id_med',Auth::user()->id_med);
        return view('Medecin.ModifierMedecin',['isAdmin'=>$isAdmin,'medecins'=>$listeMedecins]);
    }
    public function supprimerMedecinForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        $listeMedecins = Medecin::all()->whereNotIn('id_med',Auth::user()->id_med);
        return view('Medecin.SupprimerMedecin',['isAdmin'=>$isAdmin,'medecins'=>$listeMedecins]);
    }
    public function ajouterSecretaireForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.AjouterSecretaire',['isAdmin'=>$isAdmin]);
    }
    public function modifierSecretaireForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        $listeSecretaires = Secretaire::all();
        return view('Medecin.ModifierSecretaire',['isAdmin'=>$isAdmin,'secretaires'=>$listeSecretaires]);
    }
    public function supprimerSecretaireForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        $listeSecretaires = Secretaire::all();
        return view('Medecin.SupprimerSecretaire',['isAdmin'=>$isAdmin,'secretaires'=>$listeSecretaires]);
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
            $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
            return view('Medecin.MettreAjourMedecin',['isAdmin'=>$isAdmin,'medecin'=>$medecin]);
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
            $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
            return view('Medecin.MettreAjourSecretaire',['isAdmin'=>$isAdmin,'secretaire'=>$secretaire]);
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
}
