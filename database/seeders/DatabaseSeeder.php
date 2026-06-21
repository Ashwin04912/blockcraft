<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use \Illuminate\Database\Console\Seeds\WithoutModelEvents;

    public function run(): void
    {
        // Create admin user with known credentials
        User::updateOrCreate(
            ['email' => 'admin@blockcraft.test'],
            [
                'name'     => 'BlockCraft Admin',
                'email'    => 'admin@blockcraft.test',
                'password' => Hash::make('password'),
            ]
        );

        $this->call(PortfolioSeeder::class);

        $this->command->info('');
        $this->command->info('Admin credentials:');
        $this->command->info('  Email:    admin@blockcraft.test');
        $this->command->info('  Password: password');
    }
}
