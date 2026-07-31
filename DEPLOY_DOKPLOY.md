# Deployment Runbook — Dokploy (GitHub Provider)

> **Versi Dokploy:** v2.x (terbaru)
> **Repository:** `github.com/Yaosanz/suryacipta`
> **Branch Production:** `main`
> **Builder:** Docker Compose (WAJIB — lihat §2.2)
> **Domain:** Default domain Dokploy gratis (Traefik auto-generated)

---

## 1. Prasyarat

### 1.1 Di Dokploy

- Server sudah terdaftar di Dokploy dashboard.
- Port `80`, `443` terbuka (Traefik handle SSL otomatis).
- Docker & Docker Compose sudah terinstall oleh Dokploy.
- **Tidak perlu domain sendiri** — Dokploy menyediakan domain default gratis dengan SSL Let's Encrypt otomatis.
- **Database MySQL managed sudah dibuat** di halaman **Database** Dokploy
  (bukan container database di dalam compose).

### 1.2 Di GitHub

- Repository sudah di-push ke GitHub (origin).
- Branch `main` adalah branch production.
- GitHub Personal Access Token (classic) dengan scope `repo` sudah dibuat.

---

## 2. Setup Awal di Dokploy

### 2.1 Hubungkan GitHub

1. Buka **Dokploy Dashboard** → **Settings** → **Git Providers**.
2. Klik **Add Git Provider** → pilih **GitHub**.
3. Masukkan **Personal Access Token** GitHub anda.
4. Authorize akses ke repository `suryacipta`.
5. Simpan.

### 2.2 BUAT PROJECT — WAJIB DOCKER COMPOSE (BUKAN NIXPACKS)

> **PENTING:** Jika project dibuat dengan tipe **Nixpacks** / **Node.js** / auto-builder,
> deployment akan gagal dengan error:

```text
> Starting nixpacks build...
> Nixpacks was unable to generate a build plan for this app.
> The contents of the app directory are: .github/, .dockerignore, .gitattributes, ...
> ✗ Nixpacks build failed
>
```

> Ini terjadi karena repository ini adalah **monorepo** (frontend + backend + compose),
> tidak ada `Dockerfile` / `package.json` di root sehingga Nixpacks tidak bisa menentukan
> build plan. **Repository ini hanya bisa dibangun via Docker Compose.**

**Cara benar membuat project:**

1. **Dashboard** → **New Project**.
2. Nama project: `suryacipta`.
3. Pilih **Docker Compose** sebagai tipe deployment — **jangan pilih Nixpacks / Node.js / lainnya**.
4. Pilih repository: `suryacipta`.
5. Branch: `main`.
6. Docker Compose path: `./docker-compose.yml`.
7. Klik **Create**.

**Jika project sudah terlanjur dibuat dengan Nixpacks:**

- **Opsi A (disarankan):** Hapus project Nixpacks, lalu buat ulang dengan tipe **Docker Compose**.
- **Opsi B:** Buka **Project → Settings → Build Type** → ubah ke **Docker Compose**,
  pastikan compose path `./docker-compose.yml`, lalu redeploy.

### 2.3 Buat Database MySQL Managed

1. **Dokploy Dashboard** → **Database** → **New Database** → pilih **MySQL**.
2. Isi:
   - Nama database: `suryacipta`
   - Username: `mysql`
   - Password: (password kuat anda — contoh `@Alexandra00123`)
   - Port: `3306`
3. Catat **hostname internal** yang tampil (contoh: `suryacipta-suryacipta-iztnsy`)
   — hostname inilah yang dipakai `DB_HOST`, **bukan** IP publik.
4. Simpan.

---

## 3. Environment Variables — LENGKAP

Set environment variables berikut di Dokploy project settings → **Environment**.

> **Cara generate `APP_KEY`:**
> Setelah deploy dan backend berjalan, SSH ke VPS lalu:

```bash
> docker exec -it <nama_container_backend> php artisan key:generate --show
>
```

> Copy hasilnya dan simpan ke variable `APP_KEY`.

### 3.1 Variable WAJIB — Kredensial Database Managed

| Variable      | Contoh Value                   | Keterangan                                         |
| ------------- | ------------------------------ | -------------------------------------------------- |
| `DB_HOST`     | `suryacipta-suryacipta-iztnsy` | Hostname internal dari halaman Database Dokploy    |
| `DB_PORT`     | `3306`                         | Port MySQL (default)                               |
| `DB_DATABASE` | `suryacipta`                   | Nama database yang anda buat                       |
| `DB_USERNAME` | `mysql`                        | User database yang anda buat                       |
| `DB_PASSWORD` | (rahasia)                      | Password user database — SIMPAN di Dokploy Secrets |
| `APP_KEY`     | `base64:xxx...`                | Generate setelah deploy pertama                    |

