<?php

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\PageSection;
use App\Models\PortfolioProject;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class CmsContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::query()->firstOrCreate([], [
            'company_name' => 'CV. Surya Cipta Estetica Mandiri',
            'short_name' => 'Surya Cipta Estetica',
            'tagline' => 'Spesialis GRC Panel Cetak - Dari Desain Hingga Terpasang',
            'description' => 'Produsen dan installer GRC di Cibinong, Bogor untuk proyek masjid, hotel, bangunan komersial, dan perumahan di seluruh Indonesia.',
            'phone' => '0812 8233 3021',
            'whatsapp' => '6281282333021',
            'email' => 'dsuparyo@yahoo.com',
            'address' => [
                'street' => 'Jl. Raya Bogor Km. 40,5, Pabuaran',
                'city' => 'Cibinong',
                'regency' => 'Bogor',
                'province' => 'Jawa Barat',
                'postalCode' => '16914',
                'country' => 'Indonesia',
            ],
            'business_hours' => 'Senin - Sabtu, 08.00 - 17.00 WIB',
            'statistics' => [
                ['value' => '25+', 'label' => 'Tahun pengalaman'],
                ['value' => '200+', 'label' => 'Proyek selesai'],
                ['value' => '150+', 'label' => 'Klien dilayani'],
                ['value' => '98%', 'label' => 'Kepuasan klien'],
            ],
            'default_seo_title' => 'Surya Cipta Estetica - Spesialis GRC Indonesia',
            'default_seo_description' => 'Produksi dan pemasangan GRC panel cetak untuk proyek arsitektur di seluruh Indonesia.',
        ]);

        $sections = [
            'hero' => [
                'eyebrow' => 'Spesialis GRC Indonesia',
                'title' => 'Solusi GRC Presisi untuk Setiap Karya Arsitektur',
                'description' => 'Dari desain, produksi, hingga pemasangan oleh tim berpengalaman.',
                'primary_cta_label' => 'Konsultasi Gratis',
                'primary_cta_url' => 'https://wa.me/6281282333021',
                'content' => [
                    'badge' => 'Berpengalaman lebih dari 25 tahun',
                    'secondaryCtaLabel' => 'Lihat Portofolio',
                    'secondaryCtaUrl' => '/portofolio',
                    'highlights' => [
                        ['title' => '200+ proyek selesai'],
                        ['title' => 'Produksi dan instalasi'],
                        ['title' => 'Custom sesuai desain'],
                    ],
                ],
                'sort_order' => 10,
            ],
            'about' => [
                'eyebrow' => 'Tentang Kami',
                'title' => 'Pengalaman Produksi yang Terukur',
                'description' => 'Kami menerjemahkan konsep arsitektur menjadi elemen GRC yang presisi dan siap dipasang.',
                'primary_cta_label' => 'Pelajari lebih lanjut',
                'primary_cta_url' => '/tentang-kami',
                'content' => [
                    'locationTitle' => 'Cibinong',
                    'locationText' => 'Basis produksi strategis untuk Jabodetabek',
                    'features' => [
                        ['icon' => 'factory', 'title' => 'Produksi terkendali', 'text' => 'Cetakan, curing, finishing, dan pengecekan dilakukan dalam alur kerja yang jelas.'],
                        ['icon' => 'drafting', 'title' => 'Custom dari shop drawing', 'text' => 'Dimensi dan detail disepakati sebelum produksi untuk mengurangi rework.'],
                    ],
                ],
                'sort_order' => 20,
            ],
            'products' => [
                'eyebrow' => 'Produk GRC',
                'title' => 'Dibuat presisi untuk desain yang berbeda',
                'description' => 'Pilih produk standar atau konsultasikan bentuk custom untuk kebutuhan arsitektur, kontraktor, dan pemilik bangunan.',
                'primary_cta_label' => 'Lihat semua produk',
                'primary_cta_url' => '/produk',
                'sort_order' => 30,
            ],
            'advantages' => [
                'eyebrow' => 'Mengapa Kami',
                'title' => 'Satu tim dari desain hingga terpasang',
                'description' => 'Kemampuan operasional yang membantu proyek berjalan lebih terukur sejak gambar kerja hingga serah terima.',
                'content' => [
                    'items' => [
                        ['icon' => 'factory', 'title' => 'Produksi sendiri', 'text' => 'Kontrol mutu sejak cetakan hingga finishing.'],
                        ['icon' => 'palette', 'title' => 'Custom sesuai desain', 'text' => 'Motif, dimensi, warna, dan tekstur mengikuti gambar kerja.'],
                        ['icon' => 'clock', 'title' => 'Jadwal transparan', 'text' => 'Milestone produksi dan instalasi disepakati sejak awal.'],
                        ['icon' => 'weather', 'title' => 'Material tahan cuaca', 'text' => 'Komposisi dan finishing disesuaikan dengan fungsi elemen.'],
                        ['icon' => 'delivery', 'title' => 'Layanan instalasi', 'text' => 'Koordinasi pengiriman, lifting, pemasangan, dan finishing.'],
                        ['icon' => 'quality', 'title' => 'Quality control', 'text' => 'Dimensi dan kondisi elemen diperiksa sebelum dikirim.'],
                    ],
                ],
                'sort_order' => 40,
            ],
            'process' => [
                'eyebrow' => 'Proses Kerja',
                'title' => 'Tahapan yang dapat dipantau',
                'description' => 'Setiap proyek memiliki ruang lingkup berbeda. Alur berikut menjadi dasar koordinasi agar keputusan teknis tercatat sebelum produksi.',
                'content' => [
                    'items' => [
                        ['title' => 'Konsultasi dan survey', 'text' => 'Kebutuhan, lokasi, ukuran, akses, dan target jadwal.'],
                        ['title' => 'Desain dan shop drawing', 'text' => 'Detail dimensi dan metode sambungan untuk persetujuan.'],
                        ['title' => 'Produksi GRC', 'text' => 'Pembuatan cetakan, laminasi, curing, dan finishing.'],
                        ['title' => 'Quality control', 'text' => 'Pemeriksaan dimensi, permukaan, dan kesiapan pengiriman.'],
                        ['title' => 'Instalasi dan serah terima', 'text' => 'Pemasangan, pengecekan akhir, dan dokumentasi pekerjaan.'],
                    ],
                ],
                'sort_order' => 50,
            ],
            'portfolio' => [
                'eyebrow' => 'Portofolio',
                'title' => 'Pekerjaan lintas fungsi dan skala',
                'description' => 'Contoh cakupan proyek religi, hospitality, komersial, dan residensial.',
                'primary_cta_label' => 'Semua proyek',
                'primary_cta_url' => '/portofolio',
                'sort_order' => 60,
            ],
            'testimonials' => [
                'eyebrow' => 'Pengalaman Klien',
                'title' => 'Kolaborasi yang terukur',
                'description' => 'Umpan balik dari pihak yang pernah berkoordinasi dalam pekerjaan kami.',
                'sort_order' => 70,
            ],
            'team' => [
                'eyebrow' => 'Tim Kami',
                'title' => 'Orang-orang di balik setiap pekerjaan',
                'description' => 'Tim lintas fungsi yang menjaga koordinasi desain, produksi, mutu, dan pemasangan tetap terukur.',
                'primary_cta_label' => 'Kenali perusahaan',
                'primary_cta_url' => '/tentang-kami',
                'sort_order' => 75,
            ],
            'cta' => [
                'eyebrow' => 'Mulai dari kebutuhan Anda',
                'title' => 'Diskusikan Kebutuhan Proyek Anda',
                'description' => 'Kirim ukuran atau gambar kerja untuk mendapatkan estimasi yang relevan.',
                'primary_cta_label' => 'Chat WhatsApp',
                'primary_cta_url' => 'https://wa.me/6281282333021',
                'content' => [
                    'secondaryCtaLabel' => 'Telepon kami',
                    'secondaryCtaUrl' => 'tel:+6281282333021',
                ],
                'sort_order' => 80,
            ],
            'contact' => [
                'eyebrow' => 'Kontak dan RFQ',
                'title' => 'Ceritakan kebutuhan proyek',
                'description' => 'Data hanya digunakan untuk menanggapi permintaan Anda. Untuk estimasi lebih akurat, cantumkan lokasi, ukuran, dan target waktu.',
                'content' => [
                    'sidebarTitle' => 'Workshop Cibinong',
                    'sidebarDescription' => 'Jadwalkan survey atau pertemuan terlebih dahulu agar tim yang sesuai dapat mendampingi.',
                    'mapUrl' => 'https://www.google.com/maps?q=Jl.%20Raya%20Bogor%20Km%2040.5%20Cibinong%20Bogor&output=embed',
                    'projectTypes' => [
                        ['label' => 'Masjid / Musholla'],
                        ['label' => 'Hotel / Resort'],
                        ['label' => 'Perumahan'],
                        ['label' => 'Perkantoran / Komersial'],
                        ['label' => 'Fasilitas Umum'],
                        ['label' => 'Lainnya'],
                    ],
                    'budgetRanges' => [
                        ['label' => 'Di bawah Rp50 juta'],
                        ['label' => 'Rp50 - 150 juta'],
                        ['label' => 'Rp150 - 500 juta'],
                        ['label' => 'Di atas Rp500 juta'],
                    ],
                ],
                'sort_order' => 90,
            ],
        ];

        foreach ($sections as $key => $defaults) {
            $section = PageSection::query()->firstOrCreate(
                ['page' => 'home', 'section_key' => $key],
                [...$defaults, 'is_enabled' => true],
            );

            $missing = collect($defaults)
                ->filter(fn (mixed $value, string $field): bool => blank($section->{$field}) && filled($value))
                ->all();

            if ($missing !== []) {
                $section->forceFill($missing)->save();
            }
        }

        $pageSections = [
            'products' => [
                'hero' => [
                    'eyebrow' => 'Katalog Produk',
                    'title' => 'Produk GRC untuk proyek arsitektur',
                    'description' => 'Dari elemen dekoratif satuan hingga kebutuhan fasad proyek, seluruh spesifikasi ditinjau berdasarkan fungsi dan metode pemasangan.',
                    'sort_order' => 10,
                ],
                'detail' => [
                    'eyebrow' => 'Spesifikasi Fleksibel',
                    'title' => 'Dibuat sesuai kebutuhan proyek',
                    'description' => 'Ukuran, ketebalan, detail cetakan, sistem rangka, warna, dan metode instalasi ditentukan setelah tinjauan gambar dan kondisi lapangan.',
                    'primary_cta_label' => 'Minta Penawaran',
                    'sort_order' => 20,
                ],
                'support' => [
                    'eyebrow' => 'FAQ Produk',
                    'title' => 'Sebelum meminta penawaran',
                    'content' => [
                        'estimator' => [
                            'title' => 'Kalkulator kebutuhan panel',
                            'description' => 'Estimasi jumlah panel termasuk allowance. Verifikasi lapangan tetap diperlukan.',
                            'areaLabel' => 'Luas area (m2)',
                            'panelAreaLabel' => 'Luas per panel (m2)',
                            'resultLabel' => 'Estimasi kebutuhan',
                            'allowancePercent' => 5,
                            'defaultArea' => 20,
                            'defaultPanelArea' => 1.2,
                        ],
                    ],
                    'sort_order' => 30,
                ],
            ],
            'portfolio' => [
                'hero' => [
                    'eyebrow' => 'Portofolio Proyek',
                    'title' => 'Portofolio pekerjaan GRC',
                    'description' => 'Ragam pekerjaan religi, hospitality, komersial, dan residensial yang menggambarkan cakupan produksi dan instalasi kami.',
                    'sort_order' => 10,
                ],
            ],
            'blog' => [
                'hero' => [
                    'eyebrow' => 'Wawasan GRC',
                    'title' => 'Catatan untuk keputusan proyek yang lebih baik',
                    'description' => 'Panduan praktis tentang desain, pemilihan produk, produksi, dan pemasangan GRC.',
                    'sort_order' => 10,
                ],
            ],
            'about' => [
                'hero' => [
                    'eyebrow' => 'Profil Perusahaan',
                    'title' => 'Pengalaman produksi yang bertemu kebutuhan desain',
                    'description' => 'Berbasis di Cibinong, kami melayani kebutuhan elemen GRC custom untuk proyek di Jawa Barat dan berbagai wilayah Indonesia.',
                    'sort_order' => 10,
                ],
                'story' => [
                    'eyebrow' => 'Perjalanan Kami',
                    'title' => 'Tumbuh bersama proyek sejak era 1990-an',
                    'description' => 'Surya Cipta Estetica Mandiri berkembang dari pengalaman lapangan dalam pekerjaan cetak arsitektural. Fokus kami adalah mengubah detail desain menjadi elemen GRC yang dapat diproduksi, dikirim, dan dipasang secara efektif.',
                    'content' => [
                        'secondaryDescription' => 'Basis produksi di koridor Cibinong memudahkan koordinasi proyek Jabodetabek. Untuk proyek luar wilayah, perencanaan modul dan metode pengiriman disusun sejak awal.',
                    ],
                    'sort_order' => 20,
                ],
                'values' => [
                    'eyebrow' => 'Arah Perusahaan',
                    'title' => 'Visi, misi, dan nilai kerja',
                    'content' => [
                        'vision' => 'Menjadi mitra GRC yang tepercaya untuk pekerjaan arsitektural custom di Indonesia.',
                        'mission' => 'Menghasilkan elemen presisi, menjaga komunikasi proyek, dan terus meningkatkan proses produksi serta keselamatan kerja.',
                        'items' => [
                            ['icon' => 'quality', 'title' => 'Kualitas', 'text' => 'Spesifikasi dan detail disepakati sebelum produksi.'],
                            ['icon' => 'collaboration', 'title' => 'Kolaborasi', 'text' => 'Koordinasi terbuka dengan owner, arsitek, dan kontraktor.'],
                            ['icon' => 'solution', 'title' => 'Solutif', 'text' => 'Desain diselaraskan dengan kemampuan produksi dan instalasi.'],
                            ['icon' => 'responsibility', 'title' => 'Tanggung jawab', 'text' => 'Milestone kerja dan perubahan ruang lingkup dicatat.'],
                        ],
                    ],
                    'sort_order' => 30,
                ],
                'team' => [
                    'eyebrow' => 'Tim Kami',
                    'title' => 'Orang-orang di balik setiap pekerjaan',
                    'description' => 'Tim lintas fungsi yang menjaga koordinasi desain, produksi, mutu, dan pemasangan tetap terukur.',
                    'sort_order' => 40,
                ],
            ],
            'contact' => [
                'hero' => [
                    'eyebrow' => 'Hubungi Tim Kami',
                    'title' => 'Konsultasi dan permintaan penawaran',
                    'description' => 'Sampaikan kebutuhan awal. Tim kami akan membantu menentukan informasi teknis yang perlu dilengkapi.',
                    'sort_order' => 10,
                ],
                'contact' => [
                    'eyebrow' => 'Kontak dan RFQ',
                    'title' => 'Ceritakan kebutuhan proyek',
                    'description' => 'Data hanya digunakan untuk menanggapi permintaan Anda. Untuk estimasi lebih akurat, cantumkan lokasi, ukuran, dan target waktu.',
                    'content' => $sections['contact']['content'],
                    'sort_order' => 20,
                ],
                'faq' => [
                    'eyebrow' => 'Pertanyaan Umum',
                    'title' => 'Informasi awal sebelum konsultasi',
                    'sort_order' => 30,
                ],
            ],
        ];

        foreach ($pageSections as $page => $pageItems) {
            foreach ($pageItems as $key => $defaults) {
                PageSection::query()->firstOrCreate(
                    ['page' => $page, 'section_key' => $key],
                    [...$defaults, 'is_enabled' => true],
                );
            }
        }

        $products = [
            ['kubah-masjid-grc', 'Kubah Masjid GRC', 'Religi', 'Kubah ringan dan kokoh dengan ukuran serta ornamen islami yang disesuaikan pada rancangan masjid.', ['Tahan cuaca', 'Custom ornamen', 'Finishing fleksibel']],
            ['panel-grc-cladding', 'Panel GRC / Cladding', 'Komersial', 'Panel fasad eksterior dan interior dengan dimensi presisi, bobot ringan, dan pilihan tekstur custom.', ['Presisi dimensi', 'Tahan cuaca', 'Instalasi efisien']],
            ['ornamen-relief-grc', 'Ornamen & Relief GRC', 'Dekoratif', 'Elemen dekoratif klasik, modern, atau islami yang dicetak presisi dari shop drawing terverifikasi.', ['Detail presisi', 'Motif custom', 'Finishing fleksibel']],
            ['grc-krawangan-roster', 'GRC Krawangan / Roster', 'Ventilasi', 'Krawangan dekoratif untuk pencahayaan dan aliran udara alami dalam motif geometris atau custom.', ['Sirkulasi alami', 'Beragam motif', 'Kuat dan awet']],
            ['lisplang-profil-grc', 'Lisplang & Profil GRC', 'Finishing', 'Profil arsitektural untuk hasil akhir eksterior yang rapi, tahan lama, dan konsisten.', ['Profil presisi', 'Bobot ringan', 'Tahan lama']],
            ['kolom-pilar-grc', 'Kolom & Pilar GRC', 'Struktural', 'Selubung kolom dan pilar dekoratif dengan tampilan solid tanpa beban beton konvensional.', ['Custom ukuran', 'Ringan', 'Pilihan gaya']],
        ];

        foreach ($products as $index => [$slug, $name, $category, $description, $features]) {
            Product::query()->firstOrCreate(['slug' => $slug], [
                'name' => $name, 'category' => $category, 'short_description' => $description,
                'description' => $description, 'features' => $features, 'sort_order' => $index,
                'is_featured' => $index < 3, 'is_published' => true, 'published_at' => now(),
            ]);
        }

        $projects = [
            ['kubah-masjid-jami-bogor', 'Kubah Masjid Jami', 'Bogor, Jawa Barat', 'Religi', 2023, 'Kubah diameter 8 meter dengan detail ornamen kaligrafi custom.'],
            ['fasad-hotel-green-kosambi', 'Fasad Hotel Green Kosambi', 'Bandung, Jawa Barat', 'Hospitality', 2022, 'Cladding panel GRC untuk fasad hotel seluas 2.400 meter persegi.'],
            ['kantor-perbankan-bogor', 'Kantor Perbankan', 'Bogor, Jawa Barat', 'Komersial', 2021, 'Panel dekoratif eksterior dengan modul presisi.'],
            ['kawasan-residensial-cikarang', 'Kawasan Residensial', 'Cikarang, Jawa Barat', 'Residensial', 2020, 'Lisplang dan profil GRC untuk unit hunian.'],
            ['menara-masjid-laban', 'Menara Masjid Laban', 'Serang, Banten', 'Religi', 2019, 'Produksi menara, ornamen islami, dan finishing eksterior.'],
            ['ornamen-area-tematik', 'Ornamen Area Tematik', 'Bandung, Jawa Barat', 'Hospitality', 2020, 'Ornamen dan krawangan dekoratif untuk area publik.'],
        ];

        foreach ($projects as $index => [$slug, $title, $location, $category, $year, $description]) {
            PortfolioProject::query()->firstOrCreate(['slug' => $slug], [
                'title' => $title, 'location' => $location, 'category' => $category,
                'year' => $year, 'description' => $description, 'sort_order' => $index,
                'is_featured' => true, 'is_published' => true, 'published_at' => now(),
            ]);
        }

        foreach ([
            ['Berapa harga GRC panel per m2?', 'Harga dipengaruhi ketebalan, bentuk, detail, finishing, volume, dan lokasi instalasi.'],
            ['Apakah motif dan ukuran dapat dibuat custom?', 'Ya. Tim kami meninjau konsep dan shop drawing sebelum produksi cetakan.'],
            ['Berapa lama proses produksi?', 'Durasi bergantung pada kompleksitas, volume, dan jadwal instalasi proyek.'],
        ] as $index => [$question, $answer]) {
            Faq::query()->firstOrCreate(['question' => $question], [
                'answer' => $answer, 'category' => 'Produk', 'is_published' => true, 'sort_order' => $index,
            ]);
        }

        foreach ([
            ['Perwakilan Kontraktor', 'Proyek rumah ibadah, Bogor', 'Koordinasi ukuran dan detail ornamen berjalan jelas sebelum produksi dimulai.'],
            ['Tim Arsitek', 'Proyek komersial, Jawa Barat', 'Tim responsif terhadap revisi shop drawing dan kebutuhan pemasangan di lapangan.'],
            ['Pemilik Proyek', 'Proyek residensial', 'Pilihan motif custom membantu tampilan fasad tetap sesuai konsep bangunan.'],
        ] as $index => [$name, $role, $quote]) {
            Testimonial::query()->firstOrCreate(['name' => $name, 'company_role' => $role], [
                'quote' => $quote, 'rating' => 5, 'is_published' => true, 'sort_order' => $index,
            ]);
        }

        foreach ([
            ['Tim Manajemen', 'Manajemen & Hubungan Klien', 'Mengawal arah perusahaan, kebutuhan klien, dan keputusan utama agar ruang lingkup proyek tetap jelas.'],
            ['Tim Engineering', 'Desain & Shop Drawing', 'Menerjemahkan konsep arsitektur menjadi detail produksi, modul, dan metode pemasangan yang dapat dilaksanakan.'],
            ['Tim Produksi', 'Produksi & Quality Control', 'Menjaga proses cetakan, laminasi, curing, finishing, dan pemeriksaan mutu sebelum pengiriman.'],
            ['Tim Proyek', 'Instalasi & Koordinasi Lapangan', 'Mengelola kesiapan lokasi, pengiriman, pemasangan, finishing, dan dokumentasi serah terima.'],
        ] as $index => [$name, $role, $bio]) {
            TeamMember::query()->firstOrCreate(['name' => $name], [
                'role' => $role,
                'bio' => $bio,
                'is_published' => true,
                'sort_order' => $index,
            ]);
        }

        foreach ([
            ['panduan-memilih-grc-panel-untuk-masjid', 'Panduan Memilih GRC Panel untuk Masjid', 'Faktor desain, ketebalan, cetakan, finishing, dan pemasangan yang perlu diperiksa.'],
            ['grc-untuk-fasad-komersial', 'GRC untuk Fasad Komersial: Ringan, Presisi, dan Fleksibel', 'Cara kontraktor dan arsitek memanfaatkan panel GRC pada fasad bangunan.'],
        ] as [$slug, $title, $excerpt]) {
            BlogPost::query()->firstOrCreate(['slug' => $slug], [
                'title' => $title, 'excerpt' => $excerpt,
                'content' => "## {$title}\n\n{$excerpt}\n\nHubungi tim kami untuk pembahasan teknis sesuai kebutuhan proyek.",
                'status' => PublicationStatus::Published, 'published_at' => now(),
            ]);
        }
    }
}
