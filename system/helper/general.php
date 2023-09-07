<?php
function token($length = 32) {
	// Create random token
	$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
	$max = strlen($string) - 1;
	
	$token = '';
	
	for ($i = 0; $i < $length; $i++) {
		$token .= $string[mt_rand(0, $max)];
	}	
	
	return $token;
}

# Get Number Only from number string i.e 1,000,000 to 1000000
function getNumber($currency_string)
{
	return preg_replace('/(?!-)[^0-9.]/', '', $currency_string);
}
