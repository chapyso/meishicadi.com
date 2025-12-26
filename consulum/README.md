# HOONA - Digital Business Card SaaS Platform

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Project Structure](#project-structure)
- [Key Features Breakdown](#key-features-breakdown)
- [Payment Gateways](#payment-gateways)
- [Themes](#themes)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Security](#security)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

---

## 🎯 Overview

HOONA is a comprehensive SaaS platform for creating and managing digital business cards (vCards). It enables users to create, customize, and share professional digital business cards with multiple themes, appointment scheduling, analytics, and various integrations.

The platform supports:
- Multiple business cards per user
- Custom themes and branding
- Appointment scheduling and calendar management
- Contact management and analytics
- QR code generation
- Social media integration
- Multi-language support
- Various payment gateways

---

## ✨ Features

### Core Features
- **Digital Business Cards**: Create unlimited digital business cards with custom themes
- **Multi-Theme Support**: 19+ professional card themes with customizable colors
- **Appointment Scheduling**: Integrated calendar system for booking appointments
- **Analytics Dashboard**: Track card views, contacts, and engagement metrics
- **QR Code Generation**: Generate QR codes for easy sharing
- **Contact Management**: Manage and organize contacts efficiently
- **Social Media Integration**: Link social media profiles and share cards
- **Custom Domain Support**: Use custom domains or subdomains for business cards
- **SEO Optimization**: Meta tags, keywords, and descriptions for better visibility

### Business Features
- **Products/Services**: Showcase products and services on business cards
- **Gallery**: Display images and media galleries
- **Testimonials**: Show customer testimonials and reviews
- **Business Hours**: Set and display business operating hours
- **Contact Forms**: Collect contact information through forms
- **vCard Export**: Export contacts in vCard (.vcf) format

### Admin Features
- **User Management**: Multi-user support with role-based permissions
- **Plan Management**: Subscription plans with features and limits
- **Coupon System**: Create and manage discount coupons
- **Email Templates**: Customizable email templates
- **System Settings**: Comprehensive system configuration
- **Analytics**: Track user activity and system statistics

---

## 🛠 Technology Stack

### Backend
- **Framework**: Laravel 9.x
- **PHP Version**: 8.0.2 or higher
- **Database**: MySQL 5.7+ / MariaDB
- **Cache**: Redis / File-based caching

### Frontend
- **JavaScript**: jQuery, Alpine.js, ApexCharts
- **CSS Framework**: Custom CSS with Tailwind CSS components
- **Build Tool**: Laravel Mix (Webpack)
- **Icons**: Tabler Icons, Feather Icons, Font Awesome, Material Icons

### Third-Party Integrations
- **Payment Gateways**: Stripe, PayPal, Paystack, Razorpay, Mollie, and 15+ more
- **Calendar**: Google Calendar integration
- **QR Code**: SimpleSoftwareIO QR Code
- **vCard**: JeroenDesloovere vCard library
- **AI**: OpenAI integration for templates
- **Analytics**: Visitor tracking and analytics

---

## 📦 Requirements

### Server Requirements
- PHP >= 8.0.2
- MySQL >= 5.7 or MariaDB >= 10.3
- Redis (optional, for caching)
- Composer
- Node.js & NPM (for asset compilation)
- Web Server (Apache/Nginx)

### PHP Extensions
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo
- GD or Imagick (for image processing)

---

## 🚀 Installation

### Step 1: Clone the Repository
```bash
git clone <repository-url> HOONA
cd HOONA
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
```

### Step 3: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE u916293666_vcardsaas;

# Import database (if you have SQL file)
mysql -u root -p u916293666_vcardsaas < u916293666_vcardsaas.20251029142911.sql

# Or run migrations
php artisan migrate
php artisan db:seed
```

### Step 5: Configure Environment
Edit `.env` file with your database credentials and other settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u916293666_vcardsaas
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 6: Storage Setup
```bash
# Create storage link
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
```

### Step 7: Build Assets
```bash
# Development
npm run dev

# Production
npm run prod
```

### Step 8: Run the Application
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## ⚙️ Configuration

### Application Settings
Configuration files are located in `config/` directory:
- `app.php` - Application configuration
- `database.php` - Database connections
- `mail.php` - Email settings
- `payment gateways` - Payment configurations

### Key Configuration Files
- **Database**: `config/database.php`
- **Mail**: `config/mail.php`
- **Payment Gateways**: `config/stripe.php`, `config/paypal.php`, etc.
- **Google Calendar**: `config/google-calendar.php`

### Environment Variables
Key environment variables in `.env`:
```env
APP_NAME=HOONA
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u916293666_vcardsaas
DB_USERNAME=root
DB_PASSWORD=

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Redis (optional)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025

# Payment Gateways (configure as needed)
STRIPE_KEY=
STRIPE_SECRET=
PAYPAL_CLIENT_ID=
PAYPAL_SECRET=
```

---

## 🗄 Database Setup

### Database Information
- **Primary Database**: MySQL/MariaDB
- **Database Name**: `u916293666_vcardsaas` (configurable)
- **Connection**: Single MySQL connection (default)

### Database Structure
Key tables include:
- `users` - User accounts
- `businesses` - Business card data
- `appoinments` - Appointment records
- `contacts` - Contact information
- `plans` - Subscription plans
- `plan_orders` - Plan purchase records
- `coupons` - Discount coupons
- `products` - Products/services
- `gallery` - Image galleries
- `testimonials` - Customer testimonials
- `business_hours` - Operating hours
- `social` - Social media links

### Running Migrations
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration (drops all tables)
php artisan migrate:fresh --seed
```

---

## 📁 Project Structure

```
HOONA/
├── app/
│   ├── Console/              # Artisan commands
│   ├── Exceptions/           # Exception handlers
│   ├── Exports/              # Data export classes
│   ├── Http/
│   │   ├── Controllers/      # Application controllers (59 controllers)
│   │   ├── Middleware/       # Custom middleware (11 middleware)
│   │   └── Requests/         # Form requests
│   ├── Mail/                 # Mail classes
│   ├── Models/               # Eloquent models (31 models)
│   ├── Providers/            # Service providers
│   ├── Services/             # Business logic services
│   └── Traits/               # Reusable traits
├── bootstrap/                # Framework bootstrap
├── config/                   # Configuration files
├── database/
│   ├── factories/           # Model factories
│   ├── migrations/           # Database migrations (53 migrations)
│   └── seeders/             # Database seeders (4 seeders)
├── public/                   # Public assets and entry point
│   ├── assets/              # Compiled assets
│   ├── custom/              # Custom themes and assets
│   └── card/                # Public card URLs
├── resources/
│   ├── views/               # Blade templates (117 views)
│   │   └── card/
│   │       └── theme19/     # Card theme templates
│   ├── lang/                # Language files (80 PHP, 16 JSON)
│   ├── css/                 # Source CSS
│   └── js/                  # Source JavaScript
├── routes/
│   ├── web.php              # Web routes
│   ├── api.php              # API routes
│   ├── auth.php             # Authentication routes
│   └── channels.php         # Broadcasting channels
├── storage/
│   ├── app/                 # Application files
│   ├── framework/           # Framework files
│   └── logs/                # Application logs
├── tests/                    # PHPUnit tests
├── vendor/                   # Composer dependencies
├── .env                      # Environment configuration
├── composer.json             # PHP dependencies
├── package.json              # NPM dependencies
└── artisan                   # Artisan command line tool
```

---

## 🎨 Key Features Breakdown

### 1. Business Card Management
- Create unlimited business cards per user
- Customizable themes and colors
- Logo and banner uploads
- SEO metadata configuration
- Custom domain/subdomain support
- QR code generation
- Shareable links

### 2. Appointment System
- Calendar view of appointments
- Appointment booking system
- Business hours configuration
- Email notifications
- Appointment notes and details
- Google Calendar integration

### 3. Analytics & Tracking
- Card view statistics
- Contact collection analytics
- Device tracking (mobile/desktop)
- Visitor tracking
- Engagement metrics
- Dashboard charts and graphs

### 4. Contact Management
- Contact form integration
- Contact database
- vCard export functionality
- Contact organization
- Search and filter contacts

### 5. Content Management
- **Products/Services**: Add products with images and descriptions
- **Gallery**: Image and media galleries
- **Testimonials**: Customer reviews and testimonials
- **Social Links**: Multiple social media profiles
- **Custom HTML**: Custom content blocks

### 6. User Management
- Multi-user support
- Role-based permissions
- User profiles
- Plan management
- User activity logs

---

## 💳 Payment Gateways

The platform supports 20+ payment gateways:

1. **Stripe** - Credit/debit cards
2. **PayPal** - PayPal payments
3. **Paystack** - African payment gateway
4. **Razorpay** - Indian payment gateway
5. **Mollie** - European payment gateway
6. **Flutterwave** - African payment gateway
7. **Paytm** - Indian payment gateway
8. **Skrill** - Digital wallet
9. **Coingate** - Cryptocurrency payments
10. **Mercado Pago** - Latin American gateway
11. **PaymentWall** - Global payments
12. **Toyyibpay** - Malaysian gateway
13. **Payfast** - South African gateway
14. **IyziPay** - Turkish gateway
15. **Paytabs** - Middle Eastern gateway
16. **Benefit** - Bahrain gateway
17. **Cashfree** - Indian gateway
18. **Aamarpay** - Bangladesh gateway
19. **Paytr** - Turkish gateway
20. **SS Pay** - Custom gateway
21. **Bank Transfer** - Manual payment

Each gateway can be configured in the respective config files and `.env`.

---

## 🎭 Themes

The platform includes 19+ professional card themes:
- Theme 1-19 (various designs)
- Custom theme builder
- Color customization
- RTL (Right-to-Left) support for Arabic/Hebrew
- Dark mode support
- Responsive designs

Theme files are located in:
- `resources/views/card/theme[1-19]/`
- Public theme assets in `public/custom/`

---

## 📖 Usage

### Creating a Business Card

1. **Login** to your account
2. Navigate to **Business Cards** → **Create New**
3. Fill in business information:
   - Title, Designation, Description
   - Logo and Banner images
   - Contact information
   - Social media links
4. **Choose Theme**: Select from 19 available themes
5. **Customize**: Adjust colors, layout, and content
6. **Configure Settings**:
   - Domain/subdomain
   - SEO settings
   - Business hours
   - Appointment settings
7. **Publish**: Save and share your card

### Managing Appointments

1. Go to **Appointments** section
2. View calendar or list view
3. Create new appointments
4. Manage existing appointments
5. Set business hours
6. Configure email notifications

### Analytics

1. Navigate to **Analytics** for a business card
2. View statistics:
   - Total views
   - Device breakdown
   - Contact collection
   - Engagement metrics
3. Export reports (if available)

---

## 🔌 API Documentation

### Authentication
The API uses Laravel Sanctum for authentication. Include the bearer token in requests:
```
Authorization: Bearer {token}
```

### Key Endpoints
- `GET /api/business/{id}` - Get business card details
- `POST /api/business` - Create business card
- `GET /api/appointments` - Get appointments
- `POST /api/appointments` - Create appointment

For complete API documentation, check the `routes/api.php` file.

---

## 🔒 Security

### Security Features
- CSRF protection
- XSS filtering middleware
- SQL injection prevention (Eloquent ORM)
- Authentication and authorization
- Role-based access control
- Secure file uploads
- Password hashing
- Session management

### Best Practices
- Keep Laravel and dependencies updated
- Use strong database passwords
- Configure HTTPS in production
- Regular security audits
- Backup database regularly
- Use environment variables for sensitive data

---

## 🐛 Troubleshooting

### Common Issues

**Issue**: Database connection error
- **Solution**: Check `.env` database credentials
- Verify MySQL service is running
- Ensure database exists

**Issue**: Permission denied errors
- **Solution**: Run `chmod -R 775 storage bootstrap/cache`
- Check file ownership

**Issue**: Assets not loading
- **Solution**: Run `php artisan storage:link`
- Run `npm run dev` or `npm run prod`
- Clear cache: `php artisan cache:clear`

**Issue**: Class not found errors
- **Solution**: Run `composer dump-autoload`
- Clear config cache: `php artisan config:clear`

**Issue**: Payment gateway errors
- **Solution**: Verify API keys in `.env`
- Check gateway configuration in `config/` directory
- Review gateway-specific documentation

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add comments for complex logic
- Test your changes thoroughly
- Update documentation as needed

---

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

## 📞 Support

For support and inquiries:
- Check the documentation
- Review existing issues
- Create a new issue if needed

---

## 🗺 Roadmap

Future enhancements may include:
- Additional themes
- Mobile app integration
- Advanced analytics
- More payment gateways
- Enhanced API features
- Multi-tenant improvements

---

## 📝 Changelog

### Version Information
- **Laravel Version**: 9.x
- **PHP Version**: 8.0.2+
- **Database**: MySQL 5.7+ / MariaDB 10.3+

---

**Last Updated**: 2025

**Version**: 1.0.0

---

*This documentation is maintained by the development team. For the latest updates, please check the repository.*
