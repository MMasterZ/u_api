<?php 
include 'connection.php';

// $datas = $db -> select("account","*");

// foreach($datas as $data){
//   echo "user_name:" . $data["user_name"] . " - email:" . $data["email"] ;
// }
$file = fopen("./data/sector_code_list.csv","r");
// while(! feof($file))
//   {
//   print_r(fgetcsv($file)[0]);
//   print_r(fgetcsv($file)[1]);

//   $name = 
//   }

while(($items = fgetcsv($file)))
{

  $name = $items[0];
  // $iso = $items[1];
  $sector_id = $items[1];
  // $continent = $item[2];
  // echo ('name -> '.$items[0].'--');
  // echo ('id ->'.$items[1]."\n");
  // $db -> insert("sector",
  // [
  //   "id" => $sector_id,
  //   "name" => $name
  // ]);
}

fclose($file);
 ?>
