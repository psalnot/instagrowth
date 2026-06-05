<?php



#require 'vendor/autoload.php';
include_once("../class/mailgun.class.php");

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');
include_once("../class/cleaninput.class.php");
include_once("../class/user.class.php");
include_once("../class/mysql.class.php");
require_once("../utils/defines.php");



$mlocation = "location: /" . $BACKOFFICE_LOGIN;

$msecondstep= "location: /".$SIGNUP_SECOND;
$mbfront = "location: /" . $BACKOFFICE_FRONT;





$cleaninput = new Cleaninput();





if (isset($_POST["inputPassword"]))
{

        $cpassword = $cleaninput->genclean($_POST["inputPassword"]);

}
else
{
        header($mlocation);die();
}

//echo "Le téléphone " . $cphone . " Le mot de passe " . $cpassword . " Votre mail " . $cmail;exit();





$cremoteuseragent = $cleaninput->genclean($_SERVER["HTTP_USER_AGENT"]);
$cremoteip = $cleaninput->genclean($_SERVER["REMOTE_ADDR"]);
if (isset($_SERVER["REMOTE_REFERER"]))
{
	$creferer = $cleaninput->genclean($_SERVER["REMOTE_REFERER"]);
}
else
{
	$creferer = "none";
}
if (isset($_SERVER["REMOTE_HOST"]))
{
	$cremotehost = $cleaninput->genclean($_SERVER["REMOTE_HOST"]);
}
else
{
	$cremotehost = "none";
}

//RF42
//$cid=5;

session_start();
if ( ! isset($_SESSION['bgp']))
{
   echo "ici none";exit();	
   header($mlocation);
   die();
}

$cemail = $_SESSION['bgp'];
$cid = $_SESSION['bgpid'];
$curl = $_SESSION['bgpurl'];








$msql = new MMsql();
$muser = new MUser();
$mailgun = new MMmailgun();

$cstring = $msql->dbconnect($DBUSER,$DBPASS,$DBNAME);


if (mysqli_connect_errno()) {
       printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
}

#Ajout utilisateur 2017/01/20


#echo " Nom de famille "  . $cname . " Société " . $ccompany . " Instagram " . $cinstagram;
#exit();




//echo "Mot de Passe " . $cpassword;exit();
$result = $muser->updateinstagrowthPassword($cstring,$cid, md5($cpassword),$cremoteip,$cremotehost, $cremoteuseragent);
$muser->updateurlmail($cstring,$curl,$cremoteip,$cremotehost,$cremoteuseragent);
//$result_details = $muser->createmarquedata($cstring,$user_id,$cdesc,$csecteur,$ccampagne,$cbudget);
//echo " User_id " . $user_id;exit();
//$user_id = $msql->getuserid($cstring,$cusername);






#
#require 'vendor/autoload.php';
#use Mailgun\Mailgun;

# Instantiate the client.
#$mgClient = new Mailgun('key-4d3f7268e467c52b9af7ca6c852479d8');
#$domain = "mailgun.lesinfluenceurs.info";

# Make the call to the client.
#$result = $mgClient->sendMessage($domain, array(
#    'from'    => 'Administration <information@mailgun.lesinfluenceurs.info>',
#    'to'      => '<' . $cusername . '>',
#    'subject' => 'Creation de votre compte sur les influenceurs',
#    'text'    => 'Ceci est votre mot de passe '
#));












mysqli_close($cstring);



 //session_start();
 session_unset();
 session_destroy();
 header($mlocation);









?>
