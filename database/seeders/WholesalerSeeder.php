<?php
namespace Database\Seeders;

use App\Models\Wholesaler;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WholesalerSeeder extends Seeder
{
    public function run()
    {
        Wholesaler::create([
            'name' => 'Test Wholesaler',
            'email' => 'wholesaler@example.com',
            'password' => Hash::make('password'),
            'phone' => '01761502669',
            'business_name' => 'Test Wholesale Business',
            'address' => '123 Business Street, City, State',
            'status' => 'active',
        ]);
    }
}
