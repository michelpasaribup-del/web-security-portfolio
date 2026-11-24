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
<img width="1920" height="1026" alt="Screenshot 2026-11-24 192608" src="https://github.com/user-attachments/assets/00586a79-e7c9-4d87-b698-a5d7a3836fdf" />
<img width="1920" height="1031" alt="Screenshot 2026-11-24 192508" src="https://github.com/user-attachments/assets/9e1498b6-fa21-469c-ae0c-feab4daffef9" />
<img width="1920" height="1036" alt="Screenshot 2026-11-24 192410" src="https://github.com/user-attachments/assets/b34112b6-1cbb-4d61-82b9-53c8d90ec69b" />
<img width="1920" height="1037" alt="Screenshot 2026-11-24 192242" src="https://github.com/user-attachments/assets/6d0ff964-92a5-4164-9c77-bc9ffca943bc" />
<img width="1920" height="1034" alt="Screenshot 2026-11-24 192045" src="https://github.com/user-attachments/assets/fe436c13-4abb-4657-9c3e-09149989d8ce" />


## 📁 Struktur Folder
* `/aplikasi` - Kode utama website Laravel (Controller, Model).
* `/sumber daya` - Kode tampilan (Blade/HTML/CSS).
* `/rute` - Pengaturan URL (routes).

## ⚙️ Cara Menjalankan
1. Jalankan API Python: `python app.py`
2. Jalankan Laravel: `php artisan serve`
3. Buka `http://127.0.0.1:8000`
