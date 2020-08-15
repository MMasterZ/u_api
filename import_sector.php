<?php 
include 'connection.php';

$check_sector_code_exists = $db -> count("sector","*");

if($check_sector_code_exists)
{
// กรณีมีตารางแล้ว
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
}else{

    $db->create("sector",[
    "id" => [
		"INT",
		"NOT NULL",
		"AUTO_INCREMENT",
		"PRIMARY KEY"
	],
    "name" => [
        "TEXT"
    ],
    ]
    );

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

}


 ?>
