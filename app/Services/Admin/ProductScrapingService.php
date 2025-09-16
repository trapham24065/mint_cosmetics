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

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ProductScrapingService
{

    /**
     * Scrape product data from a given URL using modern Symfony components.
     *
     * @param  string  $url  The URL of the product page to scrape.
     *
     * @return array The scraped product data.
     */
    public function scrape(string $url): array
    {
        $client = new HttpBrowser(HttpClient::create(['timeout' => 60]));
        $crawler = $client->request('GET', $url);

        // ==============================================================================
        // QUAN TRỌNG: Bạn phải thay thế các CSS selector dưới đây cho đúng với trang web bạn muốn cào.
        // Mỗi trang web có một cấu trúc HTML khác nhau.
        // Hướng dẫn: Mở trang web -> Click chuột phải vào tên sản phẩm -> Inspect ->
        // -> Click chuột phải vào thẻ HTML -> Copy -> Copy selector.
        // ==============================================================================

        // ĐÃ CẬP NHẬT: Sử dụng selector bạn đã tìm thấy
        $nameSelector = '#sll2-normal-pdp-main > div > div > div > div.container > section > section.flex.flex-auto.YTDXQ0 > div > div.WBVL_7 > h1';
//        $descriptionSelector = 'div.product-description'; // <-- Cần thay thế
//        $priceSelector = '.price-tag'; // <-- Cần thay thế
//        $imageGallerySelector = '.product-gallery img'; // <-- Cần thay thế

        $name = $this->getText($crawler, $nameSelector);
//        $description = $this->getText($crawler, $descriptionSelector);
//
//        $priceText = $this->getText($crawler, $priceSelector);
//        $price = (float)preg_replace('/[^\d.]/', '', $priceText);
//
//        $images = $crawler->filter($imageGallerySelector)->each(function (Crawler $node) {
//            return $node->attr('src') ?? $node->attr('data-src');
//        });

        $scrapedData = [
            'name' => trim($name),
            //            'description' => trim($description),
            //            'price'       => $price,
            //            'images'      => array_filter($images), // Lọc bỏ các giá trị rỗng
        ];

        // Dòng dd() để kiểm tra dữ liệu
        dd($scrapedData);

        return $scrapedData;
    }

    /**
     * Helper function to safely get text from a selector.
     *
     * @param  Crawler  $crawler  The crawler instance.
     * @param  string  $selector  The CSS selector.
     *
     * @return string The found text or an empty string.
     */
    private function getText(Crawler $crawler, string $selector): string
    {
        // Chỉ lấy text nếu selector tồn tại
        if ($crawler->filter($selector)->count() > 0) {
            return $crawler->filter($selector)->text('');
        }
        return '';
    }

}

