<?php 

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

// $exp_country = ['THA'];
// $imp_country = ['USA'];
// $year = ['2017'];
// $sector = [0];

// Import Country
$impText = ' and imp_country in (';
foreach($imp_country as $impData){
    $impText = $impText . "'".  $impData . "',";
}
$impText = substr($impText,0,-1);
$impText = $impText . ")";

//Sector
$sectorText = '';
$sectorZero = 0;
if($sector[0] == 0){
    $sectorZero = 1;
   array_shift($sector);
}
// echo $sector;
$sectorText = ' and exp_sector in(';
foreach($sector as $sectorData){
$sectorText = $sectorText . "'".  $sector_data[$sectorData] . "',";


}
$sectorText = substr($sectorText,0,-1);
$sectorText = $sectorText . ")";

$final = [];
$final2 = [];

foreach($exp_country as $expData){
    foreach($year as $yearData){
        $tableName =  $expData . "_" . $yearData;
        if(count($sector) > 0){  
            $sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector,source_country, year  from " . $tableName . " where (variable = 'fva_fin_yl' or variable='fva_int_yl') ". $impText . $sectorText . " group by imp_country, exp_sector, source_country" ;
        //  echo $sql;
         $value = $db->query($sql)->fetchAll();
         $final = array_merge($final,$value);
        } 
        if($sectorZero == 1){
       $sql  = "select sum(value) as sum,exp_country, imp_country,source_country, year  from " . $tableName . " where (variable = 'fva_fin_yl' or variable='fva_int_yl') ". $impText .  " group by imp_country, source_country" ;
    //    echo $sql;
        
        $value = $db->query($sql)->fetchAll();
        $final2 = array_merge($final2,$value);
      }

        
    }
}

for($i=0;$i< count($final);$i++){

    $result[$i]['source_country'] =$final[$i]['source_country'];
    $result[$i]['exp_country'] = $final[$i]['exp_country'];
    $result[$i]['exp_sector'] = $final[$i]['exp_sector'];  
    $result[$i]['imp_country'] = $final[$i]['imp_country'];
    $result[$i]['value'] = round($final[$i][0],2);
    $result[$i]['year'] = $final[$i]['year'];
    $result[$i]['indicator'] = 'Back_link_country';
}
for($i=0;$i< count($final2);$i++){
    $result[$i+count($final)]['source_country'] =$final2[$i]['source_country'];
    $result[$i+count($final)]['exp_country'] = $final2[$i]['exp_country'];
    $result[$i+count($final)]['exp_sector'] = 'all';
    $result[$i+count($final)]['imp_country'] = $final2[$i]['imp_country'];
    $result[$i+count($final)]['value'] = round($final2[$i][0],2);
    $result[$i+count($final)]['year'] = $final2[$i]['year'];
    $result[$i+count($final)]['indicator'] = 'Back_link_country';
}
echo json_encode($result);
?>
