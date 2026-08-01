import type { NextConfig } from 'next';

const apiUrl = process.env.NEXT_PUBLIC_API_URL ?? 'http://127.0.0.1:8000/api';

function getOrigin(url: string): { protocol: 'http' | 'https'; hostname: string; port: string | undefined } {
  try {
    const parsed = new URL(url);
    const p = parsed.protocol.replace(':', '');
    return {
      protocol: p === 'https' ? 'https' : 'http',
      hostname: parsed.hostname,
      port: parsed.port || undefined,
    };
  } catch {
    throw new Error('NEXT_PUBLIC_API_URL harus berupa URL absolut yang valid.');
  }
}

const apiOrigin = getOrigin(apiUrl);
const isProduction = process.env.NODE_ENV === 'production';

function originHost(): string {
  let h = `${apiOrigin.protocol}://${apiOrigin.hostname}`;
  if (apiOrigin.port) h += `:${apiOrigin.port}`;
  return h;
}

const originHostStr = originHost();

const scriptSources = ["'self'", "'unsafe-inline'", ...(!isProduction ? ["'unsafe-eval'"] : []), 'https://www.googletagmanager.com'].join(' ');
const contentSecurityPolicy = [
  "default-src 'self'",
  "base-uri 'self'",
  "object-src 'none'",
  "frame-ancestors 'none'",
  "form-action 'self'",
  `script-src ${scriptSources}`,
  "style-src 'self' 'unsafe-inline'",
  "font-src 'self' data:",
  `img-src 'self' data: ${originHostStr} https://*.google.com https://*.gstatic.com`,
  "frame-src 'self' https://www.google.com https://maps.google.com",
  `connect-src 'self' ${originHostStr} https://www.google-analytics.com https://*.google-analytics.com`,
  "media-src 'self'",
  "worker-src 'self' blob:",
  "manifest-src 'self'",
  ...(isProduction ? ['upgrade-insecure-requests'] : []),
].join('; ');

const securityHeaders = [
  { key: 'Content-Security-Policy', value: contentSecurityPolicy },
  { key: 'Cross-Origin-Opener-Policy', value: 'same-origin' },
  { key: 'Cross-Origin-Resource-Policy', value: 'same-origin' },
  { key: 'Referrer-Policy', value: 'strict-origin-when-cross-origin' },
  { key: 'X-Content-Type-Options', value: 'nosniff' },
  { key: 'X-DNS-Prefetch-Control', value: 'off' },
  { key: 'X-Frame-Options', value: 'DENY' },
  { key: 'X-Permitted-Cross-Domain-Policies', value: 'none' },
  {
    key: 'Permissions-Policy',
    value: 'camera=(), microphone=(), geolocation=(), browsing-topics=()',
  },
  ...(isProduction ? [{ key: 'Strict-Transport-Security', value: 'max-age=31536000' }] : []),
];

const nextConfig: NextConfig = {
  output: 'standalone',
  poweredByHeader: false,
  allowedDevOrigins: ['127.0.0.1'],
  images: {
    // CMS media is served directly by the backend API. The Next.js optimizer
    // would otherwise block private IPs (127.0.0.1, 10.x, 172.16-31.x,
    // 192.168.x) via SSRF protection, breaking local development. Images are
    // already compressed at upload time, so optimization is unnecessary.
    unoptimized: true,
    remotePatterns: [
      {
        protocol: apiOrigin.protocol,
        hostname: apiOrigin.hostname,
        port: apiOrigin.port ?? '',
        pathname: '/storage/**',
      },
    ],
    formats: ['image/avif', 'image/webp'],
  },
  async headers() {
    return [{ source: '/(.*)', headers: securityHeaders }];
  },
};

export default nextConfig;
