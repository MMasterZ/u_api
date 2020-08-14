<?php 
include 'connection.php';

$file = fopen("./data/country_list.csv","r");
while(($items = fgetcsv($file)))
{
  $iso = $items[1];
  $name = $items[0];
  $region = $items[2];

  $db -> insert("country_list",
  [
    "name" => $name,
    "region" => $region,
    "iso" => $iso
  ]);


}

fclose($file);
 ?>
