# 🛡️ Cyber-Minimalist Portfolio

> A TALL Stack (Tailwind v4, Alpine.js, Laravel 12, Livewire 3) portfolio designed for cybersecurity professionals. Features a "Blue Team Defense" theme with glassmorphism, terminal aesthetics, and real-time server status monitoring.

## 🚀 Tech Stack

- **Framework:** [Laravel 12](https://laravel.com)
- **Frontend:** [Livewire 3](https://livewire.laravel.com) + [Alpine.js](https://alpinejs.dev)
- **Styling:** [Tailwind CSS v4](https://tailwindcss.com)
- **Admin Panel:** [Filament 4.0](https://filamentphp.com)
- **Bundler:** [Vite](https://vitejs.dev)

## ✨ Key Features

- **🌐 Live Server Status:** Real-time system monitoring simulation using Livewire polling.
- **💻 Terminal Command Palette:** Interactive command-line interface for navigation.
- **📄 Dynamic Content:** Manage projects, skills, and experiences via the Admin Panel.
- **🎨 Glassmorphism UI:** Modern, dark-mode first design with "Cyber-Minimalist" aesthetics.
- **🔧 Admin Dashboard:** Built with Filament for easy content management.

## 🛠️ Installation

### Prerequisites
- PHP ^8.2
- Composer
- Node.js & NPM
- SQLite (or your preferred database)

### Quick Setup

We have included a convenient setup script in `composer.json`.

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd portfolio-lv
   ```

2. **Run the Setup Script**
   This single command installs PHP dependencies, sets up the `.env` file, generates the app key, migrates the database, and installs/builds Node dependencies.
   ```bash
   composer setup
   ```

### Manual Setup

If you prefer to set up manually:

```bash
# Install PHP dependencies
composer install

# Environment Setup
cp .env.example .env
php artisan key:generate

# Database Setup
touch database/database.sqlite
php artisan migrate --seed

# Frontend Setup
npm install
npm run build
```

## 👾 Development

Start the development server with a single command. This runs Laravel Serve, Queue Listener, Pail (Logs), and Vite concurrently.

```bash
composer dev
```

Or run them individually:

```bash
# Backend
php artisan serve

# Frontend (Hot Reload)
npm run dev

# Queue Worker
php artisan queue:listen
```

## 📂 Project Structure

- `app/Livewire/` - Frontend components (Server Status, Skills, etc.)
- `app/Filament/` - Admin panel resources.
- `resources/views/` - Blade templates and layout files.
- `routes/web.php` - Application routes.

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
