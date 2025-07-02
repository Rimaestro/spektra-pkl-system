<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\PKL;
use App\Models\Report;
use App\Models\Evaluation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_model_helper_methods(): void
    {
        $user = User::factory()->mahasiswa()->create();

        $this->assertTrue($user->isMahasiswa());
        $this->assertFalse($user->isDosen());
        $this->assertEquals('Mahasiswa', $user->getRoleLabel());
        $this->assertNotEmpty($user->nim);
    }

    public function test_user_role_scopes(): void
    {
        User::factory()->mahasiswa()->count(3)->create();
        User::factory()->dosen()->count(2)->create();

        $this->assertEquals(3, User::mahasiswa()->count());
        $this->assertEquals(2, User::dosen()->count());
    }

    public function test_company_model_methods(): void
    {
        $company = Company::factory()->active()->create(['max_students' => 5]);

        $this->assertTrue($company->isActive());
        $this->assertEquals(5, $company->getAvailableSlots());
        $this->assertTrue($company->hasAvailableSlots());
        $this->assertEquals('Aktif', $company->getStatusLabel());
    }

    public function test_pkl_model_status_methods(): void
    {
        $pkl = PKL::factory()->create(['status' => 'pending']);

        $this->assertTrue($pkl->isPending());
        $this->assertFalse($pkl->isOngoing());
        $this->assertEquals('Menunggu Persetujuan', $pkl->getStatusLabel());
    }

    public function test_report_model_methods(): void
    {
        $report = Report::factory()->create([
            'status' => 'draft',
            'report_type' => 'daily'
        ]);

        $this->assertTrue($report->isDraft());
        $this->assertTrue($report->isDaily());
        $this->assertEquals('Laporan Harian', $report->getReportTypeLabel());
        $this->assertTrue($report->canBeEdited());
    }

    public function test_evaluation_model_score_calculation(): void
    {
        $evaluation = Evaluation::factory()->create([
            'technical_score' => 80,
            'attitude_score' => 85,
            'communication_score' => 90
        ]);

        $this->assertEquals(85.0, $evaluation->calculateFinalScore());
        $this->assertEquals('A-', $evaluation->getGradeLetter());
        $this->assertTrue($evaluation->isPassingGrade());
    }

    public function test_message_model_methods(): void
    {
        $message = Message::factory()->create([
            'priority' => 'high',
            'read_at' => null
        ]);

        $this->assertTrue($message->isHighPriority());
        $this->assertTrue($message->isUnread());
        $this->assertEquals('Tinggi', $message->getPriorityLabel());
    }
}
