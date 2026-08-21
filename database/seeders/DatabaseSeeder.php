<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::updateOrCreate(['email'=>'admin@sltc-inter.bj'],[
            'name'=>'Administrateur SLTC','password'=>'ChangeMe_123!','role'=>'super_admin'
        ]);
        $this->call(ContentSeeder::class);
    }
}