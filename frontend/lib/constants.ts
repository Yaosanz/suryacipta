export const COMPANY = {
  name: "CV. Surya Cipta Estetica Mandiri",
  shortName: "Surya Cipta Estetica",
  tagline: "Spesialis GRC Panel Cetak - Dari Desain Hingga Terpasang",
  description:
    "Produsen dan installer GRC di Cibinong, Bogor untuk proyek masjid, hotel, bangunan komersial, dan perumahan di seluruh Indonesia.",
  phone: "0812 8233 3021",
  whatsapp: "6281282333021",
  email: "dsuparyo@yahoo.com",
  address: {
    street: "Jl. Raya Bogor Km. 40,5, Pabuaran",
    city: "Cibinong",
    regency: "Bogor",
    province: "Jawa Barat",
    postalCode: "16914",
    country: "Indonesia",
    full: "Jl. Raya Bogor Km. 40,5, Pabuaran, Cibinong, Bogor, Jawa Barat 16914",
    googleMaps: "https://maps.google.com/?q=Jl.+Raya+Bogor+Km+40.5+Cibinong+Bogor",
  },
  hours: "Senin - Sabtu, 08.00 - 17.00 WIB",
  stats: [
    { value: "25+", label: "Tahun pengalaman" },
    { value: "200+", label: "Proyek selesai" },
    { value: "150+", label: "Klien dilayani" },
    { value: "98%", label: "Kepuasan klien" },
  ],
} as const;

export type Product = {
  id: string;
  name: string;
  slug: string;
  category: string;
  description: string;
  features: readonly string[];
  image: string;
};

export const PRODUCTS: readonly Product[] = [
  {
    id: "kubah-grc",
    name: "Kubah Masjid GRC",
    slug: "kubah-masjid-grc",
    category: "Religi",
    description:
      "Kubah ringan dan kokoh dengan ukuran serta ornamen islami yang disesuaikan pada rancangan masjid.",
    features: ["Tahan cuaca", "Custom ornamen", "Finishing fleksibel"],
    image:
      "https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=1200&q=82",
  },
  {
    id: "panel-grc",
    name: "Panel GRC / Cladding",
    slug: "panel-grc-cladding",
    category: "Komersial",
    description:
      "Panel fasad eksterior dan interior dengan dimensi presisi, bobot ringan, dan pilihan tekstur custom.",
    features: ["Presisi dimensi", "Tahan cuaca", "Instalasi efisien"],
    image:
      "https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1200&q=82",
  },
  {
    id: "ornamen-grc",
    name: "Ornamen & Relief GRC",
    slug: "ornamen-relief-grc",
    category: "Dekoratif",
    description:
      "Elemen dekoratif klasik, modern, atau islami yang dicetak presisi dari shop drawing terverifikasi.",
    features: ["Detail presisi", "Motif custom", "Finishing fleksibel"],
    image:
      "https://images.unsplash.com/photo-1511818966892-d7d671e672a2?auto=format&fit=crop&w=1200&q=82",
  },
  {
    id: "krawangan-grc",
    name: "GRC Krawangan / Roster",
    slug: "grc-krawangan-roster",
    category: "Ventilasi",
    description:
      "Krawangan dekoratif untuk pencahayaan dan aliran udara alami dalam motif geometris atau custom.",
    features: ["Sirkulasi alami", "Beragam motif", "Kuat dan awet"],
    image:
      "https://images.unsplash.com/photo-1524230572899-a752b3835840?auto=format&fit=crop&w=1200&q=82",
  },
  {
    id: "lisplang-grc",
    name: "Lisplang & Profil GRC",
    slug: "lisplang-profil-grc",
    category: "Finishing",
    description:
      "Profil arsitektural untuk hasil akhir eksterior yang rapi, tahan lama, dan konsisten.",
    features: ["Profil presisi", "Bobot ringan", "Tahan lama"],
    image:
      "https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=82",
  },
  {
    id: "kolom-grc",
    name: "Kolom & Pilar GRC",
    slug: "kolom-pilar-grc",
    category: "Struktural",
    description:
      "Selubung kolom dan pilar dekoratif dengan tampilan solid tanpa beban beton konvensional.",
    features: ["Custom ukuran", "Ringan", "Pilihan gaya"],
    image:
      "https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=1200&q=82",
  },
];

