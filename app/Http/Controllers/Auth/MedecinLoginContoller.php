<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class MedecinLoginContoller extends Controller
{
    public function __construct(){
        $this->middleware('guest:medecin',['except'=>['logout']]);
    }
    public function showLoginForm(){
        return view('auth.medecinLogin');
    }
    public function login(Request $request){
        $this->validate($request,[
            'login' => 'required',
            'password' =>'required'
        ]);
        //remember
        if(Auth::guard('medecin')->attempt([ 'login'=> $request->login ,'password'=> $request->password ],$request->remember)){
            return redirect()->intended(route('medecin.dashboard'));
        }
        return redirect()->back()->withInput($request->only('login','remember'));
        
    }
    public function logout(Request $request){
        Auth::guard('medecin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('/');
    }
}
