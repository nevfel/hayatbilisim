<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => '1C ERP Kurulum ve Danışmanlık Paketi',
            'description' => 'İşletmenizin dijital dönüşümü için kapsamlı ERP çözümü. 1C ERP sistemi kurulum, özelleştirme ve danışmanlık hizmetleri ile işletmenizi geleceğe taşıyın.',
            'price' => 25000.00,
            'sku' => 'ERP-001',
            'stock' => 999,
            'is_active' => true,
            'meta' => [
                'features' => [
                    'Temel 1C ERP Kurulumu',
                    'Kullanıcı Eğitimi (5 kişi)',
                    '3 Ay Teknik Destek',
                    'Sistem Entegrasyon Danışmanlığı'
                ],
                'services' => [
                    [
                        'id' => 'iot_integration',
                        'name' => 'IoT Sistem Entegrasyonu',
                        'description' => 'Nesnelerin interneti (IoT) cihazlarının ERP sisteminize entegrasyonu',
                        'price' => 15000.00
                    ],
                    [
                        'id' => 'advanced_training',
                        'name' => 'İleri Düzey Kullanıcı Eğitimi',
                        'description' => 'Ek 10 kullanıcı için kapsamlı eğitim programı',
                        'price' => 5000.00
                    ],
                    [
                        'id' => 'custom_modules',
                        'name' => 'Özel Modül Geliştirme',
                        'description' => 'İşletmenize özel 2 adet modül geliştirme',
                        'price' => 20000.00
                    ],
                    [
                        'id' => 'data_migration',
                        'name' => 'Veri Taşıma Hizmeti',
                        'description' => 'Mevcut sistemden 1C ERP\'ye veri aktarımı',
                        'price' => 8000.00
                    ],
                    [
                        'id' => 'extended_support',
                        'name' => 'Genişletilmiş Destek (1 Yıl)',
                        'description' => '7/24 premium teknik destek hizmeti',
                        'price' => 12000.00
                    ]
                ],
                'slug' => 'erp-kurulum-danismanlik'
            ]
        ]);
    }
}
