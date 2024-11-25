<?php
// Heading
$_['heading_title']              = 'Pembagian Gaji';

// Text
$_['text_complete_success']      = 'Success: Penggajian karyawan periode ini telah selesai!';
$_['text_confirm_release'] 		 = 'Warning: Export Release (kecuali draft) hanya boleh 1x per karyawan. Pastikan data yang dipilih telah sesuai.\nLanjutkan?';
$_['text_confirm_send_all'] 	 = 'Send Payroll Statement to All Employees?';
$_['text_edit']                  = 'Edit Akun Sumber Dana';
$_['text_fund_acc_name'] 	  	 = 'Nama :';
$_['text_fund_acc_no'] 	  	   	 = 'No Rekening :';
$_['text_fund_date_release'] 	 = 'Tanggal Proses :';
$_['text_fund_email'] 	  	   	 = 'Email :';
$_['text_information'] 	 		 = 'Data aktif yang tidak diarsipkan lebih dari %d bulan. Silahkan set status menjadi Complete untuk mengurangi data aktif.';
$_['text_list']                  = 'Daftar Gaji';
$_['text_method'] 	 			 = '%s (%d orang) :';
$_['text_no_template'] 	 		 = 'BANK TEMPLATE NOT FOUND! CONTACT YOUR WEB PROGRAMMER!';
$_['text_payroll_period']        = 'Periode: %s, Status: %s';
$_['text_period_list']           = 'Daftar Periode Pembagian Gaji';
$_['text_release_info'] 	  	 = 'Info Pembagian Gaji';
$_['text_release_late'] 	  	 = 'Penggajian Tertunda Periode Sebelumnya';
$_['text_release_present'] 	  	 = 'Penggajian Periode Ini';
$_['text_success_send'] 	 	 = 'Success: Payroll Statements sending process has been completed!';
$_['text_success']          	 = 'Success: You have modified payroll releases!';
$_['text_pending']               = 'Ditunda';
$_['text_released']              = 'Telah dirilis';
$_['text_unreleased']            = 'Belum dirilis';
$_['text_cancelled']             = 'Dibatalkan';

//Code
$_['code_full_overtime']		 = 'LH';

// Column
$_['column_acc_no']      	 	 = 'No. Rekening';
$_['column_action']              = 'Action';
$_['column_customer_department'] = 'Divisi';
$_['column_customer_group']      = 'Jabatan';
$_['column_date_released'] 	 	 = 'Tanggal Proses';
$_['column_email']      		 = 'Email';
$_['column_fund_acc_name'] 	  	 = 'Sumber Dana';
$_['column_fund_acc_no']      	 = 'No. Rekening';
$_['column_location'] 			 = 'Lokasi Kerja';
$_['column_name']       		 = 'Nama Karyawan';
$_['column_net_salary']          = 'Jumlah Gaji';
$_['column_nip']            	 = 'NIP';
$_['column_payroll_method']      = 'Metode/Bank';
$_['column_payroll_status']      = 'Payroll Status';
$_['column_period']              = 'Periode Absensi';
$_['column_statement_sent'] 	 = 'Statement Sent';
$_['column_sum_grandtotal'] 	 = 'Total Gaji';

// Entry
$_['entry_payroll_status']      = 'Payroll Status';
$_['entry_period']         		= 'Periode Absensi';
$_['entry_fund_account']		= 'Sumber Dana';
$_['entry_date_release']        = 'Tanggal Proses';
$_['entry_name']      			= 'Nama Karyawan';
$_['entry_email']				= 'E-Mail';
$_['entry_customer_group']      = 'Jabatan';
$_['entry_customer_department']	= 'Divisi';
$_['entry_location']			= 'Lokasi Kerja';
$_['entry_payroll_method']      = 'Metode/Bank';
$_['entry_release_status'] 	 	= 'Status Rilis';
$_['entry_statement_sent'] 	 	= 'Statement Sent';

// Button
$_['button_action']				= 'Action';
$_['button_draft']				= 'Export Draft';
$_['button_release']			= 'Release Payroll';
$_['button_payroll_complete'] 	= 'Complete Payroll';
$_['button_export'] 			= 'Export Release';
// $_['button_export_cimb'] 		= 'Export to CIMB';
$_['button_send'] 				= 'Send Statement';
$_['button_uncomplete'] 		= 'Completed & Archived. Click to Unarchive';
$_['button_pending'] 			= 'Set Selected as Pending';
$_['button_cancelled'] 			= 'Set Selected as Cancelled';
$_['button_unreleased'] 		= 'Unset Selected Release Status';

// Error
$_['error_warning']				= 'Warning: Please check the form carefully for errors!';
$_['error_permission']          = 'Warning: You do not have permission to modify payroll releases!';
$_['error_status']              = 'Warning: Wrong Status! Could not complete this action!';
$_['error_completed_after']		= 'Warning: Completed Status allowed %s months after released!';
$_['error_db_archive']			= 'Warning: Database archive not defined!';
$_['error_fund_account']		= 'Warning: Pilih akun sumber dana!';
$_['error_date_release']        = 'Warning: Tanggal proses harus diisi dan belum berlalu!';
$_['error_not_found']           = 'Warning: Data tidak ditemukan!';
$_['error_mail_sending_status'] = 'Some statement sending process got error!';
$_['error_complete'] 	 		= 'Warning: Data aktif yang tidak diarsipkan lebih dari %d bulan. Silahkan set status payroll untuk periode-periode yang lalu menjadi Complete untuk mengurangi data aktif.';
$_['error_status_released'] 	= '%s: Action gagal karena penggajian telah dirilis!';
$_['error_mail_not_released']	= '%s: Gaji harus dirilis sebelum rincian gaji dikirimkan!';
