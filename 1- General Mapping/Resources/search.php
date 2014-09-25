<?php
/**  
 * @author German Dosko
 * @version Sep 04 2014
 */

class Search
{
	
	/**
	* Search provider  
	* @param  array	
	* @return array
	*/
	public function searchProvider($providerSearched){
		
		$clean = new Clean();

		$record_id= $providerSearched["record_id"];
		$firstName = $providerSearched["FirstName"];
		$lastName = $providerSearched["LastName"];
		$practiceName = $providerSearched["PracticeName"];
		$adressFull = $providerSearched["Street"];
		$city = $providerSearched["addressLocality"];
		$zipcode = $providerSearched["postalCodechar"];
		$phone = $providerSearched["Phone"];

		//$providerNamePorcent = addPorcent($providerName);

		//$adressFullPorcent = cleanAddress($adressFull);
		//$adressFullPorcent = addPorcent($adressFullPorcent);

		$adressFullPorcent = $clean->cleanAddress($adressFull);
		$adressFullPorcent = $clean->addPorcent($adressFullPorcent);

		$phoneLike = $clean->cleanPhone($phone);
		$phoneLike = $clean->addPorcent($phoneLike);
		
		//$addressFirst = deleteSecondAddress($adressFull);
		//$addressFirst = cleanAddress($addressFirst);	
		//$addressFirstPorcent = addPorcent($addressFirst);

		//$firstNamePorcent = addPorcent($firstName);


		//$likeGeneralName = $firstName.' '.$middleName.' '.$lastName;
		//$likeGeneralName = addPorcent($likeGeneralName);

		$addressFirstNumber = $clean->deleteNumberAddress($adressFull);
		$addressFirstNumber = $clean->addPorcent($addressFirstNumber);
		//$adress_2Porcent = cleanAddress($adress_2);
		//$adress_2Porcent = addPorcent($adress_2Porcent);

		
		$association = '1-equalFirstName+equalLastName+igualAddres+equalCity+equalZipcode+equalPhone';
		$query = 'SELECT * FROM '.TABLE_PROVIDER_SERVER.' WHERE first_name="'. $firstName .'" AND last_name="'. $lastName .'" AND concat_ws(" ", address, address_2) = "'. $adressFull .'" AND city="'. $city .'" AND zipcode="'. $zipcode .'" AND phone_number="'. $phoneLike .'"';
		$result = $this->querySearch($query, $record_id, $association);
		if($result == 0){
			$association = '2-equalFirstName+equalLastName+igualAddres+equalCity+equalZipcode';
			$query = 'SELECT * FROM '.TABLE_PROVIDER_SERVER.' WHERE first_name="'. $firstName .'" AND last_name="'. $lastName .'" AND concat_ws(" ", address, address_2) = "'. $adressFull .'" AND city="'. $city .'" AND zipcode="'. $zipcode .'"';
			$result = $this->querySearch($query, $record_id, $association);
			if($result == 0){
				$association = '3-equalFirstName+equalLastName+likeAddres+equalCity+equalZipcode';
				$query = 'SELECT * FROM '.TABLE_PROVIDER_SERVER.' WHERE first_name="'. $firstName .'" AND last_name="'. $lastName .'" AND concat_ws(" ", address, address_2) LIKE "'. $adressFullPorcent .'" AND city="'. $city .'" AND zipcode="'. $zipcode .'"';				
				$result = $this->querySearch($query, $record_id, $association);
				if($result == 0){
					$association = '4-ADDRESS WITHOUT NUMBER-equalFirstName+equalLastName+likeAddres+equalCity+equalZipcode';
					$query = 'SELECT * FROM '.TABLE_PROVIDER_SERVER.' WHERE first_name="'. $firstName .'" AND last_name="'. $lastName .'" AND concat_ws(" ", address, address_2) LIKE "'. $addressFirstNumber .'" AND city="'. $city .'" AND zipcode="'. $zipcode .'"';				
					$result = $this->querySearch($query, $record_id, $association);
					if($result == 0){
						$association = '5-UNIQUE PROVIDER IN CITY-equalFirstName+equalLastName+equalCity';
						$query = 'SELECT * FROM '.TABLE_PROVIDER_SERVER.' WHERE first_name="'. $firstName .'" AND last_name="'. $lastName .'" AND city="'. $city .'"';				
						$result = $this->querySearch($query, $record_id, $association);
						if($result == 0){
							$association = '6-UNIQUE PROVIDER-equalFirstName+equalLastName';
							$query = 'SELECT * FROM '.TABLE_PROVIDER_SERVER.' WHERE first_name="'. $firstName .'" AND last_name="'. $lastName .'"';				
							$result = $this->querySearch($query, $record_id, $association);
						}
					}
				}		
			}	
		}
		
		
		/*
		$association = '5- UNIQUE PROVIDER IN CITY without middle name- IgualFirstName+LikeGeneralName+IgualLastName+igualCity';
		$query = 'SELECT DISTINCT provider_id FROM '.$table_to_control.' WHERE first_name = "'. $firstName .'" AND last_name = "'. $lastName .'" AND provider_name LIKE "'. $likeGeneralName .'" AND city="'. $city .'"';
		$result = querySearchUnique($query, $record_id, $association);
		if($result == 0){
			$association = '6- UNIQUE PROVIDER- IgualFirstName+LikeGeneralName+IgualLastName+igualCity';
			$query = 'SELECT DISTINCT provider_id FROM '.$table_to_control.' WHERE first_name = "'. $firstName .'" AND last_name = "'. $lastName .'" AND provider_name LIKE "'. $likeGeneralName .'"';
			$result = querySearchUnique($query, $record_id, $association);
			if($result == 0){
				$association = '7-UNIQUE PROVIDER IN CITY without middle name- IgualFirstName+LikeGeneralName+IgualLastName+igualCity';
				$query = 'SELECT DISTINCT provider_id FROM '.$table_to_control.' WHERE first_name = "'. $firstName .'" AND last_name = "'. $lastName .'" AND city="'. $city .'"';
				$result = querySearchUnique($query, $record_id, $association);
				if($result == 0){
					$association = '8-UNIQUE PROVIDER without middle name- IgualFirstName+IgualLastName';
					$query = 'SELECT DISTINCT provider_id FROM '.$table_to_control.' WHERE first_name = "'. $firstName .'" AND last_name = "'. $lastName .'"';
					$result = querySearchUnique($query, $record_id, $association);						
				}							
			}						
		}
		*/	
		
		return $result;
	}	

