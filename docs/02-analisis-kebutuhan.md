# BAB II - ANALISIS KEBUTUHAN SISTEM

## 2.1 Tempat Penelitian

### 2.1.1 Profil Institusi

**Nama Institusi**: SMK Muhammadiyah 3 Purwokerto, Banyumas  
**Alamat**: Jl. Kyai H. Wahid Hasim No.271, Windusara, Karangklesem, Kec. Purwokerto Sel., Kabupaten Banyumas, Jawa Tengah 53144  
**Status**: Sekolah Menengah Kejuruan Swasta  

### 2.1.2 Unit Penelitian

**Bagian**: Unit Praktek Kerja Lapangan (PKL) dan Bagian Akademik Kesiswaan  
**Objek Penelitian**: Sistem pengelolaan PKL yang saat ini masih manual/semi-manual  

### 2.1.3 Karakteristik Institusi

- **Jumlah Siswa PKL**: Rata-rata 150-200 siswa per tahun
- **Program Keahlian**: Teknik Komputer dan Jaringan, Rekayasa Perangkat Lunak, Multimedia
- **Durasi PKL**: 3-6 bulan tergantung program keahlian
- **Mitra Industri**: 50+ perusahaan dan instansi

## 2.2 Work Flow (Alur Kerja) Sistem yang Berjalan

### 2.2.1 Sistem Lama (Manual/Semi-Manual)

#### A. Tahap Pendaftaran PKL
1. **Pengumuman PKL**
   - Koordinator mengumumkan jadwal PKL via papan pengumuman
   - Informasi disebarkan melalui guru kelas
   - Tidak ada sistem notifikasi otomatis

2. **Pendaftaran Siswa**
   - Siswa mengisi formulir pendaftaran manual
   - Mengumpulkan berkas fisik (CV, surat keterangan sehat, dll)
   - Antrian panjang di bagian akademik
   - **Waktu proses**: 2-3 minggu

3. **Verifikasi Dokumen**
   - Admin memeriksa kelengkapan berkas secara manual
   - Proses verifikasi memakan waktu lama
   - Risiko kehilangan dokumen

#### B. Tahap Penempatan PKL
1. **Pencarian Tempat PKL**
   - Siswa mencari tempat PKL secara mandiri
   - Tidak ada database perusahaan mitra
   - Informasi perusahaan tersebar

2. **Pengajuan Surat Pengantar**
   - Siswa mengajukan surat pengantar manual
   - Proses pembuatan surat memakan waktu 3-5 hari
   - Koordinasi via telepon/email terpisah

3. **Konfirmasi Penempatan**
   - Konfirmasi dilakukan via telepon
   - Tidak ada dokumentasi yang sistematis
   - Sering terjadi miscommunication

#### C. Tahap Monitoring
1. **Laporan Progress**
   - Siswa membuat laporan harian/mingguan manual
   - Pengumpulan laporan tidak terjadwal
   - Sulit melakukan tracking progress

2. **Kunjungan Pembimbing**
   - Jadwal kunjungan tidak terstruktur
   - Koordinasi via WhatsApp/telepon
   - Dokumentasi kunjungan tidak lengkap

3. **Komunikasi**
   - Komunikasi tersebar di berbagai platform
   - Tidak ada record komunikasi yang sistematis
   - Sulit melakukan follow-up

#### D. Tahap Evaluasi
1. **Penilaian**
   - Penilaian dari pembimbing lapangan manual
   - Form penilaian fisik
   - Proses pengumpulan nilai lambat

2. **Laporan Akhir**
   - Pengumpulan laporan PKL dalam bentuk fisik
   - Tidak ada template yang standar
   - Sulit melakukan evaluasi komprehensif

3. **Sidang PKL**
   - Penjadwalan sidang manual
   - Koordinasi jadwal sulit
   - Dokumentasi hasil sidang tidak sistematis

### 2.2.2 Sistem Baru (SPEKTRA)

#### A. Pendaftaran Online
1. **Registrasi dan Login**
   - Sistem multi-role authentication
   - Verifikasi email otomatis
   - Dashboard sesuai role pengguna

