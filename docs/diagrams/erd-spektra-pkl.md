# Entity Relationship Diagram (ERD) - SPEKTRA PKL System

## Diagram ERD Lengkap

```mermaid
erDiagram
    USERS {
        bigint id PK "AUTO_INCREMENT"
        string name "NOT NULL"
        string email "UNIQUE, NOT NULL"
        timestamp email_verified_at "NULLABLE"
        string password "NOT NULL"
        enum role "admin, koordinator, guru, siswa, pembimbing_lapangan"
        string nis "UNIQUE, NULLABLE - untuk siswa"
        string nip "UNIQUE, NULLABLE - untuk guru"
        string phone "NULLABLE"
        text address "NULLABLE"
        string avatar "NULLABLE"
        enum status "active, inactive, pending"
        timestamp last_login_at "NULLABLE"
        string last_login_ip "NULLABLE"
        int login_attempts "DEFAULT 0"
        timestamp locked_until "NULLABLE"
        timestamp password_changed_at "NULLABLE"
        boolean force_password_change "DEFAULT false"
        timestamp created_at
        timestamp updated_at
    }

    COMPANIES {
        bigint id PK "AUTO_INCREMENT"
        string name "NOT NULL"
        text address "NOT NULL"
        string contact_person "NOT NULL"
        string phone "NOT NULL"
        string email "NOT NULL"
        text description "NULLABLE"
        string website "NULLABLE"
        enum status "active, inactive"
        int max_students "DEFAULT 5"
        timestamp created_at
        timestamp updated_at
    }

    PKLS {
        bigint id PK "AUTO_INCREMENT"
        bigint user_id FK "NOT NULL - siswa"
        bigint company_id FK "NOT NULL"
        bigint supervisor_id FK "NULLABLE - guru pembimbing"
        bigint field_supervisor_id FK "NULLABLE - pembimbing lapangan"
        date start_date "NOT NULL"
        date end_date "NOT NULL"
        enum status "pending, approved, rejected, ongoing, completed"
        text description "NULLABLE"
        json documents "NULLABLE"
        text rejection_reason "NULLABLE"
        decimal final_score "5,2 NULLABLE"
        date defense_date "NULLABLE"
        timestamp created_at
        timestamp updated_at
    }

    REPORTS {
        bigint id PK "AUTO_INCREMENT"
        bigint pkl_id FK "NOT NULL"
        bigint user_id FK "NOT NULL - pembuat laporan"
        enum report_type "daily, weekly, monthly, final"
        string title "NOT NULL"
        text content "NOT NULL"
        string file_path "NULLABLE"
        json attachments "NULLABLE"
        date report_date "NOT NULL"
        enum status "draft, submitted, reviewed, approved, rejected"
        text feedback "NULLABLE - dari pembimbing"
        timestamp created_at
        timestamp updated_at
    }

    EVALUATIONS {
        bigint id PK "AUTO_INCREMENT"
        bigint pkl_id FK "NOT NULL"
        bigint evaluator_id FK "NOT NULL - penilai"
        enum evaluator_type "supervisor, field_supervisor"
        decimal technical_score "5,2 NULLABLE"
        decimal attitude_score "5,2 NULLABLE"
        decimal communication_score "5,2 NULLABLE"
        decimal final_score "5,2 NULLABLE"
        text comments "NULLABLE"
        text suggestions "NULLABLE"
        enum status "draft, submitted, approved"
        timestamp created_at
        timestamp updated_at
    }

    MESSAGES {
        bigint id PK "AUTO_INCREMENT"
        bigint sender_id FK "NOT NULL"
        bigint receiver_id FK "NOT NULL"
        string subject "NOT NULL"
        text message "NOT NULL"
        json attachments "NULLABLE"
        timestamp read_at "NULLABLE"
        enum priority "low, normal, high"
        timestamp created_at
        timestamp updated_at
    }

    NOTIFICATIONS {
        bigint id PK "AUTO_INCREMENT"
        string type "NOT NULL"
        string notifiable_type "NOT NULL - polymorphic"
        bigint notifiable_id "NOT NULL - polymorphic"
        text data "NOT NULL - JSON data"
        timestamp read_at "NULLABLE"
        timestamp created_at
        timestamp updated_at
    }

    PASSWORD_RESET_TOKENS {
        string email PK
        string token "INDEXED"
        timestamp created_at "NULLABLE"
        timestamp expires_at "NULLABLE"
        boolean used "DEFAULT false"
        string ip_address "NULLABLE"
        text user_agent "NULLABLE"
    }

    SESSIONS {
        string id PK
        bigint user_id FK "NULLABLE, INDEXED"
        string ip_address "NULLABLE"
        text user_agent "NULLABLE"
        longtext payload "NOT NULL"
        int last_activity "INDEXED"
    }

    LOGIN_ATTEMPTS {
        bigint id PK "AUTO_INCREMENT"
        string email "INDEXED"
        string ip_address "45 chars"
        string user_agent "NULLABLE"
        boolean successful "DEFAULT false"
        string failure_reason "NULLABLE"
        timestamp attempted_at
        timestamp created_at
        timestamp updated_at
    }

    USER_SESSIONS {
        bigint id PK "AUTO_INCREMENT"
        bigint user_id FK "NOT NULL"
        string session_id "NOT NULL"
        string ip_address "NULLABLE"
        text user_agent "NULLABLE"
        timestamp login_at "NOT NULL"
        timestamp last_activity "NOT NULL"
        boolean is_active "DEFAULT true"
        timestamp created_at
        timestamp updated_at
    }

    %% Relationships
    USERS ||--o{ PKLS : "siswa memiliki"
    USERS ||--o{ PKLS : "guru membimbing (supervisor_id)"
    USERS ||--o{ PKLS : "pembimbing lapangan (field_supervisor_id)"
    COMPANIES ||--o{ PKLS : "menerima siswa"
    
    PKLS ||--o{ REPORTS : "memiliki laporan"
    USERS ||--o{ REPORTS : "membuat laporan"
    
    PKLS ||--o{ EVALUATIONS : "dievaluasi"
    USERS ||--o{ EVALUATIONS : "mengevaluasi"
    
    USERS ||--o{ MESSAGES : "mengirim (sender)"
    USERS ||--o{ MESSAGES : "menerima (receiver)"
    
    USERS ||--o{ NOTIFICATIONS : "menerima notifikasi (polymorphic)"
    
    USERS ||--o{ SESSIONS : "memiliki sesi"
    USERS ||--o{ USER_SESSIONS : "tracking sesi"
```

