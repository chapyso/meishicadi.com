# Dashboard System - Complete File Map & Architecture

## 📋 Table of Contents
1. [Controllers](#controllers)
2. [Models](#models)
3. [Routes & Endpoints](#routes--endpoints)
4. [Views & Templates](#views--templates)
5. [JavaScript & Chart Libraries](#javascript--chart-libraries)
6. [Middleware](#middleware)
7. [Database Tables](#database-tables)
8. [Data Flow Explanation](#data-flow-explanation)
9. [Chart Rendering Explanation](#chart-rendering-explanation)
10. [UI Structure Explanation](#ui-structure-explanation)

---

## 🎮 Controllers

### Primary Dashboard Controller
**File:** `app/Http/Controllers/HomeController.php`
- **Main Method:** `index()` - Handles both super admin and regular user dashboards
- **Chart Methods:**
  - `getOrderChart($arrParam)` - Generates appointment/visitor chart data for regular users
  - `getPlanOrderChart($arrParam)` - Generates plan order chart data for super admin
- **Business Switching:** `changeCurrantBusiness($business_id)` - Changes active business context

**Key Data Processing:**
- Aggregates visitor data (device, browser, platform) from `visitor` table
- Calculates appointment statistics per business
- Computes storage limits and plan information
- Filters all data by `created_by` (user's creatorId) for multi-tenant isolation

### Business Analytics Controller
**File:** `app/Http/Controllers/BusinessController.php`
- **Method:** `analytics($id)` - Shows analytics for a specific business card
- **Method:** `getOrderChart($arrParam, $id)` - Generates chart data filtered by business ID

---

## 📊 Models

### Core Models Used by Dashboard

1. **`app/Models/Business.php`**
   - Stores business card information
   - Used for: Total cards count, business selection dropdown, QR code display

2. **`app/Models/Appointment_deatail.php`**
   - Stores appointment records
   - Used for: Total appointments count, appointment chart data

3. **`app/Models/User.php`**
   - User management
   - Used for: Current user info, staff count, plan association, storage limits

4. **`app/Models/Plan.php`**
   - Subscription plans
   - Used for: Plan limits, storage calculations

5. **`app/Models/PlanOrder.php`**
   - Plan purchase orders (super admin dashboard)
   - Used for: Order statistics, revenue calculations

6. **`app/Models/Businessqr.php`**
   - QR code settings for businesses
   - Used for: QR code display on dashboard

7. **`app/Models/Utility.php`**
   - Utility functions and settings
   - Used for: Theme colors, file paths, system settings

### Visitor Tracking
- **Table:** `visitor` (direct DB queries, no model)
- **Package:** `shetabit/visitor` package
- **Model:** `vendor/shetabit/visitor/src/Models/Visit.php`
- **Migration:** `database/migrations/2021_10_28_114242_create_visits_table.php`

**Visitor Table Structure:**
- `device`, `platform`, `browser`, `ip`, `slug`, `created_by`, `created_at`

---

## 🛣️ Routes & Endpoints

### Main Dashboard Routes
**File:** `routes/web.php`

```php
// Main dashboard routes (lines 65-67)
Route::get('/home', [HomeController::class, 'index'])
    ->middleware('XSS', 'auth', 'CheckPlan')
    ->name('home');

Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware('XSS', 'auth', 'CheckPlan')
    ->name('dashboard');

Route::get('/dashboard/{id}', [HomeController::class, 'changeCurrantBusiness'])
    ->name('business.change');
```

### Business Analytics Route
```php
// Line 83
Route::get('business/analytics/{id}', [BusinessController::class, 'analytics'])
    ->name('business.analytics');
```

### Route Service Provider
**File:** `app/Providers/RouteServiceProvider.php`
- **Line 20:** `HOME = '/dashboard'` - Default redirect after login

---

## 🎨 Views & Templates

### Main Dashboard Views

1. **Regular User Dashboard**
   - **File:** `resources/views/dashboard/dashboard.blade.php`
   - **Layout:** Extends `layouts.admin`
   - **Sections:**
     - Welcome card with greeting
     - Business QR code display (if business selected)
     - Statistics cards (Total Cards, Appointments, Admins)
     - Appointments chart (ApexCharts area chart)
     - Platform chart (ApexCharts bar chart)
     - Browser chart (ApexCharts donut chart)
     - Device chart (ApexCharts donut chart)
     - Storage status chart (ApexCharts radial bar chart)

2. **Super Admin Dashboard**
   - **File:** `resources/views/dashboard/admin_dashboard.blade.php`
   - **Layout:** Extends `layouts.admin`
   - **Sections:**
     - Total Users card
     - Total Orders card
     - Total Plans card
     - Recent Order chart (ApexCharts area chart)

3. **Business Analytics View**
   - **File:** `resources/views/business/analytics.blade.php`
   - **Layout:** Extends `layouts.admin`
   - **Sections:** Similar charts but filtered by specific business

### Layout Structure

**Main Layout:** `resources/views/layouts/admin.blade.php`
- Includes header, sidebar, menu, and footer partials
- Theme color system (theme-1 through theme-9)
- RTL support
- Modal dialogs

**Partial Files:**
- `resources/views/partials/admin/header.blade.php` - CSS, meta tags, ApexCharts library
- `resources/views/partials/admin/sidemenu.blade.php` - Left navigation sidebar
- `resources/views/partials/admin/menu.blade.php` - Top menu bar
- `resources/views/partials/admin/footer.blade.php` - JavaScript includes

---

## 📜 JavaScript & Chart Libraries

### Chart Library
**Library:** ApexCharts
- **File:** `public/assets/js/plugins/apexcharts.min.js`
- **Included in:** `resources/views/partials/admin/footer.blade.php` (line 16)

### Purpose.js
**File:** `public/custom/js/purpose.js`
- Theme styling utilities
- Color palette definitions (`PurposeStyle.colors`)
- Font definitions (`PurposeStyle.fonts`)

### Chart Initialization Scripts

All charts are initialized inline in Blade templates using JavaScript:

1. **Appointments Chart** (Area Chart)
   - Location: `resources/views/dashboard/dashboard.blade.php` (lines 337-510)
   - Type: Area chart with smooth curve
   - Data: `$chartData['data']` (array of series with business names and appointment counts)
   - Custom legend with user avatars

2. **Platform Chart** (Bar Chart)
   - Location: `resources/views/dashboard/dashboard.blade.php` (lines 550-686)
   - Type: Bar chart
   - Data: `$platformarray['data']` and `$platformarray['label']`

3. **Browser Chart** (Donut Chart)
   - Location: `resources/views/dashboard/dashboard.blade.php` (lines 531-548)
   - Type: Donut chart
   - Data: `$browserarray['data']` and `$browserarray['label']`

4. **Device Chart** (Donut Chart)
   - Location: `resources/views/dashboard/dashboard.blade.php` (lines 512-529)
   - Type: Donut chart
   - Data: `$devicearray['data']` and `$devicearray['label']`

5. **Storage Chart** (Radial Bar Chart)
   - Location: `resources/views/dashboard/dashboard.blade.php` (lines 749-792)
   - Type: Radial bar chart
   - Data: `$storage_limit` (percentage)

---

## 🔒 Middleware

### CheckPlan Middleware
**File:** `app/Http/Middleware/CheckPlan.php`
- **Purpose:** Validates user's plan expiration
- **Logic:** 
  - Checks if user plan is expired
  - Redirects to plans page if expired (unless trial)
  - Applied to all dashboard routes

**Registration:** `app/Http/Kernel.php` (line 68)

### XSS Middleware
**File:** `app/Http/Middleware/XSS.php`
- **Purpose:** XSS protection
- Applied to all dashboard routes

### Auth Middleware
- Standard Laravel authentication
- Ensures user is logged in

---

## 🗄️ Database Tables

### Primary Tables Used

1. **`visitor`** (visitor tracking)
   - Columns: `id`, `device`, `platform`, `browser`, `ip`, `slug`, `created_by`, `created_at`
   - Used for: Analytics charts (device, browser, platform statistics)

2. **`appointment_deatails`** (appointments)
   - Used for: Appointment counts, appointment timeline charts
   - Filtered by: `business_id`, `created_by`

3. **`businesses`** (business cards)
   - Used for: Total cards count, business selection

4. **`users`** (user accounts)
   - Used for: Staff count, plan association, storage limits

5. **`plans`** (subscription plans)
   - Used for: Plan limits, storage calculations

6. **`plan_orders`** (plan purchases - super admin)
   - Used for: Order statistics, revenue

---

## 🔄 Data Flow Explanation

### How Dashboard Loads Data

1. **Route Hit:** User navigates to `/home` or `/dashboard`

2. **Middleware Execution:**
   - `verified` - Ensures email verified
   - `auth` - Ensures user authenticated
   - `XSS` - XSS protection
   - `CheckPlan` - Validates plan expiration

3. **Controller Processing** (`HomeController@index`):
   
   **For Regular Users:**
   - Queries `Business` model: Counts total businesses by `created_by`
   - Queries `Appointment_deatail` model: Counts total appointments by `created_by`
   - Queries `User` model: Counts total staff by `created_by`
   - Queries `visitor` table directly:
     - Groups by `device` → `$devicearray`
     - Groups by `browser` → `$browserarray`
     - Groups by `platform` → `$platformarray`
   - Calls `getOrderChart()` to generate appointment timeline data
   - Calculates storage limit percentage
   - Gets current business and QR code details
   - Passes all data to view via `compact()`

   **For Super Admin:**
   - Queries `User` model: Total users, paid users
   - Queries `PlanOrder` model: Total orders, order prices
   - Queries `Plan` model: Total plans, most purchased plan
   - Calls `getPlanOrderChart()` for order timeline
   - Passes data to admin dashboard view

4. **View Rendering:**
   - Blade template receives data variables
   - Charts are initialized with JavaScript using ApexCharts
   - Data is JSON-encoded and passed to chart options
   - Charts render client-side

### Data Filtering (Multi-Tenant)

**All queries are filtered by `created_by`:**
- `Business::where('created_by', \Auth::user()->creatorId())`
- `Appointment_deatail::where('created_by', \Auth::user()->creatorId())`
- `\DB::table('visitor')->where('created_by', \Auth::user()->creatorId())`

This ensures users only see their own data.

---

## 📊 Chart Rendering Explanation

### Chart Initialization Process

1. **Data Preparation (Server-Side):**
   - Controller queries database
   - Formats data into arrays:
     ```php
     $chartData = [
         'label' => ['25-Nov', '26-Nov', ...],
         'data' => [
             ['name' => 'Business 1', 'data' => [1, 2, 3, ...], 'avatar' => '...'],
             ['name' => 'Business 2', 'data' => [4, 5, 6, ...], 'avatar' => '...'],
         ]
     ];
     ```

2. **Data Passing to View:**
   - Data is passed via `compact()` to Blade template
   - Blade template JSON-encodes data: `{!! json_encode($chartData['data']) !!}`

3. **Client-Side Rendering:**
   - JavaScript creates ApexCharts instance:
     ```javascript
     var chart = new ApexCharts(document.querySelector("#apex-storedashborad"), options);
     chart.render();
     ```
   - Chart options include:
     - Series data (from PHP)
     - X-axis categories (from PHP)
     - Colors, styling (from PurposeStyle)
     - Chart type (area, bar, donut, radialBar)

4. **Custom Legend (Appointments Chart):**
   - After chart renders, custom JavaScript creates legend
   - Shows user avatars, color dots, and business names
   - Allows toggling series visibility

### Chart Types Used

- **Area Chart:** Appointments timeline (multiple series, one per business)
- **Bar Chart:** Platform statistics
- **Donut Charts:** Browser and Device statistics
- **Radial Bar Chart:** Storage usage percentage

---

## 🎨 UI Structure Explanation

### Layout Hierarchy

```
layouts/admin.blade.php (Main Layout)
├── partials/admin/header.blade.php
│   ├── CSS files
│   ├── Meta tags
│   └── ApexCharts library
├── partials/admin/sidemenu.blade.php (Left sidebar navigation)
├── partials/admin/menu.blade.php (Top menu bar)
├── @yield('content') ← Dashboard content injected here
└── partials/admin/footer.blade.php
    └── JavaScript files (purpose.js, etc.)
```

### Dashboard Content Structure

**Regular Dashboard** (`dashboard/dashboard.blade.php`):
```
Row 1: Welcome Card + Business QR Card + Stats Cards (Total Cards, Appointments, Admins)
Row 2: Appointments Chart (left) + Platform Chart (right)
Row 3: Browser Chart + Device Chart + Storage Chart
```

**Admin Dashboard** (`dashboard/admin_dashboard.blade.php`):
```
Row 1: Stats Cards (Total Users, Total Orders, Total Plans)
Row 2: Recent Order Chart
```

### Theme System

- **Theme Colors:** Defined in `Utility::colorset()`
- **Applied via:** `body` class (`theme-1` through `theme-9`)
- **CSS:** Theme-specific styles in main CSS files
- **PurposeStyle:** JavaScript color palette for charts

### Responsive Design

- Uses Bootstrap grid system (`col-md-`, `col-lg-`, etc.)
- Charts are responsive via ApexCharts options
- Mobile-friendly sidebar and menu

---

## ✅ Summary: Static vs Dynamic Data

### **Data Type: DYNAMIC/REAL-TIME**

- **Database Queries:** All data is queried fresh on each page load
- **No Caching:** No evidence of data caching
- **Real-time Updates:** Data reflects current database state
- **Time-based Filtering:** Charts show last 7 days (appointments) or last 15 days (analytics)

### **Dashboard Layout: REUSABLE**

- **Layout System:** Uses Laravel Blade `@extends` and `@yield`
- **Component-based:** Charts are reusable JavaScript functions
- **Template Inheritance:** All dashboards extend `layouts.admin`
- **Partial Views:** Header, sidebar, footer are reusable partials
- **Consistent Structure:** Same layout pattern across all dashboard views

---

## 📁 Complete File List

### Controllers
- ✅ `app/Http/Controllers/HomeController.php`
- ✅ `app/Http/Controllers/BusinessController.php` (analytics method)

### Models
- ✅ `app/Models/Business.php`
- ✅ `app/Models/Appointment_deatail.php`
- ✅ `app/Models/User.php`
- ✅ `app/Models/Plan.php`
- ✅ `app/Models/PlanOrder.php`
- ✅ `app/Models/Businessqr.php`
- ✅ `app/Models/Utility.php`

### Routes
- ✅ `routes/web.php` (lines 65-67, 83)

### Views
- ✅ `resources/views/dashboard/dashboard.blade.php` (Main user dashboard)
- ✅ `resources/views/dashboard/admin_dashboard.blade.php` (Super admin dashboard)
- ✅ `resources/views/business/analytics.blade.php` (Business-specific analytics)
- ✅ `resources/views/layouts/admin.blade.php` (Main layout)
- ✅ `resources/views/partials/admin/header.blade.php`
- ✅ `resources/views/partials/admin/sidemenu.blade.php`
- ✅ `resources/views/partials/admin/menu.blade.php`
- ✅ `resources/views/partials/admin/footer.blade.php`

### JavaScript Libraries
- ✅ `public/assets/js/plugins/apexcharts.min.js` (ApexCharts library)
- ✅ `public/custom/js/purpose.js` (Theme utilities)

### Middleware
- ✅ `app/Http/Middleware/CheckPlan.php`
- ✅ `app/Http/Middleware/XSS.php`
- ✅ `app/Http/Kernel.php` (middleware registration)

### Database
- ✅ `database/migrations/2021_10_28_114242_create_visits_table.php` (visitor table)
- ✅ Direct queries to `visitor` table (no model)

---

## 🔍 Key Insights

1. **Multi-Tenant Architecture:** All data is filtered by `created_by` ensuring tenant isolation
2. **No API Endpoints:** All data is loaded server-side, no AJAX calls for initial load
3. **Chart Library:** Uses ApexCharts (not Chart.js) for all visualizations
4. **Dynamic Data:** All statistics are calculated from database queries on each page load
5. **Reusable Layout:** Consistent layout system using Blade inheritance
6. **Business Context:** Dashboard can switch between businesses via `current_business` field
7. **Permission-Based:** Dashboard access controlled by `can('show dashboard')` permission

---

**Generated:** $(date)
**Project:** HOOLA BACKUP
**Framework:** Laravel
**Chart Library:** ApexCharts

