<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Illuminate\Database\Seeder;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing sponsors
        Sponsor::truncate();

        // Sample sponsor data
        $sponsors = [
            // Platinum Sponsors
            [
                'name' => 'Bank Mandiri',
                'logo' => '/images/sponsors/bank-mandiri.png',
                'website' => 'https://www.bankmandiri.co.id',
                'type' => Sponsor::TYPE_PLATINUM,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Telkom Indonesia',
                'logo' => '/images/sponsors/telkom.png',
                'website' => 'https://www.telkom.co.id',
                'type' => Sponsor::TYPE_PLATINUM,
                'order' => 2,
                'is_active' => true,
            ],

            // Gold Sponsors
            [
                'name' => 'Gojek',
                'logo' => '/images/sponsors/gojek.png',
                'website' => 'https://www.gojek.com',
                'type' => Sponsor::TYPE_GOLD,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Tokopedia',
                'logo' => '/images/sponsors/tokopedia.png',
                'website' => 'https://www.tokopedia.com',
                'type' => Sponsor::TYPE_GOLD,
                'order' => 4,
                'is_active' => true,
            ],

            // Silver Sponsors
            [
                'name' => 'Shopee',
                'logo' => '/images/sponsors/shopee.png',
                'website' => 'https://www.shopee.co.id',
                'type' => Sponsor::TYPE_SILVER,
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Bukalapak',
                'logo' => '/images/sponsors/bukalapak.png',
                'website' => 'https://www.bukalapak.com',
                'type' => Sponsor::TYPE_SILVER,
                'order' => 6,
                'is_active' => true,
            ],

            // Bronze Sponsors
            [
                'name' => 'Grab',
                'logo' => '/images/sponsors/grab.png',
                'website' => 'https://www.grab.com',
                'type' => Sponsor::TYPE_BRONZE,
                'order' => 7,
                'is_active' => true,
            ],

            // Media Partners
            [
                'name' => 'Kompas',
                'logo' => '/images/sponsors/kompas.png',
                'website' => 'https://www.kompas.com',
                'type' => Sponsor::TYPE_MEDIA_PARTNER,
                'order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Detik',
                'logo' => '/images/sponsors/detik.png',
                'website' => 'https://www.detik.com',
                'type' => Sponsor::TYPE_MEDIA_PARTNER,
                'order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Tribun News',
                'logo' => '/images/sponsors/tribun.png',
                'website' => 'https://www.tribunnews.com',
                'type' => Sponsor::TYPE_MEDIA_PARTNER,
                'order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($sponsors as $sponsor) {
            Sponsor::create($sponsor);
        }

        $this->command->info('✅ Sponsor seeder completed: 10 sponsors created');
    }
}

