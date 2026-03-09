<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Carbon;

class SitemapGenerator
{
    /**
     * Build the sitemap XML string.
     *
     * @return string
     */
    public static function build(): string
    {
        $urls = [];
        $now = Carbon::now()->toAtomString();

        // static pages
        $urls[] = ['loc' => url('/'), 'lastmod' => $now, 'changefreq' => 'daily', 'priority' => '1.0'];
        $urls[] = ['loc' => url('/about-us'), 'lastmod' => $now, 'changefreq' => 'monthly', 'priority' => '0.5'];
        $urls[] = ['loc' => url('/contact'), 'lastmod' => $now, 'changefreq' => 'monthly', 'priority' => '0.5'];
        $urls[] = ['loc' => url('/blog'), 'lastmod' => $now, 'changefreq' => 'weekly', 'priority' => '0.6'];
        $urls[] = ['loc' => url('/shop'), 'lastmod' => $now, 'changefreq' => 'daily', 'priority' => '0.8'];

        try {
            Product::where('active', true)->select('slug', 'updated_at')->chunk(500, function ($products) use (&$urls, $now) {
                foreach ($products as $product) {
                    $last = $product->updated_at ? $product->updated_at->toAtomString() : $now;
                    $urls[] = [
                        'loc' => url('/products/' . $product->slug),
                        'lastmod' => $last,
                        'changefreq' => 'weekly',
                        'priority' => '0.7',
                    ];
                }
            });
        } catch (\Exception $e) {
            // If DB is unavailable (e.g. during local dev without db), skip products gracefully
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $u) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_QUOTES | ENT_XML1) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $u['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $u['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $u['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
