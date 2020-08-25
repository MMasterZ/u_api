<?php 
require "connection.php";
require "mysqli_connection.php";

$fname = $_FILES['file']['tmp_name'];

$real_name = $_FILES['file']['name'];

$uploads_dir = "./uploads";

move_uploaded_file($fname, "$uploads_dir/$real_name");

$real_file_destination = $uploads_dir . "/" . $real_name;

$sql = "load data local infile '" . $real_file_destination . "' into table country_data fields terminated by ','  optionally enclosed by '\"' ignore 1 lines (source_country,exp_country,exp_sector,imp_country,variable,value,year)";


mysqli_query($sqli,$sql) or die(mysqli_error($sqli));


unlink($uploads_dir . "/" . $real_name);

echo "Success";

?>