<?php
require 'Resources/siteConfig.php';
require 'Resources/clean.php';
require 'Resources/connection.php';
require 'Resources/search.php';
	
/**
*  Principal Function 
* @param	
* @return 
*/
function run(){
	$numFound = 0;
	$search = new Search();
	$providersArray = $search->findProviders();	
	//echo '<pre>';
	//var_dump($providersArray);
	//echo '</pre>';
	//die();
	$arrayRegistersFound = $search->findRegistersFound();
	$numProperties = count($providersArray);
	echo 'Numero de Registros a evaluar: '.$numProperties.'</br>';
	for ($i=3075; $i < $numProperties; $i++) { 
		$record_id = $providersArray[$i]['record_id'];
		if(!in_array($record_id, $arrayRegistersFound)){			
			$numRow = $search->searchProvider($providersArray[$i]);
			$numFound += $numRow; 
		}	
	}
	echo 'FIN DE CONSULTA </br>';
	echo 'REGISTROS ENCONTRADOS: '.$numFound;
}



run();

