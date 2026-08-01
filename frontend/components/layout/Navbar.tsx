"use client";

import { useEffect, useState } from "react";
import Image from "next/image";
import Link from "next/link";
import { Menu, MessageCircle, X } from "lucide-react";
import type { CmsHomeContent } from "@/lib/cms";

const links = [
  { href: "/", label: "Beranda" },
  { href: "/tentang-kami", label: "Tentang Kami" },
  { href: "/produk", label: "Produk" },
  { href: "/portofolio", label: "Portofolio" },
  { href: "/blog", label: "Blog" },
  { href: "/kontak", label: "Kontak" },
];

/** Navigasi utama responsif dengan menu mobile yang dapat diakses keyboard. */
export default function Navbar({ site }: { site: CmsHomeContent["site"] }) {
  const [open, setOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  useEffect(() => {
    const update = () => setScrolled(window.scrollY > 24);
    update();
    window.addEventListener("scroll", update, { passive: true });
    return () => window.removeEventListener("scroll", update);
  }, []);

  return (
    <header
      className={`fixed inset-x-0 top-0 z-50 border-b transition-colors ${
        scrolled || open
          ? "border-line bg-paper/95 text-navy shadow-sm backdrop-blur"
          : "border-white/15 bg-navy-dark/30 text-white backdrop-blur-sm"
      }`}
    >
      <div className="container-site flex h-[76px] items-center justify-between">
        <Link
          href="/"
          className="flex min-w-0 max-w-[300px] items-center gap-3"
          aria-label={`${site.companyName}, beranda`}
        >
          {site.logo && (
            <Image
              src={site.logo}
              alt={site.logoAlt || `Logo ${site.companyName}`}
              width={52}
              height={52}
              sizes="52px"
              priority
              unoptimized
              className={`size-[52px] shrink-0 object-contain ${
                scrolled || open
                  ? "drop-shadow-[0_1px_1px_rgba(255,255,255,0.7)]"
                  : "drop-shadow-[0_1px_2px_rgba(255,255,255,0.95)]"
              }`}
            />
          )}
          <span className="min-w-0">
            <span className="line-clamp-2 block font-display text-sm font-bold leading-tight sm:text-base">
              {site.companyName}
            </span>
            <span
              className={`mt-1 hidden truncate text-[10px] font-bold sm:block ${
                scrolled || open ? "text-gold" : "text-gold-light"
              }`}
            >
              {site.tagline}
            </span>
          </span>
        </Link>

        <nav aria-label="Navigasi utama" className="hidden items-center gap-6 lg:flex">
          {links.map((link) => (
            <Link key={link.href} href={link.href} className="text-sm font-semibold hover:text-gold">
              {link.label}
            </Link>
          ))}
        </nav>

        <a
          className="btn-whatsapp ml-4 hidden lg:inline-flex"
          href={`https://wa.me/${site.whatsapp}?text=${encodeURIComponent(
            "Halo, saya ingin konsultasi tentang produk GRC Surya Cipta Estetica.",
          )}`}
          target="_blank"
          rel="noreferrer"
        >
          <MessageCircle size={17} aria-hidden="true" />
          Konsultasi
        </a>

        <button
          type="button"
          className="grid size-11 place-items-center lg:hidden"
          aria-label={open ? "Tutup menu" : "Buka menu"}
          aria-expanded={open}
          aria-controls="mobile-menu"
          onClick={() => setOpen((value) => !value)}
        >
          {open ? <X aria-hidden="true" /> : <Menu aria-hidden="true" />}
        </button>
      </div>

      {open && (
        <nav id="mobile-menu" aria-label="Navigasi mobile" className="border-t border-line bg-paper px-4 py-4 text-navy lg:hidden">
          {links.map((link) => (
            <Link
              key={link.href}
              href={link.href}
              onClick={() => setOpen(false)}
              className="block border-b border-line py-3 font-semibold last:border-0"
            >
              {link.label}
            </Link>
          ))}
          <a
            className="btn-whatsapp mt-4 w-full"
            href={`https://wa.me/${site.whatsapp}`}
            target="_blank"
            rel="noreferrer"
          >
            <MessageCircle size={18} aria-hidden="true" />
            Chat WhatsApp
          </a>
        </nav>
      )}
    </header>
  );
}
