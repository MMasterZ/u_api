<?php 
// require "connection";


$sqli = new mysqli("localhost", "root", "123456789","untest");


// // $fname = './data.txt';
// $fname = fopen('./data.txt', "r");

$db_sql = "LOAD DATA LOCAL INFILE './data.txt' INTO TABLE test_upload FIELDS TERMINATED BY ',' ( dat1, dat2 )";

$db_res = mysqli_query($sqli,$db_sql) or die("Error");

echo "Finish";


?>