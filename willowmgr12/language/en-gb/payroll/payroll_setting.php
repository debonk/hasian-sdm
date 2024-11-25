<?php
// Heading
$_['heading_title']              = 'Payroll Setting';

// Text
$_['text_edit']					= 'Edit Payroll Setting';
$_['text_firstname']			= 'Nama';
$_['text_lastname']				= 'Nama Lengkap';
$_['text_image']				= 'Foto Karyawan';
$_['text_success']				= 'Success: You have modified payroll settings!';

// Tab
$_['tab_presence_status']        = 'Presence Status';
$_['tab_payroll_status']         = 'Payroll Status';
$_['tab_general']          		 = 'General';

// Entry
$_['entry_status_off']    	 	 = 'Status Tidak Hadir (Off)';
$_['entry_status_h']    	 	 = 'Status Hadir';
$_['entry_status_s']    	 	 = 'Status Sakit';
$_['entry_status_i']    	 	 = 'Status Izin';
$_['entry_status_ns']    	 	 = 'Status Belum/Tidak Bekerja';
$_['entry_status_ia']    	 	 = 'Status Izin Alpha';
$_['entry_status_a']    	 	 = 'Status Alpa';
$_['entry_status_c']    	 	 = 'Status Cuti';
$_['entry_status_t1']    	 	 = 'Status Terlambat I';
$_['entry_status_t2']    	 	 = 'Status Terlambat II';
$_['entry_status_t3']    	 	 = 'Status Terlambat III';
$_['entry_presence_lock'] 		 = 'Kunci Data Hadir';
$_['entry_presence_statuses']    = 'Pemberitahuan Absen';
$_['entry_pending_status']    	 = 'Pending Status';
$_['entry_processing_status']    = 'Processing Status';
$_['entry_submitted_status']     = 'Submitted Status';
$_['entry_generated_status']     = 'Generated Status';
$_['entry_approved_status']      = 'Approved Status';
$_['entry_released_status']      = 'Released Status';
$_['entry_completed_status']     = 'Completed Status';

$_['entry_default_hke']  	 	 = 'HKE Default';
$_['entry_vacation_limit']  	 = 'Batas Cuti Maksimal';
$_['entry_schedule_lock']  	 	 = 'Kunci Jadwal';
$_['entry_login_session']  	 	 = 'Batas Waktu Sesi Login(jam)';
$_['entry_login_date']  	 	 = 'Modifikasi Tanggal Login (jam)';
$_['entry_logout_date']  	 	 = 'Modifikasi Tanggal Logout (jam)';
$_['entry_login_start']  	 	 = 'Awal Login (menit)';
$_['entry_login_end']  	 	 	 = 'Akhir Login (menit)';
$_['entry_logout_start']  	 	 = 'Awal Logout (menit)';
$_['entry_late_tolerance']  	 = 'Toleransi Keterlambatan (menit)';
$_['entry_schedule_check']  	 = 'Validasi Jadwal';
$_['entry_presence_card']  	 	 = 'Kartu Absensi';
$_['entry_use_fingerprint']  	 = 'Gunakan Fingerprint';
$_['entry_vacation_status']  	 = 'Status Cuti';
$_['entry_max_unarchive']	 	 = 'Max Unarchive Data (bulan)';

// Error
$_['error_warning']              = 'Warning: Please check the form carefully for errors!';
$_['error_permission']           = 'Warning: You do not have permission to modify payrolls!';
$_['error_default_hke']     	 = 'Warning: Default HKE harus lebih besar dari 0!';
$_['error_vacation_limit']     	 = 'Warning: Batas cuti maksimal harus lebih besar dari 0!';
$_['error_vacation_status']      = 'Warning: Status Cuti harus ditentukan!';

// Help
$_['help_pending_status']    	= 'Payroll Status sebelum pengisian daftar hadir karyawan';
$_['help_processing_status']    = 'Payroll Status saat proses pengisian daftar hadir karyawan';
$_['help_submitted_status']     = 'Payroll Status setelah daftar hadir karyawan diajukan/submitted';
$_['help_generated_status']     = 'Payroll Status saat proses perhitungan gaji karyawan';
$_['help_approved_status']      = 'Payroll Status setelah gaji karyawan disetujui';
$_['help_released_status']      = 'Payroll Status saat gaji karyawan dibagikan';
$_['help_completed_status']     = 'Payroll Status proses penggajian karyawan telah selesai';
$_['help_max_unarchive']		= 'Maksimum data yang tidak diarsipkan (status belum Complete)';
$_['help_presence_lock']     	= 'Kunci data hadir. Kunci dibuka untuk lokasi kerja yang masih masa trial.';
$_['help_default_hke']     		= 'Default HKE untuk karyawan yang masuk di pertengahan bulan.';
$_['help_vacation_limit']     	= 'Isi dengan 0 untuk menonaktifkan Batas Cuti Tahunan';
$_['help_schedule_lock']     	= 'Jika diset No, jadwal bisa diedit walaupun sudah diterapkan.';
$_['help_login_date']     		= 'Penentuan tanggal untuk pengecekan jadwal login. Diisi jika ada jadwal terlalu malam.';
$_['help_logout_date']     		= 'Penentuan tanggal untuk pengecekan jadwal logout. Diisi jika ada jadwal dimana logout dilakukan esok harinya.';
$_['help_login_start']     		= 'Waktu awal mulai login yang diizinkan(dalam menit). Set 0 untuk menonaktifkan.';
$_['help_login_end']     		= 'Waktu akhir login yang diizinkan(dalam menit). Set 0 untuk menonaktifkan.';
$_['help_logout_start']     	= 'Waktu awal mulai logout yang diizinkan(dalam menit). Set 0 untuk menonaktifkan.';
$_['help_use_fingerprint']     	= 'Gunakan peralatan fingerprint untuk login/logout';
$_['help_presence_card']   		= 'Kartu pengenal karyawan yang digunakan pada halaman absensi.';
$_['help_schedule_check']     	= 'Jika diset No, login akan diproses tanpa validasi jadwal.';
$_['help_presence_statuses']    = 'Status Ketidakhadiran dengan pemberitahuan';
