import Link from "next/link";
import { Mail, MapPin, Phone } from "lucide-react";
import { COMPANY, PRODUCTS } from "@/lib/constants";

/** Footer informatif yang memuat navigasi, produk, dan identitas lokal bisnis. */
export default function Footer() {
  return (
    <footer className="bg-navy-dark text-white">
      <div className="container-site grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-4">
        <div>
          <p className="font-display text-2xl font-bold">Surya Cipta Estetica</p>
          <p className="mt-4 text-sm leading-7 text-white/70">{COMPANY.description}</p>
          <p className="mt-5 text-xs font-bold uppercase text-gold">Produsen GRC Bogor & Jawa Barat</p>
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
            {PRODUCTS.slice(0, 5).map((product) => (
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
              {COMPANY.address.full}
            </span>
            <a className="flex gap-3" href={`tel:+${COMPANY.whatsapp}`}>
              <Phone className="shrink-0 text-gold" size={17} aria-hidden="true" />
              {COMPANY.phone}
            </a>
            <a className="flex gap-3 break-all" href={`mailto:${COMPANY.email}`}>
              <Mail className="shrink-0 text-gold" size={17} aria-hidden="true" />
              {COMPANY.email}
            </a>
          </address>
        </div>
      </div>
      <div className="border-t border-white/10">
        <div className="container-site flex flex-col gap-2 py-5 text-xs text-white/55 md:flex-row md:justify-between">
          <p>&copy; {new Date().getFullYear()} {COMPANY.name}. Hak cipta dilindungi.</p>
          <Link href="/kebijakan-privasi">Kebijakan Privasi</Link>
        </div>
      </div>
    </footer>
  );
}
