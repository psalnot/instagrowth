<?php


class Waf
{

	public function retrievedata($cstring, $id_ref,$iduser)
	{

		$sql = "select waf.ipv4,waf.url,waf.version,waf.id_ref,waf.id_offre,paiement_waf.date_end_abo,paiement_waf.customer_id,paiement_waf.subscription_id from waf inner join paiement_waf on paiement_waf.id_waf = waf.id_ref where  waf.id_ref='" . $id_ref . "'  and waf.id_user='" . $iduser . "' and paiement_waf.user_id = waf.id_user and waf.is_last=1 and paiement_waf.date_end_abo>NOW() limit 1";
		//echo "SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		return $row;
	}

	public function getwafoffreinfo($cstring,$id_stripe)
	{
		$sql = "select description,long_description,montant_ht,montant_ttc from waf_offre where id_stripe='" . $id_stripe . "' limit 1";
		
		$result = mysqli_query($cstring,$sql);
		if (!$result )
		{
			if (DEBUG)
				echo "erreur";exit();
		}
		$row = mysqli_fetch_row($result);
		return $row;
	}

	public function disablewaf($cstring,$id_ref)
	{
		$sql = "update waf set is_last = 0 where id_ref=" . $id_ref;
		$resul_insert = mysqli_query($cstring, $sql);
		return 0;
	}
	
	public function annulationwaf($cstring,$id_ref,$user_id,$customer_id,$subscription_id)
	{
		$sql = "update waf set is_annul=1 where id_ref='" . $id_ref . "' and is_last=1";
		$resul = mysqli_query($cstring, $sql);
		$sql = "update paiement_waf set is_annul=1 where id_waf='" . $id_ref . "' and customer_id='" . $customer_id . "' and subscription_id='" . $subscription_id ."'";
		$resul = mysqli_query($cstring, $sql);
	}

	public function updatewaf($cstring, $cipv4, $curl, $remote_ip, $remote_host, $id_waf, $user_id,$id_waf,$iduser)
	{
		$resul = $this->retrievedata($cstring,$id_waf,$iduser);
		if (!$resul)
		{
			return -1;
		}
		$id_offre = $resul[4];
		$version = $resul[2] + 1;
		if ( $resul[3])
		{
			$id_ref = $resul[3];
		}
		else { $id_ref = $id_waf;}
		
		$sql = "insert into waf (url,ipv4,remote_ip,remote_host,id_offre,id_user,is_last,version,id_ref ) values ('" . $curl . "','" . $cipv4 . "','" . $remote_ip . "','" . $remote_host . "','" . $id_offre . "','" . $user_id . "','1','" . $version . "','" . $id_ref . "')";
		$result_insert = mysqli_query($cstring, $sql);
		//update paiement_waf table
		$waf_id = mysqli_insert_id($cstring);
		$this->disablewaf($cstring, $id_waf);
		

		$sql = "update waf set is_last=1 where id='" . $waf_id . "'";
		
		mysqli_query($cstring, $sql);
		return 1;
		
	}

	/*Add waf url*/
	public function addurl($cstring, $cipv4, $curl, $remote_ip, $remote_host, $id_offre, $user_id)
	{

		// Retrieve paiement id
		$mpaiement = new Paiement();
		$id_paiement = $mpaiement->getemptywafid($cstring, $id_offre, $user_id); 
		if ( !$id_paiement || $id_paiement < 0)
		{


			return 0;
		}
		$sql = "insert into waf (url,ipv4,remote_ip,remote_host,id_offre,id_user,is_last,version ) values ('" . $curl . "','" . $cipv4 . "','" . $remote_ip . "','" . $remote_host . "','" . $id_offre . "','" . $user_id . "','1','1')";

						

		$result = mysqli_query($cstring, $sql);
		if (!$result) {
                          die('Invalid query: ' . mysql_error());
                          //return 0;
		}


		//update paiement_waf table
		$waf_id = mysqli_insert_id($cstring);
		//update id_ref
		$sql = "update waf set id_ref=" . $waf_id . "  where id=" . $waf_id;
		mysqli_query($cstring,$sql);
		$mupdate = $mpaiement->updatewaf($cstring, $waf_id, $id_paiement[0]);
		
		return 1;
		
		
		
	}

	
	public function getwaflist($cstring, $iduser)
	{
		$sql = "select ipv4,url,is_active,id_ref,is_annul from waf where id_user='" . $iduser . "' and is_last=1";
		$result = mysqli_query($cstring,$sql);
		if (!$result)
		{
			echo "Erreur " . $sql; die();
		}
		return $result;
		//return mysql_fetch_object($result);
	}




}

?>