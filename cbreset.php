<?php
require_once('connection.php');
$_POST = json_decode(file_get_contents("php://input"),true);
$exp_country = $_POST['country'];
$year = $_POST['year'];

$data = $db->delete("country_brief",["AND"=>[
    "year"=>$year
    ]
]);

$data2 = $db->select("country_list2",["iso"]);
for($i=0; $i<count($data2);$i++){
    $data3 = $db->insert("country_brief",[
       "economy"=> $data2[$i]['iso'],
       "year" => $year,
       "set1"=> '',
       "set2"=>'',
       "set3"=>'',
       "set4"=>'',
       "set5"=>'',
       "set6"=>'',
       "set7"=>'',
       "set8"=>'',
       "set9"=>'',
       "set9a"=>'',
       "set10"=>'',
       "set10a"=>'',
       "set11"=>'',
       "set11a"=>'',
       "set12"=>'',
       "set12a"=>'',
    ]);
}
echo "finish";
?>