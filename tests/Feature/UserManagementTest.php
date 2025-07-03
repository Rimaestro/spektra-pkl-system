<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $koordinator;
    protected $siswa;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->admin()->create([
            'status' => 'active',
            'password' => Hash::make('password')
        ]);
        $this->koordinator = User::factory()->koordinator()->create([
            'status' => 'active',
            'password' => Hash::make('password')
        ]);
        $this->siswa = User::factory()->siswa()->create([
            'status' => 'active',
            'password' => Hash::make('password')
        ]);
    }

    public function test_admin_can_list_all_users(): void
    {
        Sanctum::actingAs($this->admin);

        User::factory()->count(5)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'name',
                                'email',
                                'role',
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

    public function test_koordinator_can_list_users(): void
    {
        Sanctum::actingAs($this->koordinator);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);
    }

    public function test_siswa_cannot_list_users(): void
    {
        Sanctum::actingAs($this->siswa);

        $response = $this->getJson('/api/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user(): void
    {
        Sanctum::actingAs($this->admin);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'dosen',
            'nip' => '1234567890',
            'phone' => '081234567890',
            'status' => 'active'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'status'
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'dosen',
            'nip' => '1234567890'
        ]);
    }

    public function test_admin_can_update_user(): void
    {
        Sanctum::actingAs($this->admin);

        $user = User::factory()->create([
            'role' => 'mahasiswa',
            'status' => 'pending'
        ]);

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'Updated Name',
            'status' => 'active',
            'phone' => '081234567890'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'status' => 'active',
            'phone' => '081234567890'
        ]);
    }

    public function test_admin_can_activate_user(): void
    {
        Sanctum::actingAs($this->admin);

        $user = User::factory()->create(['status' => 'pending']);

        $response = $this->putJson("/api/users/{$user->id}", [
            'status' => 'active'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'active'
        ]);
    }

    public function test_admin_can_deactivate_user(): void
    {
        Sanctum::actingAs($this->admin);

        $user = User::factory()->create(['status' => 'active']);

        $response = $this->putJson("/api/users/{$user->id}", [
            'status' => 'inactive'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'inactive'
        ]);
    }

    public function test_admin_can_delete_user(): void
    {
        Sanctum::actingAs($this->admin);

        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function test_user_can_update_own_profile(): void
    {
        Sanctum::actingAs($this->siswa);

        $response = $this->putJson('/api/profile', [
            'name' => 'Updated Name',
            'phone' => '081234567890',
            'address' => 'New Address'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $this->siswa->id,
            'name' => 'Updated Name',
            'phone' => '081234567890',
            'address' => 'New Address'
        ]);
    }

    public function test_user_can_change_password(): void
    {
        // Update siswa password to known value
        $this->siswa->update([
            'password' => Hash::make('password'),
            'force_password_change' => false
        ]);

        Sanctum::actingAs($this->siswa);

        $response = $this->postJson('/api/change-password', [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertStatus(200);

        // Verify password was changed
        $this->siswa->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->siswa->password));
    }

    public function test_change_password_requires_correct_current_password(): void
    {
        Sanctum::actingAs($this->siswa);

        $response = $this->postJson('/api/change-password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['current_password']);
    }

    public function test_user_role_validation(): void
    {
        Sanctum::actingAs($this->admin);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'invalid_role'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['role']);
    }

    public function test_siswa_requires_nis(): void
    {
        Sanctum::actingAs($this->admin);

        $userData = [
            'name' => 'Test Siswa',
            'email' => 'siswa@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'siswa'
            // Missing NIS
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['nis']);
    }

    public function test_dosen_requires_nip(): void
    {
        Sanctum::actingAs($this->admin);

        $userData = [
            'name' => 'Test Dosen',
            'email' => 'dosen@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'dosen'
            // Missing NIP
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['nip']);
    }

    public function test_email_must_be_unique(): void
    {
        Sanctum::actingAs($this->admin);

        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'name' => 'Test User',
            'email' => 'existing@example.com', // Duplicate email
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'mahasiswa',
            'nis' => '1234567890'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    public function test_nis_must_be_unique(): void
    {
        Sanctum::actingAs($this->admin);

        $existingUser = User::factory()->siswa()->create(['nis' => '1234567890']);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'siswa',
            'nis' => '1234567890' // Duplicate NIS
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['nis']);
    }

    public function test_nip_must_be_unique(): void
    {
        Sanctum::actingAs($this->admin);

        $existingUser = User::factory()->dosen()->create(['nip' => '1234567890']);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'dosen',
            'nip' => '1234567890' // Duplicate NIP
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['nip']);
    }
}
