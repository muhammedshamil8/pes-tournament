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
<!-- on infinity free app free hosting kickoff -->
<?php
/*
$db_server = "sql112.infinityfree.com";
$db_user = "if0_34999877";
$db_password = "IUDG5TlJGAs9";
$db_database = "if0_34999877_pes";
// Ali@kathalat4

$conn = new mysqli($db_server,$db_user,$db_password,$db_database);

if ($conn->connect_error){
   die("Connection failed:" . $conn->connect_error);
}

*/
?>