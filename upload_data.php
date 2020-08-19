<?php 
require_once('connection.php');

for($i = 0;$i < count($_POST);$i++){
    
    $source_country = $_POST[$i]['source_country'];
    $exp_country = $_POST[$i]['exp_country'];
    $exp_sector = $_POST[$i]['exp_sector'];
    $imp_country = $_POST[$i]['imp_country'];
    $variable = $_POST[$i]['variable'];
    $value = $_POST[$i]['value'];
    $year = $_POST[$i]['year'];

    $db -> insert('test',[
        "source_country" => $source_country,
        "exp_country" => $exp_country,
        "exp_sector" => $exp_sector,
        "imp_country" => $imp_country,
        "variable" => $variable,
        "value" => $value,
        "year" => $year
    ]);

}



echo "Success";


?>