> **Tidak ada `DB_ROOT_PASSWORD`** — database dikelola langsung oleh Dokploy
> (managed), root credentials tidak diperlukan oleh Laravel.

### 3.2 Variable WAJIB — URL & App

| Variable               | Contoh Value              | Keterangan                                  |
| ---------------------- | ------------------------- | ------------------------------------------- |
| `BACKEND_URL`          | (lihat catatan di bawah)  | URL backend setelah deploy                  |
| `FRONTEND_URL`         | (lihat catatan di bawah)  | URL frontend setelah deploy                 |
| `NEXT_PUBLIC_SITE_URL` | (lihat catatan)           | URL publik frontend                         |
| `NEXT_PUBLIC_API_URL`  | (lihat catatan)           | URL API backend + `/api`                    |
| `CMS_API_URL`          | `http://backend:8080/api` | URL internal Docker untuk server-side fetch |

> **Penting:** `NEXT_PUBLIC_API_URL` dipakai ganda:
>
> 1. **Build time** — di-inject ke bundle browser (untuk fetch client-side & konstruksi URL aset).
> 2. **Runtime** — dipakai server untuk me-resolve path aset (`/storage/...`) menjadi URL publik.
>
> Jadi `NEXT_PUBLIC_API_URL` **harus diisi URL publik backend** (bukan `http://backend:8080`),
> karena browser pengguna tidak bisa menjangkau hostname Docker internal.
>
> Gambar CMS di-render langsung dari URL backend (`unoptimized`) untuk menghindari
> proteksi SSRF Next.js yang memblokir IP privat saat image optimizer mencoba fetch.
> Image sudah dikompresi saat upload, jadi optimasi Next.js tidak diperlukan.

### 3.3 Variable OPSIONAL

| Variable                      | Contoh Value        | Keterangan                                                  |
| ----------------------------- | ------------------- | ----------------------------------------------------------- |
| `NEXT_PUBLIC_WHATSAPP_NUMBER` | `6281282333021`     | Nomor WhatsApp untuk tombol WA floating                     |
| `NEXT_PUBLIC_GA_ID`           | `G-XXXXXXXXXX`      | Google Analytics ID (isi jika punya)                        |
| `GOOGLE_SITE_VERIFICATION`    | (string verifikasi) | Google Search Console (isi jika perlu)                      |
| `CMS_PASSWORD_RESET_ENABLED`  | `false`             | Ubah ke `true` hanya jika mail provider sudah dikonfigurasi |

---

## 4. Cara Mendapatkan URL Default Dokploy

Setelah deployment pertama berhasil:

1. **Dokploy Dashboard** → **Project suryacipta** → **Services**.
2. Akan ada **2 service**: `frontend` dan `backend` (database tidak ada di compose
   karena dikelola terpisah oleh Dokploy).
3. Klik service **frontend** → lihat bagian **Domains** → akan ada URL default seperti:

```
   https://suryacipta-frontend-<random>.dokploy.app

```

4. Klik service **backend** → lihat bagian **Domains** → URL default seperti:

```
   https://suryacipta-backend-<random>.dokploy.app

```

5. Catat 2 URL tersebut, gunakan untuk mengisi `BACKEND_URL`, `FRONTEND_URL`, `NEXT_PUBLIC_SITE_URL`, `NEXT_PUBLIC_API_URL`.

---

## 5. Update Environment Variable Setelah Deploy Pertama

Karena kita belum tahu URL default sebelum deploy, ikuti langkah ini:

### Langkah 1: Deploy dengan Variable Sementara

Set di Dokploy environment dulu untuk **deploy pertama**:

```bash
BACKEND_URL=http://backend:8080
FRONTEND_URL=http://frontend:3000
NEXT_PUBLIC_SITE_URL=http://frontend:3000
NEXT_PUBLIC_API_URL=http://backend:8080/api
CMS_API_URL=http://backend:8080/api
DB_HOST=suryacipta-suryacipta-iztnsy
DB_PORT=3306
DB_DATABASE=suryacipta
DB_USERNAME=mysql
DB_PASSWORD=<password database anda>
APP_KEY=base64:placeholder (nanti diganti)
```

