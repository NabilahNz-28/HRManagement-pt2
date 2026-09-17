# Implementasi Fitur Profil & Dark Mode

Berikut adalah ringkasan dari fitur yang telah diselesaikan untuk memenuhi permintaan Anda (karena Anda sudah mencapai limit pemakaian, Anda dapat meninjau hasil ini nanti).

## 1. Dark Mode & Light Mode (Sudah Selesai)
- **CSS Variables:** Telah ditambahkan sistem tema menggunakan CSS custom properties (`--bg-main`, `--text-primary`, dll) di `index.html`.
- **Toggle Button:** Tombol bulan/matahari di topbar dan toggle switch di halaman Pengaturan sekarang berfungsi.
- **Penyimpanan Preferensi:** Pilihan tema disimpan di `localStorage` (`hrTheme`), sehingga saat halaman di-refresh, tema yang dipilih (dark/light) akan tetap aktif.
- **Javascript Logic:** Fungsi `toggleTheme()` dan `applyTheme()` telah ditambahkan di `app.js`.

## 2. Manajemen Profil & Foto (Sudah Selesai)
Halaman "Pengaturan" yang sebelumnya hanya berisi data *dummy* telah dirombak total menjadi editor profil interaktif:
- **Informasi Pribadi:** Form untuk mengubah Nama, NIK, Email, No. Telepon, Jabatan, dan Departemen.
- **Penyimpanan Lokal:** Data disimpan menggunakan `localStorage` (`hrProfile`), sehingga perubahan akan langsung tercermin di sidebar, topbar, dan halaman profil tanpa memerlukan database backend untuk versi ini.
- **Ganti Password:** Simulasi form ganti password dengan validasi dasar.

## 3. Fitur Upload & Crop Foto Profil (Sudah Selesai)
- **Upload:** Tombol kamera di atas avatar untuk memilih foto dari perangkat Anda.
- **Crop Modal:** Setelah memilih foto, akan muncul popup (modal) dengan *crop box* yang bisa digeser dan diubah ukurannya secara dinamis.
- **Canvas Processing:** Pemotongan gambar dilakukan langsung di browser menggunakan HTML5 Canvas, memastikan foto profil yang disimpan selalu berbentuk persegi sempurna.
- **Hapus Foto:** Tombol untuk menghapus foto profil dan kembali menggunakan inisial nama.
- **Penyimpanan:** Foto hasil *crop* disimpan di `localStorage` (`hrProfilePhoto`) dalam format Data URL (Base64).

## Cara Uji Coba (Bila Limit Anda Sudah Reset)
1. Buka browser dan arahkan ke `http://localhost/HRManagement-pt2/`.
2. Lakukan **Hard Refresh** (`Ctrl + Shift + R`) agar browser memuat versi terbaru dari `index.html` dan `app.js`.
3. Klik icon bulan/matahari di topbar untuk menguji Dark/Light mode.
4. Pergi ke menu **Pengaturan**, coba ubah nama Anda dan klik "Simpan Informasi". Perhatikan nama di sidebar dan topbar akan otomatis berubah.
5. Klik ikon kamera pada avatar profil, pilih foto, lakukan pemotongan (crop), dan terapkan. Foto akan langsung tampil di semua elemen avatar.

Semua fitur *frontend* ini telah dikodekan menggunakan Vanilla JS dan Tailwind CSS agar ringan, responsif, dan stabil.
