# Portal Manajemen Berita

Portal Manajemen Berita berbasis Laravel 12 dan Filament 5 yang dirancang dengan arsitektur scalable, workflow editorial multi-role, serta optimasi performa untuk mendukung 100.000+ artikel.

Sistem ini menerapkan Role-Based Access Control (RBAC) menggunakan Spatie Laravel Permission dan memisahkan business logic ke dalam Service Layer untuk menjaga clean architecture dan maintainability.

---

## Teknologi yang Digunakan

- Laravel 12
- Filament 5
- Spatie Laravel Permission
- MySQL
- Blade & TailwindCSS
- Laravel Scheduler

---

## Role & Hak Akses

Sistem memiliki 5 role:

- User
- Reporter
- Editor
- Admin
- Super Admin

Setiap role memiliki permission granular sesuai tanggung jawabnya.

---

## Workflow Editorial

Alur artikel:

Reporter/User  
→ Review Super Admin  
→ Review Editor  
→ Publish oleh Admin

Status artikel:

draft → pending → approved/revision/rejected → finished → published

Ketentuan sistem:

- Artikel yang sudah published tidak dapat diedit
- Author tetap penulis asli
- Editor dicatat sebagai "Disunting oleh"
- Setiap perubahan status tercatat dalam history log

---

## Fitur Utama

- Multi-role authentication (RBAC)
- Workflow editorial terstruktur
- Penjadwalan publish artikel
- Moderasi komentar
- Breaking News flag
- Trending system berbasis views
- Dashboard monitoring performa
- History perubahan artikel
- Service Layer Architecture

---

## Desain Skalabilitas

Dirancang untuk skala besar:

- Optimasi query Eloquent
- Indexing database
- Siap integrasi caching (Redis-ready)
- Clean code mengikuti PSR-12

Target kapasitas: 100.000+ artikel

---

## Instalasi

```bash
git clone https://github.com/YohanesSaputra405/Portal-Berita.git
cd Portal-Berita

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate --seed

npm install
npm run build

php artisan serve
```
