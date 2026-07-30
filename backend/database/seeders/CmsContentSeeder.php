<?php

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\PageSection;
use App\Models\PortfolioProject;
use App\Models\Product;
use App\Models\SiteSetting;
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

        foreach ([
            ['hero', 'Solusi GRC Presisi untuk Setiap Karya Arsitektur', 'Dari desain, produksi, hingga pemasangan oleh tim berpengalaman.', 10],
            ['about', 'Pengalaman Produksi yang Terukur', 'Kami menerjemahkan konsep arsitektur menjadi elemen GRC yang presisi dan siap dipasang.', 20],
            ['cta', 'Diskusikan Kebutuhan Proyek Anda', 'Kirim ukuran atau gambar kerja untuk mendapatkan estimasi yang relevan.', 90],
        ] as [$key, $title, $description, $order]) {
            PageSection::query()->firstOrCreate(
                ['page' => 'home', 'section_key' => $key],
                ['title' => $title, 'description' => $description, 'is_enabled' => true, 'sort_order' => $order],
            );
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
