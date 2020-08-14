<?php 
require_once('connection.php');

$test = $db -> select("year","*");

echo $test;

?>