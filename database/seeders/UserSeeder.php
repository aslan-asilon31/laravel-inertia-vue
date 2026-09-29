<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Haruto Sato', 'email' => 'haruto.sato@example.com'],
            ['name' => 'Yuki Tanaka', 'email' => 'yuki.tanaka@example.com'],
            ['name' => 'Sakura Nakamura', 'email' => 'sakura.nakamura@example.com'],
            ['name' => 'Ren Kobayashi', 'email' => 'ren.kobayashi@example.com'],
            ['name' => 'Aoi Yamamoto', 'email' => 'aoi.yamamoto@example.com'],
            ['name' => 'Kaito Watanabe', 'email' => 'kaito.watanabe@example.com'],
            ['name' => 'Hina Ito', 'email' => 'hina.ito@example.com'],
            ['name' => 'Sora Suzuki', 'email' => 'sora.suzuki@example.com'],
            ['name' => 'Riku Takahashi', 'email' => 'riku.takahashi@example.com'],
            ['name' => 'Mei Fujita', 'email' => 'mei.fujita@example.com'],
            ['name' => 'Daichi Saito', 'email' => 'daichi.saito@example.com'],
            ['name' => 'Nana Shimizu', 'email' => 'nana.shimizu@example.com'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('123456'),
            ]);
        }
    }
}