export type PortfolioItem = {
  id: number;
  title: string;
  location: string;
  category: string;
  year: string;
  description: string;
  image: string;
};

export const PORTFOLIO: readonly PortfolioItem[] = [
  {
    id: 1,
    title: "Kubah Masjid Jami",
    location: "Bogor, Jawa Barat",
    category: "Religi",
    year: "2023",
    description: "Kubah diameter 8 meter dengan detail ornamen kaligrafi custom.",
    image:
      "https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=1400&q=82",
  },
  {
    id: 2,
    title: "Fasad Hotel Green Kosambi",
    location: "Bandung, Jawa Barat",
    category: "Hospitality",
    year: "2022",
    description: "Cladding panel GRC untuk fasad hotel seluas 2.400 meter persegi.",
    image:
      "https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1400&q=82",
  },
  {
    id: 3,
    title: "Kantor Perbankan",
    location: "Bogor, Jawa Barat",
    category: "Komersial",
    year: "2021",
    description: "Panel dekoratif eksterior dengan modul presisi.",
    image:
      "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1400&q=82",
  },
  {
    id: 4,
    title: "Kawasan Residensial",
    location: "Cikarang, Jawa Barat",
    category: "Residensial",
    year: "2020",
    description: "Lisplang dan profil GRC untuk unit hunian.",
    image:
      "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1400&q=82",
  },
  {
    id: 5,
    title: "Menara Masjid Laban",
    location: "Serang, Banten",
    category: "Religi",
    year: "2019",
    description: "Produksi menara, ornamen islami, dan finishing eksterior.",
    image:
      "https://images.unsplash.com/photo-1542816417-0983c9c9ad53?auto=format&fit=crop&w=1400&q=82",
  },
  {
    id: 6,
    title: "Ornamen Area Tematik",
    location: "Bandung, Jawa Barat",
    category: "Hospitality",
    year: "2020",
    description: "Ornamen dan krawangan dekoratif untuk area publik.",
    image:
      "https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1400&q=82",
  },
];

export const FAQS = [
  {
    question: "Berapa harga GRC panel per m2?",
    answer:
      "Harga dipengaruhi ketebalan, bentuk, tingkat detail, finishing, volume, dan lokasi instalasi. Kirim ukuran atau gambar kerja untuk penawaran yang akurat.",
  },
  {
    question: "Apakah motif dan ukuran dapat dibuat custom?",
    answer:
      "Ya. Tim kami meninjau konsep, membuat atau memeriksa shop drawing, lalu memproduksi cetakan sesuai persetujuan.",
  },
  {
    question: "Berapa lama proses produksi?",
    answer:
      "Durasi bergantung pada jumlah cetakan, kompleksitas bentuk, volume, dan jadwal instalasi. Estimasi waktu dicantumkan pada penawaran proyek.",
  },
] as const;

export const BLOG_POSTS = [
  {
    slug: "panduan-memilih-grc-panel-untuk-masjid",
    title: "Panduan Memilih GRC Panel untuk Masjid",
    excerpt: "Faktor desain, ketebalan, cetakan, finishing, dan pemasangan yang perlu diperiksa.",
    date: "2026-07-20",
  },
  {
    slug: "grc-untuk-fasad-komersial",
    title: "GRC untuk Fasad Komersial: Ringan, Presisi, dan Fleksibel",
    excerpt: "Cara kontraktor dan arsitek memanfaatkan panel GRC pada fasad bangunan.",
    date: "2026-07-12",
  },
] as const;
