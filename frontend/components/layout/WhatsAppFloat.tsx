"use client";

import { useEffect, useState } from "react";
import { MessageCircle } from "lucide-react";

/** Tombol WhatsApp kontekstual yang tidak mengganggu tampilan awal desktop. */
export default function WhatsAppFloat({ whatsapp }: { whatsapp: string }) {
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    const update = () => setVisible(window.innerWidth < 768 || window.scrollY > 300);
    update();
    window.addEventListener("scroll", update, { passive: true });
    window.addEventListener("resize", update);
    return () => {
      window.removeEventListener("scroll", update);
      window.removeEventListener("resize", update);
    };
  }, []);

  if (!visible) return null;

  return (
    <a
      href={`https://wa.me/${whatsapp}?text=${encodeURIComponent(
        "Halo, saya ingin konsultasi tentang produk GRC Surya Cipta Estetica. Mohon informasinya.",
      )}`}
      target="_blank"
      rel="noreferrer"
      className="fixed bottom-5 right-5 z-40 grid size-14 place-items-center rounded-full bg-success text-white shadow-lg transition-transform hover:scale-105"
      aria-label="Konsultasi melalui WhatsApp"
    >
      <MessageCircle size={27} aria-hidden="true" />
    </a>
  );
}
