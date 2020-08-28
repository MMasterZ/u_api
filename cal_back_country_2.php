<?php 
///imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//source_country = source countrh ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];


//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
$region = $region_data[0];

//get country in same region
$country_data = $db->select("country_list","iso",[
region =>$region
]);

// print_r($country_data);

for($i=0; $i<count($country_data);$i++){
  $imp_country2 = $country_data[$i];
  $area = $db->select("country_list","name",["iso"=>$imp_country2]);
  // $result[$i]['country'] =$area[0];
  $tableName = $imp_country2 . "_" . $year;

  if($sector == 0){
$sql  = "select sum(value) as sum,source_country,exp_country, imp_country  from " . $tableName . " 
where exp_country='" . $imp_country2."' and year = " . $year ." and (variable = 'fva_fin_yl' or variable='fva_int_yl' )  group by imp_country" ;
$value = $db->query($sql)->fetchAll();

} else {
 $sql  = "select sum(value) as sum,source_country,exp_sector, exp_country, imp_country  from 
" . $tableName . " 
where exp_country='" . $imp_country2. "'and exp_sector = '" . $sector_data[$sector]  ."' and year = " . $year ." and (variable = 'fva_fin_yl' or variable='fva_int_yl' )  group by imp_country" ;
$value = $db->query($sql)->fetchAll(); 
}
// print_r($value);
if($sector == 0){
    $value2 = $db->sum($tableName,"value",[
    variable => ['total_export']
]);
} else {
    $value2 = $db->sum($tableName,"value",[
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

for($j=0;$j<count($value);$j++){
  $result[$i][$j]['exp_country'] = $area[0];
    $area2 = $db->select("country_list",["name","area"],["iso"=>$value[$j]['imp_country']]);
  $result[$i][$j]['imp_country'] =$area2[0]['name'];
  $result[$i][$j]['area'] = $area2[0]['area'];
  $result[$i][$j]['value'] = round($value[$j]['sum']/$value2*100,2);

}

}




 echo json_encode($result);
?>
