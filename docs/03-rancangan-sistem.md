# BAB III - RANCANGAN SISTEM

## 3.1 Pemilihan Metode Perancangan

### 3.1.1 Metodologi Pengembangan

Sistem SPEKTRA PKL dikembangkan menggunakan **metodologi Agile** dengan pendekatan **Scrum** yang memungkinkan pengembangan iteratif dan adaptif. Pemilihan metodologi ini didasarkan pada:

1. **Fleksibilitas Requirements**: Kebutuhan sistem PKL dapat berubah seiring dengan feedback stakeholder
2. **Iterative Development**: Memungkinkan pengembangan bertahap dengan validasi berkelanjutan
3. **Stakeholder Involvement**: Melibatkan pengguna aktif dalam proses pengembangan
4. **Risk Mitigation**: Mengurangi risiko dengan delivery incremental

### 3.1.2 Pemilihan UML sebagai Modeling Language

Untuk perancangan sistem, dipilih **Unified Modeling Language (UML)** dengan justifikasi:

**Keunggulan UML:**
- Standar industri yang widely accepted
- Mendukung object-oriented design yang sesuai dengan Laravel framework
- Menyediakan berbagai diagram untuk berbagai aspek sistem
- Mudah dipahami oleh stakeholder teknis dan non-teknis
- Terintegrasi dengan development tools modern

**Diagram UML yang Digunakan:**
1. **Use Case Diagram** - Menggambarkan interaksi user dengan sistem
2. **Class Diagram** - Struktur kelas dan relasi dalam sistem
3. **Sequence Diagram** - Alur komunikasi antar objek
4. **Activity Diagram** - Alur proses bisnis sistem

### 3.1.3 Arsitektur Sistem

Sistem SPEKTRA PKL menggunakan arsitektur **Model-View-Controller (MVC)** yang diimplementasikan dalam Laravel framework:

- **Model**: Representasi data dan business logic (Eloquent ORM)
- **View**: Presentation layer dengan Blade templating
- **Controller**: Logic layer yang menghubungkan Model dan View

## 3.2 Desain Diagram UML

### 3.2.1 Use Case Diagram

Use Case Diagram menggambarkan interaksi antara aktor (pengguna) dengan sistem SPEKTRA PKL.

#### Aktor Sistem:
1. **Admin** - Administrator sistem
2. **Koordinator** - Koordinator PKL
3. **Dosen** - Dosen pembimbing
4. **Siswa** - Siswa yang melakukan PKL
5. **Pembimbing Lapangan** - Supervisor di perusahaan

#### Use Cases Utama:

**UC001: Manajemen Authentication**
- Login ke sistem
- Logout dari sistem
- Reset password
- Verifikasi email

**UC002: Manajemen Profil**
- View profil
- Edit profil
- Upload avatar
- Change password

**UC003: Manajemen PKL (Mahasiswa)**
- Daftar PKL
- Upload dokumen
- View status PKL
- Submit laporan

**UC004: Monitoring PKL (Dosen)**
- View mahasiswa bimbingan
- Monitor progress PKL
- Input penilaian
- Komunikasi dengan mahasiswa

**UC005: Administrasi PKL (Koordinator)**
- Approve/reject pendaftaran
- Assign pembimbing
- Generate laporan
- Manage pengumuman

**UC006: Evaluasi PKL (Pembimbing Lapangan)**
- View profil mahasiswa
- Input evaluasi
- Upload dokumentasi
- Komunikasi dengan dosen

### 3.2.2 Class Diagram

Class Diagram menunjukkan struktur kelas dalam sistem dan relasi antar kelas.

#### Core Classes:

**User Class**
```
User
- id: int
- name: string
- email: string
- password: string
- role: enum
- nim: string
- nip: string
- phone: string
- address: text
- avatar: string
- status: enum
- last_login_at: datetime
- login_attempts: int
- locked_until: datetime
+ authenticate()
+ hasRole()
+ isActive()
+ canLogin()
```

**PKL Class**
```
PKL
- id: int
- user_id: int
- company_id: int
- supervisor_id: int
- field_supervisor_id: int
- start_date: date
- end_date: date
- status: enum
- description: text
- documents: json
- final_score: decimal
+ isPending()
+ isApproved()
+ isCompleted()
+ calculateDuration()
```

