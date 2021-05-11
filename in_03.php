<?php

//1. Gross exports used in importer's consumption (Imp_cons)
//imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017
//sector = sector ส่งมาเป็น id ตัวเลข sector 

require_once('connection.php');
require_once('sector_data.php');

$data = json_decode(file_get_contents("php://input"), true);
$exp_country= $data['exporting'];
$imp_country = $data['importing'];
$year = $data['year'];
$sector = $data['sector'];

// $exp_country = ['THA'];
// $imp_country = ['CHN'];
// $year = ['2017'];
// $sector= ['0'];




// echo $sectorText;
$result = [];

foreach($exp_country as $expData){
    foreach($year as $yearData){
        foreach($imp_country as $impData){
            foreach($sector as $sectorData){
                $tableName =  strtolower($expData) . "_" . $yearData;
                        
                        if($sectorData != '0'){
                             $sql2  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'total_export' )  and imp_country = '". $impData . "' and exp_sector = '" . $sector_data[$sectorData] ."'" ;
                        } else {
                            $sql2  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'total_export' )  and imp_country = '". $impData  ."'";
                        }
                       
                        $value2 = $db->query($sql2)->fetchAll();
                        $value = $value2[0][0];
                        $value_gross = $value2[0][0];
                   
                        
       
                 

                  $tableName2 =  strtolower($impData) . "_" . $yearData;       
                    if($sectorData != '0'){
                             $sql2  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName2 . " where (variable = 'total_export' )  and imp_country = '". $expData . "' and exp_sector = '" . $sector_data[$sectorData] ."'" ;
                        } else {
                            $sql2  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName2 . " where (variable = 'total_export' )  and imp_country = '". $expData  ."'";
                        }
                        $value3 = $db->query($sql2)->fetchAll();
                        $value1 = $value3[0][0];
               
                            $temp['exp_country'] = $expData;
                            
                            if($sectorData == '0'){
                                $temp['exp_sector'] = 'All';
                            } else {
                                $temp['exp_sector'] = $sector_data[$sectorData];
                            }   
                            $temp['imp_country'] = $impData;
                            $temp['value'] = round(($value - $value1)/ $value_gross*100,2);
                            $temp['year'] = $yearData;
                            $temp['indicator_name'] = 'Gross trade balance as % of gross exports';
                            $temp['unit'] = '% of gross exports to partner';
                        array_push($result,$temp);
                            $temp['value'] = round(($value - $value1),2);
                            $temp['indicator_name'] = 'Gross trade balance in $US';
                            $temp['unit'] = '$US millions';
                        array_push($result,$temp);

                        
            }   
        }
    }
}
  
echo json_encode($result);
?>