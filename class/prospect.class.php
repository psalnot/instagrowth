<?php

include_once("../utils/defines.php");

class Mprospect
{



	
	
	public function createweb($cstring, $cfirstname, $clastname,$cemail,$cphone,$cbudget,$cbesoin, $cremotehost,$cremoteip)
	{

		$sql = "insert into prospect_web (firstname,lastname, remoteip,remotehost,phone,besoin,budget, email) values ('" . $cfirstname . "','" . $clastname . "','" . $cremoteip  . "','" . $cremotehost. "','" . $cphone . "','" . $cbesoin . "','" . $cbudget . "','" . $cemail . "')";

		
		mysqli_query($cstring,$sql);
		
	}



	
}

?>