<?php

	date_default_timezone_set(@date_default_timezone_get());

	require_once('Tk/Rule.php');
	require_once('Tk.php');
	use \Spiro\Puggan\Bankgiro\Tk;

	require_once('Tk56.php');
	use \Spiro\Puggan\Bankgiro\Tk56;

	$tks = [];

	$tk = new Tk(51, ['Clearingnummer' => 9900, 'Bankgironummer' => 9912346]);
	$tks[] = $tk;

	/** @var \Spiro\Puggan\Bankgiro\Tk52 $tk */
	$tk = new Tk(52);
	$tk->Bankgironummer = 9912346;
	$tk->Betalarnummer = 10133;
	$tk->Bankkontonummer = 9918000000041014;
	$tk->Personnummer = 194512121212;
	$tks[] = $tk;

	$tk = new Tk(53);
	$tk['Information'] = "JAG VILL BETALA MÅNADSVIS";
	$tks[] = $tk;

	$tk = new Tk(54, ['DORIS DEMOSSON', 'C/o DAVID DEMOSSON']);
	$tks[] = $tk;

	$tks[] = new Tk(55, ['DEMOVÄGEN 1']);
	$tks[] = new Tk56([10000, 'DEMOSTAD']);

	$tks[] = new Tk(59, [NULL, 9900, count($tks) - 1]);

	print_r($tks);

	echo implode(PHP_EOL, $tks) . PHP_EOL;