	/**
	* Search the register found 
	* @param string
	* @return array
	*/
	protected function query($query){
		$connection = new Connection(MYSQL_DB_2);
		$result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());
		$resultArray = array();
		while ($row = mysql_fetch_assoc($result)) {
			array_push($resultArray, $row);
		}
		$connection->close();
		return $resultArray;
	}

		/**
	* Search all the providers searched
	* @return array
	*/
	public function findProviders(){	
		$query = "SELECT * FROM ". TABLE_INFO;
		return $this->query($query);
	}

	/**
	* Search the register found 
	* @return array
	*/
	public function findRegistersFound(){
		$query = 'SELECT record_id FROM '.TABLE_RESULT;
		$result = $this->query($query);
		$num = count($result);
		$array_id = array();
		for ($i=0; $i <= $num ; $i++) {
			array_push($array_id, $result[$i]['record_id']);
		}
		return $array_id;
	}

	/**
	* QuerySearch
	* @param 
	* @return 
	*/
	function querySearch($query, $record_id, $association){
		
		$tempProviderId = '';	
		$sameProvider = true;
		$connection = new Connection(MYSQL_DB_1);
		$result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());
		$connection->close();
		
		$connection = new Connection(MYSQL_DB_2);
		$numFound = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$numFound += 1; 
			if($tempProviderId == ''){
				$tempProviderId = $row["provider_id"];
			}
			$providerIdTemp = $row["provider_id"];
			$practiceIdTemp = $row["practice_id"];
			$fullNameTemp = $row["provider_name"];
			if($tempProviderId != $row["provider_id"]){
				$sameProvider = false;	
			}			
		}
		if($numFound == 1){
			echo 'ID INSERTADO:'.$record_id.'</br>';
			$queryInsert = 'INSERT INTO '.TABLE_RESULT.' (record_id, provider_id, practice_id, full_Name, association_type) VALUES ("'.$record_id.'", "'.$providerIdTemp.'", "'.$practiceIdTemp.'", "'.$fullNameTemp.'", "'.$association.'")';
			$result = mysql_query($queryInsert) or die('Insert fallida: ' . mysql_error());
		} elseif ($numFound > 1 && $sameProvider == true){
			$queryInsert = 'INSERT INTO '.TABLE_RESULT.' (record_id, provider_id, practice_id, full_Name, association_type) VALUES ("'.$record_id.'", "'.$providerIdTemp.'", "'.$practiceIdTemp.'",  "'.$fullNameTemp.'", "'.$association.'")';
			$result = mysql_query($queryInsert) or die('Insert fallida: ' . mysql_error());
		}

		$connection->Close();
		return $numFound;
		
	}

}