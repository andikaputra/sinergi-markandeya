# SSO Universitas Markandeya — Setup Guide

## Arsitektur

```
Browser/User
    │
    ▼
[Client App] ──────redirect──────► [SSO Server: sinergi-markandeya]
    │                                        │
    │                                   Login Form
    │                                   (dosen/mhs)
    │                                        │
    │◄────────callback?code=xxx─────────────┘
    │
    ▼
POST /api/sso/token (server-to-server)
    │
    ▼
[SSO Server] returns { access_token, user_type, user }
    │
    ▼
Client simpan di session → user logged in ✓
```

## Apps yang Terdaftar

| App              | client_id            | Akses             |
|------------------|----------------------|-------------------|
| LPPM Markandeya  | lppm-markandeya      | dosen, admin      |
| TTE Dosen        | tte-dosen            | dosen             |
| Portal Dosen     | lecturer-portal      | dosen             |
| Portal Mahasiswa | student-portal       | mahasiswa         |
| E-Learning       | elearning-markandeya | dosen, mahasiswa  |

---

## 1. Setup SSO Server (sinergi-markandeya)

### Jalankan migrasi
```bash
php artisan migrate
```

### Generate SSO Key Salt (simpan di .env)
```bash
php artisan key:generate  # jika belum
```

### Tambah ke .env sinergi-markandeya
```env
SSO_KEY_SALT=isi_dengan_string_rahasia_panjang_acak
```

### Seed SSO clients
```bash
php artisan db:seed --class=SsoClientSeeder
```

Catat output secret yang ditampilkan — diperlukan untuk .env masing-masing client app.

---

## 2. Setup Client App (lppm-markandeya)

### Tambah ke .env lppm-markandeya
```env
SSO_SERVER_URL=https://sinergi.markandeyabali.ac.id
SSO_CLIENT_ID=lppm-markandeya
SSO_CLIENT_SECRET=lppm_secret_[NILAI_SSO_KEY_SALT]
APP_URL=https://lppm.markandeyabali.ac.id
```

### File yang sudah dibuat (copy ke client app lain juga)
```
config/sso.php
app/Http/Controllers/Sso/SsoClientController.php
app/Http/Middleware/SsoAuthenticate.php
```

### Tambah ke routes/web.php
```php
use App\Http\Controllers\Sso\SsoClientController;

Route::prefix('sso')->name('sso.')->group(function () {
    Route::get('/redirect', [SsoClientController::class, 'redirect'])->name('redirect');
    Route::get('/callback', [SsoClientController::class, 'callback'])->name('callback');
    Route::post('/logout',  [SsoClientController::class, 'logout'])->name('logout');
});
```

### Tambah alias middleware ke bootstrap/app.php
```php
$middleware->alias([
    'sso' => \App\Http\Middleware\SsoAuthenticate::class,
]);
```

### Proteksi route dengan middleware
```php
// Semua role yang diizinkan
Route::middleware('sso')->group(function () { ... });

// Hanya dosen
Route::middleware('sso:dosen')->group(function () { ... });

// Hanya mahasiswa
Route::middleware('sso:mahasiswa')->group(function () { ... });
```

### Akses user di controller
```php
public function dashboard(Request $request)
{
    $user     = $request->sso_user;       // array user data
    $userType = $request->sso_user_type;  // "dosen" | "mahasiswa" | "admin"

    // Contoh data dosen:
    // $user['nidn'], $user['nama'], $user['foto']

    // Contoh data mahasiswa:
    // $user['nim'], $user['nama'], $user['email'], $user['prodi']
}
```

### Tombol logout di Blade
```html
<form action="{{ route('sso.logout') }}" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>
```

---

## 3. Setup Client Apps Lainnya

### tte-dosen
```env
SSO_CLIENT_ID=tte-dosen
SSO_CLIENT_SECRET=tte_secret_[NILAI_SSO_KEY_SALT]
```

### lecturer-portal
```env
SSO_CLIENT_ID=lecturer-portal
SSO_CLIENT_SECRET=lecturer_secret_[NILAI_SSO_KEY_SALT]
```

### student-portal
```env
SSO_CLIENT_ID=student-portal
SSO_CLIENT_SECRET=student_secret_[NILAI_SSO_KEY_SALT]
```

### elearning-markandeya
```env
SSO_CLIENT_ID=elearning-markandeya
SSO_CLIENT_SECRET=elearning_secret_[NILAI_SSO_KEY_SALT]
```

---

## 4. API v1 (untuk Mobile App / Integrasi Langsung)

Endpoint | Method | Body | Keterangan
---------|--------|------|----------
`/api/v1/login` | POST | `{username, password}` | Auto-detect dosen/mahasiswa
`/api/v1/login/dosen` | POST | `{username, password}` | Login khusus dosen (NIDN)
`/api/v1/login/mahasiswa` | POST | `{username, password}` | Login khusus mahasiswa (NIM/email)
`/api/v1/me` | GET | — | User info (Bearer token)
`/api/v1/logout` | POST | — | Revoke token (Bearer token)

### Response login sukses
```json
{
    "access_token": "xxxx",
    "token_type": "Bearer",
    "expires_in": 28800,
    "user_type": "dosen",
    "user": {
        "id": 1,
        "nidn": "0123456789",
        "nama": "Dr. Contoh",
        "foto": "https://sinergi.markandeyabali.ac.id/storage/foto.jpg"
    }
}
```

---

## 5. SSO Endpoints (Server)

Endpoint | Method | Deskripsi
---------|--------|----------
`/sso/authorize` | GET | Mulai flow SSO (browser redirect)
`/sso/authenticate` | POST | Proses login SSO
`/api/sso/token` | POST | Exchange auth code → access token
`/api/sso/me` | GET | Get user info dari token
`/api/sso/logout` | POST | Revoke token + clear SSO session

---

## Token TTL

| User Type | TTL |
|-----------|-----|
| Dosen     | 8 jam |
| Mahasiswa | 24 jam |
| Admin     | 8 jam |
| API v1    | Sama seperti di atas |
