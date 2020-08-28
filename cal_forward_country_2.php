<?php 
///imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');

$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];
$tableName = $exp_country . "_" . $year;

//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
$region = $region_data[0];

//get country in same region
$country_data = $db->select("country_list","iso",[
region =>$region
]);


for($i=0; $i<count($country_data);$i++){
  $imp_country = $country_data[$i];
  $area = $db->select("country_list","name",["iso"=>$imp_country]);
  // $result[$i]['country'] =$area[0];
  $tableName = $imp_country . "_" . $year;

  if($sector == 0){
$sql  = "select sum(value) as sum,exp_country, imp_country  from " . $tableName . " 
where exp_country='" . $imp_country."' and year = " . $year ." and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' )  group by imp_country" ;
$value = $db->query($sql)->fetchAll();
} else {
$sql  = "select sum(value) as sum,exp_country, imp_country  from " . $tableName . " 
where exp_country='" . $imp_country."' and year = " . $year ." and exp_sector = '" . $sector_data[$sector] ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' )  group by imp_country" ;
$value = $db->query($sql)->fetchAll();
}

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
