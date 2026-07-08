# 💼 HR Management System

A comprehensive human resource management and recruitment web application built from scratch using clean **PHP (custom MVC Architecture)** and **MySQL**. This system streamlines corporate workflows, handles applicant tracking, manages corporate structure, and provides rich reporting tools.

---

## 🚀 Key Features

*   **Custom MVC Architecture:** Crafted a lightweight, scalable MVC framework from scratch utilizing PSR-4 autoloading via Composer.
*   **Role-Based Authentication & Security:** Secure session management with built-in CSRF token defense and PDO Prepared Statements to prevent SQL injection.
*   **Comprehensive CRUD Operations:** Fully functional management for:
    *   `Users` & `Employees` (with Candidate-to-Employee onboarding workflow)
    *   `Departments` & `Fields`
    *   `Recruiters`, `Positions`, & `Candidates`
*   **Applicant Tracking System (ATS):** Public career page (`/careers`) allowing external applicants to view open positions, submit information, and upload CVs.
*   **Advanced Data Export:** Integrated reporting tools to seamlessly export analytical data into **Excel** spreadsheets and **PDF** documents.
*   **User Experience Enhancements:** Includes dynamic toast/flash notification mechanisms and robust helper utilities.

---

## 🛠️ Tech Stack & Dependencies

*   **Core:** PHP (OOP), MySQL (PDO Singleton)
*   **Autoloading:** Composer (PSR-4 compliant)
*   **Key Packages:**
    *   `fakerphp/faker` - Automated mock data generation for system seeding.
    *   `phpmailer/phpmailer` - Secure SMTP corporate email dispatch.
    *   `mpdf/mpdf` - Server-side dynamic PDF compilation and export.
    *   `phpoffice/phpspreadsheet` - Advanced programmatic Excel generation.

---

## 📂 System Architecture

The project strictly follows the customized MVC pattern separated into modular boundaries:

```text
├── db/                  # Database schema definitions (db.sql)
├── public/              # Document Root (Application Entry Point)
│   └── index.php        # Central router dispatching incoming requests
├── src/
│   ├── Controllers/     # Modules executing specific business logic operations
│   ├── Models/          # Direct database interaction layer mapping PDO queries
│   └── Core/
│       ├── Router.php   # Tailored custom routing engine
│       ├── Database.php # Thread-safe PDO Singleton connection instance
│       └── Controller.php # Base Controller managing auth & access structures
├── views/               # Front-end layout structures (HTML/PHP views)
└── config.php           # Core environment configurations
