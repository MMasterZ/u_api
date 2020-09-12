<?php 
//1. Gross exports used in importer's consumption (Imp_cons)
//imp_country = import country ส่งมาเป็นรหัสประเทศ 3 ตัว
//exp_country = export country ส่งมาเป็นรหัสประเทศ 3 ตัว
//year = ปี ส่งเป็น ค.ศ. 2017
//sector = sector ส่งมาเป็น id ตัวเลข sector 
require_once('connection.php');
require_once('sector_data.php');


$exp_country = $_GET['exp_country'];
$year = $_GET['year'];

$tableName = $exp_country . "_" . $year;

$sql  = "select sum(value) as sum,exp_country, imp_country,exp_sector, year  from " . $tableName . " 
where variable = 'DVA_FIN' or variable='DVA_INT'   group by imp_country, exp_sector " ;
$value = $db->query($sql)->fetchAll();

// imp_cons

$is_table_exit = $db -> count("imp_cons","*");


if($is_table_exit){
    // TABLE IMP_CONS is exists
    $db -> delete("imp_cons",
    [
        "AND" => [
		"exp_country" => $exp_country,
	    ]
    ]);
}else{
    // TABLE IMP_CONS is not exists
    $db -> create("imp_cons",
    [
        "id" => [
            "INT",
            "NOT NULL",
            "AUTO_INCREMENT",
            "PRIMARY KEY"
        ],
        "source_country" => [
            "TEXT"
        ],
        "exp_country" => [
            "TEXT"
        ],
        "exp_sector" => [
            "TEXT"
        ],
        "imp_country" => [
            "TEXT"
        ],
        "value" => [
            "FLOAT"
        ],
        "variable_set" => [
            "TEXT"
        ],
        "year" => [
            "INT"
        ],
        "indicator" => [
            "TEXT"
        ]
    ]);
}

$sql_imp = "insert into imp_cons (source_country,exp_country,exp_sector,imp_country,value,variable_set,year,indicator) values ";

for($i=0;$i < count($value);$i++){
    $sql_imp .= "(";
    $sql_imp .= "'-',"; //source_country
    $sql_imp .= "'".$value[$i]['exp_country']."'". ",";
    $sql_imp .= "'".$value[$i]['exp_sector']."'" . ",";
    $sql_imp .= "'".$value[$i]['imp_country']."'" . ",";
    $sql_imp .= $value[$i]['sum'] . ",";
    $sql_imp .= "'-',";
    $sql_imp .= $year . ",";
    $sql_imp .= "'imp_cons'";
    if($i < count($value) - 1){
        $sql_imp .= "),";
    }else{
        $sql_imp .= ")";
    }
    
}

$db -> query($sql_imp) -> fetchAll();
echo "finish";






// print_r($value);

// $result['source_country'] = "-";
// $result['exp_country'] = $exp_country;
// $result['exp_sector'] = $sector_data[$sector];
// $result['imp_country'] = $imp_country;
// $result['variable_set'] = "-";
// $result['value'] = round($value,2);
// $result['year'] = $year;
// $result['indicator'] = 'imp_cons';



?>
