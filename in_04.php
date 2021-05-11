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
                             $sql2  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'DVA_INTrex1' or variable = 'DVA_INTrex2' or variable = 'DVA_INTrex3' )  and imp_country = '". $impData . "' and exp_sector = '" . $sector_data[$sectorData] ."'" ;
                        } else {
                            $sql2  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'DVA_INTrex1' or variable = 'DVA_INTrex2' or variable = 'DVA_INTrex3' )   and imp_country = '". $impData  ."'";
                        }
                        $valueA = $db->query($sql2)->fetchAll();
                        $value1A = $valueA[0][0];

                         if($sectorData != '0'){
                             $sql2  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'MVA_FIN' or variable = 'MVA_INT' or variable = 'OVA_FIN'  or variable = 'OVA_INT')  and imp_country = '". $impData . "' and exp_sector = '" . $sector_data[$sectorData] ."'" ;
                        } else {
                            $sql2  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'MVA_FIN' or variable = 'MVA_INT' or variable = 'OVA_FIN'  or variable = 'OVA_INT')   and imp_country = '". $impData  ."'";
                        } 
                        $valueA = $db->query($sql2)->fetchAll();
                        $value1B = $valueA[0][0];

                        if($sectorData != '0'){
                             $sql2  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'DDC_FIN' or variable = 'DDC_INT' or variable = 'MDC'  or variable = 'ODC')  and imp_country = '". $impData . "' and exp_sector = '" . $sector_data[$sectorData] ."'" ;
                        } else {
                            $sql2  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'DDC_FIN' or variable = 'DDC_INT' or variable = 'MDC'  or variable = 'ODC')   and imp_country = '". $impData  ."'";
                        } 
                        $valueA = $db->query($sql2)->fetchAll();
                        $value1C = $valueA[0][0];

                         if($sectorData != '0'){
                             $sql2  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'total_export')  and imp_country = '". $impData . "' and exp_sector = '" . $sector_data[$sectorData] ."'" ;
                        } else {
                            $sql2  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'total_export')   and imp_country = '". $impData  ."'";
                        } 
                        $valueA = $db->query($sql2)->fetchAll();
                        $value_gross = $valueA[0][0];
               
                   
                        
       
                 

                             $temp['exp_country'] = $expData;
                            
                            if($sectorData == '0'){
                                $temp['exp_sector'] = 'All';
                            } else {
                                $temp['exp_sector'] = $sector_data[$sectorData];
                            }   
                            $temp['imp_country'] = $impData;
                            $temp['value'] = round(($value1A)/ $value_gross*100,2);
                            $temp['year'] = $yearData;
                            $temp['indicator_sub_name'] = "Domestic production used in the importer's exports (forward linkages)";
                            $temp['indicator_main_name'] = "GVC praticipation as % of gross exports";
                            $temp['unit'] = '% of gross exports to partner';
                            array_push($result,$temp);
                            $temp['value'] = round(($value1B)/ $value_gross*100,2);
                            $temp['indicator_sub_name'] = "Foreign production consumed by the importer (backward linkages)";
                            $temp['indicator_main_name'] = "GVC praticipation as % of gross exports";
                            $temp['unit'] = '% of gross exports to partner';
                            array_push($result,$temp);
                            $temp['value'] = round(($value1C)/ $value_gross*100,2);
                            $temp['indicator_sub_name'] = "Double counted";
                            $temp['indicator_main_name'] = "GVC praticipation as % of gross exports";
                            $temp['unit'] = '% of gross exports to partner';
                            array_push($result,$temp);
                             $temp['value'] = round(($value1A),2);
                            $temp['indicator_sub_name'] = "Domestic production used in the importer's exports (forward linkages)";
                             $temp['indicator_main_name'] = "GVC praticipation in $US";
                            $temp['unit'] = '$US millions';
                            array_push($result,$temp);
                            $temp['value'] = round(($value1B),2);
                            $temp['indicator_sub_name'] = "Foreign production consumed by the importer (backward linkages)";
                            $temp['indicator_main_name'] = "GVC praticipation in $US";
                            $temp['unit'] = '$US millions';
                            array_push($result,$temp);
                            $temp['value'] = round(($value1C),2);
                            $temp['indicator_sub_name'] = "Double counted";
                            $temp['indicator_main_name'] = "GVC praticipation in $US";
                            $temp['unit'] = '$US millions';
                            array_push($result,$temp);
                     

                        
            }   
        }
    }
}
  
echo json_encode($result);
?>