<?php

namespace Tests\Feature;

use App\Models\AnalysisModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CloudStorageTest extends TestCase
{
    use RefreshDatabase;

    // ── Public uploads disk ──────────────────────────────────────────────

    public function test_uploads_go_to_the_uploads_disk_with_a_safe_name(): void
    {
        Storage::fake('uploads');

        $path = store_upload(UploadedFile::fake()->image('../../evil name.png'), 'chapters');

        $this->assertMatchesRegularExpression('#^uploads/chapters/[A-Za-z0-9]{40}\.png$#', $path);
        Storage::disk('uploads')->assertExists(upload_key($path));
        $this->assertStringEndsWith('/'.upload_key($path), storage_asset($path));
    }

    public function test_executable_or_script_files_are_refused(): void
    {
        Storage::fake('uploads');

        foreach ([
            UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;'),
            UploadedFile::fake()->createWithContent('x.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'),
        ] as $file) {
            try {
                store_upload($file, 'site');
                $this->fail('Upload should have been refused: '.$file->getClientOriginalName());
            } catch (HttpException $e) {
                $this->assertSame(422, $e->getStatusCode());
            }
        }

        $this->assertSame([], Storage::disk('uploads')->allFiles());
    }

    public function test_delete_upload_removes_the_file(): void
    {
        Storage::fake('uploads');
        $path = store_upload(UploadedFile::fake()->image('a.jpg'), 'content');

        delete_upload($path);

        Storage::disk('uploads')->assertMissing(upload_key($path));
    }

    public function test_existing_database_paths_and_external_urls_still_resolve(): void
    {
        $this->assertSame(
            Storage::disk('uploads')->url('chapters/old.jpg'),
            storage_asset('uploads/chapters/old.jpg')
        );
        $this->assertSame('https://cdn.example.com/x.jpg', storage_asset('https://cdn.example.com/x.jpg'));
        $this->assertSame('', storage_asset(null));
    }

    // ── Logo / site images ───────────────────────────────────────────────

    public function test_logo_upload_is_stored_on_the_disk_and_used_by_the_site(): void
    {
        Storage::fake('uploads');
        $this->assertSame(asset('images/bright-path-logo.png'), site_image('site_logo', 'images/bright-path-logo.png'));

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.logo.upload'), [
                'logo' => UploadedFile::fake()->image('logo.png'),
                'logo_white' => UploadedFile::fake()->image('white.png'),
            ])
            ->assertSessionHas('success');

        $logo = setting('site_logo');
        $this->assertStringStartsWith('uploads/site/', $logo);
        Storage::disk('uploads')->assertExists(upload_key($logo));
        $this->assertSame(storage_asset($logo), site_image('site_logo', 'images/bright-path-logo.png'));
        $this->assertStringStartsWith('uploads/site/', setting('site_logo_white'));
    }

    // ── Private disk: analysis model files ───────────────────────────────

    public function test_analysis_model_excel_is_stored_privately_parsed_and_downloadable(): void
    {
        Storage::fake('private');

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.analysis-models.store'), [
                'name' => 'نموذج تجريبي',
                'excel_file' => $this->excelUpload(),
                'is_active' => '1',
            ])
            ->assertRedirect();

        $model = AnalysisModel::sole();
        Storage::disk('private')->assertExists($model->file_path);
        $this->assertStringStartsWith('analysis-models/', $model->file_path);
        $this->assertNotEmpty($model->structure);

        auth()->logout();
        $this->get(route('analysis-models.download', $model))
            ->assertOk()
            ->assertDownload('model.xlsx');
        $this->assertSame(1, $model->fresh()->downloads_count);
    }

    public function test_missing_analysis_model_file_returns_404(): void
    {
        Storage::fake('private');
        $model = AnalysisModel::create([
            'name' => 'x', 'slug' => 'x', 'file_path' => 'analysis-models/gone.xlsx',
            'original_file_name' => 'gone.xlsx', 'is_active' => true,
        ]);

        $this->get(route('analysis-models.download', $model))->assertNotFound();
        $this->assertSame(0, $model->fresh()->downloads_count);
    }

    // ── Hostinger-only maintenance routes are gone ───────────────────────

    public function test_web_maintenance_routes_no_longer_exist(): void
    {
        $admin = User::factory()->admin()->create();

        foreach (['run-migrations', 'clear-cache', 'storage-link'] as $path) {
            $this->actingAs($admin)->get("/control-panel/{$path}")->assertNotFound();
        }
    }

    private function excelUpload(): UploadedFile
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([['الاسم', 'العمر'], ['أحمد', 30], ['سارة', 25]]);

        $path = tempnam(sys_get_temp_dir(), 'xlsx');
        (new Xlsx($spreadsheet))->save($path);

        return new UploadedFile($path, 'model.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
    }
}
