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
    $result[$country_data[$i]]['Agriculture']['value'] =0;
    $result[$country_data[$i]]['Mining']['value'] = 0;
    $result[$country_data[$i]]['Low tech']['value'] = 0;
    $result[$country_data[$i]]['High and medium tech']['value'] = 0;
    $result[$country_data[$i]]['Utilities']['value'] = 0;
    $result[$country_data[$i]]['Construction']['value'] = 0;
    $result[$country_data[$i]]['Trade and repair service']['value'] = 0;
    $result[$country_data[$i]]['Tourism']['value'] = 0;
    $result[$country_data[$i]]['Transport service']['value'] = 0;
    $result[$country_data[$i]]['ICT service']['value'] = 0;
    $result[$country_data[$i]]['Financial service']['value'] = 0;
    $result[$country_data[$i]]['Property service']['value'] = 0;
    $result[$country_data[$i]]['Public and welfare service']['value'] = 0;
    $result[$country_data[$i]]['Private household service']['value'] = 0;
    
    $tableName = $country_data[$i] . "_" . $year;
    $sql  = "select sum(value) as sum, exp_sector  from " . $tableName . " where variable = 'fva_fin_yl' or variable='fva_int_yl' group by exp_sector" ;
    $value1 = $db->query($sql)->fetchAll();
    //add value to sector grouping
    for($j=0;$j<count($value1);$j++){
        $grouping = $db->select("sector_data","grouping",[name=>$value1[$j]['exp_sector']]);
     $result[$country_data[$i]][$grouping[0]]['value'] += $value1[$j]['sum'];
    }
    //total
    $sql  = "select sum(value) as sum  from " . $tableName . "  
  where variable = 'total_export'" ;
   $value2 = $db->query($sql)->fetchAll();
   $total = round($value2[0]['sum'],2);

   
    //ทำเป็นทศนิยม 2 ตำแหน่ง
    $result[$country_data[$i]]['Agriculture']['value'] =round($result[$country_data[$i]]['Agriculture']['value'],2);
    $result[$country_data[$i]]['Agriculture']['ratio'] =round($result[$country_data[$i]]['Agriculture']['value']/$total*100,2);

    $result[$country_data[$i]]['Mining']['value'] =round($result[$country_data[$i]]['Mining']['value'],2);
    $result[$country_data[$i]]['Mining']['ratio'] =round($result[$country_data[$i]]['Mining']['value']/$total*100,2);

    $result[$country_data[$i]]['Low tech']['value'] =round($result[$country_data[$i]]['Low tech']['value'],2);
    $result[$country_data[$i]]['Low tech']['ratio'] =round($result[$country_data[$i]]['Low tech']['value']/$total*100,2);


    $result[$country_data[$i]]['High and medium tech']['value'] =round($result[$country_data[$i]]['High and medium tech']['value'],2);
    $result[$country_data[$i]]['High and medium tech']['ratio'] =round($result[$country_data[$i]]['High and medium tech']['value']/$total*100,2);


    $result[$country_data[$i]]['Utilities']['value'] =round($result[$country_data[$i]]['Utilities']['value'],2);
    $result[$country_data[$i]]['Utilities']['ratio'] =round($result[$country_data[$i]]['Utilities']['value']/$total*100,2);

    $result[$country_data[$i]]['Construction']['value'] =round($result[$country_data[$i]]['Construction']['value'],2);
    $result[$country_data[$i]]['Construction']['ratio'] =round($result[$country_data[$i]]['Construction']['value']/$total*100,2);


    $result[$country_data[$i]]['Trade and repair service']['value'] =round($result[$country_data[$i]]['Trade and repair service']['value'],2);
    $result[$country_data[$i]]['Trade and repair service']['ratio'] =round($result[$country_data[$i]]['Trade and repair service']['value']/$total*100,2);


    $result[$country_data[$i]]['Tourism']['value'] =round($result[$country_data[$i]]['Tourism']['value'],2);
     $result[$country_data[$i]]['Tourism']['ratio'] =round($result[$country_data[$i]]['Tourism']['value']/$total*100,2);


    $result[$country_data[$i]]['Transport service']['value'] =round($result[$country_data[$i]]['Transport service']['value'],2);
    $result[$country_data[$i]]['Transport service']['ratio'] =round($result[$country_data[$i]]['Transport service']['value']/$total*100,2);

    $result[$country_data[$i]]['ICT service']['value'] =round($result[$country_data[$i]]['ICT service']['value'],2);
    $result[$country_data[$i]]['ICT service']['ratio'] =round($result[$country_data[$i]]['ICT service']['value']/$total*100,2);


    $result[$country_data[$i]]['Financial service']['value'] =round($result[$country_data[$i]]['Financial service']['value'],2);
    $result[$country_data[$i]]['Financial service']['ratio'] =round($result[$country_data[$i]]['Financial service']['value']/$total*100,2);


    $result[$country_data[$i]]['Property service']['value'] =round($result[$country_data[$i]]['Property service']['value'],2);
    $result[$country_data[$i]]['Property service']['ratio'] =round($result[$country_data[$i]]['Property service']['value']/$total*100,2);


    $result[$country_data[$i]]['Public and welfare service']['value'] =round($result[$country_data[$i]]['Public and welfare service']['value'],2);
    $result[$country_data[$i]]['Public and welfare service']['ratio'] =round($result[$country_data[$i]]['Public and welfare service']['value']/$total*100,2);

    $result[$country_data[$i]]['Private household service']['value'] =round($result[$country_data[$i]]['Private household service']['value'],2);
    $result[$country_data[$i]]['Private household service']['ratio'] =round($result[$country_data[$i]]['Private household service']['value']/$total*100,2);

}

echo "\n**********10********\n";
  echo json_encode($result);
$dataInput = json_encode($result);
$db->update("country_brief",["set10"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);
?>