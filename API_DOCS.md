# API Documentation

Seluruh endpoint berada di bawah prefix `/api` dan (kecuali register & login) membutuhkan autentikasi Sanctum.

---

## 1. Register
- **URL:** `/api/register`
- **Method:** `POST`
- **Body (JSON):**
  ```json
  {
    "name": "Nama Lengkap",
    "email": "email@domain.com",
    "password": "password",
    "password_confirmation": "password"
  }
  ```
- **Response:**
  - 201 Created, data user baru

---

## 2. Login
- **URL:** `/api/login`
- **Method:** `POST`
- **Body (JSON):**
  ```json
  {
    "email": "email@domain.com",
    "password": "password"
  }
  ```
- **Response:**
  - 200 OK, token autentikasi

---

## 3. Logout
- **URL:** `/api/logout`
- **Method:** `POST`
- **Headers:**
  - `Authorization: Bearer {token}`
- **Response:**
  - 200 OK, pesan logout sukses

---

## 4. Get Authenticated User
- **URL:** `/api/user`
- **Method:** `GET`
- **Headers:**
  - `Authorization: Bearer {token}`
- **Response:**
  - 200 OK, data user yang sedang login

---

## 5. Get All Penduduk (Summary per Desa)
- **URL:** `/api/penduduk/all`
- **Method:** `GET`
- **Headers:**
  - `Authorization: Bearer {token}`
- **Response:**
  - 200 OK, summary statistik per desa:

```json
{
  "status": "success",
  "message": "Summary penduduk dari semua desa berhasil diambil",
  "data": {
    "dero": {
      "total_penduduk": 1234,
      "jumlah_dusun": 5,
      "jumlah_keluarga": 456,
      "jumlah_surat": 789,
      "jumlah_kelompok": 12,
      "jumlah_rtm": 100,
      "jumlah_program": 2,
      "jumlah_layanan_mandiri": 8
    },
    "sumberbening": {
      "total_penduduk": 1000,
      "jumlah_dusun": 4,
      "jumlah_keluarga": 300,
      "jumlah_surat": 500,
      "jumlah_kelompok": 10,
      "jumlah_rtm": 80,
      "jumlah_program": 1,
      "jumlah_layanan_mandiri": 5
    }
  }
}
```

**Penjelasan Field:**
- `total_penduduk`: Jumlah penduduk aktif (status_dasar = 1)
- `jumlah_dusun`: Jumlah dusun unik
- `jumlah_keluarga`: Jumlah keluarga aktif (kepala keluarga aktif)
- `jumlah_surat`: Jumlah surat (log_surat)
- `jumlah_kelompok`: Jumlah kelompok (tabel kelompok)
- `jumlah_rtm`: Jumlah rumah tangga (RTM) aktif (kepala keluarga aktif dan rtm_level = 1)
- `jumlah_program`: Jumlah jenis sasaran unik pada program bantuan (tabel program, kolom sasaran)
- `jumlah_layanan_mandiri`: Jumlah akun layanan mandiri aktif (tweb_penduduk_mandiri.aktif = 1)

---

## 6. Get Clusters Detailed
- **URL:** `/api/penduduk/clusters/detailed`
- **Method:** `GET`
- **Query Params:**
  - `desa` (wajib): nama desa (`sumberbening`, `dero`, `rejuno`, dst)
  - `dusun` (opsional): filter nama dusun
  - `rt` (opsional): filter RT
  - `rw` (opsional): filter RW
- **Headers:**
  - `Authorization: Bearer {token}`
- **Response:**
  - 200 OK, data cluster detail dan summary

---

## 7. Get Penduduk List per Desa
- **URL:** `/api/penduduk/{desa}`
- **Method:** `GET`
- **Query Params:**
  - `per_page` (opsional): jumlah data per halaman
  - `page` (opsional): halaman ke berapa
  - `search` (opsional): pencarian nama/nik
- **Headers:**
  - `Authorization: Bearer {token}`
- **Response:**
  - 200 OK, daftar penduduk per desa

---

## 8. Store Penduduk Baru per Desa
- **URL:** `/api/penduduk/{desa}`
- **Method:** `POST`
- **Body (JSON):**
  (Sesuai validasi di controller, misal: `nama`, `nik`, `sex`, dst)
- **Headers:**
  - `Authorization: Bearer {token}`
- **Response:**
  - 201 Created, data penduduk baru

---

## 9. Show Penduduk by ID
- **URL:** `/api/penduduk/{desa}/{id}`
- **Method:** `GET`
- **Headers:**
  - `Authorization: Bearer {token}`
- **Response:**
  - 200 OK, detail penduduk

---

## 10. Update Penduduk by ID
- **URL:** `/api/penduduk/{desa}/{id}`
- **Method:** `PUT` atau `PATCH`
- **Body (JSON):**
  (Field yang ingin diupdate)
- **Headers:**
  - `Authorization: Bearer {token}`
- **Response:**
  - 200 OK, data penduduk setelah update

---

## 11. Delete Penduduk by ID
- **URL:** `/api/penduduk/{desa}/{id}`
- **Method:** `DELETE`
- **Headers:**
  - `Authorization: Bearer {token}`
- **Response:**
  - 200 OK, pesan sukses hapus

---

## 12. Get Penduduk by NIK
- **URL:** `/api/penduduk/{desa}/nik/{nik}`
- **Method:** `GET`
- **Headers:**
  - `Authorization: Bearer {token}`
- **Response:**
  - 200 OK, data penduduk dengan NIK tersebut

---

## Catatan
- Semua endpoint (kecuali register & login) membutuhkan header:
  - `Authorization: Bearer {token}`
- Untuk endpoint yang menerima `{desa}`, gunakan nama desa sesuai model yang tersedia (`sumberbening`, `dero`, `rejuno`, dst).
- Untuk endpoint yang menerima query string (misal: filter), tambahkan di URL, contoh:
  `/api/penduduk/clusters/detailed?desa=sumberbening&dusun=NamaDusun&rt=1&rw=2` 