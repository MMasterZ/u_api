<?php 
//10. Backward linkages, all exporting sectors (Back_link_sector)
//imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//source_country = source countrh ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');

$data = json_decode(file_get_contents("php://input"), true);
$exp_country= $data['exporting'];
$imp_country = $data['importing'];
$year = $data['year'];
$source = $data['source'];
// $exp_country = ['THA'];
// $imp_country = ['CHN','MYS'];
// $year = ['2017'];
// $source = ['JPN','KOR'];


// Import Country
$impText = ' and imp_country in (';
foreach($imp_country as $impData){
    $impText = $impText . "'".  $impData . "',";
}
$impText = substr($impText,0,-1);
$impText = $impText . ")";

// source Country
$sourceText = ' and source_country in (';
foreach($source as $sourceData){
    $sourceText = $sourceText . "'".  $sourceData . "',";
}
$sourceText = substr($sourceText,0,-1);
$sourceText = $sourceText . ")";


$final = [];

foreach($exp_country as $expData){
    foreach($year as $yearData){
        $tableName =  $expData . "_" . $yearData;
        $sql  = "select sum(value) as sum,source_country,exp_country,exp_sector, imp_country  from " . $tableName . "  where (variable = 'fva_fin_yl' or variable='fva_int_yl' )" . $impText . $sourceText . " group by exp_sector, imp_country, source_country" ;
        // echo $sql;
        $value = $db->query($sql)->fetchAll();
        $final = array_merge($final,$value);
    }
}

for($i=0;$i < count($final);$i++){
    $result[$i]['source_country'] = $final[$i]['source_country'];
     $result[$i]['variable_set'] = "-";
    $result[$i]['exp_country'] = $final[$i]['exp_country'];
    $result[$i]['exp_sector'] = $final[$i]['exp_sector'];
    $result[$i]['imp_country'] = $final[$i]['imp_country'];
    $result[$i]['value'] = round($final[$i]['sum'],2);
    $result[$i]['year'] = $final[$i]['year'];
    $result[$i]['indicator'] = 'Back_link_sector';
}

 echo json_encode($result);
?>
