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
    $result['total'] = 0;

for($i=0; $i<count($country_data);$i++){
    $tableName = $country_data[$i] . "_" . $year;
    $sql  = "select sum(value) as sum, imp_country  from " . $tableName . "  
  where variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3'  group by imp_country" ;
  $value1 = $db->query($sql)->fetchAll();

  //วนใส่ region
    for($j=0;$j<count($value1);$j++){
         $country_data2 = $db->select("country_list","area",[iso=>$value1[$j]['imp_country']]);
 
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
    $sql  = "select sum(value) as sum  from " . $tableName . "  
  where variable = 'total_export'" ;
   $value2 = $db->query($sql)->fetchAll();
   $result['total'] += round($value2[0]['sum'],2);
}

$result2['latinAmerica'] = round($result['latinAmerica']['value']/ $result['total']*100,2);

$result2['asiaPacific'] = round($result['asiaPacific']['value']/ $result['total']*100,2);

$result2['europe'] = round($result['europe']['value']/ $result['total']*100,2);

$result2['northAmerica'] = round($result['northAmerica']['value']/ $result['total']*100,2);

$result2['row'] = round($result['row']['value']/ $result['total']*100,2);

echo "\n**********11A********\n";
  echo json_encode($result2);
   $dataInput = json_encode($result2);
  $db->update("country_brief",["set11a"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);
?>