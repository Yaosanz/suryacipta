"use client";

import { useState } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { Clock3, LoaderCircle, Mail, MapPin, Phone, Send } from "lucide-react";
import SectionHeader from "@/components/common/SectionHeader";
import { COMPANY } from "@/lib/constants";
import type { CmsHomeContent } from "@/lib/cms";

const contactSchema = z.object({
  name: z.string().trim().min(2, "Nama minimal 2 karakter").max(100),
  company: z.string().trim().max(120).optional(),
  email: z.string().email("Email tidak valid").max(150),
  phone: z.string().trim().regex(/^[+0-9][0-9\s-]{7,19}$/, "Nomor telepon tidak valid"),
  projectType: z.string().min(1, "Pilih jenis proyek"),
  estimatedArea: z.string().trim().max(30).optional(),
  budgetRange: z.string().trim().max(60).optional(),
  message: z.string().trim().min(20, "Pesan minimal 20 karakter").max(3000),
  attachment: z
    .custom<FileList | undefined>(
      (value) =>
        value === undefined || (typeof FileList !== "undefined" && value instanceof FileList),
      "Lampiran tidak valid",
    )
    .refine((files) => !files?.[0] || files[0].size <= 5 * 1024 * 1024, "Maksimal 5 MB")
    .refine(
      (files) =>
        !files?.[0] ||
        ["application/pdf", "image/jpeg", "image/png", "image/webp"].includes(files[0].type),
      "Format harus PDF, JPG, PNG, atau WebP",
    ),
  website: z.string().max(0).optional(),
});

type ContactFormData = z.infer<typeof contactSchema>;

