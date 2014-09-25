<?php
$url_csv_file = 'Files/cancer_stanford_fotos.csv';


/**
*  Principal Function 
* @param	
* @return 
*/
function run(){
	$arrayInfo = csvToArray();
	foreach ($arrayInfo as $value) {
		$record_id = $value[0];
		$url_photo = $value[1];
		
		if($url_photo){
			$contents= file_get_contents($url_photo);	
			$savefile = fopen('Photos_Cancer_Stanford/cancer_stanford_'.$record_id.'.jpeg', 'w');
			fwrite($savefile, $contents);
			fclose($savefile);
		}
		$num += 1;
	}	
}


/**
* Return an array from a csv file.
* @param	
* @return array
*/
function csvToArray(){
	global $url_csv_file;
	$finalArray = array();
	$file = fopen($url_csv_file,"r");
	while(! feof($file)){
			array_push($finalArray, fgetcsv($file, 3000, ";")); 
		}
	fclose($file);
	return $finalArray;
}


run();