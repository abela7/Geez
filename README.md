# Restaurant Management System

A modern restaurant management system built with Laravel 11, Livewire v3, Tailwind CSS, and Alpine.js.

## Stack

- **Backend**: Laravel 11
- **Frontend**: Livewire v3 + Tailwind CSS + Alpine.js (sprinkles only)
- **Design**: Brand tokens (Cream, Eggplant, Plum Gray, Gold)
- **Internationalization**: English, Amharic, Tigrinya
- **Testing**: Pest (when compatible), Larastan (level 8)

## Quickstart

1. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

2. **Environment setup**:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

3. **Build assets**:
   ```bash
   npm run build
   ```

4. **Start development server**:
   ```bash
   php artisan serve
   ```

5. **Run tests**:
   ```bash
   composer test
   ```

## Development Workflow

### Cursor Protocol

We follow a strict development protocol:

1. **PLAN**: Propose a tiny checklist + files to touch + wait for PM approval
2. **IMPLEMENT**: Ship exactly that slice + post summary + follow-ups

### One Tiny Slice Rule

- Build one subsection at a time
- No "nice extras" without approval
- Finish each slice completely before moving on
- All text via i18n keys
- Mobile-first responsive design
- Dark/light theme support

### Quality Gates

Each slice must pass:
- `composer lint` (Pint)
- `composer analyse` (Larastan level 8)
- `composer test` (Pest)
- `npm run ci` (build only)

### Design Tokens

Use semantic color tokens (no raw hex):
- `bg-cream`, `text-eggplant`, `border-gold`
- Dark theme: `dark:bg-cream`, `dark:text-eggplant`
- AA contrast compliance

### Internationalization

All UI text via translation keys:
- `resources/lang/en/` (English)
- `resources/lang/am/` (Amharic)
- `resources/lang/ti/` (Tigrinya)

## Security

See [SECURITY.md](SECURITY.md) for security guidelines and defaults.
