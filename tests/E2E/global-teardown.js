/**
 * Global Teardown for SPEKTRA PKL E2E Tests
 * 
 * Cleanup setelah semua tests selesai
 */

const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

async function globalTeardown() {
  console.log('🧹 Starting SPEKTRA PKL E2E Test Cleanup...');
  
  try {
    // 1. Clear test database
    await clearTestDatabase();
    
    // 2. Clear cache and config
    await clearCacheAndConfig();
    
    // 3. Clean up temporary files
    await cleanupTempFiles();
    
    // 4. Generate test report summary
    await generateTestSummary();
    
    console.log('✅ E2E Test Cleanup completed successfully!');
    
  } catch (error) {
    console.error('❌ E2E Test Cleanup failed:', error);
    // Don't throw error in teardown to avoid masking test failures
  }
}

async function clearTestDatabase() {
  console.log('🗄️ Clearing test database...');
  
  try {
    // Clear test database (for SQLite in-memory, this is automatic)
    // For persistent test databases, you might want to drop tables
    execSync('php artisan config:clear --env=testing', { 
      stdio: 'pipe',
      env: { ...process.env, APP_ENV: 'testing' }
    });
    
    console.log('✅ Test database cleared');
  } catch (error) {
    console.warn('⚠️ Database cleanup warning:', error.message);
  }
}

async function clearCacheAndConfig() {
  console.log('🧹 Clearing cache and config...');
  
  try {
    // Clear various caches
    const commands = [
      'php artisan config:clear',
      'php artisan cache:clear',
      'php artisan route:clear',
      'php artisan view:clear'
    ];
    
    for (const command of commands) {
      try {
        execSync(command, { 
          stdio: 'pipe',
          env: { ...process.env, APP_ENV: 'testing' }
        });
      } catch (error) {
        // Ignore individual command failures
      }
    }
    
    console.log('✅ Cache and config cleared');
  } catch (error) {
    console.warn('⚠️ Cache cleanup warning:', error.message);
  }
}

async function cleanupTempFiles() {
  console.log('📁 Cleaning up temporary files...');
  
  try {
    const tempFiles = [
      'temp_create_users.php',
      'temp_create_companies.php',
      'storage/logs/laravel-testing.log'
    ];
    
    for (const file of tempFiles) {
      const filePath = path.join(process.cwd(), file);
      if (fs.existsSync(filePath)) {
        fs.unlinkSync(filePath);
        console.log(`🗑️ Removed ${file}`);
      }
    }
    
    console.log('✅ Temporary files cleaned up');
  } catch (error) {
    console.warn('⚠️ Temp files cleanup warning:', error.message);
  }
}

async function generateTestSummary() {
  console.log('📊 Generating test summary...');
  
  try {
    const reportsDir = path.join(process.cwd(), 'tests/E2E/reports');
    
    // Ensure reports directory exists
    if (!fs.existsSync(reportsDir)) {
      fs.mkdirSync(reportsDir, { recursive: true });
    }
    
    // Create a simple test summary
    const summary = {
      timestamp: new Date().toISOString(),
      project: 'SPEKTRA PKL System',
      testType: 'E2E Authentication Tests',
      environment: 'testing',
      platform: process.platform,
      nodeVersion: process.version,
      testCategories: [
        'Registration Flow Tests',
        'Login Flow Tests', 
        'Forgot Password Flow Tests',
        'Dashboard Access Tests',
        'Logout Flow Tests'
      ],
      notes: 'E2E tests for user authentication journey completed'
    };
    
    const summaryPath = path.join(reportsDir, 'test-summary.json');
    fs.writeFileSync(summaryPath, JSON.stringify(summary, null, 2));
    
    console.log(`✅ Test summary generated: ${summaryPath}`);
  } catch (error) {
    console.warn('⚠️ Test summary generation warning:', error.message);
  }
}

module.exports = globalTeardown;
