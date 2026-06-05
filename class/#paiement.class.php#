<?php

include_once("../utils/defines.php");

class Paiement
{

	public function getinstaGrowthTarifs($cstring)
	{
		
		$sql = "select name, montant_mensuel_ht,montant_mensuel_ttc   from ref_abo";
		$result = mysqli_query($cstring,$sql);
		return $result;
		
	}





	public function checkpaiementwaf($cstring,$user_id)
	{
		
		$sql = "select waf_offre.id, waf_offre.description  from paiement_waf inner join waf_offre on paiement_waf.id_offre = waf_offre.id where paiement_waf.date_end_abo > NOW() and paiement_waf.id_waf is null  limit 1;";
		$result = mysqli_query($cstring,$sql);
                $row = mysqli_fetch_row($result);
		
		if ($row and $row[0] and $row[1])
                {
                        return $row;
                }
                else
                {
                        return 0;
                }
	}


	
	public function updatewaf($cstring,$waf_id,$paiement_id)
	{
	   $sql = "update paiement_waf set id_waf='" . $waf_id . "' where id='" . $paiement_id . "'";

	   $result = mysqli_query($cstring,$sql);
           if (!$result)
	   {
		die('Invalid query: ' . mysql_error());
                return -1;
	   }
	   return 0;

	}


	public function checkpaiementwebhook($cstring,$stripe_cus_id,$stripe_sub_id,$user_id)
	{
		
		$sql = "select webhook_stripe_track_id,id from paiement_waf where user_id='" . $user_id . "' and subscription_id='" . $stripe_sub_id . "'  and customer_id='" . $stripe_cus_id . "' limit 1";
		$result = mysqli_query($cstring,$sql);
		if (isset($result))
		{
		   $row = mysqli_fetch_row($result);
		   if (isset($row) and !$row[0])
			return $row[1];
		}
		return 0;
		
	}

	public function createpaiementwaf($cstring,$stripe_plan_id,$user_id,$stripe_cus_id,$stripe_sub_id,$webhook_id,$payment_status)
	{
		$sql = "insert into paiement_waf (id_offre,customer_id, user_id,date_start_abo,date_end_abo,subscription_id, webhook_stripe_track_id,payment_status) values ('" . $stripe_plan_id . "','" . $stripe_cus_id . "','" . $user_id . "', NOW(), DATE_ADD(NOW(), interval 1 MONTH),'" . $stripe_sub_id .  "','" . $webhook_id . "','" . $payment_status . "')";

		mysqli_query($cstring,$sql);
		
	}

	public function updatewafpaymentstatus($cstring,$event_type,$stripe_cus_id,$stripe_plan_id,$stripe_sub_id,$user_id,$webhook_id)
	{
		
		if ( ($paiement_id = $this->checkpaiementwebhook($cstring,$stripe_cus_id,$stripe_sub_id,$user_id)))
		{
		
			if (!strcmp($event_type,"invoice.payment_succeeded"))
			{
			   $sql = "update paiement_waf set payment_status='1',webhook_stripe_track_id='" . $webhook_id . "' where id='" . $paiement_id . "'";
			 
			   $result = mysqli_query($cstring,$sql);
			}

			elseif (!strcmp($event_type,"invoice.payment_failed"))
			{
			   $sql = "update paiement_waf set payment_status='2',webhook_stripe_track_id='" . $webhook_id . "' where id='" . $paiement_id . "'";
			 
			   $result = mysqli_query($cstring,$sql);
			}
		

		}
		else
		{
			if (!strcmp($event_type,"invoice.payment_succeeded"))
			   $this->createpaiementwaf($cstring,$stripe_plan_id,$user_id,$stripe_cus_id,$stripe_sub_id,$webhook_id,1);
			elseif (!strcmp($event_type,"invoice.payment_failed"))
			   $this->createpaiementwaf($cstring,$stripe_plan_id,$user_id,$stripe_cus_id,$stripe_sub_id,$webhook_id,2);
			
		}
           	if (!$result)
	   	{
			die('Invalid query: ' . mysql_error());
                	return -1;
	   	}
	   	return 0;

	}



