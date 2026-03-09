<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--force : Overwrite existing sitemap without prompting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate static sitemap.xml file under public/';

    public function handle(): int
    {
        $path = public_path('sitemap.xml');

        if (file_exists($path) && ! $this->option('force')) {
            if (! $this->confirm('sitemap.xml already exists. Overwrite?')) {
                $this->info('Cancelled.');
                return 0;
            }
        }

        $xml = SitemapGenerator::build();
        file_put_contents($path, $xml);

        $this->info('sitemap.xml generated at ' . $path);
        return 0;
    }
}
