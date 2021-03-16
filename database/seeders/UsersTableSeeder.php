<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item = 'root';
        $user = User::create([
            'name' => $item,
            'email' => "$item@$item.org",
            'password' => Hash::make($item),
            'email_verified_at' => now(),
        ]);
        $user->assignRole($item);
    }
}
