<?php 
//11. Forward linkages, all importing country (Forward_link_country)
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//sector = sector ส่งมาเป็น id ตัวเลข sector 
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');

$exp_country = $_GET['exp_country'];
$sector = $_GET['sector'];
$year = $_GET['year'];


if($sector == 0){
$sql  = "select sum(value) as sum,exp_country, imp_country  from country_data 
where exp_country='" . $exp_country. "' and year = " . $year ." and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' )  group by imp_country" ;
$value = $db->query($sql)->fetchAll();

} else {

    $sql  = "select sum(value) as sum,exp_country, imp_country from country_data 
where exp_country='" . $exp_country.  "'and exp_sector = '" . $sector_data[$sector] ."' and year = " . $year ." and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' )  group by imp_country" ;

$value = $db->query($sql)->fetchAll(); 
}

for($i=0;$i< count($value);$i++){
    $result[$i]['source_country'] = "-";
    $result[$i]['exp_country'] = $exp_country;
    $result[$i]['exp_sector'] = $sector_data[$sector];
    $result[$i]['imp_country'] = $value[$i]['imp_country'];
    $result[$i]['variable_set'] = "-";
    $result[$i]['value'] = $value[$i]['sum'];
    $result[$i]['year'] = $year;
    $result[$i]['indicator'] = 'Forward_link_country';
}

 echo json_encode($result);
?>
