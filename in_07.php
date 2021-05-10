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
$sector = $data['sector'];
$year = $data['year'];


// $exp_country = ['THA'];
// $sector = ['0'];
// $year = ['2017'];
// $source_country= ['CHN'];




// echo $sectorText;
$result = [];

foreach($exp_country as $expData){
    foreach($year as $yearData){
        foreach($sector as $sectorData){
          
                $tableName =  $expData . "_" . $yearData;                        
                      if($sectorData != '0'){
                             $sql2  = "select sum(value) as sum,exp_country, imp_country  from " . $tableName . " where (variable = 'DVA_INTrex1' or variable = 'DVA_INTrex2' or variable = 'DVA_INTrex3' )  and exp_sector = '" . $sector_data[$sectorData]. "'  group by imp_country" ;
                           
                      } else {
                         $sql2  = "select sum(value) as sum,exp_country, imp_country  from " . $tableName . " where (variable = 'DVA_INTrex1' or variable = 'DVA_INTrex2' or variable = 'DVA_INTrex3' )    group by imp_country" ;
                      }
                        $valueA = $db->query($sql2)->fetchAll();
                 
                    
                        for($i=0;$i< count($valueA);$i++){
                                $temp['exp_country'] = $expData;
                                $temp['exp_sector'] = $sector_data[$sectorData];
                              
                                $temp['imp_country'] = $valueA[$i]['imp_country'];
                                $temp['value'] = round($valueA[$i]['sum'],2);
                                $temp['year'] = $yearData;
                                $temp['indicator_name'] = "Forward linkages (by exporting sector)";
                                $temp['unit'] = '$ US millions';
                                array_push($result,$temp);
                        }
         
                       

                         
                     

                        
            
        }
    }
}
  
echo json_encode($result);
?>