> **Catatan:** Variable `BACKEND_URL` dan `FRONTEND_URL` di docker-compose digunakan untuk build argumen Docker. Untuk deploy pertama, URL internal sudah cukup karena container bisa saling komunikasi via Docker network.

> **Catatan:** Database managed Dokploy sudah dibuat **sebelum** deploy — pastikan
> hostname `DB_HOST` sudah benar agar migration berjalan saat container pertama start.

### Langkah 2: Deploy & Dapatkan URL Default

1. Klik **Deploy** → tunggu selesai (5-10 menit).
2. Setelah semua container running, buka **Services** → **frontend** → copy default domain.
3. Buka **Services** → **backend** → copy default domain.

Contoh:

```text
Frontend: https://suryacipta-frontend-abc123.dokploy.app
Backend:  https://suryacipta-backend-abc123.dokploy.app
```

### Langkah 3: Update Environment Variable

Edit environment Dokploy dengan URL asli:

```bash
# === UPDATE INI SETELAH DEPLOY ===
BACKEND_URL=https://suryacipta-backend-abc123.dokploy.app
FRONTEND_URL=https://suryacipta-frontend-abc123.dokploy.app
NEXT_PUBLIC_SITE_URL=https://suryacipta-frontend-abc123.dokploy.app
NEXT_PUBLIC_API_URL=https://suryacipta-backend-abc123.dokploy.app/api

# === INI TETAP (TIDAK BERUBAH) ===
CMS_API_URL=http://backend:8080/api
DB_HOST=suryacipta-suryacipta-iztnsy
DB_PORT=3306
DB_DATABASE=suryacipta
DB_USERNAME=mysql
DB_PASSWORD=<password database anda>
APP_KEY=<hasil dari generate>

# === OPSIONAL ===
NEXT_PUBLIC_WHATSAPP_NUMBER=6281282333021
NEXT_PUBLIC_GA_ID=
GOOGLE_SITE_VERIFICATION=
CMS_PASSWORD_RESET_ENABLED=false
```

### Langkah 4: Redeploy

1. Klik **Deploy** sekali lagi dengan environment variable baru.
2. Sekarang aplikasi bisa diakses via domain Dokploy default.

---

## 6. Generate APP_KEY & Bootstrap Admin

### 6.1 Generate APP_KEY

```bash
# SSH ke VPS
ssh user@<IP_VPS>

# Masuk ke container backend
docker exec -it suryacipta_backend bash

# Generate key
php artisan key:generate --show
# Output: base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

Copy output dan simpan ke Dokploy environment `APP_KEY`, lalu redeploy.

### 6.2 Seed Content Awal

```bash
docker exec -it suryacipta_backend php artisan db:seed --class=CmsContentSeeder
```

> Migration dan seeding otomatis berjalan via entrypoint saat container pertama start
> (`RUN_MIGRATIONS=true`). Perintah di atas hanya diperlukan jika seeding manual.

### 6.3 Buat Admin User

```bash
docker exec -it suryacipta_backend php artisan cms:create-admin
```

Ikuti prompt:

- **Name:** `Super Admin`
- **Email:** `admin@suryaciptaestetika.co.id`
- **Password:** minimal 12 karakter

### 6.4 Setup MFA

1. Buka browser → `https://suryacipta-backend-xxx.dokploy.app/admin`
2. Login dengan email & password yang dibuat.
3. Scan QR code TOTP dengan Google Authenticator / Authy.
4. Simpan **recovery codes**.
5. Selesai ✅

---

## 7. Verifikasi Smoke Test

### 7.1 Frontend

- [ ] Buka `https://suryacipta-frontend-xxx.dokploy.app` — landing page tampil
- [ ] `/produk` — halaman produk
- [ ] `/blog` — halaman blog
- [ ] `/portofolio` — halaman portofolio
- [ ] `/kontak` — form RFQ bisa diisi & submit
- [ ] `/tentang-kami` — halaman tentang
- [ ] `/kebijakan-privasi` — halaman privasi
- [ ] `/sitemap.xml` — sitemap
- [ ] `/robots.txt` — robots

### 7.2 Backend API

```bash
# Health check
curl -I https://suryacipta-backend-xxx.dokploy.app/up

# Content API
curl https://suryacipta-backend-xxx.dokploy.app/api/v1/content

# Cek apakah data dari managed DB keluar (harus berisi JSON konten)
```

### 7.3 CMS Admin

- [ ] Login: `https://suryacipta-backend-xxx.dokploy.app/admin`
- [ ] Dashboard muncul
- [ ] CRUD konten berfungsi

---

## 8. Deployment Berikutnya (Update)

