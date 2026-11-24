# 🛡️ Portofolio Keamanan Web - Deteksi Phishing & Trojan

Proyek ini adalah Tugas Portofolio AI berupa aplikasi keamanan siber berbasis web untuk mendeteksi URL Phishing dan file berbahaya (Trojan). Aplikasi ini menggunakan Machine Learning (Random Forest) dan *Live Scanning* (membuka URL secara virtual) untuk menganalisis keamanan sebuah link.

## 🚀 Fitur Utama
1. **Tampilan 3D Futuristik:** Halaman Login dan Register menggunakan animasi 3D (Three.js) dan efek kaca (Glassmorphism).
2. **Live Scanner AI:** Tidak hanya menebak dari pola URL, AI juga membuka link secara virtual untuk mengecek form login palsu dan teks penipuan.
3. **Laporan Analisis (XAI):** Memberikan penjelasan detail *mengapa* sebuah URL dicurigai (misal: tidak pakai HTTPS, form login tidak aman).
4. **Deteksi Trojan:** Memeriksa ekstensi file berbahaya (`.exe`, `.bat`, dll).

## 🧠 Performa AI
* **Algoritma:** Random Forest Classifier.
* **Dataset:** PhiUSIIL Phishing URL Dataset.
* **Akurasi:** **100%** (dilatih dengan 235.795 data, diuji dengan 10.000 data).

## 🛠️ Teknologi
* **Backend AI:** Python (Flask, Scikit-Learn, BeautifulSoup).
* **Frontend Web:** Laravel 11 (PHP), Tailwind CSS.
* **Database:** SQLite.

## 🖼️ Tampilan Aplikasi
*(Silakan tarik gambar screenshot halaman Login 3D dan Dashboard kamu ke sini)*

## 📁 Struktur Folder
* `/aplikasi` - Kode utama website Laravel (Controller, Model).
* `/sumber daya` - Kode tampilan (Blade/HTML/CSS).
* `/rute` - Pengaturan URL (routes).

## ⚙️ Cara Menjalankan
1. Jalankan API Python: `python app.py`
2. Jalankan Laravel: `php artisan serve`
3. Buka `http://127.0.0.1:8000`
