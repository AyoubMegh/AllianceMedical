<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Medecin;
use App\Secretaire;
use Hash;
use Mail;


class ForgotPasswordController extends Controller
{
    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email'  => 'required|email',
        ]);
        if($validator->fails()){
            if(isset($failedRules['email'])){
                if(isset($failedRules['email']['Required'])){
                    return redirect()->back()->withErrors(['E-Mail manquant!']);
                }else{
                    return redirect()->back()->withErrors(['Problème dans l\'e-mail!']);
                }
            }else{
                return redirect()->back()->withErrors(['Impossible de réinitialiser votre Mot de Passe !']);
            }
        }else{
            $email = $request->input('email');
            $query = null;

            if(!is_null(Medecin::all()->where('email',$email)->first())){
                $query=Medecin::all()->where('email',$email)->first();
            }
            if(!is_null(Secretaire::all()->where('email',$email)->first())){
                $query=Secretaire::all()->where('email',$email)->first();
            }

            if($query==null){
                return redirect()->back()->withErrors(['Compte Introuvable!']);
            }
            else{
                $nvPass = (string) $this->generateurMotDePasse(16,0); // taille de 16
                $date = date('Y/m/d H:i:s');
                $message =  "Vous avez demandé une réinitialisation du mot de passe de votre compte le : ".$date."\n";
                $message .= "Votre Nouveau Mot de Passe : ".$nvPass;
                $query->password = bcrypt($nvPass);
                $query->save();
                Mail::raw($message,function($mail) use ($query){
                    $mail->from('Alliance.Medical.Mail@gmail.com','Alliance Medicale');
                    $mail->to($query->email,$query->nom.' '.$query->prenom)->subject('réinitialisation de votre mot de passe');
                });
                return redirect()->back()->with('success',"Mot de Passe Bien Réinitialisé veuillez consulter votre boite mail");
            }   
        }
    }
    function generateurMotDePasse($length, $bitmask) {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special  = '~!@#$%^&*(){}[],./?';   
        $characters = '';
        if ($bitmask & 1) {
          $characters .= $uppercase;
        }      
        if ($bitmask & 2) {
          $characters .= $lowercase;
        }
        if ($bitmask & 4) {
          $characters .= $numbers;
        }
        if ($bitmask & 8) {
          $characters .= $special;
        }
        if (!$characters) {
          $characters = $uppercase . $lowercase;
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
