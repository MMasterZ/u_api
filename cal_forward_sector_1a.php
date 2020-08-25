<?php 
///imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];



$value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3' ]
]);

$result['contributionto'] = $value/1000;

$value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['total_export']
]);

$result['exportto'] = $value/1000;

 echo json_encode($result);
?>
