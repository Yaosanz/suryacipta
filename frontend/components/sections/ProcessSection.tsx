import SectionHeader from "@/components/common/SectionHeader";

const steps = [
  ["01", "Konsultasi & survey", "Kebutuhan, lokasi, ukuran, akses, dan target jadwal."],
  ["02", "Desain & shop drawing", "Detail dimensi dan metode sambungan untuk persetujuan."],
  ["03", "Produksi GRC", "Pembuatan cetakan, laminasi, curing, dan finishing."],
  ["04", "Quality control", "Pemeriksaan dimensi, permukaan, dan kesiapan pengiriman."],
  ["05", "Instalasi & serah terima", "Pemasangan, pengecekan akhir, dan dokumentasi pekerjaan."],
];

/** Timeline proses proyek GRC dari konsultasi hingga serah terima. */
export default function ProcessSection() {
  return (
    <section className="section-space bg-navy text-white">
      <div className="container-site">
        <SectionHeader
          eyebrow="Proses Kerja"
          title="Tahapan yang dapat dipantau"
          description="Setiap proyek memiliki ruang lingkup berbeda. Alur berikut menjadi dasar koordinasi agar keputusan teknis tercatat sebelum produksi."
        />
        <ol className="mt-12 grid gap-7 lg:grid-cols-5">
          {steps.map(([number, title, text]) => (
            <li key={number} className="relative border-l border-gold/50 pl-5 lg:border-l-0 lg:border-t lg:pl-0 lg:pt-6">
              <span className="font-display text-3xl font-bold text-gold">{number}</span>
              <h3 className="mt-3 font-bold">{title}</h3>
              <p className="mt-2 text-sm leading-6 text-white/65">{text}</p>
            </li>
          ))}
        </ol>
      </div>
    </section>
  );
}
