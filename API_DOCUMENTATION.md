# LandingLens API Documentation

A powerful REST API for AI-powered image object detection using LandingLens technology.

## Base URL
```
https://your-domain.com/api/v1
```

## Authentication

### Public Endpoints
No authentication required for basic image analysis.

### Authenticated Endpoints  
Use Laravel Sanctum tokens for user-specific operations:
```http
Authorization: Bearer YOUR_API_TOKEN
```

## Endpoints Overview

| Method | Endpoint | Auth Required | Description |
|--------|----------|---------------|-------------|
| POST | `/predict` | ❌ | Analyze image for object detection |
| GET | `/predict/{id}` | ❌ | Get analysis results by ID |
| GET | `/predict` | ❌ | List recent analyses (paginated) |
| GET | `/my-analyses` | ✅ | Get user's analysis history |
| DELETE | `/predict/{id}` | ✅ | Delete user's analysis |

---

## 1. Analyze Image

Analyze an image for object detection using AI.

### Request
```http
POST /api/v1/predict
Content-Type: multipart/form-data
```

### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `image` | File | ✅ | Image file (JPEG, PNG, JPG, max 10MB) |
| `user_email` | String | ❌ | Associate analysis with email |
| `callback_url` | URL | ❌ | Webhook URL for completion notification |

### Example Request (cURL)
```bash
curl -X POST "https://your-domain.com/api/v1/predict" \
  -H "Content-Type: multipart/form-data" \
  -F "image=@/path/to/your/image.jpg" \
  -F "user_email=user@example.com"
```

### Example Request (PHP)
```php
<?php
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://your-domain.com/api/v1/predict',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
        'image' => new CURLFile('/path/to/image.jpg'),
        'user_email' => 'user@example.com'
    ],
]);

$response = curl_exec($curl);
$data = json_decode($response, true);
curl_close($curl);
?>
```

### Example Request (JavaScript)
```javascript
const formData = new FormData();
formData.append('image', fileInput.files[0]);
formData.append('user_email', 'user@example.com');

fetch('https://your-domain.com/api/v1/predict', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => console.log(data));
```

### Example Request (Python)
```python
import requests

url = 'https://your-domain.com/api/v1/predict'
files = {'image': open('/path/to/image.jpg', 'rb')}
data = {'user_email': 'user@example.com'}

response = requests.post(url, files=files, data=data)
result = response.json()
```

### Success Response (201 Created)
```json
{
    "success": true,
    "message": "Image analyzed successfully",
    "data": {
        "analysis_id": 123,
        "status": "completed",
        "original_filename": "image.jpg",
        "file_size": 245760,
        "mime_type": "image/jpeg",
        "objects_detected_count": 3,
        "average_confidence": 0.87,
        "processing_time_seconds": 2.45,
        "created_at": "2024-07-30T08:30:00Z",
        "images": {
            "original_url": "https://your-domain.com/storage/uploads/analyses/123/original_123_1690704600.jpg",
            "processed_url": "https://your-domain.com/storage/uploads/analyses/123/processed_123_1690704600.jpg"
        },
        "detected_objects": [
            {
                "id": 456,
                "label": "car",
                "confidence": 0.92,
                "confidence_percentage": "92.0%",
                "bounding_box": {
                    "x_min": 100,
                    "y_min": 150,
                    "x_max": 300,
                    "y_max": 250,
                    "width": 200,
                    "height": 100,
                    "area": 20000
                },
                "defect_id": null
            },
            {
                "id": 457,
                "label": "person",
                "confidence": 0.85,
                "confidence_percentage": "85.0%",
                "bounding_box": {
                    "x_min": 50,
                    "y_min": 80,
                    "x_max": 120,
                    "y_max": 200,
                    "width": 70,
                    "height": 120,
                    "area": 8400
                },
                "defect_id": null
            }
        ],
        "api_response": {
            "model_id": "model_123",
            "backbone_type": "efficientnet",
            "latency_seconds": 2.45
        }
    },
    "links": {
        "self": "https://your-domain.com/api/v1/predict/123",
        "original_image": "https://your-domain.com/storage/uploads/analyses/123/original_123_1690704600.jpg",
        "processed_image": "https://your-domain.com/storage/uploads/analyses/123/processed_123_1690704600.jpg"
    }
}
```

