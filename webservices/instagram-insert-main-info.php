<?php
include_once("../class/instagram.class.php");
include_once("../class/cleaninput.class.php");
include_once("../class/mysql.class.php");
require_once("../utils/defines.php");

header("Content-Type: application/json");
// build a PHP variable from JSON sent using POST method
$v = json_decode(stripslashes(file_get_contents("php://input")));
$fhandle = fopen("./tmp/plouf","w+");
fwrite($fhandle, "info "  . $v->profile_picture_url);
// Insert data 
$cstring = mysqli_connect('localhost', $DBUSER, $DBPASS,"liplus");
$minsta = new MInstagram();
$mclean = new Cleaninput();
#$cstring = $msql->dbconnect($DBUSER,$DBPASS);
$minsta->updateUserInstagram($cstring,$v->cid,$mclean->genclean($v->biography),$v->accountName,$v->followers_count,$v->media_count,$v->follows_count,$v->website,$v->profile_picture_url);

?>