STOKKU - Project Pemrograman Web

CARA MENJALANKAN (XAMPP Windows)
1. Ekstrak ZIP ke C:\xampp\htdocs sehingga ada folder stokku.
2. Jalankan Apache dan MySQL pada XAMPP.
3. Buka http://localhost/phpmyadmin lalu Import file database.sql (cukup sekali).
4. Buka http://localhost/stokku/ di browser.
5. Buat akun dengan username minimal 3 karakter dan password minimal 8 karakter, klik Daftar akun, lalu Masuk.
6. Tambah, cari, edit, dan hapus barang. Semua akun mengelola inventaris bersama; pendaftaran terbuka untuk demo lokal.

PEMENUHAN KETENTUAN
HTML: struktur form, tabel, dashboard.
CSS: tampilan responsif dan mode gelap.
JavaScript + DOM: pencarian langsung, konfirmasi hapus, estimasi harga, perubahan tema.
PHP: login, validasi, kondisi stok, perulangan dan perhitungan total inventaris.
Session: menyimpan username login dan token CSRF, melindungi dashboard.
Cookie: last_user menyimpan username selama 30 hari jika dipilih, bukan password.
MySQL: tabel users dan barang, koneksi PDO di config.php.
CRUD: INSERT, SELECT, UPDATE, DELETE pada dashboard.php.
Tanpa framework: PHP, HTML, CSS, JS native.

ALGORITMA
Untuk setiap barang, jumlahkan stok dan stok dikali harga. Jika stok kurang dari 5, tambah penghitung stok menipis. Jika stok 0 tampilkan Habis, jika 1–4 Menipis, selain itu Tersedia.

PENGUJIAN MANUAL
Daftar dan login; tambah barang; edit stok; cari nama; hapus; logout; akses dashboard tanpa login harus diarahkan ke login. Uji cookie dengan mencentang Ingat username. Uji tampilan pada HP.
Konfigurasi default XAMPP: root tanpa password. Ubah config.php jika berbeda. Untuk penggunaan publik, batasi registrasi dan tambahkan otorisasi sesuai kebutuhan.
