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


$result['latinAmerica']['value'] = 0;
$result['asiaPacific']['value'] =0;
$result['europe']['value'] = 0;
$result['northAmerica']['value'] = 0;
$result['row']['value'] =0;
 $result['total']  = 0;

for($i=0; $i<count($country_data);$i++){
    $tableName = $country_data[$i] . "_" . $year;
    $sql  = "select sum(value) as sum, source_country  from " . $tableName . "  
  where variable = 'fva_fin_yl' or variable='fva_int_yl' group by source_country" ;
  $value1 = $db->query($sql)->fetchAll();

      //วนใส่ region
    for($j=0;$j<count($value1);$j++){
       
 $country_data2 = $db->select("country_list","area",[iso=>$value1[$j]['source_country']]);
 
        if($country_data2[0] == 'Latin America'){
            $result['latinAmerica']['value'] += round($value1[$j]['sum'],2);
        } else if($country_data2[0] == 'Asia-Pacific'){
            $result['asiaPacific']['value']+= $value1[$j]['sum'];
        } else if($country_data2[0] == 'Europe'){
            $result['europe']['value']+= $value1[$j]['sum'];
        }else if($country_data2[0] == 'North America'){
            $result['northAmerica']['value']+= $value1[$j]['sum'];
        } else if($country_data2[0] == 'Rest of the world'){
            $result['row']['value']+= $value1[$j]['sum'];
        }

          
    }

    $sql  = "select sum(value) as sum  from " . $tableName . " where variable = 'total_export'" ;
   $value2 = $db->query($sql)->fetchAll();
   $result['total'] += round($value2[0]['sum'],2);
}

$result2['asiaPacific']['value'] = round($result['asiaPacific']['value'] / $result['total']*100,2);

$result2['latinAmerica']['value'] = round($result['latinAmerica']['value'] / $result['total']*100,2);

$result2['europe']['value'] = round($result['europe']['value'] / $result['total']*100,2);

$result2['northAmerica']['value'] = round($result['northAmerica']['value'] / $result['total']*100,2);

$result2['row']['value'] = round($result['row']['value'] / $result['total']*100,2);

echo "\n**********9A********\n";
  echo json_encode($result2);

      $dataInput = json_encode($result2);
$db->update("country_brief",["set9a"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);
?>
