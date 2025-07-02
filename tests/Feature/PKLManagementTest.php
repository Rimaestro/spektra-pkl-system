<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\PKL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class PKLManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $siswa;
    protected $koordinator;
    protected $dosen;
    protected $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->siswa = User::factory()->siswa()->create(['status' => 'active']);
        $this->koordinator = User::factory()->koordinator()->create(['status' => 'active']);
        $this->dosen = User::factory()->dosen()->create(['status' => 'active']);
        $this->company = Company::factory()->create(['status' => 'active']);
    }

    public function test_siswa_can_register_pkl(): void
    {
        Sanctum::actingAs($this->siswa);

        $pklData = [
            'company_id' => $this->company->id,
            'start_date' => '2025-08-01',
            'end_date' => '2025-11-30',
            'description' => 'PKL di bidang web development',
            'documents' => [
                'proposal' => 'documents/proposal_test.pdf',
                'cv' => 'documents/cv_test.pdf'
            ]
        ];

        $response = $this->postJson('/api/pkl', $pklData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'user_id',
                        'company_id',
                        'start_date',
                        'end_date',
                        'status',
                        'description'
                    ]
                ]);

        $this->assertDatabaseHas('pkls', [
            'user_id' => $this->siswa->id,
            'company_id' => $this->company->id,
            'status' => 'pending'
        ]);
    }

    public function test_pkl_registration_requires_valid_dates(): void
    {
        Sanctum::actingAs($this->siswa);

        $pklData = [
            'company_id' => $this->company->id,
            'start_date' => '2025-11-30',
            'end_date' => '2025-08-01', // End date before start date
            'description' => 'PKL test'
        ];

        $response = $this->postJson('/api/pkl', $pklData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['end_date']);
    }

    public function test_koordinator_can_approve_pkl(): void
    {
        Sanctum::actingAs($this->koordinator);

        $pkl = PKL::factory()->create([
            'user_id' => $this->siswa->id,
            'company_id' => $this->company->id,
            'status' => 'pending'
        ]);

        $response = $this->putJson("/api/pkl/{$pkl->id}", [
            'status' => 'approved',
            'supervisor_id' => $this->dosen->id
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('pkls', [
            'id' => $pkl->id,
            'status' => 'approved',
            'supervisor_id' => $this->dosen->id
        ]);
    }

    public function test_koordinator_can_reject_pkl(): void
    {
        Sanctum::actingAs($this->koordinator);

        $pkl = PKL::factory()->create([
            'user_id' => $this->siswa->id,
            'status' => 'pending'
        ]);

        $response = $this->putJson("/api/pkl/{$pkl->id}", [
            'status' => 'rejected',
            'rejection_reason' => 'Dokumen tidak lengkap'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('pkls', [
            'id' => $pkl->id,
            'status' => 'rejected',
            'rejection_reason' => 'Dokumen tidak lengkap'
        ]);
    }

    public function test_siswa_can_view_own_pkl(): void
    {
        Sanctum::actingAs($this->siswa);

        $pkl = PKL::factory()->create([
            'user_id' => $this->siswa->id,
            'company_id' => $this->company->id
        ]);

        $response = $this->getJson("/api/pkl/{$pkl->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'user_id',
                        'company_id',
                        'status',
                        'start_date',
                        'end_date'
                    ]
                ]);
    }

    public function test_siswa_cannot_view_other_student_pkl(): void
    {
        $otherSiswa = User::factory()->siswa()->create();
        Sanctum::actingAs($this->siswa);

        $pkl = PKL::factory()->create([
            'user_id' => $otherSiswa->id
        ]);

        $response = $this->getJson("/api/pkl/{$pkl->id}");

        $response->assertStatus(403);
    }

    public function test_dosen_can_view_supervised_pkl(): void
    {
        Sanctum::actingAs($this->dosen);

        $pkl = PKL::factory()->create([
            'user_id' => $this->siswa->id,
            'supervisor_id' => $this->dosen->id,
            'status' => 'approved'
        ]);

        $response = $this->getJson("/api/pkl/{$pkl->id}");

        $response->assertStatus(200);
    }

    public function test_pkl_status_transitions(): void
    {
        $pkl = PKL::factory()->create(['status' => 'pending']);

        // Test status methods
        $this->assertTrue($pkl->isPending());
        $this->assertFalse($pkl->isApproved());
        $this->assertFalse($pkl->isOngoing());
        $this->assertFalse($pkl->isCompleted());

        // Update status to approved
        $pkl->update(['status' => 'approved']);
        $pkl->refresh();

        $this->assertFalse($pkl->isPending());
        $this->assertTrue($pkl->isApproved());
        $this->assertFalse($pkl->isOngoing());
        $this->assertFalse($pkl->isCompleted());
    }

    public function test_pkl_duration_calculation(): void
    {
        $pkl = PKL::factory()->create([
            'start_date' => '2025-08-01',
            'end_date' => '2025-11-30'
        ]);

        $duration = $pkl->getDurationInDays();
        
        // August has 31 days, September 30, October 31, November 30
        // From Aug 1 to Nov 30 = 121 days
        $this->assertEquals(121, $duration);
    }

    public function test_pkl_progress_calculation(): void
    {
        // Create PKL that started 30 days ago and ends in 60 days
        $startDate = now()->subDays(30);
        $endDate = now()->addDays(60);

        $pkl = PKL::factory()->create([
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ]);

        $progress = $pkl->getProgressPercentage();
        
        // 30 days elapsed out of 90 total days = 33.33%
        $this->assertEqualsWithDelta(33.33, $progress, 0.1);
    }

    public function test_siswa_can_update_pkl_before_approval(): void
    {
        Sanctum::actingAs($this->siswa);

        $pkl = PKL::factory()->create([
            'user_id' => $this->siswa->id,
            'status' => 'pending'
        ]);

        $response = $this->putJson("/api/pkl/{$pkl->id}", [
            'description' => 'Updated description',
            'end_date' => '2025-12-31'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('pkls', [
            'id' => $pkl->id,
            'description' => 'Updated description'
        ]);

        $pkl->refresh();
        $this->assertEquals('2025-12-31', $pkl->end_date->format('Y-m-d'));
    }

    public function test_siswa_cannot_update_approved_pkl(): void
    {
        Sanctum::actingAs($this->siswa);

        $pkl = PKL::factory()->create([
            'user_id' => $this->siswa->id,
            'status' => 'approved'
        ]);

        $response = $this->putJson("/api/pkl/{$pkl->id}", [
            'description' => 'Updated description'
        ]);

        $response->assertStatus(403);
    }

    public function test_koordinator_can_list_all_pkl(): void
    {
        Sanctum::actingAs($this->koordinator);

        PKL::factory()->count(5)->create();

        $response = $this->getJson('/api/pkl');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'user_id',
                                'company_id',
                                'status',
                                'start_date',
                                'end_date'
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

    public function test_siswa_can_delete_pending_pkl(): void
    {
        Sanctum::actingAs($this->siswa);

        $pkl = PKL::factory()->create([
            'user_id' => $this->siswa->id,
            'status' => 'pending'
        ]);

        $response = $this->deleteJson("/api/pkl/{$pkl->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('pkls', [
            'id' => $pkl->id
        ]);
    }

    public function test_siswa_cannot_delete_approved_pkl(): void
    {
        Sanctum::actingAs($this->siswa);

        $pkl = PKL::factory()->create([
            'user_id' => $this->siswa->id,
            'status' => 'approved'
        ]);

        $response = $this->deleteJson("/api/pkl/{$pkl->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('pkls', [
            'id' => $pkl->id
        ]);
    }
}
