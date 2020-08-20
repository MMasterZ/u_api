<?php 
// require "connection";


$sqli = new mysqli("localhost", "root", "","test");

// if($sqli){
//     echo "CONNECTED";
// }


$fname = '/ARG.csv';
// $fname = fopen('/ARG.csv', "r");

// if($fname){
//     echo "exists";

// }else{
//     echo "not exists";

// }

$path_file = __DIR__.'\ARG.csv';





// $sql1 = "load data local infile '" . $path_file ."' into table country_list fields terminated by ','  optionally enclosed by '\"' ignore 1 lines (col1,col2,col3,col4,col5,col6,col7)";

$sql = "load data local infile '/ARG.csv' into table country_list fields terminated by ','  optionally enclosed by '\"' ignore 1 lines (col1,col2,col3,col4,col5,col6,col7)";

mysqli_query($sqli,$sql) or die(mysqli_error($sqli));


echo $sql;




// mysqli_query($sqli,$sql);

// $sql = "select * from country_list";


// $result = mysqli_query($sqli,$sql);


// echo $result;



// echo $result;

// echo "FINISH";





// $db_sql = "LOAD DATA LOCAL INFILE './data.txt' INTO TABLE test_upload FIELDS TERMINATED BY ',' ( dat1, dat2 )";

// $db_res = mysqli_query($sqli,$db_sql) or die("Error");

// echo "Finish";


?>