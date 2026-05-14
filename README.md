# 🖥️ Computer Laboratory Reservation and Scheduling Tracking System

> A web-based system for managing computer laboratory reservations, scheduling, and usage tracking in academic environments — built with **Laravel**, **React (Inertia.js)**, and **TypeScript**.

---

## 📌 About

This system was developed as part of a **System Analysis and Design (SAD)** course, following the complete **Systems Development Life Cycle (SDLC)**. It provides a streamlined solution for students, instructors, and administrators to manage computer lab bookings, scheduling, and usage tracking efficiently.

---

## ✨ Features

- 📅 **Lab Reservation** — Book computer laboratory slots in advance
- 🗓️ **Scheduling Management** — View and manage lab schedules by room, time, and date
- 📊 **Usage Tracking** — Monitor laboratory usage history and occupancy
- 👤 **User Roles** — Separate access for Students, Instructors, and Administrators
- 🔔 **Notifications** — Alerts for upcoming reservations and schedule conflicts
- 🗃️ **Admin Dashboard** — Manage rooms, equipment, and user accounts

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | React 19, TypeScript, Tailwind CSS |
| Backend | Laravel (PHP) |
| Routing | Inertia.js (SPA without API) |
| UI Components | shadcn/ui, Radix UI |
| Build Tool | Vite |
| Database | MySQL |
| Testing | PHPUnit |

---

## 📋 SDLC Phases Covered

1. **Planning** — Project scope, feasibility study, and resource planning
2. **Analysis** — Requirements gathering, use case diagrams, DFDs
3. **Design** — System architecture, ERD, UI wireframes
4. **Implementation** — Full-stack development using Laravel + React
5. **Testing** — Unit testing, integration testing, UAT
6. **Deployment** — Production build and server configuration
7. **Maintenance** — Bug fixes and future enhancements

---

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/ItsmeEremiyaaaa/-System-Analysis-and-Design.git
cd -System-Analysis-and-Design

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Set up environment
cp .env.example .env
php artisan key:generate

# 5. Configure your database in .env
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 6. Run migrations
php artisan migrate

# 7. Seed the database (optional)
php artisan db:seed

# 8. Start development servers
php artisan serve
npm run dev
```

Visit `http://localhost:8000` in your browser.

---

## 🧪 Running Tests

```bash
php artisan test
```

---

## 📁 Project Structure

```
├── app/                # Laravel application logic
│   ├── Http/           # Controllers, Middleware, Requests
│   └── Models/         # Eloquent models
├── bootstrap/          # App bootstrap files
├── config/             # Configuration files
├── database/           # Migrations and seeders
├── public/             # Public assets
├── resources/          # Views, React components, CSS
│   ├── js/             # React + TypeScript frontend
│   └── views/          # Blade templates (Inertia root)
├── routes/             # Web and API routes
├── storage/            # Logs and file storage
└── tests/              # PHPUnit test cases
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature-name`
3. Commit your changes: `git commit -m "Add: your feature description"`
4. Push to the branch: `git push origin feature/your-feature-name`
5. Open a **Pull Request** with a description and screenshots

---

## 📄 License

This project is open-sourced software licensed under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Jeremiah Falcon Escubido* ([@ItsmeEremiyaaaa](https://github.com/ItsmeEremiyaaaa))

> Developed for System Analysis and Design — Academic Project
