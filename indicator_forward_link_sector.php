<?php 
//12. Forward linkages, all exporting sectors (Forward_link_sector)
//imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$tableName = $exp_country . "_" . $year;


$sql  = "select sum(value) as sum,exp_country,exp_sector, imp_country  from ". $tableName . "  
where exp_country='" . $exp_country. "'and imp_country = '". $imp_country . "' and year = " . $year ." and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' )  group by exp_sector" ;
$value = $db->query($sql)->fetchAll();


for($i=0;$i< count($value);$i++){
$result[$i]['source_country'] = "-";
 $result[$i]['variable_set'] = "-";
    $result[$i]['exp_country'] = $exp_country;
    $result[$i]['exp_sector'] = $value[$i]['exp_sector'];
    $result[$i]['imp_country'] = $imp_country;
    $result[$i]['value'] = round($value[$i]['sum'],2);
    $result[$i]['year'] = $year;
    $result[$i]['indicator'] = 'Forward_link_sector';
}

 echo json_encode($result);
?>
