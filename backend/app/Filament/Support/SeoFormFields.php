<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;

class SeoFormFields
{
    public static function fields(bool $post = false, string $defaultSchemaType = 'Article', bool $ogImage = true): array
    {
        $fields = [
            Section::make('Search Engine Fields')->schema([
                TextInput::make('seo_title')
                    ->label('SEO Title')
                    ->helperText('Recommended: 50-60 characters')
                    ->maxLength(60)
                    ->live(onBlur: true)
                    ->suffix(fn ($state) => strlen($state ?? '') . '/60')
                    ->placeholder('Leave blank to use the page title'),
                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->helperText('Recommended: 140-160 characters')
                    ->maxLength(160)
                    ->rows(3)
                    ->live(onBlur: true),
            ])->columns(2),
        ];

        if ($post) {
            $fields[] = Section::make('Keywords & Canonical')->schema([
                TagsInput::make('meta_keywords')
                    ->label('Focus Keywords')
                    ->helperText('Add keywords one at a time, press Enter')
                    ->separator(','),
                TextInput::make('focus_keyword')
                    ->label('Primary Focus Keyword')
                    ->helperText('Single most important keyword for this page'),
                TextInput::make('canonical_url')
                    ->label('Canonical URL')
                    ->url()
                    ->helperText('Leave blank to use the default page URL'),
            ])->columns(2);

            $fields[] = Section::make('Robots')->schema([
                Toggle::make('noindex')
                    ->label('Hide from search engines (noindex)')
                    ->helperText('Enable only for pages you do not want indexed'),
                Toggle::make('nofollow')
                    ->label('Do not follow links (nofollow)'),
            ])->columns(2);
        }

        $openGraphFields = [
            TextInput::make('og_title')
                ->label('Social Share Title')
                ->maxLength(60)
                ->helperText('Used when shared on Facebook or LinkedIn. Defaults to SEO title.'),
            Textarea::make('og_description')
                ->label('Social Share Description')
                ->maxLength(160)
                ->rows(3),
        ];

        if ($ogImage) {
            $openGraphFields[] =
            FileUpload::make('og_image')
                ->label('Social Share Image')
                ->image()
                ->disk('public')
                ->directory('seo/og-images')
                ->helperText('Recommended: 1200x630px. Defaults to the featured image.');
        }

        $fields[] = Section::make('Open Graph')->schema($openGraphFields)->columns(2);

        if ($post) {
            $fields[] = Section::make('Twitter Card')->schema([
                TextInput::make('twitter_title')
                    ->label('Twitter Title')
                    ->maxLength(60)
                    ->helperText('Defaults to SEO title if blank'),
                Textarea::make('twitter_description')
                    ->label('Twitter Description')
                    ->maxLength(160)
                    ->rows(3),
            ])->columns(2);
        }

        $fields[] = Section::make('Schema Type')->schema([
            Select::make('schema_type')
                ->label('Structured Data Type')
                ->options([
                    'Article' => 'Article',
                    'BlogPosting' => 'Blog Post',
                    'NewsArticle' => 'News Article',
                    'Product' => 'Product',
                    'Event' => 'Event',
                    'VideoObject' => 'Video',
                    'CollectionPage' => 'Collection Page',
                    'FAQPage' => 'FAQ Page',
                ])
                ->default($defaultSchemaType),
        ]);

        $fields[] = Section::make('SEO Preview')->schema([
            ViewField::make('seo_preview')
                ->view('filament.forms.components.seo-preview')
                ->columnSpanFull(),
        ]);

        return $fields;
    }
}