2. **Upload Dokumen Digital**
   - Upload dokumen dalam format digital
   - Validasi format dan ukuran file otomatis
   - Backup dokumen tersentralisasi

3. **Verifikasi Otomatis**
   - Checklist kelengkapan dokumen otomatis
   - Notifikasi status verifikasi real-time
   - **Waktu proses**: 1-2 hari

#### B. Penempatan Terintegrasi
1. **Database Perusahaan Mitra**
   - Katalog perusahaan dengan detail lengkap
   - Informasi kuota dan persyaratan
   - Status ketersediaan real-time

2. **Matching Otomatis**
   - Algoritma matching berdasarkan kriteria
   - Rekomendasi tempat PKL yang sesuai
   - Sistem ranking dan prioritas

3. **Notifikasi Real-time**
   - Notifikasi email dan in-app
   - Update status penempatan otomatis
   - Reminder deadline penting

#### C. Monitoring Digital
1. **Dashboard Progress Tracking**
   - Visualisasi progress PKL real-time
   - Grafik dan chart monitoring
   - Alert untuk milestone penting

2. **Laporan Online Berkala**
   - Form laporan digital terstruktur
   - Upload foto dan dokumentasi
   - Tracking lokasi (opsional)

3. **Komunikasi Terintegrasi**
   - Sistem messaging internal
   - Forum diskusi
   - Video call integration (future)

#### D. Evaluasi Sistematis
1. **Penilaian Online**
   - Form evaluasi digital
   - Sistem scoring otomatis
   - Multi-evaluator support

2. **Laporan Digital**
   - Template laporan standar
   - Export ke berbagai format
   - Version control dokumen

3. **Jadwal Sidang Otomatis**
   - Sistem penjadwalan terintegrasi
   - Kalender bersama
   - Reminder otomatis

## 2.3 Job Description

### 2.3.1 Administrator
**Tanggung Jawab:**
- Mengelola konfigurasi sistem secara keseluruhan
- Manajemen user dan hak akses
- Monitoring performa dan keamanan sistem
- Backup dan maintenance database
- Mengelola master data perusahaan

**Wewenang:**
- Full access ke semua modul sistem
- Dapat menambah, mengubah, dan menghapus data
- Mengatur role dan permission user
- Mengakses log sistem dan audit trail

### 2.3.2 Koordinator PKL
**Tanggung Jawab:**
- Mengkoordinasikan seluruh proses PKL
- Verifikasi dan approval pendaftaran PKL
- Monitoring progress seluruh siswa PKL
- Mengelola jadwal dan timeline PKL
- Membuat laporan komprehensif untuk manajemen

**Wewenang:**
- Approve/reject pendaftaran PKL
- Assign dosen pembimbing
- Akses dashboard monitoring semua PKL
- Generate laporan dan analitik
- Mengelola pengumuman dan notifikasi

### 2.3.3 Dosen Pembimbing
**Tanggung Jawab:**
- Membimbing siswa selama PKL
- Melakukan monitoring progress siswa
- Memberikan feedback dan evaluasi
- Melakukan kunjungan ke tempat PKL
- Menilai laporan dan presentasi PKL

**Wewenang:**
- Akses data siswa bimbingan
- Input penilaian dan evaluasi
- Komunikasi dengan siswa dan pembimbing lapangan
- Upload dokumentasi kunjungan
- Approve laporan PKL

### 2.3.4 Siswa
**Tanggung Jawab:**
- Mendaftar PKL sesuai jadwal yang ditentukan
- Upload dokumen persyaratan PKL
- Melaksanakan PKL sesuai aturan yang berlaku
- Membuat laporan progress berkala
- Mengikuti bimbingan dan evaluasi

**Wewenang:**
- Akses informasi PKL dan perusahaan
- Upload dokumen dan laporan
- Komunikasi dengan pembimbing
- View progress dan penilaian
- Download sertifikat PKL

