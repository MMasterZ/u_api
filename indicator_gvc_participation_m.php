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

// $exp_country = ['THA',];
// $imp_country = ['CHN'];
// $year = ['2017'];
// $sector = [1,12];

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
// echo $sectorText;

$final1 = [];
$final2 = [];
$final3 = [];
$final1A = [];
$final2A = [];
$final3A = [];
foreach($exp_country as $expData){
    foreach($year as $yearData){
        $tableName =  $expData . "_" . $yearData;
        if(count($sector) > 0){  
        $sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2'  or variable='DVA_INTrex3') ". $impText . $sectorText . " group by imp_country, exp_sector" ;
        $value = $db->query($sql)->fetchAll();
        $final1 = array_merge($final1,$value);
        
        $sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'MVA_FIN' or variable='MVA_INT'  or variable='OVA_FIN'  or variable='OVA_INT') ". $impText . $sectorText . " group by imp_country, exp_sector" ;
        $value = $db->query($sql)->fetchAll();
        $final2 = array_merge($final2,$value);
        
        $sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'DDC_FIN' or variable='DDC_INT'  or variable='MDC'  or variable='ODC') ". $impText . $sectorText . " group by imp_country, exp_sector" ;
        $value = $db->query($sql)->fetchAll();
        $final3 = array_merge($final3,$value);
        }
        if($sectorZero == 1){
          $sql  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2'  or variable='DVA_INTrex3') ". $impText  . " group by imp_country" ;
        $value = $db->query($sql)->fetchAll();
        $final1A = array_merge($final1A,$value);
        
        $sql  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'MVA_FIN' or variable='MVA_INT'  or variable='OVA_FIN'  or variable='OVA_INT') ". $impText . " group by imp_country" ;
        $value = $db->query($sql)->fetchAll();
        $final2A = array_merge($final2A,$value);
       $sql  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'DDC_FIN' or variable='DDC_INT'  or variable='MDC'  or variable='ODC') ". $impText . " group by imp_country" ;
        $value = $db->query($sql)->fetchAll();
        $final3A = array_merge($final3A,$value);
      }

        // print_r($final1A);
        
    }
}

for($i=0;$i< count($final1);$i++){

    $result[$i]['exp_country'] = $final1[$i][1];
    $result[$i]['exp_sector'] = $final1[$i][3];
    $result[$i]['imp_country'] = $final1[$i][2];
    $result[$i]['variable_set'] = "forward";
    $result[$i]['value'] = round($final1[$i][0],2);
    $result[$i]['year'] = $final1[$i][4];
    $result[$i]['indicator'] = 'GVC_part_$';
}
for($j=0;$j< count($final2);$j++){
    $result[$j+count($final2)]['exp_country'] = $final2[$j][1];
    $result[$j+count($final2)]['exp_sector'] = $final2[$j][3];
    $result[$j+count($final2)]['imp_country'] = $final2[$j][2];
    $result[$j+count($final2)]['variable_set'] = "backward";
    $result[$j+count($final2)]['value'] = round($final2[$j][0],2);
    $result[$j+count($final2)]['year'] = $final2[$j][4];
    $result[$j+count($final2)]['indicator'] = 'GVC_part_$';
}
for($j=0;$j< count($final3);$j++){
    $result[$j+count($final3)+count($final2)]['exp_country'] = $final3[$j][1];
    $result[$j+count($final3)+count($final2)]['exp_sector'] = $final3[$j][3];
    $result[$j+count($final3)+count($final2)]['imp_country'] = $final3[$j][2];
    $result[$j+count($final3)+count($final2)]['variable_set'] = "double";
    $result[$j+count($final3)+count($final2)]['value'] = round($final3[$j][0],2);
    $result[$j+count($final3)+count($final2)]['year'] = $final3[$j][4];
    $result[$j+count($final3)+count($final2)]['indicator'] = 'GVC_part_$';
}
$sumx = count($final1) + count($final2) + count($final3);
// echo $sumx;
// echo count($final1A);
for($j=0;$j< count($final1A);$j++){
   $result[$j+$sumx]['exp_country'] = $final1A[$j][1];
    $result[$j+$sumx]['exp_sector'] = 'all';
    $result[$j+$sumx]['imp_country'] = $final1A[$j][2];
    $result[$j+$sumx]['variable_set'] = "forward";
    $result[$j+$sumx]['value'] = round($final1A[$j][0],2);
    $result[$j+$sumx]['year'] = $final1A[$j][3];
    $result[$j+$sumx]['indicator'] = 'GVC_part_$';
}
$sumx = count($final1 ) + count($final2) + count($final3) + count($final1A);

for($j=0;$j< count($final2A);$j++){
   $result[$j+$sumx]['exp_country'] = $final2A[$j][1];
    $result[$j+$sumx]['exp_sector'] = 'all';
    $result[$j+$sumx]['imp_country'] = $final2A[$j][2];
    $result[$j+$sumx]['variable_set'] = "backward";
    $result[$j+$sumx]['value'] = round($final2A[$j][0],2);
    $result[$j+$sumx]['year'] = $final2A[$j][3];
    $result[$j+$sumx]['indicator'] = 'GVC_part_$';
}
$sumx = count($final1 ) + count($final2) + count($final3) + count($final1A)+ count($final2A);

for($j=0;$j< count($final3A);$j++){
   $result[$j+$sumx]['exp_country'] = $final3A[$j][1];
    $result[$j+$sumx]['exp_sector'] = 'all';
    $result[$j+$sumx]['imp_country'] = $final3A[$j][2];
    $result[$j+$sumx]['variable_set'] = "double";
    $result[$j+$sumx]['value'] = round($final3A[$j][0],2);
    $result[$j+$sumx]['year'] = $final3A[$j][3];
    $result[$j+$sumx]['indicator'] = 'GVC_part_$';
}

echo json_encode($result);
?>
