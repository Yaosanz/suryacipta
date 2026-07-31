# Deployment Runbook — Dokploy (GitHub Provider)

> **Versi Dokploy:** v2.x (terbaru)  
> **Repository:** `github.com/<org>/suryacipta`  
> **Branch Production:** `main`  
> **Domain:** `suryaciptaestetika.co.id` (frontend), `api.suryaciptaestetika.co.id` (backend API)

---

## 1. Prasyarat

### 1.1 Di Dokploy

- Server sudah terdaftar di Dokploy dashboard.
- Port `80`, `443` terbuka (Traefik handle SSL).
- Docker & Docker Compose sudah terinstall oleh Dokploy.
- Domain sudah pointing A record ke IP server.

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

### 2.2 Buat Project Baru

1. **Dashboard** → **New Project**.
2. Nama project: `suryacipta`.
3. Pilih **Docker Compose** sebagai tipe deployment.
4. Pilih repository: `suryacipta`.
5. Branch: `main`.
6. Docker Compose path: `./docker-compose.yml`.
7. Klik **Create**.

---

## 3. Environment Variables

Set environment variables berikut di Dokploy project settings → **Environment**:

### 3.1 Wajib (Required)

```bash
# Backend
APP_KEY=<generate dengan: php artisan key:generate --show>
DB_PASSWORD=<password kuat, minimal 32 karakter>
DB_ROOT_PASSWORD=<password root MySQL, minimal 32 karakter>
BACKEND_URL=https://api.suryaciptaestetika.co.id
FRONTEND_URL=https://suryaciptaestetika.co.id

# Frontend
NEXT_PUBLIC_SITE_URL=https://suryaciptaestetika.co.id
NEXT_PUBLIC_API_URL=https://api.suryaciptaestetika.co.id/api
CMS_API_URL=http://backend:8080/api
```

### 3.2 Opsional

```bash
# Database (default sudah aman)
DB_DATABASE=suryacipta
DB_USERNAME=suryacipta_app

# Frontend
NEXT_PUBLIC_WHATSAPP_NUMBER=6281282333021
NEXT_PUBLIC_GA_ID=G-XXXXXXXXXX    # Google Analytics ID (jika ada)
GOOGLE_SITE_VERIFICATION=         # Google Search Console (jika ada)

# CMS
CMS_PASSWORD_RESET_ENABLED=false  # Aktifkan hanya setelah mail provider dikonfigurasi
```

> **Catatan:** Jangan commit `.env` ke Git. Semua secrets disimpan di Dokploy Secret Manager.

---

## 4. Setup Domain & SSL (Traefik)

Setelah deployment pertama berhasil:

### 4.1 Frontend — `suryaciptaestetika.co.id`

1. **Dokploy** → **Project** → **suryacipta** → **Services** → **frontend**.
2. Klik **Domains** → **Add Domain**.
3. Masukkan: `suryaciptaestetika.co.id`.
4. Centang **Wildcard** → `*.suryaciptaestetika.co.id` (opsional).
5. Centang **SSL** → biarkan Traefik auto-provision via Let's Encrypt.
6. Simpan.

### 4.2 Backend API — `api.suryaciptaestetika.co.id`

1. **Services** → **backend** → **Domains** → **Add Domain**.
2. Masukkan: `api.suryaciptaestetika.co.id`.
3. Centang **SSL**.
4. Simpan.

### 4.3 Verifikasi DNS

Pastikan A record DNS mengarah ke IP VPS:

```text
suryaciptaestetika.co.id → <IP_VPS>
api.suryaciptaestetika.co.id → <IP_VPS>
```

---

## 5. Deploy Perdana

### 5.1 Trigger Deployment

