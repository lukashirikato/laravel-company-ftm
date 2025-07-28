<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'name' => 'Aisyah Fitri',
            'email' => 'aisyah@example.com',
            'phone_number' => '08123456789',
            'program' => 'pilates',
            'quota' => 10,
            'membership' => 'exclusive program',
            'preferred_membership' => 'private program',
            'user_id' => null,
        ]);

        // Tambah data lain jika perlu...
    }
}
