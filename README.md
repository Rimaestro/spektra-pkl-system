# Rancangan Sistem PKL Terintegrasi SPEKTRA

## A. Tempat Penelitian

**Lokasi**: Universitas/Institut Teknologi [Nama Institusi]
- Program Studi Teknik Informatika/Sistem Informasi
- Bagian Akademik dan Kemahasiswaan
- Unit Praktek Kerja Lapangan (PKL)

**Objek Penelitian**: Sistem pengelolaan PKL yang saat ini masih manual/semi-manual

## B. Work Flow (Alur Kerja) Sistem yang Berjalan

### Sistem Lama (Manual/Semi-Manual):
1. **Pendaftaran PKL**
   - Mahasiswa mengisi formulir manual
   - Mengumpulkan berkas fisik ke bagian akademik
   - Verifikasi manual oleh admin

2. **Penempatan PKL**
   - Mahasiswa mencari tempat PKL sendiri
   - Pengajuan surat pengantar manual
   - Koordinasi via telepon/email terpisah

3. **Monitoring**
   - Laporan progress manual
   - Kunjungan dosen pembimbing tidak terjadwal
   - Komunikasi via WhatsApp/telepon

4. **Evaluasi**
   - Penilaian manual dari pembimbing lapangan
   - Pengumpulan laporan fisik
   - Sidang PKL dengan jadwal manual

### Sistem Baru (SPEKTRA):
1. **Pendaftaran Online**
   - Registrasi dan login sistem
   - Upload dokumen digital
   - Verifikasi otomatis

2. **Penempatan Terintegrasi**
   - Database perusahaan mitra
   - Matching otomatis berdasarkan kriteria
   - Notifikasi real-time

3. **Monitoring Digital**
   - Dashboard progress tracking
   - Laporan online berkala
   - Komunikasi terintegrasi

4. **Evaluasi Sistematis**
   - Penilaian online
   - Laporan digital
   - Jadwal sidang otomatis

## C. Job Description

### 1. Administrator Sistem
- Mengelola data master (mahasiswa, dosen, perusahaan)
- Memverifikasi dokumen dan pendaftaran
- Monitoring sistem secara keseluruhan
- Generate laporan dan statistik

### 2. Koordinator PKL
- Mengelola penempatan mahasiswa
- Koordinasi dengan perusahaan mitra
- Monitoring progress PKL
- Evaluasi dan penilaian

### 3. Dosen Pembimbing
- Membimbing mahasiswa PKL
- Memberikan penilaian
- Monitoring progress melalui sistem
- Komunikasi dengan pembimbing lapangan

### 4. Pembimbing Lapangan (Perusahaan)
- Membimbing mahasiswa di tempat PKL
- Memberikan penilaian kinerja
- Melaporkan progress ke sistem
- Koordinasi dengan dosen pembimbing

### 5. Mahasiswa
- Mendaftar PKL online
- Upload dokumen dan laporan
- Mengikuti monitoring berkala
- Mengikuti sidang PKL

## D. Analisis PIECES

### Performance (Kinerja)
**Masalah Saat Ini:**
- Proses pendaftaran PKL memakan waktu 2-3 minggu
- Pencarian tempat PKL tidak efisien
- Koordinasi antar pihak lambat
- Monitoring progress tidak real-time

**Solusi SPEKTRA:**
- Pendaftaran online dalam 1-2 hari
- Matching otomatis tempat PKL
- Notifikasi real-time
- Dashboard monitoring langsung

### Information (Informasi)
**Masalah Saat Ini:**
- Data tersebar di berbagai tempat
- Informasi tidak up-to-date
- Sulit akses data historis
- Laporan manual tidak akurat

**Solusi SPEKTRA:**
- Database terpusat
- Informasi real-time
- Histori data lengkap
- Laporan otomatis dan akurat

### Economics (Ekonomi)
**Masalah Saat Ini:**
- Biaya operasional tinggi (kertas, printing)
- Waktu staff untuk administrasi manual
- Transportasi untuk koordinasi
- Duplikasi pekerjaan

**Solusi SPEKTRA:**
- Paperless system
- Otomatisasi proses
- Komunikasi digital
- Efisiensi operasional

### Control (Kontrol)
**Masalah Saat Ini:**
- Sulit tracking progress mahasiswa
- Tidak ada standar penilaian
- Monitoring tidak konsisten
- Arsip dokumen tidak terorganisir

**Solusi SPEKTRA:**
- Tracking system terintegrasi
- Standarisasi penilaian
- Monitoring otomatis
- Arsip digital terorganisir

### Efficiency (Efisiensi)
**Masalah Saat Ini:**
- Proses berulang dan manual
- Koordinasi via multiple channel
- Duplikasi data entry
- Workflow tidak optimal

**Solusi SPEKTRA:**
- Workflow otomatis
- Single platform komunikasi
- Input data sekali pakai
- Proses streamlined

### Service (Layanan)
**Masalah Saat Ini:**
- Layanan terbatas jam kerja
- Respon lambat
- Tidak ada self-service
- Informasi tidak mudah diakses

**Solusi SPEKTRA:**
- Akses 24/7
- Respon cepat via sistem
- Self-service portal
- Informasi mudah diakses

