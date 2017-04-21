<?php

require_once('../config/config.php');

class Record{
	
	
	private $now;

	private $db_connection = null;
	public function __construct(){
		$now = date("Y-m-d");
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
	private function countingData($rate, $from , $to){
			if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 'SELECT count( * )FROM `happyornot` WHERE `rate` = :rate AND `timestamp` >= :from  and `timestamp` <= :to' );
			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			$query->bindValue ( ':from', $this->dateToDB($from), PDO::PARAM_STR );
			$query->bindValue ( ':to', $this->dateToDB($to)." 23:59:59", PDO::PARAM_STR );
						
			$query->execute ();
			$results = $query->fetchAll ();
			
			//$j = 0;
			if (count ( $results ) > 0) {
				
				foreach ( $results as $result ) {
					return 	$result[0];								
				}
			} else return 0 ;

		}	

	}

	private function countingDataall($rate){
			if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 'SELECT count( * )FROM `happyornot` WHERE `rate` = :rate AND `timestamp` <= NOW( )' );
			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			$success=$query->execute ();

			
			$results = $query->fetchAll ();
			
			//$j = 0;
			if (count ( $results ) > 0) {
				
				foreach ( $results as $result ) {
					return 	$result[0];								
				}
			} else return 0 ;

		}	

	}
	public function getHappyRecords($from, $to  ){
		return $this->countingData(4, $from, $to);

	}
	public function getHappyRecords1( ){
		return $this->countingData(4, $from, $to);

	}
	public function getGoodRecords($from, $to  ){
		return $this->countingData(3, $from, $to);		
	}
	public function getBadRecords($from, $to  ){
		return $this->countingData(2, $from, $to);			
	}
	public function getAwfulRecords($from, $to  ){
		return $this->countingData(1, $from, $to);		
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
	public function generateData($rate, $from, $to)
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

        $output.= ;//#todo: fill month data
        
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
        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
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
        $output.= ;#todo: add counter of happiness data;
        

        $output.=
        "]
    }, 
    {
        name: 'Good',
        data: [";
        $output.= ; #todo: add counter of good data;
		
		$output.=
        "]
    },
    {
        name: 'Soso',
        data: [";
        $output.= ; #todo: add counter of good data;
		$output.=
        "]
    },  
    {
        name: 'Bad',
        data: [";
        $output.= ;#todo: add counter of good data;
        $output.=
        "

        ]
    }]
});"
		return 
	}
}

?>