**Company Class**
```
Company
- id: int
- name: string
- address: text
- contact_person: string
- phone: string
- email: string
- description: text
- website: string
- status: enum
- max_students: int
+ isActive()
+ hasAvailableSlots()
+ getActiveStudents()
```

**Report Class**
```
Report
- id: int
- pkl_id: int
- report_type: enum
- title: string
- content: text
- file_path: string
- status: enum
+ isApproved()
+ getFileUrl()
+ generatePDF()
```

#### Relationships:
- User (1) -> (0..*) PKL
- Company (1) -> (0..*) PKL
- PKL (1) -> (0..*) Report
- PKL (1) -> (0..*) Evaluation

### 3.2.3 Sequence Diagram

#### Sequence Diagram: Proses Login
```
User -> LoginController: submitLogin(email, password)
LoginController -> AuthService: authenticate(credentials)
AuthService -> User: findByEmail(email)
User -> AuthService: user data
AuthService -> AuthService: validatePassword()
AuthService -> LoginController: authentication result
LoginController -> SessionService: createSession(user)
SessionService -> Database: store session
LoginController -> User: redirect to dashboard
```

#### Sequence Diagram: Pendaftaran PKL
```
Mahasiswa -> PKLController: submitRegistration(data)
PKLController -> PKLService: validateRegistration(data)
PKLService -> PKL: create(data)
PKL -> Database: save()
PKLService -> NotificationService: sendNotification()
NotificationService -> Koordinator: email notification
PKLController -> Mahasiswa: success response
```

### 3.2.4 Activity Diagram

#### Activity Diagram: Proses Approval PKL

```
[Start] -> [Mahasiswa Submit PKL]
[Mahasiswa Submit PKL] -> [Validasi Dokumen]
[Validasi Dokumen] -> {Dokumen Lengkap?}
{Dokumen Lengkap?} --No--> [Notifikasi Dokumen Kurang] -> [End]
{Dokumen Lengkap?} --Yes--> [Review Koordinator]
[Review Koordinator] -> {Approve?}
{Approve?} --No--> [Reject dengan Alasan] -> [Notifikasi Mahasiswa] -> [End]
{Approve?} --Yes--> [Assign Pembimbing]
[Assign Pembimbing] -> [Update Status PKL]
[Update Status PKL] -> [Notifikasi Semua Pihak]
[Notifikasi Semua Pihak] -> [End]
```

## 3.3 Perancangan Basis Data

### 3.3.1 Entity Relationship Diagram (ERD)

#### Entitas Utama:

**Users**
- Primary Key: id
- Attributes: name, email, password, role, nim, nip, phone, address, avatar, status
- Relationships: 1:M dengan PKL, Reports, Evaluations

**Companies**
- Primary Key: id
- Attributes: name, address, contact_person, phone, email, description, website, status, max_students
- Relationships: 1:M dengan PKL

**PKLs**
- Primary Key: id
- Foreign Keys: user_id, company_id, supervisor_id, field_supervisor_id
- Attributes: start_date, end_date, status, description, documents, final_score, defense_date
- Relationships: M:1 dengan Users dan Companies, 1:M dengan Reports dan Evaluations

**Reports**
- Primary Key: id
- Foreign Key: pkl_id, user_id
- Attributes: report_type, title, content, file_path, status, submitted_at
- Relationships: M:1 dengan PKL

**Evaluations**
- Primary Key: id
- Foreign Keys: pkl_id, evaluator_id
- Attributes: evaluation_type, score, comments, criteria, submitted_at
- Relationships: M:1 dengan PKL dan Users

#### Relasi Antar Entitas:

1. **Users - PKL**: One-to-Many
   - Satu user (mahasiswa) dapat memiliki beberapa PKL
   - Satu PKL hanya dimiliki oleh satu mahasiswa

2. **Companies - PKL**: One-to-Many
   - Satu perusahaan dapat menerima beberapa mahasiswa PKL
   - Satu PKL hanya di satu perusahaan

3. **PKL - Reports**: One-to-Many
   - Satu PKL dapat memiliki beberapa laporan
   - Satu laporan hanya untuk satu PKL

4. **PKL - Evaluations**: One-to-Many
   - Satu PKL dapat memiliki beberapa evaluasi
   - Satu evaluasi hanya untuk satu PKL

### 3.3.2 Normalisasi Database

Database dirancang mengikuti **Third Normal Form (3NF)** untuk:
- Menghindari redundansi data
- Memastikan integritas data
- Optimalisasi storage
- Kemudahan maintenance

