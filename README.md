# hsdm software

2.5.3	21/06/2022
Modify: Framework Updated
MOdify: Filemanager: Generate image filename

2.5.2	23/04/2022
Bug Fixed: Editing allowance can not be saved.

2.5.1	31/03/2022
Bug Fixed: Error save presence/presence if no modify permission.
Bug Fixed: New Employee not saved.
Bug Fixed: Config SSL not set on store_id = 0.
Modify: Schedule > Print: Need access role only to print schedule
Modify: Cutoff: Modify some language.
Bug Fixed: Finger Device list if no results
Modify: Allowance: Add components option in Setting for allowance calculation
Refactoring: Setting

2.5.0	03/01/2022
Modul: Report - User Activity
	# Create Table
		CREATE TABLE `oc_user_activity` (
		`user_activity_id` int(11) NOT NULL,
		`user_id` int(11) NOT NULL,
		`key` varchar(64) NOT NULL,
		`data` text NOT NULL,
		`ip` varchar(40) NOT NULL,
		`date_added` datetime NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;


		ALTER TABLE `oc_user_activity`
		ADD PRIMARY KEY (`user_activity_id`);


		ALTER TABLE `oc_user_activity`
		MODIFY `user_activity_id` int(11) NOT NULL AUTO_INCREMENT;
	# End Create Table

Bug Fixed: Password Reset not work.

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