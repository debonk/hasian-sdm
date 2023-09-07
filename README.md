# hsdm software

2.x.0-a
MODUL: APP > Account: Login by location (Trial & Progress)
MODUL: Customer > Presence Method

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