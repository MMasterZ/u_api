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

// include 'cb4.php';


//************5**************/
//Gross trade balance

// include 'cb5.php';


//***********6 ***************/
//Top 10 partner economies that Thailand relies on for backward and forward linkages

// $year = 2007;
// $tableName = $exp_country . "_" . $year;
// include 'cb6.php';
// $year = 2011;
// $tableName = $exp_country . "_" . $year;
// include 'cb6.php';
$year = 2017;
$tableName = $exp_country . "_" . $year;
include 'cb6.php';





//***********7 ***************/
//Top 10 exporting sectors that Thailand relies on for backward and forward linkages

// include 'cb7.php';




//*************8  *******************/
// GVC related trade

// include 'cb8.php';



//***********9************ */
// backward linkages by source region

// include 'cb9.php';

//***********9A************* */
// backward linkages by source region (Region)
// $year = 2007;
// $tableName = $exp_country . "_" . $year;
// include 'cb9a.php';
// $year = 2011;
// $tableName = $exp_country . "_" . $year;
// include 'cb9a.php';
// $year = 2017;
// $tableName = $exp_country . "_" . $year;
// include 'cb9a.php';


//**********10 ***********/
// backward linkages by exporting sector
// $year = 2007;
// $tableName = $exp_country . "_" . $year;
// include 'cb10.php';
// $year = 2011;
// $tableName = $exp_country . "_" . $year;
// include 'cb10.php';
// $year = 2017;
// $tableName = $exp_country . "_" . $year;
// include 'cb10.php';

//**********10A ***********/
// backward linkages by exporting sector (exporting sector)

// $year = 2007;
// $tableName = $exp_country . "_" . $year;
// include 'cb10a.php';
// $year = 2011;
// $tableName = $exp_country . "_" . $year;
// include 'cb10a.php';
// $year = 2017;
// $tableName = $exp_country . "_" . $year;
// include 'cb10a.php';


//*********11 *************/
// Forward linkages by importing region

// $year = 2007;
// $tableName = $exp_country . "_" . $year;
// include 'cb11.php';
// $year = 2011;
// $tableName = $exp_country . "_" . $year;
// include 'cb11.php';
// $year = 2017;
// $tableName = $exp_country . "_" . $year;
// include 'cb11.php';


//*********11a *************/
// Forward linkages by importing region (Region)

// $year = 2007;
// $tableName = $exp_country . "_" . $year;
// include 'cb11a.php';
// $year = 2011;
// $tableName = $exp_country . "_" . $year;
// include 'cb11a.php';
// $year = 2017;
// $tableName = $exp_country . "_" . $year;
// include 'cb11a.php'; 

//*********12 *************/
// Forward linkages by exporting sector

// $year = 2007;
// $tableName = $exp_country . "_" . $year;
// include 'cb12.php';
// $year = 2011;
// $tableName = $exp_country . "_" . $year;
// include 'cb12.php';
// $year = 2017;
// $tableName = $exp_country . "_" . $year;
// include 'cb12.php'; 

//*********12A *************/
// Forward linkages by exporting sector(exporting sector)

// $year = 2007;
// $tableName = $exp_country . "_" . $year;
// include 'cb12a.php';
// $year = 2011;
// $tableName = $exp_country . "_" . $year;
// include 'cb12a.php';
// $year = 2017;
// $tableName = $exp_country . "_" . $year;
// include 'cb12a.php'; 




?>