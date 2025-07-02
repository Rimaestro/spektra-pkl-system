# Activity Diagram - Workflow PKL SPEKTRA System

## Deskripsi

Activity Diagram menunjukkan alur proses bisnis lengkap PKL dalam sistem SPEKTRA, dari pendaftaran hingga completion. Diagram ini menggunakan swimlanes untuk menunjukkan tanggung jawab setiap role dan mencerminkan workflow aktual yang diimplementasikan dalam codebase.

## Diagram Activity - Workflow PKL

```mermaid
flowchart TD
    %% Start
    Start([Mulai Proses PKL])
    
    %% Siswa Activities
    subgraph "Siswa"
        A1[Siswa Login ke Sistem]
        A2[Pilih Perusahaan PKL]
        A3[Isi Form Pendaftaran PKL]
        A4[Upload Dokumen Persyaratan]
        A5[Submit Pendaftaran PKL]
        A6{Dokumen Lengkap?}
        A7[Lengkapi Dokumen]
        A8[Terima Notifikasi Status]
        A9{PKL Disetujui?}
        A10[Perbaiki Dokumen]
        A11[Mulai PKL di Perusahaan]
        A12[Buat Laporan Harian]
        A13[Buat Laporan Mingguan]
        A14[Buat Laporan Bulanan]
        A15[Submit Laporan ke Sistem]
        A16[Terima Feedback Laporan]
        A17[Revisi Laporan jika Perlu]
        A18[Buat Laporan Akhir]
        A19[Submit Laporan Akhir]
        A20[Terima Hasil Evaluasi]
        A21[Selesai PKL]
    end
    
    %% Koordinator Activities
    subgraph "Koordinator PKL"
        B1[Terima Notifikasi Pendaftaran Baru]
        B2[Review Dokumen Pendaftaran]
        B3{Dokumen Valid?}
        B4[Tolak Pendaftaran]
        B5[Kirim Alasan Penolakan]
        B6[Setujui Pendaftaran]
        B7[Assign Guru Pembimbing]
        B8[Kirim Notifikasi Approval]
        B9[Monitor Progress Semua PKL]
        B10[Generate Laporan Sistem]
        B11[Review Laporan Akhir]
        B12{Laporan Akhir OK?}
        B13[Approve Completion PKL]
        B14[Request Revisi Laporan]
    end
    
    %% Guru Pembimbing Activities
    subgraph "Guru Pembimbing"
        C1[Terima Notifikasi Assignment]
        C2[Review Data Siswa]
        C3[Komunikasi dengan Siswa]
        C4[Monitor Progress PKL]
        C5[Review Laporan Berkala]
        C6{Laporan Acceptable?}
        C7[Approve Laporan]
        C8[Reject Laporan dengan Feedback]
        C9[Buat Evaluasi Siswa]
        C10[Input Nilai Technical]
        C11[Input Nilai Attitude]
        C12[Input Nilai Communication]
        C13[Submit Evaluasi]
        C14[Kunjungan ke Perusahaan]
        C15[Dokumentasi Kunjungan]
    end
    
    %% Pembimbing Lapangan Activities
    subgraph "Pembimbing Lapangan"
        D1[Terima Siswa PKL]
        D2[Orientasi Siswa]
        D3[Assign Tugas Harian]
        D4[Monitor Aktivitas Siswa]
        D5[Review Laporan Harian]
        D6{Performance OK?}
        D7[Berikan Feedback Positif]
        D8[Berikan Feedback Perbaikan]
        D9[Buat Evaluasi Lapangan]
        D10[Input Penilaian Kinerja]
        D11[Submit Evaluasi ke Sistem]
        D12[Komunikasi dengan Guru]
    end
    
    %% System Activities
    subgraph "Sistem"
        S1[Validasi Dokumen Otomatis]
        S2[Generate Notifikasi]
        S3[Update Status PKL]
        S4[Calculate Final Score]
        S5[Send Email Notifications]
        S6[Log Activity]
        S7[Backup Data]
    end
    
    %% Main Flow
    Start --> A1
    A1 --> A2
    A2 --> A3
    A3 --> A4
    A4 --> A5
    A5 --> S1
    S1 --> A6
    A6 -->|Tidak| A7
    A7 --> A4
    A6 -->|Ya| S2
    S2 --> B1
    
    %% Koordinator Review Process
    B1 --> B2
    B2 --> B3
    B3 -->|Tidak Valid| B4
    B4 --> B5
    B5 --> S2
    S2 --> A8
    A8 --> A9
    A9 -->|Ditolak| A10
    A10 --> A3
    
    B3 -->|Valid| B6
    B6 --> B7
    B7 --> B8
    B8 --> S3
    S3 --> S2
    S2 --> A8
    A8 --> A9
    A9 -->|Disetujui| C1
    
    %% Guru Assignment
    C1 --> C2
    C2 --> C3
    C3 --> A11
    
    %% PKL Execution Phase
    A11 --> D1
    D1 --> D2
    D2 --> D3
    D3 --> A12
    
    %% Parallel Reporting Process
    A12 --> A15
    A15 --> C5
    C5 --> C6
    C6 -->|Tidak| C8
    C8 --> A16
    A16 --> A17
    A17 --> A15
    
    C6 -->|Ya| C7
    C7 --> S3
    
    %% Parallel Monitoring
    A12 --> D4
    D4 --> D5
    D5 --> D6
    D6 -->|Ya| D7
    D6 -->|Tidak| D8
    D7 --> A13
    D8 --> A16
    
    %% Weekly and Monthly Reports
    A13 --> A15
    A15 --> A14
    A14 --> A15
    
    %% Field Supervisor Evaluation
    D4 --> D9
    D9 --> D10
    D10 --> D11
    D11 --> S4
    
    %% Guru Evaluation
    C4 --> C9
    C9 --> C10
    C10 --> C11
    C11 --> C12
    C12 --> C13
    C13 --> S4
    
    %% Final Report Process
    A14 --> A18
    A18 --> A19
    A19 --> B11
    B11 --> B12
    B12 -->|Tidak| B14
    B14 --> A16
    B12 -->|Ya| B13
    B13 --> S4
    S4 --> A20
    A20 --> A21
    
    %% Parallel Activities
    C3 -.-> D12
    D12 -.-> C3
    C4 -.-> C14
    C14 --> C15
    C15 --> S6
    
    %% System Background Processes
    S3 -.-> S5
    S5 -.-> S6
    S6 -.-> S7
    
    %% Decision Points Styling
    classDef decision fill:#FFE4B5,stroke:#FF8C00,stroke-width:2px
    classDef process fill:#E6F3FF,stroke:#4169E1,stroke-width:2px
    classDef start fill:#90EE90,stroke:#006400,stroke-width:3px
    classDef end fill:#FFB6C1,stroke:#DC143C,stroke-width:3px
    classDef system fill:#F0F0F0,stroke:#696969,stroke-width:2px
    
    class A6,A9,B3,B12,C6,D6 decision
    class A1,A2,A3,A4,A5,A7,A8,A10,A11,A12,A13,A14,A15,A16,A17,A18,A19,A20 process
    class B1,B2,B4,B5,B6,B7,B8,B9,B10,B11,B13,B14 process
    class C1,C2,C3,C4,C5,C7,C8,C9,C10,C11,C12,C13,C14,C15 process
    class D1,D2,D3,D4,D5,D7,D8,D9,D10,D11,D12 process
    class Start start
    class A21 end
    class S1,S2,S3,S4,S5,S6,S7 system
```

