<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
 
use Illuminate\Database\Seeder;
 
use Spatie\Permission\Models\Role;
 
use Spatie\Permission\Models\Permission;
 
class RoleSeeder extends Seeder
 
{
 
    public function run(): void
    
    {
        
        $role1 = Role::create(['name' => 'superAdmin']);
        
        $role2 = Role::create(['name' => 'admin']);

        $role3 = Role::create(['name' => 'doctor']);

        $role4 = Role::create(['name' => 'administrativo']);

        $role5 = Role::create(['name' => 'tecnico']);

        $role6 = Role::create(['name' => 'visualizacion']);
        
        Permission::create(['name'=>'estudios.home'])->syncRoles([$role1,$role2,$role3,$role4,$role5,$role6]);
        
        Permission::create(['name'=>'estudios.index'])->syncRoles([$role1,$role2,$role3,$role4,$role5,$role6]);

        Permission::create(['name'=>'estudios.edit'])->syncRoles([$role1,$role2,$role3,$role4,$role5,$role6]);
        
        Permission::create(['name'=>'estudios.create'])->syncRoles([$role1,$role2,$role4]);
        
        
        Permission::create(['name'=>'estudios.update'])->syncRoles([$role1,$role2,$role3,$role5]);
        
        Permission::create(['name'=>'estudios.finally'])->syncRoles([$role1,$role2,$role3]);
        
        Permission::create(['name'=>'estudios.finalizar'])->syncRoles([$role1,$role4]);
        
        Permission::create(['name'=>'estudios.ampliarInforme'])->syncRoles([$role1,$role2]);
        
    }
 
}