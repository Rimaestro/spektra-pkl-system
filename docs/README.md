# Dokumentasi SPEKTRA PKL System

Selamat datang di dokumentasi lengkap sistem SPEKTRA PKL (Sistem Praktek Kerja Lapangan) yang dikembangkan untuk mengelola proses PKL secara digital dan terintegrasi.

## Struktur Dokumentasi

### 📋 Laporan Utama
- [**01-pendahuluan.md**](01-pendahuluan.md) - Latar belakang, tujuan, dan ruang lingkup sistem
- [**02-analisis-kebutuhan.md**](02-analisis-kebutuhan.md) - Analisis lengkap kebutuhan sistem
- [**03-rancangan-sistem.md**](03-rancangan-sistem.md) - Desain dan arsitektur sistem
- [**04-pengkodean.md**](04-pengkodean.md) - Implementasi dan cuplikan kode
- [**05-pengujian.md**](05-pengujian.md) - Hasil testing dan evaluasi
- [**06-penutup.md**](06-penutup.md) - Kesimpulan dan saran pengembangan

### 📁 Lampiran
- [**07-lampiran.md**](07-lampiran.md) - Biografi tim dan dokumentasi tambahan

### 🖼️ Assets
- **diagrams/** - Folder untuk menyimpan diagram UML, ERD, dan flowchart
- **screenshots/** - Folder untuk screenshot antarmuka sistem
- **mockups/** - Folder untuk wireframe dan mockup desain

## Teknologi yang Digunakan

### Backend
- **Framework**: Laravel 12
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum + Custom Authentication
- **File Storage**: Laravel Storage

### Frontend
- **UI Framework**: Bootstrap 5
- **JavaScript**: jQuery/Alpine.js
- **Charts**: Chart.js
- **Icons**: Font Awesome

### Tools & Services
- **Version Control**: Git
- **Package Manager**: Composer, NPM
- **Email**: SMTP Gmail
- **Testing**: PHPUnit

## Fitur Utama Sistem

1. **Multi-Role Authentication** - Admin, Koordinator, Dosen, Mahasiswa, Pembimbing Lapangan
2. **Manajemen PKL** - Pendaftaran, penempatan, dan monitoring PKL
3. **Dashboard Monitoring** - Tracking progress dan laporan real-time
4. **Sistem Pelaporan** - Laporan digital dan evaluasi online
5. **Komunikasi Terintegrasi** - Messaging dan notifikasi sistem

## Cara Menggunakan Dokumentasi

1. Mulai dengan membaca **Pendahuluan** untuk memahami konteks sistem
2. Lanjutkan ke **Analisis Kebutuhan** untuk memahami requirement
3. Pelajari **Rancangan Sistem** untuk memahami arsitektur
4. Lihat **Pengkodean** untuk detail implementasi
5. Review **Pengujian** untuk memahami kualitas sistem
6. Baca **Penutup** untuk kesimpulan dan pengembangan lanjutan

## 📊 Diagram Sistem

Dokumentasi visual sistem tersedia dalam folder terpisah:

### **[📋 Diagram SPEKTRA PKL](diagrams/README.md)**

**Diagram Tersedia:**
- ✅ **[Entity Relationship Diagram (ERD)](diagrams/erd-spektra-pkl.md)** - Struktur database lengkap
- ✅ **[Use Case Diagram](diagrams/use-case-diagram.md)** - Interaksi 5 aktor dengan sistem
- ✅ **[Class Diagram](diagrams/class-diagram.md)** - Struktur model Laravel dengan relationships
- ✅ **[Activity Diagram](diagrams/activity-workflow-pkl.md)** - Workflow PKL dengan swimlanes
- 🔄 **Sequence Diagram** *(Planned)* - Interaksi komponen
- 🔄 **Architecture Diagram** *(Planned)* - Arsitektur sistem Laravel

**Format:** Semua diagram menggunakan Mermaid untuk integrasi dengan dokumentasi Markdown dan version control.

---

**Dikembangkan oleh Tim SPEKTRA PKL System**
*Dokumentasi ini dibuat untuk memenuhi laporan proyek pengembangan sistem PKL terintegrasi*
