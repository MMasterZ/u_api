<?php 
//3. Gross exports that return home and used in the exporter's domestic consumption (Dom_cons)
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
    variable => ['RDV_FIN1', 'RDV_FIN2', 'RDV_INT']
]);
} else {
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['RDV_FIN1', 'RDV_FIN2', 'RDV_INT'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

$result['source_country'] = "-";
$result['exp_country'] = $exp_country;
$result['exp_sector'] = $sector_data[$sector];
$result['imp_country'] = $imp_country;
$result['variable_set'] = "-";
$result['value'] = $value;
$result['year'] = $year;
$result['indicator'] = 'Dom_cons';


 echo json_encode($result);



?>
