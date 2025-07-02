<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\PKL;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class ReportManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $mahasiswa;
    protected $dosen;
    protected $koordinator;
    protected $pkl;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mahasiswa = User::factory()->mahasiswa()->create([
            'status' => 'active',
            'force_password_change' => false
        ]);
        $this->dosen = User::factory()->dosen()->create([
            'status' => 'active',
            'force_password_change' => false
        ]);
        $this->koordinator = User::factory()->koordinator()->create([
            'status' => 'active',
            'force_password_change' => false
        ]);
        
        $company = Company::factory()->create(['status' => 'active']);
        
        $this->pkl = PKL::factory()->create([
            'user_id' => $this->mahasiswa->id,
            'company_id' => $company->id,
            'supervisor_id' => $this->dosen->id,
            'status' => 'ongoing'
        ]);
    }

    public function test_mahasiswa_can_submit_daily_report(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $reportData = [
            'pkl_id' => $this->pkl->id,
            'report_type' => 'daily',
            'title' => 'Laporan Harian - Hari 1',
            'content' => 'Hari ini saya belajar tentang Laravel dan membuat API.',
            'status' => 'submitted'
        ];

        $response = $this->postJson('/api/reports', $reportData);



        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'pkl_id',
                        'report_type',
                        'title',
                        'content',
                        'status'
                    ]
                ]);

        $this->assertDatabaseHas('reports', [
            'pkl_id' => $this->pkl->id,
            'report_type' => 'daily',
            'title' => 'Laporan Harian - Hari 1',
            'status' => 'submitted'
        ]);
    }

    public function test_mahasiswa_can_submit_weekly_report(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $reportData = [
            'pkl_id' => $this->pkl->id,
            'report_type' => 'weekly',
            'title' => 'Laporan Mingguan - Minggu 1',
            'content' => 'Minggu ini saya telah menyelesaikan modul authentication.',
            'status' => 'submitted'
        ];

        $response = $this->postJson('/api/reports', $reportData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reports', [
            'pkl_id' => $this->pkl->id,
            'report_type' => 'weekly',
            'title' => 'Laporan Mingguan - Minggu 1'
        ]);
    }

    public function test_mahasiswa_can_save_report_as_draft(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $reportData = [
            'pkl_id' => $this->pkl->id,
            'report_type' => 'daily',
            'title' => 'Draft Laporan',
            'content' => 'Konten belum selesai...',
            'status' => 'draft'
        ];

        $response = $this->postJson('/api/reports', $reportData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reports', [
            'pkl_id' => $this->pkl->id,
            'status' => 'draft'
        ]);
    }

    public function test_mahasiswa_can_update_draft_report(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $report = Report::factory()->create([
            'pkl_id' => $this->pkl->id,
            'user_id' => $this->mahasiswa->id,
            'status' => 'draft'
        ]);

        $response = $this->putJson("/api/reports/{$report->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 'submitted'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 'submitted'
        ]);
    }

    public function test_mahasiswa_cannot_update_submitted_report(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $report = Report::factory()->create([
            'pkl_id' => $this->pkl->id,
            'user_id' => $this->mahasiswa->id,
            'status' => 'submitted'
        ]);

        $response = $this->putJson("/api/reports/{$report->id}", [
            'title' => 'Updated Title'
        ]);

        $response->assertStatus(403);
    }

    public function test_dosen_can_view_student_reports(): void
    {
        Sanctum::actingAs($this->dosen);

        $report = Report::factory()->create([
            'pkl_id' => $this->pkl->id,
            'user_id' => $this->mahasiswa->id
        ]);

        $response = $this->getJson("/api/reports/{$report->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'pkl_id',
                        'title',
                        'content',
                        'status'
                    ]
                ]);
    }

    public function test_dosen_can_provide_feedback_on_report(): void
    {
        Sanctum::actingAs($this->dosen);

        $report = Report::factory()->create([
            'pkl_id' => $this->pkl->id,
            'user_id' => $this->mahasiswa->id,
            'status' => 'submitted'
        ]);

        $response = $this->putJson("/api/reports/{$report->id}", [
            'feedback' => 'Laporan sudah baik, tingkatkan detail analisis.',
            'status' => 'reviewed'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'feedback' => 'Laporan sudah baik, tingkatkan detail analisis.',
            'status' => 'reviewed'
        ]);
    }

    public function test_mahasiswa_can_list_own_reports(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        Report::factory()->count(3)->create([
            'pkl_id' => $this->pkl->id,
            'user_id' => $this->mahasiswa->id
        ]);

        $response = $this->getJson('/api/reports');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'pkl_id',
                                'title',
                                'report_type',
                                'status'
                            ]
                        ],
                        'pagination' => [
                            'current_page',
                            'last_page',
                            'per_page',
                            'total'
                        ]
                    ]
                ]);
    }

    public function test_mahasiswa_cannot_view_other_student_reports(): void
    {
        $otherMahasiswa = User::factory()->mahasiswa()->create();
        $otherPkl = PKL::factory()->create(['user_id' => $otherMahasiswa->id]);
        
        Sanctum::actingAs($this->mahasiswa);

        $report = Report::factory()->create([
            'pkl_id' => $otherPkl->id,
            'user_id' => $otherMahasiswa->id
        ]);

        $response = $this->getJson("/api/reports/{$report->id}");

        $response->assertStatus(403);
    }

    public function test_koordinator_can_view_all_reports(): void
    {
        Sanctum::actingAs($this->koordinator);

        Report::factory()->count(5)->create();

        $response = $this->getJson('/api/reports');

        $response->assertStatus(200);
    }

    public function test_report_requires_valid_pkl(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $reportData = [
            'pkl_id' => 99999, // Non-existent PKL
            'report_type' => 'daily',
            'title' => 'Test Report',
            'content' => 'Test content'
        ];

        $response = $this->postJson('/api/reports', $reportData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['pkl_id']);
    }

    public function test_report_requires_title_and_content(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $reportData = [
            'pkl_id' => $this->pkl->id,
            'report_type' => 'daily'
            // Missing title and content
        ];

        $response = $this->postJson('/api/reports', $reportData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title', 'content']);
    }

    public function test_report_type_validation(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $reportData = [
            'pkl_id' => $this->pkl->id,
            'report_type' => 'invalid_type',
            'title' => 'Test Report',
            'content' => 'Test content'
        ];

        $response = $this->postJson('/api/reports', $reportData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['report_type']);
    }

    public function test_mahasiswa_can_delete_draft_report(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $report = Report::factory()->create([
            'pkl_id' => $this->pkl->id,
            'user_id' => $this->mahasiswa->id,
            'status' => 'draft'
        ]);

        $response = $this->deleteJson("/api/reports/{$report->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('reports', [
            'id' => $report->id
        ]);
    }

    public function test_mahasiswa_cannot_delete_submitted_report(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        $report = Report::factory()->create([
            'pkl_id' => $this->pkl->id,
            'user_id' => $this->mahasiswa->id,
            'status' => 'submitted'
        ]);

        $response = $this->deleteJson("/api/reports/{$report->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('reports', [
            'id' => $report->id
        ]);
    }

    public function test_report_filtering_by_type(): void
    {
        Sanctum::actingAs($this->mahasiswa);

        Report::factory()->create([
            'pkl_id' => $this->pkl->id,
            'user_id' => $this->mahasiswa->id,
            'report_type' => 'daily'
        ]);

        Report::factory()->create([
            'pkl_id' => $this->pkl->id,
            'user_id' => $this->mahasiswa->id,
            'report_type' => 'weekly'
        ]);

        $response = $this->getJson('/api/reports?type=daily');

        $response->assertStatus(200);

        $reports = $response->json('data.data');
        $this->assertCount(1, $reports);
        $this->assertEquals('daily', $reports[0]['report_type']);
    }
}