## Penjelasan Entitas dan Relasi

### 1. **USERS** (Entitas Utama)
**Deskripsi:** Menyimpan semua pengguna sistem dengan multi-role
**Atribut Kunci:**
- `role`: Menentukan jenis pengguna (admin, koordinator, guru, siswa, pembimbing_lapangan)
- `nis`: Khusus untuk siswa (unique)
- `nip`: Khusus untuk guru (unique)
- `status`: Status aktif/tidak aktif pengguna
- Security fields: `login_attempts`, `locked_until`, `password_changed_at`

### 2. **COMPANIES** (Perusahaan)
**Deskripsi:** Data perusahaan tempat siswa melakukan PKL
**Atribut Kunci:**
- `max_students`: Batas maksimal siswa yang bisa diterima
- `status`: Status aktif/tidak aktif perusahaan
- `contact_person`: PIC di perusahaan

### 3. **PKLS** (Praktek Kerja Lapangan)
**Deskripsi:** Data utama PKL dengan workflow status yang kompleks
**Atribut Kunci:**
- `status`: Workflow (pending → approved → ongoing → completed/rejected)
- `documents`: JSON untuk menyimpan dokumen persyaratan
- `final_score`: Nilai akhir PKL
- Multiple foreign keys untuk relasi supervisor

### 4. **REPORTS** (Laporan PKL)
**Deskripsi:** Laporan berkala yang dibuat siswa
**Atribut Kunci:**
- `report_type`: Jenis laporan (daily, weekly, monthly, final)
- `status`: Status review laporan
- `attachments`: JSON untuk multiple file attachments
- `feedback`: Feedback dari pembimbing

### 5. **EVALUATIONS** (Evaluasi PKL)
**Deskripsi:** Penilaian PKL dari supervisor dan pembimbing lapangan
**Atribut Kunci:**
- `evaluator_type`: Jenis penilai (supervisor/field_supervisor)
- Multiple scoring: `technical_score`, `attitude_score`, `communication_score`
- `final_score`: Nilai akhir hasil kalkulasi

### 6. **MESSAGES** (Sistem Komunikasi)
**Deskripsi:** Komunikasi internal antar pengguna sistem
**Atribut Kunci:**
- `priority`: Tingkat prioritas pesan
- `read_at`: Timestamp kapan dibaca
- `attachments`: JSON untuk file attachments

### 7. **NOTIFICATIONS** (Sistem Notifikasi)
**Deskripsi:** Notifikasi sistem menggunakan Laravel Notification (polymorphic)
**Atribut Kunci:**
- Polymorphic relationship dengan `notifiable_type` dan `notifiable_id`
- `data`: JSON data notifikasi

## Relasi Utama (Cardinality)

### **One-to-Many Relationships:**
1. **USERS → PKLS** (1:M)
   - Satu siswa bisa memiliki beberapa PKL (historis)
   - Satu guru bisa membimbing banyak siswa
   - Satu pembimbing lapangan bisa membimbing banyak siswa

2. **COMPANIES → PKLS** (1:M)
   - Satu perusahaan bisa menerima banyak siswa PKL

3. **PKLS → REPORTS** (1:M)
   - Satu PKL memiliki banyak laporan (harian, mingguan, dll)

4. **PKLS → EVALUATIONS** (1:M)
   - Satu PKL bisa dievaluasi berkali-kali oleh supervisor berbeda

5. **USERS → MESSAGES** (1:M untuk sender dan receiver)
   - Satu user bisa mengirim/menerima banyak pesan

### **Many-to-Many Relationships:**
- **USERS ↔ MESSAGES**: Melalui sender_id dan receiver_id (komunikasi antar user)

### **Polymorphic Relationships:**
- **NOTIFICATIONS**: Bisa terkait dengan berbagai entitas (User, PKL, Report, dll)

## Constraints dan Indexes

### **Foreign Key Constraints:**
- `ON DELETE CASCADE`: Untuk relasi yang dependent (reports, evaluations)
- `ON DELETE SET NULL`: Untuk relasi yang optional (supervisor assignments)

### **Unique Constraints:**
- `users.email`: Unique untuk login
- `users.nis`: Unique untuk siswa
- `users.nip`: Unique untuk guru

### **Indexes untuk Performance:**
- `email` pada login_attempts
- `ip_address` pada login_attempts
- `user_id` pada sessions
- `last_activity` pada sessions

## Catatan Implementasi

1. **Security**: Sistem memiliki tracking login attempts dan session management
2. **Audit Trail**: Semua tabel memiliki `created_at` dan `updated_at`
3. **Soft Deletes**: Tidak digunakan, menggunakan status field untuk deaktivasi
4. **JSON Fields**: Digunakan untuk data yang fleksibel (documents, attachments)
5. **Enum Fields**: Digunakan untuk status dan role yang terbatas dan terdefinisi

ERD ini mencerminkan sistem PKL yang komprehensif dengan multi-role, workflow yang kompleks, dan fitur komunikasi terintegrasi.
