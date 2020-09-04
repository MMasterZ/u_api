<?php 
///imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];

$table_name = $exp_country . "_" . $year;

$value = $db->sum($table_name ,"value",[
    imp_country => $imp_country,
    variable => ['DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3' ]
]);

$result['contributionto'] = round($value/1000,2);

$value = $db->sum($table_name ,"value",[
    imp_country => $imp_country,
    variable => ['total_export']
]);

$result['exportto'] = round($value/1000,2);

echo json_encode($result);
?>
