# sdm
hasian sdm software

2.4.1	30/12/2021
Modul: CATALOG Presence > Login: Redirect Home after 1 minute idle.
Modul: Location > List: Add Clear Location Token feature, to unlink from all device.
Bug Fixed: Presence > Login: Cookies if location token is empty.
Bug Fixed: Customer > Customer: Undefined index: health_insurance, life_insurance, employment_insurance.
Bug Fixed: CATALOG Presence > Login: date() expects parameter 2 to be int, string given.
Bug Fixed: Presence > Presence: in_array() expects parameter 2 to be array, null given.

2.4.0	28/12/2021
Modul: Dashboard - Login Session
	Setting: Pengaturan batas waktu session
	User Group: Pengaturan akses login session pada user
	User: Penambahan user security

Pemasangan SSL Connection
CATALOG > Presence - Login > Layout absensi
Bug Fixed: Presence - Schedule > Perhitungan kehadiran pada Data Log jika jadwal tidak diset
Bug Fixed: Employee - Finger - Verification > Datetime tidak berfungsi.

2.3.10	28/12/2021
Fatal Error: Absensi finger tidak terdaftar karena struktur headers server berubah.

2.3.9	20/12/2021
Fatal Error: Absensi finger tidak terdaftar.
Bug Fixed: Forgotten Password

2.3.8	18/9/2021
Bug Fixed: Karyawan baru, Jadwal Off ikut mengurangi nilai NS sehingga HKE < 25.

2.3.7	11/9/2021
Modul: Schedule Type > Menambah kolom 'Currently Use' jika schedule type digunakan pada periode berjalan.
Bug Fixed: Tukar Off tercatat A, jika sudah mencoba login sebelum input Tukar Off.
Bug Fixed: NS terhitung 20, seharusnya 25.
Bug Fixed: Tukar Off beda periode terhitung A.
Bug Fixed: Report - Employee > Jika tanggal lahir belum di-set, tampil tanggal 1 Jan 1970.

2.3.6	7/9/2021
Bug Fixed: Tanggal masuk karyawan saat reaktivasi, tidak tercatat.

2.3.4
Bug Fixed: Finger log harus menggunakan waktu server.