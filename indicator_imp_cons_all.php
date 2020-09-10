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



$sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " 
where variable = 'DVA_FIN' or variable='DVA_INT'   group by imp_country, exp_sector " ;
$value = $db->query($sql)->fetchAll();




print_r($value);

// $result['source_country'] = "-";
// $result['exp_country'] = $exp_country;
// $result['exp_sector'] = $sector_data[$sector];
// $result['imp_country'] = $imp_country;
// $result['variable_set'] = "-";
// $result['value'] = round($value,2);
// $result['year'] = $year;
// $result['indicator'] = 'imp_cons';


//  echo json_encode($result);



?>
