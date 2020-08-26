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

if($sector == 0){
$sql  = "select sum(value) as sum,source_country,exp_country, imp_country  from " . $tableName . " 
where exp_country='" . $exp_country. "'and imp_country = '". $imp_country ."' and year = " . $year ." and (variable = 'fva_fin_yl' or variable='fva_int_yl' )  group by source_country" ;
$value = $db->query($sql)->fetchAll();

} else {
 $sql  = "select sum(value) as sum,source_country,exp_sector, exp_country, imp_country  from 
" . $tableName . " 
where exp_country='" . $exp_country. "'and exp_sector = '" . $sector_data[$sector] . "' and imp_country = '". $imp_country ."' and year = " . $year ." and (variable = 'fva_fin_yl' or variable='fva_int_yl' )  group by source_country" ;
$value = $db->query($sql)->fetchAll(); 
}


$result[0]['id'] = "A";
$result[0]['name'] = "Asia-Pacific";
$result[0]['color'] = "#2381B8";

$result[1]['id'] = "B";
$result[1]['name'] = "Europe";
$result[1]['color'] = "#EB1E63";

$result[2]['id'] = "C";
$result[2]['name'] = "North America";
$result[2]['color'] = "#F99704";

$result[3]['id'] = "D";
$result[3]['name'] = "Latin America";
$result[3]['color'] = "#2D9687";

$result[4]['id'] = "E";
$result[4]['name'] = "Rest of the world";
$result[4]['color'] = "#9C26B3";

for($i=0;$i< count($value);$i++){
     $area = $db->select("country_list",["area","name"],["iso"=>$value[$i]['source_country']]);
     If($area[0]['name'] == 'Rest of the World'){
        $result[$i+5]['name'] = "Other";
     } else {
          $result[$i+5]['name'] = $area[0]['name'];
     }
    
    if($area[0]['area'] == 'Asia-Pacific'){
        $result[$i+5]['parent'] = "A";
    } else if($area[0]['area'] == 'Europe'){
        $result[$i+5]['parent'] = "B";
    } else if($area[0]['area'] == 'North America'){
        $result[$i+5]['parent'] = "C";
    } else if($area[0]['area'] == 'Latin America'){
        $result[$i+5]['parent'] = "D";
    } else if($area[0]['area'] == 'Rest of the world'){
        $result[$i+5]['parent'] = "E";
    }
    $result[$i+5]['value'] = round($value[$i]['sum'],2);

}

 echo json_encode($result);
?>
