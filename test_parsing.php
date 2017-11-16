<?php

	date_default_timezone_set(@date_default_timezone_get());

	require_once('Tk/Rule.php');
	require_once('Tk.php');

	use \Spiro\Puggan\Bankgiro\Tk;

	$content = <<<TK_FILE
512016071499000009912346AG-EMEDGIV                                              
52000991234600000000000101339918000000041014194512121212     0                  
53JAG VILL BETALA M≈NADSVIS                                                     
54DORIS DEMOSSON                      C/o DAVID DEMOSSON                        
55DEMOVƒGEN 1                                                                   
5610000DEMOSTAD                                                                 
52000991234600000000000101339919000000041014194512121212     1                  
53                                                                              
54BENGT BENGTSSON                                                               
55TESTVƒGEN 2                                                                   
5610000STORSTAD                                                                 
52000991234600000000000101339920000000041014194512121212     2                  
53                                                                              
54KARL KARLSSON                                                                 
55STORGATAN 3                                                                   
5610000STORSTAD                                                                 
592004101599000000015
TK_FILE;


	$tks = [];
	foreach(explode("\n", $content) as $row)
	{
		$row = trim($row);
		if($row) $tks[] = new Tk($row);
	}

	print_r($tks);