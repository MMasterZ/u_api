<?php
require_once('connection.php');
require_once('sector_data.php');
$data = json_decode(file_get_contents("php://input"), true);
$exp_country= $data['exporting'];
$source_country = $data['sourcing'];
$year = $data['year'];

// $exp_country = 'THA';
// $source_country = 'JPN';
// $year = 2017;

$tableName = strtolower($exp_country) . "_" . $year;

 $sql  = "select sum(value) as sum,  exp_sector from " . $tableName . " where  source_country = '" . $source_country ."' and (variable = 'fva_yl')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  group by exp_sector order by sum DESC limit 5" ;
 $backward2 = $db->query($sql)->fetchAll();

 for($i=0;$i<=4;$i++){
    $sector = $backward2[$i]['exp_sector'];
    $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    $result[$i]['sector'] = $sector_short[0];
    $result[$i]['val'] = round($backward2[$i]['sum'],2);
 }
   
 echo json_encode($result);

?>