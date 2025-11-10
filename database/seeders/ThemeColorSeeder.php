<?php

namespace Database\Seeders;

use App\Models\ThemeColor;
use Illuminate\Database\Seeder;

class ThemeColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ThemeColor::truncate();

        $this->ThemeColors();
    }

    private function ThemeColors()
    {
        $colors = [
            [
                'primary' => '#51AF5B',
                'secondary' => '#C9E9CC',
                'variant_50' => '#F3FAF3',
                'variant_100' => '#E4F4E5',
                'variant_200' => '#C9E9CC',
                'variant_300' => '#9FD6A5',
                'variant_400' => '#6DBB76',
                'variant_500' => '#51AF5B',
                'variant_600' => '#388140',
                'variant_700' => '#2E6734',
                'variant_800' => '#29522E',
                'variant_900' => '#234427',
                'variant_950' => '#0F2413',
                'is_default' => true,
                'theme_name' => 'NovaStore',
            ],
            [
                'primary' => '#51AF5B',
                'secondary' => '#C9E9CC',
                'variant_50' => '#F3FAF3',
                'variant_100' => '#E4F4E5',
                'variant_200' => '#C9E9CC',
                'variant_300' => '#9FD6A5',
                'variant_400' => '#6DBB76',
                'variant_500' => '#51AF5B',
                'variant_600' => '#388140',
                'variant_700' => '#2E6734',
                'variant_800' => '#29522E',
                'variant_900' => '#234427',
                'variant_950' => '#0F2413',
                'is_default' => false,
                'theme_name' => 'MegaMart',
            ],
            [
                'primary' => '#E34648',
                'secondary' => '#FFC9C9',
                'variant_50' => '#FEF2F2',
                'variant_100' => '#FEE2E2',
                'variant_200' => '#FFC9C9',
                'variant_300' => '#FDA4A4',
                'variant_400' => '#E34648',
                'variant_500' => '#F14242',
                'variant_600' => '#DF2323',
                'variant_700' => '#BB1A1A',
                'variant_800' => '#9B1919',
                'variant_900' => '#801C1C',
                'variant_950' => '#460909',
                'is_default' => false,
                'theme_name' => 'NextMart',
            ],[
                'primary' => '#51AF5B',
                'secondary' => '#C9E9CC',
                'variant_50' => '#F3FAF3',
                'variant_100' => '#E4F4E5',
                'variant_200' => '#C9E9CC',
                'variant_300' => '#9FD6A5',
                'variant_400' => '#6DBB76',
                'variant_500' => '#51AF5B',
                'variant_600' => '#388140',
                'variant_700' => '#2E6734',
                'variant_800' => '#29522E',
                'variant_900' => '#234427',
                'variant_950' => '#0F2413',
                'is_default' => false,
                'theme_name' => 'PrimeCart',
            ],[
                'primary' => '#4086E1',
                'secondary' => '#C3E5FA',
                'variant_50' => '#F0F8FE',
                'variant_100' => '#DDEFFC',
                'variant_200' => '#C3E5FA',
                'variant_300' => '#99D6F7',
                'variant_400' => '#69BEF1',
                'variant_500' => '#46A2EB',
                'variant_600' => '#3186DF',
                'variant_700' => '#2973D2',
                'variant_800' => '#275BA6',
                'variant_900' => '#244E84',
                'variant_950' => '#1B3050',
                'is_default' => false,
                'theme_name' => 'UltraMart',
            ],
        ];

        ThemeColor::insert($colors);
    }
}