1. **Dokploy Dashboard** → **Project suryacipta**.
2. Klik **Deploy** → pilih branch `main`.
3. Dokploy akan:
   - Clone repository.
   - Build image `frontend` dan `backend` sesuai Dockerfile.
   - Pull image `mysql:8.4`.
   - Buat network: `edge` (external) dan `internal`.
   - Buat volumes: `database_data`, `backend_uploads`, `backend_media`.
   - Start container: `database` → `backend` → `frontend`.
4. Proses memakan waktu **5–10 menit** untuk build pertama.

### 5.2 Verifikasi Container

```bash
# SSH ke VPS, cek status
docker ps

# Harusnya terlihat 3 container:
# - suryacipta_frontend
# - suryacipta_backend
# - suryacipta_database

# Cek log backend untuk memastikan migration sukses
docker logs suryacipta_backend --tail 50
```

### 5.3 Cek Health

```bash
# Health check frontend
curl -I https://suryaciptaestetika.co.id

# Health check backend
curl -I https://api.suryaciptaestetika.co.id/up

# Cek API content publik
curl https://api.suryaciptaestetika.co.id/api/v1/content
```

---

## 6. Bootstrap Admin CMS

Setelah backend berjalan, buat admin pertama:

### 6.1 SSH ke Server

```bash
ssh user@<IP_VPS>
```

### 6.2 Masuk ke Container Backend

```bash
docker exec -it suryacipta_backend bash
```

### 6.3 Generate App Key (hanya sekali)

```bash
php artisan key:generate
# Copy output KEY, simpan di Dokploy environment variable APP_KEY
```

### 6.4 Seed Content Awal

```bash
php artisan db:seed --class=CmsContentSeeder
```

### 6.5 Buat Admin User

```bash
php artisan cms:create-admin
```

Ikuti prompt:

- **Name:** `Super Admin`
- **Email:** `admin@suryaciptaestetika.co.id`
- **Password:** minimal 12 karakter (simpan di password manager)

### 6.6 Setup MFA

1. Login ke `https://api.suryaciptaestetika.co.id/admin`.
2. Scan QR code TOTP dengan Google Authenticator / Authy.
3. Simpan **recovery codes** yang ditampilkan.
4. Selesai.

---

## 7. Verifikasi Smoke Test

Setelah bootstrap, verifikasi semua fungsi:

### 7.1 Frontend

- [ ] Landing page: `https://suryaciptaestetika.co.id`
- [ ] Halaman produk: `/produk`
- [ ] Halaman blog: `/blog`
- [ ] Halaman portofolio: `/portofolio`
- [ ] Halaman kontak: `/kontak` → submit form RFQ
- [ ] Halaman tentang kami: `/tentang-kami`
- [ ] Halaman kebijakan privasi: `/kebijakan-privasi`
- [ ] Sitemap: `/sitemap.xml`
- [ ] Robots: `/robots.txt`

### 7.2 Backend API

- [ ] Health check: `GET /up` → `200`
- [ ] Content API: `GET /api/v1/content` → `200` + JSON
- [ ] Product detail: `GET /api/v1/products/{slug}` → `200`
- [ ] Blog detail: `GET /api/v1/posts/{slug}` → `200`
- [ ] Inquiry API: `POST /api/inquiries` → `201` (test dengan form kontak)

### 7.3 CMS Admin

- [ ] Login: `https://api.suryaciptaestetika.co.id/admin`
- [ ] Dashboard muncul dengan statistik
- [ ] CRUD produk, blog, portofolio, FAQ, testimonial, media
- [ ] Manage inquiries
- [ ] Audit log

---

## 8. Deployment Berikutnya (Update)

### 8.1 Manual via Dokploy Dashboard

1. Push perubahan ke `main`.
2. Buka **Dokploy Dashboard** → **Project suryacipta**.
3. Klik **Deploy** → pilih branch `main`.
4. Dokploy otomatis rebuild image yang berubah dan rolling update.

### 8.2 Auto Deploy (Opsional)

Aktifkan **Auto Deploy** di Dokploy:

1. **Project Settings** → **Deploy**.
2. Centang **Auto Deploy** → pilih branch `main`.
3. Setiap push ke `main` akan otomatis trigger deployment baru.

### 8.3 Rollback

1. **Project** → **Deployments** → pilih deployment sebelumnya.
2. Klik **Rollback**.
3. Dokploy akan deploy ulang image dari commit sebelumnya.

---

## 9. Monitoring & Maintenance

### 9.1 Monitoring Built-in Dokploy

- **Logs:** Real-time container logs di dashboard.
- **Metrics:** CPU, memory, disk usage per container.
- **Notifications:** Setup Slack/Discord/Email untuk deployment events.

### 9.2 Backup Database

Buat cron job di VPS untuk backup harian:

```bash
# Backup MySQL dari container
docker exec suryacipta_database mysqldump \
  -u root \
  -p"$DB_ROOT_PASSWORD" \
  suryacipta \
  | gzip > /backups/suryacipta-$(date +%Y%m%d-%H%M%S).sql.gz

# Hapus backup lebih dari 30 hari
find /backups -name "*.sql.gz" -mtime +30 -delete
```

### 9.3 Backup Volume

```bash
# Backup volume data (inquiry attachments, CMS media)
docker run --rm \
  -v backend_uploads:/source:ro \
  -v /backups:/target \
  alpine tar czf /target/backend-uploads-$(date +%Y%m%d).tar.gz -C /source .

docker run --rm \
  -v backend_media:/source:ro \
  -v /backups:/target \
  alpine tar czf /target/backend-media-$(date +%Y%m%d).tar.gz -C /source .
```

---

## 10. Troubleshooting

| Masalah                      | Penyebab                    | Solusi                                                 |
| ---------------------------- | --------------------------- | ------------------------------------------------------ |
| Container restart-loop       | DB connection gagal         | Cek environment `DB_HOST`, `DB_PASSWORD`               |
| 502 Bad Gateway              | Frontend/backend belum siap | Tunggu 30 detik, reload                                |
| SSL error                    | Domain belum pointing       | Verifikasi DNS A record                                |
| Migration gagal              | DB password mismatch        | Reset password di Dokploy env, redeploy                |
| 413 Request Entity Too Large | Upload file melebihi limit  | Cek `post_max_size` & `upload_max_filesize` di PHP ini |
| Frontend blank / 404         | NEXT_PUBLIC_API_URL salah   | Pastikan URL backend benar di env                      |
| Rate limit inquiry           | IP terkena throttle         | Whitelist IP atau tunggu 1 menit                       |

---

## 11. Quick Reference

### Perintah Berguna (SSH)

```bash
# Cek status semua container
docker ps -a --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"

# Log real-time backend
docker logs -f suryacipta_backend

# Log real-time frontend
docker logs -f suryacipta_frontend

# Akses shell backend
docker exec -it suryacipta_backend bash

# Akses MySQL
docker exec -it suryacipta_database mysql -u root -p

# Restart service
docker compose -p suryacipta restart backend

# Rebuild tanpa cache
docker compose -p suryacipta build --no-cache backend
```

### Struktur Volume

```text
docker volume ls
# local    suryacipta_backend_uploads     → storage/app/private (inquiries)
# local    suryacipta_backend_media       → storage/app/public (CMS media)
# local    suryacipta_database_data       → /var/lib/mysql
```

---

## 12. Security Checklist

- [ ] Semua secrets disimpan di Dokploy, bukan di .env file
- [ ] MFA TOTP aktif untuk semua admin CMS
- [ ] Password admin minimal 12 karakter
- [ ] Database hanya accessible dari internal network
- [ ] Container berjalan sebagai non-root (www-data / nextjs)
- [ ] Hanya port 80/443 terbuka di firewall VPS
- [ ] Backup database terjadwal otomatis
- [ ] Dependency audit: `composer audit` & `npm audit` sebelum deploy
- [ ] SSL via Let's Encrypt (auto-renewal oleh Traefik)
