# Database Seeders Documentation

## Overview

This application seeds the database with roles, permissions, users, categories, courses, exams, and questions. The seeding process creates a complete working system with sample data.

---

## Seeding Order

The `DatabaseSeeder` runs seeders in this order:

1. **RolePermissionSeeder** - Creates roles and permissions
2. **UserSeeder** - Creates users with assigned roles
3. **CatSeeder** - Creates categories, courses, exams, and questions

---

## 1. Roles & Permissions Seeder

### Roles with Granted Permissions

#### **Admin** (`admin`)

-   **Display Name:** Administrator
-   **Description:** Full system access with all permissions
-   **Seeded Users:** 1 (admin@example.com)
-   **Total Permissions:** 26

**Granted Permissions:**

**Exam Management (6):**

-   ✅ `create-exam` - Create new exams
-   ✅ `edit-exam` - Edit existing exams
-   ✅ `delete-exam` - Delete exams
-   ✅ `view-exam` - View exam details
-   ✅ `publish-exam` - Publish exams to students
-   ✅ `archive-exam` - Archive old exams

**Question Management (6):**

-   ✅ `create-question` - Create new questions
-   ✅ `edit-question` - Edit existing questions
-   ✅ `delete-question` - Delete questions
-   ✅ `view-question` - View question details
-   ✅ `import-questions` - Import questions from files
-   ✅ `export-questions` - Export questions to files

**Exam Taking (3):**

-   ✅ `take-exam` - Take available exams
-   ✅ `view-own-results` - View own exam results
-   ✅ `retake-exam` - Retake exams if allowed

**Results Management (4):**

-   ✅ `view-all-results` - View all student results
-   ✅ `view-student-results` - View results of assigned students
-   ✅ `grade-exam` - Grade exam submissions
-   ✅ `export-results` - Export exam results

**User Management (3):**

-   ✅ `manage-users` - Create, edit, and delete users
-   ✅ `view-users` - View user list
-   ✅ `assign-roles` - Assign roles to users

**System Settings (3):**

-   ✅ `manage-settings` - Manage system settings
-   ✅ `view-reports` - View system reports
-   ✅ `manage-categories` - Manage question categories

---

#### **Instructor** (`instructor`)

-   **Display Name:** Instructor
-   **Description:** Can manage exams, questions, and view student results
-   **Seeded Users:** 3 (instructor1@example.com, instructor2@example.com, instructor3@example.com)
-   **Total Permissions:** 18

**Granted Permissions:**

**Exam Management (6):**

-   ✅ `create-exam` - Create new exams
-   ✅ `edit-exam` - Edit existing exams
-   ✅ `delete-exam` - Delete exams
-   ✅ `view-exam` - View exam details
-   ✅ `publish-exam` - Publish exams to students
-   ✅ `archive-exam` - Archive old exams

**Question Management (6):**

-   ✅ `create-question` - Create new questions
-   ✅ `edit-question` - Edit existing questions
-   ✅ `delete-question` - Delete questions
-   ✅ `view-question` - View question details
-   ✅ `import-questions` - Import questions from files
-   ✅ `export-questions` - Export questions to files

**Results Management (4):**

-   ✅ `view-all-results` - View all student results
-   ✅ `view-student-results` - View results of assigned students
-   ✅ `grade-exam` - Grade exam submissions
-   ✅ `export-results` - Export exam results

**System (2):**

-   ✅ `view-reports` - View system reports
-   ✅ `manage-categories` - Manage question categories

**Denied Permissions:**

-   ❌ Exam Taking (cannot take exams, view own results, or retake exams)
-   ❌ User Management (cannot manage users, view users, or assign roles)
-   ❌ `manage-settings` - Cannot manage system settings

---

#### **Student** (`student`)

-   **Display Name:** Student
-   **Description:** Can take exams and view own results
-   **Seeded Users:** 25+ students
-   **Total Permissions:** 4

**Granted Permissions:**

