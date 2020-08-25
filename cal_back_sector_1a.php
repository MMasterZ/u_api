<?php 
///imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//source_country = source countrh ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$source_country = $_GET['source_country'];
$year = $_GET['year'];



$value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    source_country =>$source_country,
    year => $year,
    variable => ['fva_fin_yl', 'fva_int_yl']
]);

$result['fromsource'] = $value/1000;

$value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['total_export']
]);

$result['exportto'] = $value/1000;

 echo json_encode($result);
?>
