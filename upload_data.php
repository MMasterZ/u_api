<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header('Content-type: application/x-www-form-urlencoded');
header('Content-type: application/json');

require "mysqli_connection.php";

require "connection.php";

$fname = $_FILES['file']['tmp_name'];
$country = $_POST['country_name'];
$year = $_POST['year'];

$real_name = $_FILES['file']['name'];

$uploads_dir = "./uploads";

$table_name = $country . "_" . $year;

$check_country_list_exists = $db -> count($table_name,"*");


if($check_country_list_exists){
// กรณีมีตารางนี้แล้ว
}else{
// กรณียังไม่มีตารางนี้
$db -> create($table_name,
[
    "id" => [
        "INT",
        "NOT NULL",
        "AUTO_INCREMENT",
        "PRIMARY KEY"
    ],
    "source_country" => [
        "TEXT",
    ],
    "exp_country" => [
        "TEXT"
    ],
     "exp_sector" => [
        "TEXT"
    ],
    "imp_country" => [
        "TEXT"
    ],
    "variable" => [
        "TEXT"
    ],
    "value" => [
        "FLOAT"
    ],
    "year" => [
        "INT"
    ]
]);
}

move_uploaded_file($fname, "$uploads_dir/$real_name");

$real_file_destination = $uploads_dir . "/" . $real_name;

$sql = "load data local infile '" . $real_file_destination . "' into table " . $table_name . " fields terminated by ','  optionally enclosed by '\"' ignore 1 lines (source_country,exp_country,exp_sector,imp_country,variable,value,year)";

mysqli_query($sqli,$sql) or die(mysqli_error($sqli));

unlink($uploads_dir . "/" . $real_name);

echo "Success";

?>