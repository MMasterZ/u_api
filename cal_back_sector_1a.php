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

$tableName = $exp_country . "_" . $year;



$value = $db->sum($tableName ,"value",[
    imp_country => $imp_country,
    source_country =>$source_country,
    variable => ['fva_fin_yl', 'fva_int_yl']
]);

$result['fromsource'] = round($value,2);

$value = $db->sum($tableName ,"value",[
    imp_country => $imp_country,
    variable => ['total_export']
]);

$result['exportto'] = round($value,2);

 echo json_encode($result);
?>