### 2.3.5 Pembimbing Lapangan
**Tanggung Jawab:**
- Membimbing siswa di tempat PKL
- Memberikan tugas dan project sesuai kompetensi
- Melakukan evaluasi kinerja siswa
- Koordinasi dengan dosen pembimbing
- Memberikan sertifikat dan penilaian akhir

**Wewenang:**
- Akses profil siswa bimbingan
- Input penilaian dan feedback
- Komunikasi dengan dosen pembimbing
- Upload dokumentasi kegiatan PKL
- Generate sertifikat PKL

## 2.4 Analisis PIECES

### 2.4.1 Performance (Kinerja)

**Masalah Saat Ini:**
- Proses pendaftaran PKL memakan waktu 2-3 minggu
- Pencarian tempat PKL tidak efisien (1-2 bulan)
- Koordinasi antar pihak lambat (response time 1-3 hari)
- Monitoring progress tidak real-time
- Pembuatan laporan memakan waktu berhari-hari

**Solusi SPEKTRA:**
- Pendaftaran online dalam 1-2 hari
- Matching otomatis tempat PKL (1-3 hari)
- Notifikasi real-time (response time < 1 jam)
- Dashboard monitoring langsung
- Generate laporan otomatis (< 5 menit)

**Target Peningkatan:**
- Efisiensi waktu proses: 80-90%
- Response time komunikasi: 95%
- Kecepatan akses informasi: 90%

### 2.4.2 Information (Informasi)

**Masalah Saat Ini:**
- Data tersebar di berbagai tempat (file, email, WhatsApp)
- Informasi tidak up-to-date dan sering outdated
- Sulit akses data historis PKL
- Laporan manual tidak akurat dan tidak konsisten
- Tidak ada backup data yang sistematis

**Solusi SPEKTRA:**
- Database terpusat dengan single source of truth
- Real-time update informasi
- Sistem archiving dan historical data
- Laporan otomatis dengan template standar
- Backup otomatis dan disaster recovery

**Target Peningkatan:**
- Akurasi data: 98%
- Konsistensi informasi: 95%
- Availability data: 99.5%

### 2.4.3 Economics (Ekonomi)

**Masalah Saat Ini:**
- Biaya operasional tinggi (kertas, tinta, fotocopy)
- Biaya komunikasi (pulsa, internet terpisah)
- Biaya tenaga kerja untuk proses manual
- Biaya penyimpanan dokumen fisik
- Loss productivity karena proses lambat

**Solusi SPEKTRA:**
- Paperless system mengurangi biaya operasional
- Komunikasi terintegrasi mengurangi biaya telekomunikasi
- Otomatisasi mengurangi beban kerja manual
- Cloud storage mengurangi biaya penyimpanan fisik
- Peningkatan produktivitas dengan proses yang efisien

**Target Penghematan:**
- Biaya operasional: 60-70%
- Biaya komunikasi: 50%
- Efisiensi tenaga kerja: 40%

### 2.4.4 Control (Kontrol)

**Masalah Saat Ini:**
- Tidak ada audit trail yang jelas
- Sulit melakukan tracking perubahan data
- Kontrol akses tidak terstruktur
- Tidak ada backup dan recovery plan
- Keamanan data tidak terjamin

**Solusi SPEKTRA:**
- Sistem logging dan audit trail lengkap
- Version control untuk semua perubahan
- Role-based access control (RBAC)
- Automated backup dan disaster recovery
- Enkripsi data dan secure authentication

**Target Peningkatan:**
- Security level: 95%
- Data integrity: 99%
- Audit compliance: 100%

### 2.4.5 Efficiency (Efisiensi)

**Masalah Saat Ini:**
- Duplikasi pekerjaan dan redundansi proses
- Manual data entry yang repetitif
- Tidak ada standardisasi proses
- Resource tidak optimal
- Bottleneck di berbagai tahapan

**Solusi SPEKTRA:**
- Eliminasi duplikasi dengan sistem terintegrasi
- Otomatisasi data entry dan validasi
- Standardisasi workflow dan business process
- Optimalisasi resource allocation
- Parallel processing untuk menghindari bottleneck

**Target Peningkatan:**
- Efisiensi proses: 75%
- Pengurangan redundansi: 90%
- Standardisasi: 100%

