<?php 
include 'connection.php';

$check_country_list_exists = $db -> count("country_list","*");

if($check_country_list_exists){
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
}else{
    // CREATE NEW TABLE
    $db->create("country_list",[
    "id" => [
		"INT",
		"NOT NULL",
		"AUTO_INCREMENT",
		"PRIMARY KEY"
	],
    "name" => [
        "TEXT"
    ],
    "region" => [
        "TEXT"
    ],
    "iso" => [
        "TEXT"
    ]
    ]
    );

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

}

 ?>
