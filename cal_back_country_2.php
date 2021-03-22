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

$count = 0;
$count2 = 0;
for($i=0; $i<count($country_data);$i++){
  
  $imp_country2 = $country_data[$i];
  if($imp_country2 != $imp_country){
    $area = $db->select("country_list","name",["iso"=>$imp_country2]);
    $tableName = $imp_country2 . "_" . $year;

    if($sector == 0){
      $sql  = "select sum(value) as sum, source_country  from " . $tableName . " where (variable = 'fva_fin_yl' or variable='fva_int_yl' ) and imp_country='" . $imp_country . "' group by source_country" ;
      $value = $db->query($sql)->fetchAll();
    } else {
      $sql  = "select sum(value) as sum, source_country  from " . $tableName . " where exp_sector = '" . $sector_data[$sector]  ."' and (variable = 'fva_fin_yl' or variable='fva_int_yl' ) and imp_country='" . $imp_country . "' group by source_country" ;
      $value = $db->query($sql)->fetchAll(); 
    }

  if($sector == 0){
      $value2 = $db->sum($tableName,"value",[
      variable => ['total_export'],
      imp_country =>  $imp_country
  ]);
  } else {
    //   $value2 = $db->sum($tableName,"value",[
    //   variable => ['total_export'],
    //   imp_country =>  $imp_country,
    //   exp_sector=>$sector_data[$sector],
    // ]);  

    $sql2 = "select sum(value) as sum from " . $tableName . " where (variable = 'total_export') and imp_country = '" . $imp_country . "' and exp_sector = '" . $sector_data[$sector] . "'";
    // echo $sql2;
     $value2 = $db->query($sql2)->fetchAll(); 
     $value2 = $value2[0][0];

  }
 


  for($j=0;$j<count($value);$j++){
    $result[$count][$j]['exp_country'] = $area[0];
      $area2 = $db->select("country_list",["name","area"],["iso"=>$value[$j]['source_country']]);
    $result[$count][$j]['imp_country'] =$area2[0]['name'];
    $result[$count][$j]['area'] = $area2[0]['area'];
     if($value2 > 0){
    $result[$count][$j]['value'] = round($value[$j]['sum']/$value2*100,2);
     } else {
       $result[$count][$j]['value'] = 0;
     }
    $result[$count][$j]['valueM'] = round($value[$j]['sum'],2);
    if(round($value[$j]['sum']/$value2*100,2) >0){
    $count2++;
    }
    
  }
  $count++;
  }
  
// print_r($result);

}

//  echo json_encode($result);

if($count2 ==0){
   $dataShow ['show'] = 'off';
  echo json_encode($dataShow);
} else {
  echo json_encode($result);
}


 
?>
