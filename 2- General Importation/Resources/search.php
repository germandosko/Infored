<?php
/**  
 * @author German Dosko
 * @version Sep 04 2014
 */

class Search
{
	
	
	/**
	* Search the register found 
	* @param string
	* @return array
	*/
	public function query($query, $db){
		$connection = new Connection($db);
		$result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());
		$resultArray = array();
		while ($row = mysql_fetch_assoc($result)) {
			array_push($resultArray, $row);
		}
		$connection->close();
		return $resultArray;
	}

}