	public function getgenwafinvoice($cstring)
	{

		
		//$sql = "select waf_offre.id as id, waf_offre.description as description,waf_offre.long_description as long_description,waf_offre.montant_ht as montant_ht, waf_offre.montant_ttc as montant_ttc,paiement_waf.user_id as user_id,paiement_waf.date_start_abo as date_start_abo,paiement_waf.id as pid  from paiement_waf inner join waf_offre on paiement_waf.id_offre = waf_offre.id where (paiement_waf.is_facture='0' or paiement_waf.is_facture is null) and paiement_waf.payment_status='1' ";
		//Nous attendons 20 jours apres le debut de l abonnement pour generer la facture
		$sql = "select waf_offre.id as id, waf_offre.description as description,waf_offre.long_description as long_description,waf_offre.montant_ht as montant_ht, waf_offre.montant_ttc as montant_ttc,paiement_waf.user_id as user_id,paiement_waf.date_start_abo as date_start_abo,paiement_waf.id as pid  from paiement_waf inner join waf_offre on paiement_waf.id_offre = waf_offre.id where (paiement_waf.is_facture='0' or paiement_waf.is_facture is null) and paiement_waf.payment_status='1' and DATE_ADD(paiement_waf.date_start_abo, INTERVAL 20 DAY) < NOW()";
		//echo " La requete " . $sql;die();
		$result = mysqli_query($cstring,$sql);
		/*if ($result)
			$row = mysqli_fetch_row($result);
		else
			$row = 0;*/
		return $result;
	}




	public function retrieveinvoice($cstring,$user_id)
	{
		$sql = "select waf_offre.id as id, waf_offre.description as description,waf_offre.long_description as long_description,waf_offre.montant_ht as montant_ht, waf_offre.montant_ttc as montant_ttc,paiement_waf.user_id as user_id,paiement_waf.date_start_abo as date_start_abo,paiement_waf.id as pid,paiement_waf.path_invoice as path_invoice  from paiement_waf inner join waf_offre on paiement_waf.id_offre = waf_offre.id where (paiement_waf.is_facture='1' ) and paiement_waf.payment_status='1' and paiement_waf.user_id='" . $user_id . "'";
		//echo " La requete " . $sql;die();
		$result = mysqli_query($cstring,$sql);
		/*if ($result)
			$row = mysqli_fetch_row($result);
		else
			$row = 0;*/
		return $result;
	}



	public function getinvoicepdf($cstring,$pid,$user_id)
	{
		$sql = "select paiement_waf.path_invoice as path_invoice  from paiement_waf inner join waf_offre on paiement_waf.id_offre = waf_offre.id where (paiement_waf.is_facture='1' ) and paiement_waf.payment_status='1' and paiement_waf.id='" . $pid . "' and paiement_waf.user_id='" . $user_id . "' limit 1";
		//echo " La requete " . $sql;die();
		$result = mysqli_query($cstring,$sql);
		if ($result)
			$row = mysqli_fetch_row($result);
		else
			$row = 0;
		return $row;
	}



	
	
	public function updategenpdf($cstring,$paiement_id,$mfpdf,$invoice_number)
	{
		$sql = "update paiement_waf set  is_facture='1',path_invoice='" . $mfpdf . "', invoice_number='" . $invoice_number . "' where id='" . $paiement_id . "'";
		$result = mysqli_query($cstring,$sql);
		if (!$result)
		   return 0;
		return 1;
	}


	public function getemptywafid($cstring, $id_offre, $user_id)
	{
		
		$sql = "select paiement_waf.id,paiement_waf.price_ht,paiement_waf.price_ttc  from paiement_waf  where paiement_waf.id_offre ='" . $id_offre . "' and  paiement_waf.user_id='" . $user_id . "' and paiement_waf.id_waf is null and paiement_waf.date_end_abo > NOW()  limit 1;";

		

		$result = mysqli_query($cstring,$sql);

                $row = mysqli_fetch_row($result);
		
		if ($row and $row[0] )
                {
                        return $row;
                }
                else
                {
			echo " Mauvais empty waf";
                        return 0;
                }
	}


	

	
}

?>