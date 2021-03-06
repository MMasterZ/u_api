<?php

$tableName = $exp_country . "_" . $year;


//backward
$sql  = "select sum(value) as sum, source_country from " . $tableName . " where  (variable = 'fva_yl' )  group by source_country order by sum DESC limit 11" ;
$backward = $db->query($sql)->fetchAll();


//extra cal -a-1
$sql  = "select sum(value) as sum from " . $tableName . " where  (variable = 'MVA_FIN' or variable='fva_yl' )  group by source_country order by sum DESC limit 11"  ;
$backward_a_1 = $db->query($sql)->fetchAll();


$sql  = "select sum(value) as sum from " . $tableName . " where  (variable='total_export') " ;
$backward_a_2 = $db->query($sql)->fetchAll();
 
$count = 0;
for($i=0;$i<count($backward);$i++){
    $source_country = $backward[$i]['source_country'];
        if($source_country !='RoW'){
        $result['backward'][$count]['country'] = $source_country;
        $result['backward'][$count]['precent'] = round($backward_a_1[$i]['sum']/$backward_a_2[0]['sum']*100,2);
        $result['backward'][$count]['value'] = round($backward_a_1[$i]['sum'],2);
        $count++;
        }
    if($count == 10){
        break;
    }

}

//forward
$sql  = "select sum(value) as sum, imp_country from " . $tableName . " where  (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')  group by imp_country order by sum DESC limit 11" ;
$backward = $db->query($sql)->fetchAll();

$sql  = "select sum(value) as sum from " . $tableName . " where ( variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3') group by imp_country order by sum DESC limit 11" ;
$backward_a_1 = $db->query($sql)->fetchAll();

$sql  = "select sum(value) as sum from " . $tableName . " where  (variable='total_export') " ;
$backward_a_2 = $db->query($sql)->fetchAll();

$count = 0;
for($i=0;$i<count($backward);$i++){
    $imp_country = $backward[$i]['imp_country'];
    if($imp_country !='RoW'){
        $result['forward'][$count]['country'] = $imp_country;
        $result['forward'][$count]['precent'] = round($backward_a_1[$i]['sum']/$backward_a_2[0]['sum']*100,2);
        $result['forward'][$count]['value'] = round($backward_a_1[$i]['sum'],2);
        $count++;
    }
    if($count == 10){
        break;
    }
    

}
echo "\n**********6********\n";
 echo json_encode($result);
 $dataInput = json_encode($result);
$db->update("country_brief",["set6"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);

?>