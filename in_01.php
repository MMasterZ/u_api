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

// $exp_country = ['AUT','AUS','BEL'];
// $imp_country = ['CHN','MYS'];
// $year = ['2017'];
// $sector = ['0','1'];


$impText = ' and imp_country in (';
foreach($imp_country as $impData){
    $impText = $impText . "'".  $impData . "',";
}
$impText = substr($impText,0,-1);
$impText = $impText . ")";


//Sector
$sectorText = '';
$sectorZero = 0;
if($sector[0] == 0){
    $sectorZero = 1;
   array_shift($sector);
}
// echo $sector;
$sectorText = ' and exp_sector in(';
foreach($sector as $sectorData){
$sectorText = $sectorText . "'".  $sector_data[$sectorData] . "',";


}
$sectorText = substr($sectorText,0,-1);
$sectorText = $sectorText . ")";
// echo $sectorText;
$result = [];
$final01 = [];
$final02 = [];
$final03 = [];
$final04 = [];
$final05 = [];
$final06 = [];
$final07 = [];
$final08 = [];
$final09 = [];
$final10 = [];
$count = 0;
foreach($exp_country as $expData){
    foreach($year as $yearData){
        $tableName =  strtolower($expData) . "_" . $yearData;
      if(count($sector) > 0){ 
        //สำหรับ Domestic production consumed by the importer
         $sql2  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'DVA_FIN' or variable='DVA_INT' ) ". $impText . $sectorText . " group by imp_country, exp_sector" ;
        $value2 = $db->query($sql2)->fetchAll();
        $final01 = array_merge($final01,$value2);
        for($i=0;$i< count($final01);$i++){
            $temp['exp_country'] = $final01[$i]['exp_country'];
            $temp['exp_sector'] = $final01[$i]['exp_sector'];
            $temp['imp_country'] = $final01[$i]['imp_country'];
            $temp['value'] = round($final01[$i][0],2);
            $temp['year'] = $final01[$i]['year'];
            $temp['indicator_name'] = "Domestic production consumed by the importer";
            $temp['unit'] = '$ US millions';
           array_push($result,$temp);
         
        }

        //สำหรับ Domestic production used in the importer's exports
        $sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3'  ) ". $impText . $sectorText . " group by imp_country, exp_sector" ;
        $value = $db->query($sql)->fetchAll();
        $final02 = array_merge($final02,$value);
        for($i=0;$i< count($final02);$i++){
            $temp['exp_country'] = $final02[$i]['exp_country'];
            $temp['exp_sector'] = $final02[$i]['exp_sector'];
            $temp['imp_country'] = $final02[$i]['imp_country'];
            $temp['value'] = round($final02[$i][0],2);
            $temp['year'] = $yearData;
            $temp['indicator_name'] = "Domestic production used in the importer's exports";
            $temp['unit'] = '$ US millions';
           array_push($result,$temp);
        }

        //สำหรับ Domestic production that returns via the importer's exports 
        $sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'RDV_FIN1' or variable='RDV_FIN2' or variable='RDV_INT'  ) ". $impText . $sectorText . " group by imp_country, exp_sector" ;
        $value = $db->query($sql)->fetchAll();
        $final03 = array_merge($final03,$value);
        for($i=0;$i< count($final03);$i++){
            $temp['exp_country'] = $final03[$i]['exp_country'];
            $temp['exp_sector'] = $final03[$i]['exp_sector'];
            $temp['imp_country'] = $final03[$i]['imp_country'];
            $temp['value'] = round($final03[$i][0],2);
            $temp['year'] = $yearData;
            $temp['indicator_name'] = "Domestic production that returns via the importer's exports ";
            $temp['unit'] = '$ US millions';
           array_push($result,$temp);
        }

         //สำหรับ Double conunted
        $sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'DDC_FIN' or variable='DDC_INT' or variable='MDC'  or variable='ODC') ". $impText . $sectorText . " group by imp_country, exp_sector" ;
        $value = $db->query($sql)->fetchAll();
        $final04 = array_merge($final04,$value);
        for($i=0;$i< count($final04);$i++){
            $temp['exp_country'] = $final04[$i]['exp_country'];
            $temp['exp_sector'] = $final03[$i]['exp_sector'];
            $temp['imp_country'] = $final04[$i]['imp_country'];
            $temp['value'] = round($final04[$i][0],2);
            $temp['year'] = $yearData;
            $temp['indicator_name'] = "Double conunted";
            $temp['unit'] = '$ US millions';
            array_push($result,$temp);
        }
            
         //สำหรับ Foreign production consumed by importer
        $sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " where (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN'  or variable='OVA_INT') ". $impText . $sectorText . " group by imp_country, exp_sector" ;
        $value = $db->query($sql)->fetchAll();
        $final05 = array_merge($final05,$value);
        for($i=0;$i< count($final05);$i++){
            $temp['exp_country'] = $final05[$i]['exp_country'];
            $temp['exp_sector'] = $final05[$i]['exp_sector'];
            $temp['imp_country'] = $final05[$i]['imp_country'];
            $temp['value'] = round($final05[$i][0],2);
            $temp['year'] = $yearData;
            $temp['indicator_name'] = "Foreign production consumed by importer";
            $temp['unit'] = '$ US millions';
            array_push($result,$temp);
        }
      } 
    
      if($sectorZero == 1){
   
        //สำหรับ Domestic production consumed by the importer
        $sql  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'DVA_FIN'  or variable='DVA_INT'  ) ". $impText . " group by imp_country" ;
        $value = $db->query($sql)->fetchAll();
        $final06 = array_merge($final06,$value);
   
        for($i=0;$i< count($final06);$i++){
            $temp['exp_country'] = $final06[$i]['exp_country'];
            $temp['exp_sector'] = 'all';
            $temp['imp_country'] = $final06[$i]['imp_country'];
            $temp['value'] = round($final06[$i][0],2);
            $temp['year'] = $yearData;
            $temp['indicator_name'] = "Domestic production consumed by the importer";
            $temp['unit'] = '$ US millions';
            array_push($result,$temp);
        }
        
         //สำหรับ Domestic production used in the importer's exports 
        $sql  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'DVA_INTrex1'  or variable='DVA_INTrex2' or variable='DVA_INTrex3' ) ". $impText . " group by imp_country" ;
        $value = $db->query($sql)->fetchAll();
        $final07 = array_merge($final07,$value);
   
        for($i=0;$i< count($final07);$i++){
            $temp['exp_country'] = $final07[$i]['exp_country'];
            $temp['exp_sector'] = 'all';
            $temp['imp_country'] = $final07[$i]['imp_country'];
            $temp['value'] = round($final07[$i][0],2);
            $temp['year'] = $yearData;
            $temp['indicator_name'] = "Domestic production used in the importer's exports ";
            $temp['unit'] = '$ US millions';
             array_push($result,$temp);
        }

           //สำหรับ Domestic production that returns via the importer's exports 
        $sql  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'RDV_FIN1'  or variable='RDV_FIN2' or variable='RDV_INT'  ) ". $impText . " group by imp_country" ;
        $value = $db->query($sql)->fetchAll();
        $final08 = array_merge($final08,$value);
   
        for($i=0;$i< count($final08);$i++){
            $temp['exp_country'] = $final08[$i]['exp_country'];
            $temp['exp_sector'] = 'all';
            $temp['imp_country'] = $final08[$i]['imp_country'];
            $temp['value'] = round($final08[$i][0],2);
            $temp['year'] = $yearData;
            $temp['indicator_name'] = "Domestic production that returns via the importer's exports ";
            $temp['unit'] = '$ US millions';
            array_push($result,$temp);
        }
      

          //สำหรับ Double counted 
        $sql  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'DDC_FIN'  or variable='DDC_INT' or variable='MDC'  or variable='ODC' ) ". $impText . " group by imp_country" ;
        $value = $db->query($sql)->fetchAll();
        $final09 = array_merge($final09,$value);
   
        for($i=0;$i< count($final09);$i++){
            $temp['exp_country'] = $final09[$i]['exp_country'];
            $temp['exp_sector'] = 'all';
            $temp['imp_country'] = $final09[$i]['imp_country'];
            $temp['value'] = round($final09[$i][0],2);
            $temp['year'] = $yearData;
            $temp['indicator_name'] = " Double counted ";
            $temp['unit'] = '$ US millions';
            array_push($result,$temp);
        }
         


          //สำหรับ Foreign production consumed by importer
        $sql  = "select sum(value) as sum,exp_country, imp_country, year  from " . $tableName . " where (variable = 'MVA_FIN'  or variable='MVA_INT' or variable='OVA_INT' or variable= 'OVA_FIN'  ) ". $impText . " group by imp_country" ;
        $value = $db->query($sql)->fetchAll();
        $final10 = array_merge($final10,$value);
   
        for($i=0;$i< count($final10);$i++){
            $temp['exp_country'] = $final10[$i]['exp_country'];
            $temp['exp_sector'] = 'all';
            $temp['imp_country'] = $final10[$i]['imp_country'];
            $temp['value'] = round($final10[$i][0],2);
            $temp['year'] = $yearData;
            $temp['indicator_name'] = "Foreign production consumed by importer";
            $temp['unit'] = '$ US millions';
            array_push($result,$temp);
         
        }
      }
        
    



        
    }
}

// print_r($final);
// print_r($final2);



// echo $count;
echo json_encode($result);
?>