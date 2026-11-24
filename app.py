from flask import Flask, request, jsonify
import joblib
import pandas as pd
import re
import requests
from bs4 import BeautifulSoup
import urllib3

# Menonaktifkan peringatan SSL jika URL tidak punya sertifikat aman
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

app = Flask(__name__)
model = joblib.load('model_phishing_235k.pkl')

fitur_kolom = ['URLLength', 'DomainLength', 'IsDomainIP', 'URLSimilarityIndex', 'CharContinuationRate', 'TLDLegitimateProb', 'URLCharProb', 'TLDLength', 'NoOfSubDomain', 'HasObfuscation', 'NoOfObfuscatedChar', 'ObfuscationRatio', 'NoOfLettersInURL', 'LetterRatioInURL', 'NoOfDegitsInURL', 'DegitRatioInURL', 'NoOfEqualsInURL', 'NoOfQMarkInURL', 'NoOfAmpersandInURL', 'NoOfOtherSpecialCharsInURL', 'SpacialCharRatioInURL', 'IsHTTPS', 'LineOfCode', 'LargestLineLength', 'HasTitle', 'DomainTitleMatchScore', 'URLTitleMatchScore', 'HasFavicon', 'Robots', 'IsResponsive', 'NoOfURLRedirect', 'NoOfSelfRedirect', 'HasDescription', 'NoOfPopup', 'NoOfiFrame', 'HasExternalFormSubmit', 'HasSocialNet', 'HasSubmitButton', 'HasHiddenFields', 'HasPasswordField', 'Bank', 'Pay', 'Crypto', 'HasCopyrightInfo', 'NoOfImage', 'NoOfCSS', 'NoOfJS', 'NoOfSelfRef', 'NoOfEmptyRef', 'NoOfExternalRef']

def ekstrak_fitur_url(url):
    fitur = []
    for col in fitur_kolom:
        if col == 'URLLength': fitur.append(len(url))
        elif col == 'IsHTTPS': fitur.append(1 if 'https' in url else 0)
        elif col == 'NoOfDegitsInURL': fitur.append(sum(c.isdigit() for c in url))
        elif col == 'NoOfLettersInURL': fitur.append(sum(c.isalpha() for c in url))
        elif col == 'NoOfEqualsInURL': fitur.append(url.count('='))
        elif col == 'NoOfQMarkInURL': fitur.append(url.count('?'))
        elif col == 'NoOfAmpersandInURL': fitur.append(url.count('&'))
        elif col == 'IsDomainIP': fitur.append(1 if re.match(r'\d+\.\d+\.\d+\.\d+', url) else 0)
        elif col == 'NoOfSubDomain': fitur.append(url.count('.'))
        else: fitur.append(0)
    return fitur

