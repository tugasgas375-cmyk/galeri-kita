# Galeri Kita — Our Memories

Website galeri momen bersama pasangan, dibangun dengan **Laravel 12** + **Tailwind CSS**, siap di-deploy ke **Railway**.

## Fitur
- Halaman publik galeri (hero + penghitung "hari bersama" + kartu momen + lightbox foto)
- Panel admin login (`/admin/login`) untuk unggah foto & tulis cerita
- Upload banyak foto sekaligus, edit judul/tanggal/deskripsi (CRUD lengkap)
- Kelola foto per momen: tambah foto lagi, edit caption per foto, hapus foto per-satu
- Unduh album sebagai ZIP per momen
- Penghitung "berapa kali dilihat" per momen
- Pencarian momen di dashboard admin + paginasi halaman utama
- Foto tersimpan di `storage/app/public` dan dilayani via symlink `public/storage`

## Menjalankan di lokal (XAMPP)

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build

# database sqlite
php artisan migrate --force
php artisan storage:link

php artisan serve
```

Lalu buka:
- Website: http://127.0.0.1:8000
- Admin: http://127.0.0.1:8000/admin/login
- Login bawaan: `admin` / `rahasia123` (ubah lewat `ADMIN_USERNAME` & `ADMIN_PASSWORD` di `.env`)
- Atur tanggal pertama bersama lewat `FIRST_DATE` (format `YYYY-MM-DD`) untuk penghitung "hari bersama"

## Deploy ke Railway

### 1. Push ke GitHub
```bash
git init
git add .
git commit -m "init galeri"
git branch -M main
git remote add origin https://github.com/<username>/galeri-kita.git
git push -u origin main
```

> Pastikan `.env` TIDAK ikut ter-commit. File `.env.example` adalah template yang aman.

### 2. Buat project di Railway
1. Buka [railway.app](https://railway.app) → **New Project** → **Deploy from GitHub repo**
2. Pilih repo `galeri-kita`
3. Railway otomatis mendeteksi `Dockerfile` (sudah disediakan)

### 3. Tambah PostgreSQL
1. **+ New** → **Database** → klik **PostgreSQL**
2. Railway akan menaruh variable `PGHOST`, `PGPORT`, `PGDATABASE`, `PGUSER`, `PGPASSWORD` otomatis ke app service

### 4. Volume untuk foto upload
Agar foto yang diunggah tidak hilang saat redeploy:
1. Buka service app → tab **Volumes**
2. **Add Volume** → Mount Path: `/var/www/html/storage/app/public`

### 5. Variables penting (tab Variables service app)
| Variable | Nilai |
|---|---|
| `APP_KEY` | jalankan `php artisan key:generate --show` di lokal lalu tempel |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | URL domain Railway kamu (contoh `https://xxx.up.railway.app`) |
| `APP_NAME` | `Our Memories` |
| `APP_TAGLINE` | `Momen terindah kita berdua` |
| `APP_LOCALE` | `id` |
| `DB_CONNECTION` | `pgsql` |
| `ADMIN_USERNAME` | ganti dari default |
| `ADMIN_PASSWORD` | ganti dari default |
| `FIRST_DATE` | tanggal mulai bersama, contoh `2020-01-01` |
| `HOME_PER_PAGE` | jumlah momen per halaman (default `6`) |

> Railway Postgres plugin otomatis menyetel `PGHOST`/`PGPORT`/`PGDATABASE`/`PGUSER`/`PGPASSWORD`. `.env.example` dan `config/database.php` sudah disiapkan untuk itu.

### 6. Deploy
- Railway otomatis menjalankan migration (`php artisan migrate --force`) dan membuat symlink storage setiap deploy.
- Setelah deploy sukses, buka domain yang di-generate Railway → **Generate Domain** di tab Settings → Networking.

## Struktur
```
app/Models/Moment.php        # model momen (hasMany photos)
app/Models/Photo.php         # model foto (belongsTo moment)
app/Http/Controllers/        # MomentController (publik), AdminController, AuthController
resources/views/             # layout publik/admin + halaman
config/gallery.php           # username & password admin
Dockerfile                   # image PHP 8.2 + Apache untuk Railway
railway.json                 # konfigurasi deploy Railway
```