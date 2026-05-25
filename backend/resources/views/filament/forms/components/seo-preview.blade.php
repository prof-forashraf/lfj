@php
    if (! isset($get) || ! is_callable($get)) {
        $get = fn ($key = null) => null;
    }

    $title = $get('seo_title') ?: $get('title') ?: $get('name') ?: $get('product_name_snapshot') ?: 'Page title preview';
    $description = $get('meta_description') ?: 'Meta description preview. Add a concise summary that helps searchers understand the page.';
    $slug = $get('slug') ?: $get('amazon_asin') ?: 'page-slug';
    $focusKeyword = $get('focus_keyword');
    $titleLength = strlen($get('seo_title') ?? '');
    $descriptionLength = strlen($get('meta_description') ?? '');
    $url = rtrim(config('seo.frontend_url'), '/') . '/' . ltrim($slug, '/');
@endphp

<div class="space-y-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
    <div class="text-sm font-medium text-gray-700 dark:text-gray-200">Google preview</div>
    <div class="space-y-1">
        <div class="text-lg leading-snug text-blue-700">{{ \Illuminate\Support\Str::limit($title, 60, '') }}</div>
        <div class="text-sm text-green-700">{{ $url }}</div>
        <div class="text-sm leading-relaxed text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($description, 160, '') }}</div>
    </div>
    <div class="flex flex-wrap gap-2 text-xs">
        <span class="rounded bg-gray-100 px-2 py-1 text-gray-700 dark:bg-gray-800 dark:text-gray-200">Title: {{ $titleLength }}/60</span>
        <span class="rounded bg-gray-100 px-2 py-1 text-gray-700 dark:bg-gray-800 dark:text-gray-200">Description: {{ $descriptionLength }}/160</span>
        @if ($titleLength > 60)
            <span class="rounded bg-red-100 px-2 py-1 text-red-700">Title is too long</span>
        @endif
        @if ($descriptionLength > 160)
            <span class="rounded bg-red-100 px-2 py-1 text-red-700">Description is too long</span>
        @endif
        @if (empty($get('meta_description')))
            <span class="rounded bg-amber-100 px-2 py-1 text-amber-800">Meta description missing</span>
        @endif
        @if ($focusKeyword && ! str_contains(strtolower($title), strtolower($focusKeyword)))
            <span class="rounded bg-amber-100 px-2 py-1 text-amber-800">Focus keyword is not in title</span>
        @endif
    </div>
</div>
