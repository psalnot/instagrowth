<?php

	// If you're using Composer, use Composer's autoload:

	include_once("../class/cleaninput.class.php");
	include_once("../class/mysql.class.php");
	include_once("../class/user.class.php");
	include_once("../class/mailovh.class.php");
	include_once("../class/mailgun.class.php");
	require_once("../utils/defines.php");
	//require_once("../utils/minvoice.php");
	

	//$mlocation = "location: /" . $BACKOFFICE_AUTHENTIFICATION";
	$mlocation = "location: /" . $DEV_BACKOFFICE_AUTHENTIFICATION_EN . "?mreset=1";

	$cleaninput = new Cleaninput();
	if (isset($_POST["inputEmail"]))
	{
		$toemail = $cleaninput->cleanemail($_POST["inputEmail"]);
	}
	else
	{
		header($mlocation);die();
	}
	


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




	$msql = new MMsql();
	//$memail = new MMmail();
	$memail = new MMmailgun();
        $cstring = $msql->dbconnect($DBUSER,$DBPASS,$DBNAME);
	$muser = new MUser();
	$user_id = $msql->getuserid($cstring,$toemail);

	if (!$user_id)
	{
		header($mlocation);die();
	}
	$urlsign = md5($user_id . time() );
	$muser->createurlmail($cstring,$user_id,"RESET",$urlsign,$cremoteip,$cremotehost,$cremoteuseragent);
	$parameter = $URL_SITE . "/" . $BACKOFFICE_AUTHENTIFICATION_RESET . "?" . $PARAMETER_EMAIL . "=" . $urlsign;
	mysqli_close($cstring);
	$result = $memail->msendresetpassword($toemail,"Reset de votre password",$parameter);
	header($mlocation);
	exit();


?>