### 2.4.6 Service (Layanan)

**Masalah Saat Ini:**
- Layanan tidak konsisten antar periode
- Response time lambat untuk pertanyaan
- Tidak ada self-service untuk user
- Kualitas layanan bergantung pada individu
- Tidak ada feedback mechanism yang sistematis

**Solusi SPEKTRA:**
- Layanan standar dengan SLA yang jelas
- Response time cepat dengan notifikasi otomatis
- Self-service portal untuk berbagai kebutuhan
- Kualitas layanan konsisten dengan sistem
- Feedback dan rating system terintegrasi

**Target Peningkatan:**
- Service quality: 90%
- User satisfaction: 85%
- Self-service adoption: 70%

## 2.5 Analisis Kebutuhan Fungsional dan Non-Fungsional

### 2.5.1 Kebutuhan Fungsional

#### A. Manajemen User (F001-F004)
- **F001**: Registrasi dan login multi-role (Admin, Koordinator, Guru, Siswa, Pembimbing Lapangan)
- **F002**: Manajemen profil user dengan validasi data
- **F003**: Reset password dengan verifikasi email
- **F004**: Aktivasi akun dengan email verification

#### B. Manajemen PKL (F005-F009)
- **F005**: Pendaftaran PKL online dengan upload dokumen
- **F006**: Upload dokumen persyaratan dengan validasi format
- **F007**: Verifikasi dokumen dengan workflow approval
- **F008**: Penempatan PKL dengan sistem matching
- **F009**: Matching siswa dengan perusahaan berdasarkan kriteria

#### C. Monitoring dan Laporan (F010-F014)
- **F010**: Input laporan harian/mingguan dengan template
- **F011**: Tracking progress PKL dengan milestone
- **F012**: Dashboard monitoring dengan visualisasi data
- **F013**: Notifikasi deadline dan reminder otomatis
- **F014**: Generate laporan PKL dalam berbagai format

#### D. Komunikasi (F015-F017)
- **F015**: Sistem messaging internal antar user
- **F016**: Forum diskusi untuk sharing pengalaman
- **F017**: Notifikasi email dan in-app notification

#### E. Evaluasi (F018-F020)
- **F018**: Sistem penilaian online dengan rubrik
- **F019**: Evaluasi multi-perspektif (dosen, pembimbing lapangan)
- **F020**: Generate sertifikat PKL otomatis

### 2.5.2 Kebutuhan Non-Fungsional

#### A. Performance (NF001-NF005)
- **NF001**: Response time < 3 detik untuk operasi normal
- **NF002**: Support 100+ concurrent users
- **NF003**: Uptime 99.5% dengan minimal downtime
- **NF004**: Database query optimization untuk performa optimal
- **NF005**: Caching mechanism untuk data yang sering diakses

#### B. Security (NF006-NF010)
- **NF006**: Enkripsi data sensitif (password, dokumen)
- **NF007**: Role-based access control (RBAC)
- **NF008**: Session management dengan timeout
- **NF009**: Input validation dan sanitization
- **NF010**: Audit logging untuk semua aktivitas

#### C. Usability (NF011-NF015)
- **NF011**: Interface yang user-friendly dan intuitif
- **NF012**: Responsive design untuk berbagai device
- **NF013**: Multilingual support (Bahasa Indonesia)
- **NF014**: Help system dan dokumentasi user
- **NF015**: Accessibility compliance untuk user dengan disabilitas

#### D. Reliability (NF016-NF020)
- **NF016**: Automated backup harian
- **NF017**: Disaster recovery plan dengan RTO < 4 jam
- **NF018**: Error handling yang graceful
- **NF019**: Data validation dan integrity checks
- **NF020**: Monitoring system health dan alerting

#### E. Compatibility (NF021-NF024)
- **NF021**: Compatible dengan PHP 8.0+
- **NF022**: MySQL 8.0+ support
- **NF023**: Modern browser support (Chrome, Firefox, Safari, Edge)
- **NF024**: Mobile responsive untuk smartphone dan tablet
