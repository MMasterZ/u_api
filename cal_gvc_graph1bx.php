<?php
require_once('connection.php');
require_once('sector_data.php');
$data = json_decode(file_get_contents("php://input"), true);
$exp_country= $data['exporting'];
$sector = $data['sector'];
$year = $data['year'];

$exp_country= 'THA';
$sector = 'metals';
$year = 2017;
$tableName = $exp_country . "_" . $year;
 $sector_full = $db->select("sector_data","name",["shortname"=>$sector]);

 $sql  = "select sum(value) as sum,  imp_country from " . $tableName . " where exp_sector = '" . $sector_full[0] ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')   group by imp_country order by sum DESC limit 6" ;
 $backward2 = $db->query($sql)->fetchAll();

 $count=0;
 for($i=0;$i<=6;$i++){
     if($backward2[$i]['imp_country'] != "RoW"){
         if($count <=4){
            $result[$count]['country'] = $backward2[$i]['imp_country'];
            $result[$count]['val'] = round($backward2[$i]['sum'],2);
            $count++; 
         }
         
     }
 }
   
 echo json_encode($result);

?>