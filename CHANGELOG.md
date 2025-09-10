# Changelog

## [Unreleased]

### Removed
- Bootstrap CSS/JS and all related imports
- Font Awesome icons and stylesheets
- Out-of-scope admin pages (dashboard, menu management, navigation)
- Admin controllers and routes
- Demo charts, cards, and generic UI components

### Added
- Tailwind CSS with brand design tokens (Cream, Eggplant, Plum Gray, Gold)
- Dark/light theme support via CSS custom properties
- Alpine.js for minimal JavaScript sprinkles
- Inter + Noto Sans Ethiopic fonts for multilingual support
- i18n skeleton (English, Amharic, Tigrinya language folders)
- Larastan static analysis (level 8)
- Tailwind plugins (@tailwindcss/forms, @tailwindcss/typography)
- CI scripts (lint, analyse, test, build)
- Base layout shell for token validation
- Security guidelines (SECURITY.md)
- Development workflow documentation (README.md)

### Changed
- Stack aligned to Laravel 11 + Livewire v3 + Tailwind + Alpine
- All colors use semantic design tokens (no raw hex values)
- Mobile-first responsive design approach
- Single welcome route only (no feature routes yet)

### Technical
- Environment setup complete for first slice development
- Quality gates configured (Pint, Larastan, Pest, Vite build)
- Repository hygiene: .env excluded, no secrets committed
