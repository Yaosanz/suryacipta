# Surya Cipta Estetica

Platform company profile dan CMS untuk **CV. Surya Cipta Estetica Mandiri**, produsen dan installer
GRC di Cibinong, Bogor. Repository ini merupakan monorepo production-oriented yang menyatukan
website publik, API, panel admin, database schema, dan konfigurasi deployment.

## Arsitektur

```text
Browser
  |
  +-- suryaciptaestetika.co.id
  |     Next.js 16 / React 19
  |     SEO, landing page, katalog, artikel, RFQ
  |
  +-- api.suryaciptaestetika.co.id
        Laravel 13 / Filament 5
        API read-only, inquiry API, CMS, MFA, audit
          |
          +-- MySQL 8.4
          +-- private inquiry storage
          +-- public CMS media storage
```

Next.js mengambil konten terbit dari Laravel dengan revalidasi 60 detik. Draft, artikel terjadwal,
inquiry, pengguna, dan audit tidak pernah dimasukkan ke payload publik. Docker Compose menjalankan
frontend, backend, dan MySQL sebagai container terpisah dalam satu release atomik.

## Fitur

### Website Publik

- Landing page responsif untuk company profile GRC.
- Katalog dan detail produk berbasis CMS.
- Portofolio terfilter, artikel, FAQ, testimonial, CTA, dan kontak.
- Form RFQ dengan validasi client/server, honeypot, rate limit, dan lampiran privat.
- Metadata, canonical URL, Open Graph, JSON-LD, sitemap, robots, dan ISR.
- Fallback build-time terkontrol ketika CMS belum tersedia pada deployment pertama.

### CMS Admin

- Identitas perusahaan dan section landing page.
- CRUD produk, portofolio, artikel, FAQ, testimonial, serta pustaka media.
- Pengelolaan status inquiry tanpa mengekspos endpoint administrasi publik.
- Dashboard ringkas dan audit aktivitas read-only.
- MFA TOTP wajib beserta recovery code.
- Strict model authorization melalui Laravel Policy.

| Role | Hak akses |
|---|---|
| `Super Admin` | Pengguna, pengaturan, seluruh konten, inquiry, media, dan audit |
| `Content Manager` | Konten terbit, media, pengaturan situs, dan inquiry |
| `Editor` | Membuat dan mengubah draft; tidak dapat menerbitkan atau menghapus konten |

## Stack

| Area | Teknologi |
|---|---|
| Frontend | Next.js 16, React 19, TypeScript, Tailwind CSS 4 |
| Form | React Hook Form, Zod |
| Backend | PHP 8.3+, Laravel 13 |
| CMS | Filament 5, Livewire 4 |
| Audit | Spatie Laravel Activitylog 5 |
| Database | MySQL 8.4 |
| Deployment | Docker, Docker Compose, Dokploy, Traefik |
| Quality | ESLint, TypeScript, PHPUnit, Laravel Pint, Dependabot |

## Struktur Repository

```text
.
|-- frontend/              Next.js public website
|-- backend/               Laravel API and Filament CMS
|-- .github/dependabot.yml Dependency update policy
|-- docker-compose.yml     Production service topology
|-- .env.docker.example    Production environment template
|-- DEPLOY_DOKPLOY.md      Deployment and rollback runbook
`-- SECURITY.md            Security baseline and reporting
```

## Prasyarat Lokal

- Node.js 22+ dan npm
- PHP 8.3+ dengan `pdo_mysql`, `mbstring`, `intl`, dan `zip`
- Composer 2
- MySQL 8
- Laragon dapat digunakan dengan user MySQL lokal `root` tanpa password

Database lokal yang digunakan adalah `suryacipta`. Konfigurasi tanpa password hanya untuk Laragon
lokal dan tidak boleh digunakan pada staging atau production.

## Setup Backend

```powershell
cd backend
Copy-Item .env.example .env
composer install
php artisan key:generate
mysql -u root -e "CREATE DATABASE IF NOT EXISTS suryacipta CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
php artisan db:seed --class=CmsContentSeeder
php artisan cms:create-admin
php artisan serve --host=127.0.0.1 --port=8000
```

Perintah `cms:create-admin` meminta password minimal 12 karakter secara tersembunyi. Pada login
pertama, pengguna wajib menyiapkan MFA TOTP dan menyimpan recovery code.

Endpoint lokal:

- API health: `http://127.0.0.1:8000/up`
- API konten: `http://127.0.0.1:8000/api/v1/content`
- CMS admin: `http://127.0.0.1:8000/admin`

## Setup Frontend

```powershell
cd frontend
Copy-Item .env.example .env.local
npm ci
npm run dev -- --hostname 127.0.0.1 --port 3000
```

Pastikan environment frontend lokal berisi:

```dotenv
NEXT_PUBLIC_SITE_URL=http://127.0.0.1:3000
NEXT_PUBLIC_API_URL=http://127.0.0.1:8000/api
CMS_API_URL=http://127.0.0.1:8000/api
```

