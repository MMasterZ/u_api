<?php 
//9. Backward linkages, all source countries (Back link country)
//imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017
//sector = sector ส่งมาเป็น id ตัวเลข sector 
require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];
$tableName = $exp_country . "_" . $year;

if($sector == 0){
$sql  = "select sum(value) as sum,source_country,exp_country, imp_country  from " . $tableName . " 
where exp_country='" . $exp_country. "'and imp_country = '". $imp_country ."' and year = " . $year ." and (variable = 'fva_yl'  )  group by source_country" ;
$value = $db->query($sql)->fetchAll();

} else {
 $sql  = "select sum(value) as sum,source_country,exp_sector, exp_country, imp_country  from 
" . $tableName . " 
where exp_country='" . $exp_country. "'and exp_sector = '" . $sector_data[$sector] . "' and imp_country = '". $imp_country ."' and year = " . $year ." and (variable = 'fva_yl'  )  group by source_country" ;
$value = $db->query($sql)->fetchAll(); 
}

for($i=0;$i< count($value);$i++){
    $result[$i]['source_country'] = $value[$i]['source_country'];
    $result[$i]['exp_country'] = $exp_country;
    if($sector ==0){
        $result[$i]['exp_sector'] = "All";
    } else {
        $result[$i]['exp_sector'] = $value[$i]['exp_sector'];
    }
    $result[$i]['imp_country'] = $imp_country;
    $result[$i]['variable_set'] = '-';
    $result[$i]['value'] = round($value[$i]['sum'],2);
    $result[$i]['year'] = $year;
    $result[$i]['indicator'] = 'Back_link_country';
}

 echo json_encode($result);
?>
