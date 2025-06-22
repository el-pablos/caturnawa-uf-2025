# API Documentation - UNAS Fest 2025

Dokumentasi API untuk sistem pendaftaran kompetisi UNAS Fest 2025.

## Base URL
```
http://localhost:8000/api
```

## Authentication
Semua endpoint API memerlukan autentikasi menggunakan session-based authentication.

### Headers
```
Content-Type: application/json
Accept: application/json
X-CSRF-TOKEN: {csrf_token}
```

## Endpoints

### 1. Competitions

#### Get All Competitions
```http
GET /api/competitions
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Masak Masakan",
      "slug": "masak-masakan",
      "description": "Kompetisi memasak...",
      "category": "biodiversity",
      "price": 200000,
      "current_price": 150000,
      "is_early_bird": true,
      "registration_status": "open",
      "participants_count": 25,
      "max_participants": 100,
      "registration_start": "2025-06-12T00:00:00Z",
      "registration_end": "2025-08-22T23:59:59Z"
    }
  ]
}
```

#### Get Competition Detail
```http
GET /api/competitions/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Masak Masakan",
    "slug": "masak-masakan",
    "description": "Kompetisi memasak dengan tema sustainable cooking...",
    "category": "biodiversity",
    "theme": "Sustainable Cooking",
    "price": 200000,
    "early_bird_price": 150000,
    "current_price": 150000,
    "is_early_bird": true,
    "registration_status": "open",
    "participants_count": 25,
    "max_participants": 100,
    "is_team_competition": true,
    "min_team_members": 2,
    "max_team_members": 4,
    "requirements": [
      "Peserta mahasiswa aktif",
      "Membawa peralatan memasak sendiri",
      "Menggunakan bahan lokal minimal 70%"
    ],
    "prizes": [
      "Juara 1: Rp 10.000.000",
      "Juara 2: Rp 7.500.000",
      "Juara 3: Rp 5.000.000"
    ],
    "rules": [
      "Waktu memasak maksimal 3 jam",
      "Tidak boleh menggunakan bahan pengawet",
      "Harus menyajikan 3 menu lengkap"
    ],
    "image_url": "http://localhost:8000/storage/competitions/image.jpg",
    "registration_start": "2025-06-12T00:00:00Z",
    "registration_end": "2025-08-22T23:59:59Z",
    "competition_start": "2025-09-01T00:00:00Z",
    "competition_end": "2025-09-03T23:59:59Z",
    "submission_deadline": "2025-09-06T23:59:59Z",
    "result_announcement": "2025-09-11T00:00:00Z",
    "created_at": "2025-06-12T10:00:00Z",
    "updated_at": "2025-06-12T10:00:00Z"
  }
}
```

### 2. Registrations

#### Get User Registrations
```http
GET /api/registrations
```

**Parameters:**
- `status` (optional): pending, confirmed, cancelled, expired
- `competition_id` (optional): Filter by competition

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "registration_number": "UF2025-06-0001",
      "competition": {
        "id": 1,
        "name": "Masak Masakan",
        "category": "biodiversity"
      },
      "team_name": "Sustainable Cooks",
      "team_members": [
        {
          "name": "John Doe",
          "role": "Team Leader",
          "student_id": "123456789"
        }
      ],
      "institution": "Universitas Nasional",
      "amount": 150000,
      "status": "confirmed",
      "ticket_code": "TICKET-ABC12345",
      "qr_code_url": "http://localhost:8000/storage/qrcodes/TICKET-ABC12345.png",
      "payment": {
        "id": 1,
        "status": "settlement",
        "payment_type": "bank_transfer",
        "paid_at": "2025-06-12T15:30:00Z"
      },
      "registered_at": "2025-06-12T14:00:00Z",
      "confirmed_at": "2025-06-12T15:30:00Z"
    }
  ]
}
```

#### Get Registrations DataTable
```http
GET /api/registrations/datatable
```

**Parameters:**
- `draw` (required): DataTables draw counter
- `start` (required): Pagination start
- `length` (required): Pagination length
- `search[value]` (optional): Search term
- `order[0][column]` (optional): Sort column
- `order[0][dir]` (optional): Sort direction

**Response:**
```json
{
  "draw": 1,
  "recordsTotal": 100,
  "recordsFiltered": 10,
  "data": [
    {
      "id": 1,
      "registration_number": "UF2025-06-0001",
      "competition_name": "Masak Masakan",
      "participant_name": "John Doe",
      "team_name": "Sustainable Cooks",
      "amount": "Rp 150.000",
      "status": "<span class=\"badge bg-success\">Confirmed</span>",
      "registered_at": "12/06/2025 14:00",
      "action": "<button class=\"btn btn-sm btn-info\">View</button>"
    }
  ]
}
```

### 3. Payments

#### Get Payments
```http
GET /api/payments
```

**Parameters:**
- `status` (optional): pending, settlement, deny, cancel, expire
- `registration_id` (optional): Filter by registration

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "order_id": "UF2025-20250622143000-001",
      "registration": {
        "id": 1,
        "registration_number": "UF2025-06-0001",
        "participant_name": "John Doe",
        "competition_name": "Masak Masakan"
      },
      "gross_amount": 150000,
      "transaction_status": "settlement",
      "payment_type": "bank_transfer",
      "payment_method": "BCA Virtual Account",
      "status_label": "Berhasil",
      "paid_at": "2025-06-12T15:30:00Z",
      "created_at": "2025-06-12T14:00:00Z"
    }
  ]
}
```

