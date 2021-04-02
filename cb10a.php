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

    $result['Agriculture']['value'] =0;
    $result['Mining']['value'] = 0;
    $result['Low tech']['value'] = 0;
    $result['High and medium tech']['value'] = 0;
    $result['Utilities']['value'] = 0;
    $result['Construction']['value'] = 0;
    $result['Trade and repair service']['value'] = 0;
    $result['Tourism']['value'] = 0;
    $result['Transport service']['value'] = 0;
    $result['ICT service']['value'] = 0;
    $result['Financial service']['value'] = 0;
    $result['Property service']['value'] = 0;
    $result['Public and welfare service']['value'] = 0;
    $result['Private household service']['value'] = 0;
    $result['total'] =0;
    for($i=0; $i<count($country_data);$i++){
        $tableName = $country_data[$i] . "_" . $year;
        $sql  = "select sum(value) as sum, exp_sector  from " . $tableName . " where variable = 'fva_yl' group by exp_sector" ;
        $value1 = $db->query($sql)->fetchAll();

        //add value to sector grouping
        for($j=0;$j<count($value1);$j++){
            $grouping = $db->select("sector_data","grouping",[name=>$value1[$j]['exp_sector']]);
        $result[$grouping[0]]['value'] += $value1[$j]['sum'];
        }

        //total
    $sql  = "select sum(value) as sum  from " . $tableName . "  
  where variable = 'total_export'" ;
   $value2 = $db->query($sql)->fetchAll();
   $result['total'] += round($value2[0]['sum'],2);
    }

    $result['Agriculture']['value'] =round($result['Agriculture']['value'] /$result['total']*100,2);
    $result['Mining']['value'] =round($result['Mining']['value'] /$result['total']*100,2);
    $result['Low tech']['value'] =round($result['Low tech']['value'] /$result['total']*100,2);
    $result['High and medium tech']['value'] =round($result['High and medium tech']['value'] /$result['total']*100,2);
    $result['Utilities']['value'] =round($result['Utilities']['value'] /$result['total']*100,2);
    $result['Construction']['value']=round($result['Construction']['value'] /$result['total']*100,2);
    $result['Trade and repair service']['value']=round($result['Trade and repair service']['value'] /$result['total']*100,2);
    $result['Tourism']['value']=round($result['Tourism']['value'] /$result['total']*100,2);
    $result['Transport service']['value']=round($result['Transport service']['value'] /$result['total']*100,2);
    $result['ICT service']['value']=round($result['ICT service']['value'] /$result['total']*100,2);
    $result['Financial service']['value']=round($result['Financial service']['value'] /$result['total']*100,2);
    $result['Property service']['value']=round($result['Property service']['value'] /$result['total']*100,2);
    $result['Public and welfare service']['value']=round($result['Public and welfare service']['value'] /$result['total']*100,2);
    $result['Private household service']['value']=round($result['Private household service']['value'] /$result['total']*100,2);
   
    echo "\n**********10A********\n";
  echo json_encode($result);
  $dataInput = json_encode($result);
$db->update("country_brief",["set10a"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);
?>