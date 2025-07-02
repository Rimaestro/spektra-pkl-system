# Class Diagram - SPEKTRA PKL System

## Deskripsi

Class Diagram menunjukkan struktur model Laravel dalam sistem SPEKTRA PKL, termasuk atribut, methods, dan relationships antar class. Diagram ini menggunakan terminologi SMK yang benar (guru, siswa, NIS) dan mencerminkan implementasi aktual dalam codebase.

## Diagram Class

```mermaid
classDiagram
    %% Base Laravel Classes
    class Authenticatable {
        <<abstract>>
    }
    
    class Model {
        <<abstract>>
        +timestamps
        +created_at: datetime
        +updated_at: datetime
        +save(): bool
        +delete(): bool
        +fresh(): Model
    }
    
    %% Interfaces
    class MustVerifyEmail {
        <<interface>>
        +hasVerifiedEmail(): bool
        +markEmailAsVerified(): bool
        +sendEmailVerificationNotification(): void
    }
    
    %% Traits
    class HasFactory {
        <<trait>>
        +factory(): Factory
    }
    
    class Notifiable {
        <<trait>>
        +notify(): void
        +notifications(): MorphMany
    }
    
    class HasApiTokens {
        <<trait>>
        +createToken(): NewAccessToken
        +tokens(): MorphMany
    }
    
    %% Main Models
    class User {
        -id: int
        -name: string
        -email: string
        -password: string
        -role: enum
        -nis: string
        -nip: string
        -phone: string
        -address: text
        -avatar: string
        -status: enum
        -last_login_at: datetime
        -last_login_ip: string
        -login_attempts: int
        -locked_until: datetime
        -password_changed_at: datetime
        -force_password_change: bool
        -email_verified_at: datetime
        -remember_token: string
        
        +getFullNameWithRoleAttribute(): string
        +getRoleLabel(): string
        +getStatusLabel(): string
        +hasRole(role: string): bool
        +hasAnyRole(roles: array): bool
        +isActive(): bool
        +canLogin(): bool
        +isAccountLocked(): bool
        +incrementLoginAttempts(): void
        +clearLoginAttempts(): void
        +lockAccount(): void
        +unlockAccount(): void
        +updateLastLogin(): void
        +forcePasswordChange(): void
        +scopeByRole(query, role): Builder
        +scopeActive(query): Builder
        +scopeSiswa(query): Builder
        +scopeGuru(query): Builder
        +scopePembimbingLapangan(query): Builder
        
        +pkls(): HasMany
        +supervisedPkls(): HasMany
        +fieldSupervisedPkls(): HasMany
        +reports(): HasMany
        +evaluations(): HasMany
        +sentMessages(): HasMany
        +receivedMessages(): HasMany
        +companies(): BelongsToMany
        +supervisedReports(): HasManyThrough
        +tokens(): MorphMany
        +sessions(): HasMany
        +activeSessions(): HasMany
    }
    
    class Company {
        -id: int
        -name: string
        -address: text
        -contact_person: string
        -phone: string
        -email: string
        -description: text
        -website: string
        -status: enum
        -max_students: int
        
        +getStatusLabel(): string
        +getStatusColorClass(): string
        +getCompanyType(): string
        +isActive(): bool
        +getAvailableSlots(): int
        +hasAvailableSlots(): bool
        +scopeActive(query): Builder
        
        +pkls(): HasMany
        +ongoingPkls(): HasMany
        +completedPkls(): HasMany
        +supervisors(): BelongsToMany
    }
    
    class PKL {
        -id: int
        -user_id: int
        -company_id: int
        -supervisor_id: int
        -field_supervisor_id: int
        -start_date: date
        -end_date: date
        -status: enum
        -description: text
        -documents: json
        -rejection_reason: text
        -final_score: decimal
        -defense_date: date
        
        +isPending(): bool
        +isApproved(): bool
        +isRejected(): bool
        +isOngoing(): bool
        +isCompleted(): bool
        +getStatusLabel(): string
        +getStatusColorClass(): string
        +calculateDuration(): int
        +getProgress(): float
        +canBeUpdated(): bool
        +canBeDeleted(): bool
        +scopeByStatus(query, status): Builder
        +scopePending(query): Builder
        +scopeApproved(query): Builder
        +scopeOngoing(query): Builder
        +scopeCompleted(query): Builder
        
        +user(): BelongsTo
        +company(): BelongsTo
        +supervisor(): BelongsTo
        +fieldSupervisor(): BelongsTo
        +reports(): HasMany
        +evaluations(): HasMany
    }
    
    class Report {
        -id: int
        -pkl_id: int
        -user_id: int
        -report_type: enum
        -title: string
        -content: text
        -file_path: string
        -attachments: json
        -report_date: date
        -status: enum
        -feedback: text
        
        +isDraft(): bool
        +isSubmitted(): bool
        +isReviewed(): bool
        +isApproved(): bool
        +isRejected(): bool
        +isDaily(): bool
        +isWeekly(): bool
        +isMonthly(): bool
        +isFinal(): bool
        +getReportTypeLabel(): string
        +getStatusLabel(): string
        +getStatusColorClass(): string
        +canBeEdited(): bool
        +canBeDeleted(): bool
        +hasAttachments(): bool
        +getAttachmentsCount(): int
        +scopeByType(query, type): Builder
        +scopeByStatus(query, status): Builder
        +scopeDraft(query): Builder
        +scopeSubmitted(query): Builder
        
        +pkl(): BelongsTo
        +user(): BelongsTo
    }
    
    class Evaluation {
        -id: int
        -pkl_id: int
        -evaluator_id: int
        -evaluator_type: enum
        -technical_score: decimal
        -attitude_score: decimal
        -communication_score: decimal
        -final_score: decimal
        -comments: text
        -suggestions: text
        -status: enum
        
        +isDraft(): bool
        +isSubmitted(): bool
        +isFinal(): bool
        +isSupervisorEvaluation(): bool
        +isFieldSupervisorEvaluation(): bool
        +calculateFinalScore(): float
        +updateFinalScore(): void
        +getGradeLetter(): string
        +getEvaluatorTypeLabel(): string
        +getStatusColorClass(): string
        +canBeEdited(): bool
        +isComplete(): bool
        +getCompletionPercentage(): float
        +getAverageScore(): float
        +isPassingGrade(threshold: float): bool
        +getEvaluationWeight(): float
        +scopeByEvaluatorType(query, type): Builder
        +scopeSupervisorEvaluations(query): Builder
        +scopeFieldSupervisorEvaluations(query): Builder
        +scopeFinal(query): Builder
        
        +pkl(): BelongsTo
        +evaluator(): BelongsTo
        +student(): HasOneThrough
        +company(): HasOneThrough
    }
    
    class Message {
        -id: int
        -sender_id: int
        -receiver_id: int
        -subject: string
        -message: text
        -attachments: json
        -read_at: datetime
        -priority: enum
        
        +isRead(): bool
        +isUnread(): bool
        +markAsRead(): void
        +markAsUnread(): void
        +isHighPriority(): bool
        +isNormalPriority(): bool
        +isLowPriority(): bool
        +getPriorityLabel(): string
        +getPriorityColorClass(): string
        +getPriorityBadgeClass(): string
        +getTimeAgo(): string
        +hasAttachments(): bool
        +getAttachmentsCount(): int
        +getPreview(length: int): string
        +isSystemMessage(): bool
        +scopeUnread(query): Builder
        +scopeRead(query): Builder
        +scopeConversation(query, userId1, userId2): Builder
        +scopeLatest(query): Builder
        +scopeByPriority(query, priority): Builder
        +scopeHighPriority(query): Builder
        
        +sender(): BelongsTo
        +receiver(): BelongsTo
    }
    
    class Notification {
        -id: string
        -type: string
        -notifiable_type: string
        -notifiable_id: int
        -data: json
        -read_at: datetime
        
        +markAsRead(): void
        +markAsUnread(): void
        +isRead(): bool
        +isUnread(): bool
        
        +notifiable(): MorphTo
    }
    
    %% Inheritance Relationships
    Authenticatable <|-- User
    Model <|-- Company
    Model <|-- PKL
    Model <|-- Report
    Model <|-- Evaluation
    Model <|-- Message
    Model <|-- Notification
    
    %% Interface Implementation
    User ..|> MustVerifyEmail
    
    %% Trait Usage
    User ..> HasFactory
    User ..> Notifiable
    User ..> HasApiTokens
    Company ..> HasFactory
    PKL ..> HasFactory
    Report ..> HasFactory
    Evaluation ..> HasFactory
    Message ..> HasFactory
    
    %% Associations
    User ||--o{ PKL : "siswa memiliki"
    User ||--o{ PKL : "guru membimbing (supervisor)"
    User ||--o{ PKL : "pembimbing lapangan (field_supervisor)"
    Company ||--o{ PKL : "menerima siswa"
    
    PKL ||--o{ Report : "memiliki laporan"
    User ||--o{ Report : "membuat laporan"
    
    PKL ||--o{ Evaluation : "dievaluasi"
    User ||--o{ Evaluation : "mengevaluasi"
    
    User ||--o{ Message : "mengirim (sender)"
    User ||--o{ Message : "menerima (receiver)"
    
    User ||--o{ Notification : "menerima notifikasi"
    
    User }o--o{ Company : "pembimbing lapangan di"
```

