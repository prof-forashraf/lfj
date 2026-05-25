<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JewelryHoroscopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zodiacData = [
            'Aries' => [
                'description' => 'Today brings bold energy. Wear red gemstones to amplify your natural confidence.',
                'lucky_color' => 'Red',
                'lucky_gemstone' => 'Ruby',
                'recommended_jewelry' => ['Ruby Ring', 'Gold Necklace', 'Bold Earrings'],
            ],
            'Taurus' => [
                'description' => 'Stability and comfort are your focus. Earthy tones and emerald jewelry will ground you.',
                'lucky_color' => 'Green',
                'lucky_gemstone' => 'Emerald',
                'recommended_jewelry' => ['Emerald Pendant', 'Silver Bracelet', 'Pearl Earrings'],
            ],
            'Gemini' => [
                'description' => 'Communication flows freely. Light, versatile jewelry will match your adaptable nature.',
                'lucky_color' => 'Yellow',
                'lucky_gemstone' => 'Agate',
                'recommended_jewelry' => ['Agate Ring', 'Light Necklace', 'Delicate Earrings'],
            ],
            'Cancer' => [
                'description' => 'Emotional depth calls for nurturing pieces. Moonstone and silver will soothe your soul.',
                'lucky_color' => 'Silver',
                'lucky_gemstone' => 'Moonstone',
                'recommended_jewelry' => ['Moonstone Necklace', 'Silver Ring', 'Pearl Bracelet'],
            ],
            'Leo' => [
                'description' => 'Your radiant energy shines bright. Gold and citrine will enhance your natural charisma.',
                'lucky_color' => 'Gold',
                'lucky_gemstone' => 'Citrine',
                'recommended_jewelry' => ['Gold Ring', 'Citrine Pendant', 'Statement Earrings'],
            ],
            'Virgo' => [
                'description' => 'Practicality guides you today. Clean lines and peridot jewelry will serve you well.',
                'lucky_color' => 'Green',
                'lucky_gemstone' => 'Peridot',
                'recommended_jewelry' => ['Peridot Ring', 'Simple Necklace', 'Minimalist Earrings'],
            ],
            'Libra' => [
                'description' => 'Harmony and balance are key. Sapphire and balanced designs will align your energies.',
                'lucky_color' => 'Blue',
                'lucky_gemstone' => 'Sapphire',
                'recommended_jewelry' => ['Sapphire Ring', 'Balanced Necklace', 'Elegant Earrings'],
            ],
            'Scorpio' => [
                'description' => 'Intense passion drives you. Deep colors and garnet will fuel your transformative power.',
                'lucky_color' => 'Deep Red',
                'lucky_gemstone' => 'Garnet',
                'recommended_jewelry' => ['Garnet Ring', 'Mysterious Necklace', 'Bold Bracelet'],
            ],
            'Sagittarius' => [
                'description' => 'Adventure awaits. Turquoise and free-spirited designs will accompany your journey.',
                'lucky_color' => 'Blue',
                'lucky_gemstone' => 'Turquoise',
                'recommended_jewelry' => ['Turquoise Ring', 'Bohemian Necklace', 'Adventure Bracelet'],
            ],
            'Capricorn' => [
                'description' => 'Ambition drives your success. Onyx and structured pieces will support your goals.',
                'lucky_color' => 'Black',
                'lucky_gemstone' => 'Onyx',
                'recommended_jewelry' => ['Onyx Ring', 'Structured Necklace', 'Professional Earrings'],
            ],
            'Aquarius' => [
                'description' => 'Innovation flows through you. Amethyst and unique designs will inspire your creativity.',
                'lucky_color' => 'Purple',
                'lucky_gemstone' => 'Amethyst',
                'recommended_jewelry' => ['Amethyst Ring', 'Unique Necklace', 'Creative Earrings'],
            ],
            'Pisces' => [
                'description' => 'Intuition guides you today. Aquamarine and flowing designs will enhance your sensitivity.',
                'lucky_color' => 'Light Blue',
                'lucky_gemstone' => 'Aquamarine',
                'recommended_jewelry' => ['Aquamarine Ring', 'Flowing Necklace', 'Dreamy Earrings'],
            ],
        ];

        $today = now()->toDateString();

        foreach ($zodiacData as $sign => $data) {
            \App\Models\JewelryHoroscope::updateOrCreate(
                ['zodiac_sign' => $sign, 'date' => $today],
                [
                    'description' => $data['description'],
                    'recommended_jewelry' => $data['recommended_jewelry'],
                    'lucky_color' => $data['lucky_color'],
                    'lucky_gemstone' => $data['lucky_gemstone'],
                ]
            );
        }
    }
}
