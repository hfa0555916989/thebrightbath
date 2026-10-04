<?php

namespace Tests\Feature;

use App\Models\Consultant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Every public page must render, with production-like settings (database cache and
 * sessions, debug off) and seeded content. Catches template compile errors such as
 * Laravel 12+'s @context directive swallowing JSON-LD "@context" keys.
 */
class ProductionLikeRenderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['cache.default' => 'database', 'session.driver' => 'database', 'app.debug' => false]);
        $this->artisan('db:seed', ['--force' => true]);
    }

    public static function publicPages(): array
    {
        return [
            'home' => ['home'],
            'about' => ['about'],
            'vision & mission' => ['vision-mission'],
            'strategic goals' => ['strategic-goals'],
            'values' => ['values'],
            'services' => ['services'],
            'terms' => ['terms'],
            'privacy' => ['privacy'],
            'assessments' => ['assessments.index'],
            'analysis models' => ['analysis-models.index'],
            'library' => ['career-book.index'],
            'contact' => ['contact'],
            'consultants' => ['consultations.index'],
            'login' => ['login'],
            'register' => ['register'],
            'forgot password' => ['password.forgot'],
            'sitemap' => ['sitemap'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_public_page_renders(string $route): void
    {
        $this->get(route($route))->assertOk();
        $this->get(route($route))->assertOk(); // second hit reads from the cache
    }

    public function test_consultant_profile_page_renders(): void
    {
        $consultant = Consultant::factory()->create();

        $this->get(route('consultations.show', $consultant))->assertOk();
    }

    public function test_json_ld_keeps_its_context_key(): void
    {
        $this->get(route('home'))->assertSee('"@context"', false);
    }

    public function test_client_dashboard_renders(): void
    {
        $this->actingAs(User::factory()->create())->get(route('client.dashboard'))->assertOk();
    }
}
