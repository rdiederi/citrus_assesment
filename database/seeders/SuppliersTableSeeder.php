<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SuppliersTableSeeder extends Seeder
{
    public function run()
    {
        // Creating multiple suppliers so that there's at least one with id=1, id=2, etc.
        // This ensures we can reference them in Purchase Orders without foreign key violations.

        Supplier::create([
            'business_name'           => 'Citrus Co',
            'address'                 => '123 Orange Lane, Cape Town',
            'country'                 => 'South Africa',
            'vat_number'              => 'ZA-123456',
            'sales_contact_name'      => 'Alice Apple',
            'sales_contact_email'     => 'alice@citrusco.example',
            'sales_contact_phone'     => '+27 111 2222',
            'logistics_contact_name'  => 'Bob Banana',
            'logistics_contact_email' => 'bob@citrusco.example',
            'logistics_contact_phone' => '+27 111 3333',
        ]);

        Supplier::create([
            'business_name'           => 'Grahamstown Oranges',
            'address'                 => '456 Orchard Road, Grahamstown',
            'country'                 => 'South Africa',
            'vat_number'              => 'ZA-654321',
            'sales_contact_name'      => 'Carol Cherry',
            'sales_contact_email'     => 'carol@grahamoranges.example',
            'sales_contact_phone'     => '+27 444 5555',
            'logistics_contact_name'  => 'Dan Durian',
            'logistics_contact_email' => 'dan@grahamoranges.example',
            'logistics_contact_phone' => '+27 444 6666',
        ]);

    }
}
