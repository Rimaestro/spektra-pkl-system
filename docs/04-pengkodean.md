# BAB IV - PENGKODEAN

## 4.1 Teknologi dan Framework yang Digunakan

### 4.1.1 Backend Technology Stack

**Laravel Framework 12**
- **PHP Version**: 8.0+
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum untuk API authentication
- **ORM**: Eloquent ORM untuk database operations
- **Validation**: Laravel Form Request Validation
- **Middleware**: Custom middleware untuk role-based access control

**Package Dependencies:**
```json
{
    "laravel/framework": "^12.0",
    "laravel/sanctum": "^4.0",
    "laravel/tinker": "^2.9",
    "intervention/image": "^3.0",
    "barryvdh/laravel-dompdf": "^3.0",
    "maatwebsite/excel": "^3.1"
}
```

### 4.1.2 Frontend Technology Stack

**UI Framework:**
- **Bootstrap 5**: Untuk responsive design dan component library
- **jQuery**: Untuk DOM manipulation dan AJAX requests
- **Chart.js**: Untuk visualisasi data dan dashboard charts
- **Font Awesome**: Untuk icon system
- **Alpine.js**: Untuk reactive components

## 4.2 Implementasi Authentication System

### 4.2.1 User Model dengan Enhanced Security

<augment_code_snippet path="app/Models/User.php" mode="EXCERPT">
````php
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'nim', 'nip',
        'phone', 'address', 'avatar', 'status',
        'last_login_at', 'last_login_ip', 'login_attempts',
        'locked_until', 'password_changed_at', 'force_password_change',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'password_changed_at' => 'datetime',
            'force_password_change' => 'boolean',
            'login_attempts' => 'integer',
        ];
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if account is locked due to failed login attempts
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }
}
````
</augment_code_snippet>

### 4.2.2 API Authentication Controller

<augment_code_snippet path="app/Http/Controllers/Api/AuthController.php" mode="EXCERPT">
````php
class AuthController extends BaseApiController
{
    /**
     * User login with rate limiting and security checks
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
                'device_name' => ['nullable', 'string', 'max:255'],
            ]);

            // Rate limiting
            $key = 'login.' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 5)) {
                return $this->errorResponse('Too many login attempts', 429);
            }

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                RateLimiter::hit($key);
                return $this->unauthorizedResponse('Invalid credentials');
            }

            // Security checks
            if (!$user->isActive()) {
                return $this->forbiddenResponse('Account is not active');
            }

            if ($user->isLocked()) {
                return $this->forbiddenResponse('Account is locked');
            }

            // Clear rate limiter and update login tracking
            RateLimiter::clear($key);
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'login_attempts' => 0,
            ]);

            // Create API token
            $deviceName = $validated['device_name'] ?? 'API Token';
            $token = $user->createToken($deviceName)->plainTextToken;

            return $this->successResponse([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Login successful');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }
}
````
</augment_code_snippet>

### 4.2.3 Role-Based Access Control Middleware

<augment_code_snippet path="app/Http/Middleware/RoleMiddleware.php" mode="EXCERPT">
````php
class RoleMiddleware
{
    /**
     * Handle role-based access control
     */
    public function handle(Request $request, Closure $next, string ...$roles): SymfonyResponse
    {
        // Check authentication
        if (!$request->user()) {
            return $this->unauthorizedResponse($request, 'Authentication required');
        }

        $user = $request->user();

        // Check if user has any of the required roles
        if (!$this->hasAnyRole($user, $roles)) {
            return $this->forbiddenResponse(
                $request,
                'Insufficient permissions. Required roles: ' . implode(', ', $roles)
            );
        }

        return $next($request);
    }

    /**
     * Check if user has any of the specified roles
     */
    private function hasAnyRole($user, array $roles): bool
    {
        return in_array($user->role, $roles);
    }

    /**
     * Return forbidden response for insufficient permissions
     */
    private function forbiddenResponse(Request $request, string $message): SymfonyResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'error' => 'Forbidden'
            ], Response::HTTP_FORBIDDEN);
        }

        return redirect()->route('dashboard')->with('error', $message);
    }
}
````
</augment_code_snippet>

## 4.3 Implementasi Model PKL dengan Business Logic

### 4.3.1 PKL Model dengan Relationships

