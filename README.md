# TeamMate

TeamMate merupakan aplikasi berbasis web yang digunakan oleh mahasiswa untuk mencari anggota tim dalam suatu perlombaan.

## Project Description

TeamMate membantu mahasiswa menemukan rekan satu tim berdasarkan kebutuhan, kriteria, dan kategori kompetisi. Platform ini memungkinkan mahasiswa untuk membuat postingan pencarian anggota tim, melihat postingan yang tersedia, mengajukan permintaan bergabung, dan berkomunikasi dengan calon anggota tim.

## Main Features

- Browse Posts: Melihat daftar postingan pencarian anggota tim.
- Create Posts: Membuat postingan untuk mencari anggota tim.
- Apply: Mengajukan permintaan untuk bergabung dengan tim.
- Message: Berkomunikasi dengan anggota tim.
- Account Management: Mengelola akun pengguna.
- Post Moderation: Mengelola dan memoderasi postingan.

## Technology Stack

- Backend: Laravel
- Frontend: Blade
- Programming Language: PHP
- Database: PostgreSQL
- Version Control: Git & GitHub

## Project Structure

- `app/`: Application logic, Models, and Controllers.
- `bootstrap/`: Application bootstrapping and configuration.
- `config/`: Application configuration files.
- `database/`: Database migrations, seeders, and factories.
- `public/`: Public assets and application entry point.
- `resources/`: Blade views, CSS, and JavaScript.
- `routes/`: Application routes.
- `storage/`: Application logs, cache, and uploaded files.
- `tests/`: Application tests.

## Requirements

Make sure the following tools are installed:

- PHP
- Composer
- Node.js and npm
- PostgreSQL
- Git

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/5026241205Sabela/TeamMate.git
cd TeamMate
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Configure Environment

Copy the example environment file:

```bash
copy .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 4. Configure Database

Open the `.env` file and configure the PostgreSQL connection:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=teammate_db
DB_USERNAME=postgres
DB_PASSWORD=your_password
DB_SSLMODE=prefer
```

Replace the database credentials with your local PostgreSQL configuration.

To use a hosted PostgreSQL service such as Aiven, use the host, port, user, password, and database name from the service's connection information, and set `DB_SSLMODE=require`.

> The migrations use PostgreSQL-specific identity columns, so MySQL and SQLite are not supported.

### 5. Run Database Migrations

```bash
php artisan migrate
```

### 6. Run the Application

```bash
composer run dev
```

Open the application in your browser:

```text
http://localhost:8000
```

## Team Development

TeamMate is developed collaboratively using GitHub.

Development guidelines:

- Each member works on a separate feature branch.
- Do not commit directly to the `main` branch.
- Use meaningful commit messages.
- Submit a Pull Request before merging changes into `main`.
- Review code changes before merging.
- Do not upload `.env`, `vendor`, or `node_modules` to GitHub.

## License

This project is developed for academic purposes.