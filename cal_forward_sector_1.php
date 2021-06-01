<?php 
///imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017

require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$table_name = strtolower($exp_country) . "_" . $year;


$sql  = "select sum(value) as sum,exp_sector  from " . $table_name ." 
where imp_country = '". $imp_country  ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' or variable='RDV_FIN1'  or variable='RDV_FIN2'  or variable='RDV_INT' )  group by exp_sector" ;
$value = $db->query($sql)->fetchAll();

$result[0]['id'] = "A";
$result[0]['name'] = "Agriculture";
$result[0]['color'] = "#2F978B";

$result[1]['id'] = "B";
$result[1]['name'] = "Mining";
$result[1]['color'] = "#9A25B1";

$result[2]['id'] = "C";
$result[2]['name'] = "Construction";
$result[2]['color'] = "#8D243B";

$result[3]['id'] = "D";
$result[3]['name'] = "Utilities";
$result[3]['color'] = "#FA9908";

$result[4]['id'] = "E";
$result[4]['name'] = "Low tech";
$result[4]['color'] = "#F34336";

$result[5]['id'] = "F";
$result[5]['name'] = "High and medium tech";
$result[5]['color'] = "#C3165B";

$result[6]['id'] = "G";
$result[6]['name'] = "Trade and repair service";
$result[6]['color'] = "#5E6DC1";

$result[7]['id'] = "H";
$result[7]['name'] = "Tourism";
$result[7]['color'] = "#3F50B8";

$result[8]['id'] = "I";
$result[8]['name'] = "Transport service";
$result[8]['color'] = "#3949AB";

$result[9]['id'] = "J";
$result[9]['name'] = "ICT service";
$result[9]['color'] = "#1565C0";

$result[10]['id'] = "K";
$result[10]['name'] = "Property service";
$result[10]['color'] = "#19227D";

$result[11]['id'] = "L";
$result[11]['name'] = "Financial service";
$result[11]['color'] = "#43A7F5";

$result[12]['id'] = "M";
$result[12]['name'] = "Public and welfare service";
$result[12]['color'] = "#2088E7";

$result[13]['id'] = "N";
$result[13]['name'] = "Private household service";
$result[13]['color'] = "#1564C0";


for($i=0;$i< count($value);$i++){

 $result[$i+14]['name'] = $value[$i]['exp_sector'];
    $result[$i+14]['value'] = round($value[$i]['sum'],2);
    $area = $db->select("sector_data",
    "grouping",["name"=>$value[$i]['exp_sector']]);
    if($area[0] == 'Agriculture'){
        $result[$i+14]['parent'] = "A";
    } else if($area[0] == 'Mining'){
        $result[$i+14]['parent'] = "B";
    } else if($area[0] == 'Construction'){
        $result[$i+14]['parent'] = "C";
    } else if($area[0] == 'Utilities'){
        $result[$i+14]['parent'] = "D";
    } else if($area[0] == 'Low tech'){
        $result[$i+14]['parent'] = "E";
    } else if($area[0] == 'High and medium tech'){
        $result[$i+14]['parent'] = "F";
    } else if($area[0] == 'Trade and repair service'){
        $result[$i+14]['parent'] = "G";
    } else if($area[0] == 'Tourism'){
        $result[$i+14]['parent'] = "H";
    } else if($area[0] == 'Transport service'){
        $result[$i+14]['parent'] = "I";
    } else if($area[0] == 'ICT service'){
        $result[$i+14]['parent'] = "J";
    } else if($area[0] == 'Property service'){
        $result[$i+14]['parent'] = "K";
    } else if($area[0] == 'Financial service'){
        $result[$i+14]['parent'] = "L";
    }else if($area[0] == 'Public and welfare service'){
        $result[$i+14]['parent'] = "M";
    } else if($area[0] == 'Private household service'){
        $result[$i+14]['parent'] = "N";
    } 
}
echo json_encode($result);
?>
