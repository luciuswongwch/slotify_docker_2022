<?php

ob_start();

session_start();



include(dirname(__DIR__)."/db_config.php");


$con = mysqli_connect($dbconn_host, $dbconn_username, $dbconn_password, $dbconn_dbname);

if(mysqli_connect_errno()) {
	echo "Failed to connect: " . mysqli_connect_errno();
}

?>