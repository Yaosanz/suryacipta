import Link from "next/link";
import { ArrowLeft } from "lucide-react";

export default function NotFoundPage() {
  return (
    <section className="grid min-h-[75vh] place-items-center bg-navy-dark px-4 pt-20 text-center text-white">
      <div>
        <p className="font-display text-8xl font-bold text-gold">404</p>
        <h1 className="mt-5 font-display text-3xl font-bold">Halaman tidak ditemukan</h1>
        <p className="mt-3 text-white/65">Alamat mungkin berubah atau halaman sudah tidak tersedia.</p>
        <Link href="/" className="btn-primary mt-8"><ArrowLeft size={18} /> Kembali ke beranda</Link>
      </div>
    </section>
  );
}