<augment_code_snippet path="app/Models/PKL.php" mode="EXCERPT">
````php
class PKL extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_id', 'supervisor_id', 'field_supervisor_id',
        'start_date', 'end_date', 'status', 'description',
        'documents', 'rejection_reason', 'final_score', 'defense_date',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'defense_date' => 'date',
            'documents' => 'array',
            'final_score' => 'decimal:2',
        ];
    }

    /**
     * Business logic methods for PKL status
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Calculate PKL duration in days
     */
    public function getDurationInDays(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Get progress percentage based on elapsed time
     */
    public function getProgressPercentage(): float
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $today = Carbon::today();
        $totalDays = $this->getDurationInDays();

        if ($totalDays <= 0) return 0;
        if ($today->lt($this->start_date)) return 0;
        if ($today->gt($this->end_date)) return 100;

        $elapsedDays = $this->start_date->diffInDays($today);
        return min(100, ($elapsedDays / $totalDays) * 100);
    }

    /**
     * Eloquent relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function fieldSupervisor()
    {
        return $this->belongsTo(User::class, 'field_supervisor_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
````
</augment_code_snippet>

## 4.4 Database Migrations

### 4.4.1 Users Table Migration

<augment_code_snippet path="database/migrations/0001_01_01_000000_create_users_table.php" mode="EXCERPT">
````php
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->enum('role', ['admin', 'koordinator', 'dosen', 'mahasiswa', 'pembimbing_lapangan'])
              ->default('mahasiswa');
        $table->string('nim')->nullable()->unique(); // untuk siswa
        $table->string('nip')->nullable()->unique(); // untuk dosen
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->string('avatar')->nullable();
        $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');

        // Authentication enhancement fields
        $table->timestamp('last_login_at')->nullable();
        $table->string('last_login_ip')->nullable();
        $table->integer('login_attempts')->default(0);
        $table->timestamp('locked_until')->nullable();
        $table->timestamp('password_changed_at')->nullable();
        $table->boolean('force_password_change')->default(false);

        $table->rememberToken();
        $table->timestamps();

        // Indexes for better performance
        $table->index(['role', 'status']);
        $table->index('email_verified_at');
        $table->index('last_login_at');
    });
}
````
</augment_code_snippet>

### 4.4.2 PKL Table Migration

<augment_code_snippet path="database/migrations/2025_07_01_155509_create_pkls_table.php" mode="EXCERPT">
````php
public function up(): void
{
    Schema::create('pkls', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // siswa
        $table->foreignId('company_id')->constrained()->onDelete('cascade');
        $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null'); // dosen pembimbing
        $table->foreignId('field_supervisor_id')->nullable()->constrained('users')->onDelete('set null'); // pembimbing lapangan
        $table->date('start_date');
        $table->date('end_date');
        $table->enum('status', ['pending', 'approved', 'rejected', 'ongoing', 'completed'])
              ->default('pending');
        $table->text('description')->nullable();
        $table->json('documents')->nullable(); // menyimpan path dokumen yang diupload
        $table->text('rejection_reason')->nullable();
        $table->decimal('final_score', 5, 2)->nullable();
        $table->date('defense_date')->nullable(); // tanggal sidang
        $table->timestamps();
    });
}
````
</augment_code_snippet>

## 4.5 API Routes dan Endpoint Structure

### 4.5.1 API Routes Configuration

<augment_code_snippet path="routes/api.php" mode="EXCERPT">
````php
// Public API routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Public data routes
Route::get('/companies', [CompanyController::class, 'index']);

// Protected API routes
Route::middleware(['auth:sanctum', 'active'])->group(function () {
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    });

    // Profile routes
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/change-password', [ProfileController::class, 'changePassword']);
    
    // PKL routes
    Route::apiResource('pkl', PKLController::class);
    Route::apiResource('reports', ReportController::class);
    Route::apiResource('evaluations', EvaluationController::class);
    Route::apiResource('messages', MessageController::class);
    
    // Admin routes
    Route::middleware(['role:admin,koordinator'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('companies', CompanyController::class)->except(['index']);
    });
});
````
</augment_code_snippet>

### 4.5.2 Middleware Configuration

<augment_code_snippet path="bootstrap/app.php" mode="EXCERPT">
````php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);

        // Sanctum middleware for API
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
````
</augment_code_snippet>

## 4.6 Data Seeding untuk Testing

### 4.6.1 User Seeder dengan Multiple Roles

<augment_code_snippet path="database/seeders/UserSeeder.php" mode="EXCERPT">
````php
public function run(): void
{
    // Admin User
    User::create([
        'name' => 'Rio Mayesta',
        'email' => 'admin@spektra.ac.id',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'role' => 'admin',
        'phone' => '081234567890',
        'address' => 'Jl. Merdeka No. 15, Jakarta Pusat',
        'status' => 'active',
        'password_changed_at' => now(),
    ]);

    // Koordinator PKL
    User::create([
        'name' => 'Dr. Siti Nurhaliza',
        'email' => 'koordinator@spektra.ac.id',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'role' => 'koordinator',
        'nip' => '198505152010122001',
        'phone' => '081234567891',
        'status' => 'active',
        'password_changed_at' => now(),
    ]);

    // Sample students and supervisors
    User::factory(15)->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'email_verified_at' => now(),
    ]);

    User::factory(5)->create([
        'role' => 'dosen',
        'status' => 'active',
        'email_verified_at' => now(),
    ]);
}
````
</augment_code_snippet>

## 4.7 Penjelasan Implementasi Fitur Utama

### 4.7.1 Authentication System
Sistem authentication menggunakan Laravel Sanctum untuk API dan session-based authentication untuk web. Fitur keamanan yang diimplementasikan:
- Rate limiting untuk mencegah brute force attack
- Account locking setelah failed login attempts
- Password hashing dengan bcrypt
- Email verification untuk aktivasi akun
- Role-based access control dengan middleware

### 4.7.2 Multi-Role Management
Sistem mendukung 5 role berbeda dengan permission yang berbeda:
- **Admin**: Full access ke semua fitur sistem
- **Koordinator**: Mengelola PKL dan approve pendaftaran
- **Dosen**: Membimbing siswa dan monitoring progress
- **Siswa**: Mendaftar PKL dan submit laporan
- **Pembimbing Lapangan**: Evaluasi siswa di perusahaan

### 4.7.3 PKL Business Logic
Model PKL memiliki business logic untuk:
- Status management (pending, approved, ongoing, completed)
- Progress calculation berdasarkan timeline
- Duration calculation dalam hari
- Relationship management dengan user dan company

### 4.7.4 Database Design
Database dirancang dengan normalisasi 3NF dan menggunakan:
- Foreign key constraints untuk data integrity
- Indexes untuk optimasi query performance
- JSON columns untuk flexible document storage
- Soft deletes untuk data preservation