### 3.3.3 Indexing Strategy

**Primary Indexes:**
- id pada semua tabel sebagai primary key

**Secondary Indexes:**
- email pada tabel users (unique)
- nim, nip pada tabel users (unique)
- status pada tabel users dan companies
- pkl_id pada tabel reports dan evaluations
- created_at pada semua tabel untuk sorting

**Composite Indexes:**
- (role, status) pada tabel users
- (company_id, status) pada tabel pkls
- (pkl_id, report_type) pada tabel reports

### 3.3.4 Entity Relationship Diagram (ERD)

ERD lengkap sistem SPEKTRA PKL dapat dilihat pada file terpisah untuk kemudahan maintenance dan rendering:

📊 **[ERD SPEKTRA PKL System](diagrams/erd-spektra-pkl.md)**

ERD mencakup:
- **12 Entitas Utama**: Users, Companies, PKLs, Reports, Evaluations, Messages, Notifications, dll
- **Relasi Lengkap**: One-to-Many, Many-to-Many, dan Polymorphic relationships
- **Atribut Detail**: Semua kolom dengan tipe data dan constraints
- **Foreign Keys**: Dengan ON DELETE CASCADE/SET NULL sesuai business logic
- **Indexes**: Untuk optimasi performance query

**Entitas Inti:**
- `USERS` (5 roles): admin, koordinator, guru, siswa, pembimbing_lapangan
- `PKLS` (5 status): pending → approved → ongoing → completed/rejected
- `REPORTS` (4 types): daily, weekly, monthly, final
- `EVALUATIONS`: Multi-aspect scoring (technical, attitude, communication)
- `MESSAGES`: Internal communication system
- `NOTIFICATIONS`: Laravel notification system (polymorphic)

**Relasi Utama:**
- User 1:M PKL (siswa memiliki PKL, guru membimbing)
- Company 1:M PKL (perusahaan menerima siswa)
- PKL 1:M Report (PKL memiliki banyak laporan)
- PKL 1:M Evaluation (PKL dievaluasi berkali-kali)
- User M:M Message (komunikasi antar user)

## 3.4 Desain Antarmuka (UI/UX Design)

### 3.4.1 Prinsip Desain

#### A. User-Centered Design
- **Simplicity**: Interface yang sederhana dan mudah dipahami
- **Consistency**: Konsistensi dalam layout, warna, dan navigasi
- **Accessibility**: Dapat diakses oleh pengguna dengan berbagai kemampuan
- **Responsiveness**: Optimal di berbagai ukuran layar

#### B. Design System
- **Color Palette**:
  - Primary: #007bff (Blue) - untuk aksi utama
  - Secondary: #6c757d (Gray) - untuk aksi sekunder
  - Success: #28a745 (Green) - untuk status berhasil
  - Warning: #ffc107 (Yellow) - untuk peringatan
  - Danger: #dc3545 (Red) - untuk error/bahaya

- **Typography**:
  - Font Family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif
  - Heading: Bold, 1.5-2.5rem
  - Body: Regular, 1rem
  - Small: 0.875rem

- **Spacing**:
  - Base unit: 8px
  - Small: 8px, Medium: 16px, Large: 24px, XL: 32px

### 3.4.2 Layout Structure

#### A. Header Navigation
```
[SPEKTRA Logo] [Dashboard] [PKL] [Reports] [Messages] [Profile] [Logout]
```

#### B. Sidebar Navigation (Role-based)
**Admin/Koordinator:**
- Dashboard
- Manajemen User
- Manajemen PKL
- Manajemen Perusahaan
- Laporan & Analitik
- Pengaturan Sistem

**Dosen:**
- Dashboard
- Mahasiswa Bimbingan
- Monitoring PKL
- Evaluasi & Penilaian
- Komunikasi

**Mahasiswa:**
- Dashboard
- Profil Saya
- PKL Saya
- Laporan PKL
- Komunikasi

**Pembimbing Lapangan:**
- Dashboard
- Mahasiswa PKL
- Evaluasi
- Komunikasi

#### C. Main Content Area
- Breadcrumb navigation
- Page title dan description
- Content area dengan grid system
- Action buttons dan forms

#### D. Footer
- Copyright information
- Contact information
- Help links

### 3.4.3 Wireframe Halaman Utama

