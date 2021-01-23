<?php
require_once('connection.php');

$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$tableName = $exp_country . "_" . $year;


// **************1***************
// Structure of value-added in Thailand's exports to world

// include 'cb1.php';



//**********2 ************/
//Structure of value-added in SEA exports to world

// include 'cb2.php';


//**************3 *******************/
//Structure of value-add in global exports

// include 'cb3.php';



//************4**************/
//Value-added  trade balance

include 'cb4.php';


//************5**************/
//Gross trade balance

// include 'cb5.php';


//***********6 ***************/
//Top 10 partner economies that Thailand relies on for backward and forward linkages
// include 'cb6.php';


//***********7 ***************/
//Top 10 exporting sectors that Thailand relies on for backward and forward linkages
// include 'cb7.php';




?>