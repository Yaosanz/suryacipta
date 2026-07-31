# Security Policy — Surya Cipta Estetica

## Reporting a Vulnerability

Jika anda menemukan celah keamanan pada platform ini, **jangan** buka GitHub Issue publik.  
Laporkan langsung ke:

- **Email:** security@suryaciptaestetika.co.id
- **Response time:** Maksimal 48 jam untuk konfirmasi penerimaan laporan.

Kami berkomitmen untuk menangani laporan dalam **14 hari kerja** sejak validasi.

---

## Security Baseline

### Authentication & Authorization

| Area                  | Implementasi                                   |
| --------------------- | ---------------------------------------------- |
| Admin authentication  | Laravel built-in + session-based               |
| Multi-factor auth     | TOTP wajib (via Filament)                      |
| Password minimum      | 12 karakter (dipaksa oleh `cms:create-admin`)  |
| Bcrypt rounds         | 12                                             |
| Session encryption    | AES-256-CBC (via `SESSION_ENCRYPT=true`)       |
| Session secure cookie | Hanya via HTTPS (`SESSION_SECURE_COOKIE=true`) |
| Authorization         | Laravel Policy per model                       |

### API Security

| Area               | Implementasi                                                      |
| ------------------ | ----------------------------------------------------------------- |
| CORS               | Whitelist origin via `CORS_ALLOWED_ORIGINS`                       |
| Origin enforcement | `REQUIRE_ORIGIN_HEADER=true` melarang request tanpa Origin header |
| Rate limiting      | 10 request/menit per IP untuk `/api/inquiries`                    |
| Input validation   | Form Request + validasi server-side                               |
| Honeypot           | Field `website` tersembunyi pada form RFQ                         |
| File upload        | Hanya PDF/JPEG/PNG/WebP, maks 5 MB                                |
| Sanitized output   | Markdown HTML disanitasi sebelum dirender                         |

### Container Security

| Area                      | Implementasi                               |
| ------------------------- | ------------------------------------------ |
| Read-only root filesystem | `read_only: true` di semua service         |
| Non-root user             | `www-data` (backend) / `nextjs` (frontend) |
| No new privileges         | `no-new-privileges:true`                   |
| Drop all capabilities     | `cap_drop: ALL`                            |
| PIDs limit                | 200 (frontend/backend), 300 (database)     |
| Temporary filesystem      | `tmpfs` dengan size terbatas               |
| Internal network          | Database tidak terekspos ke network `edge` |

### HTTP Security Headers

| Header                         | Value                                            |
| ------------------------------ | ------------------------------------------------ |
| `Content-Security-Policy`      | Ketat, hanya allow Google Analytics & Maps       |
| `Cross-Origin-Opener-Policy`   | `same-origin`                                    |
| `Cross-Origin-Resource-Policy` | `same-origin`                                    |
| `Referrer-Policy`              | `strict-origin-when-cross-origin`                |
| `X-Content-Type-Options`       | `nosniff`                                        |
| `X-Frame-Options`              | `DENY`                                           |
| `Permissions-Policy`           | Semua sensor nonaktif (camera, mic, geolocation) |
| `Strict-Transport-Security`    | 1 tahun (production only)                        |
| `Server`                       | Dimatikan                                        |
| `X-Powered-By`                 | Dimatikan                                        |

### Database Security

| Area              | Implementasi                       |
| ----------------- | ---------------------------------- |
| Network exposure  | Hanya di network `internal`        |
| User privilege    | Non-root user (`suryacipta_app`)   |
| Root password     | Wajib, disimpan di Dokploy secret  |
| Local-infile      | Dimatikan (`--local-infile=0`)     |
| Skip name resolve | Diaktifkan (`--skip-name-resolve`) |

### Audit & Logging

- Semua aktivitas CMS tercatat di Spatie Activitylog.
- Audit hanya bisa dibaca (read-only), tidak bisa dihapus.
- Log backend hanya mengarah ke `stderr`.
- Log level production: `warning`.

---

## Dependencies

- Lock file (`composer.lock` / `package-lock.json`) wajib di-commit.
- Dependabot aktif untuk monitoring update dependency setiap minggu.
- Sebelum deploy, jalankan:
  ```bash
  composer audit       # Backend vulnerability scan
  npm audit            # Frontend vulnerability scan
  ```

---

## Deployment Security

1. Semua secrets disimpan di **Dokploy Secret Manager**, bukan di `.env` file.
2. Hanya port **80** dan **443** yang terbuka di firewall VPS.
3. SSL/TLS via **Let's Encrypt** (auto-renewal oleh Traefik).
4. Backup database dan volume dilakukan otomatis via cron.

---

## No Warranty

Konfigurasi keamanan ini tidak menjamin aplikasi bebas dari kerentanan secara permanen.  
Dependency harus diperbarui secara rutin, backup diuji periodik, dan setiap perubahan kode  
harus melalui code review.
