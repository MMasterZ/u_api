<?php 
include 'connection.php';

$file = fopen("./data/ARG.csv","r");

// if($file){

// }
// while(($items = fgetcsv($file)))
// {
//   $sector_id = $items[1];
//   $name = $items[0];
//   $db -> insert("sector",
//   [
//     "id" => $sector_id,
//     "name" => $name
//   ]);
// }

// fclose($file);



// $test = file_get_contents($data);
if ($file) {

    $counter = 0;
    $sql ="INSERT INTO test_csv(exp_country,imp_country,variable) VALUES ";
    while (($line = fgetcsv($file)) !== false) {
        $exp_country = $line[0];
        $imp_country = $line[1];
        $variable = $line[2];

        // $value = "(" . "'" . $exp_country . "'" . "," . "'" . $imp_country . "'". "," . "'" .$variable . "'" . ")";


         $value = '(' . '"' . $exp_country . '"' . ',' . '"' . $imp_country . '"'. ',' . '"' .$variable . '"' . ')';


    $sql .= $value . ",";
      $counter++;
    }

    $sql = substr($sql, 0, strlen($sql) - 1) ;

    // echo $sql;


    
    $mysqli = new mysqli("localhost","root","12345678","untest");

    if($mysqli->query($sql) === TRUE){
        echo "CREATED SUCCESS";
    }else{
        echo $sql;
        echo "\n";
       echo "error" . $mysqli -> error;
    }



//     if ($result = $mysqli -> query("SELECT * FROM sector")) {
//   echo "Returned rows are: " . $result -> num_rows;
//   // Free result set
//   $result -> free_result();
// }


    // if($mysqli){
    //     echo "true";
    //     $mysqli -> query($sql);
    // }
    //  if ($conn->query($sql) === TRUE) {
    //      echo "TRUE";
    // } else {
    //     echo "False";
    //  }
    fclose($file);
} else {  
    echo "not file";
} 
unlink($data);




 ?>
