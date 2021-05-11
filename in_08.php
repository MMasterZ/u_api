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


// $exp_country = ['THA'];
// $imp_country = ['CHN'];
// // $sector = ['0'];
// $year = ['2017'];
// $source_country= ['CHN'];




// echo $sectorText;
$result = [];

foreach($exp_country as $expData){
    foreach($year as $yearData){
        foreach($imp_country as $impData){
          
                $tableName =  strtolower($expData) . "_" . $yearData;                        
                  
                             $sql2  = "select sum(value) as sum,exp_country,  exp_sector  from " . $tableName . " where (variable = 'DVA_INTrex1' or variable = 'DVA_INTrex2' or variable = 'DVA_INTrex3' )  and imp_country = '" . $impData . "'  group by exp_sector" ;
                           
                     
                        $valueA = $db->query($sql2)->fetchAll();
                 
                    
                        for($i=0;$i< count($valueA);$i++){
                                $temp['exp_country'] = $expData;
                                $temp['exp_sector'] = $valueA[$i]['exp_sector'];
                              
                                $temp['imp_country'] = $impData;
                                $temp['value'] = round($valueA[$i]['sum'],2);
                                $temp['year'] = $yearData;
                                $temp['indicator_name'] = "Forward linkages (by importing economy)";
                                $temp['unit'] = '$ US millions';
                                array_push($result,$temp);
                        }
         
                       

                         
                     

                        
            
        }
    }
}
  
echo json_encode($result);
?>