## E. Analisis Kebutuhan Fungsional dan Non Fungsional

### Kebutuhan Fungsional

#### 1. Manajemen User
- **F001**: Registrasi dan login multi-role (Admin, Koordinator, Dosen, Mahasiswa, Pembimbing Lapangan)
- **F002**: Manajemen profil user
- **F003**: Reset password
- **F004**: Aktivasi akun

#### 2. Manajemen PKL
- **F005**: Pendaftaran PKL online
- **F006**: Upload dokumen persyaratan
- **F007**: Verifikasi dokumen
- **F008**: Penempatan PKL
- **F009**: Matching mahasiswa dengan perusahaan

#### 3. Monitoring dan Laporan
- **F010**: Input laporan harian/mingguan
- **F011**: Tracking progress PKL
- **F012**: Dashboard monitoring
- **F013**: Notifikasi deadline
- **F014**: Generate laporan PKL

#### 4. Penilaian
- **F015**: Penilaian dari pembimbing lapangan
- **F016**: Penilaian dari dosen pembimbing
- **F017**: Perhitungan nilai akhir
- **F018**: Jadwal sidang PKL

#### 5. Komunikasi
- **F019**: Messaging system
- **F020**: Notifikasi email/SMS
- **F021**: Forum diskusi
- **F022**: Chat real-time

#### 6. Administrasi
- **F023**: Manajemen data perusahaan
- **F024**: Manajemen data mahasiswa
- **F025**: Generate surat-surat
- **F026**: Backup data

### Kebutuhan Non Fungsional

#### 1. Performance
- **NF001**: Response time maksimal 3 detik
- **NF002**: Support concurrent user minimal 100
- **NF003**: Uptime 99.5%
- **NF004**: Loading page maksimal 5 detik

#### 2. Security
- **NF005**: Enkripsi data sensitif
- **NF006**: Authentication dan authorization
- **NF007**: Session management
- **NF008**: Input validation
- **NF009**: SQL injection prevention

#### 3. Usability
- **NF010**: Interface user-friendly
- **NF011**: Responsive design
- **NF012**: Multi-browser support
- **NF013**: Accessible design (WCAG)

#### 4. Reliability
- **NF014**: Error handling yang baik
- **NF015**: Data backup otomatis
- **NF016**: Recovery system
- **NF017**: Logging system

#### 5. Scalability
- **NF018**: Database scalable
- **NF019**: Modular architecture
- **NF020**: Cloud deployment ready

#### 6. Compatibility
- **NF021**: Compatible dengan PHP 8.0+
- **NF022**: MySQL 8.0+ support
- **NF023**: Modern browser support
- **NF024**: Mobile responsive

## Fitur Utama SPEKTRA

### 1. Dashboard
- Overview statistics
- Quick actions
- Recent activities
- Notifications panel

### 2. Profil Management
- Edit profil
- Upload foto
- Change password
- Activity log

### 3. PKL Management
- Daftar PKL
- Upload dokumen
- Status tracking
- Jadwal kegiatan

### 4. Monitoring
- Progress tracking
- Daily/weekly reports
- Photo evidence
- Location tracking

### 5. Communication
- Internal messaging
- Email notifications
- Announcement board
- Discussion forum

### 6. Reporting
- Progress reports
- Final reports
- Evaluation forms
- Certificate generation

## Teknologi yang Digunakan

### Backend
- **Framework**: Laravel 12
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage

### Frontend
- **UI Framework**: Bootstrap 5
- **JavaScript**: jQuery/Alpine.js
- **Charts**: Chart.js
- **Icons**: Font Awesome

### Tools & Services
- **Version Control**: Git
- **Deployment**: Shared Hosting/VPS
- **Email**: SMTP Gmail
- **Notification**: WhatsApp API (optional)

## Timeline Pengembangan

### Phase 1 (Minggu 1-2)
- Setup project Laravel
- Database design dan migration
- Authentication system
- Basic UI template

### Phase 2 (Minggu 3-4)
- User management
- PKL registration
- Document upload
- Basic dashboard

### Phase 3 (Minggu 5-6)
- Monitoring system
- Reporting features
- Communication features
- Notification system

### Phase 4 (Minggu 7-8)
- Testing dan debugging
- UI/UX improvement
- Documentation
- Deployment

## Struktur Database Utama

### Users Table
- id, name, email, password, role, status, created_at, updated_at

### PKL Table
- id, user_id, company_id, supervisor_id, start_date, end_date, status, created_at, updated_at

### Companies Table
- id, name, address, contact_person, phone, email, created_at, updated_at

### Reports Table
- id, pkl_id, report_type, content, file_path, created_at, updated_at

### Evaluations Table
- id, pkl_id, evaluator_id, score, comments, created_at, updated_at

## Kesimpulan

Sistem SPEKTRA dirancang untuk mengatasi permasalahan PKL yang ada dengan pendekatan digital terintegrasi. Dengan fitur-fitur yang sederhana namun lengkap, sistem ini akan meningkatkan efisiensi, transparansi, dan kualitas pengelolaan PKL di institusi pendidikan.