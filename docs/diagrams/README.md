# Diagram SPEKTRA PKL System

Folder ini berisi semua diagram yang digunakan untuk dokumentasi sistem SPEKTRA PKL.

## 📊 Daftar Diagram

### 1. Entity Relationship Diagram (ERD)
- **File:** `erd-spektra-pkl.md` - Dokumentasi lengkap dengan penjelasan
- **File:** `erd-spektra-pkl.mmd` - Source Mermaid untuk rendering
- **Deskripsi:** ERD lengkap sistem dengan 12 entitas utama dan relasi antar tabel
- **Status:** ✅ Completed

### 2. Use Case Diagram
- **File:** `use-case-diagram.md` - Dokumentasi lengkap dengan penjelasan
- **File:** `use-case-diagram.mmd` - Source Mermaid untuk rendering
- **Deskripsi:** Interaksi 5 aktor (Admin, Koordinator, Guru, Siswa, Pembimbing Lapangan) dengan sistem
- **Status:** ✅ Completed

### 3. Class Diagram
- **File:** `class-diagram.md` - Dokumentasi lengkap dengan penjelasan
- **File:** `class-diagram.mmd` - Source Mermaid untuk rendering
- **Deskripsi:** Struktur model Laravel dengan atribut, methods, dan relationships
- **Status:** ✅ Completed

### 4. Activity Diagram - Workflow PKL
- **File:** `activity-workflow-pkl.md` - Dokumentasi lengkap dengan penjelasan
- **File:** `activity-workflow-pkl.mmd` - Source Mermaid untuk rendering
- **Deskripsi:** Alur proses bisnis PKL lengkap dengan swimlanes dan decision points
- **Status:** ✅ Completed

### 5. Sequence Diagram *(Planned)*
- **File:** `sequence-diagram.mmd`
- **Deskripsi:** Interaksi antar komponen sistem
- **Status:** 🔄 Planned

### 6. Architecture Diagram *(Planned)*
- **File:** `architecture-diagram.mmd`
- **Deskripsi:** Arsitektur sistem Laravel MVC
- **Status:** 🔄 Planned

## 🛠️ Cara Menggunakan

### Rendering Diagram Mermaid

#### 1. Di GitHub/GitLab
Diagram Mermaid akan otomatis ter-render ketika file `.md` dibuka di GitHub atau GitLab.

#### 2. Di VS Code
Install extension "Mermaid Preview" untuk preview diagram.

#### 3. Online Mermaid Editor
Buka [Mermaid Live Editor](https://mermaid.live/) dan copy-paste kode dari file `.mmd`.

#### 4. Di Dokumentasi
Embed diagram langsung dalam file Markdown:

```markdown
```mermaid
// Copy kode dari file .mmd
```
```

### Editing Diagram

1. **Edit file `.mmd`** untuk mengubah diagram
2. **Update file `.md`** jika ada perubahan penjelasan
3. **Test rendering** sebelum commit
4. **Update README** jika menambah diagram baru

## 📋 Konvensi Penamaan

- **Format file:** `[jenis-diagram]-[deskripsi].mmd`
- **Dokumentasi:** `[jenis-diagram]-[deskripsi].md`
- **Contoh:** 
  - `erd-spektra-pkl.mmd`
  - `use-case-diagram.mmd`
  - `activity-workflow-pkl.mmd`

## 🎯 Prioritas Diagram

### Priority 1 (Wajib)
- ✅ **ERD** - Entity Relationship Diagram
- ✅ **Use Case** - Interaksi user dengan sistem
- ✅ **Class** - Struktur model Laravel
- ✅ **Activity** - Workflow PKL

### Priority 2 (Penting)
- 🔄 **Sequence** - Interaksi komponen
- 🔄 **Architecture** - Struktur sistem

### Priority 3 (Opsional)
- 🔄 **State** - Status transitions
- 🔄 **Component** - Arsitektur komponen
- 🔄 **Deployment** - Infrastruktur

## 📝 Template Diagram Baru

Ketika membuat diagram baru, gunakan template berikut:

```markdown
# [Nama Diagram] - SPEKTRA PKL System

## Deskripsi
[Penjelasan singkat tujuan diagram]

## Diagram

```mermaid
[Kode Mermaid]
```

## Penjelasan
[Penjelasan detail komponen dan relasi]

## Catatan Implementasi
[Catatan teknis jika diperlukan]
```

## 🔗 Referensi

- [Mermaid Documentation](https://mermaid-js.github.io/mermaid/)
- [Mermaid Live Editor](https://mermaid.live/)
- [GitHub Mermaid Support](https://github.blog/2022-02-14-include-diagrams-markdown-files-mermaid/)

---

**Catatan:** Semua diagram dalam folder ini menggunakan format Mermaid untuk konsistensi dan kemudahan maintenance.
