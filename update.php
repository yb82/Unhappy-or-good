<?php
require_once ("./classes/Record.php");

define("HAPPY",4);
define("GOOD",3);
define("SOSO",2);
define("ANGRY",1);
error_reporting(0);
$record = new Record();
if(isset($_POST["date"])){

	$data = $_REQUEST['date'];
	//print_r($data);
	echo $record->createRangeData($data["from"],$data["to"]);

}
if(isset($_POST["today"])){

	echo $record->createTodayData();

}
if(isset($_POST["all"])){

	echo $record->createAllData();

}

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