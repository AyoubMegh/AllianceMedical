<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class SecretaireLoginContoller extends Controller
{
    public function __construct(){
        $this->middleware('guest:secretaire',['except'=>['logout']]);
    }
    public function showLoginForm(){
        return view('auth.secretaireLogin');
    }
    public function login(Request $request){
        $this->validate($request,[
            'login' => 'required',
            'password' =>'required'
        ]);
        //remember
        if(Auth::guard('secretaire')->attempt([ 'login'=> $request->login ,'password'=> $request->password ],$request->remember)){
            return redirect()->intended(route('secretaire.dashboard'));
        }
        return redirect()->back()->withErrors(['Login ou mot de passe incorrecte !'])->withInput($request->only('login','remember'));

    }
    public function logout(Request $request){
        Auth::guard('secretaire')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('/');
    }
}
