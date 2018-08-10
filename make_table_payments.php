<?php

$contents = file_get_contents('bnm_payments.tsv');

$lines = explode("\n", $contents);

// count all the days
$array = [];
foreach ($lines as $line => $value) {
	if ($line == 0) {
		continue;
	}
	$content = explode("\t", $value);

	if (!in_array($content[4], $array)) {
		array_push($array, $content[4]);
	}
}
array_pop($array);


$dayCount = count($array);

// create a fixed array -- 1 day has 11 items
$newFile = array_fill(0, $dayCount, array_fill(0, 11, 0));


$array2 = [];
foreach ($lines as $line => $value) {
	if ($line == 0) {
		continue;
	}
	$content = explode("\t", $value);

	$num = (int) $content[4];

	// note: num -1 for the index
	switch (true) {
		case $content[3] == 'agent' && $content[2] == 'bank'  :
			$newFile[$num-1][0] = $content[0];
			break;

		case $content[3] == 'agent' && $content[2] == 'bpay'  :
			$newFile[$num-1][1] = $content[0];
		break;

		case $content[3] == 'agent' && $content[2] == 'over_the_phone'  :
			$newFile[$num-1][2] = $content[0];
		break;

		case $content[3] == 'agent' && $content[2] == 'stripe'  :
			$newFile[$num-1][3] = $content[0];
		break;

		case $content[3] == 'site' && $content[2] == 'bank'  :
			$newFile[$num-1][4] = $content[0];
		break;

		case $content[3] == 'site' && $content[2] == 'bpay'  :
			$newFile[$num-1][5] = $content[0];
		break;

		case $content[3] == 'site' && $content[2] == 'over_the_phone'  :
			$newFile[$num-1][6] = $content[0];
		break;

		case $content[3] == 'site' && $content[2] == 'paypal'  :
			$newFile[$num-1][7] = $content[0];
		break;

		case $content[3] == 'site' && $content[2] == 'stripe'  :
			$newFile[$num-1][8] = $content[0];
		break;

		case $content[3] == 'agent' && $content[2] == 'NULL'  :
			$newFile[$num-1][9] = $content[0];
		break;

		case $content[3] == 'site' && $content[2] == 'NULL'  :
			$newFile[$num-1][10] = $content[0];
		break;
	}
}

$f = fopen('payments_new.csv', "w+");

$lineArray = [];
foreach ($newFile as $key => $value) {
	array_push($lineArray, implode("\t", $value));
}

$csv = '';
foreach ($lineArray as $key => $value) {
	$csv .= $value . "\n";
}

fwrite($f, $csv);
fclose($f);