## Penjelasan Workflow dan Decision Points

### 1. **Fase Pendaftaran PKL**

#### **Siswa Activities:**
- **Login dan Pilih Perusahaan:** Siswa memilih perusahaan dari daftar yang tersedia
- **Form Pendaftaran:** Mengisi data PKL (tanggal mulai, selesai, deskripsi)
- **Upload Dokumen:** Upload dokumen persyaratan (CV, surat lamaran, dll)
- **Submit Pendaftaran:** Status otomatis menjadi 'pending'

#### **Decision Point 1: Dokumen Lengkap?**
- **Ya:** Lanjut ke review koordinator
- **Tidak:** Siswa harus melengkapi dokumen

#### **Koordinator Review:**
- **Review Dokumen:** Validasi kelengkapan dan kesesuaian
- **Decision Point 2: Dokumen Valid?**
  - **Tidak:** Tolak dengan alasan → Siswa revisi
  - **Ya:** Approve → Assign guru pembimbing

### 2. **Fase Assignment dan Persiapan**

#### **Guru Pembimbing Assignment:**
- **Terima Notifikasi:** Sistem assign guru berdasarkan kapasitas (max 10 siswa)
- **Review Data Siswa:** Memahami background dan tujuan PKL
- **Komunikasi Awal:** Briefing dengan siswa sebelum mulai PKL

