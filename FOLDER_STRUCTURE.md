# 🏗️ CRM & AI Business Automation Suite
**Laravel 11 | PHP 8.2 | Modular Architecture — Zero Duplication**

---

## ⚖️ Core Design Rule

```
app/        →  GLOBAL only  (shared across ALL modules)
modules/    →  DOMAIN only  (one module = one responsibility)
```

**Never** put module-specific logic in `app/`. **Never** duplicate between the two.

---

## 📂 `app/` — Truly Global Layer

```
app/
│
├── Console/
│   └── Commands/
│       ├── RunScheduledPayroll.php       # global scheduler
│       ├── SendAttendanceReminder.php
│       └── PruneActivityLogs.php
│
├── Contracts/                            # Cross-module interfaces
│   ├── Repository/
│   │   └── BaseRepositoryInterface.php  # all() find() create() update() delete()
│   ├── Service/
│   │   └── BaseServiceInterface.php
│   └── Notification/
│       └── NotifiableContract.php
│
├── Enums/
│   └── Shared/                          # Used by 2+ modules
│       ├── Status.php
│       ├── Priority.php
│       ├── Gender.php
│       ├── Country.php
│       └── Language.php
│
├── Events/
│   └── System/                          # Cross-module system events only
│
├── Exceptions/
│   ├── Handler.php
│   ├── UnauthorizedException.php
│   ├── ResourceNotFoundException.php
│   ├── ValidationException.php
│   └── PaymentException.php
│
├── Helpers/
│   ├── helpers.php                      # Global functions
│   ├── date_helpers.php
│   └── number_helpers.php
│
├── Http/
│   ├── Controllers/
│   │   ├── BaseApiController.php        # Shared JSON response methods
│   │   └── BaseWebController.php
│   ├── Middleware/
│   │   ├── CheckPermission.php
│   │   ├── TwoFactorAuth.php
│   │   ├── LocaleMiddleware.php
│   │   ├── ApiVersionMiddleware.php
│   │   ├── RateLimitMiddleware.php
│   │   └── EnsureEmailVerified.php
│   ├── Requests/
│   │   └── BaseFormRequest.php          # Module Requests extend this
│   └── Resources/
│       └── BaseResource.php             # Module Resources extend this
│
├── Jobs/
│   └── System/
│       ├── CleanupOldLogsJob.php
│       ├── BulkNotificationJob.php
│       └── ExportReportJob.php
│
├── Listeners/
│   └── System/                          # Global event listeners only
│
├── Mail/
│   └── Auth/                            # Login OTP, password reset
│
├── Models/
│   └── Shared/                          # Cross-module Eloquent models
│       ├── User.php                     # Auth user (all modules use this)
│       ├── Role.php
│       ├── Permission.php
│       ├── ActivityLog.php              # Global audit log
│       ├── MediaFile.php                # Shared file/upload model
│       ├── SystemNotification.php
│       └── Tenant.php                   # (if multi-tenant)
│
├── Notifications/
│   └── System/                          # System-wide push/email notifications
│
├── Providers/
│   ├── AppServiceProvider.php
│   ├── AuthServiceProvider.php
│   ├── EventServiceProvider.php
│   ├── RouteServiceProvider.php
│   ├── RepositoryServiceProvider.php    # Binds all module repositories
│   └── ModuleServiceProvider.php        # Loads all 15 module providers
│
├── Rules/
│   └── Shared/
│       ├── BangladeshPhone.php
│       ├── BangladeshNID.php
│       └── UniquePerTenant.php
│
└── Traits/
    ├── HasUuid.php
    ├── Auditable.php
    ├── SoftDeleteWithLog.php
    ├── ApiResponse.php
    ├── HasFilters.php
    └── HasMediaUpload.php
```

> **Rule:** `app/` এ নতুন কিছু add করার আগে জিজ্ঞেস করুন:
> *"এটা কি সত্যিই ২+ module ব্যবহার করবে?"* — না হলে module-এর ভেতরে রাখুন।

---

## 📦 `modules/` — 15টি Domain Module

প্রতিটি module **self-contained** — নিজের Controllers, Models, Services, Repositories, DB migrations সব নিজেই।

