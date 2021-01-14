<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Redirect;
use Auth;
use Hash;
use App\Secretaire;

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
    /////////////////////////////////////////////////////////////////
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
}
