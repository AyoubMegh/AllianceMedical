<?php

namespace App\Http\Controllers;

use Validator;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Clinique;
use App\Medecin;
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
        return view('Medecin.ModifierSecretaire',['isAdmin'=>$isAdmin]);
    }
    public function supprimerSecretaireForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.SupprimerSecretaire',['isAdmin'=>$isAdmin]);
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
}
