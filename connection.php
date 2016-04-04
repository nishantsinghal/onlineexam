<?php
/*
** Used for the connection to the database 
*/
function dbconnect() {
	$conn = mysql_connect("localhost","root","innoraft");
	if( !$conn )
		echo "cannot connect to this".error_log();
	mysql_select_db('examdb');
	return $conn;
}
?>