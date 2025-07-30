# Landing Lens API - AI Object Detection Platform

A modern Laravel application that integrates with LandingLens AI for intelligent object detection and analysis. Upload images, get AI-powered insights, and visualize results with professional bounding box overlays.

## 🚀 Features

- ✅ **Laravel 10** with PHP 8.1+
- ✅ **Laravel Breeze Authentication** with stunning Bootstrap 5 UI
- ✅ **LandingLens AI Integration** for advanced object detection
- ✅ **Professional Drag & Drop Upload** with real-time validation
- ✅ **Visual Results** with color-coded bounding boxes and confidence scores
- ✅ **Responsive Design** - works perfectly on desktop and mobile
- ✅ **Image Gallery** with GLightbox for full-screen viewing
- ✅ **Analysis History** - track all your AI predictions
- ✅ **REST API** for third-party integrations
- ✅ **AJAX Processing** - no page refreshes, smooth UX
- ✅ **Database Storage** - save originals and processed images

## 📋 System Requirements

- **PHP 8.1** or higher
- **MySQL 5.7** or higher (or MariaDB 10.3+)
- **Composer** (latest version)
- **Apache** or **Nginx** web server
- **GD Extension** for image processing
- **Internet connection** for LandingLens API
- **LandingLens API credentials** (get free account at landing.ai)

## ⚡ Quick Setup (One Command)

For Ubuntu/Debian servers with PHP 8.1+ and MySQL already installed:

```bash
# Clone, install, and configure in one go
git clone <repository-url> landing-lens-api && cd landing-lens-api && composer install && cp .env.example .env && php artisan key:generate && php artisan storage:link && echo "✅ Basic setup complete! Now edit .env with your database and API credentials, then run: php artisan migrate"
```

## 🚀 Detailed Installation

### 1. Clone or Download
```bash
# Clone the repository
git clone <repository-url> landing-lens-api
cd landing-lens-api

# OR download and extract ZIP file
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
# Copy environment configuration
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Edit `.env` file with your database credentials:
```env
# Database Settings
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=landing_lens_api
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Application Settings
APP_NAME="Landing Lens API"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain.com
```

### 5. LandingLens API Configuration
Add your LandingLens credentials to `.env`:
```env
LANDINGLENS_API_KEY=your_actual_api_key
LANDINGLENS_ENDPOINT_ID=your_actual_endpoint_id
```
> **Get your API key**: Sign up at [landing.ai](https://landing.ai/) for free

### 6. Database Setup
```bash
# Create database tables
php artisan migrate

# Link storage for file uploads
php artisan storage:link
```

### 7. Set Permissions
```bash
# Make storage and bootstrap writable
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# If using Apache, you may need:
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

## 🌐 Web Server Configuration

### Apache Setup
Create a virtual host configuration:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/landing-lens-api/public
    
    <Directory /path/to/landing-lens-api/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/landing-lens-error.log
    CustomLog ${APACHE_LOG_DIR}/landing-lens-access.log combined
</VirtualHost>
```

### Nginx Setup
Add this server block to your Nginx configuration:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/landing-lens-api/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🎯 Getting Started

### 1. Access Your Application
- Open your web browser
- Navigate to your configured domain (e.g., `http://your-domain.com`)
- You'll see the landing page

### 2. Create Your Account
1. Click **"Register"** in the top navigation
2. Fill in your details (name, email, password)
3. Click **"Register"** button
4. You'll be automatically logged in

### 3. Start Analyzing Images
1. Navigate to **"Analyze Image"** from the navigation menu
2. **Drag & drop** an image or **click to browse**
3. Supported formats: JPEG, PNG, JPG (max 10MB)
4. Click **"Analyze Image with LandingLens"**
5. View AI results with bounding boxes and confidence scores

### 4. View Your History
- Click **"History"** to see all your previous analyses
- Click on any thumbnail to view full-size images
- Track your usage statistics

## 📁 Project Structure

```
landing-lens-api/
├── app/
│   ├── Http/Controllers/
│   │   ├── PredictController.php           # Web prediction controller
│   │   └── Api/PredictApiController.php    # REST API controller
│   ├── Models/
│   │   ├── ImageAnalysis.php              # Image analysis model
│   │   └── DetectedObject.php             # Detected object model
│   └── Services/
│       └── LandingLensService.php          # LandingLens API integration
├── database/migrations/                    # Database schema
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php                  # Bootstrap 5 main layout
│   │   └── navigation.blade.php            # Professional navbar
│   ├── predict/
│   │   ├── index.blade.php                # Drag & drop upload form
│   │   ├── result.blade.php               # AI results display
│   │   └── history.blade.php              # Analysis history
│   └── dashboard.blade.php                 # Enhanced dashboard
├── routes/
│   ├── web.php                            # Web routes with auth
│   └── api.php                            # REST API routes
├── config/
│   └── services.php                       # LandingLens configuration
└── storage/app/public/uploads/             # Image storage directory
```

## 🔑 Key Features

### 🎨 Modern User Interface
- **Professional Bootstrap 5** design with custom styling
- **Responsive** mobile-friendly layout
- **Drag & Drop** file upload with visual feedback
- **GLightbox** integration for full-screen image viewing
- **Font Awesome** icons throughout
- **Animated** processing indicators and smooth transitions

