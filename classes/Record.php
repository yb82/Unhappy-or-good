<?php

class Record{
	
	
	private $dateTime;
	private $db_connection = null;


	public function addRecord($rate){
		if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 'insert into happyornot (`rate`) values (:rate)' );
			
			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			$success=$query->execute ();
		}
	}
	private function countingData($rate, $from , $to){
			if ($this->databaseConnection ()) {
			$query = $this->db_connection->prepare ( 'select count(*) from happyornot where `rate` = :rate and `timestamp`>= :fromdate  and `timestamp`<= :to' );
			$query->bindValue ( ':rate', $rate, PDO::PARAM_INT );
			$query->bindValue ( ':from', $this->dateToDB($fromdate), PDO::PARAM_String );
			$query->bindValue ( ':to', $this->dateToDB($to), PDO::PARAM_String );
			$success=$query->execute ();

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
	public function getHappyRecords($from, $to = now(); ){
		return $this->countingData(4, $from, $to)

	}
	public function getGoodRecords($from, $to = now(); ){
		return $this->countingData(3, $from, $to)		
	}
	public function getBadRecords($from, $to = now(); ){
		return $this->countingData(2, $from, $to)			
	}
	public function getAwfulRecords($from, $to = now(); ){
		return $this->countingData(1, $from, $to)		
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
}

?>
