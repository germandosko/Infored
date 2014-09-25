<?php
require 'Resources/siteConfig.php';
require 'Resources/clean.php';
require 'Resources/connection.php';
require 'Resources/search.php';

$table_to_control = 'tmp_batch_1_result';
$table_to_save = 'tmp_batch_1_result';

/**
*  Principal Function 
* @param	
* @return 
*/
function run(){
	$numFound = 0;
	$search = new Search();
	$providersArray = $search->findProviders();	
	$arrayRegistersFound = $search->findRegistersFound();
	$numProperties = count($providersArray);
	echo 'Numero de Registros a evaluar: '.$numProperties.'</br>';
	for ($i=0; $i < $numProperties; $i++) { 
		$record_id = $providersArray[$i]['record_number'];
		if(!in_array($record_id, $arrayRegistersFound)){
			$numRow = $search->searchProvider($providersArray[$i]);
			$numFound += $numRow; 
		}	
	}
	echo 'FIN DE CONSULTA </br>';
	echo 'REGISTROS ENCONTRADOS: '.$numFound;
}



run();

