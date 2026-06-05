<?php

#include_once("/utils/defines.php");

class MMsql
{

	public function dbconnect($login,$password,$mdatabase)
	{


		$cstring = mysqli_connect('localhost',$login,$password);
		//$cstring = mysqli_connect('localhost',$login,$password);
		//mysql_select_db("liplus",$cstring);
		if (mysqli_connect_errno())
		{
		    echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		
		mysqli_select_db($cstring,$mdatabase);
		return $cstring;
		
	}
	
	public function createuser($cstring, $cname, $cpassword, $cemail)
	{
		//$sql = "insert into user (username,password,email) values (\'". $cname . "\',\'" . $cpassword . "\',\'" . $cemail . "\')";
		//mysql_select_db("liplus",$cstring);
		$sql = "insert into user (username,password,email) values ('". $cname . "','" . $cpassword . "','" . $cemail . "')";
		mysqli_query($cstring,$sql);
		return (mysqli_insert_id($cstring));
		
	}


	 public function subscribecreateuser($cstring, $cname, $cpassword, $cemail,$phone, $remote_ip, $remote_host, $remote_user_agent)
	 {
		$sql = "insert into user (username,password,email,phone,remote_ip,remote_host, remote_user_agent) values ('". $cname . "','" . $cpassword . "','" . $cemail . "','" . $phone . "','" . $remote_ip . "','" . $remote_host . "','" . $remote_user_agent . "')";
		#echo "La reqete " . $sql;
		mysqli_query($cstring,$sql);
		
		
		mysqli_insert_id($cstring);
		$mid = mysqli_insert_id($cstring);
		return $mid;

	}


	public function subscribecreateinfluenceurs($cstring, $cname, $cpassword, $cemail,$phone, $remote_ip, $remote_host, $remote_user_agent, $insta, $firstname,$lastname)
		         {
				$sql = "insert into user (username,password,email,phone,remote_ip,remote_host, remote_user_agent,instagram,firstname,lastname) values ('". $cemail . "','" . $cpassword . "','" . $cemail . "','" . $phone . "','" . $remote_ip . "','" . $remote_host . "','" . $remote_user_agent . "','" . $insta . "','" . $firstname . "','" . $lastname ."')";
				
				//echo "La reqete " . $sql;exit();
				mysqli_query($cstring,$sql);


                mysqli_insert_id($cstring);
		                $mid = mysqli_insert_id($cstring);
				                return $mid;

        }



	public function subscribe_event($cstring, $clastname, $cfirstname, $ccompany, $cinsta, $cweb, $cpoledance, $cphone, $cusername, $ccodepromo, $remote_ip, $remote_host, $remoteuseragent,$event_name)
        {
                                $sql = "insert into sub_event (lastname,firstname,company,instagram, web, poledance, mphone, email, remote_ip,remote_host,remote_useragent,event_name,code_promo) values ('". $clastname . "','" . $cfirstname . "','" . $ccompany . "','" . $cinsta . "','" . $cweb . "','" .  $cpoledance . "','" . $cphone . "','" . $cusername . "','" . $remote_ip . "','" . $remote_host . "','"  . $remoteuseragent . "','" . $event_name . "','" . $ccodepromo . "')";

                                //echo "La reqete " . $sql; exit();
                                mysqli_query($cstring,$sql);
				//exit();

                //mysqli_insert_id($cstring);
                                //$mid = mysqli_insert_id($cstring);
                                                return $mid;

        }


	 public function eval_event($cstring, $cemail, $cinstagram, $ceval, $ccomment, $cautorisation, $remote_ip, $remote_host, $remoteuseragent,$event_name)
        {
                                $sql = "insert into eval_event (email,instagram,evaluation,comment, autorisation, remote_ip,remote_host,remote_useragent,event_name) values ('". $cemail . "','" . $cinstagram . "','" . $ceval . "','" . $ccomment . "','" . $cautorisation . "','" .  $remote_ip . "','" . $remote_host . "','"  . $remoteuseragent . "','" . $event_name . "')";

                                //echo "La reqete " . $sql; exit();
                                mysqli_query($cstring,$sql);
                                //exit();

                //mysqli_insert_id($cstring);
                                //$mid = mysqli_insert_id($cstring);
                                                return $mid;

        }

	public function subscribecreatemarque($cstring, $cmail, $cpassword, $cmail2,$cphone, $remote_ip, $remote_host, $remote_user_agent, $insta, $cweb, $ccontact,$ccompany)
	                         {
				                                 $sql = "insert into user (username,password,email,phone,remote_ip,remote_host, remote_user_agent,instagram,url_web,lastname,is_marque,company_name) values ('". $cmail . "','" . $cpassword . "','" . $cmail . "','" . $cphone . "','" . $remote_ip . "','" . $remote_host . "','" . $remote_user_agent . "','" . $insta . "','" . $cweb . "','" . $ccontact ."','1','" . $ccompany . "')";

								//echo "La reqete " . $sql;exit();
								mysqli_query($cstring,$sql);
													   

               // mysqli_insert_id($cstring);
		                          $mid = mysqli_insert_id($cstring);
						                                                return $mid;

        }




	public function updatesubscribeinstagrowth($cstring, $cmail, $cpassword, $cphone, $cname, $ccompany, $cinstagram, $remote_ip, $remote_host, $remote_user_agent)
	{
		$sql = "update user set phone='" . $cphone . "', password='" . $cpassword . "', name='" . $cname . "', company='" . $ccompany . "', instagram='" . $cinstagram . "', remote_ip='" . $remote_ip . "', remote_user_agent='" . $remote_user_agent . "' where email='" . $cmail . "';";
		
		if ( ! mysqli_query($cstring,$sql) )
		{
			echo("Error description: " . mysqli_error($cstring));
			exit();
		}
	       $mid = mysqli_insert_id($cstring);

	       return $mid;

        }




	public function subscribeinstagrowth($cstring, $cmail, $cpassword, $cphone, $cname, $ccompany, $cinstagram, $remote_ip, $remote_host, $remote_user_agent)
	{
		$sql = "insert into user (email,phone,password,name,company,instagram,remote_ip,remote_host, remote_user_agent) values ('". $cmail . "','" . $cphone . "','" . $cpassword . "','" . $cname . "','" . $ccompany . "','" . $cinstagram . "','" . $remote_ip . "','" . $remote_host . "','" . $remote_user_agent . "')";
		if ( ! mysqli_query($cstring,$sql) )
		{
			echo("Error description: " . mysqli_error($cstring));
			exit();
		}
	       $mid = mysqli_insert_id($cstring);

	       return $mid;

        }

	
	
	
	
	
	

	public function checkuser($cstring,$cemail)
	{
		$sql = "select username from user where email='" . $cemail . "' limit 1";
		$result = mysqli_query($cstring,$sql);
                $row = mysqli_fetch_row($result);

		if ($row and $row[0])
                {
                        return 1;
                }
                else
                {
                        return 0;
                }
	}


	//Add 27/10/2015 create message
	public function createmessage($cstring, $cname, $cemail, $cmessage, $cremoteuseragent, $cremoteip, $creferer,$cremotehost)
	{
		//$sql = "insert into user (username,password,email) values (\'". $cname . "\',\'" . $cpassword . "\',\'" . $cemail . "\')";
		//mysql_select_db("liplus",$cstring);
		$sql = "insert into prospect_message (nom,email,message,remoteuseragent,remoteip,referer,remotehost) values ('". $cname . "','" . $cemail . "','" . $cmessage . "','" . $cremoteuseragent . "','" .  $cremoteip . "','" . $creferer . "','" . $cremotehost .  "')"; 
		$result = mysqli_query($cstring,$sql);
		if (DEBUG)
		{
			if ($result == false)
			{
				echo "<br>####### " . $sql . "<br>######";
				throw new Exception(mysqli_error($cstring));
			}
			 
		}
	}



	//Add 19/02/2016 create atelier
	public function createatelier($cstring, $cname, $cemail, $clastname, $cremoteuseragent, $cremoteip, $creferer,$cremotehost)
	{
		
		//$sql = "insert into user (username,password,email) values (\'". $cname . "\',\'" . $cpassword . "\',\'" . $cemail . "\')";
		//mysql_select_db("liplus",$cstring);
		$sql = "insert into atelier (nom,email,prenom,remoteuseragent,remoteip,referer,remotehost) values ('". $cname . "','" . $cemail . "','" . $clastname . "','" . $cremoteuseragent . "','" .  $cremoteip . "','" . $creferer . "','" . $cremotehost .  "')";
		//echo "ici " . $sql;exit();
		$result = mysqli_query($cstring,$sql);
		if (DEBUG)
		{
			if ($result == false)
			{
				echo "<br>####### " . $sql . "<br>######";
				throw new Exception(mysqli_error($cstring));
			}
			 
		}
	}




	//Add 27/10/2015 create message
	public function createmailing($cstring, $cemail, $cremoteuseragent, $cremoteip, $creferer)
	{

		$sql = "insert into prospect_mailing (email,remoteuseragent,remoteip,referer) values ('".  $cemail . "','" . $cremoteuseragent . "','" .  $cremoteip . "','" . $creferer . "')"; 
		$result = mysqli_query($cstring,$sql);
		if (DEBUG)
		{
			if ($result == false)
			{
				echo "<br>####### " . $sql . "<br>######";
				throw new Exception(mysqli_error($cstring));
			}
			 
		}
	}











	
	public function checkpassword($cstring,$login,$password)
	{
		$sql = "select email,phone,name from user where email='" . $login . "' and password='" . $password ."' limit 1";
		
		$result = mysqli_query($cstring,$sql);
		
		//echo $sql;
		//echo " Résultat " . $result;exit();
		$row = mysqli_fetch_row($result);
		//printf("Errormessage: %s\n", mysqli_error($cstring));exit();
		//echo " information xxx " . $row[0];
		if ($row and $row[0])
		{
			return 1;
		}
		else
		{
			
			return 0;
		}
		 
	}

	public function valid_pass($candidate) {
	        
		if (!preg_match_all('$\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $candidate))
			return 0;
		return 1;

	}

	
	public function checknewpassword($cstring,$newpassword,$newpassword2)
	{

		if (!strcmp($newpassword,$newpassword2))
		{
			
			if ( $this->valid_pass($newpassword))
				return 1;
		}
		return 0;
		
		 
	}


	
	//Travail 03/08/2015 gestion de l'affichage administrateur
	public function isadmin($cstring,$login)
	{
		$sql = "select is_admin from user where email='" . $login ."' limit 1";
		#$result = mysql_query($sql,$cstring);
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if (!$result)
		{
			return 0;
		}
		else 
		{
			return $row[0];
		}
	}

	public function updatepassword($cstring,$mpassword,$cuserid)
	{
	   $sql = "update user set password='" . $mpassword . "' where id='" . $cuserid . "'";
	  
	   $result = mysqli_query($cstring,$sql);
           if (!$result)
	   {
		die('Invalid query: ' . mysql_error());
                return -1;
	   }
	   return 1;
	}
	
	public function checkfirst($cstring,$userid)
	{
		$sql = "select id from user where id='" . $userid . "' and password='4242'";
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ( !$row )
		{
			 //die('Invalid query: ' . mysql_error());
			return -1;
		}
		elseif ($row and $row[0])
		{
			return 1; 
		}
		return -1;
	}


	public function getuserid($cstring,$login)
	{
		$sql = "select id from user where email='" . $login ."' limit 1";
		#echo "requete sql " . $sql;exit();
		#$result = mysqli_query($sql,$cstring);
		$result = mysqli_query($cstring,$sql);

		#$row = mysql_fetch_row($result);
		$row = mysqli_fetch_row($result);
		
		if ($row)
		{
			return $row[0];
		}
		else
		{
			return 0;
		}
	}

	public function getuserinfo($cstring,$uid)
	{
		$sql = "select id,firstname,lastname,date_create,active,username,avatar from user where id='" . $uid ."' limit 1";

                #$result = mysql_query($sql,$cstring);
		 $result = mysqli_query($cstring,$sql);
                $row = mysqli_fetch_row($result);

		     if ($row)
                {
                        return $row;
                }
                else
                {
                        return 0;
                }

	}
	
	public function getvmlist($cstring, $iduser)
	{
		$sql = "select * from vm_list where id_user='" . $iduser . "'";
		$result = mysql_query($sql,$cstring);
		
		return $result;
		//return mysql_fetch_object($result);
	}

	public function getrequestlist($cstring)
	{
		$sql = "select * from request_type_list";
		$result = mysqli_query($cstring,$sql);

		return $result;
	}
	
	
}

?>
