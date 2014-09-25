<?php
/**  
 * @author German Dosko
 * @version Sep 03 2014
 */

// Report all errors
error_reporting(E_ALL);
ini_set('error_reporting',E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');

// Just in case it hasn't been specified in the PHP ini: 86400 = 24 hours in seconds
ini_set('max_execution_time', 86400);

// Database configuration
define('MYSQL_HOST', 'stg-rds.aws.doctor.com');
define('MYSQL_USER', 'doctorrdsadmin');
define('MYSQL_PASS', '8785j9ETM7811x4');
define('MYSQL_DB_1', 'pdi_doctor');
define('MYSQL_DB_2', 'tmp_better_doctor');
define('TABLE_PROVIDER_SERVER', 'mv_provider_search');
define('TABLE_INFO', 'tmp_batch_1');
define('TABLE_RESULT', 'tmp_batch_1_result');

