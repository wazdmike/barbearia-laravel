<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        User::create([
            'name' => 'Reginaldo Administrador',
            'email' => 'admin@barbervibe.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Felipe Barbeiro',
            'email' => 'barbeiro@barbervibe.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'barber',
        ]);

        User::create([
            'name' => 'Carlos Cliente',
            'email' => 'cliente@barbervibe.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'client',
        ]);

        Service::create([
            'name' => 'Corte de Cabelo (Degradê Moderno)',
            'price' => 45.00,
            'duration_minutes' => 40,
        ]);

        Service::create([
            'name' => 'Barba Completa (Terapia de Toalha Quente)',
            'price' => 35.00,
            'duration_minutes' => 30,
        ]);

        Service::create([
            'name' => 'Combo Premium (Cabelo + Barba + Sobrancelha)',
            'price' => 70.00,
            'duration_minutes' => 60,
        ]);
    }
}