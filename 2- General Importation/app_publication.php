<?php
require 'Resources/siteConfig.php';
require 'Resources/clean.php';
require 'Resources/connection.php';
require 'Resources/search.php';
require 'Resources/insert.php';

$queryFindProviders = "SELECT * FROM cancer_stanford_provider_publication";

/**
*  Principal Function 
* @param	
* @return 
*/
function run(){
	createInsert();
}
function createInsert(){
	global $queryFindProviders;
	$search = new Search();
	$clean = new Clean();
	$arrayProviders = $search->query($queryFindProviders, MYSQL_DB_2);	
	$numProperties = count($arrayProviders);
	echo 'Numero de Registros a INSERTAR: '.$numProperties.'</br></br>';
	$finalQuery = 'INSERT INTO prd01.provider_residency (provider_id, residency_type_id, hospital_name, area_of_focus, year_completed) VALUES </br>';	
	for ($i=0; $i < $numProperties; $i++) { 
			$arrayPublication = explode('||', $arrayProviders[$i]['publication']);
			foreach ($arrayPublication as $key => $value) {
				$arrayData = explode('::', $value);
				echo '<pre>'; 
				var_dump($arrayData);
				echo '</pre>';
			}
	}
	echo $finalQuery;
	echo 'FIN DE INSERTAR </br>';
}

function createInsertEducation(){
	global $queryFindProviders;
	
	$search = new Search();
	$clean = new Clean();

	$arrayProviders = $search->query($queryFindProviders, MYSQL_DB_2);	
	$numProperties = count($arrayProviders);
	echo 'Numero de Registros a INSERTAR: '.$numProperties.'</br></br>';
	$queryNewEducation = 'INSERT INTO prd01.education_name (name) VALUES </br>';	
	//$finalQuery = 'INSERT INTO prd01.provider_residency (provider_id, residency_type_id, hospital_name, area_of_focus, year_completed) VALUES </br>';	
	$result = '';
	for ($i=0; $i < $numProperties; $i++) { 
		$pos_educ = strpos($arrayProviders[$i]['Medical_Education'], '(');
		$educ_name = substr($arrayProviders[$i]['Medical_Education'], 0, $pos_educ); 
		$educ_name = $clean->cleanState($educ_name);
		$querySearchEducation = "SELECT * FROM prd01.education_name WHERE prd01.education_name.name = '".$educ_name."'";
		$result = $search->query($querySearchEducation, MYSQL_DB_3);
		if(!$result){
			$queryNewEducation .= "'".$educ_name."',</br>";
		}
		


		/*
		$id = $i+1;
		$pos_year = strpos($arrayProviders[$i]['Residency_1'], ')')-4;
		$pos_hosp = strpos($arrayProviders[$i]['Residency_1'], '(');			
		$year = substr($arrayProviders[$i]['Residency_1'], $pos_year, 4);
		$hospital_name = substr($arrayProviders[$i]['Residency_1'], 0, $pos_hosp); 
		$hospital_name = $clean->cleanState($hospital_name);
		$providerId = $arrayProviders[$i]['provider_id'];
		$finalQuery .= "(".$providerId.", 2, '" .$hospital_name. "', NULL, ".$year."),</br>";
		*/	
	}
	echo $queryNewEducation;
	echo 'FIN DE INSERTAR </br>';
}

function deleteState(){
	$queryStates = 'SELECT abbreviation FROM state';
	$states = $search->query($queryStates);	
	$finalString = ''; 
	foreach ($states as $key => $value) {
		$finalString .= '"'.$value["abbreviation"].'", '; 
	}
	echo $finalString;
}

function createUpdate(){
	$arrayToUpdate = $search->query($queryFindProviders);	
	var_dump($arrayToUpdate);
	die();
	$numProperties = count($arrayProviders);
	echo 'Numero de Registros a INSERTAR: '.$numProperties.'</br></br>';
	$finalQuery = 'INSERT INTO cancer_standford_provider_residency VALUES </br>';	
	for ($i=0; $i < $numProperties; $i++) { 
			$id = $i+1;
			$pos_year = strpos($arrayProviders[$i]['Residency_1'], ')')-4;
			$pos_hosp = strpos($arrayProviders[$i]['Residency_1'], '(');			
			$year = substr($arrayProviders[$i]['Residency_1'], $pos_year, 4);
			$hospital_name = substr($arrayProviders[$i]['Residency_1'], 0, $pos_hosp); 
			$hospital_name = $clean->cleanState($hospital_name);
			$providerId = $arrayProviders[$i]['provider_id'];
			$finalQuery .= "(".$id.", ".$providerId.", 2, '" .$hospital_name. "', NULL, ".$year."),</br>";		
	}
	echo $finalQuery;
	echo 'FIN DE UPDATE </br>';
}


run();

