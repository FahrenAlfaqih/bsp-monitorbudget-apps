# 📊 Business Travel Budget Management System

This system is designed to assist the HCM Department in monitoring and managing the travel budget for official business trips (**SPD**) and declaration reports (**DPD**). It provides functionalities to track submissions, DPD issuance by Finance, and export reports for financial recaps.

---

## 🎯 Objectives

- Monitor the realization of travel budgets per department
- Simplify the DPD recap and reporting process
- Minimize manual entry errors and miscalculations
- Provide real-time status visibility of SPD and DPD workflows

---

## 🧪 Workflow Example

1. HCM admin logs into the system  
2. Manage department and employee data  
3. Create SPD records and submit travel requests  
4. FINEC admin approves/rejects requests  
   - If **approved**, DPD is issued  
   - If **rejected**, status is updated and SPD can be revised/resubmitted  
5. Once DPD is issued, department dashboards display employee travel statistics

---

## 🧩 Key Features

- 🔐 Role-based login for HCM Admin, Department Admins, and Technical Manager
- 📋 Manage SPD records (add, edit, filter by department/date/status)
- 📊 Budget tracking with real-time remaining balance per department
- 🚨 Auto alerts for over-budget submissions
- 📈 Dynamic dashboards with submission stats and exportable reports

---

## 🔧 Technology Stack

| Layer     | Tech Used                            |
|-----------|---------------------------------------|
| Backend   | Laravel 10 (PHP Framework)            |
| Database  | MySQL + PhpMyAdmin                    |
| Frontend  | Tailwind CSS, SweetAlert2             |
| Export    | Laravel Excel (XLSX), DomPDF (PDF)    |
| Build Tool| Vite, NPM                             |

---

## 🛠️ Installation & Setup

```bash
# 1. Clone the repository
git clone https://github.com/FahrenAlFaqih/bsp-final-project.git
cd bsp-final-project

# 2. Install dependencies
composer install

# 3. Create environment config
cp .env.example .env

# 4. Run database migrations and seeders
php artisan migrate
php artisan db:seed

# 5. Start development server
php artisan serve

# 6. Build frontend assets
npm install && npm run dev
```

## 👥 User Roles
| Role                    | Access & Responsibilities                                  |
| ----------------------- | ---------------------------------------------------------- |
| 🧑‍💼 HCM Admin         | Manage departments, employees, SPD, DPD                    |
| 🧾 FINEC Admin          | Approve/reject SPD and issue DPD                           |
| 🧑‍🔧 Tech Manager HCM  | Manage submission periods and travel budget plans          |
| 🏢 Dept. Admin (Others) | Draft travel budgets, monitor SPD/DPD for their department |
| 👁️ Viewer (Optional)   | View-only access to reports and analytics dashboards       |

## 📜 License
This project is developed exclusively for internal use at PT. Bumi Siak Pusako. Redistribution or public deployment is prohibited without formal approval.
