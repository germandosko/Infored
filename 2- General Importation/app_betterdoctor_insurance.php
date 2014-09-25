<?php
require 'Resources/siteConfig.php';
require 'Resources/clean.php';
require 'Resources/connection.php';
require 'Resources/search.php';
require 'Resources/insert.php';

$queryFindProviders = "SELECT tmp_batch_1.record_id, provider_id, insurancesAccept FROM tmp_batch_1 INNER JOIN tmp_batch_1_result limit 1";

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
	$sqlFinal = '';
	$sqlCompanies  = '';
	$sqlInsurances  = '';
	$search = new Search();
	$clean = new Clean();
	$arrayProviders = $search->query($queryFindProviders, MYSQL_DB_2);	
	$numProperties = count($arrayProviders);
	echo 'Numero de Registros a INSERTAR: '.$numProperties.'</br></br>';
	for ($i=0; $i < $numProperties; $i++) { 
			$arrayInsurance = explode(' | ', $arrayProviders[$i]['insurancesAccept']);
			$record_id = $arrayProviders[$i]['record_id'];
			$provider_id = $arrayProviders[$i]['provider_id'];			
			foreach ($arrayInsurance as $key => $value) {
				$pos = strpos($value, ':');
				$company_insurance = trim(substr($value, 0, $pos));
				$queryCompany = "SELECT * FROM prd01.insurance_company WHERE prd01.insurance_company.name ='".$company_insurance."'";
				$company_existency = $search->query($queryCompany, MYSQL_DB_1);
				if($company_existency){
					$pos += 1;
					$insurances = explode(',', (substr($value, $pos)));
					foreach ($insurances as $key => $value) {
						$queryInsurance = "SELECT * FROM prd01.insurance WHERE prd01.insurance.company_id = ".$company_existency[0]["id"]." AND prd01.insurance.name ='".trim($value)."'";
						$insurance_existency = $search->query($queryInsurance, MYSQL_DB_1);
						if($insurance_existency){
							$sqlFinal .= "(".$provider_id.", ".$insurance_existency[0]["company_id"].", ".$insurance_existency[0]["id"]."),\n";
						} else {
							$sqlInsurances .= $company_existency[0]['name']." - ".$value."\n";
						}						
					}
				} else {
					$sqlCompanies .= $company_existency[0]['name']."\n";
				}
			}
	}
	$fp = fopen("Reports/insurance.txt", "w");
	fputs ($fp, $sqlFinal);
	fclose ($fp);
	$fp = fopen("Reports/companiesNotFound.txt", "w");
	fputs ($fp, $sqlCompanies);
	fclose ($fp);
	$fp = fopen("Reports/insurancesNotFound.txt", "w");
	fputs ($fp, $sqlInsurances);
	fclose ($fp);
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

