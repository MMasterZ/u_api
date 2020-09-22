<?php 
//12. Forward linkages, all exporting sectors (Forward_link_sector)
//imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');
$data = json_decode(file_get_contents("php://input"), true);
$exp_country= $data['exporting'];
$imp_country = $data['importing'];
$year = $data['year'];

// Import Country
$impText = ' and imp_country in (';
foreach($imp_country as $impData){
    $impText = $impText . "'".  $impData . "',";
}
$impText = substr($impText,0,-1);
$impText = $impText . ")";
$final = [];

foreach($exp_country as $expData){
    foreach($year as $yearData){
        $tableName =  $expData . "_" . $yearData;
        $sql  = "select sum(value) as sum,exp_country,exp_sector, imp_country,year  from ". $tableName . "  where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' ) " . $impText . " group by exp_sector, imp_country" ;
    $value = $db->query($sql)->fetchAll();
    $final = array_merge($final,$value);
    }
}






for($i=0;$i< count($final);$i++){
    $result[$i]['exp_country'] = $final[$i]['exp_country'];
    $result[$i]['exp_sector'] = $final[$i]['exp_sector'];
    $result[$i]['imp_country'] = $final[$i]['imp_country'];
    $result[$i]['value'] = round($final[$i]['sum'],2);
    $result[$i]['year'] = $final[$i]['year'];
    $result[$i]['indicator'] = 'Forward_link_sector';
}

 echo json_encode($result);
?>
