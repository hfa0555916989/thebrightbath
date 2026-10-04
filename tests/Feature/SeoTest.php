<?php

namespace Tests\Feature;

use App\Models\AnalysisModel;
use App\Models\BookChapter;
use App\Models\Consultant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    private function locs(string $xml): array
    {
        preg_match_all('#<loc>([^<]+)</loc>#', $xml, $m);

        return array_map('html_entity_decode', $m[1]);
    }

    private function pathOf(string $url): string
    {
        return parse_url($url, PHP_URL_PATH) ?: '/';
    }

    public function test_every_sitemap_url_opens_a_real_page(): void
    {
        $consultant = Consultant::factory()->create();
        $chapter = BookChapter::create(['title' => 'مقدمة', 'slug' => 'intro', 'order' => 1, 'is_free' => true, 'is_published' => true, 'content_html' => '<p>نص</p>']);
        $model = AnalysisModel::create(['name' => 'نموذج', 'slug' => 'model-1', 'is_active' => true]);

        $index = $this->get(route('sitemap'))->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8')->getContent();
        $sitemaps = $this->locs($index);
        $this->assertCount(3, $sitemaps);

        $urls = [];
        foreach ($sitemaps as $sitemap) {
            $urls = array_merge($urls, $this->locs($this->get($this->pathOf($sitemap))->assertOk()->getContent()));
        }

        $this->assertContains(route('consultations.show', $consultant), $urls);
        $this->assertContains(route('career-book.show', $chapter->slug), $urls);
        $this->assertContains(route('analysis-models.show', $model), $urls);

        foreach ($urls as $url) {
            $this->get($this->pathOf($url))->assertOk();
        }
        $this->assertGreaterThan(12, count($urls));
    }

    public function test_robots_txt_points_to_the_sitemap_and_hides_private_areas(): void
    {
        $robots = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Sitemap: https://thebrightbath.com/sitemap.xml', $robots);
        $this->assertStringContainsString('Disallow: /video-call/', $robots);
        $this->assertStringNotContainsString(config('app.admin_path'), $robots);
        $this->assertStringNotContainsString('Disallow: /*.xml', $robots);
    }

    public function test_pages_get_their_own_title_with_the_site_name(): void
    {
        $consultant = Consultant::factory()->create();

        $this->get(route('consultations.show', $consultant))
            ->assertSee('<title>حجز موعد مع '.$consultant->user->name.' | الطريق المشرق</title>', false);

        $this->get(route('home'))->assertSee('<title>الطريق المشرق للتدريب والتطوير - Bright Path</title>', false);
    }

    public function test_public_pages_are_indexable_and_signed_in_pages_are_not(): void
    {
        $this->get(route('home'))
            ->assertSee('<meta name="robots" content="index, follow">', false)
            ->assertSee('<meta property="og:type" content="website">', false);

        $this->actingAs(User::factory()->create())
            ->get(route('client.dashboard'))
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }
}
