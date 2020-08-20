<?php 
require "connection.php";

$sqli = new mysqli("localhost", "root", "","test");

$fname = $_FILES['file']['tmp_name'];

$real_name = $_FILES['file']['name'];

$uploads_dir = "./uploads";

move_uploaded_file($fname, "$uploads_dir/$real_name");

$real_file_destination = $uploads_dir . "/" . $real_name;

echo $real_file_destination;

$sql = "load data local infile '" . $real_file_destination . "' into table country_list fields terminated by ','  optionally enclosed by '\"' ignore 1 lines (col1,col2,col3,col4,col5,col6,col7)";

mysqli_query($sqli,$sql) or die(mysqli_error($sqli));

?>