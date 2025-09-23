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