### 🤖 AI Integration
- **LandingLens API** for advanced object detection
- **Real-time processing** with AJAX (no page refreshes)
- **Visual results** with color-coded bounding boxes
- **Confidence scores** for each detected object
- **Multiple object detection** in single images

### 💾 Data Management
- **Database storage** for all analyses and results
- **Image history** with thumbnails and metadata
- **User statistics** tracking
- **Original + processed** image storage
- **Automatic cleanup** of temporary files

### 🔐 Security & Authentication
- **Laravel Breeze** authentication system
- **Route protection** with auth middleware
- **File upload validation** (type, size, security)
- **CSRF protection** on all forms
- **Secure file storage** with proper permissions

### 🌐 API Integration
- **REST API endpoints** for third-party integration
- **JSON responses** with standardized error handling
- **Public and authenticated** endpoints
- **Rate limiting** and security headers

## 🔗 REST API Documentation

The application provides a REST API for third-party integrations:

### Base URL
```
https://your-domain.com/api/v1
```

### Endpoints

#### 1. Upload & Analyze Image
```http
POST /predict
Content-Type: multipart/form-data

Parameters:
- file: image file (required)
- email: user email (required)
```

**Example Response:**
```json
{
  "success": true,
  "message": "Image analyzed successfully",
  "data": {
    "id": 123,
    "original_image_url": "https://your-domain.com/storage/uploads/original_image.jpg",
    "processed_image_url": "https://your-domain.com/storage/uploads/processed_image.jpg",
    "objects_detected": 3,
    "status": "completed",
    "detected_objects": [
      {
        "label": "person",
        "confidence": 0.95,
        "x": 100,
        "y": 150,
        "width": 200,
        "height": 300
      }
    ]
  }
}
```

#### 2. Get Analysis Results
```http
GET /predict/{id}
```

#### 3. List Recent Analyses
```http
GET /predict
```

#### 4. User's Analyses (Authenticated)
```http
GET /my-analyses
Authorization: Bearer {token}
```

## 🛠️ Development & Maintenance

### Enable Debug Mode
```bash
# Edit .env file for development
APP_DEBUG=true
APP_ENV=local
```

### View Application Logs
```bash
tail -f storage/logs/laravel.log
```

### Clear Application Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

## 🚨 Troubleshooting

### Common Issues & Solutions

#### 1. Permission Errors
```bash
# Fix storage permissions
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
```

#### 2. Composer/Class Not Found
```bash
composer dump-autoload
composer install --optimize-autoloader
```

#### 3. Database Connection Issues
```bash
# Test database connection
php artisan migrate:status

# Check database configuration
php artisan config:show database
```

#### 4. LandingLens API Errors
- Verify API credentials in `.env` file
- Check internet connectivity
- Ensure API endpoint URL is correct
- Monitor API usage limits

#### 5. File Upload Issues
```bash
# Check PHP upload limits
php -ini | grep -E "(upload_max_filesize|post_max_size|max_execution_time)"

# Verify GD extension
php -m | grep -i gd
```

#### 6. Image Processing Errors
- Ensure GD extension is installed and enabled
- Check available memory: `php -ini | grep memory_limit`
- Verify write permissions on storage directory

### Debug Steps
1. **Check Laravel logs**: `tail -f storage/logs/laravel.log`
2. **Verify environment**: `php artisan config:show`
3. **Test database**: `php artisan migrate:status`
4. **Check PHP configuration**: `php --ini`
5. **Test web server**: Check Apache/Nginx error logs

## 🎯 Production Deployment

### SSL Certificate (Recommended)
```bash
# Using Let's Encrypt (free SSL)
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d your-domain.com
```

### Performance Optimization
```bash
# Enable OPcache in php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000

# Set up queue workers for background processing
php artisan queue:work --daemon
```

### Backup Strategy
```bash
# Database backup
mysqldump -u username -p landing_lens_api > backup.sql

# File backup
tar -czf uploads_backup.tar.gz storage/app/public/uploads/
```

## 🎯 Next Steps

1. **Get LandingLens API credentials** from [landing.ai](https://landing.ai/)
2. **Configure your web server** (Apache/Nginx)
3. **Set up SSL certificate** for production security
4. **Test image upload** and AI analysis functionality
5. **Customize** the application for your specific use case
6. **Monitor** application performance and API usage

## 📱 Mobile App Integration

The REST API makes it easy to integrate with mobile applications:

### Example API Call (JavaScript)
```javascript
const formData = new FormData();
formData.append('file', imageFile);
formData.append('email', 'user@example.com');

fetch('https://your-domain.com/api/v1/predict', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => console.log(data));
```

## 📝 License

This project is open-sourced software licensed under the MIT license.

---

## 🎉 Ready to Launch!

Your AI-powered object detection platform is ready for production!

### ✅ **What You Have:**
- 🤖 **AI Integration** - LandingLens object detection
- 🎨 **Professional UI** - Modern drag & drop interface  
- 📱 **Responsive Design** - Works on all devices
- 🔐 **Secure Authentication** - User registration & login
- 💾 **Data Persistence** - Full analysis history
- 🌐 **REST API** - Third-party integration ready
- 📊 **Analytics** - User statistics and insights

### 🚀 **Start Analyzing Images Now:**
1. Upload your LandingLens API credentials
2. Register your first user account
3. Start detecting objects in images!

**Your professional AI platform is live!** 🎊✨
