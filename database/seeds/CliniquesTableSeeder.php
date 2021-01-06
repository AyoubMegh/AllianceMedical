<?php

use Illuminate\Database\Seeder;
use App\Clinique;

class CliniquesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $clinique = new Clinique();
        $clinique->id_clq = 1;
        $clinique->nom = 'Paris-Bercy';
        $clinique->adresse = '9 Quai de Bercy, 94220 Charenton-le-Pont, France';
        $clinique->num_tel = '+33143967800';
        $clinique->id_med_res = null;
        $clinique->save();
    }
}
