# PurityFlow 💧 - Sistem Monitoring Kualitas Air Berbasis IoT

**PurityFlow** adalah sistem IoT berbasis ESP32 yang dirancang untuk memantau kualitas air secara real-time pada depot isi ulang air mineral. Sistem ini mengukur nilai TDS (Total Dissolved Solids) untuk memastikan air layak dikonsumsi, serta menyediakan fitur pemantauan melalui web server dan pembaruan firmware Over-The-Air (OTA).

---

## 🧰 Fitur Utama

- 🔬 **Monitoring TDS**: Menggunakan sensor TDS untuk membaca kualitas air.
- 📶 **Web Server ESP32**: Tampilkan data sensor langsung melalui jaringan WiFi lokal.
- 🚨 **Alarm Otomatis**: Buzzer akan berbunyi jika nilai TDS melebihi ambang batas.
- 🌐 **OTA Update**: Pembaruan firmware ESP32 langsung lewat jaringan (tanpa kabel).
- 📝 **EEPROM Support**: Menyimpan konfigurasi secara permanen (seperti WiFi dan batas TDS).

---

## 📷 Tampilan Sistem

> Tambahkan di sini screenshot dari tampilan web server (jika ada) dan foto alat (opsional).

---

## 🧱 Arsitektur Sistem

```

+--------------------+
\|    Sensor TDS      |───> Mengukur kualitas air
+--------------------+
│
▼
+--------------------+
\|      ESP32         |───> Proses data, tampilkan via Web UI, aktifkan alarm
+--------------------+
│
OTA Firmware & Web UI
│
▼
+--------------------+
\|     Web Server     |───> Akses dari browser lokal
+--------------------+

````

---

## ⚙️ Perangkat Keras (Hardware)

- ESP32 (DevKit v1 atau sejenis)
- Sensor TDS Gravity
- Buzzer (Alarm)
- LED (Opsional)
- Breadboard / PCB
- Power Supply 5V
- Koneksi WiFi

---

## 🧪 Kalibrasi & Ambang Batas

- Ambang batas kualitas air: `TDS ≤ 100 ppm`
- Nilai ini bisa diubah dalam kode atau melalui EEPROM jika sudah diimplementasikan dalam konfigurasi dinamis.

---

## 🚀 Cara Instalasi & Upload

1. **Clone Repo**
    ```bash
    git clone https://github.com/sigit3329/PurityFlow.git
    cd PurityFlow
    ```

2. **Buka File di Arduino IDE / PlatformIO**

3. **Install Library yang Dibutuhkan**
    - `WiFi.h`
    - `ESPAsyncWebServer.h`
    - `AsyncTCP.h`
    - `EEPROM.h`
    - `Update.h`
    - `GravityTDS` (jika belum ada, cari secara manual)

4. **Upload Program ke ESP32**

5. **Akses Web Server**
    - Hubungkan ESP32 ke jaringan WiFi
    - Catat alamat IP di serial monitor
    - Akses via browser: `http://<alamat-ip-esp32>`

---

## 🖥️ Struktur Proyek

````

PurityFlow/
├── data/                    # File HTML, CSS, JS untuk Web UI (jika pakai SPIFFS)
├── src/
│   └── main.ino             # Program utama ESP32
├── platformio.ini           # Konfigurasi untuk PlatformIO
├── README.md                # Dokumentasi proyek ini

````

---

## 🔐 Keamanan OTA

Untuk OTA update, pastikan Anda menggunakan autentikasi atau jaringan lokal yang aman. Fitur ini sangat powerful tapi harus dikontrol.

---

## 📄 Lisensi

Proyek ini berada di bawah lisensi [MIT License](LICENSE).

---

## 🤝 Kontribusi

Kontribusi sangat terbuka! Silakan buat pull request atau buka issue jika Anda punya saran, bug, atau fitur tambahan.

---

## 👤 Author

**Muhammad Sigit Nugroho**  
📧 [Email Anda]  
🔗 [LinkedIn atau Website opsional]  

---

## ❤️ Terima Kasih

Terima kasih telah menggunakan dan mengembangkan sistem monitoring kualitas air yang lebih baik. Mari jaga kesehatan dan air bersih bersama!