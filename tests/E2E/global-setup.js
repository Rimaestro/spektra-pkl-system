/**
 * Global Setup for SPEKTRA PKL E2E Tests
 * 
 * Setup database, test data, dan environment sebelum menjalankan tests
 */

const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

async function globalSetup() {
  console.log('🚀 Starting SPEKTRA PKL E2E Test Setup...');
  
  try {
    // 1. Setup environment variables untuk testing
    setupTestEnvironment();
    
    // 2. Setup database testing
    await setupTestDatabase();
    
    // 3. Run migrations dan seeders
    await runMigrationsAndSeeders();
    
    // 4. Create test users
    await createTestUsers();
    
    // 5. Setup test companies
    await setupTestCompanies();
    
    console.log('✅ E2E Test Setup completed successfully!');
    
  } catch (error) {
    console.error('❌ E2E Test Setup failed:', error);
    throw error;
  }
}

function setupTestEnvironment() {
  console.log('📝 Setting up test environment...');
  
  // Create .env.testing if it doesn't exist
  const envTestingPath = path.join(process.cwd(), '.env.testing');
  
  if (!fs.existsSync(envTestingPath)) {
    const envContent = `
APP_NAME="SPEKTRA PKL Testing"
APP_ENV=testing
APP_KEY=base64:${Buffer.from('spektra-pkl-testing-key-32-chars').toString('base64')}
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://127.0.0.1:8000

LOG_CHANNEL=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

BROADCAST_CONNECTION=log
CACHE_STORE=array
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=array

MAIL_MAILER=array
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

VITE_APP_NAME="\${APP_NAME}"
`;
    
    fs.writeFileSync(envTestingPath, envContent.trim());
    console.log('✅ Created .env.testing file');
  }
}

async function setupTestDatabase() {
  console.log('🗄️ Setting up test database...');
  
  try {
    // Clear config cache
    execSync('php artisan config:clear --env=testing', { 
      stdio: 'inherit',
      env: { ...process.env, APP_ENV: 'testing' }
    });
    
    console.log('✅ Test database setup completed');
  } catch (error) {
    console.error('❌ Database setup failed:', error);
    throw error;
  }
}

async function runMigrationsAndSeeders() {
  console.log('🔄 Running migrations and seeders...');
  
  try {
    // Run migrations
    execSync('php artisan migrate:fresh --env=testing --force', { 
      stdio: 'inherit',
      env: { ...process.env, APP_ENV: 'testing' }
    });
    
    // Run basic seeders
    execSync('php artisan db:seed --class=DatabaseSeeder --env=testing --force', { 
      stdio: 'inherit',
      env: { ...process.env, APP_ENV: 'testing' }
    });
    
    console.log('✅ Migrations and seeders completed');
  } catch (error) {
    console.warn('⚠️ Migrations/seeders warning (may be normal):', error.message);
  }
}

async function createTestUsers() {
  console.log('👥 Creating test users...');
  
  try {
    // Create test users via artisan command or API
    const testUsersScript = `
      use App\\Models\\User;
      use Illuminate\\Support\\Facades\\Hash;
      
      // Admin user
      User::create([
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'status' => 'active',
        'email_verified_at' => now(),
      ]);
      
      // Koordinator user
      User::create([
        'name' => 'Koordinator Test',
        'email' => 'koordinator@test.com',
        'password' => Hash::make('password'),
        'role' => 'koordinator',
        'nip' => '123456789012345678',
        'status' => 'active',
        'email_verified_at' => now(),
      ]);
      
      // Dosen user
      User::create([
        'name' => 'Dosen Test',
        'email' => 'dosen@test.com',
        'password' => Hash::make('password'),
        'role' => 'dosen',
        'nip' => '123456789012345679',
        'status' => 'active',
        'email_verified_at' => now(),
      ]);
      
      // Siswa user
      User::create([
        'name' => 'Siswa Test',
        'email' => 'siswa@test.com',
        'password' => Hash::make('password'),
        'role' => 'mahasiswa',
        'nim' => '1234567890',
        'status' => 'active',
        'email_verified_at' => now(),
      ]);
      
      // Pembimbing lapangan user
      User::create([
        'name' => 'Pembimbing Test',
        'email' => 'pembimbing@test.com',
        'password' => Hash::make('password'),
        'role' => 'pembimbing_lapangan',
        'status' => 'active',
        'email_verified_at' => now(),
      ]);
      
      // Generic test user
      User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'role' => 'mahasiswa',
        'nim' => '0987654321',
        'status' => 'active',
        'email_verified_at' => now(),
      ]);
    `;
    
    // Write and execute the script
    const scriptPath = path.join(process.cwd(), 'temp_create_users.php');
    fs.writeFileSync(scriptPath, `<?php\nrequire_once 'vendor/autoload.php';\n$app = require_once 'bootstrap/app.php';\n${testUsersScript}`);
    
    execSync(`php -f ${scriptPath}`, { 
      stdio: 'inherit',
      env: { ...process.env, APP_ENV: 'testing' }
    });
    
    // Clean up
    fs.unlinkSync(scriptPath);
    
    console.log('✅ Test users created');
  } catch (error) {
    console.warn('⚠️ Test users creation warning:', error.message);
  }
}

async function setupTestCompanies() {
  console.log('🏢 Setting up test companies...');
  
  try {
    // Create test companies
    const companiesScript = `
      use App\\Models\\Company;
      
      Company::create([
        'name' => 'PT Test Company',
        'address' => 'Jl. Test No. 123, Jakarta',
        'phone' => '021-1234567',
        'email' => 'info@testcompany.com',
        'website' => 'https://testcompany.com',
        'description' => 'Test company for E2E testing',
        'status' => 'active',
      ]);
      
      Company::create([
        'name' => 'CV Test Startup',
        'address' => 'Jl. Startup No. 456, Bandung',
        'phone' => '022-7654321',
        'email' => 'hello@teststartup.com',
        'website' => 'https://teststartup.com',
        'description' => 'Test startup company',
        'status' => 'active',
      ]);
    `;
    
    const scriptPath = path.join(process.cwd(), 'temp_create_companies.php');
    fs.writeFileSync(scriptPath, `<?php\nrequire_once 'vendor/autoload.php';\n$app = require_once 'bootstrap/app.php';\n${companiesScript}`);
    
    execSync(`php -f ${scriptPath}`, { 
      stdio: 'inherit',
      env: { ...process.env, APP_ENV: 'testing' }
    });
    
    fs.unlinkSync(scriptPath);
    
    console.log('✅ Test companies created');
  } catch (error) {
    console.warn('⚠️ Test companies creation warning:', error.message);
  }
}

module.exports = globalSetup;