**Exam Taking (3):**

-   ✅ `take-exam` - Take available exams
-   ✅ `view-own-results` - View own exam results
-   ✅ `retake-exam` - Retake exams if allowed

**View Only (1):**

-   ✅ `view-exam` - View exam details

**Denied Permissions:**

-   ❌ Exam Management (cannot create, edit, delete, publish, or archive exams)
-   ❌ Question Management (cannot create, edit, delete, view, import, or export questions)
-   ❌ Results Management (cannot view all results, view student results, grade exams, or export results)
-   ❌ User Management (cannot manage users, view users, or assign roles)
-   ❌ System Settings (cannot manage settings, view reports, or manage categories)

---

#### **Moderator** (`moderator`)

-   **Display Name:** Moderator
-   **Description:** Can manage content and view reports
-   **Seeded Users:** 2 (moderator1@example.com, moderator2@example.com)
-   **Total Permissions:** 10

**Granted Permissions:**

**Exam Management (3):**

-   ✅ `view-exam` - View exam details
-   ✅ `publish-exam` - Publish exams to students
-   ✅ `archive-exam` - Archive old exams

**Question Management (2):**

-   ✅ `view-question` - View question details
-   ✅ `edit-question` - Edit existing questions

**Results Management (2):**

-   ✅ `view-all-results` - View all student results
-   ✅ `view-student-results` - View results of assigned students

**User Management (1):**

-   ✅ `view-users` - View user list

**System (2):**

-   ✅ `view-reports` - View system reports
-   ✅ `manage-categories` - Manage question categories

**Denied Permissions:**

-   ❌ `create-exam`, `edit-exam`, `delete-exam` - Cannot create, edit, or delete exams
-   ❌ `create-question`, `delete-question` - Cannot create or delete questions
-   ❌ `import-questions`, `export-questions` - Cannot import or export questions
-   ❌ Exam Taking (cannot take exams, view own results, or retake exams)
-   ❌ `grade-exam`, `export-results` - Cannot grade or export results
-   ❌ `manage-users`, `assign-roles` - Cannot manage or assign roles to users
-   ❌ `manage-settings` - Cannot manage system settings

---

---

## 2. User Seeder

### Seeded User Accounts

#### Admin (1 user)

-   **Name:** Admin User
-   **Username:** admin
-   **Email:** admin@example.com
-   **Phone:** +1234567890
-   **Password:** password
-   **Role:** Admin

#### Instructors (3 users)

1. **John Instructor** - john.instructor / instructor1@example.com
2. **Sarah Teacher** - sarah.teacher / instructor2@example.com
3. **Michael Professor** - michael.professor / instructor3@example.com

-   **Password:** password (for all)
-   **Role:** Instructor

#### Moderators (2 users)

1. **Mike Moderator** - mike.moderator / moderator1@example.com
2. **Lisa Moderator** - lisa.moderator / moderator2@example.com

-   **Password:** password (for all)
-   **Role:** Moderator

#### Students (25+ users)

Named students (10):

1. Alice Johnson - alice.johnson@example.com
2. Bob Smith - bob.smith@example.com
3. Charlie Brown - charlie.brown@example.com
4. Diana Williams - diana.williams@example.com
5. Eve Davis - eve.davis@example.com
6. Frank Miller - frank.miller@example.com
7. Grace Wilson - grace.wilson@example.com
8. Henry Moore - henry.moore@example.com
9. Ivy Taylor - ivy.taylor@example.com
10. Jack Anderson - jack.anderson@example.com

Additional students: student11@example.com through student25@example.com

-   **Password:** password (for all)
-   **Role:** Student

### User Features

-   All users have verified email addresses
-   Prevents duplicate users by checking existing emails
-   Ensures minimum of 25 students are created

---

## 3. Category, Course, Exam, and Question Seeder

### Categories (3)

The seeder creates 3 main categories with bilingual names (English/Arabic):

