<?php
include_once("../class/instagram.class.php");
include_once("../class/cleaninput.class.php");
include_once("../class/mysql.class.php");
require_once("../utils/defines.php");

header("Content-Type: application/json");
// build a PHP variable from JSON sent using POST method
$v = json_decode(stripslashes(file_get_contents("php://input")));

// Insert data 
$cstring = mysqli_connect('localhost', $DBUSER, $DBPASS,"liplus");
$minsta = new MInstagram();
$mclean = new Cleaninput();

$cid = $v->cid;
$minsta->CreateUserTown($cstring, 42, "42",42, "inscription");
foreach ($v as $key=>$mvalue)
{
    
    if ($key == "cid")
        continue;
    else
    {
        $minsta->CreateUserTown($cstring, $cid, $key,$mvalue, "inscription");
    }
}


?>