@app.route('/api/scan', methods=['POST'])
def scan():
    data = request.json
    target = data.get('target', '')
    tipe = data.get('tipe', '')

    if not target:
        return jsonify({'error': 'Target kosong'}), 400

    try:
        if tipe == 'phishing':
            # 1. Pastikan URL punya format http:// atau https://
            if not target.startswith('http://') and not target.startswith('https://'):
                target = 'https://' + target

            alasan = []
            is_phishing = False
            ada_password = False

            # 2. LIVE SCANNING: Buka URL-nya beneran!
            try:
                headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'}
                
                try:
                    # COBA PERTAMA: Cepat (5 detik) untuk website ringan
                    response = requests.get(target, timeout=5, verify=False, allow_redirects=True, headers=headers)
                except requests.exceptions.Timeout:
                    # COBA KEDUA: Jika waktu habis, coba lagi dengan waktu lebih lama (20 detik) untuk website berat
                    response = requests.get(target, timeout=20, verify=False, allow_redirects=True, headers=headers)

                html_konten = response.text
                status_code = response.status_code

                # 3. Cek isi HTML
                soup = BeautifulSoup(html_konten, 'html.parser')
                judul_halaman = soup.title.string if soup.title else "Tidak ada judul"
                teks_halaman = soup.get_text().lower()

                # --- DETEKSI HALAMAN TIDAK DITEMUKAN (404 / BROKEN LINK) ---
                # Kadang server membalas dengan status 200, tapi isinya tulisan "Not Found"
                is_404_page = (
                    status_code == 404 or 
                    "404" in judul_halaman or 
                    "not found" in judul_halaman.lower() or 
                    "page not found" in teks_halaman or
                    "halaman tidak ditemukan" in teks_halaman
                )

                if is_404_page:
                    return jsonify({
                        'hasil': 'URL TIDAK DITEMUKAN (Aman)', 
                        'alasan': [
                            f"Halaman tidak ditemukan (Status: {status_code}).",
                            "Meskipun domainnya asli (seperti McAfee/Google), menambahkan teks acak di akhir URL akan menghasilkan halaman error.",
                            "Ini menandakan URL dimodifikasi secara manual atau salah ketik, bukan merupakan link resmi yang aktif."
                        ]
                    })

                # 4. Cek form login (password)
                ada_password = soup.find('input', {'type': 'password'})
                
                if ada_password:
                    if not target.startswith('https://'):
                        is_phishing = True
                        alasan.append("Halaman meminta kata sandi (Password) tetapi TIDAK menggunakan koneksi aman (HTTPS).")
                    elif len(target) > 50 or '-' in target.split('/')[2]: 
                        is_phishing = True
                        alasan.append("Pola URL tidak wajar (terlalu panjang/ada tanda hubung) untuk sebuah website resmi.")
                    else:
                        alasan.append("Website memiliki form login, namun menggunakan HTTPS yang aman.")
                
                # Cek kata kunci phishing
                kata_kunci_bahaya = ['verifikasi akun', 'rekening anda diblokir', 'menang undian', 'masukkan pin', 'login bca', 'update data']
                found_keywords = [k for k in kata_kunci_bahaya if k in teks_halaman]
                if found_keywords:
                    is_phishing = True
                    alasan.append(f"Terdapat teks mencurigakan di halaman: {', '.join(found_keywords)}.")

            except requests.exceptions.RequestException:
                # Jika domain benar-benar mati / tidak bisa dihubungi
                return jsonify({'hasil': 'URL TIDAK DAPAT DIAKSES (Aman)', 'alasan': ['Domain tidak aktif, diblokir, atau koneksi internet lambat (Koneksi Gagal).']})

            # 5. Cek pakai AI (Model Random Forest)
            fitur = ekstrak_fitur_url(target)
            df_input = pd.DataFrame([fitur], columns=fitur_kolom)
            hasil_prediksi = model.predict(df_input)[0]

            # Jika Live Scanner bilang aman (tidak ada form password & tidak ada teks bahaya)
            if not ada_password and not is_phishing:
                hasil_prediksi = 1 

            if is_phishing or hasil_prediksi == 0:
                hasil_text = "URL BERBAHAYA (Phishing!)"
                if not alasan:
                    alasan.append("Pola struktur URL sangat mirip dengan dataset Phishing berdasarkan model AI.")
            else:
                hasil_text = "URL AMAN (Legitimate)"
                alasan.append(f"Halaman berhasil dibuka (Status: {status_code}). Judul: '{judul_halaman}'.")
                alasan.append("Tidak ditemukan form login palsu atau teks penipuan.")
                
        elif tipe == 'trojan':
            ekstensi_berbahaya = ['.exe', '.bat', '.vbs', '.js', '.scr', '.cmd', '.ps1']
            if any(target.endswith(ext) for ext in ekstensi_berbahaya):
                hasil_text = "TERINDIKASI TROJAN/MALWARE"
                alasan = [f"File berekstensi {target.split('.')[-1]} sangat berisiko mengeksekusi kode berbahaya."]
            else:
                hasil_text = "File Terlihat Aman"
                alasan = ["Ekstensi file aman."]
                
        return jsonify({'hasil': hasil_text, 'alasan': alasan})
        
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True, port=5000)