1. **Programming** (البرمجة)
2. **Design** (التصميم)
3. **Marketing** (التسويق)

**Cat Model Features:**

-   Uses `HasTranslations` trait for multi-language support
-   Translatable field: `name`
-   Has many `courses` relationship
-   Active/inactive status flag
-   Scope: `active()` to filter active categories

---

### Courses (24 total - 8 per category)

Each category contains 8 courses with bilingual names:

**Available Courses:**

-   Web Development (تطوير الويب)
-   Mobile Apps (تطبيقات الجوال)
-   Graphic Design (التصميم الجرافيكي)
-   UI/UX Design (تصميم واجهات المستخدم)
-   Digital Marketing (التسويق الرقمي)
-   SEO (تحسين محركات البحث)
-   Content Writing (كتابة المحتوى)
-   Video Editing (مونتاج الفيديو)

**Course Model Features:**

-   Uses `HasTranslations` trait for multi-language support
-   Translatable field: `name`
-   Belongs to `Cat` (category)
-   Has many `exams` relationship
-   Image path stored in `img` field (e.g., `courses/1.png`)
-   Active/inactive status flag
-   Scope: `active()` to filter active courses
-   Method: `getStudentsCount()` - calculates total enrolled students across all exams

---

### Exams (48 total - 2 per course)

Each course contains 2 exams with varying difficulty and duration:

**Exam Types:**

1. **Fundamentals** (الأساسيات)
2. **Advanced Concepts** (المفاهيم المتقدمة)

**Exam Specifications:**

-   **Questions:** 15 questions per exam
-   **Difficulty:** Random (1-5 scale)
-   **Duration:** Random (30, 60, or 90 minutes)
-   **Description:** Bilingual detailed exam description
-   **Image:** Path stored (e.g., `exams/1.png`)

**Exam Model Features:**

-   Uses `HasTranslations` trait for multi-language support
-   Translatable fields: `name`, `desc`
-   Belongs to `Course`
-   Has many `questions` relationship
-   BelongsToMany `users` with pivot fields: `score`, `time_mins`, `status`
-   Active/inactive status flag
-   Tracks number of questions, difficulty level, and duration

---

### Questions (720 total - 15 per exam)

Each exam contains 15 multiple-choice questions with 4 options each.

**Question Structure:**

-   **Title:** Bilingual question text
-   **Options:** 4 choices (option_1, option_2, option_3, option_4) - all bilingual
-   **Correct Answer:** Random selection (1-4)

**Sample Question Templates:**

1. "What is the correct syntax for this concept?"
2. "Which statement best describes this process?"
3. "How would you implement this feature?"

**Question Model Features:**

-   Uses `HasTranslations` trait for multi-language support
-   Translatable fields: `title`, `option_1`, `option_2`, `option_3`, `option_4`
-   Belongs to `Exam`
-   Stores correct answer as integer (1-4)
-   Each option is stored separately with bilingual support

---

### Exam Management (6 permissions)

| Permission   | Key            | Description               | Admin | Instructor | Student | Moderator |
| ------------ | -------------- | ------------------------- | ----- | ---------- | ------- | --------- |
| Create Exam  | `create-exam`  | Create new exams          | ✅    | ✅         | ❌      | ❌        |
| Edit Exam    | `edit-exam`    | Edit existing exams       | ✅    | ✅         | ❌      | ❌        |
| Delete Exam  | `delete-exam`  | Delete exams              | ✅    | ✅         | ❌      | ❌        |
| View Exam    | `view-exam`    | View exam details         | ✅    | ✅         | ✅      | ✅        |
| Publish Exam | `publish-exam` | Publish exams to students | ✅    | ✅         | ❌      | ✅        |
| Archive Exam | `archive-exam` | Archive old exams         | ✅    | ✅         | ❌      | ✅        |

### Question Management (6 permissions)

