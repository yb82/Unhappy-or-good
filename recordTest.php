<?php
require_once ("./classes/Record.php");

echo "hello";

	 $record = new Record();
	 $i=0;
	 for($i ; $i <50 ; $i++){
	 	$record->addRecord(4);
	 }

	for($i =0 ; $i <20 ; $i++){
		$record->addRecord(3);
	}
	for($i=0 ; $i <10 ; $i++){
		$record->addRecord(2);
	}
	for($i=0 ; $i <30 ; $i++){
		$record->addRecord(1);
	}
	echo $record->getHappyRecords("19/04/2017","20/04/2017")."<br/>";
	echo $record->getGoodRecords("19/04/2017","20/04/2017")."<br/>";
	echo $record->getBadRecords("19/04/2017","20/04/2017")."<br/>";
	echo $record->getAwfulRecords("19/04/2017","20/04/2017")."<br/>";

?>