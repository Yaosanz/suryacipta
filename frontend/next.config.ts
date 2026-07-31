import type { NextConfig } from "next";

const apiUrl = process.env.NEXT_PUBLIC_API_URL ?? "http://127.0.0.1:8000/api";

function getOrigin(url: string): string {
  try {
    return new URL(url).origin;
  } catch {
    throw new Error("NEXT_PUBLIC_API_URL harus berupa URL absolut yang valid.");
  }
}

const apiOrigin = getOrigin(apiUrl);
const isProduction = process.env.NODE_ENV === "production";
const scriptSources = [
  "'self'",
  "'unsafe-inline'",
  ...(!isProduction ? ["'unsafe-eval'"] : []),
  "https://www.googletagmanager.com",
].join(" ");
const contentSecurityPolicy = [
  "default-src 'self'",
  "base-uri 'self'",
  "object-src 'none'",
  "frame-ancestors 'none'",
  "form-action 'self'",
  `script-src ${scriptSources}`,
  "style-src 'self' 'unsafe-inline'",
  "font-src 'self' data:",
  `img-src 'self' data: ${apiOrigin} https://*.google.com https://*.gstatic.com`,
  "frame-src 'self' https://www.google.com https://maps.google.com",
  `connect-src 'self' ${apiOrigin} https://www.google-analytics.com https://*.google-analytics.com`,
  "media-src 'self'",
  "worker-src 'self' blob:",
  "manifest-src 'self'",
  ...(isProduction ? ["upgrade-insecure-requests"] : []),
].join("; ");

const securityHeaders = [
  { key: "Content-Security-Policy", value: contentSecurityPolicy },
  { key: "Cross-Origin-Opener-Policy", value: "same-origin" },
  { key: "Cross-Origin-Resource-Policy", value: "same-origin" },
  { key: "Referrer-Policy", value: "strict-origin-when-cross-origin" },
  { key: "X-Content-Type-Options", value: "nosniff" },
  { key: "X-DNS-Prefetch-Control", value: "off" },
  { key: "X-Frame-Options", value: "DENY" },
  { key: "X-Permitted-Cross-Domain-Policies", value: "none" },
  {
    key: "Permissions-Policy",
    value: "camera=(), microphone=(), geolocation=(), browsing-topics=()",
  },
  ...(isProduction
    ? [{ key: "Strict-Transport-Security", value: "max-age=31536000" }]
    : []),
];

const nextConfig: NextConfig = {
  output: "standalone",
  poweredByHeader: false,
  allowedDevOrigins: ["127.0.0.1"],
  images: {
    remotePatterns: [
      new URL(`${apiOrigin}/storage/**`),
    ],
    formats: ["image/avif", "image/webp"],
  },
  async headers() {
    return [{ source: "/(.*)", headers: securityHeaders }];
  },
};

export default nextConfig;
