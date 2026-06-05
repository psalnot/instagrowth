<?php
Class MBooker
{

	public function test()
	{
		return 1;
	}

	public function createmarque($cstring, $cmail, $cpassword, $cmail,$cphone, $remote_ip, $remote_host, $remote_user_agent, $insta, $cweb, $ccontact,$ccompany,$czip,$caddress,$cclientindustry)
	{
		print("Dans la marque");
		$sql = "insert into user (username,password,email,phone,remote_ip,remote_host, remote_user_agent,instagram,url_web,lastname,is_marque,company_name,zipcode,town,fjuridique,firstname) values ('". $cmail . "','" . $cpassword . "','" . $cmail . "','" . $cphone . "','" . $remote_ip . "','" . $remote_host . "','" . $remote_user_agent . "','" . $insta . "','" . $cweb . "','" . $ccontact ."','1','" . $ccompany .  "','" .  $czip . "','" . $caddress . "','" . $cclientindustry  . ')";
		

		#echo "La reqete " . $sql;exit();
		mysqli_query($cstring,$sql);
													   

               	// mysqli_insert_id($cstring);
		$mid = mysqli_insert_id($cstring);
		return $mid;
	

	}


}

?>