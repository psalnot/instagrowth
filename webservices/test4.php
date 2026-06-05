<?php
include_once("../class/instagram.class.php");
include_once("../class/cleaninput.class.php");
include_once("../class/mysql.class.php");
require_once("../utils/defines.php");

// Insert data 
$cstring = mysqli_connect('localhost', $DBUSER, $DBPASS,"liplus");
$json = '{"Peter":65,"Harry":80,"John":78,"Clark":90}';
$arr = json_decode($json, true);
foreach($arr as $key=>$value){
    echo $key . "=>" . $value . "<br>";
}
$minsta = new MInstagram();
$minsta->CreateUserImpressions($cstring,"40",20,"2021-09-18T07:00:00+0000","day","inscription");
#$minsta->CreateeUserGender($cstring,$v->cid,$mclean->genclean($v->biography),$v->accountName,$v->followers_count,$v->media_count,$v->follows_count,$v->website,$v->profile_picture_url);

?>