### 8.1 Manual via Dokploy Dashboard

1. Push perubahan ke `main`.
2. **Dokploy Dashboard** → **Project suryacipta** → **Deploy**.
3. Dokploy otomatis rebuild dan rolling update.

### 8.2 Auto Deploy (Opsional)

1. **Project Settings** → **Deploy** → centang **Auto Deploy** → branch `main`.
2. Setiap push ke `main` auto-deploy.

### 8.3 Rollback

1. **Project** → **Deployments** → pilih deployment sebelumnya → **Rollback**.

---

## 9. Monitoring & Maintenance

### 9.1 Monitoring Built-in Dokploy

- **Logs:** Real-time container logs di dashboard.
- **Metrics:** CPU, memory, disk per container.
- **Database:** Utilisasi & backup database managed di halaman **Database** Dokploy.

### 9.2 Backup Database

Database adalah **managed MySQL Dokploy**. Gunakan fitur backup bawaan Dokploy:

1. **Dokploy Dashboard** → **Database** → pilih database `suryacipta`.
2. Klik **Backup** → buat jadwal backup & simpan ke lokasi eksternal (S3, SFTP, dll).

Alternatif manual lewat SSH ke VPS (container lain di network yang sama):

```bash
# Cari nama container database
docker ps --format "table {{.Names}}\t{{.Image}}" | grep mysql

# Dump dari dalam container database (ganti <db_container>)
docker exec <db_container> mysqldump \
  -u mysql \
  -p"<DB_PASSWORD>" \
  --single-transaction \
  suryacipta \
  | gzip > /backups/suryacipta-$(date +%Y%m%d-%H%M%S).sql.gz
```

### 9.3 Backup Volume

```bash
docker run --rm \
  -v suryacipta_backend_uploads:/source:ro \
  -v /backups:/target \
  alpine tar czf /target/uploads-$(date +%Y%m%d).tar.gz -C /source .

docker run --rm \
  -v suryacipta_backend_media:/source:ro \
  -v /backups:/target \
  alpine tar czf /target/media-$(date +%Y%m%d).tar.gz -C /source .
```

---

## 10. Troubleshooting

| Masalah                       | Penyebab                               | Solusi                                                   |
| ----------------------------- | -------------------------------------- | -------------------------------------------------------- |
| `Nixpacks build failed`       | Project dibuat dengan tipe Nixpacks    | Ubah ke **Docker Compose** (lihat §2.2)                  |
| Backend restart-loop          | DB connection gagal                    | Cek `DB_HOST`/`DB_PASSWORD` di Dokploy environment       |
| `SQLSTATE[HY000] [2002]`      | Hostname DB salah                      | Pastikan `DB_HOST` = hostname internal (bukan IP publik) |
| Migration gagal               | `APP_KEY` belum diisi                  | Generate key, update environment, redeploy               |
| 502 Bad Gateway               | Service belum siap                     | Tunggu 30 detik, reload                                  |
| 413 Request Entity Too Large  | Upload > 5MB                           | Sudah dibatasi di PHP config                             |
| Rate limit inquiry            | IP terkena throttle                    | Tunggu 1 menit                                           |
| Frontend tidak bisa fetch API | `NEXT_PUBLIC_API_URL` salah            | Pastikan pakai URL backend yg benar                      |
| Gambar produk tidak muncul    | `NEXT_PUBLIC_API_URL` di runtime salah | Pastikan build args & runtime env konsisten              |

---

## 11. Quick Reference

### Perintah Berguna (SSH ke VPS)

```bash
# Lihat container
docker ps --format "table {{.Names}}\t{{.Status}}"

# Log backend
docker logs -f suryacipta_backend

# Log frontend
docker logs -f suryacipta_frontend

# Masuk container backend
docker exec -it suryacipta_backend bash

# Restart service
docker compose -p suryacipta restart backend
```

### Volume Penting

```text
suryacipta_backend_uploads → storage/app/private (inquiries)
suryacipta_backend_media   → storage/app/public (CMS media)
```

> **Database MySQL TIDAK memakai volume compose** — data disimpan & dikelola
> sepenuhnya oleh service Database managed Dokploy.

### Env Quick Reference

| Variable      | Nilai yang dipakai             |
| ------------- | ------------------------------ |
| `DB_HOST`     | `suryacipta-suryacipta-iztnsy` |
| `DB_PORT`     | `3306`                         |
| `DB_DATABASE` | `suryacipta`                   |
| `DB_USERNAME` | `mysql`                        |
| `DB_PASSWORD` | (Dokploy Secret)               |
