<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate the main sitemap index
     */
    public function index(): Response
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $content .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Main pages sitemap
        $content .= '  <sitemap>' . "\n";
        $content .= '    <loc>' . url('/sitemap-pages.xml') . '</loc>' . "\n";
        $content .= '    <lastmod>' . Carbon::now()->toW3cString() . '</lastmod>' . "\n";
        $content .= '  </sitemap>' . "\n";
        
        // Assessments sitemap
        $content .= '  <sitemap>' . "\n";
        $content .= '    <loc>' . url('/sitemap-assessments.xml') . '</loc>' . "\n";
        $content .= '    <lastmod>' . Carbon::now()->toW3cString() . '</lastmod>' . "\n";
        $content .= '  </sitemap>' . "\n";
        
        // Consultations sitemap
        $content .= '  <sitemap>' . "\n";
        $content .= '    <loc>' . url('/sitemap-consultations.xml') . '</loc>' . "\n";
        $content .= '    <lastmod>' . Carbon::now()->toW3cString() . '</lastmod>' . "\n";
        $content .= '  </sitemap>' . "\n";
        
        $content .= '</sitemapindex>';
        
        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
    
    /**
     * Generate sitemap for static pages
     */
    public function pages(): Response
    {
        $pages = [
            ['url' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => url('/عن-المؤسسة'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => url('/الرؤية-والرسالة'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => url('/الأهداف-الاستراتيجية'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => url('/القيم'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => url('/الخدمات-والبرامج'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => url('/اختبارات-الميول'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => url('/استشارات-فورية'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => url('/الكتاب-المهني'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => url('/تواصل-معنا'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => url('/الشروط-والأحكام'), 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['url' => url('/سياسة-الخصوصية'), 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['url' => url('/login'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => url('/register'), 'priority' => '0.5', 'changefreq' => 'monthly'],
        ];
        
        $content = $this->generateSitemap($pages);
        
        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
    
    /**
     * Generate sitemap for assessments
     */
    public function assessments(): Response
    {
        $pages = [];
        
        // Add main assessments page
        $pages[] = ['url' => url('/اختبارات-الميول'), 'priority' => '0.9', 'changefreq' => 'weekly'];
        
        // Add individual assessment pages (Holland, MBTI, MI)
        $assessmentSlugs = ['holland', 'mbti', 'mi'];
        foreach ($assessmentSlugs as $slug) {
            $pages[] = [
                'url' => url('/اختبارات-الميول/' . $slug),
                'priority' => '0.8',
                'changefreq' => 'monthly'
            ];
        }
        
        $content = $this->generateSitemap($pages);
        
        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
    
    /**
     * Generate sitemap for consultations
     */
    public function consultations(): Response
    {
        $pages = [];
        
        // Add main consultations page
        $pages[] = ['url' => url('/استشارات-فورية'), 'priority' => '0.9', 'changefreq' => 'daily'];
        
        // Add individual consultant pages
        $consultants = \App\Models\Consultant::where('is_active', true)->get();
        foreach ($consultants as $consultant) {
            $pages[] = [
                'url' => url('/استشارات-فورية/' . $consultant->id),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ];
        }
        
        $content = $this->generateSitemap($pages);
        
        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
    
    /**
     * Generate sitemap XML content
     */
    private function generateSitemap(array $pages): string
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($pages as $page) {
            $content .= '  <url>' . "\n";
            $content .= '    <loc>' . htmlspecialchars($page['url']) . '</loc>' . "\n";
            $content .= '    <lastmod>' . Carbon::now()->toW3cString() . '</lastmod>' . "\n";
            $content .= '    <changefreq>' . $page['changefreq'] . '</changefreq>' . "\n";
            $content .= '    <priority>' . $page['priority'] . '</priority>' . "\n";
            $content .= '  </url>' . "\n";
        }
        
        $content .= '</urlset>';
        
        return $content;
    }
}



