<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostsSeeder extends Seeder
{
    public function run(): void
    {
        $blogCategory = Category::where('slug', 'blog')->first();
        
        if (!$blogCategory) {
            return;
        }

        $posts = [
            [
                'title' => 'The Art of Diamond Selection: A Buyer\'s Guide',
                'content' => 'Selecting the perfect diamond can be overwhelming with all the options available. In this comprehensive guide, we walk you through the Four Cs: Cut, Color, Clarity, and Carat Weight. Understanding these factors will help you make an informed decision and choose a diamond that not only fits your budget but also meets your personal aesthetic preferences. Our expert gemologists share their insider tips on finding the best value without compromising on quality.',
                'excerpt' => 'Learn how to select the perfect diamond with our comprehensive buyer\'s guide covering the Four Cs.',
                'meta_keywords' => '["diamond selection", "diamond buying guide", "four cs", "diamond quality"]',
            ],
            [
                'title' => 'Gold Investment: Why Now Is a Good Time',
                'content' => 'In times of economic uncertainty, gold has always been a safe haven for investors. This article explores current market trends and discusses why investing in gold jewelry can be both beautiful and financially prudent. We examine historical price patterns, market forecasts, and provide practical advice on how to start building your gold investment portfolio. Whether you\'re a seasoned investor or just starting out, understanding the fundamentals of gold investment is crucial.',
                'excerpt' => 'Discover why gold is a timeless investment and how to build your precious metal portfolio.',
                'meta_keywords' => '["gold investment", "precious metals", "financial planning", "gold prices"]',
            ],
            [
                'title' => 'The History and Significance of Gemstones',
                'content' => 'Gemstones have fascinated humanity for thousands of years, holding spiritual and cultural significance across different civilizations. From the deep red rubies of ancient kingdoms to the mystical emeralds of modern collectors, each gemstone carries its own story and properties. This article delves into the history of the world\'s most valuable gemstones, their mining origins, and their cultural importance in jewelry and folklore. Discover what makes each gemstone unique and why they continue to captivate people worldwide.',
                'excerpt' => 'Explore the rich history and cultural significance of precious gemstones through the ages.',
                'meta_keywords' => '["gemstones", "precious stones", "history", "crystal properties"]',
            ],
            [
                'title' => 'Wedding Jewelry Trends for the Modern Bride',
                'content' => 'Modern brides are breaking traditional molds and creating wedding jewelry that reflects their personal style. From minimalist engagement rings to statement earrings and personalized bracelets, contemporary bridal jewelry celebrates individuality. This trend report explores the latest designs, materials, and styles that are dominating the wedding jewelry market. Whether you prefer classic elegance or bold contemporary designs, we showcase inspiration for your special day. Learn about sustainable jewelry options, lab-grown diamonds, and unique customization possibilities.',
                'excerpt' => 'Discover the latest wedding jewelry trends and find inspiration for your special day.',
                'meta_keywords' => '["wedding jewelry", "engagement rings", "bridal fashion", "jewelry trends"]',
            ],
            [
                'title' => 'Understanding Jewelry Hallmarks and Certifications',
                'content' => 'When purchasing precious metal jewelry, understanding hallmarks and certifications is essential for ensuring authenticity and quality. This guide explains what different hallmarks mean, how to read them, and why certifications matter. We cover international standards, government hallmarks, maker\'s marks, and third-party certifications from renowned gemological institutes. Learning to identify these marks will give you confidence when making significant jewelry purchases and help you verify the value and authenticity of your jewelry collection.',
                'excerpt' => 'Master the meaning of jewelry hallmarks and certifications to ensure authenticity and value.',
                'meta_keywords' => '["hallmarks", "jewelry certification", "precious metals", "authenticity"]',
            ],
            [
                'title' => 'Sustainable Jewelry: Ethical Choices for the Conscious Consumer',
                'content' => 'The jewelry industry is undergoing a transformation as consumers demand more ethical and sustainable practices. This article explores responsible sourcing, fair-trade practices, and environmental considerations in jewelry production. We discuss lab-grown diamonds, recycled precious metals, and ethical gemstone mining. As conscious consumers become more influential in the market, jewelry designers and retailers are adapting to meet these expectations. Learn how to make informed choices that align with your values while maintaining beauty and quality in your jewelry collection.',
                'excerpt' => 'Make ethical jewelry choices without compromising on beauty or quality.',
                'meta_keywords' => '["sustainable jewelry", "ethical sourcing", "lab-grown diamonds", "eco-friendly"]',
            ],
            [
                'title' => 'Caring for Your Precious Jewelry: A Complete Guide',
                'content' => 'Proper care and maintenance extend the life and beauty of your precious jewelry. This comprehensive guide covers cleaning techniques, storage solutions, and protection methods for different types of jewelry. From delicate gemstones to lustrous pearls, each piece requires specific care considerations. We provide step-by-step instructions for safe cleaning at home, professional maintenance recommendations, and prevention tips to protect against damage. Regular maintenance not only keeps your jewelry looking radiant but also preserves its value for generations to come.',
                'excerpt' => 'Learn professional tips for caring for and maintaining your precious jewelry collection.',
                'meta_keywords' => '["jewelry care", "cleaning jewelry", "maintenance", "preservation"]',
            ],
            [
                'title' => 'The Psychology of Color in Jewelry',
                'content' => 'Colors in jewelry aren\'t just aesthetic choices; they carry psychological significance and can influence mood and perception. This fascinating article explores how different colors affect emotions and how to choose jewelry colors that enhance your personal style. We examine the psychology behind popular jewelry colors like deep blues, vibrant reds, and soothing greens. Whether you\'re drawn to bold statements or subtle elegance, understanding color psychology helps you select jewelry that not only looks good but also resonates with your personality and emotional needs.',
                'excerpt' => 'Discover how jewelry colors influence mood and personality, and learn to choose the perfect shades for you.',
                'meta_keywords' => '["color psychology", "jewelry colors", "personal style", "emotional wellness"]',
            ],
            [
                'title' => 'Customization: Creating Your Unique Jewelry',
                'content' => 'Customized jewelry allows you to express your individuality and create pieces that tell your personal story. Whether you\'re designing an engagement ring, a family heirloom, or a statement piece, customization offers endless possibilities. This guide walks you through the design process, working with jewelers, material selection, and budgeting for custom pieces. We showcase inspiring examples of custom jewelry and explain how collaborating with skilled craftsmen can bring your vision to life. Your custom jewelry becomes not just an accessory, but a meaningful part of your personal legacy.',
                'excerpt' => 'Create uniquely personal jewelry through customization and express your individual style.',
                'meta_keywords' => '["custom jewelry", "design process", "personalization", "bespoke jewelry"]',
            ],
            [
                'title' => 'Jewelry as Investment: Building a Valuable Collection',
                'content' => 'While jewelry is often appreciated for its beauty, certain pieces hold and appreciate in value over time. This investment guide explores which types of jewelry maintain value, including vintage pieces, designer jewelry, and precious metal investments. We discuss factors that affect jewelry value such as craftsmanship, rarity, provenance, and market demand. Learn how to authenticate vintage jewelry, understand the collector\'s market, and build a collection that serves as both personal adornment and financial asset. Expert tips help you make strategic purchases that increase in value.',
                'excerpt' => 'Build a valuable jewelry collection that appreciates over time while bringing joy to your life.',
                'meta_keywords' => '["jewelry investment", "valuable pieces", "collector market", "vintage jewelry"]',
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'slug' => Str::slug($post['title']),
                    'content' => $post['content'],
                    'excerpt' => $post['excerpt'],
                    'meta_keywords' => $post['meta_keywords'],
                    'seo_title' => $post['title'],
                    'meta_description' => $post['excerpt'],
                    'og_title' => $post['title'],
                    'og_description' => $post['excerpt'],
                    'schema_type' => 'BlogPosting',
                    'user_id' => 1,
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );
        }

        // Attach posts to the blog category
        $posts = Post::all();
        foreach ($posts as $post) {
            if (!$post->categories()->where('id', $blogCategory->id)->exists()) {
                $post->categories()->attach($blogCategory->id);
            }
        }
    }
}
