<?php
if( count(get_included_files()) == ((version_compare(PHP_VERSION, '5.0.0', '>='))?1:0) )
{
    exit('Restricted Access');
}
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER1', '159.65.156.24');
define('DB_USERNAME1', 's7user');
define('DB_PASSWORD1', 'Fisat@s7user2022');
define('DB_NAME1', 's7_project');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER1, DB_USERNAME1, DB_PASSWORD1, DB_NAME1);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>