import Image from "next/image";
import Link from "next/link";
import { Mail, MapPin, Phone } from "lucide-react";
import type { CmsHomeContent } from "@/lib/cms";
import type { Product } from "@/lib/cms-types";

/** Footer informatif yang memuat navigasi, produk, dan identitas lokal bisnis. */
export default function Footer({
  site,
  products,
}: {
  site: CmsHomeContent["site"];
  products: Product[];
}) {
  const address = site.address?.full ??
    (site.address ? Object.values(site.address).filter(Boolean).join(", ") : "");

  return (
    <footer className="bg-navy-dark text-white">
      <div className="container-site grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-4">
        <div>
          <div className="flex items-center gap-4">
            {site.logo && (
              <Image
                src={site.logo}
                alt={site.logoAlt || `Logo ${site.companyName}`}
                width={76}
                height={76}
                sizes="76px"
                unoptimized
                className="size-[76px] shrink-0 object-contain drop-shadow-[0_1px_2px_rgba(255,255,255,0.95)]"
              />
            )}
            <p className="font-display text-xl font-bold leading-tight">{site.companyName}</p>
          </div>
          <p className="mt-4 text-sm leading-7 text-white/75">{site.description}</p>
          <p className="mt-5 text-xs font-bold text-gold">{site.tagline}</p>
        </div>
        <div>
          <h2 className="text-sm font-bold uppercase text-gold">Navigasi</h2>
          <div className="mt-4 grid gap-3 text-sm text-white/75">
            <Link href="/tentang-kami">Tentang Kami</Link>
            <Link href="/produk">Produk GRC</Link>
            <Link href="/portofolio">Portofolio</Link>
            <Link href="/blog">Artikel</Link>
            <Link href="/kontak">Kontak & RFQ</Link>
          </div>
        </div>
        <div>
          <h2 className="text-sm font-bold uppercase text-gold">Produk</h2>
          <div className="mt-4 grid gap-3 text-sm text-white/75">
            {products.slice(0, 5).map((product) => (
              <Link key={product.id} href={`/produk/${product.slug}`}>
                {product.name}
              </Link>
            ))}
          </div>
        </div>
        <div>
          <h2 className="text-sm font-bold uppercase text-gold">Hubungi Kami</h2>
          <address className="mt-4 grid gap-4 text-sm not-italic text-white/75">
            <span className="flex gap-3">
              <MapPin className="mt-0.5 shrink-0 text-gold" size={17} aria-hidden="true" />
              {address}
            </span>
            <a className="flex gap-3 hover:text-white" href={`tel:+${site.whatsapp}`}>
              <Phone className="shrink-0 text-gold" size={17} aria-hidden="true" />
              {site.phone}
            </a>
            <a className="flex gap-3 break-all hover:text-white" href={`mailto:${site.email}`}>
              <Mail className="shrink-0 text-gold" size={17} aria-hidden="true" />
              {site.email}
            </a>
          </address>
        </div>
      </div>
      <div className="border-t border-white/10">
        <div className="container-site flex flex-col gap-2 py-5 text-xs text-white/55 md:flex-row md:justify-between">
          <p>&copy; {new Date().getFullYear()} {site.companyName}. Hak cipta dilindungi.</p>
          <Link href="/kebijakan-privasi">Kebijakan Privasi</Link>
        </div>
      </div>
    </footer>
  );
}
