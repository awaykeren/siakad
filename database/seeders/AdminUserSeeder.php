<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrNew([
            'email' => 'admin@siakad.test',
        ]);

        $user->name = 'Administrator SIAKAD';
        $user->password = Hash::make('admin123');
        // $user->role = 'admin';

        $user->save();
    }
}
