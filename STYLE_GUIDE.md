# SPEKTRA UI Style Guide

## Overview
SPEKTRA menggunakan design system modern minimalist dengan pendekatan monochrome (hitam, putih, abu-abu) yang dibangun dengan Tailwind CSS 4.0 dan Laravel Blade components. Design system ini menekankan kesederhanaan, konsistensi, dan aksesibilitas.

## Design Principles

### 1. Modern Minimalist Monochrome
- Clean dan uncluttered interface
- Fokus pada content dan functionality
- Subtle visual elements
- Consistent white space
- Gradasi warna netral (putih, abu-abu, hitam)

### 2. Accessibility First
- WCAG 2.1 AA compliance
- Proper color contrast ratios
- Keyboard navigation support
- Screen reader friendly

### 3. Mobile First
- Responsive design untuk semua device sizes
- Touch-friendly interface elements
- Optimized untuk mobile performance

## Monochrome Color Palette

### Monochrome Colors
```css
mono-50: #fafafa;
mono-100: #f5f5f5;
mono-200: #e5e5e5;
mono-400: #a3a3a3;
mono-500: #737373;
mono-700: #262626;
mono-900: #171717;
white: #ffffff;
black: #0a0a0a;
```

### CSS Variables
```css
:root {
  --color-bg: #ffffff;
  --color-bg-secondary: #fafafa;
  --color-surface: #f5f5f5;
  --color-border: #e5e5e5;
  --color-text-primary: #171717;
  --color-text-secondary: #525252;
  --color-text-tertiary: #a3a3a3;
}
.dark {
  --color-bg: #0a0a0a;
  --color-bg-secondary: #171717;
  --color-surface: #262626;
  --color-border: #404040;
  --color-text-primary: #fafafa;
  --color-text-secondary: #d4d4d4;
  --color-text-tertiary: #737373;
}
```

## Typography

### Font Family
- **Primary**: Instrument Sans (Modern, readable)
- **Fallback**: ui-sans-serif, system-ui, sans-serif

### Font Scale
```css
text-xs: 0.75rem (12px)
text-sm: 0.875rem (14px)
text-base: 1rem (16px) - Body text
text-lg: 1.125rem (18px)
text-xl: 1.25rem (20px)
text-2xl: 1.5rem (24px) - Page titles
text-3xl: 1.875rem (30px) - Section headers
```

### Font Weights
- **Regular (400)**: Body text
- **Medium (500)**: Labels, secondary headings
- **Semibold (600)**: Primary headings
- **Bold (700)**: Important emphasis

## Spacing System

### Base Unit: 0.25rem (4px)

```css
space-1: 0.25rem (4px)
space-2: 0.5rem (8px)
space-3: 0.75rem (12px)
space-4: 1rem (16px) - Standard spacing
space-6: 1.5rem (24px) - Section spacing
space-8: 2rem (32px) - Large spacing
space-12: 3rem (48px) - Extra large spacing
```

## Component Library

### Buttons

#### Variants
- **Primary**: Main actions (bg-primary-600)
- **Secondary**: Secondary actions (bg-neutral-100)
- **Outline**: Tertiary actions (border + transparent bg)
- **Ghost**: Minimal actions (transparent bg)
- **Danger**: Destructive actions (bg-error-600)

#### Sizes
- **xs**: px-2.5 py-1.5 text-xs
- **sm**: px-3 py-2 text-sm
- **md**: px-4 py-2 text-sm (Default)
- **lg**: px-4 py-2 text-base
- **xl**: px-6 py-3 text-base

#### Usage
```blade
<x-button variant="primary" size="md">Save Changes</x-button>
<x-button variant="outline" size="sm" icon="plus">Add Item</x-button>
```

### Cards

#### Variants
- **default**: Standard card with soft shadow
- **bordered**: Card with border emphasis
- **elevated**: Card with large shadow
- **flat**: Minimal card with subtle border

#### Usage
```blade
<x-card variant="default" padding="md">
    <x-slot name="header">Card Title</x-slot>
    Card content goes here
    <x-slot name="footer">Footer content</x-slot>
</x-card>
```

### Form Components

#### Input Fields
```blade
<x-input 
    name="email" 
    label="Email Address" 
    type="email"
    placeholder="Enter your email"
    icon="user"
    required 
    :error="$errors->first('email')"
/>
```

#### Select Dropdowns
```blade
<x-select 
    name="status" 
    label="Status"
    placeholder="Choose status"
    :options="['active' => 'Active', 'inactive' => 'Inactive']"
/>
```

#### Textareas
```blade
<x-textarea 
    name="description" 
    label="Description"
    rows="4"
    placeholder="Enter description"
/>
```

### Alerts

#### Types
- **success**: Green theme for success messages
- **error**: Red theme for error messages
- **warning**: Yellow theme for warnings
- **info**: Blue theme for information

#### Usage
```blade
<x-alert type="success" :dismissible="true">
    <strong>Success!</strong> Your changes have been saved.
</x-alert>
```

## Layout Patterns

### App Layout
- **Sidebar**: 256px width, collapsible on mobile
- **Header**: 64px height, contains breadcrumbs and user menu
- **Main Content**: max-width-7xl with responsive padding