```
modules/
├── AI/                     # 01 - AI & Automation
├── OmnichannelInbox/       # 02 - WhatsApp, Facebook, Instagram
├── ClientManagement/       # 03 - CRM Core
├── FollowUp/               # 04 - Follow-Up
├── Proposal/               # 05 - Proposals
├── Meeting/                # 06 - Meetings
├── TaskNotice/             # 07 - Tasks & Notices
├── Finance/                # 08 - Finance
├── Settings/               # 09 - System & Settings
├── Employee/               # 10 - Employee Management
├── Attendance/             # 11 - Attendance
├── Leave/                  # 12 - Leave Management
├── Payroll/                # 13 - Payroll & Salary
├── KPI/                    # 14 - KPI & Performance
└── AIHRAssistant/          # 15 - AI HR Chat Assistant
```

### প্রতিটি module-এর অভ্যন্তরীণ স্ট্রাকচার (উদাহরণ: ClientManagement)

```
modules/ClientManagement/
│
├── Providers/
│   └── ClientManagementServiceProvider.php   # Registers routes, repos, services
│
├── Http/
│   ├── Controllers/          # Extends App\Http\Controllers\BaseApiController
│   │   ├── ClientController.php
│   │   ├── DealController.php
│   │   ├── AgentController.php
│   │   └── ClientPortalController.php
│   ├── Requests/             # Extends App\Http\Requests\BaseFormRequest
│   │   ├── StoreClientRequest.php
│   │   ├── UpdateClientRequest.php
│   │   └── StoreDealRequest.php
│   └── Resources/            # Extends App\Http\Resources\BaseResource
│       ├── ClientResource.php
│       └── DealResource.php
│
├── Models/
│   ├── Client.php
│   ├── Deal.php
│   ├── DealService.php
│   ├── Agent.php
│   └── ClientNote.php
│
├── Services/
│   ├── Profile/
│   │   └── ClientProfileService.php
│   ├── Scoring/
│   │   └── ClientScoringService.php
│   ├── Segmentation/
│   │   └── SegmentationService.php
│   ├── Deal/
│   │   ├── DealService.php
│   │   └── DealAlertService.php
│   ├── Agent/
│   │   └── AgentService.php
│   └── Portal/
│       └── ClientPortalService.php
│
├── Repositories/
│   ├── BaseRepository.php           # Implements App\Contracts\Repository\BaseRepositoryInterface
│   ├── ClientRepository.php         # Extends BaseRepository
│   └── DealRepository.php
│
├── Enums/
│   ├── ClientStatus.php             # New, Follow-up, Converted, Closed Lost...
│   ├── ClientSource.php
│   └── DealType.php
│
├── Events/
│   ├── ClientCreated.php
│   └── DealConverted.php
│
├── Jobs/
│   └── ImportClientsJob.php
│
├── Policies/
│   └── ClientPolicy.php
│
└── database/
    ├── migrations/
    │   ├── create_clients_table.php
    │   ├── create_deals_table.php
    │   └── create_agents_table.php
    └── seeders/
        └── ClientSeeder.php
```

---

### 01. AI & Automation
```
modules/AI/
├── Providers/ | Http/ | Models/ | Repositories/ | Events/ | Jobs/
└── Services/
    ├── Chatbot/            WhatsApp, Facebook, Instagram bot logic
    ├── Scoring/            AI Client Scoring (VIP / Hot / Cold)
    ├── Predictive/         Conversion, Churn, Revenue prediction
    ├── Voice/              Voice AI summary + commands
    ├── OCR/                Document processing
    ├── Marketing/          Campaign generation
    ├── Finance/            Cash flow prediction
    ├── HR/                 Performance prediction
    ├── Security/           Fraud detection
    └── Automation/         No-code workflow builder
```

### 02. Omnichannel Inbox
```
modules/OmnichannelInbox/
├── Providers/ | Http/ | Models/ | Repositories/ | Events/ | Jobs/
└── Services/
    ├── WhatsApp/
    ├── Facebook/
    ├── Instagram/
    └── AutoReply/          Smart Reply Suggestion Engine
```

### 08. Finance
```
modules/Finance/
├── Providers/ | Http/ | Repositories/ | Enums/ | Policies/
├── Models/
│   ├── Income/ | Expense/ | Invoice/ | Payment/ | Supplier/
└── Services/
    ├── Income/
    ├── Expense/
    ├── Invoice/            PDF, Receipt, Money Receipt, Agreement Invoice
    ├── Payment/            Transaction tracking + Audit Log
    └── Supplier/           Supplier invoice + payment tracking
```

