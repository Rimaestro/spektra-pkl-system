<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'PT. Teknologi Maju Indonesia',
                'address' => 'Jl. Jenderal Sudirman Kav. 52-53, SCBD, Jakarta Selatan, DKI Jakarta 12190',
                'contact_person' => 'Agus Setiawan',
                'phone' => '021-29889900',
                'email' => 'hr@teknologimaju.co.id',
                'description' => 'Perusahaan teknologi terdepan yang bergerak di bidang pengembangan software enterprise, sistem informasi manajemen, dan solusi digital transformation untuk berbagai industri di Indonesia.',
                'website' => 'https://www.teknologimaju.co.id',
                'status' => 'active',
                'max_students' => 6,
            ],
            [
                'name' => 'CV. Digital Solusi Kreatif',
                'address' => 'Jl. Gatot Subroto Kav. 18, Jakarta Selatan, DKI Jakarta 12950',
                'contact_person' => 'Maya Sari Putri',
                'phone' => '021-52901234',
                'email' => 'info@digitalsolusi.com',
                'description' => 'Perusahaan kreatif yang fokus pada pengembangan aplikasi mobile, web development, UI/UX design, dan digital marketing solutions untuk UMKM dan startup.',
                'website' => 'https://www.digitalsolusi.com',
                'status' => 'active',
                'max_students' => 4,
            ],
            [
                'name' => 'PT. Inovasi Sistem Terpadu',
                'address' => 'Jl. MH Thamrin No. 28-30, Jakarta Pusat, DKI Jakarta 10350',
                'contact_person' => 'Dedi Kurniawan',
                'phone' => '021-39831234',
                'email' => 'recruitment@inovasisistem.co.id',
                'description' => 'Perusahaan yang spesialisasi dalam sistem informasi manajemen, enterprise resource planning (ERP), dan business intelligence solutions untuk perusahaan besar dan BUMN.',
                'website' => 'https://www.inovasisistem.co.id',
                'status' => 'active',
                'max_students' => 5,
            ],
            [
                'name' => 'PT. TechHub Indonesia',
                'address' => 'Jl. HR Rasuna Said Kav. C-5, Kuningan, Jakarta Selatan, DKI Jakarta 12940',
                'contact_person' => 'Lisa Permata Sari',
                'phone' => '021-57901234',
                'email' => 'careers@techhub.id',
                'description' => 'Startup teknologi yang mengembangkan platform e-commerce B2B, fintech solutions, dan marketplace digital untuk mendukung transformasi digital UMKM Indonesia.',
                'website' => 'https://www.techhub.id',
                'status' => 'active',
                'max_students' => 3,
            ],
            [
                'name' => 'PT. Data Analytics Pro',
                'address' => 'Jl. HR Rasuna Said Kav. B-4, Setiabudi, Jakarta Selatan, DKI Jakarta 12920',
                'contact_person' => 'Rudi Hartono',
                'phone' => '021-52781234',
                'email' => 'hr@dataanalytics.co.id',
                'description' => 'Perusahaan konsultan yang spesialisasi dalam big data analytics, business intelligence, data science, dan machine learning solutions untuk berbagai industri.',
                'website' => 'https://www.dataanalytics.co.id',
                'status' => 'active',
                'max_students' => 4,
            ],
            [
                'name' => 'CV. Kreatif Web Studio',
                'address' => 'Jl. Kemang Raya No. 12B, Jakarta Selatan, DKI Jakarta 12560',
                'contact_person' => 'Indira Sari',
                'phone' => '021-71901234',
                'email' => 'contact@kreatifweb.com',
                'description' => 'Studio kreatif yang fokus pada web development, mobile app development, UI/UX design, dan digital marketing untuk startup dan perusahaan menengah.',
                'website' => 'https://www.kreatifweb.com',
                'status' => 'active',
                'max_students' => 3,
            ],
            [
                'name' => 'PT. Nusantara Cloud Solutions',
                'address' => 'Jl. Asia Afrika No. 8, Senayan, Jakarta Pusat, DKI Jakarta 10270',
                'contact_person' => 'Andi Wijaya Kusuma',
                'phone' => '021-57221234',
                'email' => 'jobs@nusantaracloud.co.id',
                'description' => 'Perusahaan penyedia layanan cloud computing, infrastructure as a service (IaaS), platform as a service (PaaS), dan managed IT services.',
                'website' => 'https://www.nusantaracloud.co.id',
                'status' => 'active',
                'max_students' => 5,
            ],
            [
                'name' => 'PT. AI Innovation Indonesia',
                'address' => 'Jl. Menteng Raya No. 25, Jakarta Pusat, DKI Jakarta 10340',
                'contact_person' => 'Dr. Dina Kartika Sari',
                'phone' => '021-31451234',
                'email' => 'team@aiinnovation.id',
                'description' => 'Startup teknologi yang mengembangkan solusi artificial intelligence, machine learning, computer vision, dan natural language processing untuk industri 4.0.',
                'website' => 'https://www.aiinnovation.id',
                'status' => 'active',
                'max_students' => 4,
            ],
            [
                'name' => 'PT. Fintech Nusantara',
                'address' => 'Jl. Jenderal Sudirman Kav. 25, Jakarta Pusat, DKI Jakarta 12920',
                'contact_person' => 'Bambang Prasetyo',
                'phone' => '021-29001234',
                'email' => 'hr@fintechnusantara.co.id',
                'description' => 'Perusahaan fintech yang mengembangkan solusi pembayaran digital, peer-to-peer lending, dan financial technology untuk inklusi keuangan Indonesia.',
                'website' => 'https://www.fintechnusantara.co.id',
                'status' => 'active',
                'max_students' => 3,
            ],
            [
                'name' => 'CV. Mobile App Developer',
                'address' => 'Jl. Tebet Raya No. 45, Jakarta Selatan, DKI Jakarta 12810',
                'contact_person' => 'Sari Wulandari',
                'phone' => '021-83701234',
                'email' => 'info@mobileappdev.com',
                'description' => 'Perusahaan yang fokus pada pengembangan aplikasi mobile Android dan iOS, progressive web apps, dan cross-platform development.',
                'website' => 'https://www.mobileappdev.com',
                'status' => 'active',
                'max_students' => 2,
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }

        // Create additional companies using factory
        Company::factory(12)->create();
    }
}
