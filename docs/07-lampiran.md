# BAB VII - LAMPIRAN

## 7.1 Biografi Tim Pengembang

### 7.1.1 Tim Leader & Backend Developer

**Nama**: Rio Mayesta  
**Role**: Project Manager & Senior Backend Developer  
**Email**: aku.mayesta@gmail.com  
**LinkedIn**: linkedin.com/in/riomayesta  

**Pendidikan:**
- S1 Teknik Informatika, Universitas Teknologi Indonesia (2019-2023)
- SMA Negeri 1 Jakarta (2016-2019)

**Pengalaman:**
- Backend Developer di PT. Teknologi Maju Indonesia (2023-sekarang)
- Freelance Web Developer (2021-2023)
- Intern Software Engineer di PT. Digital Solutions (2022)

**Keahlian Teknis:**
- **Programming Languages**: PHP, JavaScript, Python, Java
- **Frameworks**: Laravel, CodeIgniter, Express.js, Django
- **Database**: MySQL, PostgreSQL, MongoDB
- **Tools**: Git, Docker, AWS, Linux

**Kontribusi dalam Proyek:**
- Arsitektur sistem dan database design
- Implementasi authentication dan authorization
- API development dan integration
- Code review dan quality assurance
- Project management dan team coordination

**Sertifikasi:**
- AWS Certified Developer Associate (2023)
- Laravel Certified Developer (2022)
- Scrum Master Certification (2023)

---

### 7.1.2 Frontend Developer & UI/UX Designer

**Nama**: Aji Dwi Sanusi
**Role**: Frontend Developer & UI/UX Designer  
**Email**: sari.wulandari@spektra.ac.id  
**Portfolio**: behance.net/sariwulandari  

**Pendidikan:**
- S1 Desain Komunikasi Visual, Institut Seni Budaya Indonesia (2020-2024)
- SMK Multimedia Nusantara (2017-2020)

**Pengalaman:**
- UI/UX Designer di CV. Creative Digital (2024-sekarang)
- Freelance Graphic Designer (2022-2024)
- Intern UI/UX Designer di PT. Design Studio (2023)

**Keahlian Teknis:**
- **Design Tools**: Figma, Adobe XD, Sketch, Photoshop, Illustrator
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap, Tailwind CSS
- **Prototyping**: InVision, Marvel, Principle
- **User Research**: User Interview, Usability Testing, A/B Testing

**Kontribusi dalam Proyek:**
- User interface design dan prototyping
- User experience research dan testing
- Frontend implementation dengan responsive design
- Design system dan component library
- Accessibility compliance implementation

**Sertifikasi:**
- Google UX Design Certificate (2023)
- Adobe Certified Expert (2022)
- Figma Advanced Certification (2023)

---

### 7.1.3 Database Administrator & DevOps Engineer

**Nama**: Alpacino Centaury Milano  
**Role**: Database Administrator & DevOps Engineer  
**Email**: ahmad.fauzi@spektra.ac.id  
**GitHub**: github.com/ahmadfauzi  

**Pendidikan:**
- S1 Sistem Informasi, Universitas Bina Nusantara (2018-2022)
- SMK Teknik Komputer dan Jaringan (2015-2018)

**Pengalaman:**
- DevOps Engineer di PT. Cloud Solutions Indonesia (2022-sekarang)
- Database Administrator di PT. Data Center Nusantara (2021-2022)
- IT Support Specialist di CV. Tech Support (2020-2021)

**Keahlian Teknis:**
- **Database**: MySQL, PostgreSQL, MongoDB, Redis
- **DevOps**: Docker, Kubernetes, Jenkins, GitLab CI/CD
- **Cloud**: AWS, Google Cloud, Azure
- **Monitoring**: Prometheus, Grafana, ELK Stack
- **Scripting**: Bash, Python, PowerShell

**Kontribusi dalam Proyek:**
- Database design dan optimization
- Server setup dan configuration
- CI/CD pipeline implementation
- Performance monitoring dan tuning
- Security hardening dan backup strategy

**Sertifikasi:**
- AWS Certified Solutions Architect (2023)
- Kubernetes Certified Administrator (2022)
- MySQL Database Administrator Certification (2021)

---

### 7.1.4 Quality Assurance Engineer

