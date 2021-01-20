<?php

namespace App\Http\Controllers;

use Validator;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Image;
use App\Patient;
class ImageController extends Controller
{
    public function AjouterImages(Request $request){
        $validator = Validator::make($request->all(),[
            'id_pat' => 'required',
            'imageries' => 'required',
            'imageries.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            $patient = Patient::find($request->input('id_pat'));
            
            foreach($request->file('imageries')  as $img){
               $image = new Image();
               $image->nom = explode('.',$img->getClientOriginalName())[0];
               $image->format = $img->extension();
               $image->date_img = date('y-m-d');
               $image->id_pat = $request->input('id_pat');
               if($image->save()){
                    $img->storeAs('public/images/imageries_patient/'.$patient->num_ss,$img->getClientOriginalName());
               }else{
                    return Redirect::back()->withErrors(['Impossible d\'ajouter l\'image : '.explode('.',$img->getClientOriginalName())[0]]);
               }
            }
            return redirect()->back()->with('success', 'Images Bien Ajouter');
            
        }
    }
}
