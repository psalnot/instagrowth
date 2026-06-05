<?php

include_once("../utils/defines.php");

class MUser
{



	
	
	public function createwaftoken($cstring, $id_offre, $stripetoken, $user_id, $customer_id,$subscription_id)
	{
		//$sql = "insert into user (username,password,email) values (\'". $cname . "\',\'" . $cpassword . "\',\'" . $cemail . "\')";
		//mysql_select_db("liplus",$cstring);
		$sql = "insert into paiement_waf (id_offre,customer_id, user_id,stripetoken,date_start_abo,date_end_abo,subscription_id) values ('" . $id_offre . "','" . $customer_id . "','" . $user_id . "','" . $stripetoken . "', NOW(), DATE_ADD(NOW(), interval 1 MONTH),'" . $subscription_id .  "')";
		
		
		mysqli_query($cstring,$sql);
		
	}
	

	public function createurlmail($cstring,$user_id,$type_demande,$urllink,$remote_ip,$remote_host,$remote_useragent)
	{
		$sql = "insert into url_mail(user_id,url_valid,is_active,type_demande,remote_ip,remote_host,remote_user_agent) values ('" . $user_id . "','" . $urllink . "','1','" . $type_demande . "','" . $remote_ip . "','" . $remote_host . "','" . $remote_useragent . "')";
		$result = mysqli_query($cstring,$sql);
		return $result;
	}

	public function geturlmail($cstring,$urllink)
	{
		$sql = "select user.email,url_mail.type_demande,user.id from url_mail inner join user on url_mail.user_id=user.id where url_mail.is_active='1' and url_mail.url_valid='" . $urllink . "' limit 1";
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ($row )
		{
			return $row;
		}
		return 0;
	}



	public function updateurlmail($cstring,$urllink,$remote_ip,$remote_host,$remote_useragent)
	{
		$sql = "update url_mail set is_active='0',remote_ip='" . $remote_ip . "', remote_host='" . $remote_host . "', remote_user_agent = '" . $remote_useragent . "'  where url_valid='" . $urllink . "'";
		$mrow = $this->geturlmail($cstring,$urllink);
		$result = mysqli_query($cstring,$sql);

		$sql = "update user set active='1' where id='" . $mrow[2] . "'";
		#echo "SQL  " . $sql;exit();
		$result = mysqli_query($cstring,$sql);

		return 1;
	}



