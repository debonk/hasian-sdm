# hsdm software

2.x.0-a
MODUL: APP > Account: Login by location (Trial & Progress)
MODUL: Customer > Presence Method

MODIFY CONFIG

MODIFY TABLE

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

====================
HSDM SOFTWARE 4.0.0a UPDATE

+ PAYROLL TYPE: Metode perhitungan pendapatan dan potongan bisa diatur berbeda untuk setiap karyawan (Juga bisa digunakan untuk karyawan harian)
+ Employee: Pengaturan Perhitungan Penggajian (Payroll Type) diset di modul Employee.
+ Presence: Kehadiran karyawan sudah bisa di-sort berdasarkan status kehadiran utama (h, s, i...)
+ Schedule: Summarize (tanpa centang) kini menyesuaikan dgn filter yang sedang digunakan. (Sebelumnya otomatis untuk seluruh karyawan)
+ Fitur Toleransi Keterlambatan. Sehingga tidak perlu diatur pada jadwal.
+ Presence, Payroll: Nilai HKE yang ditunjukkan adalah total HK keseluruhan: Total hadir + total tidak hadir (termasuk C dan CK). Sebelumnya C dan CK tidak termasuk dalam HKE
+ Karyawan bisa melihat data jadwal dan kehadiran bulan berjalan dan bulan lalu. (Sebelumnya hanya bulan berjalan)
+ Employee: Data Premi 12 Jam dan Skip Masa Percobaan dihapus.

====================

<!-- NEW TABLE -->
oc_payroll_type

<!-- MODIFY TABLE -->
ALTER TABLE oc_presence_status ADD status boolean DEFAULT 1 NOT NULL;

ALTER TABLE `oc_presence` CHANGE `date_added` `date_added` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP; 
ALTER TABLE `oc_presence_total` CHANGE `date_added` `date_added` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE oc_presence_total ADD additional TEXT NULL AFTER total_t3;


ALTER TABLE oc_customer ADD payroll_type_id INT(11) DEFAULT 1 NULL AFTER payroll_basic_id;
ALTER TABLE oc_customer MODIFY COLUMN payroll_type_id int(11) NULL;
ALTER TABLE oc_customer DROP COLUMN full_overtime;
ALTER TABLE oc_customer DROP COLUMN skip_trial_status;

ALTER TABLE oc_payroll CHANGE gaji_pokok addition_0 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll MODIFY COLUMN addition_0 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll CHANGE tunj_jabatan addition_1 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll MODIFY COLUMN addition_1 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll CHANGE tunj_hadir addition_2 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll MODIFY COLUMN addition_2 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll CHANGE tunj_pph addition_3 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll MODIFY COLUMN addition_3 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll CHANGE total_uang_makan addition_4 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll MODIFY COLUMN addition_4 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll CHANGE pot_sakit deduction_0 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll MODIFY COLUMN deduction_0 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll CHANGE pot_bolos deduction_1 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll MODIFY COLUMN deduction_1 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll CHANGE pot_tunj_hadir deduction_2 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll MODIFY COLUMN deduction_2 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll CHANGE pot_gaji_pokok deduction_3 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll MODIFY COLUMN deduction_3 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll CHANGE pot_terlambat deduction_4 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll MODIFY COLUMN deduction_4 int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll MODIFY COLUMN uang_makan int(11) NOT NULL DEFAULT '0';
ALTER TABLE oc_payroll CHANGE uang_makan uang_makan int(11) NOT NULL DEFAULT '0' AFTER date_added;
ALTER TABLE oc_payroll ADD title text NULL AFTER deduction_4;
ALTER TABLE oc_payroll ADD payroll_basic_id int(11) NOT NULL DEFAULT '0' AFTER customer_id;

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