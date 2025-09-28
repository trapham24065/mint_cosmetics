<?php

declare(strict_types=1);
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ProductScrapingService;
use Illuminate\Http\Request;
use App\Exports\ScrapedProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ScraperController extends Controller
{

    /**
     * Display the scraper view.
     */
    public function index()
    {
        return view('admin.management.scraper.index');
    }

    /**
     * Handle the scraping request and trigger an Excel download.
     */
    public function run(Request $request, ProductScrapingService $scraper)
    {
        $validated = $request->validate([
            'urls' => 'required|string',
        ]);

        $urls = array_filter(preg_split('/\r\n|\r|\n/', $validated['urls']));

        if (empty($urls)) {
            return back()->with('error', 'Please provide at least one URL.');
        }

        // Gọi service để cào dữ liệu
        $scrapedData = $scraper->scrapeMultiple($urls);

        // Tạo và tải về file Excel
        return Excel::download(new ScrapedProductsExport($scrapedData), 'scraped-products.xlsx');
    }

}
