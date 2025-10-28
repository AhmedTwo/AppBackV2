<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nom'  => "Seghiri",
            'prenom' => "Ahmed",
            'email' => "seghiriahmed9@gmail.com",
            'password' => "ahmedmdp",
            'role' => "admin",
            'telephone' => "0768687403",
            'ville' => "Sannois",
            'code_postal' => "95110",
            'cv_pdf' => "/public/assets/images/userDefault.jpeg",
            'qualification' => "Etudiant",
            'preference' => "CDI, CDD",
            'disponibilite' => 1,
            'photo' => "/public/assets/images/userDefault.jpeg",
        ]);
    }
}
