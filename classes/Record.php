<?php

require_once('../config/config.php');
require_once('classes/RecordSet.php');

class Record{
	
	
	private $now;
	private $outputData;

	private $db_connection = null;
	public function __construct(){
		$now = date("d-m-Y");
		$now = $this->dbToDate($now);
	}


	public function addRecord($rate){
		
		if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 'insert into happyornot (`rate`) values (:rate)' );
			

			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			//$query->bindValue ( ':rate', $this->now, PDO::PARAM_STR);
			$success=$query->execute ();
			return $success;
		}
	}
	public function addRecordDate($rate,$date){
		
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
			$query = $this->db_connection->prepare ( 
				'SELECT YEAR( TIMESTAMP ) , MONTH( TIMESTAMP ) , COUNT(*) 
				FROM happyornot
				WHERE rate =:rate
				AND  `timestamp` >=  :from
				AND  `timestamp` <=  :to
				GROUP BY YEAR( TIMESTAMP ) , MONTH( TIMESTAMP ) 
				');
			//echo $from." ". $this->dateToDB( $from)." 00:00:00"."<br/>"; 
			//echo $to." ".$this->dateToDB($to)." 23:59:59"."<br/>"; 

			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			$query->bindValue ( ':from', $this->dateToDB( $from)." 00:00:00", PDO::PARAM_STR );
			$query->bindValue ( ':to', $this->dateToDB($to)." 23:59:59", PDO::PARAM_STR );

			$query->execute ();
			$results = $query->fetchAll ();
			
			//$j = 0;
			if (count ( $results ) > 0) {
				
				return $results;
			} else return null ;

		}	

	}
	private function countingTodayData($rate){
		if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 
				'SELECT COUNT(*) 
				FROM happyornot
				WHERE rate =:rate
				AND  DATE(`timestamp`) = CURDATE()
				
				');
			//echo $from." ". $this->dateToDB( $from)." 00:00:00"."<br/>"; 
			//echo $to." ".$this->dateToDB($to)." 23:59:59"."<br/>"; 

			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );

			$query->execute ();
			$results = $query->fetchAll ();
			
			//$j = 0;
			if (count ( $results ) > 0) {
				
				return $results[0][0];
			} else return 0 ;

		}	

	}
	private function countingDataall($rate){
		if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 'SELECT  count( * ) FROM `happyornot` WHERE `rate` = :rate AND DATE(`timestamp`) <= NOW( ) ' );
			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			$success=$query->execute ();

			
			$results = $query->fetchAll ();
			
			//$j = 0;
			if (count ( $results ) > 0) {
				
				
				return 	$results[0][0];								
				
			} else return 0 ;

		}	

	}
	public function createRangeData($from, $to)
	{
		//echo $from." from to <br/>".$to;
		$output =array();
		$happyDataSet;
		$goodDataset;
		$sosoDataset;
		$badDataset;
		

		$happyDataSet = $this->getHappyRecords($from,$to);
		$goodDataset = $this->getGoodRecords($from,$to);
		$sosoDataset = $this->getSosoRecords($from,$to);
		$badDataset = $this->getBadRecords($from,$to);
		
		//echo "<br/>happy".$from." ".$to."<br/>" ;
		//print_r($happyDataSet);
		echo "<br/>";

		print_r($happyDataSet);
		echo "<br/>";
		print_r($goodDataset);
		echo "<br/>";
		print_r($sosoDataset);
		echo "<br/>";
		print_r($badDataset);

		if(!is_null($happyDataSet)){




			foreach ($happyDataSet as $key => $value) {
				$dataset = new RecordSet();
				//echo $dataset->happy;

				$keyValue =$value[0].'/'.$value[1];
				//echo "<br/>".$key."  ".$keyValue."<br/>";
				$dataset->yearAndMonth = $keyValue;
				//echo "<br/>".$value[2]."<br/>";
				$dataset->happy = (int)$value[2];
				$this->outputData[$keyValue] = $dataset;
			# code...
			}
		}
		if(!is_null($goodDataset)){
			foreach ($goodDataset as $goodDateRecord) {
				$tempYearAndMonth = $goodDateRecord[0].'/'.$goodDateRecord[1];
				$i =0; $j=-1;
				foreach ($this->outputData as  $outputRecord) {
					if($outputRecord->yearAndMonth == $tempYearAndMonth){
						$j = $i;
					}
					$i++;
				}

				if($j!=-1){
					$output[$j]->good = (int)$goodDateRecord[2];	
					break;	
				} else{
					$dataset = new RecordSet();
					$dataset->yearAndMonth = $tempYearAndMonth;
					$dataset->good = (int)$goodDateRecord[2];
					$this->outputData[$tempYearAndMonth] = $dataset;
				}

			}
		}
		if(!is_null($sosoDataset)){
			foreach ($sosoDataset as $sosoDateRecord) {
				$tempYearAndMonth = $sosoDateRecord[0].'/'.$sosoDateRecord[1];
				$i =0; $j=-1;
				foreach ($this->outputData as  $outputRecord) {
					if($outputRecord->yearAndMonth == $tempYearAndMonth){
						$j = $i;
					}
					$i++;
				}

				if($j!=-1){
					$output[$j]->soso = (int)$sosoDateRecord[2];	
					break;	
				} else{
					$dataset = new RecordSet();
					$dataset->yearAndMonth = $tempYearAndMonth;
					$dataset->soso =(int) $sosoDateRecord[2];
					$this->outputData[$tempYearAndMonth] = $dataset;
				}

			}
		}
		if(!is_null($badDataset)){
			foreach ($badDataset as $badDateRecord) {
				$tempYearAndMonth = $badDateRecord[0].'/'.$badDateRecord[1];
				$i =0; $j=-1;
				foreach ($this->outputData as  $outputRecord) {
					if($outputRecord->yearAndMonth == $tempYearAndMonth){
						$j = $i;
					}
					$i++;
				}

				if($j!=-1){
					$output[$j]->bad =(int) $badDateRecord[2];	
					break;	
				} else{
					$dataset = new RecordSet();
					$dataset->yearAndMonth = $tempYearAndMonth;
					$dataset->bad =(int) $badDateRecord[2];
					$this->outputData[$tempYearAndMonth] = $dataset;
				}

			}
		}
		$out = array( );
		foreach ($this->outputData as $key => $value) {
			$out[]= $value->yearAndMonth;
		}
		$happyOutput = array();
		$goodOutput = array();
		$sosoOutput = array();
		$badOutput = array();

		foreach ($this->outputData as $key => $value) {
			$happyOutput[]= $value->happy;
		}
		foreach ($this->outputData as $key => $value) {
			$goodOutput[]= $value->good;
		}
		foreach ($this->outputData as $key => $value) {
			$sosoOutput[]= $value->soso;
		}
		foreach ($this->outputData as $key => $value) {
			$badOutput[]= $value->bad;
		}
		$allOutput[]=array("name" => "Happy", "data" => $happyOutput );
		$allOutput[]=array("name" => "Good", "data" => $goodOutput );
		$allOutput[]=array("name" => "Soso", "data" => $sosoOutput );
		$allOutput[]=array("name" => "Bad", "data" => $badOutput );

		$returnOutput =array();
		$returnOutput["categories"]=$out;
		$returnOutput["series"] = $allOutput;

		//sort($this->outputData);
		//print_r($returnOutput);
		return json_encode($returnOutput);
	}
	public function getCategory(){
		$out = array( );
		foreach ($this->outputData as $key => $value) {
			$out[]= $value[0]->yearAndMonth;
		}
		return json_encode($out);
	}
	public function getSeriesData(){
		$allOutput = array( );
		$happyOutput = array();
		$goodOutput = array();
		$sosoOutput = array();
		$badOutput = array();

		foreach ($this->outputData as $key => $value) {
			$happyOutput[]= $value[0]->happy;
		}
		foreach ($this->outputData as $key => $value) {
			$goodOutput[]= $value[0]->good;
		}
		foreach ($this->outputData as $key => $value) {
			$sosoOutput[]= $value[0]->soso;
		}
		foreach ($this->outputData as $key => $value) {
			$badOutput[]= $value[0]->bad;
		}
		$allOutput[]=array("name" => "Happy", "data" => array($happyOutput) );
		$allOutput[]=array("name" => "Good", "data" => array($goodOutput) );
		$allOutput[]=array("name" => "Soso", "data" => array($sosoOutput) );
		$allOutput[]=array("name" => "Bad", "data" => array($badOutput) );

		return json_encode($allOutput);
	}
	public  function createTodayData(){
		$happyDataSet = $this->getHappyRecordsToday();
		$goodDataset = $this->getGoodRecordsToday();
		$sosoDataset = $this->getSosoRecordsToday();
		$badDataset = $this->getBadRecordsToday();
		
		$output= array(); 
		$output[]= array("name" => "Happy", "data" => array($happyDataSet) );
		$output[] = array("name" => "Good", "data" => array($goodDataset));
		$output[] =  array("name" => "Soso", "data" => array($sosoDataset ));
		$output[] =  array("name" => "Bad", "data" => array($badDataset ));
		

		return json_encode($output);
	}


	public  function createAllData()
	{
		//echo $tag." tag <br/>";
		$output=array();
		$happyDataSet;
		$goodDataset;
		$sosoDataset;
		$badDataset;
		//echo " <br/>";

		
		$happyDataSet = $this->getHappyRecordsAll();
		$goodDataset = $this->getGoodRecordsAll();
		$sosoDataset = $this->getSosoRecordsAll();
		$badDataset = $this->getBadRecordsAll();
		
		$output= array(); 
		$output[]= array("name" => "Happy", "data" => array($happyDataSet) );
		$output[] = array("name" => "Good", "data" => array($goodDataset));
		$output[] =  array("name" => "Soso", "data" => array($sosoDataset ));
		$output[] =  array("name" => "Bad", "data" => array($badDataset ));
		

		return json_encode($output);
		
	}


	public function getHappyRecords($from, $to  ){
		return $this->countingData(4, $from, $to);

	}
	private function getHappyRecordsAll( ){
		return $this->countingDataall(4);

	}
	private function getHappyRecordsToday( ){
		return $this->countingTodayData(4);

	}

	private function getGoodRecords($from, $to  ){
		return $this->countingData(3, $from, $to);		
	}
	private function getGoodRecordsAll(){
		return $this->countingDataall(3);		
	}
	private function getGoodRecordsToday(){
		return $this->countingTodayData(3);		
	}
	
	private function getSosoRecords($from, $to  ){
		return $this->countingData(2, $from, $to);			
	}
	private function getSosoRecordsAll(){
		return $this->countingDataall(2);			
	}
	private function getSosoRecordsToday(){
		return $this->countingTodayData(2);			
	}

	private function getBadRecords($from, $to  ){
		return $this->countingData(1, $from, $to);		
	}

	private function getBadRecordsAll(){
		return $this->countingDataall(1);		
	}
	private function getBadRecordsToday(){
		return $this->countingTodayData(1);			
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

	public function generateData($outputData)
	{
		$count = count($outputData);
 		//print_r($outputData);
 		//echo $count;
 		/*if ($count) {
 			# code...
 			echo $count;
 		}*/
 		/*
 		$output_array= array();
 		$output_array[]= array('chart' => array('type'=>'column') );
 		$output_array[]= array('title' => array('text'=>'Stacked column chart') );
 		$categories= array();
 		foreach ($outputData as $key => $value) {
 			$categories[]= $value->yearAndMonth;
 		}
 		$output_array[]= array('xAxis' => array('categories'=>$categories) );
 		$output_array[]= array('yAxis' => array('min'=>0, 'title'=>"Level of Student satisfaction") );
 		$output_array[]= array('tooltip' => array('pointFormat'=>'<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>', 'shared'=>"true") );
 		$output_array[]= array('plotOptions' => array('column'=>array('stacking'=>'percent')) );
 		$hapypickData = array();
 		foreach ($outputData as $key => $value) {
 			$hapypickData[]= $value->happy;
 			# code...
 		}
 		$goodpickData = array();
 		foreach ($outputData as $key => $value) {
 			$goodpickData[]= $value->good;
 			# code...
 		}
 		$sosopickData = array();
 		foreach ($outputData as $key => $value) {
 			$sosopickData[]= $value->soso;
 			# code...
 		}
 		$badpickData = array();
 		foreach ($outputData as $key => $value) {
 			$badpickData[]= $value->bad;
 			# code...
 		}

 		$output_array[]= array('series' => array('name'=>'Happy', 'data'=>$hapypickData),array('name'=>'Good', 'data'=>$goodpickData),
 			array('name'=>'Soso', 'data'=>$sosopickData),
 			array('name'=>'Bad', 'data'=>$badpickData));*/
 		 
 return $output_array;
}
}

?>
