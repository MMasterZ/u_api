<?php 
//8. GVC participation (unit% of gross exports to importer)
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
    $valueA = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3']
]);
} else {
    $valueA = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
     variable => ['DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

if($sector == 0){
    $valueB = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['MVA_FIN', 'MVA_INT', 'OVA_FIN', 'OVA_INT']
]);
} else {
    $valueB = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
     variable => ['MVA_FIN', 'MVA_INT', 'OVA_FIN', 'OVA_INT'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

if($sector == 0){
    $valueC = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DDC_FIN', 'DDC_INT', 'MDC', 'ODC']
]);
} else {
    $valueC = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DDC_FIN', 'DDC_INT', 'MDC', 'ODC'],
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





$result[0]['exp_country'] = $exp_country;
$result[0]['exp_sector'] = $sector_data[$sector];
$result[0]['imp_country'] = $imp_country;
$result[0]['variable_set'] = 'forward';
$result[0]['value'] = ($valueA/$value_gross)*100;
$result[0]['year'] = $year;
$result[0]['indicator'] = 'GVC_part';

$result[1]['exp_country'] = $exp_country;
$result[1]['exp_sector'] = $sector_data[$sector];
$result[1]['imp_country'] = $imp_country;
$result[1]['variable_set'] = 'backward';
$result[1]['value'] = ($valueB/$value_gross)*100;
$result[1]['year'] = $year;
$result[1]['indicator'] = 'GVC_part_backward';

$result[2]['exp_country'] = $exp_country;
$result[2]['exp_sector'] = $sector_data[$sector];
$result[2]['imp_country'] = $imp_country;
$result[1]['variable_set'] = 'double';
$result[2]['value'] = ($valueC/$value_gross)*100;
$result[2]['year'] = $year;
$result[2]['indicator'] = 'GVC_part';


 echo json_encode($result);
?>
