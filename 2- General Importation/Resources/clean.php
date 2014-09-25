<?php

/**
* @author    German Dosko
* @version   August 27 , 2014
*/
class Clean {
	
	/**
	* Clean the name received
	* @param string	
	* @return string
	*/
	public function cleanName($name){	
		$toDeleteFromString = array(".", ",", "#", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "<", "!", "(", ")", "\"");
		$name = str_replace($toDeleteFromString, "", $name);
		$name = str_replace("-", " ", $name);
		$name = str_replace("&;", "'", $name);
		$toDeleteFromArray = array("Rev","Mrs", "Mr", "Dr", "dr", "Ms", "LLC", "LPC", "PCS", "MA", "PLLC", "MSW", "LCSW", "MC", "PhD", "PLC", "LMFT", "MS", "LADAC", "AADC", "CCS", "CCDP-D", "CCGC", "MFT", "PsyD", "CGP", "MA-LMFT", "MD", "CEAP", "CADC", "BA", "Lcsw", "Dcsw", "DMD", "Pc", "DDS", "IMF", "ALMFT", "BCD", "BCPC", "APC", "JD", "Mft", "NCC", "LMHC", "CFMHE", "LICSW", "LCSWMSWCRP", "CMHC", "CAP", "DCSW ", "ACSW", "MSLMFT", "PhDPA", "RMHCI", "MSEd", "CP", "ARNP", "PA", "ACHP", "SW", "PL", "CTS", "LCPC", "BBC", "CMFSW", "SAP", "MEd", "MED", "MPS", "ASWCM", "Ed", "CFC", "PC", "PsyDPC", "CRADC", "CSAT", "SJ", "CH", "LTD", "Ltd", "PHD", "LISW", "INC", "LSCSW", "LPCC", "MDiv", "MBA", "BACS", "TIR", "RN", "CARN", "LADC", "LRC", "LLP", "UCCF", "LMSW", "LP", "BHSI", "DAPA", "SVC", "LMHP", "LIMHP", "LCMHC", "LCADC", "CSWR", "LCSWR", "CASAC", "Mt", "Bc");
		$arrayName = explode(" ", $name);
		foreach ($arrayName as $key => $value) {
			if(in_array($value, $toDeleteFromArray)){
				$arrayName[$key] = '';
			}
		}
		$name = implode(" ", $arrayName);
		$name = trim($name);
		return $name;
	}

	/**
	* Clean the basic characters
	* @param string	
	* @return string
	*/
	function cleanGeneral($string){
		$string = str_replace("#","",$string);
		$string = str_replace(".","",$string);
		$string = str_replace(",","",$string);
		$string = str_replace("\"","",$string);
		$string = trim($string);
		return $string;	
	}

	/**
	* Clean the Address Received 
	* @param string 	
	* @return string
	*/
	function cleanAddress($address){
		$address = cleanGeneral($address);
		$arrayAddress = explode(' ', $address);
		foreach ($arrayAddress as $key => $value) {
			if($value == "Drive" or $value == "Dr"){
				$arrayAddress[$key] = 'D';
			} elseif ($value == "Avenue" or $value == "Ave") {
				$arrayAddress[$key] = 'A';
			}  elseif ($value == "Street" or $value == "St") {
				$arrayAddress[$key] = 'S';
			}  elseif ($value == "Road" or $value == "Rd") {
				$arrayAddress[$key] = 'R';
			}  elseif ($value == "Circle" or $value == "Cir") {
				$arrayAddress[$key] = 'C';
			}   elseif ($value == "Suite" or $value == "Ste") {
				$arrayAddress[$key] = 'S';
			}   elseif ($value == "East") {
				$arrayAddress[$key] = 'E';
			}   elseif ($value == "Ln" or $value == "Lane") {
				$arrayAddress[$key] = 'L';
			}	elseif ($value == "FL" or $value == "Floor") {
				$arrayAddress[$key] = 'F';
			}	elseif ($value == "NE" or $value == "Ne") {
				$arrayAddress[$key] = 'N';
			}
		}
		$address = implode(' ', $arrayAddress);
		return $address;
	}

	/**
	* add the character % in the string
	* @param string
	* @return string
	*/
	public function addPorcent($name){
		$providerSpaces = trim($name);
		$providerSpacesPorcent = str_replace(' ', '%', $providerSpaces); 	
		$prividerSize = strlen($providerSpacesPorcent) + 2;
		$providerFinalName = str_pad($providerSpacesPorcent, $prividerSize, "%", STR_PAD_BOTH);
		return $providerFinalName;
	}

	/**
	* delete the middle name  
	* @param string
	* @return string
	*/
	public function deleteMiddleName($string){
		$separatedName = explode(" ",$string);
		$finalName = $string;
		if(count($separatedName)==3){
			$lastName = count($separatedName)-1;
			$finalName = $separatedName[0].' '.$separatedName[$lastName];
		};	
		return $finalName;
	}

	/**
	* Convert from full address to simple address  
	* @param string
	* @return string
	*/
	public function deleteSecondAddress($string){
		$finalAddress = $string;
		$separatedAddress = explode(" ",$string);
		$num = count($separatedAddress);
		if(is_numeric($separatedAddress[$num-1])){
			unset($separatedAddress[$num-1]);
			unset($separatedAddress[$num-2]);
			$finalAddress = implode(" ", $separatedAddress);
		}
		return $finalAddress;
	}

		/**
	* Delete State from the string
	* @param string	
	* @return string
	*/
	public function cleanState($name){	
		$name = str_replace("'", "\'", $name);
		$toDeleteFromArray = array("AK", "AL", "AR", "AZ", "CA", "CO", "CT", "DC", "DE", "FL", "GA", "GU", "HI", "IA", "ID", "IL", "IN", "KS", "KY", "LA", "MA", "MD", "ME", "MI", "MN", "MO", "MS", "MT", "NC", "ND", "NE", "NH", "NJ", "NM", "NV", "NY", "OH", "OK", "OR", "PA", "PR", "RI", "SC", "SD", "TN", "TX", "UT", "VA", "VI", "VT", "WA", "WI", "WV", "WY");
		$arrayName = explode(" ", $name);
		foreach ($arrayName as $key => $value) {
			if(in_array($value, $toDeleteFromArray)){
				$arrayName[$key] = '';
			}
		}
		$name = implode(" ", $arrayName);
		$name = trim($name);
		return $name;
	}

}