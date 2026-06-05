<?php


class Mdomain
{
	/*Vérifie la présence du handle*/
	public function checkhandle($cstring,$login)
	{
		$sql = "select ghandle from user where email='" . $login  ."' limit 1";
		$result = mysql_query($sql,$cstring);
		$row = mysql_fetch_row($result);
		
		if ($row[0])
		{
			return $row[0];
		}
		else
		{
			return 0;
		}
		
		
	}
	
	/*Crée le contact pour gandi*/
	public function gcontact($cstring,$login,$apikey,$grpc)
	{
	
		$sql = "select firstname,lastname,user.email,streetaddr,zip,city,country,phone from user_info  inner join user on user.id = user_info.id_user where user.email='" . $login . "'";
		$result = mysql_query($sql,$cstring);
		$row = mysql_fetch_row($result);
		
		$contact_api =
		XML_RPC2_Client::create($grpc, array('prefix'
		=> 'contact.', 'sslverify' => False));
		$contact_spec =  array(
			'given' =>  $row[0],
		    'family'=> $row[1],
		    'email' =>  $row[2],
		    'streetaddr' =>  $row[3],
		    'zip' =>  $row[4],
		    'city' =>  $row[5],
		    'country' =>  $row[6],
		    'phone' => $row[7],
		    'type' =>  0,
		    'extra_parameters' => array(
			     'birth_city' => 'PARIS',
			     'birth_department' => '99',
			     'birth_dpt' => '99',
			     'birth_country' => 'FR'
		     ),
		     'password' => 'alien567');
     
     

		try{
		$mresult = $contact_api->__call('create', array($apikey,$contact_spec));
		}
		catch (Exception $e)
		{
		 //return 0;
		 echo 'Exception reçue : ',  $e->getMessage(), "\n";
		}
		
		return 	$mresult['handle'];
		//print "Handle du domaine créé " .  $mresult['handle'];
	}
	
	/*Mise à jour du handle du user*/
	public function updatehandle($cstring,$login,$apikey,$grpc,$ghandle)
	{
		$sql = "update user set ghandle='" . $ghandle . "' where email='" . $login . "'";
		$result = mysql_query($sql,$cstring);
		if ($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}
		
	}
	
	/*Création du domaine sur gandi*/
	public function domaincreate($cstring,$apikey,$grpc,$ghandle,$domain,$id_user)
	{
		
		try
		{
			$domain_spec = array(
			'owner' => $ghandle,
			'admin' => $ghandle,
			'bill'  => $ghandle,
			'tech'  => $ghandle,
			'nameservers' => array('a.dns.gandi-ote.net', 'b.dns.gandi-ote.net',
		                           'c.dns.gandi-ote.net'),
		    'duration' => 1);
		    
		    try
		    {
		    
		    $domain_api = XML_RPC2_Client::create($grpc, array( 'prefix' => 'domain.', 'sslverify' => False ));
		    $op = $domain_api->__call('create', array($apikey, $domain, $domain_spec));
		    }
		    catch(Exception $e)
		    {
		    	$merror = addslashes($e->getMessage());
		    	$sql = " insert into error_gandi (id_user,mfunction,error_message,domainname,date_creation) values ('" . $id_user . "','domaincreate','" . $merror . "','" . $domain . "','" .  date("Y-m-d H:i:s") . "')";  
		    	
			    $result = mysql_query($sql,$cstring);
			    if (!$result) {
    die('Invalid query: ' . mysql_error());
}
				return 0;
		    }
		    
			try
		    {
	    	
		    	$operation_api =
				XML_RPC2_Client::create($grpc, array('prefix'
				=> 'operation.', 'sslverify' => False));
			   	 $op = $operation_api->info($apikey, $op['id']);
			   	 //print "EEEEEEEEEEEEEEE";
			   	 //print   " Step " . $op['step'];
			}
		   	catch (Exception $e)
		   	{
		   		$sql = " insert into error_gandi (id_user,mfunction,error_message,domainname,date_creation) values ('" . $id_user . "','domaincreate','" . $merror . "','" . $domain . "','" .  date("Y-m-d H:i:s") . "')";   
			    $result = mysql_query($sql,$cstring);
			    return 0;
			   	//echo 'Exception reçue : ',  $e->getMessage(), "\n";
		   	}
		 }
		 catch (Exception $e)
		 {
		 	$sql = " insert into error_gandi (id_user,mfunction,error_message,domainname,date_creation) values ('" . $id_user . "','domaincreate','" . $merror . "','" . $domain . "','" .  date("Y-m-d H:i:s") . "')";  
			 $result = mysql_query($sql,$cstring);
			 return 0;
			 //echo 'Exception reçue : ',  $e->getMessage(), "\n";
		 }
		 
		 /*insertion du domaine dans la table domaine*/
		 $sql = "insert into domaine (id_user,domainname,date_creation,date_renouvellement,date_end,id_domaine) values ('" . $id_user . "','" . $domain . "','" . date("Y-m-d H:i:s") . "','" . date("Y-m-d H:i:s") . "','" . date("Y-m-d H:i:s", strtotime("+1 year")) . "','" . $op['id'] . "')";
		 $result = mysql_query($sql,$cstring);
		 //echo "ICI";
		 //print $domain_api->info($apikey, $domain);
		  if (!$result) {
			  die('Invalid query: ' . mysql_error());
			  //return 0;
		  }

		 return 1;

	}
}

?>