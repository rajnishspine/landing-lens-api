# Authentication & Swagger Integration Guide

## 🎉 Successfully Integrated Features

✅ **Authentication Endpoints** (Login/Logout/Token Management)  
✅ **Swagger/OpenAPI Documentation** using DarkaOnLine/L5-Swagger  
✅ **Bearer Token Authentication** with Laravel Sanctum  
✅ **Comprehensive API Documentation** with interactive UI  

---

## 🔐 New Authentication Endpoints

### **Public Endpoints** (No Authentication Required)

#### 1. **Register User**
```http
POST /api/v1/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|abc123def456...",
        "token_type": "Bearer"
    }
}
```

#### 2. **Login User**
```http
POST /api/v1/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "token": "2|xyz789abc123...",
        "token_type": "Bearer"
    }
}
```

### **Authenticated Endpoints** (Bearer Token Required)

#### 3. **Get User Profile**
```http
GET /api/v1/user
Authorization: Bearer {your-token}
```

#### 4. **Logout (Current Device)**
```http
POST /api/v1/logout
Authorization: Bearer {your-token}
```

#### 5. **Logout All Devices**
```http
POST /api/v1/logout-all
Authorization: Bearer {your-token}
```

#### 6. **Refresh Token**
```http
POST /api/v1/refresh-token
Authorization: Bearer {your-token}
```

---

## 📊 Updated Image Analysis API

All existing endpoints have been enhanced with Swagger documentation:

### **Public Endpoints**
- `POST /api/v1/predict` - Analyze image
- `GET /api/v1/predict/{id}` - Get analysis by ID  
- `GET /api/v1/predict` - List recent analyses

### **Authenticated Endpoints**
- `GET /api/v1/my-analyses` - Get user's analysis history
- `DELETE /api/v1/predict/{id}` - Delete user's analysis

---

## 📚 Swagger Documentation

### **Access Swagger UI**
Visit: **http://localhost:8000/api/documentation**

### **Key Features:**
- 🔍 **Interactive API Explorer** - Test endpoints directly
- 🔐 **Authentication Support** - Use Bearer tokens
- 📝 **Complete Documentation** - All endpoints with examples
- 🧪 **Request/Response Examples** - Real data samples
- 📋 **Schema Definitions** - Data models explained

### **How to Use Swagger UI:**

1. **Open Swagger UI** at `/api/documentation`
2. **Authenticate** (if needed):
   - Click the "Authorize" button (🔒)
   - Enter: `Bearer your-token-here`
   - Click "Authorize"
3. **Test Endpoints**:
   - Click on any endpoint
   - Click "Try it out"
   - Fill in parameters
   - Click "Execute"

---

## 🚀 Getting Started

### **1. Generate Swagger Documentation**
```bash
php artisan l5-swagger:generate
```

### **2. Start Development Server**
```bash
php artisan serve
```

### **3. Test Authentication Flow**

#### **Option A: Use Swagger UI** 
- Visit: `http://localhost:8000/api/documentation`
- Test `/register` or `/login` endpoints
- Copy the token from response
- Use "Authorize" button to set Bearer token

#### **Option B: Use Test Script**
```bash
php test_api.php
```

#### **Option C: Use cURL**
```bash
# Register
curl -X POST "http://localhost:8000/api/v1/register" \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST "http://localhost:8000/api/v1/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Use Token
curl -X GET "http://localhost:8000/api/v1/user" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🔧 File Changes Made

### **New Files Created:**
- `app/Http/Controllers/Api/AuthController.php` - Authentication endpoints
- `app/Http/Controllers/Api/BaseApiController.php` - Swagger schemas & API info
- `test_api.php` - API testing script
- `AUTHENTICATION_AND_SWAGGER_INTEGRATION.md` - This guide

### **Files Modified:**
- `routes/api.php` - Added authentication routes
- `app/Http/Controllers/Api/PredictApiController.php` - Added Swagger annotations
- `config/l5-swagger.php` - Updated API title

### **Existing Files Enhanced:**
- All API endpoints now have comprehensive Swagger documentation
- Bearer token authentication properly configured
- User model relationships already existed (no changes needed)

---

## 🔐 Security Features

### **Token Management:**
- ✅ **Laravel Sanctum** for secure API tokens
- ✅ **Token Expiration** (configurable)
- ✅ **Multiple Device Support** (separate tokens per device)
- ✅ **Selective Logout** (current device vs all devices)
- ✅ **Token Refresh** capability

### **Validation & Error Handling:**
- ✅ **Input Validation** on all endpoints
- ✅ **Consistent Error Responses** with proper HTTP codes
- ✅ **User-friendly Error Messages**
- ✅ **Technical Error Details** (in debug mode)

---

## 📋 API Testing Checklist

- [ ] Register new user via `/api/v1/register`
- [ ] Login existing user via `/api/v1/login`
- [ ] Get user profile via `/api/v1/user`
- [ ] Upload image via `/api/v1/predict` (with Bearer token)
- [ ] Get user's analyses via `/api/v1/my-analyses`
- [ ] Delete analysis via `/api/v1/predict/{id}`
- [ ] Refresh token via `/api/v1/refresh-token`
- [ ] Logout via `/api/v1/logout`
- [ ] Test all endpoints in Swagger UI

---

## 🎯 Rate Limits

| Endpoint Type | Rate Limit |
|---------------|------------|
| **Public endpoints** | 100 requests/minute per IP |
| **Authenticated endpoints** | 500 requests/minute per token |

---

## 🚨 Important Notes

1. **Environment Setup**: Ensure `SANCTUM_STATEFUL_DOMAINS` is configured in `.env`
2. **Database Migration**: Run `php artisan migrate` if not done already
3. **Storage Link**: Run `php artisan storage:link` for image access
4. **API Keys**: Configure LandingLens API credentials in `.env`
5. **CORS**: Update CORS settings if accessing from different domains

---

## 🔄 Next Steps

1. **Production Setup**:
   - Configure proper domains in Sanctum
   - Set up HTTPS for secure token transmission
   - Configure rate limiting for production

2. **Additional Features**:
   - Email verification for registration
   - Password reset functionality
   - Admin panel for user management
   - API usage analytics

3. **Testing**:
   - Write unit tests for authentication
   - Integration tests for API endpoints
   - Load testing for performance

---

## 🆘 Troubleshooting

### **Common Issues:**

#### "Unauthenticated" Error
- ✅ Check Bearer token format: `Bearer token-here`
- ✅ Ensure token hasn't expired
- ✅ Verify token is valid (not logged out)

#### Swagger UI Not Loading
- ✅ Run `php artisan l5-swagger:generate`
- ✅ Check `/api/documentation` route
- ✅ Clear browser cache

#### CORS Issues
- ✅ Update `config/cors.php`
- ✅ Add allowed origins
- ✅ Enable credentials if needed

---

**🎉 Integration Complete!** Your Landing Lens API now has full authentication support and interactive Swagger documentation. Happy coding! 🚀