## Penjelasan Class dan Relationships

### 1. **Base Classes dan Interfaces**

#### **Authenticatable (Abstract Class)**
- Base class Laravel untuk authentication
- Diextend oleh User model untuk fitur login

#### **Model (Abstract Class)**
- Base class Laravel untuk semua Eloquent models
- Menyediakan timestamps dan basic CRUD operations

#### **MustVerifyEmail (Interface)**
- Interface Laravel untuk email verification
- Diimplementasikan oleh User model

### 2. **Traits**

#### **HasFactory**
- Laravel trait untuk model factories
- Digunakan untuk testing dan seeding

#### **Notifiable**
- Laravel trait untuk notification system
- Memungkinkan model menerima notifikasi

#### **HasApiTokens**
- Laravel Sanctum trait untuk API authentication
- Digunakan oleh User model untuk token management

### 3. **Core Models**

#### **User Model**
**Deskripsi:** Model utama untuk semua pengguna sistem dengan multi-role
**Key Features:**
- **Multi-role support:** admin, koordinator, guru, siswa, pembimbing_lapangan
- **Security features:** login attempts, account locking, password management
- **Relationship methods:** untuk PKL, reports, evaluations, messages
- **Scope methods:** untuk filtering berdasarkan role dan status

#### **Company Model**
**Deskripsi:** Model untuk perusahaan mitra PKL
**Key Features:**
- **Slot management:** max_students, available slots calculation
- **Status management:** active/inactive companies
- **Business logic:** company type detection, availability checking

