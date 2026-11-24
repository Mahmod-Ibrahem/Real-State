# 🏠 Real Estate Application

A modern, full-stack real estate platform built with Laravel 11 and Vue 3, enabling users to buy, sell, and rent properties with a seamless and intuitive experience. The application is designed with scalability, performance, and clean architecture in mind, following SOLID principles and best practices.

---

## ✨ Key Features

### 🏡 **Property Management**
- **Comprehensive Property Listings** - Browse apartments, villas, houses, offices, and commercial spaces
- **Advanced Search & Filtering** - Filter by:
  - Property type (residential, commercial, etc.)
  - Property status (buy, rent, sell)
  - Location (country, city, area)
  - Property classification
  - Price range, bedrooms, bathrooms, square footage
- **Property Details** - Each listing includes:
  - Multiple high-quality images with gallery view
  - Detailed descriptions (multilingual support)
  - Property features and amenities
  - Floor plans
  - Google Maps integration for location
  - Year built, garage availability
- **Slug-based URLs** - SEO-friendly property URLs using Eloquent Sluggable
- **Related Properties** - Intelligent suggestions based on property type
- **Favorite Listings** - Save properties for later viewing

### 🌍 **Multi-language Support**
- **Full Internationalization** - Content available in multiple languages
- **Translatable Fields** - Property names, descriptions, addresses, and more
- **Dynamic Language Switching** - Users can switch languages on the fly
- **RTL Support** - Right-to-left layout support for Arabic and other RTL languages

### 🔐 **Authentication & Authorization**
- **Laravel Breeze** - Secure authentication system
- **Role-Based Access Control** - Using Spatie Laravel Permission
  - Super Admin role with full system access
  - User roles with appropriate permissions
- **Laravel Sanctum** - API authentication for secure requests

### 🎛️ **Admin Dashboard**
Complete administrative control panel for managing the entire platform:

#### Property Management
- Create, edit, and delete properties
- Manage property images (upload, delete multiple images)
- Set property status (published/unpublished)
- Assign property types, classifications, and features

#### Location Management
- **Countries** - Manage available countries
- **Cities** - Add and organize cities
- **Areas** - Define specific areas within cities

#### Property Configuration
- **Property Types** - Define types (apartment, villa, office, etc.)
- **Property Status** - Configure statuses (for sale, for rent, sold)
- **Property Classifications** - Set classifications (residential, commercial, etc.)
- **Features** - Manage property features (pool, gym, parking, etc.)
- **Feature Types** - Categorize features for better organization

#### Lead Management
- **Property Leads** - Track and manage property inquiries
- **Contact Leads** - Handle general contact form submissions
- **Client Inquiry System** - Centralized inquiry management

#### Content Management
- **FAQs** - Create and organize frequently asked questions
- **About Us, Privacy Policy, Terms of Service** - Editable pages

#### Dashboard Analytics
- View property statistics
- Monitor inquiries and leads
- Track user engagement

### 🎨 **Modern User Interface**
- **Vue 3 + Inertia.js** - Seamless SPA experience without API overhead
- **TailwindCSS** - Modern, responsive design
- **Headless UI custom inputs** - Accessible, Chatwoot-style admin controls (no PrimeVue)
- **Reka UI & Shadcn-style Components** - Beautiful, accessible components
- **Lucide Icons** - Modern icon set
- **Responsive Design** - Mobile-first approach
- **Image Optimization** - Using Intervention Image for processing
- **Swiper Integration** - Smooth image carousels
- **Vue Easy Lightbox** - Elegant image viewing experience
- **Vue Toastification** - User-friendly notifications
- **Vue Typewriter Effect** - Engaging text animations
- **Vue CountUp** - Animated statistics

### 🚀 **Performance Optimizations**
- **Smart Caching** - File-based caching for frequently accessed data
- **Eager Loading** - Prevents N+1 query problems
- **Service Layer Pattern** - Clean separation of business logic
- **Resource Classes** - Optimized API responses
- **Pagination** - Efficient data loading
- **Image Optimization** - Compressed and optimized images

### 📧 **Communication Features**
- **Contact Form** - Easy communication with property owners
- **Client Inquiry System** - Structured inquiry management
- **Email Integration** - Automated email notifications
- **Lead Tracking** - Monitor all customer interactions

### 🔍 **SEO Optimization**
- **Meta Tags** - Dynamic meta titles and descriptions for each page
- **SEO-Friendly URLs** - Clean, readable URLs with slugs
- **Sitemap Ready** - Structured for search engine indexing
- **Optimized Content** - Proper heading structure and semantic HTML

### 🧪 **Testing & Quality**
- **Laravel Dusk** - Browser automation testing
- **PHPUnit** - Unit and feature testing
- **Laravel Pint** - Code style enforcement
- **Faker** - Test data generation

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend Framework** | Laravel 11 |
| **PHP Version** | PHP 8.2+ |
| **Frontend Framework** | Vue 3 |
| **UI Framework** | TailwindCSS |
| **UI Components** | Headless UI, Reka UI, Shadcn-style |
| **Icons** | Lucide, Heroicons, Feather Icons |
| **SPA Integration** | Inertia.js |
| **Database** | MySQL |
| **Image Processing** | Intervention Image |
| **Permissions** | Spatie Laravel Permission |
| **Translations** | Spatie Laravel Translatable |
| **Slugs** | Eloquent Sluggable |
| **API Authentication** | Laravel Sanctum |
| **Routing** | Ziggy (Laravel routes in JavaScript) |
| **Build Tool** | Vite |
| **Testing** | Laravel Dusk, PHPUnit |
| **Code Quality** | Laravel Pint |

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- MySQL database

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Mahmod-Ibrahem/RealState-App.git
   cd RealState-App
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   ```
   
   Update your `.env` file with database credentials and other configurations:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```
   
   Or for development:
   ```bash
   npm run dev
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

### Quick Development Start

For a streamlined development experience with all services running:
```bash
composer dev
```

This command starts:
- Laravel development server
- Queue worker
- Log viewer (Pail)
- Vite dev server

---

## 📁 Project Structure

```
RealState-App/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin dashboard controllers
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   └── ...             # Public-facing controllers
│   │   └── Resources/          # API resources
│   ├── Models/                 # Eloquent models
│   └── Services/               # Business logic services
├── database/
│   ├── migrations/             # Database migrations
│   ├── seeders/                # Database seeders
│   └── factories/              # Model factories
├── resources/
│   ├── js/
│   │   ├── Components/         # Vue components
│   │   └── Pages/              # Inertia pages
│   └── css/                    # Stylesheets
├── routes/
│   ├── web.php                 # Web routes
│   └── auth.php                # Authentication routes
└── public/                     # Public assets
```

---

## 🔑 Key Modules

### 1. **Property Management System**
- CRUD operations for properties
- Image gallery management
- Feature assignment
- Location-based organization

### 2. **Multi-language System**
- Dynamic language switching
- Translatable database fields
- Locale-based routing

### 3. **Admin Dashboard**
- Comprehensive property management
- Lead and inquiry tracking
- Content management system
- User and role management

### 4. **Search & Filter Engine**
- Advanced filtering options
- Real-time search
- Location-based filtering
- Price range filtering

### 5. **Lead Management**
- Property inquiry tracking
- Contact form submissions
- Client communication history

### 6. **SEO & Performance**
- Caching strategies
- Query optimization
- SEO-friendly URLs
- Meta tag management

---

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

Run browser tests with Dusk:
```bash
php artisan dusk
```

---

## 👨‍💻 Author

**Mahmoud Ibrahim**


