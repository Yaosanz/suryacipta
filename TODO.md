# ✅ Checklist Persiapan Deployment Dokploy

## Fase 1: Pembersihan Workspace ✅

- [x] Hapus file `nul` (artifact Windows)
- [x] Hapus folder `.tmp/` (temporary SQLite)
- [x] Hapus `backend/storage/framework/qa-tmp/`
- [x] Hapus `backend/package-lock.json`
- [x] Update `.gitignore` (tambah `nul`, `*.tmp`, `qa-tmp/`, `package-lock.json`)

## Fase 2: Dokumen Deployment ✅

- [x] `DEPLOY_DOKPLOY.md` — Panduan lengkap deployment Dokploy v2
- [x] `SECURITY.md` — Security policy (reporting, PGP, supported versions)

## Fase 3: Perbaikan Image Produk ✅

- [x] Backend: `assetUrl()` return **relative path** (`/storage/xxx.jpg`)
- [x] Frontend `cms.ts`: Fungsi `resolveAssetUrl()` untuk menggabungkan API origin + path
- [x] Frontend `next.config.ts`: `remotePatterns` format object benar
- [x] Terapkan resolusi URL ke semua field media (products, portfolio, posts, sections, team, site)
- [x] **VERIFIED**: Produk dengan image (Kubah Masjid GRC) sekarang render `<img>` dengan URL benar

## Fase 4: Perbaikan SSRF Image Optimizer ✅

- [x] **MASALAH**: Next.js image optimizer memblokir IP privat (`127.0.0.1`) → error `upstream image resolved to private ip`
- [x] `next.config.ts`: Tambah `unoptimized: true` — gambar langsung dirender dari backend
- [x] `cms.ts`: Pisahkan `cmsApiUrl()` (fetch internal) vs `publicApiOrigin()` (URL aset publik)
- [x] `docker-compose.yml`: Tambah `NEXT_PUBLIC_API_URL` runtime di service frontend
- [x] `DEPLOY_DOKPLOY.md`: Dokumentasikan variable & perilaku baru
- [x] **VERIFIED**: `npm run build` sukses, TypeScript clean
- [x] **VERIFIED**: HTML render `src="http://127.0.0.1:8000/storage/..."` langsung (tanpa `/_next/image`)

## Fase 5: Git & Push ✅

- [x] Commit semua perubahan
- [x] Push ke branch `fix/cms-authorization-ux`
- [x] Buat Pull Request ke `main`

## Fase 6: Deployment Dokploy

- [ ] Buat **Github App** di Dokploy, pointing ke repo `Yaosanz/suryacipta`
- [ ] Pilih branch `main` (setelah PR di-merge)
- [ ] Compose path: `./docker-compose.yml`
- [ ] **PENTING**: Project harus bertipe **Docker Compose** — BUKAN Nixpacks. Error `Nixpacks was unable to generate a build plan` terjadi jika salah memilih tipe builder.
- [ ] Set environment variables (via Dokploy Secrets):
      | Variable | Keterangan |
      |---|---|
      | `APP_KEY` | Generate with `php artisan key:generate --show` |
      | `DB_HOST` | `suryacipta-suryacipta-iztnsy` (hostname managed Dokploy) |
      | `DB_PORT` | `3306` |
      | `DB_DATABASE` | `suryacipta` |
      | `DB_USERNAME` | `mysql` |
      | `DB_PASSWORD` | Password database (Dokploy Secret) |
      | `FRONTEND_URL` | `https://suryaciptaestetika.co.id` |
      | `BACKEND_URL` | `https://api.suryaciptaestetika.co.id` |
      | `NEXT_PUBLIC_WHATSAPP_NUMBER` | `6281282333021` |
      | `NEXT_PUBLIC_GA_ID` | (optional) Google Analytics ID |
- [ ] Deploy — migration jalan otomatis via entrypoint
- [ ] Bootstrap admin: `docker exec -it <backend_container> php artisan cms:create-admin`
- [ ] Setup domain & TLS di Traefik/Dokploy
- [ ] Test: buka website, cek produk, portofolio, blog, form kontak

## Fase 7: Migrasi ke Managed MySQL Dokploy ✅

- [x] **Database**: Buat database managed MySQL di Dokploy (nama: `suryacipta`, user: `mysql`)
- [x] **Catat hostname internal**: `suryacipta-suryacipta-iztnsy`
- [x] `docker-compose.yml`: Hapus service `database` internal + volume `database_data` + network `internal`
- [x] `docker-compose.yml`: Backend diarahkan ke `DB_HOST=suryacipta-suryacipta-iztnsy`, `DB_USERNAME=mysql`
- [x] `.env.docker.example`: Update `DB_HOST`, `DB_PORT`, `DB_USERNAME`, hapus `DB_ROOT_PASSWORD`
- [x] `DEPLOY_DOKPLOY.md`: Update lengkap — managed DB, env vars, backup, troubleshooting, quick reference
- [x] `README.md`: Update arsitektur & deployment section
- [ ] **Verifikasi**: Pastikan deployment Dokploy berhasil dengan project bertipe Docker Compose

## Fase 8: Perbaikan Error Nixpacks Build ✅ (Solusi terdokumentasi)

- [x] **MASALAH**: Dokploy mencoba Nixpacks build → `Nixpacks was unable to generate a build plan`
- [x] **PENYEBAB**: Project dibuat dengan tipe Nixpacks, padahal ini monorepo (frontend + backend) tanpa Dockerfile/package.json di root
- [x] **SOLUSI**: Buat ulang project dengan tipe **Docker Compose** (compose path `./docker-compose.yml`)
- [x] `DEPLOY_DOKPLOY.md`: Dokumentasikan error & solusinya di §2.2
