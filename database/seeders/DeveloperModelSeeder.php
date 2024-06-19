<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DeveloperModelSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {
            DB::table('developers')->insert([
                'id' => Uuid::uuid4()->toString(),
                'firstName' => 'Thiago',
                'lastName' => 'Rodrigues',
                'email' => 'thiago.rodrigues'.$i.'@example.com',
                'gender' => 'heterosexual',
                'age' => 33,
                'hobby' => 'Programar e estudar',
                'birthDate' => '1991-03-10',
            ]);
        }
    }
}
