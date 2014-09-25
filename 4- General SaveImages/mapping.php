<?php
/**
* @author           Germán Dosko							
* @version			August 12 , 2014			
*/

class Mapping {
	
	/**
	* Return an array from a csv file.
	* @param	
	* @return array
	*/
	public function csvToArray(){
		$finalArray = array();
		$file = fopen("Files/photos_14_august.csv","r");
		while(! feof($file)){
  			array_push($finalArray, fgetcsv($file, 3000, ";")); 
  		}
		fclose($file);
		return $finalArray;
	}

	/**
	* Convert all the character in UpperCase
	* @param array	
	* @return array
	*/
	public function convertToUpperCase($arrayToChange){
		$arrayUpperCase = array();
		foreach ($arrayToChange as $value) {
			array_push($arrayUpperCase, array_map('strtoupper', $value));
		}
		return $arrayUpperCase;
	}

}


