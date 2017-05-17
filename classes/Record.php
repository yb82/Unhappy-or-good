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
			} else return 0 ;

		}	

	}
private function countingTodayData($rate){
			if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 
				'SELECT YEAR( TIMESTAMP ) , MONTH( TIMESTAMP ) , COUNT(*) 
				FROM happyornot
				WHERE rate =:rate
				AND  DATE(`timestamp`) = CURDATE()
				GROUP BY YEAR( TIMESTAMP ) , MONTH( TIMESTAMP ) 
				');
			//echo $from." ". $this->dateToDB( $from)." 00:00:00"."<br/>"; 
			//echo $to." ".$this->dateToDB($to)." 23:59:59"."<br/>"; 

			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
		
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
			$query = $this->db_connection->prepare ( 'SELECT  YEAR(timestamp), MONTH(timestamp), count( * ) FROM `happyornot` WHERE `rate` = :rate AND `timestamp` <= NOW( ) group by YEAR(timestamp), MONTH(timestamp)' );
			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			$success=$query->execute ();

			
			$results = $query->fetchAll ();
			
			//$j = 0;
			if (count ( $results ) > 0) {
				
				
					return 	$results;								
				
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
		/*
		echo "<br/>happy".$from." ".$to."<br/>" ;
		print_r($happyDataSet);
		echo "<br/>";
*/
		foreach ($happyDataSet as $key => $value) {
			$dataset = new RecordSet();
			//echo $dataset->happy;

			$keyValue =$key.'/'.$value[0];
			//echo "<br/>".$keyValue."<br/>";
			$dataset->yearAndMonth = $keyValue;
			//echo "<br/>".$value[2]."<br/>";
			$dataset->happy = $value[2];
			$this->outputData[$keyValue] = $dataset;
			# code...
		}
		foreach ($goodDataset as $goodDateRecord) {
			$tempYearAndMonth = $goodDateRecord[0].'/'.$goodDateRecord[1];
			$i =0; $j=-1;
			foreach ($this->outputData as  $outputRecord) {
				if($outputRecord->yearAndMonth == $goodDateRecord[0]){
					$j = $i;
				}
				$i++;
			}
			
			if($j!=-1){
				$output[$j]->good = $goodDateRecord[2];	
				break;	
			} else{
				$dataset = new RecordSet();
				$dataset->yearAndMonth = $tempYearAndMonth;
				$dataset->good = $goodDateRecord[2];
				$this->outputData[$tempYearAndMonth] = $dataset;
			}
						
		}

		foreach ($sosoDataset as $sosoDateRecord) {
			$tempYearAndMonth = $sosoDateRecord[0].'/'.$sosoDateRecord[1];
			$i =0; $j=-1;
			foreach ($this->outputData as  $outputRecord) {
				if($outputRecord->yearAndMonth == $sosoDateRecord[0]){
					$j = $i;
				}
				$i++;
			}
			
			if($j!=-1){
				$output[$j]->soso = $sosoDateRecord[2];	
				break;	
			} else{
				$dataset = new RecordSet();
				$dataset->yearAndMonth = $tempYearAndMonth;
				$dataset->soso = $sosoDateRecord[2];
				$this->outputData[$tempYearAndMonth] = $dataset;
			}
						
		}

		foreach ($badDataset as $badDateRecord) {
			$tempYearAndMonth = $badDateRecord[0].'/'.$badDateRecord[1];
			$i =0; $j=-1;
			foreach ($this->outputData as  $outputRecord) {
				if($outputRecord->yearAndMonth == $badDateRecord[0]){
					$j = $i;
				}
				$i++;
			}
			
			if($j!=-1){
				$output[$j]->bad = $badDateRecord[2];	
				break;	
			} else{
				$dataset = new RecordSet();
				$dataset->yearAndMonth = $tempYearAndMonth;
				$dataset->bad = $badDateRecord[2];
				$this->outputData[$tempYearAndMonth] = $dataset;
			}
						
		}

		

		return $this->generateData();
	}
	public  function createTodayData(){
		$happyDataSet = $this->getHappyRecordsToday();
		$goodDataset = $this->getGoodRecordsToday();
		$sosoDataset = $this->getSosoRecordsToday();
		$badDataset = $this->getBadRecordsToday();
		$output= "
    chart: {
        type: 'column'
    },
    title: {
        text: 'Stacked column chart'
    },
    xAxis: {
        categories: [\"Today\"]
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
        data: [".$happyDataSet."]
    }, 
    {
        name: 'Good',
        data: [".	$goodDataset.       "]
    },
    {
        name: 'Soso',
        data: [".$sosoDataset. "]
    },  
    {
        name: 'Bad',
        data: [".$badDataset. "]
    }],";
		return $output;
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
		
		//echo "<br/> happy:";
		//print_r($happyDataSet)."<br/>";
		if(count($happyDataSet) != 0){
			
		foreach ($happyDataSet as $key => $value) {
			$dataset = new RecordSet();
			//echo $dataset->happy;
			$keyValue =$value[0].'/'.$value[1];
			//echo $keyValue;
			$dataset->yearAndMonth = $keyValue;
			$dataset->happy = $value[2];
			$output[$keyValue] = $dataset;
			//print_r($output);
			# code...
		}
		}
		if(count($goodDataset)){
		foreach ($goodDataset as $goodDateRecord) {
			$tempYearAndMonth = $goodDateRecord[0].'/'.$goodDateRecord[1];
			$i =0; $j=-1;
			foreach ($output as  $outputRecord) {
				if($outputRecord->yearAndMonth == $goodDateRecord[0]){
					$j = $i;
				}
				$i++;
			}
			
			if($j!=-1){
				$output[$j]->good = $goodDateRecord[2];	
				break;	
			} else{
				$dataset = new RecordSet();
				$dataset->yearAndMonth = $tempYearAndMonth;
				$dataset->good = $goodDateRecord[2];
				$output[$tempYearAndMonth] = $dataset;
			}
						
		}
	}
if(count($sosoDataset)){
		foreach ($sosoDataset as $sosoDateRecord) {
			$tempYearAndMonth = $sosoDateRecord[0].'/'.$sosoDateRecord[1];
			$i =0; $j=-1;
			foreach ($output as  $outputRecord) {
				if($outputRecord->yearAndMonth == $sosoDateRecord[0]){
					$j = $i;
				}
				$i++;
			}
			
			if($j!=-1){
				$output[$j]->soso = $sosoDateRecord[2];	
				break;	
			} else{
				$dataset = new RecordSet();
				$dataset->yearAndMonth = $tempYearAndMonth;
				$dataset->soso = $sosoDateRecord[2];
				$output[$tempYearAndMonth] = $dataset;
			}
						
		}
}
if(count($badDataset)){
		foreach ($badDataset as $badDateRecord) {
			$tempYearAndMonth = $badDateRecord[0].'/'.$badDateRecord[1];
			$i =0; $j=-1;
			foreach ($output as  $outputRecord) {
				if($outputRecord->yearAndMonth == $badDateRecord[0]){
					$j = $i;
				}
				$i++;
			}
			
			if($j!=-1){
				$output[$j]->bad = $badDateRecord[2];	
				break;	
			} else{
				$dataset = new RecordSet();
				$dataset->yearAndMonth = $tempYearAndMonth;
				$dataset->bad = $badDateRecord[2];
				$output[$tempYearAndMonth] = $dataset;
			}
						
		}
}


		return $this->generateData($output);
		
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

		$output= "
    chart: {
        type: 'column'
    },
    title: {
        text: 'Stacked column chart'
    },
    xAxis: {
        categories: [";
        $i=0;
        $count = count($outputData);
        foreach ($outputData as $key => $value) {
        	$temp ="\"";
        	if ($i< $count-1) {
        		$temp = "\",";
        	} 
        	 $output.="\"".$key.$temp;
        	 $i++;
        
        
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
        
       $i=0;
       
        foreach ($outputData as $key => $value) {
        	$temp ="";
        	if ($i< $count-1) {
        		$temp = ",";
        	} 
        	 $output.=$value->happy.$temp;
        
        $i++;
        }

        $output.=
        "]
    }, 
    {
        name: 'Good',
        data: [";
        
          $i=0;
       
        foreach ($outputData as $key => $value) {
        	$temp ="";
        	if ($i< $count-1) {
        		$temp = ",";
        	} 
        	 $output.=$value->good.$temp;
        $i++;
        
        }

        
      
		$output.=
        "]
    },
    {
        name: 'Soso',
        data: [";
         
     	 $i=0;
       
        foreach ($outputData as $key => $value) {
        	$temp ="";
        	if ($i< $count-1) {
        		$temp = ",";
        	} 
        	 $output.=$value->soso.$temp;
        $i++;
        
        }

        
      
		$output.=
        "]
    },  
    {
        name: 'Bad',
        data: [";
         
        $i=0;
       
        foreach ($outputData as $key => $value) {
        	$temp ="";
        	if ($i< $count-1) {
        		$temp = ",";
        	} 
        	 $output.=$value->bad.$temp;
        
        $i++;
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
