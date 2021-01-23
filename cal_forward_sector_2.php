<?php 
///imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//source_country = source countrh ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$runNumber =0;

//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
$region = $region_data[0];

//get country in same region
$country_data = $db->select("country_list","iso",[
region =>$region
]);

for($j=0;$j<count($country_data);$j++){
    $exp_country = $country_data[$j];
$export_name = $db->select("country_list","name",["iso"=>$exp_country]);

if($exp_country != $imp_country){

    $tableName = $exp_country . "_" . $year;
    $value2 = $db->sum($tableName,"value",[
        imp_country => $imp_country,
        variable => ['total_export']
    ]);

    $sector = $db->select("sector_data","*",[]);

    $sql  = "select sum(value) as sum,exp_sector from ". $tableName ." where  imp_country = '". $imp_country  ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' )  group by exp_sector" ;

    $value = $db->query($sql)->fetchAll();


    for($k=0;$k<count($value);$k++){
        $result[$runNumber]['exp_country'] = $export_name[0];
        $result[$runNumber]['sector'] = $value[$k]['exp_sector'];
        $sector_data = $db->select("sector_data","grouping",["name"=>$value[$k]['exp_sector']]);
        $result[$runNumber]['grouping'] = $sector_data[0];
        $result[$runNumber]['value'] = round($value[$k]['sum']/$value2*100,2);
        $result[$runNumber]['valueM'] = round($value[$k]['sum'],6);
         $runNumber++;
    }
}
    

}


 echo json_encode($result);
?>
