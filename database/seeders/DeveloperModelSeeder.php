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
            $email = 'thiago.rodrigues'.$i.'@example.com';
            $bytes = md5($email, true);
            $uuid = Uuid::uuid8($bytes);

            DB::table('developers')->insert([
                'id' => $uuid->toString(),
                'firstName' => 'Thiago',
                'lastName' => 'Rodrigues',
                'email' => $email,
                'gender' => 'heterosexual',
                'age' => 33,
                'hobby' => 'Programar e estudar',
                'birthDate' => '1991-03-10',
            ]);
        }
    }
}
