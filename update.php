<?php
require_once ("./classes/Record.php");

define("HAPPY",4);
define("GOOD",3);
define("SOSO",2);
define("ANGRY",1);

$record = new Record();

if (isset ( $_POST ["emo"] )) {
	$emo = $_POST["emo"];

	switch ($emo) {
		case HAPPY:
			echo $record->addRecord(HAPPY);

			break;
		
		case GOOD:
			echo $record->addRecord(GOOD);
			break;
		case SOSO:
			echo $record->addRecord(SOSO);
			break;
		case ANGRY:
			echo $record->addRecord(ANGRY);
			break;
		
	}
}
