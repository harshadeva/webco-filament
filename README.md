
# WebcoTest

  

**WebcoTest** is a Laravel-based admin panel built using [FilamentPHP](https://filamentphp.com) to manage products, categories, colors, types, and more. This project was created as part of a technical assessment for a job interview.

  

---

  

## Table of Contents

  

- [Requirements](#requirements)

- [Installation](#installation)

- [Usage](#usage)

- [ERD Diagram](#erd-diagram)

- [Queue Worker](#queue-worker)

- [Contact](#contact)

  

---

  

## Requirements

  

-  **PHP**: >= 8.1

-  **Laravel**: >= 10.0

-  **Composer**: Latest version

-  **Node.js & npm**: For Filament asset compilation

-  **Database**: MySQL or SQLite (recommended for simplicity)

  

---

  

## Installation

  

### 1. Clone the Repository

  

```bash

git  clone  https://github.com/harshadeva/webco-filament

cd  webco-filament

```

  

### 2. Install PHP Dependencies

  

```bash

composer  install

```

  

### 3. Install Node.js Dependencies

  

```bash

npm  install

npm  run  build

```

  

### 4. Configure Environment

  

- Copy `.env.example` to `.env`:

  

```bash

cp  .env.example  .env

```

  

- Update `.env` with your database and Vocus credentials:

  

```

DB_CONNECTION=mysql # or sqlite

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=webcotest

DB_USERNAME=your_username

DB_PASSWORD=your_password

  

VOCUS_USERNAME=your_vocus_username

VOCUS_PASSWORD=your_vocus_password

```

  

> 💡 Tip: You can also use SQLite to keep things simple for local testing.

  

### 5. Generate Application Key

  

```bash

php  artisan  key:generate

```

  

### 6. Run Migrations with Seeders

  

```bash

php  artisan  migrate  --seed

```

  

### 7. Run Optional Fake Data Seeder

  

```bash

php  artisan  db:seed  --class=FakeSeeder

```

  

### 8. Start Development Server

  

```bash

php  artisan  serve

```

  

### 9.Create a New Admin User

  

You can create your first Filament admin user by running:

  

```bash

php  artisan  make:filament-user

```

  

Follow the CLI prompts to set up the name, email, and password.

### 10. Access the Application

  

Visit [http://localhost:8000/admin](http://localhost:8000/admin)

  

> ⚠️ You need to create the first admin user before logging in.


  

## Usage

  

### Admin Panel Access

  

- Navigate to `/admin` and log in using newly created admin user

- Explore dashboard widgets for:

- Product insights

- Product categories

- Service qualification statuses

  

### Clear Cache (if needed)

  

```bash

php  artisan  cache:clear

php  artisan  filament:cache-components

npm  run  build

```

  

---

  

## ERD Diagram

  

The project includes an **Entity Relationship Diagram** in both PDF and editable formats.

  

- Location: `docs/erd/`

- Editable format is compatible with **MySQL Workbench**

  

---

  

## Queue Worker

  

To experience the asynchronous queue job feature, run:

  

```bash

php  artisan  queue:work

```

  

This job runs a simple database update using Laravel's queue system.

  

---

  

## Contact

  

This project is build by Harshadeva Priyankara Bandara. For more information or questions, feel free to connect:

  

- 📧 Email: [hpbandara94@gmail.com](mailto:hpbandara94@gmail.com)

- 🌐 Portfolio: [https://webdevelopersl.com](https://webdevelopersl.com)

- 📝 Blog: [https://qcap.webdevelopersl.com](https://qcap.webdevelopersl.com)

- 💬 WhatsApp: [+94 717275539](https://wa.me/94717275539)

  

---

  

## License

  

This project is provided as-is for demonstration purposes only.