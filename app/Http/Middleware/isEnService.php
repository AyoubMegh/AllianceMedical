<?php

namespace App\Http\Middleware;

use Closure;
use App\Medecin;

class isEnService
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!is_null($request->input('login'))){
            $medecin = Medecin::all()->where('login',$request->input('login'))->values();
            if(count($medecin)==1){
                if($medecin->get(0)->enService == 1){
                    return $next($request);
                }else{
                    return redirect(route('medecin.login'))->withErrors(['Vous ne faites plus partie de l\'organisation !']);
                }
            }else{
                return redirect(route('medecin.login'))->withErrors(['Login Introuvable !']);
            }
        }else{
            return redirect(route('medecin.login'))->withErrors(['Champ Login Obligatoire']);
        }
        
    }
}