### Error Response (422 Validation Error)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "image": ["The image field is required."]
    }
}
```

### Error Response (500 Processing Error)
```json
{
    "success": false,
    "message": "Image analysis failed",
    "error": "An error occurred while processing your image",
    "technical_error": "LandingLens API request failed: ...",
    "timestamp": "2024-07-30T08:30:00Z"
}
```

---

## 2. Get Analysis Results

Retrieve analysis results by ID.

### Request
```http
GET /api/v1/predict/{id}
```

### Example Request
```bash
curl "https://your-domain.com/api/v1/predict/123"
```

### Success Response (200 OK)
```json
{
    "success": true,
    "data": {
        "analysis_id": 123,
        "status": "completed",
        "original_filename": "image.jpg",
        "file_size": 245760,
        "mime_type": "image/jpeg",
        "objects_detected_count": 3,
        "average_confidence": 0.87,
        "processing_time_seconds": 2.45,
        "created_at": "2024-07-30T08:30:00Z",
        "updated_at": "2024-07-30T08:30:15Z",
        "images": {
            "original_url": "https://your-domain.com/storage/uploads/analyses/123/original_123_1690704600.jpg",
            "processed_url": "https://your-domain.com/storage/uploads/analyses/123/processed_123_1690704600.jpg"
        },
        "detected_objects": [...],
        "api_response": {
            "model_id": "model_123",
            "backbone_type": "efficientnet",
            "latency_seconds": 2.45
        },
        "error_message": null
    }
}
```

### Error Response (404 Not Found)
```json
{
    "success": false,
    "message": "Analysis not found"
}
```

---

## 3. List Recent Analyses

Get paginated list of recent analyses.

### Request
```http
GET /api/v1/predict?per_page=20&status=completed
```

### Query Parameters
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `per_page` | Integer | 10 | Items per page (max 50) |
| `status` | String | - | Filter by status: `pending`, `processing`, `completed`, `failed` |

### Example Request
```bash
curl "https://your-domain.com/api/v1/predict?per_page=5&status=completed"
```

### Success Response (200 OK)
```json
{
    "success": true,
    "data": [
        {
            "analysis_id": 123,
            "status": "completed",
            "objects_detected_count": 3,
            "average_confidence": 0.87,
            "created_at": "2024-07-30T08:30:00Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 5,
        "total": 47,
        "last_page": 10,
        "has_more": true
    }
}
```

---

## 4. Get User's Analyses (Authenticated)

Get authenticated user's analysis history.

### Request
```http
GET /api/v1/my-analyses
Authorization: Bearer YOUR_API_TOKEN
```

### Example Request
```bash
curl "https://your-domain.com/api/v1/my-analyses" \
  -H "Authorization: Bearer YOUR_API_TOKEN"
```

### Success Response (200 OK)
Same structure as list endpoint, but filtered to user's analyses only.

---

## 5. Delete Analysis (Authenticated)

Delete a user's analysis and associated files.

### Request
```http
DELETE /api/v1/predict/{id}
Authorization: Bearer YOUR_API_TOKEN
```

### Example Request
```bash
curl -X DELETE "https://your-domain.com/api/v1/predict/123" \
  -H "Authorization: Bearer YOUR_API_TOKEN"
```

### Success Response (200 OK)
```json
{
    "success": true,
    "message": "Analysis deleted successfully"
}
```

### Error Response (404 Not Found)
```json
{
    "success": false,
    "message": "Analysis not found or not owned by user"
}
```

---

## Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created (new analysis) |
| 401 | Unauthorized (invalid token) |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

## Rate Limits

- **Public endpoints**: 100 requests per minute per IP
- **Authenticated endpoints**: 500 requests per minute per token

---

## Image Requirements

- **Formats**: JPEG, PNG, JPG
- **Max Size**: 10MB
- **Recommended**: High resolution images for better detection accuracy

---

## Webhook Support (Future)

When `callback_url` is provided, a POST request will be sent upon completion:

```json
{
    "analysis_id": 123,
    "status": "completed",
    "objects_detected_count": 3,
    "processing_time_seconds": 2.45,
    "timestamp": "2024-07-30T08:30:15Z"
}
```

---

## Error Handling

All error responses follow this structure:

```json
{
    "success": false,
    "message": "Human-readable error message",
    "error": "General error description",
    "technical_error": "Detailed technical error (optional)",
    "timestamp": "2024-07-30T08:30:00Z"
}
```

---

## SDK Examples

### Node.js SDK Example
```javascript
class LandingLensAPI {
    constructor(baseURL) {
        this.baseURL = baseURL;
    }
    
    async analyzeImage(imagePath, userEmail = null) {
        const formData = new FormData();
        formData.append('image', fs.createReadStream(imagePath));
        if (userEmail) formData.append('user_email', userEmail);
        
        const response = await fetch(`${this.baseURL}/api/v1/predict`, {
            method: 'POST',
            body: formData
        });
        
        return response.json();
    }
    
    async getAnalysis(id) {
        const response = await fetch(`${this.baseURL}/api/v1/predict/${id}`);
        return response.json();
    }
}

// Usage
const api = new LandingLensAPI('https://your-domain.com');
const result = await api.analyzeImage('/path/to/image.jpg', 'user@example.com');
```

### Python SDK Example
```python
import requests
import json

class LandingLensAPI:
    def __init__(self, base_url):
        self.base_url = base_url
    
    def analyze_image(self, image_path, user_email=None):
        url = f"{self.base_url}/api/v1/predict"
        files = {'image': open(image_path, 'rb')}
        data = {}
        if user_email:
            data['user_email'] = user_email
            
        response = requests.post(url, files=files, data=data)
        return response.json()
    
    def get_analysis(self, analysis_id):
        url = f"{self.base_url}/api/v1/predict/{analysis_id}"
        response = requests.get(url)
        return response.json()

# Usage
api = LandingLensAPI('https://your-domain.com')
result = api.analyze_image('/path/to/image.jpg', 'user@example.com')
```

---

## Support

For API support, please contact: [your-support-email]

**Base URL**: `https://your-domain.com/api/v1`  
**Version**: v1  
**Documentation Updated**: July 30, 2024