import { MessageCircle, Phone } from "lucide-react";
import { COMPANY } from "@/lib/constants";
import type { CmsHomeContent, CmsSection } from "@/lib/cms";

/** CTA utama sebelum formulir untuk jalur konversi cepat. */
export default function CTASection({
  site,
  section,
}: {
  site?: CmsHomeContent["site"];
  section?: CmsSection;
}) {
  const whatsapp = site?.whatsapp ?? COMPANY.whatsapp;
  const phone = site?.phone ?? COMPANY.phone;
  return (
    <section className="bg-gold py-14 text-navy-dark">
      <div className="container-site flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">
        <div>
          <p className="text-xs font-extrabold uppercase">Mulai dari kebutuhan Anda</p>
          <h2 className="mt-2 font-display text-3xl font-bold md:text-4xl">
            {section?.title ?? "Siap membahas proyek GRC Anda?"}
          </h2>
          <p className="mt-3 max-w-2xl text-sm leading-6">
            {section?.description ?? "Kirim ukuran, lokasi, gambar referensi, atau shop drawing untuk tinjauan awal."}
          </p>
        </div>
        <div className="flex flex-col gap-3 sm:flex-row">
          <a
            href={`https://wa.me/${whatsapp}`}
            target="_blank"
            rel="noreferrer"
            className="btn-whatsapp"
          >
            <MessageCircle size={18} aria-hidden="true" /> Chat WhatsApp
          </a>
          <a href={`tel:+${whatsapp}`} className="btn-secondary border-navy-dark text-navy-dark">
            <Phone size={18} aria-hidden="true" /> {phone}
          </a>
        </div>
      </div>
    </section>
  );
}