#### Get Payments DataTable
```http
GET /api/payments/datatable
```

**Response format sama dengan registrations datatable**

### 4. Users (Admin Only)

#### Get Users
```http
GET /api/users
```

**Parameters:**
- `role` (optional): Super Admin, Admin, Juri, Peserta
- `status` (optional): active, inactive

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "08123456789",
      "roles": ["Peserta"],
      "is_active": true,
      "avatar_url": "http://localhost:8000/storage/avatars/avatar.jpg",
      "last_login_at": "2025-06-12T15:30:00Z",
      "created_at": "2025-06-12T10:00:00Z"
    }
  ]
}
```

#### Get Users DataTable
```http
GET /api/users/datatable
```

### 5. Statistics

#### Get Dashboard Statistics
```http
GET /api/statistics/dashboard
```

**Response:**
```json
{
  "success": true,
  "data": {
    "total_users": 150,
    "total_competitions": 3,
    "total_registrations": 75,
    "total_revenue": 15000000,
    "active_competitions": 2,
    "confirmed_registrations": 60,
    "pending_payments": 5,
    "chart_data": {
      "months": ["Jan 2025", "Feb 2025", "Mar 2025"],
      "registrations": [10, 25, 40],
      "revenues": [2000000, 6000000, 7000000]
    },
    "user_distribution": {
      "labels": ["Peserta", "Juri", "Admin", "Super Admin"],
      "data": [140, 6, 3, 1],
      "colors": ["#0d6efd", "#198754", "#fd7e14", "#dc3545"]
    }
  }
}
```

#### Get Competition Statistics
```http
GET /api/statistics/competition/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "competition": {
      "id": 1,
      "name": "Masak Masakan",
      "category": "biodiversity"
    },
    "total_registrations": 25,
    "confirmed_registrations": 20,
    "pending_registrations": 5,
    "total_revenue": 3000000,
    "average_revenue_per_participant": 150000,
    "conversion_rate": 80.0,
    "daily_registrations": [
      {
        "date": "2025-06-12",
        "count": 5,
        "revenue": 750000
      }
    ]
  }
}
```

## Error Responses

### Validation Error
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

### Authentication Error
```json
{
  "success": false,
  "message": "Unauthenticated.",
  "error_code": "UNAUTHENTICATED"
}
```

### Authorization Error
```json
{
  "success": false,
  "message": "Unauthorized.",
  "error_code": "UNAUTHORIZED"
}
```

### Not Found Error
```json
{
  "success": false,
  "message": "Resource not found.",
  "error_code": "NOT_FOUND"
}
```

### Server Error
```json
{
  "success": false,
  "message": "Internal server error.",
  "error_code": "INTERNAL_ERROR"
}
```

## Status Codes

| Code | Description |
|------|-------------|
| 200  | OK |
| 201  | Created |
| 400  | Bad Request |
| 401  | Unauthorized |
| 403  | Forbidden |
| 404  | Not Found |
| 422  | Validation Error |
| 500  | Internal Server Error |

## Rate Limiting

API memiliki rate limiting:
- 60 requests per minute untuk authenticated users
- 10 requests per minute untuk guest users

## Pagination

Untuk endpoint yang mengembalikan list data, gunakan parameter:
- `page`: Nomor halaman (default: 1)
- `per_page`: Jumlah item per halaman (default: 15, max: 100)

**Response dengan pagination:**
```json
{
  "success": true,
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75,
    "from": 1,
    "to": 15
  },
  "links": {
    "first": "http://localhost:8000/api/endpoint?page=1",
    "last": "http://localhost:8000/api/endpoint?page=5",
    "prev": null,
    "next": "http://localhost:8000/api/endpoint?page=2"
  }
}
```

## Filtering & Sorting

### Filtering
Gunakan query parameters untuk filtering:
```http
GET /api/registrations?status=confirmed&competition_id=1
```

### Sorting
Gunakan parameter `sort` dan `order`:
```http
GET /api/registrations?sort=created_at&order=desc
```

## Examples

### Get Competition with cURL
```bash
curl -X GET "http://localhost:8000/api/competitions/1" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {token}"
```

### Create Registration with JavaScript
```javascript
const response = await fetch('/api/registrations', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify({
    competition_id: 1,
    team_name: 'Sustainable Cooks',
    institution: 'Universitas Nasional',
    phone: '08123456789'
  })
});

const data = await response.json();
```

## SDK/Libraries

### JavaScript/jQuery Example
```javascript
// Setup AJAX defaults
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

// Get competitions
$.get('/api/competitions', function(response) {
  console.log(response.data);
});

// Create registration
$.post('/api/registrations', {
  competition_id: 1,
  team_name: 'My Team'
}, function(response) {
  if (response.success) {
    alert('Registration successful!');
  }
});
```

### PHP Example
```php
use Illuminate\Support\Facades\Http;

// Get competitions
$response = Http::get('/api/competitions');
$competitions = $response->json()['data'];

// Create registration
$response = Http::post('/api/registrations', [
  'competition_id' => 1,
  'team_name' => 'My Team',
  'institution' => 'University Name'
]);
```

## Testing

Untuk testing API, gunakan tools seperti:
- Postman
- Insomnia
- Thunder Client (VS Code)
- cURL

### Environment Variables for Testing
```
API_BASE_URL=http://localhost:8000/api
TEST_EMAIL=admin@unasfest.ac.id
TEST_PASSWORD=admin123
```
