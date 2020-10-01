<?php 
//6. Domestic value-added trade balance (DVA_tradebalance)
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
// $imp_country = ['CHN'];
// $year = ['2017'];
// $sector = [0,1,12];


//***************Find Value
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
$finalA = [];
$finalgA = [];

$final = [];
$finalg=[];

foreach($exp_country as $expData){
    foreach($year as $yearData){
        $tableName =  $expData . "_" . $yearData;
        if(count($sector) > 0){  
        $sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'total_export') ". $impText . $sectorText . " group by imp_country, exp_sector" ;
        $value = $db->query($sql)->fetchAll();
        $final = array_merge($final,$value);
        } 
        if($sectorZero == 1){
           $sql  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'total_export') ". $impText .  " group by imp_country" ;
        $value = $db->query($sql)->fetchAll();
        $finalA = array_merge($finalA,$value);
        }
       

    }
}



//******************Find Value2

// Import Country
$impText = ' and imp_country in (';
foreach($exp_country as $impData){
    $impText = $impText . "'".  $impData . "',";
}
$impText = substr($impText,0,-1);
$impText = $impText . ")";


$final2 = [];
$final2A = [];

foreach($imp_country as $expData){
    foreach($year as $yearData){
        $tableName =  $expData . "_" . $yearData;
        if(count($sector) > 0){ 
        $sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'total_export' ) ". $impText . $sectorText . " group by imp_country, exp_sector" ;
        $value = $db->query($sql)->fetchAll();
        $final2 = array_merge($final2,$value);
        }
        if($sectorZero == 1){
          $sql  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'total_export' ) ". $impText . " group by imp_country" ;
        $value = $db->query($sql)->fetchAll();
        $final2A = array_merge($final2A,$value);
        }
    }
}

for($i=0;$i<count($final);$i++){
  $exp1 = $final[$i][1];
  $imp1 = $final[$i][2];
  $sector1 = $final[$i][3];
  $year1 = $final[$i][4];
  $value = $final[$i][0];
  for($j=0;$j<count($final2);$j++){
    if($final2[$j][1] == $imp1 && $final2[$j][2] == $exp1 && $final2[$j][3] == $sector1 && $final2[$j][4] == $year1){
      $value1 = $final2[$j][0];
    break;
    }
  }

  // echo $value . "***" . $value1 . "***" . $value_f;
  // echo ($value-$value1)/$value_f*100;
    $result[$i]['exp_country'] = $final[$i][1];
    $result[$i]['exp_sector'] = $final[$i][3];
    $result[$i]['imp_country'] = $final[$i][2];
    $result[$i]['value'] = round(($value-$value1),2);
    $result[$i]['year'] = $final[$i][4];
    $result[$i]['indicator'] = 'Gross_tradebalance_$';
}

$sumx = count($result);
for($i=0;$i<count($finalA);$i++){
  $exp1 = $finalA[$i]['exp_country'];
  $imp1 = $finalA[$i]['imp_country'];
  $year1 = $finalA[$i]['year'];
  $value = $finalA[$i]['sum'];
  for($j=0;$j<count($final2A);$j++){
    if($final2A[$j]['exp_country'] == $imp1 && $final2A[$j]['imp_country'] == $exp1 && $final2A[$j]['year'] == $year1){
      $value1 = $final2A[$j][0];
    break;
    }
  }

  // echo $value . "***" . $value1 . "***" . $value_f;
  // echo ($value-$value1)/$value_f*100;
    $result[$i+$sumx]['exp_country'] = $finalA[$i]['exp_country'];
    $result[$i+$sumx]['exp_sector'] = 'all';
    $result[$i+$sumx]['imp_country'] = $finalA[$i]['imp_country'];
    $result[$i+$sumx]['value'] = round(($value-$value1),2);
    $result[$i+$sumx]['year'] = $finalA[$i]['year'];
    $result[$i+$sumx]['indicator'] = 'Gross_tradebalance_$';
}
echo json_encode($result);

?>
