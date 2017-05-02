<?php

require_once('../config/config.php');
require_once('classes/RecordSet.php');
define ("TODAY",1);
define ("RANGE",2);
define ("ALL",3);
class Record{
	
	
	private $now;

	private $db_connection = null;
	public function __construct(){
		$now = date("d-m-Y");
		$now = $this->dbToDate($now);
	}


	public function addRecord($rate){
		print_r($this->now);
		if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 'insert into happyornot (`rate`) values (:rate)' );
			

			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			//$query->bindValue ( ':rate', $this->now, PDO::PARAM_STR);
			$success=$query->execute ();
			return $success;
		}
	}
	public function addRecordDate($rate,$date){
		print_r($this->now);
		if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 'insert into happyornot (`rate`,`timestamp`) values (:rate,:timestamp)' );
			

			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			$query->bindValue ( ':timestamp', $this->dateToDB($date), PDO::PARAM_STR);
			$success=$query->execute ();
			return $success;
		}
	}
	private function countingData($rate, $from , $to){
			if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 'SELECT YEAR(timestamp), MONTH(timestamp), count(*) from happyornot where WHERE `rate` = :rate AND `timestamp` >= :from  and `timestamp` <= :to group by YEAR(timestamp), MONTH(timestamp)');
			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			$query->bindValue ( ':from', $this->dateToDB( $from)." 00:00:00", PDO::PARAM_STR );
			$query->bindValue ( ':to', $this->dateToDB($to)." 23:59:59", PDO::PARAM_STR );
						
			$query->execute ();
			$results = $query->fetchAll ();
			
			//$j = 0;
			if (count ( $results ) > 0) {
				
				return $results;
			} else return 0 ;

		}	

	}

	private function countingDataall($rate){
			if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 'SELECT  YEAR(timestamp), MONTH(timestamp), count( * )FROM `happyornot` WHERE `rate` = :rate AND `timestamp` <= NOW( ) group by YEAR(timestamp), MONTH(timestamp)' );
			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			$success=$query->execute ();

			
			$results = $query->fetchAll ();
			
			//$j = 0;
			if (count ( $results ) > 0) {
				
				
					return 	$results;								
				
			} else return 0 ;

		}	

	}
	public  function createTodaysandAllData($tag)
	{
		$output=array();
		$happyDataSet;
		$goodDataset;
		$sosoDataset;
		$badDataset;
		switch ($tag) {
			case 'TODAY':
				$happyDataSet = $this->getHappyRecordsToday();
				$goodDataset = $this->getGoodRecordsToday();
				$sosoDataset = $this->getSosoRecordsToday()
				$badDataset = $this->getBadRecordsToday();
				break;
			case 'ALL':
				$happyDataSet = $this->getHappyRecordsAll();
				$goodDataset = $this->getGoodRecordsAll();
				$sosoDataset = $this->getSosoRecordsAll()
				$badDataset = $this->getBadRecordsAll();
				break;
			
		}

		foreach ($happyDataSet as $value) {
			$dateset = new RecordSet();
			$keyValue =$value[0].'/'.$value[1]
			$dataset->yearAndMonth = $keyValue;
			$dataset->happy = $value[2];
			$output[] = $dataset;
			# code...
		}
		foreach ($goodDataset as $goodDateRecord) {
			$tempYearAndMonth = $goodDateRecord[0].'/'.$goodDateRecord[1];
			$i =0; $j=-1;
			foreach ($output as  $outputRecord) {
				if($outputRecord->yearAndMonth == $goodDateRecord[0){
					$j = $i;
				}
				$i++;
			}
			
			if($j!=-1){
				$output[$j]->good = $goodDateRecord[2];	
				break;	
			} else{
				$dateset = new RecordSet();
				$dataset->yearAndMonth = $tempYearAndMonth;
				$dataset->good = $goodDateRecord[2];
				$output[] = $dataset;
			}
						
		}

		foreach ($sosoDataset as $sosoDateRecord) {
			$tempYearAndMonth = $sosoDateRecord[0].'/'.$sosoDateRecord[1];
			$i =0; $j=-1;
			foreach ($output as  $outputRecord) {
				if($outputRecord->yearAndMonth == $sosoDateRecord[0){
					$j = $i;
				}
				$i++;
			}
			
			if($j!=-1){
				$output[$j]->soso = $sosoDateRecord[2];	
				break;	
			} else{
				$dateset = new RecordSet();
				$dataset->yearAndMonth = $tempYearAndMonth;
				$dataset->soso = $sosoDateRecord[2];
				$output[] = $dataset;
			}
						
		}

		foreach ($badDataset as $badDateRecord) {
			$tempYearAndMonth = $badDateRecord[0].'/'.$badDateRecord[1];
			$i =0; $j=-1;
			foreach ($output as  $outputRecord) {
				if($outputRecord->yearAndMonth == $badDateRecord[0){
					$j = $i;
				}
				$i++;
			}
			
			if($j!=-1){
				$output[$j]->bad = $badDateRecord[2];	
				break;	
			} else{
				$dateset = new RecordSet();
				$dataset->yearAndMonth = $tempYearAndMonth;
				$dataset->bad = $badDateRecord[2];
				$output[] = $dataset;
			}
						
		}



		return $this->generateData($output);
		
	}


	private function getHappyRecords($from, $to  ){
		return $this->countingData(4, $from, $to);

	}
	private function getHappyRecordsAll( ){
		return $this->countingDataall(4);

	}
	private function getHappyRecordsToday( ){
		return $this->countingData(4,$this->now,$this->now);

	}

	private function getGoodRecords($from, $to  ){
		return $this->countingData(3, $from, $to);		
	}
	private function getGoodRecordsAll(){
		return $this->countingDataall(3);		
	}
	private function getGoodRecordsToday(){
		return $this->countingDataall(3,$this->now,$this->now);		
	}
	
	private function getSosoRecords($from, $to  ){
		return $this->countingData(2, $from, $to);			
	}
	private function getSosoRecordsAll(){
		return $this->countingDataall(2);			
	}
	private function getSosoRecordsToday(){
		return $this->countingDataall(2,$this->now,$this->now);			
	}

	private function getBadRecords($from, $to  ){
		return $this->countingData(1, $from, $to);		
	}

	private function getBadRecordsAll(){
		return $this->countingDataall(1);		
	}
	private function getBadRecordsToday(){
		return $this->countingDataall(2,$this->now,$this->now);			
	}

	private function databaseConnection() {
		// connection already opened
		if ($this->db_connection != null) {
			return true;
		} else {
			// create a database connection, using the constants from config/config.php
			try {
				$this->db_connection = new PDO ( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS );
				return true;
				// If an error is catched, database connection failed
			} catch ( PDOException $e ) {
				//$this->errors [] = $this->lang ['Database error'];
				return false;
			}
		}
	}
	public static function dateToDB($date) {
		$date = str_replace ( '/', '-', $date );
		$date = date ( 'Y-m-d', strtotime ( $date ) );
		return $date;
	}
	public static function dbToDate($date) {
		$date = date ( 'd-m-Y', strtotime ( $date ) );
		$date = str_replace ( '-', '/', $date );
		return $date;
	}

	public function generateData($dataSet)
	{


		$output= "Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Stacked column chart'
    },
    xAxis: {
        categories: [";
        $count = count($dataSet);
        for ($i=0 ; $i<$count ; $i++) {
        	$temp ="";
        	if ($i!= $count-1) {
        		$temp = "\",\"";
        	} 
        	$output.=$dataSet[$i]->yearAndMonth.$temp;
        }
               
        $output.=
        "]
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Level of Student satisfaction'
        }
    },
    tooltip: {
        pointFormat: '<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
        shared: true
    },
    plotOptions: {
        column: {
            stacking: 'percent'
        }
    },
    series: [
    {   name: 'Happy',
        data: [";
        
        for ($i=0; $i < $count ; $i++) { 
        	$temp ="";
        	if ($i!= $count-1) {
        		$temp = "\",\"";
        	} 
        	$output.=$happy[$i].$temp;	
        }
      
        

        $output.=
        "]
    }, 
    {
        name: 'Good',
        data: [";
         for ($i=0; $i < $count ; $i++) { 
        	$temp ="";
        	if ($i!= $count-1) {
        		$temp = "\",\"";
        	} 
        	$output.=$good[$i].$temp;	
        }
      
		$output.=
        "]
    },
    {
        name: 'Soso',
        data: [";
     	for ($i=0; $i < $count ; $i++) { 
        	$temp ="";
        	if ($i!= $count-1) {
        		$temp = "\",\"";
        	} 
        	$output.=$soso[$i].$temp;	
        }
      
		$output.=
        "]
    },  
    {
        name: 'Bad',
        data: [";
       for ($i=0; $i < $count ; $i++) { 
        	$temp ="";
        	if ($i!= $count-1) {
        		$temp = "\",\"";
        	} 
        	$output.=$bad[$i].$temp;	
        }
        $output.=
        "

        ]
    }]
});" ;
		return $output;
	}
}

?>
