# Tugas Akhir: Product Manager — ALYRA FASHION

Aplikasi toko **ALYRA FASHION** dibuat sebagai mini project tugas akhir menggunakan PHP + MySQL. Seluruh ketentuan pada instruksi tugas diterapkan: Create, Read berbentuk card responsif, Update berdasarkan ID, Delete melalui POST + CSRF, validasi nama/harga/stok, nama unik, prepared statement, `htmlspecialchars()`, pencegahan duplikasi saat refresh, dan tampilan responsif.

Aplikasi web **PHP + MySQL** untuk mengelola produk dengan konsep CRUD (Create, Read, Update, Delete), validasi input, prepared statement, output escaping, dan CSRF protection.

## 1. Fitur yang diwajibkan

- **Create:** nama, kategori, harga, stok.
- **Read:** daftar produk dalam **card responsif**.
- **Update:** edit produk berdasarkan **ID**.
- **Delete:** hanya melalui **POST + CSRF**.
- **Validasi server:** nama minimal 3 karakter, harga > 0, stok >= 0, dan nama unik.
- **Prepared statement:** INSERT, SELECT berdasarkan ID, UPDATE, DELETE.
- **Keamanan output:** `htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8')` dan escaping untuk output lainnya.
- Input seperti `<b>Promo</b>` tidak dieksekusi sebagai HTML; tampil sebagai teks.
- Refresh setelah Create tidak membuat duplikasi karena satu request hanya melakukan satu INSERT, lalu redirect ke halaman daftar (PRG), ditambah UNIQUE constraint pada nama.
- Tampilan tetap rapi pada layar sempit/responsif.

## 2. Cara menjalankan di XAMPP

1. Pastikan **Apache** dan **MySQL** di XAMPP sudah aktif.
2. Salin folder `ALYRA_FASHION` ke `C:\xampp\htdocs\`.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih menu **Import**, pilih file `database.sql`, lalu jalankan.
5. Buka `http://localhost/ALYRA_FASHION/`.

Konfigurasi database ada di `config.php`. Default XAMPP biasanya:
- Host: `127.0.0.1`
- Database: `alyra_fashion`
- User: `root`
- Password: kosong

## 3. Struktur project

```text
ALYRA_FASHION/
├── index.php       # Read / daftar produk
├── create.php      # Create
├── edit.php        # Update
├── delete.php      # Delete via POST + CSRF
├── config.php      # Koneksi PDO MySQL
├── functions.php   # Helper, CSRF, validasi, escaping
├── database.sql    # Database + tabel + data contoh
├── demo.php        # Checklist demo + refleksi
├── README.md
└── assets/
    └── style.css
```

## 4. Checklist demo

Checklist juga tersedia langsung di halaman `demo.php` melalui tombol **Checklist Demo** pada halaman utama.

| Skenario | Hasil yang diharapkan |
|---|---|
| Tambah produk valid | Muncul di daftar |
| Nama < 3 karakter | Ditolak; tidak tersimpan |
| Harga negatif / nol | Ditolak; pesan jelas |
| Stok negatif | Ditolak; pesan jelas |
| Nama sama | Ditolak; nama harus unik |
| Refresh setelah create | Tidak ada duplikasi |
| Edit berdasarkan ID | Data berubah sesuai ID |
| Delete GET | Ditolak; delete hanya POST |
| Delete POST tanpa CSRF | Ditolak |
| Nama berisi `<b>Promo</b>` | Tampil sebagai teks, bukan HTML |
| Layar sempit | Card membungkus/tersusun rapi |

## 5. Kontrol keamanan

1. **Prepared statement:** seluruh operasi SQL yang menerima input menggunakan PDO prepared statement untuk mengurangi risiko SQL Injection.
2. **CSRF:** operasi DELETE menggunakan token CSRF dan hanya menerima POST.
3. **XSS protection:** output pengguna di-escape menggunakan `htmlspecialchars()`.
4. **Server-side validation:** aturan input diperiksa di server, bukan hanya mengandalkan validasi HTML.
5. **Database constraint:** kolom `name` diberi UNIQUE sebagai pertahanan tambahan dari data ganda.
6. **PRG (Post/Redirect/Get):** setelah Create/Update/Delete berhasil, aplikasi melakukan redirect ke daftar sehingga refresh tidak mengulang POST.

## 6. Refleksi

Bagian yang paling rentan adalah **input, query, output, dan alur request**. Karena itu aplikasi menerapkan validasi server untuk input, prepared statement untuk query, `htmlspecialchars()` untuk output, serta POST + CSRF untuk request penghapusan. Dengan kombinasi kontrol tersebut, aplikasi tidak hanya menangani kondisi normal tetapi juga memiliki perilaku yang jelas ketika menerima input buruk.