**Nama**: Eggi Wahyu Pratama Putra
**Role**: Quality Assurance Engineer & Tester  
**Email**: dewi.sartika@spektra.ac.id  
**LinkedIn**: linkedin.com/in/dewisartika  

**Pendidikan:**
- S1 Teknik Informatika, Universitas Padjadjaran (2019-2023)
- SMA Negeri 3 Bandung (2016-2019)

**Pengalaman:**
- QA Engineer di PT. Software Testing Solutions (2023-sekarang)
- Manual Tester di PT. Quality Assurance Indonesia (2022-2023)
- Intern Software Tester di PT. Tech Validation (2022)

**Keahlian Teknis:**
- **Testing Tools**: Selenium, Postman, JMeter, OWASP ZAP
- **Test Management**: TestRail, Jira, Azure DevOps
- **Programming**: Java, Python, JavaScript
- **Automation**: Cypress, Playwright, Robot Framework
- **Performance**: LoadRunner, K6, Artillery

**Kontribusi dalam Proyek:**
- Test planning dan test case design
- Manual testing dan automation testing
- Performance testing dan load testing
- Security testing dan vulnerability assessment
- Bug tracking dan quality metrics reporting

**Sertifikasi:**
- ISTQB Foundation Level (2022)
- Selenium WebDriver Certification (2023)
- OWASP Security Testing Certification (2023)

---

### 7.1.5 Business Analyst & Documentation Specialist

**Nama**: Indra Permana  
**Role**: Business Analyst & Technical Writer  
**Email**: indra.permana@spektra.ac.id  
**LinkedIn**: linkedin.com/in/indrapermana  

**Pendidikan:**
- S1 Manajemen Informatika, Universitas Gunadarma (2018-2022)
- SMA Negeri 5 Jakarta (2015-2018)

**Pengalaman:**
- Business Analyst di PT. Business Solutions Indonesia (2022-sekarang)
- Technical Writer di PT. Documentation Services (2021-2022)
- Data Analyst di CV. Analytics Pro (2020-2021)

**Keahlian Teknis:**
- **Analysis Tools**: Microsoft Visio, Lucidchart, Draw.io
- **Documentation**: Confluence, Notion, GitBook
- **Data Analysis**: Excel, Power BI, Tableau
- **Project Management**: Jira, Trello, Asana
- **Modeling**: UML, BPMN, ERD

**Kontribusi dalam Proyek:**
- Requirements gathering dan analysis
- Business process modeling
- Technical documentation writing
- User manual dan training material creation
- Stakeholder communication dan coordination

**Sertifikasi:**
- Certified Business Analysis Professional (2023)
- Project Management Professional (PMP) (2022)
- Technical Writing Certification (2021)

## 7.2 Dokumentasi Teknis Tambahan

### 7.2.1 Environment Setup Guide

**Development Environment:**
```bash
# Prerequisites
- PHP 8.0+
- Composer 2.0+
- Node.js 16+
- MySQL 8.0+
- Git

# Installation Steps
git clone https://github.com/spektra/pkl-system.git
cd pkl-system
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

**Production Environment:**
```bash
# Server Requirements
- Ubuntu 20.04 LTS
- Nginx 1.18+
- PHP 8.0+ with FPM
- MySQL 8.0+
- Redis 6.0+
- SSL Certificate

# Deployment Commands
git pull origin main
composer install --optimize-autoloader --no-dev
npm run production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

### 7.2.2 API Documentation

**Authentication Endpoints:**
```
POST /api/auth/login
POST /api/auth/register
POST /api/auth/logout
GET  /api/auth/me
POST /api/auth/forgot-password
POST /api/auth/reset-password
```

**PKL Management Endpoints:**
```
GET    /api/pkl           - List PKL
POST   /api/pkl           - Create PKL
GET    /api/pkl/{id}      - Show PKL
PUT    /api/pkl/{id}      - Update PKL
DELETE /api/pkl/{id}      - Delete PKL
```

**Report Endpoints:**
```
GET    /api/reports       - List Reports
POST   /api/reports       - Create Report
GET    /api/reports/{id}  - Show Report
PUT    /api/reports/{id}  - Update Report
DELETE /api/reports/{id}  - Delete Report
```

### 7.2.3 Database Schema

