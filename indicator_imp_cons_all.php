<?php 
//1. Gross exports used in importer's consumption (Imp_cons)
//imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017
//sector = sector ส่งมาเป็น id ตัวเลข sector 
require_once('connection.php');
require_once('sector_data.php');


$exp_country = $_GET['exp_country'];
$year = $_GET['year'];

$tableName = $exp_country . "_" . $year;



$sql  = "select sum(value) as value,exp_country, imp_country,exp_sector, year  from " . $tableName . " 
where variable = 'DVA_FIN' or variable='DVA_INT'   group by imp_country, exp_sector " ;
$value = $db->query($sql)->fetchAll();




print_r($value);




?>