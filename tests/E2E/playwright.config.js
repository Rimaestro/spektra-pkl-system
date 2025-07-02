/**
 * Playwright Configuration for SPEKTRA PKL System E2E Tests
 * 
 * Konfigurasi untuk multi-browser testing dan environment setup
 */

const { defineConfig, devices } = require('@playwright/test');

module.exports = defineConfig({
  // Test directory
  testDir: './tests/E2E',
  
  // Global test timeout
  timeout: 30000,
  
  // Expect timeout for assertions
  expect: {
    timeout: 5000
  },
  
  // Fail the build on CI if you accidentally left test.only in the source code
  forbidOnly: !!process.env.CI,
  
  // Retry on CI only
  retries: process.env.CI ? 2 : 0,
  
  // Opt out of parallel tests on CI
  workers: process.env.CI ? 1 : undefined,
  
  // Reporter configuration
  reporter: [
    ['html', { outputFolder: 'tests/E2E/reports/html' }],
    ['json', { outputFile: 'tests/E2E/reports/results.json' }],
    ['junit', { outputFile: 'tests/E2E/reports/results.xml' }],
    ['list']
  ],
  
  // Global setup and teardown
  globalSetup: require.resolve('./tests/E2E/global-setup.js'),
  globalTeardown: require.resolve('./tests/E2E/global-teardown.js'),
  
  // Shared settings for all projects
  use: {
    // Base URL for tests
    baseURL: 'http://127.0.0.1:8000',
    
    // Collect trace when retrying the failed test
    trace: 'on-first-retry',
    
    // Record video on failure
    video: 'retain-on-failure',
    
    // Take screenshot on failure
    screenshot: 'only-on-failure',
    
    // Browser context options
    viewport: { width: 1280, height: 720 },
    ignoreHTTPSErrors: true,
    
    // Locale and timezone
    locale: 'id-ID',
    timezoneId: 'Asia/Jakarta',
  },

  // Configure projects for major browsers
  projects: [
    {
      name: 'chromium',
      use: { 
        ...devices['Desktop Chrome'],
        // Chrome-specific settings
        channel: 'chrome',
        launchOptions: {
          args: ['--disable-web-security', '--disable-features=VizDisplayCompositor']
        }
      },
    },

    {
      name: 'firefox',
      use: { 
        ...devices['Desktop Firefox'],
        // Firefox-specific settings
        launchOptions: {
          firefoxUserPrefs: {
            'security.tls.insecure_fallback_hosts': 'localhost,127.0.0.1'
          }
        }
      },
    },

    {
      name: 'webkit',
      use: { 
        ...devices['Desktop Safari'],
        // Safari-specific settings
      },
    },

    // Mobile testing
    {
      name: 'Mobile Chrome',
      use: { 
        ...devices['Pixel 5'],
      },
    },
    {
      name: 'Mobile Safari',
      use: { 
        ...devices['iPhone 12'],
      },
    },

    // Tablet testing
    {
      name: 'Tablet',
      use: { 
        ...devices['iPad Pro'],
      },
    },
  ],

  // Run your local dev server before starting the tests
  webServer: {
    command: 'php artisan serve --host=127.0.0.1 --port=8000',
    port: 8000,
    reuseExistingServer: !process.env.CI,
    timeout: 120000,
    env: {
      APP_ENV: 'testing',
      DB_CONNECTION: 'sqlite',
      DB_DATABASE: ':memory:',
    }
  },

  // Test output directory
  outputDir: 'tests/E2E/test-results/',
  
  // Test match patterns
  testMatch: [
    '**/*E2ETest.js',
    '**/*.e2e.js',
    '**/*.spec.js'
  ],
  
  // Global test setup
  globalTimeout: 600000, // 10 minutes for all tests
  
  // Test metadata
  metadata: {
    project: 'SPEKTRA PKL System',
    version: '1.0.0',
    environment: 'testing',
    browser: 'multi-browser',
    platform: process.platform
  }
});
