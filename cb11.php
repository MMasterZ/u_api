<?php
//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
$region = $region_data[0];

//get country in same region
$country_data = $db->select("country_list","iso",[
region =>$region
]);

for($i=0; $i<count($country_data);$i++){
    $result[$country_data[$i]]['latinAmerica']['value'] = 0;
    $result[$country_data[$i]]['latinAmerica']['ratio'] = 0;
    $result[$country_data[$i]]['asiaPacific']['value'] =0;
    $result[$country_data[$i]]['asiaPacific']['ratio']  =0;
    $result[$country_data[$i]]['europe']['value'] = 0;
    $result[$country_data[$i]]['europe']['ratio'] = 0;
    $result[$country_data[$i]]['northAmerica']['value'] = 0;
    $result[$country_data[$i]]['northAmerica']['ratio'] = 0;
    $result[$country_data[$i]]['row']['value'] =0;
    $result[$country_data[$i]]['row']['ratio'] =0;
    $tableName = $country_data[$i] . "_" . $year;
    $sql  = "select sum(value) as sum, imp_country  from " . $tableName . "  
  where variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3'  group by imp_country" ;
  $value1 = $db->query($sql)->fetchAll();
      //วนใส่ region
    for($j=0;$j<count($value1);$j++){
        if($value1[$j]['imp_country'] != $country_data[$i]){
 $country_data2 = $db->select("country_list","area",[iso=>$value1[$j]['imp_country']]);
 
        if($country_data2[0] == 'Latin America'){
            $result[$country_data[$i]]['latinAmerica']['value'] += round($value1[$j]['sum'],2);
        } else if($country_data2[0] == 'Asia-Pacific'){
            $result[$country_data[$i]]['asiaPacific']['value']+= $value1[$j]['sum'];
        } else if($country_data2[0] == 'Europe'){
            $result[$country_data[$i]]['europe']['value']+= $value1[$j]['sum'];
        }else if($country_data2[0] == 'North America'){
            $result[$country_data[$i]]['northAmerica']['value']+= $value1[$j]['sum'];
        } else if($country_data2[0] == 'Rest of the world'){
            $result[$country_data[$i]]['row']['value']+= $value1[$j]['sum'];
        }

        }    
    }
    $sql  = "select sum(value) as sum  from " . $tableName . "  
  where variable = 'total_export'" ;
   $value2 = $db->query($sql)->fetchAll();
   $result[$country_data[$i]]['total'] = round($value2[0]['sum'],2);
   $result[$country_data[$i]]['latinAmerica']['value'] = round($result[$country_data[$i]]['latinAmerica']['value'],2);
   $result[$country_data[$i]]['asiaPacific']['value'] = round($result[$country_data[$i]]['asiaPacific']['value'],2);
   $result[$country_data[$i]]['europe']['value'] = round($result[$country_data[$i]]['europe']['value'],2);
    $result[$country_data[$i]]['northAmerica']['value'] = round($result[$country_data[$i]]['northAmerica']['value'],2);
    $result[$country_data[$i]]['row']['value'] = round($result[$country_data[$i]]['row']['value'],2);

   $result[$country_data[$i]]['latinAmerica']['ratio'] =  round($result[$country_data[$i]]['latinAmerica']['value'] / $value2[0]['sum']*100,4);
   $result[$country_data[$i]]['asiaPacific']['ratio'] =  round($result[$country_data[$i]]['asiaPacific']['value'] / $value2[0]['sum']*100,4);
   $result[$country_data[$i]]['europe']['ratio'] =  round($result[$country_data[$i]]['europe']['value'] / $value2[0]['sum']*100,4);
   $result[$country_data[$i]]['northAmerica']['ratio'] =  round($result[$country_data[$i]]['northAmerica']['value'] / $value2[0]['sum']*100,4);
$result[$country_data[$i]]['row']['ratio'] =  round($result[$country_data[$i]]['row']['value'] / $value2[0]['sum']*100,4);

}

echo "\n**********11********\n";
  echo json_encode($result);
   $dataInput = json_encode($result);
$db->update("country_brief",["set11"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);
?>