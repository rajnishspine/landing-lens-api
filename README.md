# Landing Lens API - Laravel Homestead Integration

A Laravel application that integrates with LandingLens AI for image object detection and analysis, optimized for Laravel Homestead with PHP 8.1.

## 🚀 Features

- ✅ **Laravel 10** with PHP 8.1 (Homestead optimized)
- ✅ **Laravel Breeze Authentication** with Bootstrap 5 styling
- ✅ **LandingLens API Integration** for object detection
- ✅ **Image Upload & Processing** with validation
- ✅ **Visual Results** with red bounding boxes drawn on detected objects
- ✅ **Bootstrap 5 UI** with responsive design (CSS issues fixed!)
- ✅ **Error Handling** and user-friendly feedback
- ✅ **Secure File Storage** with automatic cleanup
- ✅ **Intervention Image v3** for image processing

## 📋 Requirements

- **Laravel Homestead** with Vagrant
- **PHP 8.1** (available in Homestead)
- **Composer**
- **Node.js & NPM** (for asset compilation)
- **LandingLens API credentials**

## 🏠 Homestead Setup

### 1. Start Homestead
```bash
cd /home/spine/Homestead
vagrant up --provision
```

### 2. SSH into Homestead
```bash
vagrant ssh
```

### 3. Navigate to Project
```bash
cd projects/landing-lens-api
```

## 🔧 Installation & Configuration

### 1. Install Dependencies
```bash
composer install
```

### 2. Configure Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Configure LandingLens API
Edit `.env` file and add your LandingLens credentials:
```env
LANDINGLENS_API_KEY=your_actual_api_key
LANDINGLENS_ENDPOINT_ID=your_actual_endpoint_id
```

### 4. Set up Database
```bash
# Database is already migrated during setup
# If needed, run:
php artisan migrate
```

### 5. Set up Storage
```bash
# Storage link is already created
# If needed, run:
php artisan storage:link
```

## 🎯 Quick Start

### Access the Application
- **URL**: `http://landing-lens-api.test` (or your configured Homestead domain)
- **Register**: Create a new account
- **Login**: Access the dashboard

### Upload and Analyze Images
1. Navigate to **Dashboard**
2. Click **"Start Analysis"**
3. Upload an image (JPEG/PNG, max 10MB)
4. View results with object detection

## 📁 Project Structure

```
landing-lens-api/
├── app/
│   ├── Http/Controllers/
│   │   └── PredictController.php       # Main prediction controller
│   └── Services/
│       └── LandingLensService.php      # API integration service
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php              # Bootstrap 5 main layout
│   │   └── navigation.blade.php        # Bootstrap navbar
│   ├── predict/
│   │   ├── index.blade.php            # Upload form
│   │   └── result.blade.php           # Results display
│   └── dashboard.blade.php             # Enhanced dashboard
├── routes/
│   └── web.php                        # Web routes with auth middleware
├── config/
│   └── services.php                   # LandingLens configuration
└── storage/app/public/uploads/         # Image storage directory
```

## 🔑 Key Features Implemented

### ✅ CSS Issues Fixed
- **Bootstrap 5 CDN** integration
- **No npm build required** (works in Homestead)
- **Responsive navigation** with dropdowns
- **Professional styling** throughout

### ✅ LandingLens Integration
```php
// Service handles:
- Image validation (format, size)
- API communication with proper headers
- Response parsing and coordinate extraction
- Error handling with user-friendly messages
```

### ✅ Image Processing
```php
// Uses Intervention/Image v3 to:
- Draw red bounding rectangles around detected objects
- Add confidence score labels
- Generate processed images with unique filenames
- Automatic cleanup of old processed images
```

### ✅ Authentication & Security
- **Laravel Breeze** with Bootstrap 5
- **Authentication required** for all predict routes
- **File upload validation** (type, size)
- **Secure storage** in `storage/app/public/uploads/`
- **CSRF protection** on all forms

## 🎨 UI/UX Features

- **Modern Bootstrap 5** design
- **Font Awesome** icons
- **Responsive** mobile-friendly layout
- **Flash messages** with auto-hide
- **Image preview** before upload
- **Processing indicators** and loaders
- **Statistics** and detailed results tables

## 🛠️ Development

### Debug Mode
```bash
# Enable debug mode in .env
APP_DEBUG=true
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🚨 Troubleshooting

### Common Issues

1. **"Class not found" errors**
   ```bash
   composer dump-autoload
   ```

2. **Storage permission errors**
   ```bash
   sudo chmod -R 775 storage/
   sudo chown -R vagrant:vagrant storage/
   ```

3. **CSS not loading**
   - ✅ **Fixed!** Using Bootstrap CDN
   - No npm build required

4. **API connection errors**
   - Verify LandingLens credentials in `.env`
   - Check internet connection from Homestead

5. **Image processing errors**
   - Ensure GD extension is installed (included in Homestead)
   - Check file permissions on storage directory

### Debug Steps

1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify environment variables: `php artisan config:show`
3. Test database connection: `php artisan migrate:status`
4. Check file upload limits: `php -ini | grep upload`

## 🔧 Homestead Configuration

### Recommended Homestead.yaml
```yaml
folders:
  - map: ~/projects
    to: /home/vagrant/projects

sites:
  - map: landing-lens-api.test
    to: /home/vagrant/projects/landing-lens-api/public
    php: "8.1"

databases:
  - landing_lens_api
```

### Host File Entry
```
192.168.56.56  landing-lens-api.test
```

## 📚 API Integration

### LandingLens Setup
1. Get your API credentials from [LandingLens](https://landing.ai/)
2. Add them to your `.env` file:
   ```env
   LANDINGLENS_API_KEY=your_actual_api_key
   LANDINGLENS_ENDPOINT_ID=your_actual_endpoint_id
   ```

### API Usage
```php
// The service automatically handles:
- Image validation
- API requests with proper headers
- Response parsing
- Error handling
```

## 🎯 Next Steps

1. **Configure LandingLens API** credentials
2. **Test image upload** functionality
3. **Customize object detection** for your use case
4. **Add user management** features if needed
5. **Deploy to production** when ready

## 📝 License

This project is open-sourced software licensed under the MIT license.

---

## 🎉 You're All Set!

Your Laravel LandingLens API is now ready for use in Homestead with PHP 8.1!

- ✅ **CSS Issues Fixed** - Bootstrap 5 working perfectly
- ✅ **Authentication** - Register/Login working
- ✅ **Image Upload** - Professional upload interface
- ✅ **Object Detection** - AI-powered analysis ready
- ✅ **Modern UI** - Beautiful Bootstrap 5 design

**Next step**: Add your LandingLens API credentials and start analyzing images! 🚀
