<?php 
//7. Gross trade balance (Gross_tradebalance)
//imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017
//sector = sector ส่งมาเป็น id ตัวเลข sector 
require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];

if($sector == 0){
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['total_export']
]);
} else {
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
     variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

if($sector == 0){
    $value1 = $db->sum("country_data","value",[
    exp_country =>$imp_country,
    imp_country =>$exp_country ,
    year => $year,
     variable => ['total_export']
]);
} else {
    $value1 = $db->sum("country_data","value",[
    exp_country =>$imp_country,
    imp_country => $exp_country,
    year => $year,
     variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

if($sector == 0){
    $value_gross = $db->sum("country_data","value",[
   exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
     variable => ['total_export']
]);
} else {
    $value_gross = $db->sum("country_data","value",[
   exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
     variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

$value_f = ($value - $value1)/$value_gross *100;






$result['source_country'] = "-";
$result['exp_country'] = $exp_country;
$result['exp_sector'] = $sector_data[$sector];
$result['imp_country'] = $imp_country;
// $result['value'] = $value;
// $result['value1'] = $value1;
// $result['value_gross'] = $value_gross;
$result['variable_set'] = "-";
$result['value'] = $value_f;
$result['year'] = $year;
$result['indicator'] = 'Gross_tradebalance';

 echo json_encode($result);
?>