#### **PKL Model**
**Deskripsi:** Model utama untuk data PKL dengan workflow status
**Key Features:**
- **Status workflow:** pending → approved → ongoing → completed/rejected
- **Progress tracking:** duration calculation, progress percentage
- **Multiple supervisors:** guru pembimbing dan pembimbing lapangan
- **Document management:** JSON field untuk dokumen persyaratan

#### **Report Model**
**Deskripsi:** Model untuk laporan berkala PKL
**Key Features:**
- **Report types:** daily, weekly, monthly, final
- **Status workflow:** draft → submitted → reviewed → approved/rejected
- **File management:** attachments dan file uploads
- **Feedback system:** dari supervisor

#### **Evaluation Model**
**Deskripsi:** Model untuk evaluasi PKL dengan multi-aspect scoring
**Key Features:**
- **Multi-aspect scoring:** technical, attitude, communication
- **Grade calculation:** automatic final score calculation
- **Evaluator types:** supervisor vs field_supervisor
- **Grade letters:** A, B, C, D, E based on score ranges

#### **Message Model**
**Deskripsi:** Model untuk komunikasi internal sistem
**Key Features:**
- **Priority levels:** high, normal, low
- **Read status:** read/unread tracking
- **Attachments:** JSON field untuk file attachments
- **Conversation:** scope untuk chat antar user

#### **Notification Model**
**Deskripsi:** Laravel notification model dengan polymorphic relationship
**Key Features:**
- **Polymorphic:** bisa terkait dengan berbagai model
- **JSON data:** flexible notification content
- **Read status:** read/unread tracking

### 4. **Relationship Patterns**

#### **One-to-Many (1:M)**
- User → PKL (siswa memiliki PKL, guru membimbing)
- Company → PKL (perusahaan menerima siswa)
- PKL → Report (PKL memiliki banyak laporan)
- PKL → Evaluation (PKL dievaluasi berkali-kali)
- User → Message (sender/receiver)

#### **Many-to-Many (M:M)**
- User ↔ Company (pembimbing lapangan di perusahaan)

#### **Polymorphic**
- Notification → User (dan model lain yang notifiable)

#### **Has-One-Through**
- Evaluation → User (student melalui PKL)
- Evaluation → Company (melalui PKL)

### 5. **Business Logic Patterns**

#### **Status Management**
- Semua model utama memiliki status enum
- Status-specific methods (isPending(), isApproved(), dll)
- Status color classes untuk UI

#### **Scope Methods**
- Query scopes untuk filtering (scopeActive(), scopeByRole(), dll)
- Reusable query logic

#### **Calculation Methods**
- Score calculation (calculateFinalScore())
- Progress calculation (getProgress())
- Duration calculation (calculateDuration())

#### **Validation Methods**
- Permission checking (canBeEdited(), canBeDeleted())
- Business rule validation (hasAvailableSlots(), isPassingGrade())

### 6. **Laravel Features Integration**

#### **Casting**
- Automatic type casting (dates, decimals, JSON, booleans)
- Consistent data types across application

#### **Accessors/Mutators**
- Label methods untuk UI (getStatusLabel(), getRoleLabel())
- Color class methods untuk styling

#### **Mass Assignment Protection**
- $fillable arrays untuk security
- $hidden arrays untuk sensitive data

## Catatan Implementasi

1. **Terminologi SMK:** Menggunakan guru/siswa/NIS bukan dosen/mahasiswa/NIM
2. **Security:** Account locking, login attempts, password management
3. **Flexibility:** JSON fields untuk documents dan attachments
4. **Performance:** Query scopes dan eager loading relationships
5. **UI Integration:** Color classes dan label methods untuk frontend
6. **Business Rules:** Comprehensive validation dan calculation methods

Class Diagram ini menjadi blueprint untuk development dan maintenance, memastikan struktur OOP yang solid dan relationships yang konsisten dalam sistem SPEKTRA PKL.
