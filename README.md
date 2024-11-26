<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## **Teknologi yang Digunakan**
- **Backend**: Laravel 11
- **Frontend**: Blade template dengan TailwindCSS
- **Database**: SQLite (dapat diganti dengan MySQL atau lainnya sesuai kebutuhan)

## **Instalasi**
### **Prasyarat**
Pastikan Anda memiliki hal-hal berikut sebelum memulai:
1. PHP >= 8.2
2. Composer
3. Node.js dan NPM
4. SQLite/MySQL

### **Langkah-langkah**
1. Clone repositori ini:
   ```bash
   git clone https://github.com/farelyudapratama/stockup.git
   ```
2. Masuk ke direktori proyek:
   ```bash
   cd stockup
   ```
3. Instal dependensi Laravel:
   ```bash
   composer install
   ```
4. Instal dependensi frontend:
   ```bash
   npm install
   ```
5. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
6. Buat kunci aplikasi:
   ```bash
   php artisan key:generate
   ```
7. Konfigurasikan database di file `.env`:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=/path/to/database/database.sqlite
   ```
   <p style="color: yellow;">(Jangan lupa buat atau tambahkan file .sqlite anda di folder dabatase)</p>

8. Jalankan migrasi dan seeder untuk menyiapkan tabel database:
   ```bash
   php artisan migrate --seed
   ```
9. Jalankan server pengembangan:
   ```bash
   php artisan serve
   ```
10. (Opsional) Compile aset frontend:
    ```bash
    npm run dev
    ```