Website tersedia di `http://127.0.0.1:3000`.

## API

### Konten Publik

| Method | Endpoint | Keterangan |
|---|---|---|
| `GET` | `/api/v1/content` | Identitas, section, produk, portofolio, artikel, FAQ, testimonial |
| `GET` | `/api/v1/products/{slug}` | Detail produk yang sudah terbit |
| `GET` | `/api/v1/posts/{slug}` | Artikel terbit dengan Markdown tersanitasi |

### Request for Quotation

`POST /api/inquiries` menerima `multipart/form-data`:

| Field | Aturan |
|---|---|
| `name` | Wajib, 2-100 karakter |
| `company` | Opsional, maksimal 120 karakter |
| `email` | Wajib, email valid |
| `phone` | Wajib, 8-20 karakter nomor |
| `projectType` | Wajib, nilai dari allowlist |
| `estimatedArea` | Opsional, maksimal 30 karakter |
| `budgetRange` | Opsional, nilai dari allowlist |
| `message` | Wajib, 20-3000 karakter |
| `attachment` | Opsional, PDF/JPEG/PNG/WebP, maksimal 5 MB |
| `website` | Honeypot, harus kosong |

Respons sukses menggunakan HTTP `201`; validasi `422`; rate limit `429`.

## Environment Production

Gunakan [.env.docker.example](./.env.docker.example) sebagai referensi. Nilai berikut wajib disimpan
di secret manager Dokploy, bukan di Git:

- `APP_KEY`
- `DB_PASSWORD`
- `DB_ROOT_PASSWORD`
- kredensial mail provider
- token layanan eksternal yang bersifat privat

`NEXT_PUBLIC_*` tertanam ke bundle browser dan tidak boleh berisi secret. Password reset CMS
nonaktif secara default; aktifkan `CMS_PASSWORD_RESET_ENABLED=true` hanya setelah mail provider
production selesai dikonfigurasi.

## Docker

Validasi konfigurasi:

```bash
cp .env.docker.example .env
docker compose config
docker compose build
docker compose up -d
docker compose ps
```

Compose tidak mempublikasikan MySQL ke host. Data persisten disimpan pada:

- `database_data`
- `backend_uploads`
- `backend_media`

Jangan menghapus named volume tanpa backup dan prosedur restore yang teruji.

## Quality Gates

Frontend:

```powershell
cd frontend
npm run lint
npx tsc --noEmit
npm run build
npm audit
```

Backend:

```powershell
cd backend
php -d sys_temp_dir="../.tmp" vendor/bin/phpunit
php -d sys_temp_dir="../.tmp" vendor/bin/pint --test
composer audit
```

Test backend menggunakan SQLite in-memory sehingga tidak mengubah MySQL lokal. Override
`sys_temp_dir` hanya diperlukan pada workstation Windows yang tidak dapat menulis ke temp global.

## Strategi VCS

Repository menggunakan **trunk-based development**:

- `main` adalah satu-satunya branch production dan sumber deployment Dokploy.
- Perubahan dikembangkan pada branch singkat seperti `feat/cms-media` atau `fix/inquiry-rate-limit`.
- Setiap branch masuk ke `main` melalui pull request setelah quality gates lulus.
- Frontend dan backend tidak dipisahkan ke branch permanen karena keduanya berbagi kontrak API,
  migration, Compose, dan release version.
- Tag release memakai Semantic Versioning, misalnya `v1.0.0`.

Format commit mengikuti [Conventional Commits](https://www.conventionalcommits.org/):

```text
feat(cms): add portfolio publishing workflow
fix(api): reject future scheduled posts
docs(deploy): document Dokploy rollback
test(security): cover editor publication policy
chore(deps): update locked dependencies
```

Breaking change menggunakan `!`, misalnya `feat(api)!: revise public content schema`.

## Deployment Dokploy

Dokploy harus mengambil branch `main` dan Compose path `./docker-compose.yml`. Frontend dan backend
tetap menjadi service terpisah, tetapi dirilis dari commit yang sama. Migration berjalan melalui
entrypoint backend setelah database sehat.

Ikuti [DEPLOY_DOKPLOY.md](./DEPLOY_DOKPLOY.md) untuk environment, domain, TLS, bootstrap admin,
backup, smoke test, monitoring, dan rollback.

## Security

Kontrol utama meliputi CSP dan security headers, CORS allowlist, exact Origin enforcement, CSRF,
Eloquent parameter binding, output sanitization, rate limiting, randomized upload paths, private
inquiry storage, MFA, role policy, encrypted session, non-root containers, dan audit minim data.

Tidak ada konfigurasi yang menjamin aplikasi bebas kerentanan secara permanen. Dependency harus
diperbarui, backup diuji, dan perubahan tetap melalui review. Lihat [SECURITY.md](./SECURITY.md).

## License

Proprietary. Seluruh hak atas source code dan materi bisnis berada pada CV. Surya Cipta Estetica
Mandiri kecuali dependency open-source yang tetap mengikuti lisensinya masing-masing.
