<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MedKnowledge;

class MedKnowledgeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['title' => 'Paracetamol', 'content' => 'Paracetamol digunakan untuk menurunkan demam dan meredakan nyeri ringan hingga sedang. Dosis dewasa biasanya 500 mg hingga 1000 mg setiap 4-6 jam, maksimal 4 gram per hari. Hati-hati pada pasien dengan gangguan hati.'],
            ['title' => 'Ibuprofen', 'content' => 'Ibuprofen adalah NSAID yang digunakan untuk nyeri dan peradangan. Dosis umum dewasa 200-400 mg setiap 4-6 jam, jangan melebihi 1200 mg tanpa pengawasan. Hindari pada pasien maag atau gangguan ginjal.'],
            ['title' => 'Aspirin', 'content' => 'Aspirin (asetilsalisilat) untuk nyeri ringan dan pencegahan trombosis pada dosis rendah. Tidak dianjurkan pada anak-anak dengan infeksi viral (risiko Reye). Hati-hati pada pasien perdarahan.'],
            ['title' => 'Amoxicillin', 'content' => 'Amoxicillin adalah antibiotik golongan penicillin untuk berbagai infeksi bakteri. Dosis umum tergantung indikasi; ikuti resep dokter. Perhatikan riwayat alergi penisilin.'],
            ['title' => 'Azithromycin', 'content' => 'Azithromycin merupakan makrolida untuk infeksi saluran pernapasan dan lainnya. Dosis sering 500 mg hari pertama lalu 250 mg selama 4 hari berikutnya. Perhatikan interaksi dengan obat jantung.'],
            ['title' => 'Ambroxol', 'content' => 'Ambroxol digunakan sebagai ekspektoran untuk batuk berdahak, membantu mengencerkan dahak. Ikuti dosis pada petunjuk dan jangan diberikan pada anak kecil tanpa saran.'],
            ['title' => 'Guaifenesin', 'content' => 'Guaifenesin adalah ekspektoran yang membantu batuk berdahak. Ikuti dosis sesuai label. Minum banyak air untuk membantu efektivitasnya.'],
            ['title' => 'Dextromethorphan', 'content' => 'Dextromethorphan adalah antitussive untuk batuk kering. Hindari pada anak di bawah usia tertentu dan jangan kombinasikan dengan MAOI.'],
            ['title' => 'Cetirizine', 'content' => 'Cetirizine adalah antihistamin generasi kedua untuk alergi; biasanya tidak terlalu mengantuk. Dosis 10 mg sekali sehari pada dewasa.'],
            ['title' => 'Chlorpheniramine', 'content' => 'Chlorpheniramine adalah antihistamin generasi pertama yang dapat menyebabkan kantuk. Digunakan untuk gejala alergi dan pilek. Hati-hati saat mengemudi.'],
            ['title' => 'Loperamide', 'content' => 'Loperamide untuk diare akut. Ikuti dosis pada label; jangan diberikan pada diare berdarah atau demam tinggi tanpa pemeriksaan medis.'],
            ['title' => 'Omeprazole', 'content' => 'Omeprazole adalah inhibitor pompa proton untuk tukak lambung dan GERD. Biasanya 20 mg sekali sehari. Perlu waktu beberapa hari untuk efek penuh.'],
            ['title' => 'Metformin', 'content' => 'Metformin digunakan pada diabetes tipe 2 untuk menurunkan gula darah. Perhatikan fungsi ginjal sebelum pemberian.'],
            ['title' => 'Salbutamol inhaler', 'content' => 'Salbutamol (albuterol) inhaler untuk bronkospasme/asthma episodik. Gunakan sesuai inhaler; bawalah saat serangan.'],
            ['title' => 'Warfarin', 'content' => 'Warfarin adalah antikoagulan. Memerlukan monitoring INR. Banyak interaksi obat dan makanan; konsultasi dokter wajib.'],
            ['title' => 'Simvastatin', 'content' => 'Simvastatin untuk menurunkan kolesterol LDL. Hindari penggunaan bersamaan dengan beberapa makrolida yang meningkatkan risiko miopati.'],
            ['title' => 'Amoxicillin-Clavulanate', 'content' => 'Kombinasi antibiotik yang memperluas spektrum amoxicillin; ikuti dosis dan durasi. Perhatikan diare sebagai efek samping.'],
            ['title' => 'Prednisone', 'content' => 'Prednisone adalah kortikosteroid sistemik untuk inflamasi. Jangan hentikan tiba-tiba; perhatikan efek sistemik jangka panjang.'],
            ['title' => 'Insulin - umum', 'content' => 'Insulin digunakan untuk kontrol gula darah. Dosis individual dan membutuhkan pemantauan glukosa.'],
            ['title' => 'Antasid - umum', 'content' => 'Antasid (mengandung magnesium/Aluminium) untuk meredakan maag sementara. Perhatikan interaksi dengan absorption beberapa obat.'],
            ['title' => 'Antibiotik oral - catatan umum', 'content' => 'Antibiotik harus digunakan sesuai resep; habiskan sisa obat sesuai anjuran. Jangan gunakan antibiotik untuk infeksi viral.'],
            ['title' => 'Kontraindikasi kehamilan umum', 'content' => 'Beberapa obat tidak aman pada kehamilan (mis: isotretinoin, beberapa NSAID pada trimester akhir). Selalu konsultasi jika sedang hamil.'],
            ['title' => 'Penggunaan anak-anak - catatan', 'content' => 'Banyak obat memiliki batasan usia. Jangan memberikan obat dewasa pada anak tanpa saran dokter; perhatikan dosis mg/kg.'],
            ['title' => 'Interaksi NSAID & Antikoagulan', 'content' => 'NSAID dapat meningkatkan risiko perdarahan bila dikombinasikan dengan antikoagulan seperti warfarin. Konsultasi jika pasien mengonsumsi keduanya.'],
            ['title' => 'Efek samping umum - mual', 'content' => 'Mual dapat menjadi efek samping beberapa obat seperti antibiotik atau opioid; minum bersama makanan bila dianjurkan.'],
            ['title' => 'Dosis paracetamol anak', 'content' => 'Dosis paracetamol pada anak dihitung berdasarkan berat badan - biasanya 10-15 mg/kg per dosis. Cek label atau tanya apoteker.'],
            ['title' => 'OBH & obat batuk rumah', 'content' => 'Banyak produk OTC untuk batuk; pilih sesuai type batuk (kering vs berdahak) dan usia. Bacalah label.'],
            ['title' => 'Larutan rehidrasi oral', 'content' => 'Larutan rehidrasi oral penting untuk mencegah dehidrasi pada diare. Anjurkan untuk anak dan lansia bila diperlukan.'],
            ['title' => 'Saran konsultasi apoteker', 'content' => 'Jika ragu tentang interaksi obat, dosis, atau efek samping, segera konsultasi dengan apoteker atau dokter.']
        ];


        foreach ($data as $row) {
            MedKnowledge::create($row);
        }
    }
}
