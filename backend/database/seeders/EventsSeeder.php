<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventsSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'Annual Spring Collection Launch',
                'description' => 'Join us for the unveiling of our stunning spring jewelry collection featuring new designs in precious metals and gemstones.',
                'start_datetime' => now()->addDays(7)->setHour(10)->setMinute(0),
                'end_datetime' => now()->addDays(7)->setHour(18)->setMinute(0),
                'location_name' => 'Latest Fashion Jewellery Flagship Store',
                'location_address' => '123 Fashion Lane, Downtown, City',
                'event_url' => 'https://latestfashionjewellery.com/events/spring-collection',
                'status' => 'active',
                'seo_title' => 'Annual Spring Collection Launch - Latest Fashion Jewellery',
                'meta_description' => 'Discover our new spring jewelry collection. Join us for an exclusive launch event.',
                'og_title' => 'Spring Collection Launch',
                'og_description' => 'Exclusive unveiling of our latest jewelry designs',
                'schema_type' => 'Event',
            ],
            [
                'title' => 'Diamond Certification Workshop',
                'description' => 'Learn about diamond grading, certification, and how to identify authentic diamonds from our expert gemologists.',
                'start_datetime' => now()->addDays(14)->setHour(11)->setMinute(0),
                'end_datetime' => now()->addDays(14)->setHour(13)->setMinute(0),
                'location_name' => 'Latest Fashion Jewellery Training Center',
                'location_address' => '456 Gem Street, Business District, City',
                'event_url' => 'https://latestfashionjewellery.com/events/diamond-workshop',
                'status' => 'active',
                'seo_title' => 'Diamond Certification Workshop - Learn from Experts',
                'meta_description' => 'Educational workshop on diamond certification and grading standards.',
                'og_title' => 'Diamond Certification Workshop',
                'og_description' => 'Expert-led training on diamond authentication',
                'schema_type' => 'Event',
            ],
            [
                'title' => 'Summer Wedding Jewelry Fair',
                'description' => 'Explore our exclusive wedding jewelry collection. Special discounts and customization services available for engaged couples.',
                'start_datetime' => now()->addDays(21)->setHour(9)->setMinute(0),
                'end_datetime' => now()->addDays(23)->setHour(18)->setMinute(0),
                'location_name' => 'City Convention Center',
                'location_address' => '789 Main Avenue, Central Plaza, City',
                'event_url' => 'https://latestfashionjewellery.com/events/wedding-fair',
                'status' => 'active',
                'seo_title' => 'Summer Wedding Jewelry Fair 2024',
                'meta_description' => 'Discover perfect wedding jewelry. Three-day fair with exclusive discounts.',
                'og_title' => 'Wedding Jewelry Fair',
                'og_description' => 'Find your perfect wedding jewelry at our summer fair',
                'schema_type' => 'Event',
            ],
            [
                'title' => 'Gold Investment Seminar',
                'description' => 'Understand the value of gold as an investment. Our financial advisors will discuss current market trends and investment opportunities.',
                'start_datetime' => now()->addDays(30)->setHour(14)->setMinute(0),
                'end_datetime' => now()->addDays(30)->setHour(16)->setMinute(0),
                'location_name' => 'Latest Fashion Jewellery Head Office',
                'location_address' => '321 Investment Park, Financial District, City',
                'event_url' => 'https://latestfashionjewellery.com/events/gold-seminar',
                'status' => 'active',
                'seo_title' => 'Gold Investment Seminar - Expert Insights',
                'meta_description' => 'Learn about gold investment strategies and market trends from industry experts.',
                'og_title' => 'Gold Investment Seminar',
                'og_description' => 'Expert guidance on investing in gold',
                'schema_type' => 'Event',
            ],
            [
                'title' => 'Festive Season Celebration',
                'description' => 'Celebrate the festive season with special collections, live performances, and exclusive gifts with every purchase.',
                'start_datetime' => now()->addDays(60)->setHour(10)->setMinute(0),
                'end_datetime' => now()->addDays(60)->setHour(20)->setMinute(0),
                'location_name' => 'Latest Fashion Jewellery Showroom',
                'location_address' => '555 Luxury Row, Shopping District, City',
                'event_url' => 'https://latestfashionjewellery.com/events/festive-celebration',
                'status' => 'active',
                'seo_title' => 'Festive Season Celebration - Special Collections',
                'meta_description' => 'Celebrate with us! Special festive collections and exclusive offers.',
                'og_title' => 'Festive Celebration Event',
                'og_description' => 'Join our festive season celebration with special jewelry collections',
                'schema_type' => 'Event',
            ],
            [
                'title' => 'Jewelry Design & Craftsmanship Expo',
                'description' => 'Witness the artistry of jewelry making. Meet our master craftsmen and learn about the process behind creating exquisite pieces.',
                'start_datetime' => now()->addDays(45)->setHour(11)->setMinute(0),
                'end_datetime' => now()->addDays(46)->setHour(17)->setMinute(0),
                'location_name' => 'Artisan Quarter Pavilion',
                'location_address' => '888 Craft Avenue, Arts District, City',
                'event_url' => 'https://latestfashionjewellery.com/events/craftsmanship-expo',
                'status' => 'active',
                'seo_title' => 'Jewelry Design & Craftsmanship Expo',
                'meta_description' => 'Experience the art of jewelry making at our craftsmanship expo.',
                'og_title' => 'Craftsmanship Expo',
                'og_description' => 'Discover the artistry behind exquisite jewelry',
                'schema_type' => 'Event',
            ],
        ];

        foreach ($events as $event) {
            DB::table('events')->insertOrIgnore([
                'title' => $event['title'],
                'slug' => Str::slug($event['title']),
                'description' => $event['description'],
                'start_datetime' => $event['start_datetime'],
                'end_datetime' => $event['end_datetime'],
                'location_name' => $event['location_name'],
                'location_address' => $event['location_address'],
                'event_url' => $event['event_url'],
                'featured_image' => null,
                'featured_image_url_snapshot' => null,
                'image_urls' => json_encode([]),
                'status' => $event['status'],
                'seo_title' => $event['seo_title'],
                'meta_description' => $event['meta_description'],
                'og_title' => $event['og_title'],
                'og_description' => $event['og_description'],
                'schema_type' => $event['schema_type'],
            ]);
        }
    }
}
