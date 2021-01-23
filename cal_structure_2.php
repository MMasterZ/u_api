<?php
require_once('connection.php');
require_once('sector_data.php');


$exp_country = $_GET['exp_country'];
$imp_country = $_GET['imp_country'];
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
for($i=0; $i<count($country_data);$i++){
  $value1 =0;
  $value2 =0;
  $value3 =0;
  $value4 = 0;
  $value5=0;
  $exp_country2 = $country_data[$i];
 
 
  if($exp_country2 != $imp_country){
    
     $area = $db->select("country_list","name",["iso"=>$exp_country2]);
     $result[$count]['imp_country'] =$area[0];
  $table_name = $exp_country2 . "_" . $year;
  /// calculation of imp cons
if($sector == 0){
    $value1 = $db->sum($table_name,"value",[
    imp_country=>$imp_country,
    variable => ['DVA_FIN', 'DVA_INT']
]);
} else {
    $value1 = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['DVA_FIN', 'DVA_INT'],
    exp_sector=>$sector_data[$sector],
  ]);  
}


/// Calculation of imp exp
if($sector == 0){
    $value2 = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3' ]
]);
} else {
    $value2 = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3'],
    exp_sector=>$sector_data[$sector],
  ]);  
}


/// Calculation of dom cons
if($sector == 0){
    $value3 = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['RDV_FIN1', 'RDV_FIN2', 'RDV_INT' ]
]);
} else {
    $value3 = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['RDV_FIN1', 'RDV_FIN2', 'RDV_INT'],
    exp_sector=>$sector_data[$sector],
  ]);  
}


/// Calculation of double
if($sector == 0){
    $value4 = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['DDC_FIN', 'DDC_INT', 'MDC', 'ODC' ]
]);
} else {
    $value4 = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['DDC_FIN', 'DDC_INT', 'MDC', 'ODC'],
    exp_sector=>$sector_data[$sector],
  ]);  
}



/// Calculation of imp cont
if($sector == 0){
    $value5 = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['MVA_FIN', 'MVA_INT', 'OVA_FIN', 'OVA_INT' ]
]);
} else {
    $value5 = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['MVA_FIN', 'MVA_INT', 'OVA_FIN', 'OVA_INT'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
 $total = $value1 + $value2 + $value3 + $value4 +$value5;
  if($total > 0.005){
   
    $result[$count]['imp_cons']['precent'] = round($value1/$total*100,2);
    $result[$count]['imp_cons']['value'] = round($value1,2);
    $result[$count]['imp_exp']['precent'] = round($value2/$total*100,2);
    $result[$count]['imp_exp']['value'] = round($value2,2);
    $result[$count]['dom_cons']['precent'] = round($value3/$total*100,2);
    $result[$count]['dom_cons']['value'] = round($value3,2);
    $result[$count]['double']['precent'] = round($value4/$total*100,2);
     $result[$count]['double']['value'] = round($value4,2);
    $result[$count]['imp_cont']['precent'] = round($value5/$total*100,2);
    $result[$count]['imp_cont']['value'] = round($value5,2);
    $count +=1;
  } 
  }
}

if($count ==0){
  $dataShow ['show'] = 'off';
  echo json_encode($dataShow);
} else {
echo json_encode($result);
}


?>
