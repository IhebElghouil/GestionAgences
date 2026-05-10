<?php
/*mysql_connect("127.0.0.1","root","");
mysql_select_db("pointage");

mysql_query("SET NAMES 'utf8'");
mb_internal_encoding('UTF-8');*/
//mysql_query('SET CHARACTER SET utf8'); 
// Establish a connection to the MySQL server
$connection = mysqli_connect("localhost:3307", "root", "", "pointage");

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the character set to UTF-8
mysqli_set_charset($connection, "utf8");

// Ensure internal encoding for multibyte string functions is set to UTF-8
mb_internal_encoding('UTF-8');

?>