<?php

use Illuminate\Database\Seeder;
use App\Medecin;
use App\Clinique;
class MedecinsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $medecin = new Medecin();
        $medecin->id_med= 1;
        $medecin->nom = 'BENFLEN';
        $medecin->prenom = 'Flen';
        $medecin->num_tel = '+33123456789';
        $medecin->login = 'flen.benflen';
        $medecin->email = 'flen.benflen@alliancemedical.test';
        $medecin->password = bcrypt('Root@root');
        $medecin->specialite = 'cardiologie';
        $medecin->id_clq = 1;
        $medecin->save();
        $clinique = Clinique::all()->where('id_clq',1)->first();
        $clinique->id_med_res = 1;
        $clinique->save();
        /*DB::table('cliniques')->insert([
            'nom' => 'BENFLEN',
            'prenom' => 'Flen',
            'num_tel'=>'+33123456789',
            'login'=>'flen.benflen',
            'email'=>'flen.benflen@alliancemedical.test',
            'password'=>bcrypt('Root@root'),
            'specialite'=>'cardiologie',
            'id_clq' => 1,
        ]);*/
    }
}
