<?php

namespace App\Http\Controllers;

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
        return view('Medecin.ModifierMedecin',['isAdmin'=>$isAdmin]);
    }
    public function supprimerMedecinForm(Request $request){
        $isAdmin = Auth::user()->id_med==Clinique::find(1)->id_med_res;
        return view('Medecin.SupprimerMedecin',['isAdmin'=>$isAdmin]);
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
}
