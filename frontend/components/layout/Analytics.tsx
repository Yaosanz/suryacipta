import Script from "next/script";

/** Memuat GA4 hanya saat measurement ID produksi telah dikonfigurasi. */
export default function Analytics() {
  const measurementId = process.env.NEXT_PUBLIC_GA_ID;

  if (!measurementId || !/^G-[A-Z0-9]+$/.test(measurementId)) {
    return null;
  }

  return (
    <>
      <Script
        src={`https://www.googletagmanager.com/gtag/js?id=${measurementId}`}
        strategy="afterInteractive"
      />
      <Script id="google-analytics" strategy="afterInteractive">
        {`
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', '${measurementId}', { anonymize_ip: true });
        `}
      </Script>
    </>
  );
}
