<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OCRController extends Controller
{
    public function showForm()
    {
        return view('upload');
    }

    // public function extractText(Request $request)
    // {
    //     // Validasi file yang diunggah adalah gambar
    //     $request->validate([
    //         'image' => 'required|image',
    //     ]);

    //     // Simpan file gambar yang diunggah
    //     $imagePath = $request->file('image')->store('images');

    //     // Path lengkap menuju file gambar
    //     $imageFullPath = storage_path('app/' . $imagePath);

    //     // Gunakan Tesseract untuk mengekstrak teks dari gambar
    //     $text = (new TesseractOCR($imageFullPath))->run();

    //     // Hapus file gambar yang tidak diperlukan lagi
    //     unlink($imageFullPath);

    //     // Olah teks awal, misalnya dengan memecah teks per baris
    //     $lines = explode("\n", $text);

    //     // Inisialisasi variabel untuk nama dan NIK
    //     $nama = '';
    //     $nik = '';
    //     $alamat = '';
    //     $jk = '';
    //     $darah = '';

    //     // Looping untuk mencari data nama dan NIK
    //     foreach ($lines as $line) {
    //         // Mencari pola NIK dengan menggunakan regex
    //         if (preg_match('/NIK\s*:\s*(\d+)/', $line, $matches)) {
    //             $nik = $matches[1];
    //         }

    //         // Mencari pola nama dengan kata kunci 'Nama:'
    //         if (preg_match('/Nama\s*:\s*(.*)/', $line, $matches)) {
    //             $nama = trim($matches[1]);
    //         }

    //         // Mencari pola nama dengan kata kunci 'Nama:'
    //         if (preg_match('/Alamat\s*:\s*(.*)/', $line, $matches)) {
    //             $alamat = trim($matches[1]);
    //         }

    //         if (preg_match('/Jenis Kelamin\s*:\s*(.*)/', $line, $matches)) {
    //             $jk = trim($matches[1]);
    //         }

    //         if (preg_match('/Gol. Darah\s*:\s*(.*)/', $line, $matches)) {
    //             $darah = trim($matches[1]);
    //         }
    //     }

    //     // Tampilkan hasil dalam dd untuk debugging
    //     dd([
    //         'nama' => $nama,
    //         'nik' => $nik,
    //         'alamat' => $alamat,
    //         'jk' => $jk,
    //         'darah' => $darah
    //     ]);
    // }

    public function extractText(Request $request)
    {
        // Validasi file yang diunggah adalah gambar
        $request->validate([
            'image' => 'required|image',
        ]);

        // Simpan file gambar yang diunggah
        $imagePath = $request->file('image')->store('images');

        // Path lengkap menuju file gambar
        $imageFullPath = storage_path('app/' . $imagePath);

        // Gunakan Tesseract untuk mengekstrak teks dari gambar
        $text = (new TesseractOCR($imageFullPath))->run();

        // Hapus file gambar yang tidak diperlukan lagi
        unlink($imageFullPath);

        // Olah teks awal, misalnya dengan memecah teks per baris
        $lines = explode("\n", $text);

        // Inisialisasi variabel untuk nama, NIK, alamat, jenis kelamin, dan golongan darah
        $nama = '';
        $nik = '';
        $alamat = '';
        $jk = '';
        $darah = '';
        $agama = '';
        $status = '';
        $pekerjaan = '';
        $kewarganegaraan = '';
        $berlaku = '';
        $rtRw = '';
        $tempatTanggalLahir = '';
        $kelDesa = '';
        $kecamatan = '';

        // Looping untuk mencari data nama dan NIK
        foreach ($lines as $line) {
            // Mencari pola NIK dengan menggunakan regex
            if (preg_match('/NIK\s*:\s*(\d+)/', $line, $matches)) {
                $nik = $matches[1];
            }

            // Mencari pola nama dengan kata kunci 'Nama:'
            if (preg_match('/Nama\s*:\s*(.*)/', $line, $matches)) {
                $nama = trim($matches[1]);
            }

            if (preg_match('/Tempat\/Tgl Lahir\s*:\s*(.*)/', $line, $matches)) {
                $tempatTanggalLahir = trim($matches[1]);
            }

            // Mencari pola jenis kelamin dengan kata kunci 'Jenis Kelamin:'
            if (preg_match('/Jenis Kelamin\s*:\s*(.*)/', $line, $matches)) {
                // Ambil jenis kelamin
                $jk = trim($matches[1]);

                // Hapus tulisan 'Gol. Darah : B' jika ada
                $jk = preg_replace('/Gol\. Darah\s*:\s*\w+/', '', $jk);
                $jk = trim($jk); // Trim spaces after replacement
            }

            // Mencari pola golongan darah dengan kata kunci 'Gol. Darah:'
            if (preg_match('/Gol\. Darah\s*:\s*(.*)/', $line, $matches)) {
                $darah = trim($matches[1]);
            }

            // Mencari pola alamat dengan kata kunci 'Alamat:'
            if (preg_match('/Alamat\s*:\s*(.*)/', $line, $matches)) {
                $alamat = trim($matches[1]);
            }

            if (preg_match('/RT\/RW\s*:\s*(\d{3}\/\d{3})/', $line, $matches)) {
                $rtRw = trim($matches[1]);
            }

            if (preg_match('/Kel\/Desa\s*:\s*(.*)/', $line, $matches)) {
                $kelDesa = trim($matches[1]);
            }

            if (preg_match('/Kecamatan\s*:\s*(.*)/', $line, $matches)) {
                $kecamatan = trim($matches[1]);
            }

            if (preg_match('/Agama\s*:\s*(.*)/', $line, $matches)) {
                $agama = trim($matches[1]);
            }

            if (preg_match('/Status Perkawinan\s*:\s*(.*)/', $line, $matches)) {
                $status = trim($matches[1]);
            }

            if (preg_match('/Pekerjaan\s*:\s*(.*)/', $line, $matches)) {
                $pekerjaan = trim($matches[1]);
            }

            if (preg_match('/Kewarganegaraan\s*:\s*(.*)/', $line, $matches)) {
                // Ambil bagian sebelum keterangan tambahan
                $kewarganegaraan = trim($matches[1]);

                // Hapus keterangan setelah kata kunci utama, misalnya 'WNI', 'WNA', dll.
                $parts = explode(' ', $kewarganegaraan, 2);
                if (count($parts) > 1) {
                    $kewarganegaraan = trim($parts[0]);
                }
            }

            // if (preg_match('/Berlaku Hingga\s*:\s*(\d{2}-\d{2}-\d{4})/', $line, $matches)) {
            //     $berlaku = trim($matches[1]);
            // } elseif (preg_match('/Berlaku Hingga\s*:\s*(\d{2}-\d{2}-\s*\d{4})/', $line, $matches)) {
            //     // Handle cases where there might be extra spaces
            //     $berlaku = trim($matches[1]);
            // }

            if (preg_match('/Berlaku\/Hingga\s*:\s*(.*)/', $line, $matches)) {
                $berlaku = trim($matches[1]);
            }
        }

        // Tampilkan hasil dalam dd untuk debugging
        dd([
            'nama' => $nama,
            'nik' => $nik,
            'alamat' => $alamat,
            'jk' => $jk,
            'darah' => $darah,
            'agama' => $agama,
            'status' => $status,
            'pekerjaan' => $pekerjaan,
            'kewarganegaraan' => $kewarganegaraan,
            'berlaku' => $berlaku,
            'rtRw' => $rtRw,
            'tempatTanggalLahir' => $tempatTanggalLahir,
            'kelDesa' => $kelDesa,
            'kecamatan' => $kecamatan,
        ]);
    }
}
