/**
 * SPEKTRA PKL System - Authentication E2E Test Cases
 * 
 * Test cases untuk user journey registrasi dan authentication
 * Menggunakan Playwright untuk browser automation
 */

const { test, expect } = require('@playwright/test');

// Test configuration
const BASE_URL = 'http://127.0.0.1:8000';
const TEST_TIMEOUT = 30000;

// Test data
const testUsers = {
  siswa: {
    name: 'Ahmad Siswa',
    email: 'ahmad.siswa@test.com',
    password: 'Password123!',
    role: 'mahasiswa',
    nim: '1234567890',
    phone: '081234567890'
  },
  dosen: {
    name: 'Dr. Budi Dosen',
    email: 'budi.dosen@test.com',
    password: 'Password123!',
    role: 'dosen',
    nip: '123456789012345678',
    phone: '081234567891'
  },
  pembimbing: {
    name: 'Citra Pembimbing',
    email: 'citra.pembimbing@test.com',
    password: 'Password123!',
    role: 'pembimbing_lapangan',
    phone: '081234567892'
  }
};

test.describe('SPEKTRA PKL Authentication E2E Tests', () => {
  
  test.beforeEach(async ({ page }) => {
    // Set timeout untuk setiap test
    test.setTimeout(TEST_TIMEOUT);
    
    // Navigate ke homepage
    await page.goto(BASE_URL);
    await expect(page).toHaveTitle(/Laravel/);
  });

  test.describe('Registration Flow Tests', () => {
    
    test('should successfully register siswa with valid data', async ({ page }) => {
      // Navigate ke halaman register
      await page.click('a[href="/register"]');
      await expect(page).toHaveURL(`${BASE_URL}/register`);
      await expect(page).toHaveTitle(/Buat Akun Baru/);

      // Fill registration form untuk siswa
      await page.fill('input[name="name"]', testUsers.siswa.name);
      await page.fill('input[name="email"]', testUsers.siswa.email);
      await page.fill('input[name="password"]', testUsers.siswa.password);
      await page.fill('input[name="password_confirmation"]', testUsers.siswa.password);

      // Select role siswa
      await page.selectOption('select[name="role"]', 'mahasiswa');

      // Wait for NIM field to appear (conditional field)
      await page.waitForSelector('input[name="nim"]', { timeout: 5000 });
      await page.fill('input[name="nim"]', testUsers.siswa.nim);
      await page.fill('input[name="phone"]', testUsers.siswa.phone);

      // Accept terms and conditions
      await page.check('input[type="checkbox"]');

      // Submit form
      await page.click('button[type="submit"]');

      // Verify successful registration
      await expect(page).toHaveURL(`${BASE_URL}/dashboard`);
      await expect(page.locator('text=Selamat Datang')).toBeVisible();
    });

    test('should successfully register dosen with valid data', async ({ page }) => {
      await page.click('a[href="/register"]');
      await expect(page).toHaveURL(`${BASE_URL}/register`);

      // Fill registration form untuk dosen
      await page.fill('input[name="name"]', testUsers.dosen.name);
      await page.fill('input[name="email"]', testUsers.dosen.email);
      await page.fill('input[name="password"]', testUsers.dosen.password);
      await page.fill('input[name="password_confirmation"]', testUsers.dosen.password);
      
      // Select role dosen
      await page.selectOption('select[name="role"]', 'dosen');
      
      // Wait for NIP field to appear
      await page.waitForSelector('input[name="nip"]', { timeout: 5000 });
      await page.fill('input[name="nip"]', testUsers.dosen.nip);
      await page.fill('input[name="phone"]', testUsers.dosen.phone);

      await page.check('input[type="checkbox"]');
      await page.click('button[type="submit"]');

      await expect(page).toHaveURL(`${BASE_URL}/dashboard`);
      await expect(page.locator('text=Selamat Datang')).toBeVisible();
    });

    test('should show validation errors for empty required fields', async ({ page }) => {
      await page.click('a[href="/register"]');
      
      // Submit empty form
      await page.click('button[type="submit"]');

      // Check for validation error messages
      await expect(page.locator('text=Nama lengkap wajib diisi')).toBeVisible();
      await expect(page.locator('text=Email wajib diisi')).toBeVisible();
      await expect(page.locator('text=Password wajib diisi')).toBeVisible();
    });

    test('should show validation error for invalid email format', async ({ page }) => {
      await page.click('a[href="/register"]');
      
      await page.fill('input[name="name"]', 'Test User');
      await page.fill('input[name="email"]', 'invalid-email');
      await page.fill('input[name="password"]', 'Password123!');
      await page.fill('input[name="password_confirmation"]', 'Password123!');
      
      await page.click('button[type="submit"]');
      
      await expect(page.locator('text=Format email tidak valid')).toBeVisible();
    });

    test('should show validation error for weak password', async ({ page }) => {
      await page.click('a[href="/register"]');
      
      await page.fill('input[name="name"]', 'Test User');
      await page.fill('input[name="email"]', 'test@example.com');
      await page.fill('input[name="password"]', '123'); // Weak password
      await page.fill('input[name="password_confirmation"]', '123');
      
      await page.click('button[type="submit"]');
      
      await expect(page.locator('text=Password minimal 8 karakter')).toBeVisible();
    });

    test('should show validation error for password confirmation mismatch', async ({ page }) => {
      await page.click('a[href="/register"]');
      
      await page.fill('input[name="name"]', 'Test User');
      await page.fill('input[name="email"]', 'test@example.com');
      await page.fill('input[name="password"]', 'Password123!');
      await page.fill('input[name="password_confirmation"]', 'DifferentPassword123!');
      
      await page.click('button[type="submit"]');
      
      await expect(page.locator('text=Konfirmasi password tidak cocok')).toBeVisible();
    });

    test('should require NIM for siswa role', async ({ page }) => {
      await page.click('a[href="/register"]');

      await page.fill('input[name="name"]', 'Test Siswa');
      await page.fill('input[name="email"]', 'siswa@test.com');
      await page.fill('input[name="password"]', 'Password123!');
      await page.fill('input[name="password_confirmation"]', 'Password123!');
      await page.selectOption('select[name="role"]', 'mahasiswa');

      // Don't fill NIM
      await page.check('input[type="checkbox"]');
      await page.click('button[type="submit"]');

      await expect(page.locator('text=NIS wajib diisi untuk siswa')).toBeVisible();
    });

    test('should require NIP for dosen role', async ({ page }) => {
      await page.click('a[href="/register"]');
      
      await page.fill('input[name="name"]', 'Test Dosen');
      await page.fill('input[name="email"]', 'dosen@test.com');
      await page.fill('input[name="password"]', 'Password123!');
      await page.fill('input[name="password_confirmation"]', 'Password123!');
      await page.selectOption('select[name="role"]', 'dosen');
      
      // Don't fill NIP
      await page.check('input[type="checkbox"]');
      await page.click('button[type="submit"]');
      
      await expect(page.locator('text=NIP wajib diisi untuk dosen')).toBeVisible();
    });
  });

  test.describe('Login Flow Tests', () => {
    
    test.beforeEach(async ({ page }) => {
      // Create a test user first (this would typically be done via API or database seeder)
      // For now, we'll assume the user exists or create via registration
    });

    test('should successfully login with valid credentials', async ({ page }) => {
      await page.click('a[href="/login"]');
      await expect(page).toHaveURL(`${BASE_URL}/login`);
      await expect(page).toHaveTitle(/Masuk ke Akun/);

      // Fill login form
      await page.fill('input[name="email"]', 'test@example.com');
      await page.fill('input[name="password"]', 'password');
      
      await page.click('button[type="submit"]');

      // Should redirect to dashboard
      await expect(page).toHaveURL(`${BASE_URL}/dashboard`);
      await expect(page.locator('text=Selamat Datang')).toBeVisible();
    });

    test('should show error for invalid credentials', async ({ page }) => {
      await page.click('a[href="/login"]');
      
      await page.fill('input[name="email"]', 'invalid@example.com');
      await page.fill('input[name="password"]', 'wrongpassword');
      
      await page.click('button[type="submit"]');

      await expect(page.locator('text=Email atau password tidak valid')).toBeVisible();
      await expect(page).toHaveURL(`${BASE_URL}/login`);
    });

    test('should show validation errors for empty fields', async ({ page }) => {
      await page.click('a[href="/login"]');
      
      await page.click('button[type="submit"]');

      await expect(page.locator('text=Email wajib diisi')).toBeVisible();
      await expect(page.locator('text=Password wajib diisi')).toBeVisible();
    });

    test('should show validation error for invalid email format', async ({ page }) => {
      await page.click('a[href="/login"]');
      
      await page.fill('input[name="email"]', 'invalid-email');
      await page.fill('input[name="password"]', 'password');
      
      await page.click('button[type="submit"]');

      await expect(page.locator('text=Format email tidak valid')).toBeVisible();
    });
  });

  test.describe('Forgot Password Flow Tests', () => {
    
    test('should navigate to forgot password page', async ({ page }) => {
      await page.click('a[href="/login"]');
      await page.click('a[href="/forgot-password"]');
      
      await expect(page).toHaveURL(`${BASE_URL}/forgot-password`);
      await expect(page).toHaveTitle(/Lupa Password/);
    });

    test('should show success message for valid email', async ({ page }) => {
      await page.goto(`${BASE_URL}/forgot-password`);
      
      await page.fill('input[name="email"]', 'test@example.com');
      await page.click('button[type="submit"]');

      await expect(page.locator('text=Link reset password telah dikirim')).toBeVisible();
    });

    test('should show error for non-existent email', async ({ page }) => {
      await page.goto(`${BASE_URL}/forgot-password`);
      
      await page.fill('input[name="email"]', 'nonexistent@example.com');
      await page.click('button[type="submit"]');

      await expect(page.locator('text=Email tidak ditemukan')).toBeVisible();
    });
  });

  test.describe('Dashboard Access Tests', () => {
    
    test('should redirect to login when accessing dashboard without authentication', async ({ page }) => {
      await page.goto(`${BASE_URL}/dashboard`);
      
      await expect(page).toHaveURL(`${BASE_URL}/login`);
    });

    test('should access dashboard after successful login', async ({ page }) => {
      // Login first
      await page.goto(`${BASE_URL}/login`);
      await page.fill('input[name="email"]', 'test@example.com');
      await page.fill('input[name="password"]', 'password');
      await page.click('button[type="submit"]');

      // Should be on dashboard
      await expect(page).toHaveURL(`${BASE_URL}/dashboard`);
      await expect(page.locator('text=Dashboard')).toBeVisible();
    });
  });

  test.describe('Logout Flow Tests', () => {
    
    test('should successfully logout and redirect to login', async ({ page }) => {
      // Login first
      await page.goto(`${BASE_URL}/login`);
      await page.fill('input[name="email"]', 'test@example.com');
      await page.fill('input[name="password"]', 'password');
      await page.click('button[type="submit"]');

      // Logout
      await page.click('button:has-text("Logout")');
      
      await expect(page).toHaveURL(`${BASE_URL}/login`);
    });
  });
});
