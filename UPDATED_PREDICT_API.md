# 🔒 Updated `/api/v1/predict` Endpoint

## Changes Made

✅ **Authentication Required**: The `/api/v1/predict` endpoint now requires Bearer token authentication  
✅ **Email Removed**: Email parameter is no longer required or accepted  
✅ **Swagger Updated**: Documentation reflects authentication requirement  
✅ **Routes Updated**: Moved from public to authenticated routes section  

---

## 🔐 New Authentication Requirement

### **Before** (Old Implementation):
```http
POST /api/v1/predict
Content-Type: multipart/form-data

# Required parameters:
image: [file]
email: user@example.com  # ❌ No longer needed

# Optional parameters:
callback_url: https://example.com/webhook
```

### **After** (New Implementation):
```http
POST /api/v1/predict
Authorization: Bearer {your-token}  # ✅ Now required
Content-Type: multipart/form-data

# Required parameters:
image: [file]

# Optional parameters:
callback_url: https://example.com/webhook  # Still optional
```

---

## 🚀 How to Use the Updated Endpoint

### **Step 1: Get Authentication Token**

#### Option A: Login
```bash
curl -X POST "http://localhost:8000/api/v1/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"your@email.com","password":"your-password"}'
```

#### Option B: Register (if new user)
```bash
curl -X POST "http://localhost:8000/api/v1/register" \
  -H "Content-Type: application/json" \
  -d '{
    "name":"Your Name",
    "email":"your@email.com",
    "password":"your-password",
    "password_confirmation":"your-password"
  }'
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {...},
        "token": "1|abc123def456...",
        "token_type": "Bearer"
    }
}
```

### **Step 2: Use Token for Image Analysis**

```bash
curl -X POST "http://localhost:8000/api/v1/predict" \
  -H "Authorization: Bearer 1|abc123def456..." \
  -F "image=@/path/to/your/image.jpg"
```

---

## 📚 Swagger Documentation

The Swagger UI has been updated to reflect these changes:

### **Access Swagger UI:**
Visit: http://localhost:8000/api/documentation

### **In Swagger UI:**

1. **Find the `/api/v1/predict` endpoint** - now shows 🔒 (authentication required)
2. **Click "Authorize" button** at the top
3. **Enter your Bearer token**: `Bearer your-token-here`
4. **Test the endpoint** with file upload

### **Updated Swagger Schema:**

```yaml
/api/v1/predict:
  post:
    summary: "Analyze image for object detection using AI"
    security:
      - bearerAuth: []  # 🔒 Authentication required
    requestBody:
      content:
        multipart/form-data:
          schema:
            properties:
              image:
                type: string
                format: binary
                description: "Image file (JPEG, PNG, JPG, max 10MB)"
              callback_url:
                type: string
                format: url
                description: "Optional webhook URL"
            required:
              - image
    responses:
      201:
        description: "Image analyzed successfully"
      401:
        description: "Unauthenticated"  # 🔒 New error response
      422:
        description: "Validation error"
      500:
        description: "Processing error"
```

---

## 🔄 Route Changes

### **Public Routes** (No Authentication Required):
```php
// Only read-only endpoints remain public
Route::get('/predict/{id}', [...]);  // Get analysis by ID
Route::get('/predict', [...]);       // List recent analyses
```

### **Authenticated Routes** (Bearer Token Required):
```php
// Image analysis creation now requires authentication
Route::post('/predict', [...]);      // 🔒 Analyze image (MOVED HERE)
Route::get('/my-analyses', [...]);   // Get user's analyses
Route::delete('/predict/{id}', []); // Delete user's analysis
```

---

## 💻 Code Examples

### **JavaScript/Fetch:**
```javascript
// Get token first
const loginResponse = await fetch('/api/v1/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        email: 'user@example.com',
        password: 'password'
    })
});

const { data } = await loginResponse.json();
const token = data.token;

// Use token for image analysis
const formData = new FormData();
formData.append('image', fileInput.files[0]);

const response = await fetch('/api/v1/predict', {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${token}`
    },
    body: formData
});

