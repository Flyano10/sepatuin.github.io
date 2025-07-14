# Sepatuin

**Sepatuin** adalah web e-commerce sepatu modern berbasis Laravel, lengkap untuk portfolio dan siap produksi.

## Fitur Utama
- Manajemen produk, kategori, dan stok per size (35–45)
- Galeri multi gambar per produk
- Keranjang belanja dengan pilihan size
- Checkout & simulasi pembayaran
- Wishlist, review & rating produk
- Dashboard admin (statistik, produk terlaris)
- Manajemen order (update status, detail order, invoice)
- Notifikasi email status order
- Kompres gambar otomatis
- SEO: meta tag, sitemap.xml, robots.txt
- Mobile friendly & UX modern

## Cara Install
1. Clone repo:
   ```
   git clone https://github.com/Flyano10/sepatuin.github.io.git
   cd sepatuin.github.io
   ```
2. Copy file env:
   ```
   cp .env.example .env
   ```
3. Atur database di file `.env`
4. Install dependency:
   ```
   composer install
   npm install && npm run build
   ```
5. Generate key & migrate database:
   ```
   php artisan key:generate
   php artisan migrate --seed
   ```
6. Jalankan server:
   ```
   php artisan serve
   ```

## Akun Demo
- Admin: admin@mail.com / password
- User: user@mail.com / password

## Fitur Lanjutan
- Export data order/user (admin)
- Testing & dokumentasi
- Optimasi gambar & SEO
- Invoice/print order

## Kontribusi
Pull request & issue sangat diterima!

## Lisensi
MIT
