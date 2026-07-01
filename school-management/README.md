# 🕌 স্কুল ও মাদ্রাসা ম্যানেজমেন্ট সিস্টেম
### Laravel-based Full School & Madrasa Management Application

---

## 📦 ইনস্টলেশন গাইড (Step-by-Step)

### ধাপ ১ — প্রয়োজনীয় সফটওয়্যার
```
✅ PHP >= 8.2
✅ Composer
✅ MySQL 8.0 / MariaDB
✅ Node.js >= 18
```

---

### ধাপ ২ — প্রজেক্ট তৈরি করুন

```bash
# Laravel ইনস্টল করুন
composer create-project laravel/laravel school-management
cd school-management

# Auth (Login/Register) ইনস্টল
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build

# PDF জন্য
composer require barryvdh/laravel-dompdf

# Excel Export জন্য
composer require maatwebsite/excel

# এই প্রজেক্টের সব ফাইল কপি করুন
```

---

### ধাপ ৩ — Database তৈরি করুন

```sql
CREATE DATABASE school_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

### ধাপ ৪ — .env কনফিগার করুন

```env
APP_NAME="বিদ্যাপীঠ ম্যানেজমেন্ট"
APP_URL=http://localhost:8000

DB_DATABASE=school_management
DB_USERNAME=root
DB_PASSWORD=your_password

# প্রতিষ্ঠানের তথ্য
SCHOOL_NAME="আল-আমিন স্কুল ও মাদ্রাসা"
SCHOOL_ADDRESS="গ্রাম: ------, উপজেলা: ------, জেলা: ------"
SCHOOL_PHONE="01XXXXXXXXX"
SCHOOL_EMAIL="school@example.com"
```

---

### ধাপ ৫ — config/school.php তৈরি করুন

```php
<?php
return [
    'name'    => env('SCHOOL_NAME', 'বিদ্যাপীঠ'),
    'address' => env('SCHOOL_ADDRESS', ''),
    'phone'   => env('SCHOOL_PHONE', ''),
    'email'   => env('SCHOOL_EMAIL', ''),
];
```

---

### ধাপ ৬ — Migration ও Seeder চালান

```bash
# এই কমান্ডগুলো ক্রমানুসারে চালান:

# ১. প্রতিটি migration ফাইল আলাদা আলাদা ফাইলে সেভ করুন
#    (all_migrations.php এর প্রতিটি class আলাদা ফাইলে)

# ২. migrate করুন
php artisan migrate

# ৩. Seed করুন (demo data)
php artisan db:seed

# ৪. Storage link তৈরি করুন
php artisan storage:link
```

---

### ধাপ ৭ — সার্ভার চালু করুন

```bash
php artisan serve
```

🌐 Browser এ যান: `http://localhost:8000`

---

## 🔐 লগইন তথ্য (Demo)

| ভূমিকা        | ইমেইল                | পাসওয়ার্ড |
|--------------|---------------------|---------|
| প্রধান শিক্ষক | admin@school.com     | password |
| হিসাব রক্ষক  | accounts@school.com  | password |

---

## 📋 সম্পূর্ণ ফিচার তালিকা

### 🎓 ছাত্র ব্যবস্থাপনা
- ✅ নতুন ভর্তি ফর্ম (বিস্তারিত)
- ✅ ছাত্র তালিকা (ফিল্টার, সার্চ)
- ✅ ছাত্রের প্রোফাইল দেখুন
- ✅ আইডি কার্ড PDF প্রিন্ট
- ✅ শ্রেণী উন্নয়ন (Promotion)
- ✅ Excel Export

### 👩‍🏫 শিক্ষক ও স্টাফ
- ✅ শিক্ষক নিয়োগ ফর্ম
- ✅ শিক্ষক তালিকা
- ✅ প্রোফাইল ও বেতন ইতিহাস

### ✅ উপস্থিতি
- ✅ দৈনিক হাজিরা (উপস্থিত/অনুপস্থিত/দেরিতে/ছুটি)
- ✅ এক ক্লিকে সবাই উপস্থিত/অনুপস্থিত
- ✅ মাসিক উপস্থিতি রিপোর্ট
- ✅ শিক্ষকদের হাজিরা