### 11. Attendance
```
modules/Attendance/
├── Providers/ | Http/ | Models/ | Repositories/ | Enums/ | Jobs/
└── Services/
    ├── Biometric/          Fingerprint
    ├── RFID/
    ├── GPS/                Mobile App location-based
    ├── FaceRecognition/
    ├── Manual/             Admin manual entry
    ├── LiveLocation/       Real-time staff tracking
    └── Policy/             Office time, grace period, overtime rules
```

### 12. Leave Management
```
modules/Leave/
├── Providers/ | Http/ | Models/ | Repositories/ | Enums/ | Events/ | Jobs/
└── Services/
    ├── Application/        Leave apply form
    ├── Approval/           Manager → HR workflow
    ├── Balance/            Available / Used / Pending
    ├── Calendar/           Team calendar + conflict detection
    ├── Policy/             Types, accrual, carry forward, eligibility
    └── Holiday/            Public, Company, Weekend setup
```

### 13. Payroll & Salary
```
modules/Payroll/
├── Providers/ | Http/ | Models/ | Repositories/ | Enums/ | Jobs/
└── Services/
    ├── Structure/          Basic, Allowances, Bonus, Grade
    ├── Calculation/        Auto monthly calculation
    ├── Deduction/          Late, Tax, Loan, Other
    ├── Payslip/            PDF generate + email
    ├── Processing/         Bulk run, approval, lock/finalize
    ├── Payment/            Bank, bKash, Nagad, Cash
    └── History/            Records, increment, promotion impact
```

### 14. KPI & Performance
```
modules/KPI/
├── Providers/ | Http/ | Models/ | Repositories/ | Enums/
└── Services/
    ├── Target/             Individual + Department KPI
    ├── Tracking/           Progress %, real-time update
    ├── Review/             Monthly / Quarterly appraisal
    ├── Feedback/           360° — Manager, Peer, Self
    ├── Promotion/          Promotion + Salary increment
    └── Disciplinary/       Warning, Misconduct, Penalty
```

---

## 🔗 Cross-Module Dependency Map

```
modules/*  ──extends──▶  app/Http/Controllers/BaseApiController
modules/*  ──extends──▶  app/Http/Requests/BaseFormRequest
modules/*  ──extends──▶  app/Http/Resources/BaseResource
modules/*/Repositories  ──implements──▶  app/Contracts/Repository/BaseRepositoryInterface
modules/*/Models  ──uses──▶  app/Traits/* (Auditable, HasUuid, etc.)
modules/*/Models  ──uses──▶  app/Models/Shared/User (auth relationship)
modules/*/Enums  ──uses──▶  app/Enums/Shared/* (Status, Priority, Gender)
```

---

## 🗄️ Database

```
database/
├── migrations/
│   ├── shared/             # users, roles, permissions, activity_logs, media_files
│   └── (per-module migrations live inside modules/*/database/migrations/)
├── seeders/
│   ├── DatabaseSeeder.php  # orchestrates all module seeders
│   └── shared/             # roles, permissions, admin user seed
└── factories/
    └── shared/
```

---

## 🧪 Tests

```
tests/
├── Feature/                # Integration — per module
│   ├── AI/ | Auth/ | ClientManagement/ | Finance/
│   ├── Attendance/ | Leave/ | Payroll/ | KPI/
│   └── Settings/ | Employee/ | OmnichannelInbox/
└── Unit/                   # Pure unit tests
    ├── Services/            # Module service logic
    ├── Repositories/
    ├── Models/
    └── Helpers/
```

---

## 📋 Quick Rule Sheet

| বিষয় | কোথায় রাখবেন |
|------|--------------|
| Auth User model | `app/Models/Shared/User.php` |
| Role & Permission | `app/Models/Shared/` |
| Global audit log | `app/Models/Shared/ActivityLog.php` |
| Client model | `modules/ClientManagement/Models/Client.php` |
| Client service logic | `modules/ClientManagement/Services/Profile/` |
| Payroll calculation | `modules/Payroll/Services/Calculation/` |
| Shared phone validation | `app/Rules/Shared/BangladeshPhone.php` |
| Client status enum | `modules/ClientManagement/Enums/ClientStatus.php` |
| Gender enum (shared) | `app/Enums/Shared/Gender.php` |
| Middleware | `app/Http/Middleware/` (always global) |
| Module routes | `modules/*/routes/api.php` |
| Global scheduler | `app/Console/Commands/` |
| Module-specific command | `modules/*/Console/Commands/` |

---

**Stack:** Laravel 11 · PHP 8.2 · MySQL · Redis · Sanctum · Queue
**Pattern:** Modular + Repository + Service Layer + Event-Driven
