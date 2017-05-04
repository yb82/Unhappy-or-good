<?php
require_once ("./classes/Record.php");

define("HAPPY",4);
define("GOOD",3);
define("SOSO",2);
define("ANGRY",1);

$record = new Record();
if(isset($_POST["date"])){

	$data = explode(",", $_POST['date']);
	print_r($data);
	echo $record->createRangeData($data[0],$data[1]);

}
if(isset($_POST["today"])){

	echo $record->createTodaysandAllData(1);

}
if(isset($_POST["all"])){

	echo $record->createTodaysandAllData(3);

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
