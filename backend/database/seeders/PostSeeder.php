<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = $this->loadPostsFromSqlDump();

        if (empty($posts)) {
            if ($this->command) {
                $this->command->info('PostSeeder: SQL dump not found or no blog rows were parsed.');
            }

            return;
        }

        foreach ($posts as $post) {
            if (isset($post['meta_keywords']) && is_string($post['meta_keywords'])) {
                $decoded = json_decode($post['meta_keywords'], true);
                $post['meta_keywords'] = $decoded !== null ? $decoded : [$post['meta_keywords']];
            }

            Post::updateOrCreate(
                ['slug' => $post['slug']],
                Arr::except($post, ['id'])
            );
        }
    }

    private function loadPostsFromSqlDump(): array
    {
        $dumpPath = base_path('../../etc/DB/latestfashionjew_RECTIFIED.sql');

        if (! file_exists($dumpPath)) {
            return [];
        }

        $sql = file_get_contents($dumpPath);

        if ($sql === false) {
            return [];
        }

        return $this->parseBlogRowsFromSqlDump($sql);
    }

    private function parseBlogRowsFromSqlDump(string $sql): array
    {
        $posts = [];
        $lowerSql = strtolower($sql);
        $offset = 0;

        while (false !== ($insertPos = strpos($lowerSql, 'insert into `blogs`', $offset))) {
            $valuesPos = strpos($lowerSql, 'values', $insertPos);
            if ($valuesPos === false) {
                break;
            }

            $cursor = $valuesPos + strlen('values');
            $length = strlen($sql);
            $inQuote = false;
            $escaped = false;
            $depth = 0;

            while ($cursor < $length) {
                $char = $sql[$cursor];

                if ($inQuote) {
                    if ($escaped) {
                        $escaped = false;
                    } elseif ($char === '\\') {
                        $escaped = true;
                    } elseif ($char === "'") {
                        $inQuote = false;
                    }
                } elseif ($char === "'") {
                    $inQuote = true;
                } elseif ($char === '(') {
                    $depth++;
                } elseif ($char === ')') {
                    if ($depth > 0) {
                        $depth--;
                    }
                } elseif ($char === ';' && $depth === 0) {
                    break;
                }

                $cursor++;
            }

            $valuesBlock = substr($sql, $valuesPos + strlen('values'), $cursor - ($valuesPos + strlen('values')));
            $rowStart = null;
            $currentDepth = 0;
            $inQuote = false;
            $escaped = false;
            $blockLength = strlen($valuesBlock);

            for ($i = 0; $i < $blockLength; $i++) {
                $char = $valuesBlock[$i];

                if ($inQuote) {
                    if ($escaped) {
                        $escaped = false;
                    } elseif ($char === '\\') {
                        $escaped = true;
                    } elseif ($char === "'") {
                        $inQuote = false;
                    }
                } elseif ($char === "'") {
                    $inQuote = true;
                } elseif ($char === '(') {
                    if ($currentDepth === 0) {
                        $rowStart = $i + 1;
                    }
                    $currentDepth++;
                } elseif ($char === ')') {
                    if ($currentDepth > 0) {
                        $currentDepth--;
                        if ($currentDepth === 0 && $rowStart !== null) {
                            $row = substr($valuesBlock, $rowStart, $i - $rowStart);
                            $fields = $this->parseSqlRow($row);

                            if (count($fields) >= 14) {
                                $values = array_map([$this, 'parseSqlValue'], $fields);
                                [$blogId, $title, $slug, $content, $authorId, $authorName, $celebrityId, $publicationDate, $categoryId, $featuredImage, $metaTags, $createdAt, $updatedAt, $status] = array_slice($values, 0, 14);

                                $metaDescription = $this->extractMetaTagContent($metaTags ?? '', 'description');
                                $metaKeywords = $this->extractMetaTagContent($metaTags ?? '', 'keywords');

                                if ($metaKeywords !== null) {
                                    $metaKeywords = array_values(array_filter(array_map('trim', explode(',', $metaKeywords))));
                                }

                                $posts[] = [
                                    'id' => is_int($blogId) ? $blogId : (ctype_digit((string) $blogId) ? (int) $blogId : null),
                                    'user_id' => 1,
                                    'title' => $title ?? '',
                                    'slug' => $slug ?? '',
                                    'content' => $content ?? '',
                                    'excerpt' => $this->excerptFromContent($content, $metaDescription),
                                    'featured_image_url_snapshot' => null,
                                    'image_urls' => null,
                                    'featured_image' => $featuredImage ? (Str::startsWith($featuredImage, ['/','http']) ? $featuredImage : '/post-featured-images/' . ltrim($featuredImage, '/')) : null,
                                    'status' => 'published',
                                    'is_featured' => 0,
                                    'published_at' => $publicationDate ?: null,
                                    'seo_title' => $title ?? '',
                                    'meta_description' => $metaDescription,
                                    'meta_keywords' => $metaKeywords,
                                    'canonical_url' => null,
                                    'noindex' => 0,
                                    'nofollow' => 0,
                                    'created_at' => $createdAt ?: null,
                                    'updated_at' => $updatedAt ?: null,
                                ];
                            }
                        }
                    }
                }
            }

            $offset = $cursor + 1;
        }

        usort($posts, static fn (array $a, array $b): int => ($a['id'] ?? 0) <=> ($b['id'] ?? 0));

        return $posts;
    }

    private function parseSqlRow(string $row): array
    {
        $fields = [];
        $buffer = '';
        $inQuote = false;
        $escaped = false;
        $length = strlen($row);

        for ($i = 0; $i < $length; $i++) {
            $char = $row[$i];

            if ($inQuote) {
                if ($escaped) {
                    $buffer .= $char;
                    $escaped = false;
                } elseif ($char === '\\') {
                    $buffer .= $char;
                    $escaped = true;
                } elseif ($char === "'") {
                    if ($i + 1 < $length && $row[$i + 1] === "'") {
                        $buffer .= "'";
                        $i++;
                    } else {
                        $buffer .= $char;
                        $inQuote = false;
                    }
                } else {
                    $buffer .= $char;
                }
            } elseif ($char === "'") {
                $buffer .= $char;
                $inQuote = true;
            } elseif ($char === ',') {
                $fields[] = $buffer;
                $buffer = '';
            } else {
                $buffer .= $char;
            }
        }

        $fields[] = $buffer;

        return $fields;
    }

    private function parseSqlValue(string $value): mixed
    {
        $value = trim($value);

        if (strcasecmp($value, 'NULL') === 0) {
            return null;
        }

        if ($value === '') {
            return '';
        }

        if ($value[0] === "'" && substr($value, -1) === "'") {
            $inner = substr($value, 1, -1);
            $inner = preg_replace_callback("/\\\\([\\\\\'\"nrt])/", static function (array $matches) {
                return match ($matches[1]) {
                    '\\' => '\\',
                    "'" => "'",
                    '"' => '"',
                    'n' => "\n",
                    'r' => "\r",
                    't' => "\t",
                    default => $matches[0],
                };
            }, $inner);
            $inner = str_replace("''", "'", $inner);

            return $inner;
        }

        if (is_numeric($value)) {
            return preg_match('/^-?\d+$/', $value) === 1 ? (int) $value : (float) $value;
        }

        return $value;
    }

    private function extractMetaTagContent(string $metaTags, string $name): ?string
    {
        if (preg_match('/<meta\s+[^>]*name=[\'\"]' . preg_quote($name, '/') . '[\'\"][^>]*content=[\'\"]([^\'\"]*)[\'\"][^>]*>/i', $metaTags, $matches)) {
            return html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5);
        }

        return null;
    }

    private function excerptFromContent(?string $content, ?string $description): ?string
    {
        if ($description !== null && trim($description) !== '') {
            return trim($description);
        }

        $text = trim(strip_tags($content ?? ''));

        if ($text === '') {
            return null;
        }

        return (string) Str::of($text)->limit(160, '');
    }
}