### Auth Layout
- **Split Layout**: Branding on left, form on right
- **Mobile**: Stacked layout with logo at top

### Grid System
```css
grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4
```

## Icons

### Icon System
- **Library**: Heroicons (outline and solid variants)
- **Sizes**: xs (12px), sm (16px), md (20px), lg (24px), xl (32px)
- **Usage**: Consistent with text size and context

```blade
<x-icon name="home" size="sm" class="text-neutral-500" />
```

## Dark Mode

### Implementation
- **Strategy**: Class-based dark mode
- **Toggle**: Available in header and auth pages
- **Storage**: localStorage persistence

### Color Adjustments
```css
/* Light Mode */
bg-white text-neutral-900

/* Dark Mode */
dark:bg-neutral-800 dark:text-white
```

## Responsive Breakpoints

```css
sm: 640px   (Small tablets)
md: 768px   (Tablets)
lg: 1024px  (Small laptops)
xl: 1280px  (Laptops)
2xl: 1536px (Large screens)
```

## Animation & Transitions

### Standard Transitions
```css
transition-colors duration-200  (Color changes)
transition-all duration-300     (Layout changes)
```

### Custom Animations
- **fade-in**: 0.5s ease-in-out
- **slide-up**: 0.3s ease-out
- **slide-down**: 0.3s ease-out

## Best Practices

### 1. Component Usage
- Always use provided components instead of custom HTML
- Follow consistent naming conventions
- Use semantic HTML elements

### 2. Spacing
- Use consistent spacing scale
- Maintain vertical rhythm
- Provide adequate breathing room

### 3. Colors
- Stick to defined color palette
- Ensure proper contrast ratios
- Use semantic colors appropriately

### 4. Typography
- Maintain clear hierarchy
- Use appropriate font weights
- Ensure readable line heights

### 5. Accessibility
- Include proper ARIA labels
- Ensure keyboard navigation
- Test with screen readers
- Maintain color contrast

## File Structure

```
resources/views/
├── layouts/
│   ├── app.blade.php          # Main application layout
│   └── auth.blade.php         # Authentication layout
├── components/
│   ├── button.blade.php       # Button component
│   ├── card.blade.php         # Card component
│   ├── input.blade.php        # Input component
│   ├── select.blade.php       # Select component
│   ├── textarea.blade.php     # Textarea component
│   ├── alert.blade.php        # Alert component
│   └── icon.blade.php         # Icon component
├── auth/
│   ├── login.blade.php        # Login page
│   └── register.blade.php     # Register page
├── examples/
│   ├── form-page.blade.php    # Form example
│   └── table-page.blade.php   # Table example
└── dashboard.blade.php        # Dashboard page
```

## Browser Support

- **Modern Browsers**: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- **Mobile**: iOS Safari 14+, Chrome Mobile 90+
- **Features**: CSS Grid, Flexbox, CSS Custom Properties

## Performance

- **CSS**: Optimized with Tailwind's JIT compiler
- **Images**: Lazy loading implementation
- **JavaScript**: Minimal vanilla JS for interactions
- **Bundle Size**: Optimized for production builds

## Komponen Monochrome

### Tombol
```blade
<button class="bg-white border border-gray-200 text-gray-900 hover:bg-gray-100 active:bg-gray-200 rounded-lg px-4 py-2">
  Simpan
</button>
```

### Card
```blade
<div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
  Konten Card
</div>
```

### Input
```blade
<input class="w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 bg-white placeholder-gray-400" placeholder="Masukkan data">
```

### Alert
```blade
<div class="bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-4">
  Pesan alert monochrome
</div>
```

## Tips Desain Monochrome
- Gunakan kontras tinggi antara teks dan background.
- Manfaatkan white space untuk tampilan lega.
- Gunakan shadow lembut untuk depth tanpa warna.
- Icon sebaiknya hitam/abu atau outline saja.
- Jika ingin aksen, gunakan hanya pada elemen penting (misal: tombol utama) dengan warna brand yang sangat minim.

## Dark Mode
- Gunakan gradasi abu-abu gelap dan putih untuk teks.
- Pastikan kontras tetap terjaga.

## Responsive, Layout, dan Komponen Lain
Tetap gunakan guideline layout, grid, spacing, dan komponen seperti sebelumnya, namun dengan warna-warna monochrome di atas.

## Best Practices
- Selalu gunakan komponen yang disediakan.
- Konsisten dalam penggunaan warna dan spacing.
- Pastikan aksesibilitas dan kontras warna.

## Contoh Palet Monochrome
```css
/* Light Mode */
.bg-white { background-color: #fff; }
.bg-gray-50 { background-color: #fafafa; }
.bg-gray-100 { background-color: #f5f5f5; }
.text-gray-900 { color: #171717; }
.text-gray-500 { color: #737373; }

/* Dark Mode */
.dark .bg-gray-900 { background-color: #0a0a0a; }
.dark .text-white { color: #fafafa; }
```

---

Dengan pendekatan ini, tampilan aplikasi Anda akan selalu modern, minimalis, dan mudah dikembangkan ke depan.
