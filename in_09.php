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
// $sector = ['0'];
// $year = ['2017'];
// $source_country= ['CHN'];




// echo $sectorText;
$result = [];

foreach($exp_country as $expData){
    foreach($year as $yearData){
        foreach($imp_country as $impData){
            foreach($sector as $sectorData){
                $tableName =  $expData . "_" . $yearData;                        
                  
                        if($sectorData != '0'){
                            $sql2  = "select sum(value) as sum,exp_country, imp_country  from " . $tableName . " where (variable = 'total_export' )  and exp_sector = '" . $sector_data[$sectorData]. "' and imp_country ='" . $impData . "'" ;
                           
                         } else {
                             $sql2  = "select sum(value) as sum,exp_country, imp_country  from " . $tableName . " where (variable = 'total_export')  and imp_country ='" . $impData . "'" ;
                        }
                           
                     
                        $valueA = $db->query($sql2)->fetchAll();
                 
                    
                        for($i=0;$i< count($valueA);$i++){
                                $temp['exp_country'] = $expData;
                                $temp['exp_sector'] = $sector_data[$sectorData];
                              
                                $temp['imp_country'] = $impData;
                                $temp['value'] = round($valueA[$i]['sum'],2);
                                $temp['year'] = $yearData;
                                $temp['indicator_name'] = "Gross exports";
                                $temp['unit'] = '$ US millions';
                                array_push($result,$temp);
                        }
         
                       

                         
                     
                    }
                        
            
        }
    }
}
  
echo json_encode($result);
?>