# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Mahasiswa lintas jurusan dan universitas di Indonesia yang ingin mengikuti kompetisi akademik maupun non-akademik (Hackathon, Business Case, UI/UX Design, Riset/Sosial) dan sedang mencari anggota tim dengan keahlian yang saling melengkapi, atau ingin bergabung ke tim yang sedang membuka rekrutmen.

## Product Purpose

TeamMate mempertemukan ketua tim yang membuka lowongan anggota kompetisi dengan mahasiswa yang memiliki keahlian spesifik, memverifikasi identitas mahasiswa melalui unggahan Kartu Tanda Mahasiswa (KTM) dan verifikasi email, serta mengelola alur pengajuan bergabung (*Join Requests*) hingga tim terbentuk.

## Positioning

Rekrutmen tim kompetisi khusus mahasiswa terverifikasi KTM dengan struktur peran, kategori lomba, batas kriteria anggota, serta pengelolaan status rekrutmen (*active*, *closed*, *completed*) dalam satu ruang kerja.

## Operating Context

Digunakan oleh mahasiswa saat musim pendaftaran lomba nasional/internasional (GEMASTIK, PKM, Hackathon, Business Case). Pengguna membuka tautan dari kampus/komunitas di laptop atau ponsel, masuk atau mendaftar di halaman depan, lalu memfilter lowongan kompetisi berdasarkan kategori dan kriteria keahlian di halaman utama.

## Capabilities and Constraints

- Autentikasi berbasis tabel `accounts` dengan unggahan berkas KTM (PDF/JPG/PNG maks. 2 MB) dan verifikasi email (`MustVerifyEmail`).
- Publikasi lowongan kompetisi (`posts`) dengan poster, kategori (`categories`), dan kriteria anggota.
- Pencarian kata kunci dan penyaringan kategori kompetisi secara langsung dengan umpan balik *skeleton shimmer*.
- Pengelolaan *Join Requests* (`pending`, *accepted*, *declined*), pembaruan status lowongan, dan pelaporan postingan (`reports`).
- Basis data menggunakan PostgreSQL (Aiven) dengan kolom identitas (`generatedAs`).

## Brand Commitments

- Nama: **TeamMate** (ditulis dengan mark geometris T-M interlocking dan titik aksen biru langit).
- Bahasa antarmuka: perpaduan istilah navigasi standar yang akrab bagi mahasiswa (`Find Your Perfect Teammates`, `Active Recruitment Posts`, `Create Post`, `My Posts`, `Requests`) dengan penjelasan dan pesan status berbahasa Indonesia yang lugas.
- Palet utama: Deep Campus Navy (`#0B1528` / `#0F1F3D`), Electric Sky (`#38BDF8` / `#0284C7`), dan permukaan kertas terang (`#F6F8FC`).

## Evidence on Hand

- 4 kategori kompetisi aktif di basis data (`Teknologi`, `Bisnis`, `Desain`, `Sosial`).
- Data lowongan kompetisi nyata di tabel `posts` beserta poster, kriteria anggota, dan identitas pembuat (`accounts`).
- Komponen mark vektor kustom di `public/logo-mark.svg`, `public/favicon.svg`, dan `resources/views/components/logo.blade.php`.

## Product Principles

1. **Kepercayaan Mahasiswa Terverifikasi:** Identitas kampus, NIM, dan KTM menjadi fondasi kolaborasi agar rekrutmen tim bebas akun palsu.
2. **Pemisahan Gerbang & Ruang Kerja:** Pengunjung yang belum masuk disambut oleh gerbang *Landing + Auth Satu Halaman*, sedangkan anggota yang sudah masuk langsung bekerja di *Home Page* ber-sidebar.
3. **Kejelasan Kriteria di Pandangan Pertama:** Setiap kartu rekrutmen langsung menampilkan kategori lomba, kriteria keahlian yang dicari, dan pembuat lowongan tanpa harus membuka halaman baru.
4. **Umpan Balik Instan & Taktil:** Setiap klik tombol, perpindahan tab, penyaringan daftar, dan penekanan kartu memberikan respons visual yang terukur.

