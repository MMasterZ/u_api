<?php 
//10. Backward linkages, all exporting sectors (Back_link_sector)
//imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
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


$sql  = "select sum(value) as sum,source_country,exp_country,exp_sector, imp_country  from " . $tableName . "  
where exp_country='" . $exp_country. "'and imp_country = '". $imp_country . "'and source_country = '". $source_country ."' and year = " . $year ." and (variable = 'fva_fin_yl' or variable='fva_int_yl' )  group by exp_sector" ;
$value = $db->query($sql)->fetchAll();


for($i=0;$i < count($value);$i++){
    $result[$i]['source_country'] = $source_country;
    $result[$i]['exp_country'] = $exp_country;
    $result[$i]['exp_sector'] = $value[$i]['exp_sector'];
    $result[$i]['imp_country'] = $imp_country;
    $result[$i]['value'] = round($value[$i]['sum'],2);
    $result[$i]['year'] = $year;
    $result[$i]['indicator'] = 'Back_link_sector';
}

 echo json_encode($result);
?>
