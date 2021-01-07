<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MedecinController extends Controller
{
    public function __construct(){
        $this->middleware('auth:medecin');
    }
    public function index(){
        return view('Medecin.dash');
    }
}
