<?php
# Heading
$_['heading_title']				= 'Payroll Type';

# Text
$_['text_list']					= 'Payroll Type List';
$_['text_add']					= 'Add Payroll Type';
$_['text_edit']					= 'Edit Payroll Type';
$_['text_addition']				= 'Komponen Pendapatan';
$_['text_deduction']			= 'Komponen Potongan';
$_['text_success']				= 'Success: You have modified payroll types!';
$_['text_note']					= 'Variabel yang diijinkan: %s.';
$_['text_note_deduction']		= '<br>Jika Potongan Pro Rata memenuhi, maka akan menghapus potongan lainnya.';

$_['text_gp']					= '[var] x Gaji Pokok.';
$_['text_tj']					= '[var] x Tunjangan Jabatan.';
$_['text_th']					= '[var] x Tunjangan Hadir.';
$_['text_pph']					= '[var] x PPH.';
$_['text_total_um']				= '[var] x Uang Makan.';
$_['text_pot_um']				= '[var] x Uang Makan.';
$_['text_pot_pph']				= '[var] x 100% PPH. Max 1.';
$_['text_pot_gp_tj_5']			= '[var] / [hke] x (Gaji Pokok + Tunjangan Jabatan). Pembulatan 5000. Jika [var] > 5';
$_['text_pot_gp_tj']			= '[var] / [hke] x (Gaji Pokok + Tunjangan Jabatan). Pembulatan 5000.';
$_['text_pot_gp_tj_r']			= '[var] / [hke] x (Gaji Pokok + Tunjangan Jabatan).';
$_['text_pot_gp']				= '[var] / [hke] x Gaji Pokok. Pembulatan 5000.';
$_['text_pot_gp_r']				= '[var] / [hke] x Gaji Pokok.';
$_['text_pot_tj']				= '[var] / [hke] x Tunjangan Jabatan. Pembulatan 5000.';
$_['text_pot_tj_r']				= '[var] / [hke] x Tunjangan Jabatan.';
$_['text_pot_th_20']			= '[var] x 20% Tunjangan Hadir. Max 5.';
$_['text_pot_th_100']			= '[var] x 100% Tunjangan Hadir. Max 1.';
// $_['text_pot_t']				= '[var] x Uang Makan. Potongan Keterlambatan';
$_['text_pot_prop_all']			= '[var] / [hke] x Total Pendapatan. Potongan Pro Rata menghapus potongan lainnya.';

# Column
$_['column_description']		= 'Deskripsi';
$_['column_name']				= 'Nama';

# Entry
$_['entry_action']				= 'Action';
$_['entry_description']			= 'Deskripsi';
$_['entry_variable']			= 'Variabel';
$_['entry_name']				= 'Nama';
$_['entry_sort_order']			= 'Sort Order';
$_['entry_title']				= 'Nama Komponen';
$_['entry_type']				= 'Tipe Komponen';

#Button
$_['button_payroll_type_add']	= 'Add Payroll Type Component';

# Error
$_['error_warning']				= 'Warning: Please check the form carefully for errors!';
$_['error_permission']			= 'Warning: You do not have permission to modify payroll types!';
$_['error_name']			 	= 'Nama harus diisi (5 - 100 karakter)!';
$_['error_description']		 	= 'Deskripsi tidak lebih dari 255 karakter!';
$_['error_component']			= 'Warning: Nama Komponen, Tipe Komponen, maupun Variabel harus diisi!';
$_['error_component_count']		= 'Warning: Jumlah masing-masing komponen maksimal 5! (Tidak termasuk Potongan Pro Rata).';
$_['error_customer']			= 'Warning: Tipe Payroll ini tidak bisa dihapus karena terhubung dengan %d data karyawan!';