	public function createcustomer($cstring, $user_id, $customer_id)
	{
		//$sql = "insert into user (username,password,email) values (\'". $cname . "\',\'" . $cpassword . "\',\'" . $cemail . "\')";
		//mysql_select_db("liplus",$cstring);
		$sql = "insert into customer_id (customer_id, user_id ) values ('" . $customer_id . "','" . $user_id . "')";
		echo "requete SQL " . $sql;
		mysqli_query($cstring,$sql);
		
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


	public function getprofilinfo($cstring,$uid)
	{

		$sql = "select id,firstname,lastname,date_create,active,username,avatar,language from user where id='" . $uid ."' limit 1";
		
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




	public function mcompanyinfo($cstring,$muid)
	{
	
		
		$sql = "select id,user_id,address,zipcode,phone,siret,companyname,town,country  from user_info where user_id='" . $muid ."' and version_last=1 limit 1";
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


	public function updateuser($cstring,$mlastname,$mfirstname,$uid,$remotehost,$remoteip,$remoteuseragent,$mlanguage)
	{
		$sql = "update user set lastname='" . $mlastname . "', firstname='" . $mfirstname . "', remote_ip='" . $remoteip . "', remote_host='" . $remotehost . "', remote_user_agent='" . $remoteuseragent . "', language='" . $mlanguage . "' where id='" . $uid . "'";
		
		
		$result = mysqli_query($cstring,$sql);
		return $result;

	}




	public function updatepassword($cstring,$mpassword,$cuserid)
	{
		$sql = "update user set password='" . $mpassword . "' where id='" . $cuserid;
		$result = mysqli_query($cstring,$sql);
		return $result;
		
	}



	public function adduserinfo($cstring,$cuserid,$mcompanyname,$mphone,$mzipcode,$maddress,$cremotehost,$cremoteip,$cremoteuseragent,$version_last)
	{
		$sql = "insert into user_info (user_id,companyname,phone,zipcode,address,remote_host,remote_ip,remote_user_agent,version_last) values ('" . $cuserid . "','" . $mcompanyname . "','" . $mphone . "','" . $mzipcode . "','" . $maddress . "','" . $cremotehost . "','" . $cremoteip . "','" . $cremoteuseragent . "','" . $version_last . "')"; 
		$result = mysqli_query($cstring,$sql);
		return (mysqli_insert_id($cstring));		

	}

	public function updateuserinfolast($cstring,$cuserid,$cuserinfo)
	{
		$sql = "update user_info set version_last='0' where user_id='" . $cuserid . "'";
		$result = mysqli_query($cstring,$sql);
		$sql = "update user_info set version_last='1' where id='" . $cuserinfo . "' and user_id='" . $cuserid . "'";
		$result = mysqli_query($cstring,$sql);
	}


	/*Update of influencer informations*/
	public function updateinfluencerdata($cstring,$cuserid,$ctown,$czipcode,$cbirthday,$csex,$cstatus,$cyoutube,$cfacebook)
	{
		$sql = "update user set town='" . $ctown . "',zipcode='" . $czipcode . "',sex='" . $csex . "',birthday='" . $cbirthday . "',status='" . $cstatus . "',youtube='" . $cyoutube . "',facebook='" . $cfacebook ."' where id='" . $cuserid ."'";
		
		#echo "La requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;
	}

	public function updateinfluencergen($cstring,$user_id,$ctown,$czipcode,$cinstagram)
	{
		$sql = "update user set town='" . $ctown . "',zipcode='" . $czipcode . "',instagram='" . $cinstagram . "'";
		$result = mysqli_query($cstring,$sql);
		return $result;
	}
	

        public function updateinfluencerdetails($cstring, $user_id, $cinterest,$cpolitique,$csante,$cbeaute,$ccuisine,$csport)
	{
		$sql = "update user_details set sport='" . $csport . "',sante='" . $csante . "',cuisine='" . $ccuisine . "',politique='" . $cpolitique . "',beaute='" . $cbeaute . "',centre_interet='" . $cinterest . "' where user_id='" . $user_id ."'";
		#echo "SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;
	}

        public function createinfluencerdata($cstring, $user_id, $cinterest,$cpolitique,$csante,$cbeaute,$ccuisine,$csport)
	{
		     
		$sql = "insert into user_details (user_id, centre_interet, politique, sante, beaute, cuisine, sport) values ('" . $user_id . "','" . $cinterest . "','" . $cpolitique . "','" . $csante . "','" . $cbeaute .  "','" . $ccuisine . "','" . $csport . "')";
                mysqli_query($cstring,$sql);

        }

	   public function createmarquedata($cstring, $user_id, $cdesc,$csecteur,$ccampagne,$cbudget)
	   	  {

                $sql = "insert into user_details (user_id, marque_description, marque_secteur, marque_nbinfluenceurs, marque_budget) values ('" . $user_id . "','" . $cdesc . "','" . $csecteur . "','" . $ccampagne . "','" . $cbudget . "')";
		//echo " Requete sql " . $sql;exit();
		                mysqli_query($cstring,$sql);

				}
	
	public function getinfluencerinfo($cstring,$user_id)
	{
		$sql = "select username,email,firstname,lastname,phone,instagram,youtube,facebook,zipcode,town,sex,status,birthday from user where id='" . $user_id ."'";
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

	public function getinfluencerdetails($cstring,$user_id)
	{
		$sql = "select id,sport,sante,cuisine,politique,beaute,centre_interet,user_id from user_details where user_id='" . $user_id . "' limit 1";
		//echo " SQL "  . $sql;
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

	public function getinteretinfluencer($cstring, $mlist)
	{
		$interet = "Avion";
		if ($mlist[1]) $interet = "Sport";
		if ($mlist[2]) $interet .= " Sante";
		if ($mlist[3]) $interet .= " Cuisine";
		if ($mlist[4]) $interet .= " Politique";
		if ($mlist[5]) $interet .= " Beaute";

		return $interet;
	}
	
}

?>