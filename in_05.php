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
// $imp_country = ['USA'];
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
                             $sql2  = "select sum(value) as sum,exp_country, imp_country,exp_sector, source_country, year  from " . $tableName . " where (variable = 'fva_yl' )  and imp_country = '". $impData . "' and exp_sector = '" . $sector_data[$sectorData] ."' group by source_country" ;
                        } else {
                            $sql2  = "select sum(value) as sum,exp_country, imp_country, source_country, year  from " . $tableName . " where (variable = 'fva_yl' )   and imp_country = '". $impData  ."' group by source_country";
                        }
                        $valueA = $db->query($sql2)->fetchAll();
                    
                        for($i=0;$i< count($valueA);$i++){
                                $temp['source_country'] = $valueA[$i]['source_country'];
                                $temp['exp_country'] = $expData;
                                if($sectorData == '0'){
                                $temp['exp_sector'] = 'All';
                            } else {
                                $temp['exp_sector'] = $sector_data[$sectorData];
                            }   
                                $temp['imp_country'] = $impData;
                                $temp['value'] = round($valueA[$i][0],2);
                                $temp['year'] = $yearData;
                                $temp['indicator_name'] = "Backward linkages (by exporting sector)";
                                $temp['unit'] = '$ US millions';
                                array_push($result,$temp);
                        }
         
                       

                         
                     

                        
            }   
        }
    }
}
  
echo json_encode($result);
?>