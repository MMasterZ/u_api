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
$tableName = $exp_country . "_" . $year;


//Imported content in exports

if($sector == 0){
$value = $db->sum($tableName,"value",[
    imp_country => $imp_country,
    variable => ['fva_fin_yl','fva_int_yl']
]);

} else {
 $value = $db->sum($tableName,"value",[
    imp_country => $imp_country,
    variable => ['fva_fin_yl', 'fva_int_yl'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result['ImportedContent'] = round($value,2);

//Gross exports to

if($sector == 0){
$value = $db->sum($tableName,"value",[
    imp_country => $imp_country,
    variable => ['total_export']
]);

} else {
 $value = $db->sum($tableName,"value",[
    imp_country => $imp_country,
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result['grossExport'] = round($value,2);


 echo json_encode($result);
?>
