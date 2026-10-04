<?php

namespace App\Http\Controllers;

use App\Models\AnalysisModel;
use App\Models\BookChapter;
use App\Models\Consultant;
use Carbon\Carbon;
use Illuminate\Http\Response;

/**
 * XML sitemaps for search engines. Every URL is built with route(), so it always
 * matches a real page (the old version listed Arabic paths that did not exist).
 */
class SitemapController extends Controller
{
    /**
     * Sitemap index pointing to the section sitemaps
     */
    public function index(): Response
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $content .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach (['sitemap.pages', 'sitemap.assessments', 'sitemap.consultations'] as $name) {
            $content .= "  <sitemap>\n";
            $content .= '    <loc>'.e(route($name))."</loc>\n";
            $content .= '    <lastmod>'.Carbon::now()->toW3cString()."</lastmod>\n";
            $content .= "  </sitemap>\n";
        }

        $content .= '</sitemapindex>';

        return $this->xml($content);
    }

    /**
     * Static pages, library chapters and analysis models
     */
    public function pages(): Response
    {
        $pages = [
            ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => route('vision-mission'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => route('strategic-goals'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => route('values'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => route('services'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => route('career-book.index'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => route('analysis-models.index'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => route('contact'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => route('terms'), 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['url' => route('privacy'), 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        try {
            foreach (BookChapter::published()->ordered()->get() as $chapter) {
                $pages[] = ['url' => route('career-book.show', $chapter->slug), 'priority' => '0.6', 'changefreq' => 'monthly', 'lastmod' => $chapter->updated_at];
            }

            foreach (AnalysisModel::active()->ordered()->get() as $model) {
                $pages[] = ['url' => route('analysis-models.show', $model), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => $model->updated_at];
            }
        } catch (\Exception $e) {
            // Sitemap must still be served if a table is missing
        }

        return $this->xml($this->generateSitemap($pages));
    }

    /**
     * Assessments listing (individual tests require signing in, so they are not listed)
     */
    public function assessments(): Response
    {
        return $this->xml($this->generateSitemap([
            ['url' => route('assessments.index'), 'priority' => '0.9', 'changefreq' => 'weekly'],
        ]));
    }

    /**
     * Consultants listing and each active consultant's booking page
     */
    public function consultations(): Response
    {
        $pages = [['url' => route('consultations.index'), 'priority' => '0.9', 'changefreq' => 'daily']];

        try {
            foreach (Consultant::where('is_active', true)->get() as $consultant) {
                $pages[] = ['url' => route('consultations.show', $consultant), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => $consultant->updated_at];
            }
        } catch (\Exception $e) {
            // Sitemap must still be served if a table is missing
        }

        return $this->xml($this->generateSitemap($pages));
    }

    private function generateSitemap(array $pages): string
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($pages as $page) {
            $content .= "  <url>\n";
            $content .= '    <loc>'.e($page['url'])."</loc>\n";
            $content .= '    <lastmod>'.($page['lastmod'] ?? Carbon::now())->toW3cString()."</lastmod>\n";
            $content .= '    <changefreq>'.$page['changefreq']."</changefreq>\n";
            $content .= '    <priority>'.$page['priority']."</priority>\n";
            $content .= "  </url>\n";
        }

        return $content.'</urlset>';
    }

    private function xml(string $content): Response
    {
        return response($content, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
