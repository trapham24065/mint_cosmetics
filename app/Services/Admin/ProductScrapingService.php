<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/14/2025
 * @time 2:49 PM
 */

declare(strict_types=1);

namespace App\Services\Admin;

use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;

class ProductScrapingService
{

    /**
     * Scrape product data from a given URL using a headless browser (Panther).
     */
    public function scrapeMultiple(array $urls): array
    {
        $scrapedData = [];
        $chromeDriverPath = base_path(env('PANTHER_CHROME_DRIVER', 'vendor/bin/chromedriver'));
        $client = Client::createChromeClient($chromeDriverPath);

        foreach ($urls as $url) {
            try {
                $crawler = $client->request('GET', $url);

                // detect domain
                $domain = parse_url($url, PHP_URL_HOST);

                // choose selectors based on domain
                $selectors = $this->getSelectorsForDomain($domain);

                // wait for a name element
                $client->waitFor($selectors['name'], 10);

                $name = $this->getText($crawler, $selectors['name']);
                $priceText = $this->getText($crawler, $selectors['price']);
                $shortDescription = $this->getText($crawler, $selectors['short_description']);

                // full description (HTML)
                $description = 'Not Found';
                if ($crawler->filter('a[href="#tab-description"]')->count() > 0) {
                    try {
                        // click vào tab mô tả
                        $client->click($crawler->filter('a[href="#tab-description"]')->link());

                        // đợi phần tử xuất hiện sau khi click
                        $client->waitForVisibility('#tab-description', 5);

                        // lấy lại crawler mới vì DOM thay đổi
                        $crawler = $client->getCrawler();

                        $description = $this->getHtml($crawler, '#tab-description');
                    } catch (\Exception $ex) {
                        Log::warning("Description not found after clicking tab for URL: {$url}", [
                            'error' => $ex->getMessage(),
                        ]);
                    }
                }

                $price = (float)preg_replace('/\D/', '', $priceText);

                // image gallery
                $images = [];
                if ($selectors['images']) {
                    $images = $crawler->filter($selectors['images'])->each(function (Crawler $node) {
                        return $node->attr('src') ?? $node->attr('data-src');
                    });
                }

                $scrapedData[] = [
                    'url'               => $url,
                    'images'            => array_filter($images),
                    'name'              => trim($name),
                    'price'             => $price,
                    'short_description' => trim($shortDescription),
                    'description'       => trim($description),
                ];
            } catch (\Exception $e) {
                Log::error("Failed to scrape URL: {$url}", ['error' => $e->getMessage()]);
                $scrapedData[] = [
                    'url'               => $url,
                    'images'            => [],
                    'name'              => 'SCRAPING FAILED',
                    'price'             => 0,
                    'short_description' => 'SCRAPING FAILED',
                    'description'       => $e->getMessage(),
                ];
            }
        }

        $client->quit();
        return $scrapedData;
    }

    /**
     * Return selector mapping by domain.
     */
    private function getSelectorsForDomain(string $domain): array
    {
        // default fallback
        $default = [
            'images'            => '.product-thumbnails img',
            'name'              => '.product-info h1',
            'price'             => '.product-info .price bdi',
            'short_description' => '.product-short-description',
            'description'       => '#tab-description',
        ];

        if (str_contains($domain, 'shopee.vn')) {
            return [
                'images'            => '._2Fw7Qu img',
                'name'              => 'h1._44qnta',
                'price'             => '._2Shl1j',
                'short_description' => '._2u0jt9',
                'description'       => '._2jz573',
            ];
        }

        if (str_contains($domain, 'kyo.vn') || str_contains($domain, 'woocommerce')) {
            return $default; // WooCommerce standard selectors
        }

        if (str_contains($domain, 'lazada.vn')) {
            return [
                'images'            => '.pdp-mod-common-image.gallery-preview-panel__image img',
                'name'              => '.pdp-mod-product-badge-title',
                'price'             => '.pdp-price_type_normal',
                'short_description' => '.pdp-product-desc',
                'description'       => '.pdp-product-detail',
            ];
        }

        return $default;
    }

    private function getText(Crawler $crawler, string $selector): string
    {
        return $crawler->filter($selector)->count() > 0
            ? $crawler->filter($selector)->text('')
            : 'Not Found';
    }

    private function getHtml(Crawler $crawler, string $selector): string
    {
        return $crawler->filter($selector)->count() > 0
            ? $crawler->filter($selector)->html('')
            : 'Not Found';
    }

}
