<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AffiliateProduct;
use App\Models\Category;

class AffiliateProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'amazon_asin' => 'B0C6Y373G2',
                'product_name_snapshot' => 'Betsey Johnson Rose Dangle Earring Color',
                'amazon_url' => 'https://amzn.to/3CfkocI',
                'your_notes' => 'Discover the essence of elegance with our Trendy Earrings collection. These delightful dangle earrings showcase a delicate long ear wire adorned with iridescent stones, paired gracefully with a colorful pink rose drop. The rose itself is embellished with glistening mixed stone accents, all meticulously handcrafted from polished gold-tone metal, glass, and enamel.



Enjoy the freedom of lightweight earrings that effortlessly grace your ears without tugging on the lobe, featuring a convenient shepherd\'s hook closure. With measurements of 2.25 inches in length and 0.8 inches in width, these earrings are the perfect blend of style and comfort.



Give the gift of timeless beauty – our Trendy Earrings are a wonderful choice for birthdays, bridesmaids, anniversaries, Mother\'s Day, weddings, Christmas, or any other special occasion. Elevate any ensemble with this perfect statement piece that transcends seasons.



Rest assured, our Hypoallergenic Jewelry is crafted with nickel and lead-free metal, minimizing the risk of any allergic reactions. Embrace style and comfort seamlessly with these exquisite earrings.',
                'main_image_url_snapshot' => null,
                'price' => 25.4,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/1.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2025-01-21 18:06:35',
                'updated_at' => '2025-01-21 18:06:35',
            ],
            [
                'amazon_asin' => 'B0CCNP9NC1',
                'product_name_snapshot' => 'Amazon Collection Open Filigree Swirl Hoop Earrings in Sterling Silver',
                'amazon_url' => 'https://amzn.to/3yKWjZ6',
                'your_notes' => 'Elevate your style with our round hoop earrings featuring intricate filigree scrollwork, measuring 20.8mm in length and 6mm in width. Meticulously crafted in sterling silver, these earrings are adorned with an 18K yellow gold plating for enhanced design and appearance. The click-post closure ensures both security and comfort during wear. To maintain their luster, store these earrings in a cool, dry place, avoid exposure to extreme temperatures and household cleaning products, and clean them with a soft cloth and warm, soapy water. Experience elegance and durability in one exquisite piece.',
                'main_image_url_snapshot' => null,
                'price' => 27.6,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/2.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B07VNK6NKP',
                'product_name_snapshot' => 'Flyonce Women\'s Rhinestone Crystal Wedding Bridal 2 Leaf Drop Dangle Chandelier Earrings',
                'amazon_url' => 'https://amzn.to/4bGv00N',
                'your_notes' => 'Indulge in the allure of our fashion-forward design – these captivating black earrings are adorned with sparkling Austrian crystals, showcasing a stunning array of rhinestone crystals in a paved setting. The multi-faceted color exudes confidence, elegance, and style for the beautiful you.



Crafted for comfort, these earrings boast a length of 8.2cm (3.2in), a width of 2.5cm (1in), and a weight of 15.7g each. Lead and nickel-free, they offer a delightful wear experience.



Presented in a delicate gift box, these chandelier earrings make an ideal gift for someone special in your life. Whether for birthdays, Mother\'s Day, anniversaries, weddings, or a personal treat, these nickel-free, lead-free, and hypoallergenic earrings are a perfect expression of style and sentiment.',
                'main_image_url_snapshot' => null,
                'price' => 15.99,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/3.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B0CF4Q2VHG',
                'product_name_snapshot' => 'The Love Between Mother and Daughter is Forever Mother Daughter Ring, Story Jewellery Mother Daughter Knot Ring for Women, Daughter Gifts from Mom, Friend Gifts for Women, Best Friend Rings, Friendship Rings',
                'amazon_url' => 'https://amzn.to/3Wu0jpP',
                'your_notes' => 'Express your appreciation and celebrate the bond of friendship with our Best Friend Gifts for Women. Suitable for various occasions such as birthdays, Christmas, weddings, and more, these thoughtful gifts are perfect for showing gratitude and love. Our Friendship Rings, in particular, serve as a meaningful gesture to honor those who hold a special place in your life. Crafted with premium quality alloy plated in silver and adorned with AAA Grade cubic zirconia, these rings offer luxury without the hefty price tag. Each piece comes with a message card, expressing sentiments of an unbreakable bond: \'My Best Friend, our friendship is like a knot that can never be untied.\' Elevate your gift-giving experience with our high-quality, affordable treasures.',
                'main_image_url_snapshot' => null,
                'price' => 31.2,
                'item_type' => 'rings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/4.jpg',
                'category_slug' => 'rings',
                'created_at' => '2025-01-21 18:06:35',
                'updated_at' => '2025-01-21 18:06:35',
            ],
            [
                'amazon_asin' => 'B07T9WLR73',
                'product_name_snapshot' => 'Gothic Jewelry Purple Red Black Crystal Unique Black Skull Rings for Women Halloween Christmas Gifts',
                'amazon_url' => 'https://amzn.to/4h4Ro6s',
                'your_notes' => 'Elevate your style with our Black Skull Design Rings, a unique and personalized choice symbolizing enduring love. Crafted with high-quality materials, these rings are nickel and lead-free, ensuring health and anti-allergy benefits. Adorned with Zircon, Purple Crystal, Red Crystal, and Black Crystal, they shine at various occasions like daily wear, celebrations, and formal events. The perfect fashion accessory for yourself or a beloved one, these rings add a touch of charm and elegance to every moment',
                'main_image_url_snapshot' => null,
                'price' => 19.99,
                'item_type' => 'rings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/5.jpg',
                'category_slug' => 'rings',
                'created_at' => '2025-01-21 18:06:35',
                'updated_at' => '2025-01-21 18:06:35',
            ],
            [
                'amazon_asin' => 'B07M9ZCV3H',
                'product_name_snapshot' => 'Barzel Rose Gold & White Gold Plated Created Ruby, White Fire Opal & Cubic Zirconia Accents Ring',
                'amazon_url' => 'https://amzn.to/4g5aXub',
                'your_notes' => 'Enhance your style with our Opal Ring, perfect for both casual and formal occasions. Available in White Gold, Black Rhodium & Rose Gold, this ring guarantees to retain its original content and color. It\'s an ideal gift for Christmas, Valentine’s Day, Mother\'s Day, Birthday, Anniversary, or any special occasion. Our jewelry meets excellent quality standards, ensuring it shines right and withstands wear & tear. Enjoy a Lifetime No-Questions-Asked Money-Back Guarantee for 100% satisfaction',
                'main_image_url_snapshot' => null,
                'price' => 14.99,
                'item_type' => 'rings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/6.jpg',
                'category_slug' => 'rings',
                'created_at' => '2025-01-21 18:06:35',
                'updated_at' => '2025-01-21 18:06:35',
            ],
            [
                'amazon_asin' => 'B07W4DRFK1',
                'product_name_snapshot' => 'UEUC Tree of Life Yoga Healing Stone Bracelets,Multilayer Lotus Chakra Stone Reiki Bracelet Sets,Anxiety Crystal Natural Stone,for Women Stress Relief',
                'amazon_url' => 'https://amzn.to/4gfsHDq',
                'your_notes' => 'Discover the elegance of our Tree of Life Yoga Lotus Leaf bracelet by UEUC. Crafted with 8mm/4mm Turquoise Jasper & Tibetan Agate Gemstone, it features a unique design with a Tree of Life, Lotus leaf, and yoga charm, all made with environmentally friendly metal and nickel-free. Symbolizing a fresh start, positive energy, good health, and a bright future, this bracelet is a union of meaningful symbols. Adjustable to fit both large and narrow wrists, the 7-inch bracelet is elastic, making it easy to put on and take off. Share the joy with your loved ones – a perfect gift for special occasions like Halloween, Christmas, New Year, Valentine\'s Day, Mother\'s Day, or birthdays',
                'main_image_url_snapshot' => null,
                'price' => 12.99,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/7.jpg',
                'category_slug' => 'bracelets',
                'created_at' => '2025-01-21 18:06:35',
                'updated_at' => '2025-01-21 18:06:35',
            ],
            [
                'amazon_asin' => 'B07G86N775',
                'product_name_snapshot' => 'PEARL&CLUB Beaded Chakra Bangle Turquoise Bracelet - Fashion Jewelry Wrap Bracelet with Thick Silver Metal and Mala Beads, Birthday Gifts For Women',
                'amazon_url' => 'https://amzn.to/4aoSJCA',
                'your_notes' => 'Discover the allure of our Chakra Bracelet, a harmonious blend of natural mala stone, spring beads, and silver-plated alloy. This eco-friendly accessory is hypoallergenic, making it a perfect, comfortable gift for any occasion. Its unique design, featuring a combination of beads and metal textures, effortlessly adds a touch of charm. Easy to wear and maintain, this flexible wraparound bracelet is a stylish addition to your collection. Packaged in an elegant box, it\'s an ideal gift for birthdays, celebrations, or a thoughtful gesture of affection.',
                'main_image_url_snapshot' => null,
                'price' => 18.99,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/8.jpg',
                'category_slug' => 'bracelets',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B09V2QPK25',
                'product_name_snapshot' => 'RIVIKO Love Heart Charm Bracelet For Women 925 Sterling Silver With Zirconia Birthstone Bracelets Adjustable Link for Mother Wife Girls Sister Birthday Christmas Mother\'s Day Valentine\'s Day Jewelry Gift',
                'amazon_url' => 'https://amzn.to/4g6jltA',
                'your_notes' => 'Embrace the beauty of birthstones with our December Birthstone Bracelet, a perfect birthday or Christmas gift for her. Crafted in 925 sterling silver, the pendant features a dazzling blue birthstone symbolizing success and good luck. Each month\'s birthstone carries unique meanings, making this bracelet a thoughtful and symbolic accessory. The heart bracelet design, adorned with 5A CZ stones, adds a touch of glamour and happiness. With its comfortable fit and sparking gemstone, it comes packaged in an elegant navy-blue gift box, making it an ideal present for various occasions',
                'main_image_url_snapshot' => null,
                'price' => 19.99,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/9.jpg',
                'category_slug' => 'bracelets',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0B49Z2MXM',
                'product_name_snapshot' => 'Kate Lynn Rise From the Ashes Sterling Silver Phoenix Necklaces for Women, Packaged with Jewelry Box, Birthday Gift for Women, Symbol of Luck and Renewal',
                'amazon_url' => 'https://amzn.to/3Clb8nj',
                'your_notes' => 'Experience the symbolism of rebirth and eternity with our Rising Phoenix Necklace. This exquisite women\'s necklace features a beautifully crafted pendant representing hope, just like the rising phoenix. Packaged in a jewelry box, it makes for a perfect gift for women or teens on occasions like Christmas, birthdays, anniversaries, and more. The pendant, adorned with sparkling crystals from Austria, adds an elegant and eye-catching touch to this unique accessory. With a pendant size of 0.9\"*1.8\" and a chain length of 18\"+2\" extender, it\'s a glamorous addition to any outfit',
                'main_image_url_snapshot' => null,
                'price' => 37.49,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/10.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B085DDMN7Z',
                'product_name_snapshot' => 'CDE Angel Wing Love Heart Necklaces for Women, Silver Tone/Gold Tone Pendant Necklace Jewelry Gifts for Her on Christmas, Valentine\'s/Mother\'s Day, Anniversary, Birthday Gifts for Women Girls Wife Girlfriend',
                'amazon_url' => 'https://amzn.to/4gU8uE9',
                'your_notes' => 'Express your love with our Valentine\'s Day Gifts for Women. This elegant piece comes in a CDE jewelry gift box, eliminating the need for wrapping. The heart-shaped pendant, wrapped in a wing, symbolizes angelic protection. Made with White Gold Plating, it\'s hypoallergenic and adorned with Austrian Crystals, ensuring durability and sparkle. The pendant specifications are 1.45 inch(L)/1.61 inch(H)/0.27 inch(W), accompanied by an adjustable chain (17.7+2 inch), weighing only 0.42 ounces. This exquisite piece is a perfect match for any occasion, adding irresistible sparkle to your daily or evening look',
                'main_image_url_snapshot' => null,
                'price' => 29.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/11.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B01N3U2C90',
                'product_name_snapshot' => 'Gem Stone King 925 Sterling Silver Created Blue Sapphire Butterfly Infinity Pendant Necklace For Women (1.21 Ct Marquise, With 18 Inch Silver Chain)',
                'amazon_url' => 'https://amzn.to/40oDGV1',
                'your_notes' => 'Elegance meets transformation in our Exquisite Butterfly Infinity Pendant Necklace. Adorned with a stunning 1.21 Ct marquise-cut created blue sapphire, this necklace symbolizes grace and change. Crafted from 925 sterling silver, it guarantees durability and is hypoallergenic, ideal for those with sensitive skin. The versatile 18-inch silver chain complements the pendant seamlessly, allowing it to be worn casually or formally. Presented in a stylish gift box, this necklace makes a thoughtful gift for birthdays, anniversaries, or any special occasion.',
                'main_image_url_snapshot' => null,
                'price' => 49.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/12.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B07J1VSGVJ',
                'product_name_snapshot' => 'White Freshwater Cultured Pearl Christmas Gift Necklace for Women in 18 Inch Princess Length',
                'amazon_url' => 'https://amzn.to/3E58nqV',
                'your_notes' => 'Discover elegance with our Women\'s Pearl Necklace – a timeless piece featuring round white 7.5-8mm freshwater cultured pearls in an 18\" princess length. Crafted with quality in mind, our AA-grade pearls boast a 925 sterling silver clasp with a 1.4-inch adjustable chain, meeting the strictest standards and graduatee by GIA (Gemological Institute of America). It\'s not just jewelry; it\'s a perfect gift packaged in a delightful box – ideal for yourself, mom, wife, daughter, birthdays, anniversaries, bridesmaids, or to express friendship during the festive season. Embrace the joy of giving with our exquisite pearl necklace, perfectly suited for Christmas celebrations.',
                'main_image_url_snapshot' => null,
                'price' => 33.99,
                'item_type' => 'holiday-gifts',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/13.jpg',
                'category_slug' => 'holiday-gifts',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B07Z7V167F',
                'product_name_snapshot' => 'LENOOCLE Christmas Jewelry for Women Christmas Snowflakes Necklace Earrings Set Sparkling Crystal Rhinestone Long Pendant Necklace Drop Dangle Earrings Xmas Holiday Party Jewelry Gift for Teen Girls',
                'amazon_url' => 'https://amzn.to/40sHggG',
                'your_notes' => 'Get ready to dazzle this holiday season with our Christmas jewelry for women. Our adorable snowflake charm necklace and earrings set is the perfect festive accessory. The sparkling crystal rhinestone design adds a touch of elegance and charm. Wear it to Christmas parties, family dinners, and other cheerful occasions to create a chic and stylish look. Spread holiday cheer with this fabulous holiday accessory that complements any style.',
                'main_image_url_snapshot' => null,
                'price' => 11.99,
                'item_type' => 'holiday-gifts',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/14.jpg',
                'category_slug' => 'holiday-gifts',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B088R7QDPD',
                'product_name_snapshot' => 'FANCIME Birthstone Necklaces for Women Fine Jewelry Sterling Silver Dainty Infinity Pendant Necklace Anniversary Birthday Gifts Christmas Gift for Her Women Girls Wife Mom Lady Daughter, Chain Extend',
                'amazon_url' => 'https://amzn.to/3CdQpBZ',
                'your_notes' => 'Gift her with love and elegance! Our Genuine Garnet Necklace is a perfect celebration of the special woman in your life. This dainty infinity pendant, adorned with a stunning red garnet, makes for a unique and heartfelt fashion statement. Crafted from 925 sterling silver, the necklace is not only beautiful but also hypoallergenic. The January Birthstone, representing chastity, love, and loyalty, adds a meaningful touch. Packaged beautifully and ready for gifting, this necklace, with its adjustable chain, is a symbol of everlasting love and style. Pendant dimensions: 0.39 inch (L) / 0.67 inch (H).',
                'main_image_url_snapshot' => null,
                'price' => 39.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/15.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0B2Q58QRQ',
                'product_name_snapshot' => 'VOPIPO Adjustable 18k Gold Plated CZ Leaf Open Ring Dainty Double Cubic Zirconia Olive Branch Leaves Band Stacking Finger Rings Minimalist Cute Statement Ring Jewelry for Women Teen Girls',
                'amazon_url' => 'https://amzn.to/4hqTWfg',
                'your_notes' => 'Elevate your style with our Adjustable Gold Ring for Women! Featuring an inner diameter of 0.67\" (17mm) and a width of 0.28\" (7mm), this double cubic zirconia open ring is adjustable to fit any finger perfectly. Crafted in 18k gold-plated brass with two tiny cubic zirconia in different shapes, it adds a touch of sparkle to your everyday look. Hypoallergenic and non-tarnishing, this ring offers comfort for long-time wear and was even spotted on Selena Gomez, shining in Messika diamond jewelry. Packaged in a chic dark blue gift box, it\'s a stunning gift for yourself or a loved one, suitable for any special occasion or an ordinary day.',
                'main_image_url_snapshot' => null,
                'price' => 11.99,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/16.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0C9W66399',
                'product_name_snapshot' => 'Peora Sterling Silver Wave Pendant Necklace for Women in Various Gemstones, Pear Shape Italian Chain',
                'amazon_url' => 'https://amzn.to/40H8UrW',
                'your_notes' => 'Elegance in a Teardrop: Peora\'s Genuine Aquamarine Necklace. Crafted from 0.50 Carat Pear Shape gem in Ice Blue, set in 925 Sterling Silver with a wave motif. Hypoallergenic and comfortable for sensitive skin. Perfect 18-inch Italian Chain. Ideal gift in our signature box for any occasion. Experience Peora\'s quality – handmade, lustrous, and certified',
                'main_image_url_snapshot' => null,
                'price' => 50.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/17.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B07QSJD6ZJ',
                'product_name_snapshot' => 'Uloveido Valentine\'s Day Special: Blossom in Love with Our Beautiful Red Enamel Rose Ring! Sparkle with Pear Cut Cubic Zirconia - The Perfect Cocktail Summer Ring for Her!',
                'amazon_url' => 'https://amzn.to/3C6EO7N',
                'your_notes' => 'Elevate your elegance with our Beautiful Cocktail Ring – a unique rose design adorned with a platinum-plated band, ruby center, and Austrian crystals. Versatile for daily wear, it comes in a chic gift bag with an extra bracelet. The perfect gift for any occasion – Christmas, Valentine\'s Day, Mother\'s Day, and more. Make a statement of style and sophistication with this captivating piece',
                'main_image_url_snapshot' => null,
                'price' => 9.99,
                'item_type' => 'rings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/18.jpg',
                'category_slug' => 'rings',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0BLZ1HWPJ',
                'product_name_snapshot' => 'Pink Red Acrylic Love Heart Earrings Unique Lightweight Long Tassel Resin Hearts Dangle Drop Earrings for Women Girls Valentine\'s Day Jewelry',
                'amazon_url' => 'https://amzn.to/3Eel9TL',
                'your_notes' => 'Design: The Acrylic Love Heart Earrings feature different colors and a design with three love hearts and the letters LOVE.



Size: 2.5cm*6.5cm, Weight: 8g/pair.



Material: Made of high-quality acrylic and alloy, healthy, durable, lead-free, nickel-free, fade-resistant, and skin-friendly.



Occasion: Popular among women and girls, suitable for various occasions such as Valentine\'s Day, Christmas, Thanksgiving, beach outings, work, school, parties, and vacations.



Ideal Gift: Showcase the beauty and elegance of women. Perfect for your wife, girlfriend, mother, fiancée, daughter, or yourself.',
                'main_image_url_snapshot' => null,
                'price' => 4.69,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/19.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0928W93DC',
                'product_name_snapshot' => 'Summer Chic: Explore the Allure of Handmade HEIDKRUEGER Rattan Bracelets - Lightweight, Wicker Elegance for Women and Girls',
                'amazon_url' => 'https://amzn.to/40oE85F',
                'your_notes' => 'Embrace the artistry of handcrafted elegance with the HEIDKRUEGER Rattan Bracelet. Carefully woven with wicker braid and natural straw, this fabulous bracelet is a lightweight and unique summer statement piece. Crafted from hypoallergenic materials, it\'s suitable for sensitive skin, making it a fabulous and thoughtful gift for your loved ones. The intricate design, boasting a diameter of 2.6 inches and a weight of 0.3oz, adds a touch of natural charm to any outfit. Perfect for occasions like graduation, birthdays, Mother\'s Day, Christmas, and weddings, this handmade statement bangle exudes a timeless and chic allure, making you stand out with elegance and individuality.',
                'main_image_url_snapshot' => null,
                'price' => 8.99,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/20.png',
                'category_slug' => 'bracelets',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B09JFMKW6L',
                'product_name_snapshot' => 'GOOJIDS Surfer Heishi Clay Bead Bracelets for Women Bohemian Stackable Evil Eye Vinyl Disc Beaded Stretch Bracelets Elastic Layering Friendship Bracelets Boho Jewelry',
                'amazon_url' => 'https://amzn.to/4g9J7ge',
                'your_notes' => 'Adorn your wrists with our Stackable Beaded Bracelet Set, featuring vibrant and stackable surfer-inspired bracelets. Crafted with 6mm African vinyl disc beads and elastic strings, these bohemian bracelets are easy to wear, adjustable, and effortlessly stylish. The set includes 7 strands of heishi beads and gold-plated sequins, creating a playful and colorful mix for a fashion-forward look. Perfect for any occasion, from beach outings to parties and daily wear, these fashionable bracelets are suitable for women of all ages. Embrace the essence of African vinyl disc charm, lightweight, waterproof, and perfect for gifting a stylish accessory that won\'t go unnoticed.',
                'main_image_url_snapshot' => null,
                'price' => 9.99,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/21.png',
                'category_slug' => 'bracelets',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0CPMH9LJ1',
                'product_name_snapshot' => 'Internist Bracelet, You are unstoppable, Cuban Link Chain Necklace',
                'amazon_url' => 'https://amzn.to/3PQian3',
                'your_notes' => 'Introducing our Internist Engraved Stainless Steel Chain Bracelet, a timeless and heartfelt gift for any special occasion. Crafted from 100% high-quality stainless steel, this chain bracelet exudes both elegance and environmental consciousness. With a length of approximately 8.5 inches, it gracefully adorns any wrist, making it a versatile and universally flattering accessory.



This exquisite piece is a handmade masterpiece, showcasing exceptional craftsmanship and attention to detail. The bracelet arrives in a luxurious bag of the highest quality, complete with a jewelry pouch, adding an extra touch of sophistication to your gift',
                'main_image_url_snapshot' => null,
                'price' => 19.95,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/22.jpg',
                'category_slug' => 'bracelets',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0B4B83LM3',
                'product_name_snapshot' => 'Kate Lynn Rise Above - Rise From the Ashes Phoenix Earrings, Sterling Silver Dangle Earrings with Crystals from Austria',
                'amazon_url' => 'https://amzn.to/4anr2Kx',
                'your_notes' => 'Introducing our Elegant Phoenix Earrings, a symbol of hope and rebirth, designed to brighten your life. This beautiful pair is a perfect gift for women or teens on various occasions, featuring sparkling crystals from Austria. Packaged in a stylish jewelry box, these earrings make a charming surprise for Christmas, Birthday, Anniversary, Valentine\'s Day, Mother\'s Day, Graduation, and more. Crafted with care, they are lead-free, nickel-free, and hypoallergenic, ensuring both style and comfort. Elevate your everyday look or gift a touch of glamour with these eye-catching earrings.',
                'main_image_url_snapshot' => null,
                'price' => 37.49,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/23.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B08DR8VB3Y',
                'product_name_snapshot' => 'Believe in You Sterling Silver Always Remember You are Braver Than You Believe Jewelry Pendant Necklace',
                'amazon_url' => 'https://amzn.to/4h21Trd',
                'your_notes' => 'Introducing our Heart Shape Cubic Zirconia Pendant Necklace, a perfect gift expressing love and encouragement. Crafted with care from nickel-free, lead-free, hypoallergenic 925 sterling silver, this necklace ensures comfortable wear, ideal for those with sensitive skin. The heart pendant, engraved with the inspiring words \"Always Remember You\'re braver than you believe, stronger than you seem, and smarter than you think and LOVED more than you know,\" adds a meaningful touch. The necklace features a delicate Box chain with a lobster-claw clasp, measuring 23.5 mm by 21 mm. With a width of 0.8 mm and a length of 45.3 mm (18\"), this elegant piece weighs 4.85 g. Gift this timeless necklace to someone special and let the sentiment shine through',
                'main_image_url_snapshot' => null,
                'price' => 30.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/24.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0CC1XRMFT',
                'product_name_snapshot' => 'Silver Opal Pendant Necklace - Unearthing the Magic of this Kaleidoscopic Gemstone',
                'amazon_url' => 'https://amzn.to/4hqULom',
                'your_notes' => 'Introducing the Silver Opal Pendant Necklace, a true embodiment of enchantment and allure. Crafted with meticulous attention to detail, this necklace showcases a kaleidoscopic synthetic opal, carefully nestled within a stunning 925 silver-plated pendant. The opal\'s mesmerizing hues and iridescence create a captivating display of magic that effortlessly enhances any ensemble.



Measuring 16 inches in length, the chain is adorned with a 2-inch extender, allowing for a comfortable and customized fit. This adjustable feature ensures that the necklace complements your neckline perfectly. Its striking design and opulent opal stone make it an exquisite accessory that adds a touch of sophistication and beauty to any occasion.



The Silver Opal Pendant Necklace is further embellished with colored zircon accents, which add a subtle hint of sparkle and elegance. Whether worn during the day or as a statement piece for a special evening, this necklace is destined to become a cherished favorite in your jewelry collection.



Unearth the magic of this remarkable gemstone and elevate your style with the Silver Opal Pendant Necklace—a true embodiment of timeless beauty and enchantment.',
                'main_image_url_snapshot' => null,
                'price' => 22.98,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/25.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B095C38VYX',
                'product_name_snapshot' => 'Dorella Birthday Gifts Forever Love Infinity Necklace for Wife Mom 925 Sterling Silver Pendant March April May Birthstones Aquamarine Jewelry for Women',
                'amazon_url' => 'https://amzn.to/4hfp80O',
                'your_notes' => 'Introducing our breathtaking Aquamarine Necklace for Women. This stunning piece features a Love Infinity Half Moon Pendant setting adorned with high-quality Blue Aquamarine and AAA sparkle cubic zirconia gemstones, elegantly crafted in 18K white gold plated over sterling silver. The pendant comes with a 20\" adjustable chain, ensuring the perfect fit every time.

Looking for the perfect Birthday Gifts for Women? Look no further! Our Aquamarine Necklace comes beautifully packaged in a fine jewelry gift box, making it an ideal choice for birthdays, anniversaries, or any special occasion. Whether it\'s for your wife, mom, daughter, grandma, or girlfriend, this necklace is sure to delight.

As the March Birthstone Pendant Necklace, it features a round brilliant March birthstone Blue Aquamarine at its center, delicately engraved with the heartfelt message \"You are the Only One in My Heart.\" Its elegant design and sparkling effect make it a timeless gift choice for any occasion, showcasing a unique and fashionable flair.',
                'main_image_url_snapshot' => null,
                'price' => 59.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/26.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B08678QV6Z',
                'product_name_snapshot' => 'Wooden Hoop Earrings for Women Girls Retro Black African Bohemian Wood Teardrop Geometric Lightweight Dangle Drop Earrings',
                'amazon_url' => 'https://amzn.to/3E40WAe',
                'your_notes' => 'Introducing the EMMMSUN Wooden Teardrop Earrings, where nature meets elegance in a fashion-forward design. Crafted with a perfect blend of wood and alloy, these earrings exude a natural, unique, and chic vibe that effortlessly complements any fashion look.



Measuring 5.7 by 3.8 centimeters, these lightweight earrings are delicately shaped like teardrops, adding a touch of graceful charm to your ensemble. The elegant design is both eye-catching and versatile, making it a must-have accessory for any occasion.



Comfort is key with the EMMMSUN Wooden Teardrop Earrings. Their lightweight construction ensures a comfortable fit, allowing you to wear them effortlessly throughout the day or night. Each set contains two pairs, offering versatility and options to suit your style.



These earrings make a perfect gift for your loved ones. Whether it\'s your partner, girlfriend, wife, daughter, or female friends, the EMMMSUN Wood Earrings are a thoughtful choice for occasions like Valentine\'s Day, birthdays, Christmas, and more. Show your appreciation with a gift that embodies beauty and style.',
                'main_image_url_snapshot' => null,
                'price' => 5.99,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/27.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0CQXDLCP8',
                'product_name_snapshot' => 'Handmade Big Hoop Earrings, Boho Earrings, Colorful Wooden Leather Earrings, Wood Circle, Statement Jewelry for Women',
                'amazon_url' => 'https://amzn.to/40HP2oC',
                'your_notes' => 'Experience the artistry of our Handcrafted Leather and Wood Hoop Earrings—an exquisite blend of modern and vintage, balance and harmony. Inspired by the duality of personalities, these earrings are a true expression of individuality and style.



Step into the realm of boho-chic with our Trendy Boho Hoop Earrings. Featuring a captivating design with colorful paintings, these double circle dangle earrings are a statement piece that elevates any outfit. The large and small hoops are gracefully intertwined and suspended from supple leather, creating a mesmerizing visual effect.



Crafted with utmost care and attention to detail, these earrings are the work of our talented independent artists. Each material, including leather, wood, and zinc alloy, is thoughtfully chosen to ensure a high-quality finish. The gentle textures add a bohemian touch, making these earrings truly unique. Plus, they are free from lead, cadmium, and nickel, ensuring your safety and peace of mind.



Measuring 2.44 inches in height and 1.38 inches in width, these earrings are designed to make a bold statement. Their lightweight construction makes them comfortable for everyday wear, whether you\'re heading to the office or enjoying a night out on the town.',
                'main_image_url_snapshot' => null,
                'price' => 7.99,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/28.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B07MFRGGLP',
                'product_name_snapshot' => 'Tribal Organics Earrings Wooden Tropical Feathered Style Fake Gauges',
                'amazon_url' => 'https://amzn.to/40qKy4d',
                'your_notes' => 'Discover the essence of craftsmanship with our High-Quality Hand Carved Wood Earrings. This exquisite pair is meticulously crafted from organic wood, resulting in a truly unique and one-of-a-kind accessory for regular pierced ears.



Measuring approximately 3/4\" by 2-1/2\", these earrings are designed to be lightweight and comfortable, ensuring a delightful wearing experience. Their compact size adds a touch of elegance and versatility, making them suitable for various occasions.



When it comes to presentation, we\'ve got you covered. Each pair of earrings comes in a complimentary gift box, allowing you to effortlessly add a card and create a ready-to-give present. It\'s the perfect choice for special occasions or simply to show your appreciation to someone dear.',
                'main_image_url_snapshot' => null,
                'price' => 20.99,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/29.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0CSJ8SGXG',
                'product_name_snapshot' => '14K Two Tone Gold and Silver Convertible Link Chain Hoop Earrings - Elegant Drop Dangle Paperclip Design for Women and Girls',
                'amazon_url' => 'https://amzn.to/3C6FwBZ',
                'your_notes' => 'Introducing our Delicate Paperclip Earrings, versatile accessories that effortlessly elevate any look. With a diameter of 10-12mm and a length of 22mm when linked together, they suit most earlobes. Crafted from hypoallergenic 925 sterling silver with gold posts, they\'re safe for sensitive skin and boast a chic, modern design.



These dainty earrings feature a unique double hoop paperclip link design, adding a touch of sophistication to any outfit. Lightweight at 0.15oz, they\'re comfortable to wear and won\'t weigh down your earlobes.



Perfect for various occasions, from casual outings to special events, these earrings complement any ensemble. Packaged in a beautiful gift box, they make an excellent present for loved ones on Mother\'s Day, Valentine\'s Day, birthdays, or other celebrations. Add an elegant and charming element to your style with our Delicate Paperclip Earrings.',
                'main_image_url_snapshot' => null,
                'price' => 13.99,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/30.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B01H5S2UH0',
                'product_name_snapshot' => 'Hoops & Loops 925 Sterling Silver Double Circle Round-Tube Polished Click-Top Hoop Earrings for Women and Teen Girls, 15mm 20mm 25mm',
                'amazon_url' => 'https://amzn.to/42imH9u',
                'your_notes' => 'Introducing our Hoops & Loops Round Double Circle Hoop Earrings, offering a modern stacked-hoop look that adds flair to any outfit. Crafted from solid 925 sterling silver, these earrings are nickel-free, lead-free, and feature a special anti-tarnish coating for long-lasting shine.



Measuring 15mm (0.59 inches) in diameter, these earrings are lightweight, high polished, and perfect for all-day wear. Available in five color options including Silver, Yellow Gold, Rose Gold, Two-Tone Silver & Yellow, or Rose Gold Flashed Sterling Silver, they suit any style preference.



Each order comes carefully hand-packed in a Jewelry Gift Pouch, making it ready for gifting on any occasion, from Christmas to birthdays to anniversaries. Proudly American owned and operated, Hoops & Loops is committed to providing premium quality and craftsmanship at affordable prices. Explore more trendy designs by searching Hoops & Loops on Amazon.',
                'main_image_url_snapshot' => null,
                'price' => 24.99,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/31.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B09B369RT9',
                'product_name_snapshot' => 'Nine West \"Classics\" Trio Stretch Bracelet',
                'amazon_url' => 'https://amzn.to/4g6l4PA',
                'your_notes' => 'Introducing the Nine West \"Classics\" Trio Stretch Bracelet, a stunning mix of metals that adds sophistication to any ensemble. This trio set features three bracelets crafted from a combination of metals, offering versatility and elegance.



Each bracelet is designed with stretch for easy wearing and is adorned with classic detailing that exudes timeless charm. Whether worn individually or stacked together, these bracelets are perfect for adding a touch of understated glamour to any outfit.



The mix of metals ensures compatibility with various jewelry pieces, allowing for effortless styling and coordination. Elevate your look with the Nine West \"Classics\" Trio Stretch Bracelet, a must-have accessory for any jewelry collection.',
                'main_image_url_snapshot' => null,
                'price' => 31.99,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/32.jpg',
                'category_slug' => 'bracelets',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B092VCGQ8G',
                'product_name_snapshot' => 'Professional 14k Gold/White Gold Plated Teardrop Hoop Earrings: Glam Statement Jewelry',
                'amazon_url' => 'https://amzn.to/4asKmWX',
                'your_notes' => 'These exquisite oval earrings feature a unique half-open design, blending simplicity with elegance. Crafted from hypoallergenic 925 Sterling Silver and plated with 14K gold, they offer durability and style without nickel, ensuring comfort for sensitive skin. Measuring 30mm * 18mm, they boast a lightweight build for effortless wear. Versatile and timeless, they make an ideal gift for any occasion, from birthdays to anniversaries. Gelanmeng provides exceptional customer service, offering a 60-day free return or exchange policy. Elevate your look with these high-quality, environmentally friendly earrings, designed to complement any outfit with understated sophistication.',
                'main_image_url_snapshot' => null,
                'price' => 13.99,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/33.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B01IVVEXUC',
                'product_name_snapshot' => 'Professional Elegance: Bocar 2-Layer Long Link Chain Necklace with Crystal Beads - Perfect for Parties and Gifts',
                'amazon_url' => 'https://amzn.to/4asKyp9',
                'your_notes' => 'About this item: Elevate your professional look with this Bocar 2-Layer Long Link Chain Necklace adorned with crystal beads and wood accents. Versatile for casual outings or formal occasions like parties, weddings, or office settings. Nickel and lead-free, conforming to California Proposition 65 standards, ensuring safety and quality. The necklace measures 37.8 inches in length and weighs 2.15 ounces, offering comfort and style. Packaged in a gift box, it\'s an ideal present for your loved ones, making it a perfect gift for girlfriends, loved ones, and friends.',
                'main_image_url_snapshot' => null,
                'price' => 13.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/34.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0B29HZP9P',
                'product_name_snapshot' => 'PROFESSIONAL INSPIRED VOICES: Inspirational Bracelets for Women - Motivational Jewelry, Beaded Stretch Bracelets, Encouragement Gifts',
                'amazon_url' => 'https://amzn.to/3Ws3IWg',
                'your_notes' => 'Empower with Style: Our Inspirational Bracelets for Women embody strength and beauty, featuring \'STRENGTH\' engraved on 14K Gold plated cubes. Motivate with our affirmation bracelets, reminding every woman of her resilience. Whether a gift for yourself or a loved one, our bracelets symbolize overcoming challenges. Packaged in a chic box with a matching motivational message card, they\'re ready for gifting. As a family-owned business, we prioritize quality and customer satisfaction. Explore our INSPIRED VOICES store on Amazon for more empowering designs.',
                'main_image_url_snapshot' => null,
                'price' => 18.99,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/35.jpg',
                'category_slug' => 'bracelets',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B09NSZ2F9W',
                'product_name_snapshot' => 'Boho Hippie Necklace for Women, Ethnic Gold Necklace, Handmade with Natural Crystal, Adjustable Length, Pendant Necklace, Aesthetic Jewelry, Women Gift',
                'amazon_url' => 'https://amzn.to/40raQmN',
                'your_notes' => 'Fall in love with this stunning Gold Necklace for Women! Handmade in Thailand with meticulous care, this Bohemian crystal necklace from Stand Tall Accessories is a chic statement piece. It\'s the perfect gift for any occasion, beautifully packaged and ready to impress. Crafted with premium materials, including brass and colorful waxed cotton, it offers a unique drawstring closure for adjustable styling. Embrace its beauty and make a lasting impression.',
                'main_image_url_snapshot' => null,
                'price' => 29.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/36.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0BTPMTFM4',
                'product_name_snapshot' => 'Boho Hippie Colorful Ceramic Bead Strand Necklace Bohemian Jewelry Gift for Women',
                'amazon_url' => 'https://amzn.to/4h2AG7X',
                'your_notes' => 'Discover the beauty of this long boho necklace, adorned with a captivating combination of ceramic beads in different shapes, sizes, and colors. Its adjustable sliding brown waxed cord allows for easy wear—simply put it over your head and you\'re ready to go.



Make a statement with this stunning piece of bohemian jewelry—a perfect gift for Valentine\'s Day, Christmas, birthdays, or anniversaries. It\'s a unique addition to any jewelry collection, guaranteed to make you stand out with style and individuality.



Rest assured, if you encounter any issues with your purchase, our dedicated team is here to assist you. Contact us, and we\'ll promptly resolve any concerns to ensure your satisfaction.',
                'main_image_url_snapshot' => null,
                'price' => 12.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/37.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0BFDBG2V6',
                'product_name_snapshot' => 'Gold Statement Necklace Costume Jewelry Sets for Women,Aesthetic collar Necklace and Earring Sets Fashion Accessories for Bridal Party',
                'amazon_url' => 'https://amzn.to/4h0DPVS',
                'your_notes' => 'Exquisite Gold Jewelry Sets for Women: 3-Layer Hand-Crafted Chain Necklace with Dangling Earrings. Versatile and High-Quality, Perfect for Any Occasion. Thoughtfully Designed Gift in a Beautiful Box',
                'main_image_url_snapshot' => null,
                'price' => 18.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/38.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B07FMH4Y63',
                'product_name_snapshot' => 'Rainbow Thread Geometric Dangle Earrings: Bronze & Teal Bohemian Multi Color Jewelry for Women',
                'amazon_url' => 'https://amzn.to/4atr03D',
                'your_notes' => 'Vibrant and Unique: Colorful Threader Earrings with Geometric Dangles to elevate your outfit and add a burst of color. Western-inspired Jewelry: Teal and bronze metal with rainbow tassel thread beautifully wrapped through the top. Measures 7.8 x 3.7 cm.



Versatile Style: Perfect for a beach day, pride festival, formal or casual wear. Embrace the boho western chic aesthetic with these earrings. Gifts for Her: Ideal for birthdays, Christmas, Valentine\'s, anniversaries, or simply because. Comes in a jewelry gift bag designed by Soul Statement.



Rainbow Multicolor Design: Red, Orange, Yellow, Green, Blue, Purple, Teal. Wear this set to complement any style.',
                'main_image_url_snapshot' => null,
                'price' => 17.95,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/39.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0CN6BHDDF',
                'product_name_snapshot' => 'Hadskiss Jewelry Set for Women, Necklace Dangle Earrings Bracelet Set, White Gold Plated Jewelry Set with White AAA Cubic Zirconia, Allergy Free Wedding Party Jewelry for Bridal Bridesmaid',
                'amazon_url' => 'https://amzn.to/4jm1cuv',
                'your_notes' => 'Premium Quality Material: This Women\'s Jewelry Set is crafted with 18K White Gold Plating and Clear Cubic Zirconia. It\'s hypoallergenic, lightweight, and shines beautifully.



Perfect Jewelry Set Size: The necklace, dangle earrings, and bracelet are designed to fit comfortably. They\'re versatile for any occasion and easy to match with different outfits.



Ideal Gift Choice: This Jewelry Set comes in a gift box, making it a perfect present for birthdays, weddings, graduations, or special occasions. It\'s a thoughtful gift for loved ones.



Customer Satisfaction: We prioritize your satisfaction. If you have any concerns, please contact us, and we\'ll strive to provide a satisfactory solution.',
                'main_image_url_snapshot' => null,
                'price' => 34.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/40.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2025-01-21 18:06:36',
                'updated_at' => '2025-01-21 18:06:36',
            ],
            [
                'amazon_asin' => 'B0C61FLKFM',
                'product_name_snapshot' => 'SWEETV Cubic Zirconia Wedding Bridal Bracelet for Brides,Bridesmaids, Silver Crystal Elegant Tennis Bracelet for Women Prom Jewelry Gifts',
                'amazon_url' => 'https://amzn.to/3V5CtPK',
                'your_notes' => 'Original Design: This bridal bracelet showcases a stunning \"leafy\" pattern of marquise-cut AAAAA grade cubic zirconia stones. It\'s perfect for brides, bridesmaids, or any special occasion.



Adjustable Length: The bracelet measures 18cm (7.08 inches) and can be extended up to 23cm (9.05 inches) with a 1.97-inch extender. This allows for easy adjustment to achieve the perfect fit.



Finest Quality Cubic Zirconia: Crafted with AAAAA grade cubic zirconia stones, this bracelet sparkles like diamonds, ensuring endless shine and durability. The lightweight brass metal construction protects the skin from rubbing.



Gift-Wrapping Included: Presented in a beautiful gift box, this CZ bracelet is easy to match with any outfit. It\'s an excellent choice for bridal occasions, weddings, proms, parties, and special celebrations like birthdays, anniversaries, Valentine\'s Day, or Mother\'s Day.



Versatile Any-Occasion Accessory: Whether it\'s a wedding, prom, bridesmaids\' gathering, pageant, gala, formal affair, or holiday celebration, this bracelet is a perfect accessory. It adds a touch of elegance to any special occasion.',
                'main_image_url_snapshot' => null,
                'price' => 20.99,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/41.jpg',
                'category_slug' => 'bracelets',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B095SP3QGL',
                'product_name_snapshot' => 'SWEEETV Bridal Wedding Earrings for Brides Bridesmaides-Marquise Teardrop Earrings for Women, Cubic Zirconia Drop and Dangle Earrings for Prom or Pageant,Jewelry',
                'amazon_url' => 'https://amzn.to/3V9kUy7',
                'your_notes' => 'Design: These bridal earrings feature shinning cubic zirconia in a leafy setting, creating a feminine and romantic glam look.



Material: Made with AAAAA grade cubic zirconia and silver-plated copper, these earrings are hypoallergenic and safe for sensitive ears.



Size: Measuring 41.2 cm (1.570.47 inch), they are lightweight and comfortable to wear.



Any-Occasion Accessory: Perfect for weddings, proms, parties, and special occasions.



Elegant Present: Beautifully packaged, they make a great gift for brides, girlfriends, wives, and more.',
                'main_image_url_snapshot' => null,
                'price' => 21.99,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/42.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B0836WXZP9',
                'product_name_snapshot' => 'SWEETV Cubic Zirconia Bridal Earrings for Women Brides, Crystal Chandelier Wedding Drop Earrings for Prom or Pageant',
                'amazon_url' => 'https://amzn.to/4aNvf90',
                'your_notes' => 'op, adding a touch of sparkle to any wedding day look.



Material: Made with the finest quality AAAAA grade cubic zirconia and silver-plated copper, these earrings are hypoallergenic, nickel-free, and lead-free. They feature delicate copper posts with bullet clutch earring backs, ensuring safety for sensitive ears.



Size: The dangle earrings measure 2.95 inches (7.5cm) high and 0.47 inches (1.2cm) wide. They are lightweight and comfortable to wear. Please note that there may be slight variations due to manual measurement.



Gift-Wrapping: Presented in a beautiful gift box, these earrings are ideal for various occasions such as dating, parties, weddings, and daily wear. They are perfect for formal affairs, bridesmaids, mother of the bride, pageants, homecoming, holiday parties, special occasions, birthdays, Valentine\'s Day, Mother\'s Day, or anniversary gifts.',
                'main_image_url_snapshot' => null,
                'price' => 21.99,
                'item_type' => 'earrings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/43.jpg',
                'category_slug' => 'earrings',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B0B728TT61',
                'product_name_snapshot' => 'Disney Minnie Mouse Silver Plated Cubic Zirconia Mommy & Me Necklace Set - Minnie Mouse Necklace for Mom and Daughter Jewelry',
                'amazon_url' => 'https://amzn.to/4dZdTJb',
                'your_notes' => 'Celebrate the Bond Between Mother and Daughter with Disney Magic: This matching Minnie Mouse pendant necklace set is perfect for mother and daughter. The set includes two Minnie Mouse necklaces for a coordinated and stylish look.



Two-Tone Minnie Mouse Design: Crafted from silver-plated brass, these necklaces feature a cubic zirconia Minnie Mouse pendant with an 18kt pink Flash-Plated bow, adding a cute pop of color.



Secure Closure and Adjustable Length: The mother and daughter necklace set comes in 16-inch and 18-inch lengths, with a lobster claw closure and a 2-inch extender chain for a secure fit.



Disney Gifts for Mother and Daughter: Presented in an elegant Disney gift box, these Minnie Mouse necklaces make a perfect gift for birthdays, Christmas, Mother\'s Day, and other special occasions.



Dedicated to Quality & Style: Established in 1977, SallyRose is a New York-based company known for its commitment to quality and style. They offer a wide range of affordable, finely crafted, and on-trend jewelry and gift items for the whole family.',
                'main_image_url_snapshot' => null,
                'price' => 39.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/44.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B079K7V8ZJ',
                'product_name_snapshot' => 'Make It Real: Juicy Coture Chains & Charms, DIY Bracelet Kit, Includes 12 Juicy Coture Charms, Makes Up to 5 Bracelets, Helps Young Girls Develop Fine Motor & Visual Skills, for Ages 8 and up',
                'amazon_url' => 'https://amzn.to/3RbxGei',
                'your_notes' => 'JUICY COUTURE CHAINS & CHARMS: Let young girls design their own fashionable Juicy jewelry with this DIY bracelet kit. They can create stunning charm bracelets that showcase their unique style and make a statement!



HELPS KIDS DEVELOP REAL-WORLD SKILLS: As they craft Juicy Couture Chains & Charms Bracelets, young girls will enhance their fine-motor, visual, and cognitive skills. It\'s a fun and expressive way for them to develop important abilities while enjoying the creative process.



EVERYTHING INCLUDED: This bracelet kit provides everything your child needs to make up to 5 bold and eye-catching bracelets. It includes 12 Juicy Couture charms, beads, metallic pieces, ribbon, chains, and detailed instructions.



GREAT GIFT IDEA: Make It Real\'s developmental toys are excellent gifts for girls and tweens. They not only provide entertainment but also encourage skill development, preparing young girls to become future leaders and creators.



PERFECT FOR AGES 8+: Designed for children aged 8 and above, this Juicy Couture Chains & Charms kit offers an engaging and age-appropriate activity that allows girls to express their creativity and create fashionable accessories they\'ll be proud to wear.',
                'main_image_url_snapshot' => null,
                'price' => 19.1,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/45.jpg',
                'category_slug' => 'bracelets',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B013YF2OJ2',
                'product_name_snapshot' => 'BEADNOVA Crystal Necklaces for Women Crystal Pendant Gemstone Necklace for Spiritual Energy Healing Hexagonal Pendant Divination for Men (18 Inches Stainless Chain)',
                'amazon_url' => 'https://amzn.to/45fewKv',
                'your_notes' => 'Experience elegance and healing with our Amethyst Necklace. Crafted with a 1 amethyst crystal pendant and a stainless steel chain, it radiates positive energy and promotes balance. This protective stone relieves stress and soothes emotions. Hypoallergenic and perfect for any occasion, it\'s the ideal gift for your loved ones.',
                'main_image_url_snapshot' => null,
                'price' => 6.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/46.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B0CR15ZGY3',
                'product_name_snapshot' => 'CrystalTears Healing Crystal Heart Necklace for Women Rose Flower Wrapped Natural Stone Pendant Necklaces Reiki Spiritual Quartz Gemstone Necklace Crystal',
                'amazon_url' => 'https://amzn.to/4e9urhF',
                'your_notes' => 'Express love with our Women Crystal Heart Necklace. Stylish and delicate, it\'s perfect for daily wear or special occasions. The rose flower-wrapped healing crystal heart pendant brings luck, love energy, and confidence. Ideal as a gift for birthdays, anniversaries, or holidays. Symbolizing wisdom and strength, our Amethyst Crystal Necklace is a beautiful choice. Packaged in a velvet pouch, it\'s perfect for wives, girlfriends, or mothers. The heart pendant measures 1.65x1.26 inches, and the chain is 24 inches long. Made with natural crystal gemstones, each necklace is unique. Contact us with any questions.',
                'main_image_url_snapshot' => null,
                'price' => 11.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/47.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2024-06-03 09:47:42',
                'updated_at' => '2024-06-03 09:47:42',
            ],
            [
                'amazon_asin' => 'B09G2TMVYM',
                'product_name_snapshot' => 'EUDORA Sterling Silver Initial Necklaces for Women, Classic 26 Letter Neckless Gifts for Girls, Sister, Mother Daughter, 18 inch Chain',
                'amazon_url' => 'https://amzn.to/3yIuJeZ',
                'your_notes' => 'Express your love with our Your Name, it\'s My Love  initial necklace. This beautiful, highly polished piece adds a touch of elegance to any outfit. It\'s the perfect presentation and a unique gift for that special someone in your life.



Our necklace is made of sterling silver and rhodium plated, ensuring it\'s lead-free and nickel-free. If you appreciate class and quality, this initial necklace is for you! The rhodium plated process prevents tarnishing and increases durability.



Choose the letter that represents you or your loved one. Wearing a personalized initial necklace adds a special touch. Our EUDORA initial necklace is the perfect size and comes with an 18\" chain, ideal for showcasing the jewel in your daily life.



Surprise her with a gift she\'ll cherish. Our necklace comes in an elegant jewelry box, making it perfect for any occasion. Whether it\'s a birthday, Valentine\'s Day, Christmas, anniversary, or simply a reminder of your love, it\'s a thoughtful gesture.



To keep your EUDORA silver jewelry shiny and clean, follow these tips: 1. Store them in a plastic zipper bag or jewelry box. 2. Avoid exposing them to humidity, such as in the bathroom. 3. Wear it! The friction against your skin and clothes will help keep it clean and sparkling.



Choose love and elegance with our * Your Name,* it\'s My Love *  initial necklace.',
                'main_image_url_snapshot' => null,
                'price' => 29.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/48.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B08F4VZZNQ',
                'product_name_snapshot' => 'CZ Stone Eye of Horus Pendant Necklace Symbol of Protection Egyptian 18K Gold Plated Stainless Steel Jewelry',
                'amazon_url' => 'https://amzn.to/455UToc',
                'your_notes' => 'Experience the power of protection with our Eye of Horus jewelry. Worn to ward off evil, it symbolizes the triumph of light over darkness. In Ancient Egypt, it served as an amulet, providing protection in the afterworld. This talisman represents health, power, and protection.



GUNGNEER proudly presents our premium stainless steel Egypt jewelry. We are dedicated to providing our customers with the highest quality pieces. With passion and attention to detail, we create protection jewelry that delivers the best experiences.



Our Horus Eye necklace is available in 20\" and 28\" lengths, and as a bonus gift, it comes with a lava stone bracelet. The Ankh cross bracelet, made with stainless steel, adds a touch of shine to any outfit. The package includes two pieces: 1 necklace and 1 bracelet.



This amulet is a gift worth giving. It holds deep symbolism of eternity, protection, and equality, making it a cherished keepsake for your loved ones. Whether as a special gift or a mystical treasure, it carries profound meaning.



At GUNGNEER Store, we prioritize your satisfaction with our strength jewelry. If you encounter any issues, please don\'t hesitate to reach out to us. We are here to assist you and ensure the best experience possible. Whether you are a new customer or a loyal one, our promise is to create an exceptional experience for you.',
                'main_image_url_snapshot' => null,
                'price' => 16.99,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/49.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B0C7ZWRPDM',
                'product_name_snapshot' => 'Vintage Gold Plated Pendant Chain Necklace Jewelry Gift for Women',
                'amazon_url' => 'https://amzn.to/4c456E0',
                'your_notes' => 'Embrace the unity of Africa with our pendant featuring multiple nations\' flags. This symbol of a united continent is prepared to overcome any challenges it faces.



Our necklace is meticulously crafted with stainless steel, ensuring durability, strength, and flexibility that will last for years to come. Its simplicity exudes elegance.



The small and uniquely designed dainty necklace is suitable for everyone. The chain measures 19.7\" in length, while the pendant itself is 1.37\" x 1.57\" in size.



Capture the sparkle in your loved one\'s eyes with this vintage, western-style piece of jewelry from MINACHI. It\'s a preppy and colorful gift that will bring joy to your loved ones.



We stand behind the quality of our Gold Africa Necklace. If, for any reason, you\'re not completely satisfied, simply return it within 90 days of purchase for a full refund. No questions asked! Your satisfaction is our priority.',
                'main_image_url_snapshot' => null,
                'price' => 9.98,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/50.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B08XZ1H7JB',
                'product_name_snapshot' => 'Sorrelli Delicate Cross Pendant Necklace, Antique Gold-Tone Finish, Crystal',
                'amazon_url' => 'https://amzn.to/4aQZxHH',
                'your_notes' => 'The Sorrelli Delicate Cross Pendant Necklace is a beautifully handcrafted piece of jewelry that exudes elegance and style. It features an antique gold-tone finish, giving it a vintage and timeless appeal. The pendant is in the shape of a delicate cross, adding a touch of spiritual significance to the design.



Crafted from high-quality brass, the necklace is plated with an antiqued gold tone, which enhances its overall aesthetic and durability. The pendant is adorned with sparkling Swarovski crystals, adding a touch of glamour and sophistication. Due to the nature of handmade items, each necklace may vary slightly in size, shape, and color, making it a unique and one-of-a-kind piece.',
                'main_image_url_snapshot' => null,
                'price' => 38.42,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/51.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B0BL37HSMQ',
                'product_name_snapshot' => 'TEAMER Lotus Necklace Stainless Steel Flower of Life Necklace Yoga Meditation Pendant Inspirational Jewelry',
                'amazon_url' => 'https://amzn.to/456ebtE',
                'your_notes' => 'The TEAMER Lotus Necklace is a stainless steel pendant that features the Flower of Life design, making it a symbol of spirituality and interconnectedness. The pendant has dimensions of 27 by 45.7mm and comes with a chain that is 45+5cm in length. It weighs approximately 9.8g.



The necklace is made of stainless steel, which is a durable and corrosion-resistant material. It is perfectly polished, giving it a beautiful shine and a pleasant feel to the touch. The overall design of the lotus necklace is both charming and fashionable, making it suitable for various occasions.



The lotus necklace is available in different types and colors, allowing you to choose the one that best suits your personal style and preferences. Whether you wear it for yoga sessions, meditation practices, or as an everyday accessory, it adds an attractive and fashionable touch to your overall look.',
                'main_image_url_snapshot' => null,
                'price' => 12.98,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/52.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B08XZ413R2',
                'product_name_snapshot' => 'Alex and Ani Evil Eye II Bracelet',
                'amazon_url' => 'https://amzn.to/3V9kXKj',
                'your_notes' => 'The Alex and Ani Evil Eye II Bracelet is a stylish and adjustable piece of jewelry. It is designed to expand from 2 inches to 3.5 inches, allowing for a comfortable fit on various wrist sizes. One notable feature of this bracelet is that it is nickel-free, making it suitable for individuals with nickel allergies or sensitivities.



The bracelet is adorned with genuine Sodalite gemstones, which add a touch of natural beauty to the design. Sodalite is a blue mineral that is often used in jewelry due to its attractive color and unique patterns. It is important to note that the colors of the gemstones may vary slightly due to the natural makeup of the stones, adding to the individuality of each bracelet.



Overall, the Alex and Ani Evil Eye II Bracelet offers a combination of style, adjustability, and natural gemstone adornments, making it a fashionable accessory for those who appreciate both aesthetics and functionality.',
                'main_image_url_snapshot' => null,
                'price' => 20.57,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/53.jpg',
                'category_slug' => 'bracelets',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B0CVXH143J',
                'product_name_snapshot' => 'Butterfly Semicolon Ring 925 Sterling Silver Mental Health Awareness Inspired Ring',
                'amazon_url' => 'https://amzn.to/4e7eqsw',
                'your_notes' => 'Introducing the Butterfly Semicolon Ring, a powerful symbol of mental health awareness and resilience. Crafted from high-quality 925 sterling silver, this ring is more than just a piece of jewelry—it carries a profound message. The delicate butterfly design combines with the semicolon, representing the choice to continue one\'s story despite the challenges faced.



This ring serves as a heartfelt gift for those struggling with mental illness or for anyone passionate about suicide prevention. Its adjustable size, ranging from US7 to US9, ensures a comfortable fit for women of all ages. Rest assured, the ring is made with utmost care for sensitive skin, being nickel-free, lead-free, cadmium-free, and hypoallergenic. Say goodbye to worries about green discoloration or discomfort.



Wear the Butterfly Semicolon Ring proudly, reminding yourself and others that there is always hope and the power to author your own story. Embrace the journey of mental health awareness with this meaningful and stylish accessory.',
                'main_image_url_snapshot' => null,
                'price' => 20.99,
                'item_type' => 'rings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/54.jpg',
                'category_slug' => 'rings',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B07W4QT7QS',
                'product_name_snapshot' => 'Semicolon Bar Necklace Mental Health Awareness Jewelry Inspirational Strength Gift For Cancer Survivor',
                'amazon_url' => 'https://amzn.to/3yNhOZl',
                'your_notes' => 'Introducing the Semicolon Bar Necklace: a powerful piece of mental health awareness jewelry. Crafted from stainless steel, it is both lead-free and nickel-free, ensuring safety and durability. This hypoallergenic necklace won\'t rust, change color, or tarnish.



The bar pendant measures 3.7cm (1.46\") in length, 0.5cm (0.2\") in width, and 0.5cm (0.2\") in thickness. The necklace chain has a length of 46cm (18.11\") with an additional 5cm (1.97\") extension for the perfect fit.



This necklace arrives in a beautiful bag, ready to be gifted to cancer survivors, fighters, or anyone facing personal struggles. The semicolon symbolizes the continuation of life and awareness of mental health battles, such as self-harm, suicide, depression, and anxiety. It serves as a meaningful gift of encouragement and support for friends and family in need.



Let the semicolon inspire you to keep going, even during the toughest times. Wear this necklace as a reminder of strength and resilience in personal struggles. It makes a special gift for someone going through a difficult time or a powerful self-reminder to persevere.',
                'main_image_url_snapshot' => null,
                'price' => 12.0,
                'item_type' => 'necklaces',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/55.jpg',
                'category_slug' => 'necklaces',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B08BFMBVDG',
                'product_name_snapshot' => 'ZRAY Silver Keep Going Ring Inspirational Jewelry Stainless Steel Engraving Size Adjustable Personality Encouragement Gift',
                'amazon_url' => 'https://amzn.to/3KrAqAF',
                'your_notes' => 'Introducing the ZRAY Silver Keep Going Ring: an inspirational piece of jewelry designed to empower and uplift. With its powerful and elegant design, this ring features a hidden message on the inside, serving as a private reminder to never give up and never give in. Wear it with confidence, knowing that it brings peace of mind wherever you go.



Crafted from hypo-allergenic surgical grade 316L stainless steel, this ring offers premium quality and comfort. It is durable, strong, and lightweight, ensuring long-lasting wear. Stainless steel has a lower rate of skin reaction compared to sterling silver, and it will not tarnish or change colors. The bright and shiny finish, along with its smooth edges, adds a touch of elegance to this well-made piece of jewelry. Expect to receive many compliments and attention when you wear it.



The ring is adjustable, allowing for a perfect fit on most finger sizes. Simply apply a gentle squeeze or slight spreading to adjust it accordingly. This beautiful and mindful jewelry serves as an inspirational gift for yourself or your loved ones. Encourage and uplift those dear to you with this meaningful accessory.



Let the ZRAY Silver Keep Going Ring be a constant reminder to stay strong, persevere, and embrace the journey ahead.',
                'main_image_url_snapshot' => null,
                'price' => 9.72,
                'item_type' => 'rings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/56.jpg',
                'category_slug' => 'rings',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B0CLDPF8GR',
                'product_name_snapshot' => 'CENWA Green Awareness Support Gift Green Ribbon Mental Health Awareness Beads Bracelet Green Ribbon Warrior Jewelry',
                'amazon_url' => 'https://amzn.to/3KtxRhE',
                'your_notes' => 'Introducing the CENWA Green Awareness Support Gift, a bracelet that embodies the spirit of resilience and support for mental health awareness. This bracelet is made of green glass beads and features a tiny ribbon, symbolizing strength and solidarity. Crafted with hypoallergenic materials, it guarantees a comfortable wearing experience and will never tarnish, discolor, or rust.



The bracelet is designed with elastic rope and can stretch to fit all women\'s wrists. Its flexibility ensures a perfect and comfortable fit for anyone who wishes to raise awareness for the various causes represented by the green awareness ribbon. From Amblyopia to Traumatic Brain Injury, this bracelet serves as a powerful symbol for conditions such as mental illness, organ donation, cancer, and more.



Show your support and honor your loved ones by wearing this Green Ribbon Warrior Jewelry. It serves as a reminder of the strength and resilience within us and demonstrates your commitment to supporting those facing mental health challenges. Each bracelet is thoughtfully packaged in a velvet bag, ensuring it arrives in pristine condition and protected from any damage during transportation.



Wear the CENWA Green Awareness Support Gift proudly and let it be a conversation starter, spreading awareness and compassion. Stand united with others, and let this bracelet be a symbol of hope and support for those affected by the represented conditions.',
                'main_image_url_snapshot' => null,
                'price' => 12.89,
                'item_type' => 'rings',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/57.jpg',
                'category_slug' => 'rings',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B087WL6JXW',
                'product_name_snapshot' => 'shynek Jewelry Making Kits for Adults, Jewelry Making Supplies Kit with Jewelry Making Tools, Earring Charms, Jewelry Wires, Jewelry Findings and Helping Hands for Jewelry Making and Repair',
                'amazon_url' => 'https://amzn.to/4bGvJPz',
                'your_notes' => 'Introducing the Shynek jewelry-making supplies kit! It includes 3 pliers, 2 rolls of wire, 1 elastic string, 1 waxed necklace cord, 2 rolls of chain, 6 needles, 2 tweezers, 1 caliper, 1 awl, 1 jump ring opener, 1 thimble ring, 1 thread scissors, 1 tape measure, 3 needle threaders, 1 zip pouch, 1 adjustable helping hands, and 1830 jewelry findings. Perfect for earring making, necklace repair, and bracelet fix. Stay organized with the included zip pouch. Unleash your creativity with this must-have kit!',
                'main_image_url_snapshot' => null,
                'price' => 35.99,
                'item_type' => 'jewelry-insights',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/58.jpg',
                'category_slug' => 'jewelry-insights',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
            [
                'amazon_asin' => 'B0CNPNNNLJ',
                'product_name_snapshot' => 'FANCIME Love Day Bracelet - Sterling Silver Tree of Life Birthstone Charm Bracelet, a Radiant Tribute to Motherhood and Timeless Love',
                'amazon_url' => 'https://amzn.to/3KpFlls',
                'your_notes' => 'Sterling Silver Bracelet: This bracelet is made of 925 sterling silver with real white gold plating. All the metals used are lead-free, nickel-free, and hypoallergenic, ensuring a safe and comfortable wear.



June Birthstone Bracelet: This silver tree bracelet features a created moonstone, making it a unique and heartfelt fashion accessory. It is an excellent choice for a birthday gift, especially for those born in June, as moonstone is their birthstone symbolizing wealth, happiness, and nobility.



Tree of Life Pendant: The Tree of Life represents growth, strength, and a fresh start in life. This bracelet combines the Tree of Life with a heart birthstone, symbolizing love and positive energy.



Specifications: The pendant\'s diameter is 15.8x16.4x5.1mm (0.62x0.65x0.2 inch), and the chain length is adjustable up to 23cm (0.9 inch).



Fine Jewelry Gifts for Her: Celebrate the special woman in your life with this sentimental and meaningful jewelry piece. It makes a perfect gift for occasions like birthdays, anniversaries, Valentine\'s Day, Mother\'s Day, Christmas, or weddings.',
                'main_image_url_snapshot' => null,
                'price' => 69.99,
                'item_type' => 'bracelets',
                'status' => 'active',
                'is_featured' => false,
                'local_image_path' => 'affiliate_products/59.jpg',
                'category_slug' => 'bracelets',
                'created_at' => '2024-06-03 09:46:17',
                'updated_at' => '2024-06-03 09:46:17',
            ],
        ];

        foreach ($products as $productData) {
            $categoryId = Category::where('slug', $productData['category_slug'])->value('id');
            if ($categoryId) {
                $productData['category_id'] = $categoryId;
            }
            unset($productData['category_slug']);

            AffiliateProduct::updateOrCreate(
                ['amazon_asin' => $productData['amazon_asin']],
                $productData
            );
        }
    }
}