| Permission       | Key                | Description                 | Admin | Instructor | Student | Moderator |
| ---------------- | ------------------ | --------------------------- | ----- | ---------- | ------- | --------- |
| Create Question  | `create-question`  | Create new questions        | ✅    | ✅         | ❌      | ❌        |
| Edit Question    | `edit-question`    | Edit existing questions     | ✅    | ✅         | ❌      | ✅        |
| Delete Question  | `delete-question`  | Delete questions            | ✅    | ✅         | ❌      | ❌        |
| View Question    | `view-question`    | View question details       | ✅    | ✅         | ❌      | ✅        |
| Import Questions | `import-questions` | Import questions from files | ✅    | ✅         | ❌      | ❌        |
| Export Questions | `export-questions` | Export questions to files   | ✅    | ✅         | ❌      | ❌        |

### Exam Taking (3 permissions)

| Permission       | Key                | Description             | Admin | Instructor | Student | Moderator |
| ---------------- | ------------------ | ----------------------- | ----- | ---------- | ------- | --------- |
| Take Exam        | `take-exam`        | Take available exams    | ✅    | ❌         | ✅      | ❌        |
| View Own Results | `view-own-results` | View own exam results   | ✅    | ❌         | ✅      | ❌        |
| Retake Exam      | `retake-exam`      | Retake exams if allowed | ✅    | ❌         | ✅      | ❌        |

### Results Management (4 permissions)

| Permission           | Key                    | Description                       | Admin | Instructor | Student | Moderator |
| -------------------- | ---------------------- | --------------------------------- | ----- | ---------- | ------- | --------- |
| View All Results     | `view-all-results`     | View all student results          | ✅    | ✅         | ❌      | ✅        |
| View Student Results | `view-student-results` | View results of assigned students | ✅    | ✅         | ❌      | ✅        |
| Grade Exam           | `grade-exam`           | Grade exam submissions            | ✅    | ✅         | ❌      | ❌        |
| Export Results       | `export-results`       | Export exam results               | ✅    | ✅         | ❌      | ❌        |

### User Management (3 permissions)

| Permission   | Key            | Description                    | Admin | Instructor | Student | Moderator |
| ------------ | -------------- | ------------------------------ | ----- | ---------- | ------- | --------- |
| Manage Users | `manage-users` | Create, edit, and delete users | ✅    | ❌         | ❌      | ❌        |
| View Users   | `view-users`   | View user list                 | ✅    | ❌         | ❌      | ✅        |
| Assign Roles | `assign-roles` | Assign roles to users          | ✅    | ❌         | ❌      | ❌        |

### System Settings (3 permissions)

| Permission        | Key                 | Description                | Admin | Instructor | Student | Moderator |
| ----------------- | ------------------- | -------------------------- | ----- | ---------- | ------- | --------- |
| Manage Settings   | `manage-settings`   | Manage system settings     | ✅    | ❌         | ❌      | ❌        |
| View Reports      | `view-reports`      | View system reports        | ✅    | ✅         | ❌      | ✅        |
| Manage Categories | `manage-categories` | Manage question categories | ✅    | ✅         | ❌      | ✅        |

---

## Summary Statistics

After running all seeders, the database will contain:

-   **4 Roles** (Admin, Instructor, Student, Moderator)
-   **26 Permissions** across 6 categories
-   **31+ Users** (1 admin, 3 instructors, 2 moderators, 25+ students)
-   **3 Categories** (Programming, Design, Marketing)
-   **24 Courses** (8 per category)
-   **48 Exams** (2 per course)
-   **720 Questions** (15 per exam)

All content is **bilingual** (English/Arabic) for categories, courses, exams, and questions.

---

## Notes

-   All roles and permissions use the `api` guard
-   Default password for all seeded users: `password`
-   All seeded users have verified email addresses
-   Seeders prevent duplicate entries by checking existing data
-   Permission cache is automatically cleared during role/permission seeding
-   Images are referenced but not created (you need to provide actual image files)

---

---

## Permissions Breakdown
