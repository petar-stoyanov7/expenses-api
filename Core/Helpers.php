<?php

function sanitize($string,$punctuation="") {
	if (empty($string)) {
		return $string;
	}
	$counter = 0;
	if (empty($punctuation)) {
		$check = '/[\!\?\.\,\@\`\~\#\$\%\^\&\*\(\)\-\_\=\+\\\;\:\'\"\|\<\>\/\|]{1}/';
	} else {
		$check = '/[\`\~\#\$\%\^\&\*\(\)\-\_\=\+\\\;\:\'\"\|\<\>\/\|]{1}/';
	}
	$invalid = "";
	for ($i=0;$i<strlen($string);$i++) {
		if (preg_match($check, $string[$i])) {
			$invalid .= " ".$string[$i];
		} else {			
			$counter++;
		}
	}
	if (strlen($string) != $counter) {
		die(display_warning("Невалидни символи \" ".$invalid." \""));
	} else {
		return $string;
	}
}

function convert_date($date) {
	$year = substr($date, 0,4);
	$month = substr($date, 5,2);
	$day = substr($date, 8,2);
	$bgdate = $day.".".$month.".".$year;
	return $bgdate;
}