**Users Table:**
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','koordinator','dosen','mahasiswa','pembimbing_lapangan') DEFAULT 'mahasiswa',
    nim VARCHAR(20) UNIQUE NULL,
    nip VARCHAR(20) UNIQUE NULL,
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    avatar VARCHAR(255) NULL,
    status ENUM('active','inactive','pending') DEFAULT 'pending',
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45) NULL,
    login_attempts INT DEFAULT 0,
    locked_until TIMESTAMP NULL,
    password_changed_at TIMESTAMP NULL,
    force_password_change BOOLEAN DEFAULT FALSE,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_role_status (role, status),
    INDEX idx_email_verified (email_verified_at),
    INDEX idx_last_login (last_login_at)
);
```

**PKL Table:**
```sql
CREATE TABLE pkls (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    company_id BIGINT UNSIGNED NOT NULL,
    supervisor_id BIGINT UNSIGNED NULL,
    field_supervisor_id BIGINT UNSIGNED NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('pending','approved','rejected','ongoing','completed') DEFAULT 'pending',
    description TEXT NULL,
    documents JSON NULL,
    rejection_reason TEXT NULL,
    final_score DECIMAL(5,2) NULL,
    defense_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (supervisor_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (field_supervisor_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_company_status (company_id, status),
    INDEX idx_supervisor (supervisor_id),
    INDEX idx_field_supervisor (field_supervisor_id)
);
```

## 7.3 User Manual

### 7.3.1 Panduan Login

1. **Akses Sistem**
   - Buka browser dan akses URL: https://spektra-pkl.ac.id
   - Klik tombol "Login" di halaman utama

2. **Proses Login**
   - Masukkan email dan password
   - Klik tombol "Masuk"
   - Sistem akan redirect ke dashboard sesuai role

3. **Troubleshooting Login**
   - Jika lupa password, klik "Lupa Password"
   - Masukkan email untuk reset password
   - Check email untuk link reset password

### 7.3.2 Panduan Pendaftaran PKL (Mahasiswa)

1. **Persiapan Dokumen**
   - CV dalam format PDF (max 2MB)
   - Surat Keterangan Sehat (PDF/JPG)
   - Proposal PKL (PDF)
   - Transkrip Nilai (PDF)

2. **Proses Pendaftaran**
   - Login ke sistem
   - Klik menu "PKL" > "Daftar PKL"
   - Isi form pendaftaran lengkap
   - Upload semua dokumen yang diperlukan
   - Review data dan submit

3. **Monitoring Status**
   - Check status di dashboard
   - Terima notifikasi via email
   - Follow up jika ada dokumen yang kurang

### 7.3.3 Panduan Monitoring PKL (Dosen)

1. **Akses Dashboard**
   - Login dengan akun dosen
   - View dashboard monitoring
   - Check mahasiswa bimbingan

2. **Review Laporan**
   - Klik menu "Mahasiswa Bimbingan"
   - Pilih mahasiswa yang akan direview
   - Baca laporan dan berikan feedback

3. **Input Evaluasi**
   - Akses form evaluasi
   - Isi penilaian sesuai rubrik
   - Submit evaluasi

## 7.4 Troubleshooting Guide

### 7.4.1 Common Issues

**Issue: Cannot Login**
- Solution: Check email/password, reset if needed
- Check account status with admin
- Clear browser cache and cookies

**Issue: File Upload Failed**
- Solution: Check file format (PDF/JPG only)
- Ensure file size < 2MB
- Check internet connection

**Issue: Page Loading Slow**
- Solution: Check internet connection
- Clear browser cache
- Try different browser

### 7.4.2 Contact Support

**Technical Support:**
- Email: support@spektra-pkl.ac.id
- Phone: +62-21-1234-5678
- WhatsApp: +62-812-3456-7890

**Business Support:**
- Email: admin@spektra-pkl.ac.id
- Phone: +62-21-1234-5679

**Operating Hours:**
- Monday - Friday: 08:00 - 17:00 WIB
- Saturday: 08:00 - 12:00 WIB
- Sunday: Closed

---

**Dokumentasi ini akan terus diupdate seiring dengan pengembangan sistem. Untuk versi terbaru, silakan akses repository GitHub atau hubungi tim pengembang.**
