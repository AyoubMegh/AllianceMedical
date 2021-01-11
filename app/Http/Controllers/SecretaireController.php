<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SecretaireController extends Controller
{
    public function __construct(){
        $this->middleware('auth:secretaire');
    }
    public function index(){
        return view('Secretaire.dash');
    }
    public function listePatients(Request $request){
        return view('Secretaire.ListePatients');
    }
    public function prendreRDV(Request $request){
        return view('Secretaire.PrendreRDV');
    }
    public function mettreAjourRDV(Request $request){
        return view('Secretaire.MettreAjourRDV');
    }
    public function annulerRDV(Request $request){
        return view('Secretaire.AnnulerRDV');
    }
    public function visualisationRDV(Request $request){
        return view('Secretaire.VisualisationRDV');
    }
    public function detailsPatient(Request $request){
        return view('Secretaire.DetailsPatient');
    }
    public function reprendreRDV(Request $request){
        return view('Secretaire.ReprendreRDV');
    }
}
