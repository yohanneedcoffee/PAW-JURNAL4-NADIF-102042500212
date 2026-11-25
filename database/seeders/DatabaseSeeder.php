<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210001',
            ]
        );

        // 3. Mata Kuliah Dasar (Modul 3)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama (status approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        // TODO 8: Tambahan mata kuliah (semester berbeda), mahasiswa, tugas, dan submission

        // Mahasiswa tambahan
        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'mahasiswa2@celoe.test'],
            [
                'name' => 'Siti Rahmawati',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210002',
            ]
        );

        // Mata kuliah 2 (Genap 2025/2026)
        $course2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF302'],
            [
                'nama_mk' => 'Sistem Basis Data',
                'deskripsi' => 'Mata kuliah yang membahas perancangan basis data relasional, normalisasi, SQL, dan transaksi.',
                'sks' => 3,
                'semester' => 'Genap 2025/2026',
                'dosen_id' => $dosen->id,
            ]
        );

        // Mata kuliah 3 (Ganjil 2025/2026)
        $course3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF303'],
            [
                'nama_mk' => 'Jaringan Komputer',
                'deskripsi' => 'Mata kuliah yang membahas model OSI/TCP-IP, subnetting, routing, dan konfigurasi jaringan dasar.',
                'sks' => 3,
                'semester' => 'Ganjil 2025/2026',
                'dosen_id' => $dosen->id,
            ]
        );

        // Daftarkan mahasiswa ke mata kuliah tambahan
        $course1->students()->syncWithoutDetaching([
            $mahasiswa2->id => ['status' => 'approved'],
        ]);
        $course2->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);
        $course3->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'pending'],
        ]);

        // Tugas
        $tugas1 = Assignment::updateOrCreate(
            ['course_id' => $course1->id, 'judul' => 'Tugas 1: Routing dan Controller Laravel'],
            [
                'deskripsi' => 'Buat minimal 3 route yang terhubung ke controller dan tampilkan hasilnya lewat Blade view.',
                'deadline' => now()->addDays(7),
            ]
        );

        $tugas2 = Assignment::updateOrCreate(
            ['course_id' => $course2->id, 'judul' => 'Tugas 1: Normalisasi Basis Data'],
            [
                'deskripsi' => 'Lakukan normalisasi dari 1NF sampai 3NF pada studi kasus yang diberikan.',
                'deadline' => now()->addDays(10),
            ]
        );

        // Submission 1: sudah dinilai dosen
        Submission::updateOrCreate(
            ['assignment_id' => $tugas1->id, 'student_id' => $mahasiswa1->id],
            [
                'jawaban' => 'Sudah dibuat 3 route (GET /, GET /courses, POST /courses) beserta controllernya.',
                'nilai' => 85,
                'catatan_dosen' => 'Bagus, struktur route sudah rapi. Tambahkan validasi input pada POST.',
            ]
        );

        // Submission 2: belum dinilai
        Submission::updateOrCreate(
            ['assignment_id' => $tugas2->id, 'student_id' => $mahasiswa2->id],
            [
                'jawaban' => 'Hasil normalisasi 1NF, 2NF, dan 3NF terlampir beserta tabel akhirnya.',
                'nilai' => null,
                'catatan_dosen' => null,
            ]
        );
    }
}