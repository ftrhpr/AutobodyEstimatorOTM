# Auto Damage Assessment Platform

A web application for managing vehicle damage reports and assessments. Users can submit damage reports with photos, and administrators can provide detailed repair cost assessments.

## Features

### User Features
- Phone-based registration with SMS OTP verification
- Secure login with password
- Vehicle management (add/edit/delete vehicles)
- Submit damage reports with multiple photos
- Track report status (Pending, Under Review, Assessed, Closed)
- View detailed assessments with itemized costs
- Download assessment as PDF

### Admin Features
- Secure admin login
- Dashboard with statistics
- View and manage all reports
- Photo gallery with zoom
- Create/update assessments with itemized repair costs
- User management (view/block users)
- SMS notifications to users

## Requirements

- PHP 8.0 or higher
- MySQL 8.x
- Apache/Nginx web server
- PHP Extensions: pdo, pdo_mysql, curl, gd, mbstring, json

## Installation

### Quick Install

1. Upload all files to your web server
2. Navigate to `http://yourdomain.com/install.php`
3. Fill in database credentials and admin account details
4. Click "Install Application"
5. Delete `install.php` after installation

### Manual Install

1. Create a MySQL database
2. Import `database/schema.sql`
3. Copy `app/config/config.php` and update database credentials
4. Configure SMS provider settings (Twilio, MessageBird, or Magti)
5. Ensure write permissions on:
   - `public_html/uploads/`
   - `storage/logs/`
   - `storage/cache/`
   - `storage/sessions/`

## Configuration

### Database
Edit `app/config/config.php`:
```php
'database' => [
    'host' => 'localhost',
    'database' => 'auto_damage_db',
    'username' => 'your_username',
    'password' => 'your_password',
],
```

### SMS Provider
Configure your SMS provider in `app/config/config.php`:
```php
'sms' => [
    'provider' => 'twilio', // twilio, messagebird, or magti
    'twilio' => [
        'sid' => 'YOUR_TWILIO_SID',
        'token' => 'YOUR_TWILIO_TOKEN',
        'from' => '+1234567890',
    ],
],
```

## Directory Structure

```
/
├── app/
│   ├── config/         # Configuration files
│   ├── controllers/    # Controller classes
│   ├── helpers/        # Helper classes (Router, Session, etc.)
│   ├── lang/           # Language files (Georgian, English)
│   ├── models/         # Database models
│   └── views/          # View templates
├── assets/             # CSS, JS, images
├── database/
│   └── schema.sql      # Database schema
├── storage/
│   ├── cache/
│   ├── logs/
│   └── sessions/
├── uploads/            # User uploads
├── index.php           # Entry point
└── .htaccess           # URL rewriting
```

## Default Admin Credentials

After installation, you can login to admin panel at `/admin/login`:
- Username: `admin`
- Password: (set during installation)

**Important:** Change the default password immediately after installation.

## Localization

The application supports:
- Georgian (ქართული) - default
- English

Users can switch languages using the language dropdown in the navigation bar.

## Security Features

- Password hashing with bcrypt
- CSRF protection on all forms
- Prepared statements for database queries
- Input validation and sanitization
- Rate limiting on SMS sending
- Image upload validation
- Secure session management

## cPanel Deployment

1. Upload files via File Manager or FTP
2. Set `public_html` as your document root
3. Create MySQL database via cPanel
4. Run the installer or import schema manually
5. Update configuration with your credentials

## License

This project is proprietary software. All rights reserved.

## Support

For support and bug reports, please contact the development team.
