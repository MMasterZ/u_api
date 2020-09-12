<?php 
//5. Imported content in gross exports (Imp_cont)
//imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017
//sector = sector ส่งมาเป็น id ตัวเลข sector 
require_once('connection.php');
require_once('sector_data.php');

$data = json_decode(file_get_contents("php://input"), true);
$exp_country= $data['exporting'];
$imp_country = $data['importing'];
$year = $data['year'];
$sector = $data['sector'];

// $exp_country = ['THA','BOL'];
// $imp_country = ['CHN','THA','JPN'];
// $year = ['2007','2017'];
// $sector = [1,3,5];

$tableName = $exp_country . "_" . $year;

// Import Country
$impText = ' and imp_country in (';
foreach($imp_country as $impData){
    $impText = $impText . "'".  $impData . "',";
}
$impText = substr($impText,0,-1);
$impText = $impText . ")";

//Sector
$sectorText = '';
if($sector[0] != 0 ){
    $sectorText = ' and exp_sector in(';
    foreach($sector as $sectorData){
    $sectorText = $sectorText . "'".  $sector_data[$sectorData] . "',";
}
$sectorText = substr($sectorText,0,-1);
$sectorText = $sectorText . ")";
}

$final = [];

foreach($exp_country as $expData){
    foreach($year as $yearData){
        $tableName =  $expData . "_" . $yearData;
        $sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'MVA_FIN' or variable='MVA_INT'  or variable='OVA_FIN' or variable='OVA_INT') ". $impText . $sectorText . " group by imp_country, exp_sector" ;
        $value = $db->query($sql)->fetchAll();
        $final = array_merge($final,$value);
        
    }
}

for($i=0;$i< count($final);$i++){

    $result[$i]['source_country'] = "-";
    $result[$i]['exp_country'] = $final[$i][1];
    $result[$i]['exp_sector'] = $final[$i][3];
    $result[$i]['imp_country'] = $final[$i][2];
    $result[$i]['variable_set'] = "-";
    $result[$i]['value'] = round($final[$i][0],2);
    $result[$i]['year'] = $final[$i][4];
    $result[$i]['indicator'] = 'Imp_cont';
}
echo json_encode($result);
?>
