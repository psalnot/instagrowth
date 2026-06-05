<?php
/*
error_reporting(E_ALL);
ini_set("display_errors","On");
*/

session_cache_limiter('nocache');
include_once("../class/cleaninput.class.php");
include_once("../class/mbooker.class.php");
include_once("../class/mysql.class.php");
include_once("../../utils/defines-ubooker.php");


$dashboardpage = "location: /UBOOKER/" . $DASHBOARD_PAGE;
$mlocation = "location: /";
$mbooker  = new MBooker();
$cleaninput = new Cleaninput();


if (isset($_POST["FirstName"]))
{
	print("FirstName  " . $_POST["FirstName"]  . "<br>" );
	$cfirstname = $cleaninput->genclean($_POST["FirstName"]);
}
else
{
	  $cfirstname = "EMPTY";
}

if (isset($_POST["Surname"]))
{
	print("Surname  " . $_POST["Surname"]  . "<br>");
	$csurname = $cleaninput->genclean($_POST["Surname"]);
}
else
{
	  $cposts = "EMPTY";
}


if (isset($_POST["CompanyName"]))
{
	print("CompanyName  " . $_POST["CompanyName"] . "<br>");
	$ccompanyname = $cleaninput->genclean($_POST["CompanyName"]);
}
else
{
	  $ccompanyname = "EMPTY";
}



if (isset($_POST["Email"]))
{
	print("Email  " . $_POST["Email"] . "<br>");
	$cmail = $cleaninput->genclean($_POST["Email"]);
}
else
{
	  $cmail = "EMPTY";
}


if (isset($_POST["Address"]))
{
	print("Address  " . $_POST["Address"] . "<br>");
	$caddress = $cleaninput->genclean($_POST["Address"]);
}
else
{
	  $caddress = "EMPTY";
}


if (isset($_POST["MobilePhone"]))
{
	print("MobilePhone  " . $_POST["MobilePhone"] . "<br>");
	$cmobilephone = $cleaninput->genclean($_POST["MobilePhone"]);
}
else
{
	  $cmobilephone = "EMPTY";
}


if (isset($_POST["Zip"]))
{
	print("Zip   " . $_POST["Zip"] . "<br>");
	$czip = $cleaninput->genclean($_POST["Zip"]);
}
else
{
	  $czip = "EMPTY";
}



if (isset($_POST["WebpageLink"]))
{
	print("WebpageLink   " . $_POST["WebpageLink"] . "<br>" );
	$cwebpagelink = $cleaninput->genclean($_POST["WebpageLink"]);
}
else
{
	  $cwebpagelink = "EMPTY";
}



if (isset($_POST["LinkedinLink"]))
{
	print("LinkedinLink   " . $_POST["LinkedinLink"] . "<br>");
	$clinkedinlink = $cleaninput->genclean($_POST["LinkedinLink"]);
}
else
{
	  $clinkedinlink = "EMPTY";
}



if (isset($_POST["InstagramLink"]))
{
	print("InstagramLink   " . $_POST["InstagramLink"] . "<br>");
	$cinstagramlink = $cleaninput->genclean($_POST["InstagramLink"]);
}
else
{
	  $cinstagramlink = "EMPTY";
}



if (isset($_POST["ClientIndustry"]))
{
	print("ClientIndustry  " . $_POST["ClientIndustry"] . "<br>");
	$cclientindustry = $cleaninput->genclean($_POST["ClientIndustry"]);
}
else
{
	  $cclientindustry = "EMPTY";
}


if (isset($_POST["ClientTypeID"]))
{
	print("ClientTypeID " . $_POST["ClientTypeID"] . "<br>");
	$cclienttypeid = $cleaninput->genclean($_POST["ClientTypeID"]);
}
else
{
	  $cclienttypeid = "EMPTY";
}


if (isset($_POST["ClientTypeID"]))
{
	print("ClientTypeID " . $_POST["ClientTypeID"] . "<br>" );
	$cclienttypeid = $cleaninput->genclean($_POST["ClientTypeID"]);
}
else
{
	  $cclienttypeid = "EMPTY";
}


if (isset($_POST["Password"]))
{
	print("Password " . $_POST["Password"] . "<br>" );
	$cpassword = $cleaninput->genclean($_POST["Password"]);
}
else
{
	  $cpassword = "EMPTY";
}

if (isset($_POST["Privacy"]))
{
	print("CheckBox " . $_POST["Privacy"] . "<br><br><br>");
	$cprivacy =  $cleaninput->genclean($_POST["Privacy"]);

}
else
{
	$cprivacy = 0;
}


if (isset($_SERVER["REMOTE_HOST"]))
{
	$cremotehost = $cleaninput->genclean($_SERVER["REMOTE_HOST"]);


}
else
{
	$cremotehost = 0;
}



if (isset($_SERVER["REMOTE_HOST"]))
{
	$cremotehost = $cleaninput->genclean($_SERVER["REMOTE_HOST"]);


}
else
{
	$cremotehost = 0;
}


$cremoteip = $cleaninput->genclean($_SERVER["REMOTE_ADDR"]);
$cremoteuseragent = $cleaninput->genclean($_SERVER["HTTP_USER_AGENT"]);


$msql = new MMsql();

print("Information " . $DBUSER_LIPLUS . " Password " . $DBPASS_LIPLUS);

$cstring = $msql->dbconnect("root","alien756","liplus");

#Add firstname in the list
$mbooker->createmarque($cstring,$cmail, $cpassword, $cmail, $cmobilephone,$cremoteip,$cremoteuseragent,$cinstagramlink,$cwebpagelink,$csurname,$ccompanyname,$cclientindustry,$czip,$caddress,$cclientindustry);

mysqli_close($cstring);

session_start();

$_SESSION['name42'] = $cmail;
$_SESSION['surname'] = $csurname;
$_SESSION['firstname'] = $cfirstname;
$_SESSION['companyname'] = $ccompanyname;

header($dashboardpage);

?>