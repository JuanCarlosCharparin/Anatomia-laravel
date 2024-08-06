<?php
 
namespace Database\Seeders;
 
use App\Models\User;
 
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Spatie\Permission\Models\Role;
 
use Illuminate\Database\Seeder;
 
class UserSeeder extends Seeder
 
{
 
    public function run(): void
    {
        // Encuentra usuarios existentes
        $superAdminUser = User::where('email', 'jccharparin@gmail.com')->first();
        $adminUser = User::where('email', 'fernanda.contreras@hospital.uncu.edu.ar')->first();
        $doctorUser = User::where('email', 'cecilia.torres@hospital.uncu.edu.ar')->first();
        $administrativoUser = User::where('email', 'beatriz.ortiz@hospital.uncu.edu.ar')->first();
        $tecnicoUser = User::where('email', 'paola.estalles@hospital.uncu.edu.ar')->first();
        $visualizadorUser = User::where('email', 'hospital@hu')->first();

        // Encuentra roles
        $superAdminRole = Role::findByName('superAdmin');
        $adminRole = Role::findByName('admin');
        $doctorRole = Role::findByName('doctor');
        $administrativoRole = Role::findByName('administrativo');
        $tecnicoRole = Role::findByName('tecnico');
        $visualizadorRole = Role::findByName('visualizacion');

        // Asignar roles a usuarios
        if ($superAdminUser) {
            $superAdminUser->assignRole($superAdminRole);
        }

        if ($adminUser) {
            $adminUser->assignRole($adminRole);
        }

        if ($doctorUser) {
            $doctorUser->assignRole($doctorRole);
        }

        if ($administrativoUser) {
            $administrativoUser->assignRole($administrativoRole);
        }

        if ($tecnicoUser) {
            $tecnicoUser->assignRole($tecnicoRole);
        }

        if ($visualizadorUser) {
            $visualizadorUser->assignRole($visualizadorRole);
        }
    }
 
}