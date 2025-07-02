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
        $user = User::factory()->siswa()->create(['status' => 'active']);

        $this->assertTrue($user->hasRole('mahasiswa'));
        $this->assertFalse($user->hasRole('dosen'));
        $this->assertTrue($user->hasAnyRole(['mahasiswa', 'dosen']));
        $this->assertNotEmpty($user->nim);
        $this->assertTrue($user->isActive());
        $this->assertEquals('mahasiswa', $user->role);
    }

    public function test_user_role_scopes(): void
    {
        User::factory()->create(['role' => 'mahasiswa']);
        User::factory()->create(['role' => 'dosen']);
        User::factory()->create(['role' => 'admin']);

        $this->assertEquals(1, User::where('role', 'mahasiswa')->count());
        $this->assertEquals(1, User::where('role', 'dosen')->count());
        $this->assertEquals(1, User::where('role', 'admin')->count());
    }

    public function test_company_model_methods(): void
    {
        $company = Company::factory()->create(['status' => 'active', 'max_students' => 5]);

        $this->assertEquals('active', $company->status);
        $this->assertEquals(5, $company->max_students);
        $this->assertNotEmpty($company->name);
        $this->assertNotEmpty($company->email);
    }

    public function test_pkl_model_status_methods(): void
    {
        $pkl = PKL::factory()->create(['status' => 'pending']);

        $this->assertTrue($pkl->isPending());
        $this->assertFalse($pkl->isApproved());
        $this->assertFalse($pkl->isOngoing());
        $this->assertFalse($pkl->isCompleted());
        $this->assertEquals('pending', $pkl->status);
    }

    public function test_report_model_methods(): void
    {
        $pkl = PKL::factory()->create();
        $report = Report::factory()->create([
            'pkl_id' => $pkl->id,
            'report_type' => 'daily',
            'status' => 'submitted'
        ]);

        $this->assertEquals('daily', $report->report_type);
        $this->assertEquals('submitted', $report->status);
        $this->assertNotEmpty($report->title);
        $this->assertEquals($pkl->id, $report->pkl_id);
    }

    public function test_evaluation_model_score_calculation(): void
    {
        $pkl = PKL::factory()->create();
        $evaluation = Evaluation::factory()->create([
            'pkl_id' => $pkl->id,
            'final_score' => 85.5,
            'status' => 'submitted'
        ]);

        $this->assertEquals(85.5, $evaluation->final_score);
        $this->assertEquals('submitted', $evaluation->status);
        $this->assertEquals($pkl->id, $evaluation->pkl_id);
        $this->assertNotEmpty($evaluation->comments);
    }

    public function test_message_model_methods(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $message = Message::factory()->create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'subject' => 'Test Message',
            'read_at' => null
        ]);

        $this->assertEquals($sender->id, $message->sender_id);
        $this->assertEquals($receiver->id, $message->receiver_id);
        $this->assertEquals('Test Message', $message->subject);
        $this->assertNull($message->read_at);
    }
}
