<?php

namespace App\Http\Controllers;

use App\Console\Commands\GenerateSitemap;

class SitemapController extends Controller
{
    public function __invoke(GenerateSitemap $generator)
    {
        $xml = $generator->buildSitemap()->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
