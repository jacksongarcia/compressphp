<?php
	require_once 'Compress.class.php';
	//require_once 'CompressZip.class.php';
	use compress\Compress as Comprime;
	//use compac\CompressZip as Ziper;


		//return new Zip();
		$comp = (new Comprime())->getInstanceFormat('zip');
		//$comp->comprim('Free PNG sample icons');
		//$comp->add_to_files('Free PNG sample icons', null, null);
		//$comp->extract_files ('Free PNG sample icons', 'formatos');
		$comp->extract_to_name('Free PNG sample icons');

?>