<?php 
///imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');
require_once('country_list.php');

$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];
$tableName = strtolower($exp_country) . "_" . $year;

//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
if(count($region_data)==0){
  $country_data = country_list($exp_country);
} else {
  $region = $region_data[0];
  //get country in same region
  $country_data = $db->select("country_list","iso",[
  region =>$region
  ]);
}

// print_r($country_data);
for($i=0; $i<count($country_data);$i++){
  $imp_country = $country_data[$i];
  $area = $db->select("country_list","name",["iso"=>$imp_country]);
  // $result[$i]['country'] =$area[0];
  $tableName = strtolower($imp_country) . "_" . $year;

  if($sector == 0){
    $sql  = "select sum(value) as sum,exp_country, imp_country  from " . $tableName . " where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' )  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))   group by imp_country" ;
  } else {
    $sql  = "select sum(value) as sum,exp_country, imp_country  from " . $tableName . " where exp_sector = '" . $sector_data[$sector] ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' )   and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  group by imp_country" ;
  }
  $value = $db->query($sql)->fetchAll();

  if($sector == 0){
    $sql2 = "select sum(value) as value  from " . strtolower($imp_country) . "_" . $year . " where (variable = 'total_export')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  " ;
 
     $value2 = $db->query($sql2)->fetchAll();
   $value2 =  $value2[0]['value'];
    
    // $value2 = $db->sum($tableName,"value",[
    // variable => ['total_export']
    // ]);
  } else {
     $sql2 = "select sum(value) as value  from " . strtolower($imp_country) . "_" . $year . " where exp_sector = '" . $sector_data[$sector] ."' and (variable = 'total_export')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  " ;
 
     $value2 = $db->query($sql2)->fetchAll();
   $value2 =  $value2[0]['value'];

  }

  for($j=0;$j<count($value);$j++){
    $result[$i][$j]['exp_country'] = $area[0];
    $area2 = $db->select("country_list",["name","area"],["iso"=>$value[$j]['imp_country']]);
    $result[$i][$j]['imp_country'] =$area2[0]['name'];
    $result[$i][$j]['area'] = $area2[0]['area'];
     if($value2 > 0){
    $result[$i][$j]['value'] = round($value[$j]['sum']/$value2*100,2);
     }else {
       $result[$i][$j]['value'] = 0;
     }
    $result[$i][$j]['valueM'] = round($value[$j]['sum'],2);
  }

  // echo "<BR>";
}
echo json_encode($result);
?>
