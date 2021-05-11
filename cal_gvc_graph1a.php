<?php
require_once('connection.php');
require_once('sector_data.php');
require_once('main_function.php');
$data = json_decode(file_get_contents("php://input"), true);
$exp_country= $data['exporting'];
$sector = $data['sector'];
$year = $data['year'];

$tableName = strtolower($exp_country) . "_" . $year;
 $sector_full = $db->select("sector_data","name",["shortname"=>$sector]);

$sql  = "select sum(value) as sum,  source_country from " . $tableName . " where  exp_sector = '" . $sector_full[0] ."' and (variable = 'fva_yl' ) and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  group by source_country order by sum DESC " ;
 $backward2 = $db->query($sql)->fetchAll();

 $count=0;
 for($i=0;$i<=30;$i++){
     
     if(checkCountry($backward2[$i]['source_country'])){
         if($count <=4){
            $result[$count]['country'] = $backward2[$i]['source_country'];
            $result[$count]['val'] = round($backward2[$i]['sum'],2);
            $count++; 
         }
         
     }
 }
   
 echo json_encode($result);

?>