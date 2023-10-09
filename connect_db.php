<?php
$db_server = "mysql_db";
$db_user = "root";
$db_password = "root";
$db_database = "pes";
// Ali@kathalat4

$conn = new mysqli($db_server,$db_user,$db_password,$db_database);

if ($conn->connect_error){
   die("Connection failed:" . $conn->connect_error);
}


?>