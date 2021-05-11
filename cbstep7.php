<?php
require_once('connection.php');
require_once('main_function.php');
$_POST = json_decode(file_get_contents("php://input"),true);
$exp_country = $_POST['country'];
$year = $_POST['year'];
// $exp_country = 'THA';
// $year = 2017;


//set 9
//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
$region = $region_data[0];

//get country in same region
$country_data = $db->select("country_list","iso",[
region =>$region
]);
$result = array();


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
    $tableName = strtolower($country_data[$i]) . "_" . $year;
    $sql  = "select sum(value) as sum, source_country  from " . $tableName . "  
  where variable = 'fva_yl' and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))   group by source_country" ;
  $value1 = $db->query($sql)->fetchAll();

  
    //วนใส่ region
    for($j=0;$j<count($value1);$j++){
        if($value1[$j]['source_country'] != $country_data[$i]){
 $country_data2 = $db->select("country_list","area",[iso=>$value1[$j]['source_country']]);
 
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
  where variable = 'total_export' and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
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
// echo "\n**********9********\n";
//   echo json_encode($result);
   $set9 = json_encode($result);
// $db->update("country_brief",["set9"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);


//set 9a
//get region
// $region_data = $db->select("country_list","region",[
// iso =>$exp_country
// ]);
// $region = $region_data[0];

// //get country in same region
// $country_data = $db->select("country_list","iso",[
// region =>$region
// ]);
$result = array();
$result2 = array();

$result['latinAmerica']['value'] = 0;
$result['asiaPacific']['value'] =0;
$result['europe']['value'] = 0;
$result['northAmerica']['value'] = 0;
$result['row']['value'] =0;
 $result['total']  = 0;

for($i=0; $i<count($country_data);$i++){
    $tableName = strtolower($country_data[$i]) . "_" . $year;
    $sql  = "select sum(value) as sum, source_country  from " . $tableName . "  
  where variable = 'fva_yl'  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  group by source_country" ;
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

    $sql  = "select sum(value) as sum  from " . $tableName . " where variable = 'total_export'  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
   $value2 = $db->query($sql)->fetchAll();
   $result['total'] += round($value2[0]['sum'],2);
}

$result2['asiaPacific']['value'] = round($result['asiaPacific']['value'],2);
$result2['asiaPacific']['ratio'] = round($result['asiaPacific']['value'] / $result['total']*100,2);

$result2['latinAmerica']['value'] = round($result['latinAmerica']['value'],2);
$result2['latinAmerica']['ratio'] = round($result['latinAmerica']['value'] / $result['total']*100,2);

$result2['europe']['value'] = round($result['europe']['value'],2);
$result2['europe']['ratio'] = round($result['europe']['value'] / $result['total']*100,2);

$result2['northAmerica']['value'] = round($result['northAmerica']['value'],2);
$result2['northAmerica']['ratio'] = round($result['northAmerica']['value'] / $result['total']*100,2);


$result2['row']['value'] = round($result['row']['value'],2);
$result2['row']['ratio'] = round($result['row']['value'] / $result['total']*100,2);

// echo "\n**********9A********\n";
//   echo json_encode($result2);

$set9a = json_encode($result2);


//set 10
//get region
// $region_data = $db->select("country_list","region",[
// iso =>$exp_country
// ]);
// $region = $region_data[0];

// //get country in same region
// $country_data = $db->select("country_list","iso",[
// region =>$region
// ]);

$result = array();

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
    
    $tableName = strtolower($country_data[$i]) . "_" . $year;
    $sql  = "select sum(value) as sum, exp_sector  from " . $tableName . " where variable = 'fva_yl' and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) and ( source_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) group by exp_sector" ;
    $value1 = $db->query($sql)->fetchAll();
  
    
    //add value to sector grouping
    for($j=0;$j<count($value1);$j++){
        $grouping = $db->select("sector_data","grouping",[name=>$value1[$j]['exp_sector']]);
     $result[$country_data[$i]][$grouping[0]]['value'] += $value1[$j]['sum'];
    }
    //total
    $sql  = "select sum(value) as sum  from " . $tableName . "  
  where variable = 'total_export' and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
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

// echo "\n**********10********\n";
//   echo json_encode($result);
$set10 = json_encode($result);
// $db->update("country_brief",["set10"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);

//Set 10a
//get region
// $region_data = $db->select("country_list","region",[
// iso =>$exp_country
// ]);
// $region = $region_data[0];

// //get country in same region
// $country_data = $db->select("country_list","iso",[
// region =>$region
// ]);

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
        $tableName = strtolower($country_data[$i]) . "_" . $year;
        $sql  = "select sum(value) as sum, exp_sector  from " . $tableName . " where variable = 'fva_yl'  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  and ( source_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  group by exp_sector" ;
        $value1 = $db->query($sql)->fetchAll();

        //add value to sector grouping
        for($j=0;$j<count($value1);$j++){
            $grouping = $db->select("sector_data","grouping",[name=>$value1[$j]['exp_sector']]);
        $result[$grouping[0]]['value'] += $value1[$j]['sum'];
        }

        //total
    $sql  = "select sum(value) as sum  from " . $tableName . "  
  where variable = 'total_export'  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
   $value2 = $db->query($sql)->fetchAll();
   $result['total'] += round($value2[0]['sum'],2);
    }

    $result['Agriculture']['value'] =round($result['Agriculture']['value'],2);
    $result['Agriculture']['ratio'] =round($result['Agriculture']['value'] /$result['total']*100,2);
    $result['Mining']['value'] =round($result['Mining']['value'],2);
    $result['Mining']['ratio'] =round($result['Mining']['value'] /$result['total']*100,2);
    $result['Low tech']['value'] =round($result['Low tech']['value'],2);
    $result['Low tech']['ratio'] =round($result['Low tech']['value'] /$result['total']*100,2);
    $result['High and medium tech']['value'] =round($result['High and medium tech']['value'],2);
    $result['High and medium tech']['ratio'] =round($result['High and medium tech']['value'] /$result['total']*100,2);
    $result['Utilities']['value'] =round($result['Utilities']['value'],2);
    $result['Utilities']['ratio'] =round($result['Utilities']['value'] /$result['total']*100,2);
    $result['Construction']['value'] =round($result['Construction']['value'],2);
    $result['Construction']['ratio']=round($result['Construction']['value'] /$result['total']*100,2);
    $result['Trade and repair service']['value'] =round($result['Trade and repair service']['value'],2);
    $result['Trade and repair service']['ratio']=round($result['Trade and repair service']['value'] /$result['total']*100,2);
    $result['Tourism']['value'] =round($result['Tourism']['value'],2);
    $result['Tourism']['ratio']=round($result['Tourism']['value'] /$result['total']*100,2);
    $result['Transport service']['value'] =round($result['Transport service']['value'],2);
    $result['Transport service']['ratio']=round($result['Transport service']['value'] /$result['total']*100,2);
    $result['ICT service']['value'] =round($result['ICT service']['value'],2);
    $result['ICT service']['ratio']=round($result['ICT service']['value'] /$result['total']*100,2);
    $result['Financial service']['value'] =round($result['Financial service']['value'],2);
    $result['Financial service']['ratio']=round($result['Financial service']['value'] /$result['total']*100,2);
    $result['Property service']['value'] =round($result['Property service']['value'],2);
    $result['Property service']['ratio']=round($result['Property service']['value'] /$result['total']*100,2);
    $result['Public and welfare service']['value'] =round($result['Public and welfare service']['value'],2);
    $result['Public and welfare service']['ratio']=round($result['Public and welfare service']['value'] /$result['total']*100,2);
    $result['Private household service']['value'] =round($result['Private household service']['value'],2);
    $result['Private household service']['ratio']=round($result['Private household service']['value'] /$result['total']*100,2);
   
//     echo "\n**********10A********\n";
//   echo json_encode($result);
  $set10a = json_encode($result);
$db->update("country_brief",["set10a"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);


$db->update("country_brief",["set9a"=>$set9a,"set9"=>$set9,"set10"=>$set10,"set10a"=>$set10a],["AND"=>["economy"=>$exp_country,"year"=>$year]]);

?>