#### **Status Transition:** pending → approved → ongoing

### 3. **Fase Eksekusi PKL (Parallel Activities)**

#### **Siswa Activities (Sequential):**
- **Laporan Harian:** Submit setiap hari kerja
- **Laporan Mingguan:** Rangkuman aktivitas mingguan
- **Laporan Bulanan:** Evaluasi progress bulanan
- **Laporan Akhir:** Comprehensive final report

#### **Guru Pembimbing (Parallel):**
- **Monitor Progress:** Tracking melalui dashboard
- **Review Laporan:** Approve/reject dengan feedback
- **Kunjungan Lapangan:** Dokumentasi kunjungan ke perusahaan
- **Evaluasi:** Input nilai technical, attitude, communication

#### **Pembimbing Lapangan (Parallel):**
- **Daily Supervision:** Monitor aktivitas harian siswa
- **Performance Review:** Evaluasi kinerja berkala
- **Feedback:** Berikan masukan untuk improvement
- **Field Evaluation:** Penilaian dari perspektif industri

### 4. **Decision Points dalam Workflow**

#### **Decision Point 3: Laporan Acceptable?**
- **Ya:** Approve laporan → Lanjut ke laporan berikutnya
- **Tidak:** Reject dengan feedback → Siswa revisi

#### **Decision Point 4: Performance OK?**
- **Ya:** Feedback positif → Continue PKL
- **Tidak:** Feedback perbaikan → Monitoring intensif

#### **Decision Point 5: Laporan Akhir OK?**
- **Ya:** Approve completion → Calculate final score
- **Tidak:** Request revisi → Siswa perbaiki

### 5. **Fase Completion dan Evaluasi**

#### **Final Score Calculation:**
- **Guru Evaluation (40%):** Technical, attitude, communication
- **Field Supervisor Evaluation (60%):** Performance, work quality
- **Automatic Calculation:** Sistem hitung nilai akhir

#### **Status Transition:** ongoing → completed

### 6. **Parallel System Processes**

#### **Notification System:**
- **Real-time Notifications:** Status changes, deadlines, feedback
- **Email Notifications:** Backup communication channel
- **Dashboard Updates:** Real-time progress tracking

#### **Background Processes:**
- **Activity Logging:** Audit trail semua aktivitas
- **Data Backup:** Automated backup schedule
- **Performance Monitoring:** System health checks

## Business Rules dan Constraints

### **Status Transition Rules:**
1. **pending → approved/rejected:** Hanya koordinator/admin
2. **approved → ongoing:** Otomatis saat tanggal mulai
3. **ongoing → completed:** Setelah semua evaluasi selesai
4. **rejected → pending:** Siswa bisa resubmit

### **Permission Rules:**
1. **Siswa:** Hanya edit PKL sendiri yang status 'pending'
2. **Guru:** Edit PKL yang dibimbing (limited fields)
3. **Koordinator/Admin:** Full access semua PKL
4. **Pembimbing Lapangan:** Edit notes dan feedback

### **Validation Rules:**
1. **Dokumen Upload:** Format dan ukuran file
2. **Tanggal PKL:** End date > start date
3. **Supervisor Capacity:** Max 10 siswa per guru
4. **Company Slots:** Sesuai max_students perusahaan

### **Notification Triggers:**
1. **Status Changes:** PKL approval, rejection, completion
2. **Report Submission:** Notifikasi ke supervisor
3. **Deadline Reminders:** Automated reminders
4. **Evaluation Completion:** Notifikasi hasil evaluasi

## Parallel Activities dan Synchronization

### **Concurrent Processes:**
1. **Reporting dan Monitoring:** Berjalan bersamaan
2. **Guru dan Field Supervisor Evaluation:** Independent
3. **Communication:** Antar stakeholder kapan saja
4. **System Notifications:** Background process

### **Synchronization Points:**
1. **Final Score Calculation:** Tunggu semua evaluasi
2. **PKL Completion:** Tunggu laporan akhir approved
3. **Status Updates:** Atomic transactions
4. **Notification Delivery:** Queue-based processing

Activity Diagram ini mencerminkan kompleksitas workflow PKL yang sesungguhnya, dengan multiple stakeholders, parallel processes, dan decision points yang mempengaruhi alur proses secara keseluruhan.
