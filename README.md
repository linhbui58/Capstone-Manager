# 🎓 Capstone Manager

A full-featured **Capstone Project Management System** built with PHP MVC — designed for academic institutions to streamline the capstone/thesis lifecycle from topic proposal to final evaluation.

![PHP](https://img.shields.io/badge/PHP-8.x-8892BF?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

---

## ✨ Features

| Feature | Description |
|---|---|
| 🔐 **Role-based Access** | Admin, Lecturer, Student roles with different permissions |
| 📋 **Topic Management** | Submit, review, approve/reject capstone topics |
| 📝 **Registrations** | Students register for approved topics |
| 📅 **Semesters** | Manage academic periods |
| 🚩 **Milestones** | Set submission deadlines per semester |
| 📤 **Submissions** | Students submit reports per milestone |
| 🏆 **Scores** | Lecturers grade capstone defenses |
| 👥 **Assignments** | Assign supervisors to topics |
| 🔔 **Notifications** | System-wide notification center |
| 📊 **Dashboard** | Overview with charts and recent activity |
| 🗂️ **System Logs** | Full audit trail of user actions |

---

## 🖥️ Tech Stack

- **Backend:** PHP 8.x (custom MVC, no framework)
- **Database:** MySQL 8.0 via PDO
- **Frontend:** Bootstrap 5.3 + Vanilla CSS (Dark Glassmorphism theme)
- **Icons:** Font Awesome 6.5
- **Charts:** Chart.js
- **Tables:** DataTables.js
- **Animations:** AOS (Animate On Scroll)

---

## 🚀 Installation Guide

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (PHP 8.x + MySQL)
- Git

### Step 1 — Clone the repository

```bash
git clone https://github.com/linhbui58/Capstone-Manager.git
cd Capstone-Manager
```

Place the folder inside your XAMPP `htdocs` directory:
```
C:\xampp\htdocs\Capstone-Manager\
```

### Step 2 — Import the Database

1. Start **Apache** and **MySQL** in XAMPP Control Panel
2. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
3. Create a new database named `capstone_manager`
4. Click **Import** → select `database.sql` → click **Go**

Or via command line:
```bash
mysql -u root capstone_manager < database.sql
```

### Step 3 — Configure Database Connection

Edit `core/Database.php` if your MySQL credentials differ from the defaults:

```php
// Default settings (XAMPP defaults)
$host     = 'localhost';
$dbname   = 'capstone_manager';
$username = 'root';
$password = '';   // XAMPP default has no password
```

### Step 4 — Run the Application

Open your browser and navigate to:
```
http://localhost/Capstone-Manager/public/
```

---

## 🔑 Default Accounts

All accounts use the password: **`123456`**

| Role | Email |
|---|---|
| **Admin** | `admin@capstone.edu.vn` |
| **Lecturer** | `nguyen.vana@capstone.edu.vn` |
| **Student** | `sinh.vien01@capstone.edu.vn` |

> ⚠️ Change passwords after first login in a production environment.

---

## 📁 Project Structure

```
Capstone-Manager/
├── app/
│   ├── controllers/        # Page controllers (Auth, Dashboard, Topics...)
│   ├── models/             # Database models (User, Topic, Semester...)
│   └── views/              # PHP view templates
│       ├── layouts/        # Header, Sidebar, Footer
│       ├── auth/           # Login, Register
│       ├── dashboard/      # Dashboard overview
│       ├── topic-management/
│       ├── students/
│       ├── lecturers/
│       ├── submissions/
│       ├── scores/
│       ├── notifications/
│       └── ...
├── core/
│   ├── Database.php        # PDO singleton
│   └── Router.php          # URL routing
├── public/
│   ├── assets/
│   │   ├── css/style.css   # Dark glassmorphism design system
│   │   └── js/
│   ├── uploads/            # Submitted files (gitignored)
│   └── index.php           # Application entry point
└── database.sql            # Full schema + seed data
```

---

## 🎨 Design System

The UI uses a custom **Dark Glassmorphism** theme built with CSS variables:

- **Primary:** `#7c3aed` (Purple)
- **Accent:** `#06b6d4` (Cyan)
- **Background:** `#050816` (Deep dark)
- **Glass panels** with backdrop blur
- **Smooth animations** via AOS + CSS transitions
- **DataTables** with dark styling overrides

---

## 🛠️ Development Notes

- Entry point: `public/index.php` — routes via `?page=` parameter
- Database: Singleton PDO in `core/Database.php`
- Sessions used for auth (`$_SESSION['user']`)
- RBAC enforced at controller level

---

## 📄 License

MIT License — free to use, modify, and distribute.

---

## 🙏 Credits

Built and maintained by [linhbui58](https://github.com/linhbui58).  
UI enhanced with Dark Glassmorphism design system.
