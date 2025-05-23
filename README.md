# hsdm software

2.x.0-a
MODUL: APP > Account: Login by location (Trial & Progress)
MODUL: Customer > Presence Method


User Group: ganti form ke list, trus ada kolom untuk centang access, modify, approval, print, bypass



MODIFY CONFIG

=== TABLE ===

4.1.1	12/04/2025
Report > Payroll Tax: Restructure
Absence: Employee Selection Availability (month) aktif pada pemilihan karyawan
Bug Fixed: City: Add/Edit error.
Bug Fixed: Schedule: Filter name with quote (') not work properly

=== TABLE ===
ALTER TABLE oc_city MODIFY COLUMN city int(11) auto_increment NOT NULL;

4.1.0c	19/02/2025
Bug Fixed: Schedule: Pagination link not work properly

4.1.0b	18/02/2025
Bug Fixed: Schedule: Filter name not work properly

4.1.0	18/02/2025
Fund Account: Seleksi bank berdasarkan Payroll Method
Schedule: Menambah fitur ekspor data log kehadiran.
Payroll Basic: Menambah sistem approval jika ada perubahan gaji
Schedule > Delete: Schedule yang belum lewat untuk periode berjalan (Status Processing) bisa didelete jika jadwal tidak dikunci.
Schedule > Import: Schedule yang belum lewat untuk periode berjalan (Status Processing) bisa diimport jika jadwal tidak dikunci.
Schedule: Restructure
User Group: Add Approval Permission
User Group > List: Add users column
Setting: Add payroll basic auto approve
Payroll Method > Form: Menambah field code

FILE
Schedule Log.xlsx

MODIFY CONFIG
Payroll Basic Auto Approve: set to always

MODIFY USER
User access for Payroll Basic Approval

MODIFY TABLE
ALTER TABLE oc_payroll_basic ADD date_approved DATETIME NULL;
ALTER TABLE oc_payroll_basic ADD approval_user_id INT(11) NULL;
UPDATE oc_payroll_basic SET date_approved=date_added, approval_user_id=user_id WHERE 1;
ALTER TABLE oc_fund_account ADD payroll_method_id int(11) NOT NULL AFTER fund_account_id;
ALTER TABLE oc_fund_account DROP COLUMN bank_name;

====================
HSDM SOFTWARE 4.1.0 UPDATE

+ User Group: Tambahan role "APPROVAL". Saat ini sistem approval masih hanya diterapkan pada modul Payroll Basic.
+ Payroll Basic: Sistem Approval komponen gaji.
  Cakupan diatur pada menu Setting > Option > Payroll Basic:
	Auto Approve atas input gaji.
		- Never: Tidak ada auto approve.
		- First Time Only: Auto approve pada pengaturan gaji karyawan pertama kali.
		- Always: Selalu auto approve, manual approve tidak dibutuhkan.
+ Schedule > Delete: Schedule untuk tanggal yang belum lewat pada periode berjalan (Status Processing) bisa didelete jika jadwal tidak dikunci.
+ Schedule > Import: Schedule untuk tanggal yang belum lewat pada periode berjalan (Status Processing) bisa diimport jika jadwal tidak dikunci.
+ Schedule: Tambahan fitur ekspor Data Log Kehadiran.

====================


4.0.5	17/01/2025
Framework Updated

4.0.4c	03/12/2024
Bug Fixed: Schedule > Import: Importing not working.

4.0.4b	26/11/2024
APP > Login: Presence Layout bug fixed.

4.0.4	25/11/2024
Payroll type utk DSP sales.
Release > Export CSV: Exclude data jika THP <= 0.
Free Transfer: Proteksi penamaan file dan value csv saat export.
Release: Perbaikan metode 'proteksi complete' dengan membatasi jumlah periode yg aktif.
Release: Data cuti tidak akan diarsipkan saat Archiving data (set complete)
Schedule: Save uploaded file to upload folder when import schedule

4.0.3b	28/08/2024
Bug Fixed: Schedule: NS tidak terhitung dengan benar jika di data kehadiran sudah ada status ns.
Bug Fixed: Schedule: Ringkasan kehadiran tidak tampil jika tidak punya $presence_summary['additional']

4.0.2	22/08/2024
<!-- Release > Form: Set multiple fund source -->
Payroll Method: Add Bank Mandiri
Release: Release untuk multiple bank.
Bug Fixed: Customer > List: Filter payroll include error.

MODIFY TABLE
ALTER TABLE oc_payroll_method ADD code varchar(16) NOT NULL AFTER payroll_method_id;
ALTER TABLE oc_payroll ADD release_payroll_method_id int(11) NULL AFTER status_released;
ALTER TABLE oc_payroll ADD release_acc_no TINYTEXT NULL AFTER release_payroll_method_id;
UPDATE oc_payroll p, oc_customer c SET p.release_acc_no = c.acc_no, p.release_payroll_method_id = c.payroll_method_id WHERE p.customer_id = c.customer_id AND p.date_released IS NOT NULL;

====================
HSDM SOFTWARE 4.0.2 UPDATE

+ PAYROLL METHOD: Menambah Bank Mandiri untuk release gaji
+ RELEASE: Multiple Bank
====================

4.0.1	03/08/2024
APP > Account: New Notification
Bug Fixed: APP > Schedule: Tanggal belum berlalu, kehadiran menunjukkan off.
Bug Fixed: APP > Schedule: Late Tolerance tidak teraplikasikan ke data kehadiran.

MODIFY DATA
Presence Status: Ganti nama --- menjadi Off

4.0.0	01/08/2024
NEW MODUL: Report Payroll: Termasuk group report berdasarkan jabatan, divisi, lokasi.
Payroll > Export: Bisa dilakukan jika status = generated, approved, released. (sebelumnya hanya status = generated)
Bug Fixed: Header Menu: Riwayat Cuti tidak ada 
APP > Login: Karyawan yang tidak aktif tidak muncul lagi pada halaman login
Schedule: Menentukan status NS jika sudah diset jadwal sblmnya
Schedule: Jika tanggal belum berlalu, kehadiran tidak menunjukkan off.
Customer: Login by Customer only by superuser

MODIFY DATA
Payroll Setting: Status dgn pemberitahuan, aktifkan S
Late Tolerance: Sesuaikan Schedule Type
Update user permission yg sesuai, terutama utk modul Report Payroll
Presence Status: Ganti nama --- menjadi Off (Pending sampai bug solve)
Presence Status: Ganti nama Not Staffed dari Belum/Tidak Aktif menjadi Tidak Aktif
Presence Status: Tambah kode ck
Presence Status: Disable S+, DRM
Payroll Type: Sesuaikan perhitungan payroll

====================
HSDM SOFTWARE 4.0.0 UPDATE

+ PAYROLL TYPE: Metode perhitungan pendapatan dan potongan bisa diatur berbeda untuk setiap karyawan (Juga bisa digunakan untuk karyawan harian)
+ Employee: Pengaturan Perhitungan Penggajian (Payroll Type) diset di modul Employee.
+ Presence: Kehadiran karyawan sudah bisa di-sort berdasarkan status kehadiran utama (h, s, i...)
+ Schedule: Summarize (tanpa centang) kini menyesuaikan dgn filter yang sedang digunakan. (Sebelumnya otomatis untuk seluruh karyawan)
+ Fitur Toleransi Keterlambatan. Sehingga tidak perlu diatur pada jadwal.
+ Presence, Payroll: Nilai HKE yang ditunjukkan adalah total HK keseluruhan: Total hadir + total tidak hadir (termasuk C dan CK). Sebelumnya C dan CK tidak termasuk dalam HKE
+ Karyawan bisa melihat data jadwal dan kehadiran bulan berjalan dan bulan lalu. (Sebelumnya hanya bulan berjalan)
+ Employee: Data Premi 12 Jam dan Skip Masa Percobaan dihapus.
+ NEW MODUL: Report > Payrolls > Payrolls: Report data penggajian termasuk group report berdasarkan jabatan, divisi, lokasi.
+ Payroll > Export: Export data penggajian bisa dilakukan jika status = generated, approved, released. (sebelumnya hanya status = generated)
====================

4.0.a	02/07/2024
Presence Status: Add field status.
NEW MODUL: Payroll Type
Customer: Menerapkan payroll_type ke modul customer. Tambahan filter payroll_type dan payroll include.
Payroll, Payroll Release, APP > Payroll: Menyesuaikan perhitungan gaji sehingga menggunakan Payroll Type
Customer: Remove $customer_skip_trial_status (Masa Percobaan)
Customer: Remove $customer_full_overtime_status (Premi 12 Jam)
Remove DRM/Day Off Component
HKE: Total hadir + total tidak hadir (termasuk C dan CK)
Schedule: Summarize tanpa centang untuk menghitung seluruh karyawan sesuai filter
Presence: Kehadiran karyawan sekarang bisa di-sort berdasarkan status kehadiran utama (h, s, i...)
APP > Schedule: Add last period schedule
Setting: Add Late Tolerance

Bug Fixed: Release: release info hilang jika filter nama lengkap aktif
Schedule: Repair Calculation
Presence: Repair Calculation
Presence Status: Remove Code
Bug Fixed: Dashboard > Customer: Karyawan bulan lalu selalu 0%

3.1.5b	20/06/2024
Bug Fixed: Schedule: File template

3.1.5a	19/06/2024
Some Bug Fixed

3.1.5	18/06/2024
Customer Info: Menambah info contract
Contract: Modify date_start by super user
Contract Type: Add Resign Contract Type
Contract, Customer, Presence: Menambah filter Contract Type. Improve List


3.1.4	17/05/2024
Free Transfer: Pemilihan Karyawan menggunakan autocomplete.

====================
HSDM SOFTWARE 3.1.4 UPDATE
Release: Penambahan Fitur 'Export Release Draft' jika memerlukan pengecekan gaji sebelum dirilis.
Free Transfer: Pemilihan Karyawan menggunakan autocomplete.
====================

3.1.3	16/03/2024
Release: Fitur export draft utk pengecekan gaji sebelum dirilis.
Bug Fixed: Karyawan baru disebut Berhenti Bekerja karena date_end otomatis diset.

3.1.2	01/02/2024
Bug Fixed: App > Account > Payroll
Bug Fixed: Release Info

3.1.1	31/01/2024
Bug Fixed: table v payroll berubah2.

3.1.0	26/01/2024
Update Framework
Release: Menambah fitur Release Pending, Cancelled.
Customer: Add new, otomatis set date_end = 1 bulan dari date_start

3.0.3	17/01/2024
Release: Export CIMB hanya boleh 1x per karyawan
Mail: Fixed PHPMailer

3.0.2	04/01/2024
HSDM Tool: v1.3: Improve Security

3.0.1.b	30/12/2023
Bug Fixed: Release: Filter payroll method not working

3.0.1	29/12/2023
Release: Repair archiving system

3.0.0	29/12/2023
Repair some minor bugs

3.0.beta.1	21/12/2023
APP > Login: Open HSDM Tool via web page
HSDM Tool: v 1.2
Download: Upload file HSDM Presence Tool_v1.2.zip to site
MODUL: Information > Download
Move file template folder to DIR_FILE

3.0.beta.0	15/12/2023
Trial: Penerapan HSDM Presence Tool (Desktop App)

2.8.2	07/12/2023
Schedule > Import: Penerapan code_id untuk import schedule

2.8.1	04/12/2023
Some Bugs Fixed

2.8.0	01/12/2023
Incentive, Cutoff, Overtime, Loan: Add more filter, Change customer selection to autocompletion
Presence > Absence, Exchange: Add more filter, Change customer selection to autocompletion
MODUL: APP > Account > Vacation
MODUL: Admin Maintenance
Customer > Document: Rebuild dan menambah filter dan notifikasi
MODUL: Dashboard > Attention
Customer, Report > Customer, Customer Info: Mengganti info cuti menjadi 'Sisa Cuti'
Report > Customer, Customer Info: Menyesuaikan modul dgn adanya Modul Contract
MODUL: Employment Contracts
Bug Fixed: Front: Login error jika tidak ada sidik jari aktif
Delete from oc_setting code like 'pav%'
Delete table pav&, megamenu%
Bug Fixed: Schedule: Perbaikan showed data pada schedule list.
Bug Fixed: App > Presence > Login: Jadwal malam terdeteksi besoknya karena login_start terlalu besar.
Customer Finger: Repair Layout

2.7.1	01/11/2023
Customer Finger: Add Search Feature
Customer Finger: Add multiple finger for scan
Bug Fixed: Schedule, Presence, Payroll, Release: Autocomplete pada filter termasuk karyawan yang sudah berhenti pada periode tersebut

2.7.0	21/10/2023
Modul: User Online
App > Presence > Login: Correct sort for card when using lastname
Incentive, Cut Off, Overtime: Customer List termasuk karyawan yang sudah berhenti sesuai pengaturan pada Setting
Customer, Document, Finger Register: Autocomplete pada filter termasuk karyawan yang sudah berhenti
Bug Fixed: Customer: Bbrp sort tidak berfungsi tepat
Bug Fixed: Presence Period: Default date_start untuk input data pertama kali
Bug Fixed: Schedule > Import: Schedule Type not list if employee is filtered by name
Bug Fixed: Finger Device: Error SN used
Bug Fixed: Release: Filter name not work

2.6.1	07/09/2023
Component > Incentive, Cutoff: Replace to Additions and Deductions
Setting, Free Transfer, Allowance: Customer availability in selection, last for x months
Insurance, Customer: Registered Wage untuk gaji yang terdaftar di BPJS
Global: Currency Format
DB: Create View terpusat.

2.6.0	30/08/2023
Report > Payroll Insurance: Add filters
Bug Fixed: Customer tanpa jabatan atau posisi tidak muncul dalam View
Component > Insurance: Menambah Jaminan Pensiun untuk BPJS TK

2.5.18	28/08/2023
Component > Insurance: Menambah Jaminan Pensiun untuk BPJS TK
Bug Fixed: Customer yg masuk pada tanggal akhir cutoff tidak terdaftar pada menu Presence

2.5.17	24/08/2023
Payroll Setting: Add option for Presence Card.
Payroll Export: Split Name and Longname in Export file, Add NIP column.
Bug Fixed: Customer: Health insurance save bug. 
Payroll, Release, Payroll Basic: Add Division Filter
Release: Add Email Filter

2.5.16	11/08/2023
Bug Fixed: Customer: Add new customer did not save birth date

2.5.15	27/06/2023
Payroll: Export feature. Available when status is 'generated'
Some security algorithm fixed

2.5.14	14/04/2023
Release > Allowance: Ability to add more customer

2.5.13	05/04/2023
Bug Fixed: Textual change
Insurance: Additional Setting for insurance modul

2.5.12	02/03/2023
Mail: Add Mailtrap protocol for mail service

2.5.11b	20/02/2023
Bug Fixed: Front Login not working

2.5.11	20/02/2023
Bug Fixed: Phpmailer SMTP connect failed.
Bug Fixed: Period Info
Bug Fixed: Payroll: Last update not show the right data.
Front Dashboard: Remove Login Button

2.5.10
Schedule Type > List: Currently Used field now calculate overtime and exchange too.

2.5.9	17/09/2022
MODUL: APP > Account > Payroll Basic
MODUL: APP > Account > Payroll
MODUL: APP > Account > Schedule
MODUL: APP > Account > My Account Info
Modify: Payroll Basic, attach active payroll basic to customer
	ALTER TABLE `oc_customer` ADD `payroll_basic_id` INT(11) NOT NULL DEFAULT '0' AFTER `date_end`;
Payroll Basic List: Add filter and sort 

2.5.9_a
MODUL: APP > Account: Login by location (Progress)
APP > Modify: login, forgotten
	Remove: register

2.5.8	30/08/2022
MODUL: Presence/Schedule: Import Schedule from excel
	Presence/Schedule: Export excel template
Period Info: Modify period shortcut.

2.5.7	08/08/2022
Extend Customer expiration time (Hard Code: 2 days)
	Modify Table: Customer
		ALTER TABLE `oc_customer` ADD `cookie` VARCHAR(32) NOT NULL AFTER `code`;
Extend Admin expiration time (Hard Code: 2 hours)
	Modify Table: User
		ALTER TABLE `oc_user` ADD `cookie` VARCHAR(32) NOT NULL AFTER `code`;

APP > Startup > Startup: Replace header(Setcookie:) to setcookie

2.5.6	03/08/2022
Common > Payroll Info: Add shortcut to jump to other period
Presence > Schedule, Presence: Add shortcut between modul
Employee > Document: Max size of document uploaded set to 1000x1000px
Employee: Add image in employee list
Incentive, Cutoff, Overtime: Employee list based on 1 month/2 weeks earlier active status

2.5.5	28/07/2022
Bug Fixed: Presence > Schedule: Presence Status calculation based on Schedule time in instead of Log time in
Release > Export: Sort list by name
Employee > Employee: Set Active Employee as default filter

2.5.4	27/07/2022
Loan > Loan: Loan Input can not be deleted
Bug Fixed: Released > Info: Release Summary show 0 for method "Tunai"
Bug Fixed: Released > Edit: Modify date not working
Bug Fixed: Customer > Edit: Not logged in customer history

2.5.3	21/06/2022
Modify: Framework Updated
Modify: Filemanager: Generate image filename

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