/** Form kontak tervalidasi yang mengirim ke Laravel API dan memiliki fallback WhatsApp. */
export default function ContactSection({ site }: { site?: CmsHomeContent["site"] }) {
  const whatsapp = site?.whatsapp ?? COMPANY.whatsapp;
  const phone = site?.phone ?? COMPANY.phone;
  const email = site?.email ?? COMPANY.email;
  const address =
    site?.address?.full ??
    (site?.address ? Object.values(site.address).filter(Boolean).join(", ") : COMPANY.address.full);
  const businessHours = site?.businessHours ?? COMPANY.hours;
  const [status, setStatus] = useState<"idle" | "success" | "error">("idle");
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<ContactFormData>({ resolver: zodResolver(contactSchema) });

  async function onSubmit(data: ContactFormData) {
    setStatus("idle");
    try {
      const endpoint =
        process.env.NEXT_PUBLIC_API_URL?.replace(/\/$/, "") ?? "http://127.0.0.1:8000/api";
      const body = new FormData();
      body.set("name", data.name);
      body.set("company", data.company ?? "");
      body.set("email", data.email);
      body.set("phone", data.phone);
      body.set("projectType", data.projectType);
      body.set("estimatedArea", data.estimatedArea ?? "");
      body.set("budgetRange", data.budgetRange ?? "");
      body.set("message", data.message);
      body.set("website", data.website ?? "");
      if (data.attachment?.[0]) body.set("attachment", data.attachment[0]);

      const response = await fetch(`${endpoint}/inquiries`, {
        method: "POST",
        headers: { Accept: "application/json" },
        body,
      });
      if (!response.ok) throw new Error("Request gagal");
      setStatus("success");
      reset();
    } catch {
      setStatus("error");
    }
  }

  return (
    <section id="kontak" className="section-space bg-paper">
      <div className="container-site">
        <SectionHeader
          eyebrow="Kontak & RFQ"
          title="Ceritakan kebutuhan proyek"
          description="Data hanya digunakan untuk menanggapi permintaan Anda. Untuk estimasi lebih akurat, cantumkan lokasi, ukuran, dan target waktu."
        />
        <div className="mt-10 grid gap-10 lg:grid-cols-[1.15fr_.85fr]">
          <form
            onSubmit={handleSubmit(onSubmit)}
            noValidate
            className="grid gap-5 rounded-md border border-line bg-white p-5 md:grid-cols-2 md:p-8"
          >
            <label className="text-sm font-bold text-navy">
              Nama *
              <input className="field mt-2 font-normal text-ink" autoComplete="name" {...register("name")} />
              {errors.name && <span className="field-error block">{errors.name.message}</span>}
            </label>
            <label className="text-sm font-bold text-navy">
              Perusahaan
              <input className="field mt-2 font-normal text-ink" autoComplete="organization" {...register("company")} />
            </label>
            <label className="text-sm font-bold text-navy">
              Email *
              <input className="field mt-2 font-normal text-ink" type="email" autoComplete="email" {...register("email")} />
              {errors.email && <span className="field-error block">{errors.email.message}</span>}
            </label>
            <label className="text-sm font-bold text-navy">
              No. HP / WhatsApp *
              <input className="field mt-2 font-normal text-ink" inputMode="tel" autoComplete="tel" {...register("phone")} />
              {errors.phone && <span className="field-error block">{errors.phone.message}</span>}
            </label>
            <label className="text-sm font-bold text-navy md:col-span-2">
              Jenis proyek *
              <select className="field mt-2 font-normal text-ink" defaultValue="" {...register("projectType")}>
                <option value="" disabled>Pilih jenis proyek</option>
                <option>Masjid / Musholla</option>
                <option>Hotel / Resort</option>
                <option>Perumahan</option>
                <option>Perkantoran / Komersial</option>
                <option>Fasilitas Umum</option>
                <option>Lainnya</option>
              </select>
              {errors.projectType && <span className="field-error block">{errors.projectType.message}</span>}
            </label>
            <label className="text-sm font-bold text-navy">
              Estimasi luas
              <input className="field mt-2 font-normal text-ink" placeholder="Contoh: 240 m2" {...register("estimatedArea")} />
            </label>
            <label className="text-sm font-bold text-navy">
              Rentang anggaran
              <select className="field mt-2 font-normal text-ink" defaultValue="" {...register("budgetRange")}>
                <option value="">Belum ditentukan</option>
                <option>Di bawah Rp50 juta</option>
                <option>Rp50 - 150 juta</option>
                <option>Rp150 - 500 juta</option>
                <option>Di atas Rp500 juta</option>
              </select>
            </label>
            <label className="text-sm font-bold text-navy md:col-span-2">
              Pesan *
              <textarea className="field mt-2 min-h-32 resize-y font-normal text-ink" {...register("message")} />
              {errors.message && <span className="field-error block">{errors.message.message}</span>}
            </label>
            <label className="text-sm font-bold text-navy md:col-span-2">
              Gambar kerja / referensi
              <input
                className="field mt-2 cursor-pointer font-normal text-ink"
                type="file"
                accept=".pdf,.jpg,.jpeg,.png,.webp"
                {...register("attachment")}
              />
              <span className="mt-2 block text-xs font-normal text-muted">PDF, JPG, PNG, atau WebP. Maksimal 5 MB.</span>
              {errors.attachment && <span className="field-error block">{errors.attachment.message}</span>}
            </label>
            <label className="absolute -left-[9999px]" aria-hidden="true">
              Website
              <input tabIndex={-1} autoComplete="off" {...register("website")} />
            </label>
            <div className="md:col-span-2">
              <button type="submit" className="btn-primary w-full sm:w-auto" disabled={isSubmitting}>
                {isSubmitting ? <LoaderCircle className="animate-spin" size={18} /> : <Send size={18} />}
                {isSubmitting ? "Mengirim..." : "Kirim Permintaan"}
              </button>
              {status === "success" && (
                <p className="mt-4 text-sm font-semibold text-success" role="status">
                  Permintaan telah diterima. Tim kami akan menghubungi Anda.
                </p>
              )}
              {status === "error" && (
                <p className="mt-4 text-sm text-red-700" role="alert">
                  Pengiriman API belum berhasil. Hubungi langsung melalui{" "}
                  <a className="font-bold underline" href={`https://wa.me/${whatsapp}`}>
                    WhatsApp
                  </a>
                  .
                </p>
              )}
            </div>
          </form>
          <aside className="bg-navy p-7 text-white md:p-9">
            <h3 className="font-display text-3xl font-bold">Workshop Cibinong</h3>
            <p className="mt-3 text-sm leading-6 text-white/70">
              Jadwalkan survey atau pertemuan terlebih dahulu agar tim yang sesuai dapat mendampingi.
            </p>
            <address className="mt-8 grid gap-6 not-italic">
              <a href={COMPANY.address.googleMaps} target="_blank" rel="noreferrer" className="flex gap-4">
                <MapPin className="shrink-0 text-gold" aria-hidden="true" />
                <span><strong className="block">Alamat</strong><span className="mt-1 block text-sm text-white/70">{address}</span></span>
              </a>
              <a href={`tel:+${whatsapp}`} className="flex gap-4">
                <Phone className="shrink-0 text-gold" aria-hidden="true" />
                <span><strong className="block">Telepon / WhatsApp</strong><span className="mt-1 block text-sm text-white/70">{phone}</span></span>
              </a>
              <a href={`mailto:${email}`} className="flex gap-4">
                <Mail className="shrink-0 text-gold" aria-hidden="true" />
                <span><strong className="block">Email</strong><span className="mt-1 block break-all text-sm text-white/70">{email}</span></span>
              </a>
              <div className="flex gap-4">
                <Clock3 className="shrink-0 text-gold" aria-hidden="true" />
                <span><strong className="block">Jam operasional</strong><span className="mt-1 block text-sm text-white/70">{businessHours}</span></span>
              </div>
            </address>
            <iframe
              className="mt-8 aspect-[4/3] w-full border-0"
              src="https://www.google.com/maps?q=Jl.%20Raya%20Bogor%20Km%2040.5%20Cibinong%20Bogor&output=embed"
              title="Peta lokasi workshop Surya Cipta Estetica"
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
            />
          </aside>
        </div>
      </div>
    </section>
  );
}
