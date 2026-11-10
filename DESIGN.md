# Design System — TeamMate

## Aesthetic Direction & World
**Collegiate Competition Dispatch ("Architectural Campus Roster")**
Dirancang dengan ketajaman papan pengumuman riset & kompetisi universitas modern (terinspirasi dari kerapian editorial *Linear*, *Read.cv*, dan sistem direktori kompetisi desain di *Dribbble/Behance*): kontras tinggi antara bidang *Deep Campus Navy* (`#0B1528`) dan permukaan kertas teknis terang (`#F6F8FC`), garis pembatas setipis 1px (`#E2E8F0`), tipografi geometris yang padat, serta aksen *Electric Sky* (`#38BDF8`) dan *Signal Cobalt* (`#2160F5`) untuk penanda status rekrutmen aktif.

## Brand Mark (`<x-logo />`)
- **Konsep**: Monogram geometris **T–M** di dalam persegi bersudut lengkung (`rx="52"` pada kanvas `256×256`). Palang atas dan tiang tengah membentuk huruf **T** putih tegas, sementara dua pilar biru langit (`#38BDF8`) di sisi kiri-kanan mengapit ruang negatif sehingga keseluruhan siluet membentuk huruf **M** sekaligus melambangkan dua rekan tim yang berdiri berdampingan.
- **Aturan Penggunaan**: Gunakan komponen `<x-logo />` di semua permukaan (Navbar publik, Footer, Panel Auth, dan Sidebar aplikasi). Jangan pernah mengganti simbol dengan emoji atau karakter Unicode.

## Color Tokens
| Token | Nilai | Peran |
|---|---|---|
| `--color-navy-950` | `#0b1528` | Latar sidebar aplikasi, permukaan kartu hero gelap, dasar simbol logo |
| `--color-navy-900` | `#0f1f3d` | Teks judul utama, tombol primer, aksen kontras tinggi |
| `--color-navy-800` | `#152a52` | Teks sekunder di atas permukaan biru muda |
| `--color-navy-50` | `#eef3fb` | Latar blok kriteria anggota & tab pasif |
| `--color-sky-400` / `500` | `#38bdf8` / `#0ea5e9` | Titik aksen logo, indikator slot terisi, fokus cincin input |
| `--page-surface` | `#f6f8fc` | Latar dasar halaman kerja |
| `--card-border` | `#e2e8f0` | Garis batas kartu dan tabel (1px solid) |

## Typography & Hierarchy
- **Font Utama**: `'Instrument Sans'`, sans-serif geometris dengan proporsi x-height tinggi.
- **Skala & Ritme**:
  -Judul Hero (`h1`): `clamp(2.25rem, 4vw, 3.25rem)`, `font-weight: 700`, `letter-spacing: -0.03em`, `text-wrap: balance`.
  - Judul Bagian (`h2`): `1.75rem–1.875rem`, `font-weight: 700`, `letter-spacing: -0.02em`.
  - Judul Kartu Kompetisi (`h3`): `1.0625rem` (`17px`), `font-weight: 700`, `line-height: 1.35`.
  - Teks Isi: `0.875rem–1rem`, `line-height: 1.6`, lebar baca dibatasi `60–72ch`.
  - Angka & Metrik: `font-variant-numeric: tabular-nums`.

## Micro-Interactions & Motion Grammar
Semua transisi menggunakan kurva eksponensial keluar `--ease-out-expo: cubic-bezier(0.16, 1, 0.3, 1)`:
1. **Button Click & Ripple (`.btn-primary`, `.btn-secondary`)**:
   - Hover: terangkat `-2px` dengan bayangan lembut ber-offset vertikal.
   - Active (Klik): menekan ke `scale(0.96)` dalam `80ms` disertai gelombang tinta (`ink` ripple) dari titik koordinat pointer (`--rx`, `--ry`).
   - Submit (`aria-busy="true"`): menampilkan cincin putar dan mengunci pengiriman ganda.
2. **Interactive Recruitment Cards (`.card-interactive`)**:
   - Hover: naik `-4px`, batas kartu berubah menjadi `#7dd3fc`, bayangan mendalam `0 16px 32px -16px rgb(15 31 61 / 0.24)`.
   - Active (Klik): menekan ke `scale(0.985)` sebelum membuka modal detail kompetisi.
3. **Loading Skeleton Shimmer (`.skeleton`)**:
   - Sapuan kilau diagonal (`linear-gradient(100deg, ...)`) selama `1.4s` saat mengambil data dari `/posts/latest`, diikuti animasi masuk bertahap (`.reveal-in`, jeda `70ms` antar kartu).
4. **Reduced Motion (`prefers-reduced-motion: reduce`)**:
   - Pergerakan spasial dan shimmer dinonaktifkan; umpan balik status tetap jelas melalui perubahan warna dan opasitas.

## Anti-Slop Rules Enforced
- Tidak ada *eyebrow/kicker badge* di atas judul `h1`/`h2`.
- Tidak ada teks bergradasi (*gradient text*) atau efek *glassmorphism* berlebihan.
- Tidak ada deretan kartu ikon generik berukuran sama pada bagian fitur; bagian *Features* menggunakan tata letak indeks editorial bergaris (*hairline ledger*) bernomor tabular.
- Tidak ada emoji/glyph Unicode (`♧`, `♙`, `🔔`, `👤`) sebagai pengganti ikon navigasi; seluruh ikon menggunakan SVG garis konsisten (`stroke-width="1.8"`).