### 💰 বেতন ও ফি
- ✅ ফি গ্রহণ (সার্চ করে ছাত্র বেছে নিন)
- ✅ স্বয়ংক্রিয় রশিদ নম্বর
- ✅ রশিদ PDF প্রিন্ট
- ✅ বকেয়া তালিকা
- ✅ ছাত্রের ফি ইতিহাস
- ✅ ফি ক্যাটাগরি ব্যবস্থাপনা
- ✅ মাসিক/বার্ষিক রিপোর্ট

### 📝 পরীক্ষা ও ফলাফল
- ✅ পরীক্ষা তৈরি
- ✅ বিষয়ওয়ারী নম্বর এন্ট্রি
- ✅ স্বয়ংক্রিয় গ্রেড হিসাব (A+, A, A-, B, C, D, F)
- ✅ GPA হিসাব
- ✅ মার্কশিট PDF
- ✅ মেধা তালিকা PDF

### 💵 বেতন প্রদান
- ✅ শিক্ষকদের বেতন পরিশোধ
- ✅ বোনাস ও কর্তন যোগ করুন
- ✅ একসাথে সব বেতন পরিশোধ
- ✅ বেতন স্লিপ PDF

### 📢 নোটিশ বোর্ড
- ✅ নোটিশ তৈরি ও প্রকাশ
- ✅ দর্শক নির্বাচন (সবাই/ছাত্র/শিক্ষক/অভিভাবক)

---

## 🗂️ ফাইল স্ট্রাকচার

```
app/
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── StudentController.php
│   ├── TeacherController.php
│   ├── AttendanceController.php
│   ├── FeeController.php
│   ├── ResultController.php
│   ├── SalaryController.php
│   ├── NoticeController.php
│   └── ApiController.php
├── Models/
│   ├── Student.php
│   ├── Teacher.php
│   ├── Classes.php
│   ├── Section.php
│   ├── Session.php
│   ├── Attendance.php
│   ├── FeeCategory.php
│   ├── FeeCollection.php
│   ├── Exam.php
│   ├── Subject.php
│   ├── Result.php
│   ├── SalaryPayment.php
│   └── Notice.php

database/migrations/
├── create_sessions_table.php
├── create_classes_table.php
├── create_teachers_table.php
├── create_sections_table.php
├── create_students_table.php
├── create_attendances_table.php
├── create_fee_categories_table.php
├── create_fee_collections_table.php
├── create_exams_table.php
├── create_subjects_table.php
├── create_results_table.php
├── create_salary_payments_table.php
└── create_notices_table.php

resources/views/
├── layouts/app.blade.php         ← Main layout
├── dashboard/index.blade.php
├── students/{index,form,show}.blade.php
├── teachers/{index,form,show}.blade.php
├── attendance/{index,monthly}.blade.php
├── fees/{index,receipt,ledger,due}.blade.php
├── exams/{index,form}.blade.php
├── results/{index,marksheet}.blade.php
├── salary/{index,slip}.blade.php
└── notices/{index,form}.blade.php
```

---

## 🔧 কাস্টমাইজেশন

### গ্রেডিং সিস্টেম পরিবর্তন
`app/Http/Controllers/ResultController.php` এ `calculateGrade()` মেথড সম্পাদনা করুন।

### নতুন ফি ক্যাটাগরি
Admin Panel > ফি ক্যাটাগরি থেকে সহজেই যোগ করুন।

### প্রতিষ্ঠানের তথ্য
`.env` ফাইলে `SCHOOL_NAME`, `SCHOOL_ADDRESS` ইত্যাদি পরিবর্তন করুন।

---

## 📞 সাহায্য দরকার?

আরও ফিচার যোগ করতে চাইলে:
- SMS নোটিফিকেশন (Twilio/SMSBD)
- Online Fee Payment (bKash/Nagad API)
- Parent Portal
- Mobile App API
- Biometric Attendance Integration

এই বিষয়গুলো যোগ করতে চাইলে জানান! 🚀
