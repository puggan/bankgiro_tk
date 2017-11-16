<?php

	date_default_timezone_set(@date_default_timezone_get());

	require_once('autoload.php');

	use \Spiro\Puggan\Bankgiro\File\TkFile5x;

	$f = new TkFile5x(9912346);
	$customer = [
		'Betalarnummer' => 10133,
		'Bankkontonummer' => 9918000000041014,
		'Personnummer' => 194512121212,
		'Information' => "JAG VILL BETALA MÅNADSVIS",
		'Rad1' => 'DORIS DEMOSSON',
		'Rad2' => 'C/o DAVID DEMOSSON',
		'Rad3' => 'DEMOVÄGEN 1',
		'Postnummer' => 10000,
		'Postadress' => 'DEMOSTAD',
	];

	$f->add_customer($customer);
	$f->add_customer(['Kundnummer' => 1, 'Bankkontonummer' => 5, 'Personnummer' => 197001010000]);

	echo $f . PHP_EOL;
