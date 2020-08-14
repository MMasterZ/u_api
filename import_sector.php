<?php 
include 'connection.php';

$file = fopen("./data/sector_code_list.csv","r");
while(($items = fgetcsv($file)))
{
  $sector_id = $items[1];
  $name = $items[0];
  $db -> insert("sector",
  [
    "id" => $sector_id,
    "name" => $name
  ]);
}

fclose($file);
 ?>