#### A. Dashboard Mahasiswa
```
+----------------------------------------------------------+
| Header Navigation                                         |
+----------------------------------------------------------+
| Sidebar |  Main Content Area                            |
|         |  +------------------------------------------+ |
|         |  | Welcome, [Nama Mahasiswa]                | |
|         |  +------------------------------------------+ |
|         |  | PKL Status Card | Quick Actions Card    | |
|         |  +------------------------------------------+ |
|         |  | Recent Activities | Upcoming Deadlines  | |
|         |  +------------------------------------------+ |
|         |  | Progress Chart    | Messages Preview    | |
|         |  +------------------------------------------+ |
+----------------------------------------------------------+
| Footer                                                   |
+----------------------------------------------------------+
```

#### B. Form Pendaftaran PKL
```
+----------------------------------------------------------+
| Header Navigation                                         |
+----------------------------------------------------------+
| Breadcrumb: Dashboard > PKL > Pendaftaran               |
+----------------------------------------------------------+
| Form Pendaftaran PKL                                    |
| +------------------------------------------------------+ |
| | Personal Information                                 | |
| | [Name] [NIM] [Email] [Phone]                        | |
| +------------------------------------------------------+ |
| | PKL Details                                          | |
| | [Start Date] [End Date] [Company Preference]        | |
| +------------------------------------------------------+ |
| | Document Upload                                      | |
| | [CV] [Surat Keterangan Sehat] [Proposal]           | |
| +------------------------------------------------------+ |
| | [Cancel] [Save Draft] [Submit]                      | |
| +------------------------------------------------------+ |
+----------------------------------------------------------+
```

### 3.4.4 Mockup Interface

#### A. Dashboard Cards Design
```css
.dashboard-card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  padding: 24px;
  margin-bottom: 24px;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 16px;
  font-size: 0.875rem;
  font-weight: 500;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-approved { background: #d4edda; color: #155724; }
.status-rejected { background: #f8d7da; color: #721c24; }
```

#### B. Form Design Pattern
```css
.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #495057;
}

.form-control {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 1rem;
}

.form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}
```

#### C. Button Design System
```css
.btn {
  padding: 12px 24px;
  border-radius: 4px;
  font-weight: 500;
  text-decoration: none;
  display: inline-block;
  cursor: pointer;
  border: none;
}

.btn-primary {
  background: #007bff;
  color: white;
}

.btn-secondary {
  background: #6c757d;
  color: white;
}

.btn-success {
  background: #28a745;
  color: white;
}
```

### 3.4.5 Responsive Design

#### A. Breakpoints
- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

#### B. Mobile-First Approach
```css
/* Mobile First */
.container {
  padding: 16px;
}

.sidebar {
  display: none; /* Hidden on mobile */
}

/* Tablet */
@media (min-width: 768px) {
  .container {
    padding: 24px;
  }

  .sidebar {
    display: block;
    width: 250px;
  }
}

/* Desktop */
@media (min-width: 1024px) {
  .container {
    max-width: 1200px;
    margin: 0 auto;
  }
}
```

#### C. Navigation Adaptasi
- **Mobile**: Hamburger menu dengan slide-out navigation
- **Tablet**: Collapsible sidebar
- **Desktop**: Full sidebar navigation

### 3.4.6 Accessibility Features

#### A. WCAG 2.1 Compliance
- **Color Contrast**: Minimum 4.5:1 ratio
- **Keyboard Navigation**: Full keyboard accessibility
- **Screen Reader**: Proper ARIA labels dan semantic HTML
- **Focus Indicators**: Visible focus states

#### B. Implementation
```html
<!-- Semantic HTML -->
<nav aria-label="Main navigation">
  <ul role="menubar">
    <li role="none">
      <a href="/dashboard" role="menuitem" aria-current="page">
        Dashboard
      </a>
    </li>
  </ul>
</nav>

<!-- Form Accessibility -->
<label for="email">Email Address</label>
<input
  type="email"
  id="email"
  name="email"
  required
  aria-describedby="email-help"
>
<div id="email-help">Enter your institutional email</div>
```

### 3.4.7 Performance Optimization

#### A. Frontend Optimization
- **CSS/JS Minification**: Reduce file sizes
- **Image Optimization**: WebP format dengan fallback
- **Lazy Loading**: Load images on demand
- **CDN**: Content Delivery Network untuk assets

#### B. Loading States
```css
.loading-spinner {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #007bff;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
```
