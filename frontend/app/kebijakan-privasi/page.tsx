import type { Metadata } from "next";
import { createPageMetadata } from "@/lib/seo";

export function generateMetadata(): Metadata {
  return createPageMetadata(
    "Kebijakan Privasi",
    "Kebijakan penggunaan data pada website CV. Surya Cipta Estetica Mandiri.",
    "/kebijakan-privasi",
  );
}

export default function PrivacyPage() {
  return (
    <article className="container-site max-w-4xl pb-20 pt-32">
      <p className="eyebrow">Privasi</p>
      <h1 className="mt-4 font-display text-4xl font-bold text-navy">Kebijakan Privasi</h1>
      <div className="mt-8 space-y-6 leading-8 text-muted">
        <p>Data pada formulir digunakan untuk menanggapi konsultasi atau permintaan penawaran. Kami tidak menjual data kontak kepada pihak lain.</p>
        <p>Data dapat disimpan selama diperlukan untuk tindak lanjut proyek dan kewajiban administrasi. Anda dapat meminta koreksi atau penghapusan melalui email perusahaan.</p>
        <p>Jangan mengirim dokumen yang berisi kata sandi, data pembayaran, atau informasi rahasia yang tidak diperlukan untuk peninjauan proyek.</p>
      </div>
    </article>
  );
}
