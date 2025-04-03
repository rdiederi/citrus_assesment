<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Distributor;

class DistributorsTableSeeder extends Seeder
{
    public function run()
    {
        $distributors = [
            [
                'business_name'            => 'Tanzania Fresh Ltd',
                'address'                  => '123 Mango Road, Dar es Salaam',
                'country'                  => 'Tanzania',
                'vat_number'               => 'TZ123456789',
                'sales_contact_name'       => 'John M',
                'sales_contact_email'      => 'john.m@tzfresh.example',
                'sales_contact_phone'      => '+255 456 7890',
                'logistics_contact_name'   => 'Lisa T',
                'logistics_contact_email'  => 'lisa.t@tzfresh.example',
                'logistics_contact_phone'  => '+255 456 7891',
            ],
            [
                'business_name'            => 'Moz Distributors',
                'address'                  => '456 Citrus Ave, Maputo',
                'country'                  => 'Mozambique',
                'vat_number'               => 'MZ987654321',
                'sales_contact_name'       => 'Carla P',
                'sales_contact_email'      => 'carla.p@mozdist.example',
                'sales_contact_phone'      => '+258 123 4567',
                'logistics_contact_name'   => 'Pedro G',
                'logistics_contact_email'  => 'pedro.g@mozdist.example',
                'logistics_contact_phone'  => '+258 123 4568',
            ],
            [
                'business_name'            => 'Mauritius Oranges Co',
                'address'                  => '78 Fruit Blvd, Port Louis',
                'country'                  => 'Mauritius',
                'vat_number'               => 'MU000111222',
                'sales_contact_name'       => 'Asha D',
                'sales_contact_email'      => 'asha.d@mauritiusoranges.example',
                'sales_contact_phone'      => '+230 654 3210',
                'logistics_contact_name'   => 'Oliver R',
                'logistics_contact_email'  => 'oliver.r@mauritiusoranges.example',
                'logistics_contact_phone'  => '+230 654 3211',
            ],
        ];

        foreach ($distributors as $distributor) {
            Distributor::create($distributor);
        }
    }
}
