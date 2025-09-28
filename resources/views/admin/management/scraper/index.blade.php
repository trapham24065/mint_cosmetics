@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Product Scraper</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Paste one or more product URLs (one per line) into the box below. The system will attempt to scrape
                    the data and provide you with an Excel file.
                </p>
                <div class="alert alert-warning" role="alert">
                    <h5 class="alert-heading">Important Notes</h5>
                    <ul>
                        <li><strong>Currently only collects website data:</strong> <a
                                href="https://kyo.vn/san-pham/phan-ma-hong-3ce-mood-recipe-face-blusher-pure-cake-hong-nude/"
                                target="_blank" rel="noopener noreferrer">kyo.vn</a>
                        </li>
                        <li><strong>Target Website:</strong> This tool is currently configured to scrape data from a
                            specific website structure. It may not work correctly on other sites.
                        </li>
                        <li><strong>Performance:</strong> Scraping is a slow process as it needs to load and render
                            JavaScript. Please be patient, especially with multiple URLs.
                        </li>
                        <li><strong>CSS Selectors:</strong> If the target website changes its HTML structure, this tool
                            may fail. The CSS selectors in the `ProductScrapingService` will need to be updated.
                        </li>
                        <li><strong>New Sites:</strong> To scrape from a new website, a developer needs to configure the
                            appropriate CSS selectors in the backend service.
                        </li>
                    </ul>
                </div>
                <form action="{{ route('admin.scraper.run') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="urls-textarea" class="form-label">Product URLs</label>
                        <textarea class="form-control" id="urls-textarea" name="urls" rows="10"
                                  placeholder="https://example.com/product-1&#10;https://example.com/product-2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Scrape & Download Excel</button>
                </form>
            </div>
        </div>
    </div>
@endsection