const result = await response.json();
```

### **PHP/cURL:**
```php
// Get token first
$loginData = ['email' => 'user@example.com', 'password' => 'password'];
// ... login request code ...
$token = $loginResponse['data']['token'];

// Use token for image analysis
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://localhost:8000/api/v1/predict',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token
    ],
    CURLOPT_POSTFIELDS => [
        'image' => new CURLFile('/path/to/image.jpg')
    ]
]);

$response = curl_exec($ch);
```

### **Python/Requests:**
```python
import requests

# Get token first
login_response = requests.post('http://localhost:8000/api/v1/login', 
    json={'email': 'user@example.com', 'password': 'password'})
token = login_response.json()['data']['token']

# Use token for image analysis
headers = {'Authorization': f'Bearer {token}'}
files = {'image': open('/path/to/image.jpg', 'rb')}

response = requests.post('http://localhost:8000/api/v1/predict', 
    headers=headers, files=files)
result = response.json()
```

---

## 🎯 Benefits of Authentication Requirement

### **Security:**
- ✅ **User Tracking**: All analyses are properly linked to authenticated users
- ✅ **Access Control**: Only authenticated users can create analyses
- ✅ **Rate Limiting**: Better control over API usage per user
- ✅ **Data Privacy**: Users can only access their own analyses

### **Data Management:**
- ✅ **User History**: Analyses are automatically associated with user accounts
- ✅ **Cleanup**: Users can delete their own analyses
- ✅ **Analytics**: Better tracking of user engagement and API usage
- ✅ **Accountability**: Clear audit trail of who created what

### **API Consistency:**
- ✅ **Unified Auth**: All write operations now require authentication
- ✅ **Token Management**: Consistent Bearer token usage across endpoints
- ✅ **Error Handling**: Standardized 401 Unauthenticated responses

---

## 🚨 Breaking Changes

### **For Existing API Users:**

1. **Authentication Required**: Must obtain Bearer token before using `/predict`
2. **Email Parameter Removed**: No longer accepts or requires `email` field
3. **Route Protection**: Endpoint moved to authenticated route group

### **Migration Guide:**

#### **Old Code:**
```bash
curl -X POST "/api/v1/predict" \
  -F "image=@image.jpg" \
  -F "email=user@example.com"  # ❌ No longer works
```

#### **New Code:**
```bash
# Step 1: Get token
TOKEN=$(curl -X POST "/api/v1/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}' \
  | jq -r '.data.token')

# Step 2: Use token
curl -X POST "/api/v1/predict" \
  -H "Authorization: Bearer $TOKEN" \  # ✅ Required
  -F "image=@image.jpg"                # ✅ Email not needed
```

---

## 🔧 Testing the Changes

### **Test in Swagger UI:**
1. Go to http://localhost:8000/api/documentation
2. Try `/api/v1/predict` without authentication → Should get 401 error
3. Login via `/api/v1/login` to get token
4. Use "Authorize" button with Bearer token
5. Try `/api/v1/predict` again → Should work

### **Test via cURL:**
```bash
# Test without auth (should fail)
curl -X POST "http://localhost:8000/api/v1/predict" \
  -F "image=@test.jpg"
# Expected: 401 Unauthenticated

# Test with auth (should work)
curl -X POST "http://localhost:8000/api/v1/predict" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "image=@test.jpg"
# Expected: 201 Created with analysis results
```

---

## ✅ Summary

The `/api/v1/predict` endpoint has been successfully updated to:

- 🔒 **Require Bearer token authentication**
- ❌ **Remove email parameter requirement**
- 📚 **Update Swagger documentation**
- 🔄 **Move to authenticated route group**
- 🧹 **Clean up unnecessary helper methods**

This change improves security, data consistency, and user experience while maintaining full API functionality.