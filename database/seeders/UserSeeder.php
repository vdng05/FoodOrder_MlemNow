<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Dũng VG',
            'email' => 'dungvg@gmail.com',
            'phone' => '0937243758',
            'password' => bcrypt('12345678'),
        ]);
    }
}