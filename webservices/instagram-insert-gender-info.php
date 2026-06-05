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

#$cstring = $msql->dbconnect($DBUSER,$DBPASS);
$cid = $v->cid;
#$minsta->CreateUserGender($cstring, "44", "40","60", "inscription");

foreach ($v as $key=>$mvalue)
{
    //$minsta->CreateUserGender($cstring, $mvalue, "Finterne",$mvalue, "inscription");
    if ($key == "cid")
        continue;
    else
    {
        $minsta->CreateUserGender($cstring, $cid, $key,$mvalue, "inscription");
    }
}

#$minsta->CreateeUserGender($cstring,$v->cid,$mclean->genclean($v->biography),$v->accountName,$v->followers_count,$v->media_count,$v->follows_count,$v->website,$v->profile_picture_url);

?>