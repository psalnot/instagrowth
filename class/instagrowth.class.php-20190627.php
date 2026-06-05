<?php

include_once("../utils/defines.php");

class MInstagrowth
{
	
	public function updateinstagrowthmailing($cstring,$cid)
	{
		$sql = "update user_instagram_profil set mailing=NOW() where id='" . $cid . "'"; 
		#echo " La requete " . $sql;exit();
		$result = mysqli_query($cstring,$sql);
		return $result;
		
	}






	public function insertnewperiodabo($cstring,$user_id,$montant_ttc,$montant_ht,$ref_abo)
	{
	
		$sql = "insert into user_abo_paiement (user_id,montant_ttc,montant_ht,ref_abo,date_start,date_end) values ('".$user_id."','". $montant_ttc."','" . $montant_ht . "','" . $ref_abo . "',NOW(),DATE_ADD(NOW(),INTERVAL 1 MONTH))";
		
		echo "Requete SQL " . $sql . "\n";
		#exit();
		$result = mysqli_query($cstring,$sql);
		$mid = mysqli_insert_id($cstring);
		return $mid;

	}



	public function checkcardinfo($cstring,$user_id)
	{
	
		$sql = "select customer_id from user_card_info where user_id='" . $user_id . "'";
		#echo "ici";exit();
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ($row)
		{
			if ($row[0])
			{
				return row[0];
			}
		}
		return -1;
	}


	public function checkabo($cstring,$user_id)
	{
	
		$sql = "select is_active from user_abo_info where user_id='" . $user_id . "'";
		#echo "ici";exit();
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ($row)
		{
		//echo " ROW " . $row[0];exit();
			if ($row[0])
			{
				return row[0];
			}
		}
		return -1;
	}



	public function checkdoublonsendmail($cstring,$email)
	{
	
		$sql = "select count(*) from user_instagram_profil where email='" . $email . "' and mailing is not null"; 
		#echo "ici";exit();
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ($row)
		{
			if ($row[0])
			{
				return $row[0];
			}
		}
		return 0;
	}


	public function getinstagrowthsendmail($cstring)
	{
	
		$sql = "select email,name,id from user_instagram_profil where  email is not null and email!=''  and mailing is null and is_unsubscribe is null and is_good_email=1 and id > 200000";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}



	public function getinstagrowthEnglishsendmail($cstring)
	{
	
		$sql = "select email,name,id from user_instagram_profil where ((email like '%.ru%') or (email like '%.de%') or (email like  '%.it%') or (email like '%.br%') or (email like '%.uk%')  ) and mailing is null and is_unsubscribe is null";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}



	public function getinstagrowthResendEnglishsendmail($cstring)
	{
	
		$sql = "select email,name,id from user_instagram_profil where ((email like '%.ru%') or (email like '%.de%') or (email like  '%.it%') or (email like '%.br%') or (email like '%.uk%')  ) and (date_update < DATE_SUB(NOW(), INTERVAL 90 day)) and mailing is not null  and is_unsubscribe is  null";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}




	public function getinstagrowthFrenchsendmail($cstring)
	{

		$sql = "select email,name,id from user_instagram_profil where (email like '%math%' or user_bio like '%influenceu%' or user_bio like '%partnariat%' or user_bio like '%agence%' or user_bio like '%france%' or user_bio like '%paris%' or user_bio like '%toulouse%' or user_bio like '%bonjour%' or email like '%.gp%' or email like '%.fr%' or email like '%clemence%' or email like '%carole%' or email like '%stephane%' or email like '%jeanne%' or email like '%guillaume%' or email like '%axelle%' or email like '%francois%' or email like '%laetitia%') and (mailing is null) and (email!='' ) and is_unsubscribe is null";
		//$sql = "select email,name,id from user_instagram_profil where  email is not null and email!=''  and mailing is null and id>'275854' ";
		#echo "Requete SQL " . $sql;exit();
		$result = mysqli_query($cstring,$sql);
		return $result;

	}


	public function getinstagrowthRESENDFrenchsendmail($cstring)
	{

		//$sql = "select email,name,id from user_instagram_profil where (user_bio like '%agence%' or user_bio like '%france%' or user_bio like '%paris%' or user_bio like '%toulouse%' or user_bio like '%bonjour%' or email like '%.gp%' or email like '%.fr%' or email like '%clemence%' or email like '%carole%' or email like '%stephane%' or email like '%jeanne%' or email like '%guillaume%' or email like '%axelle%' or email like '%francois%' or email like '%laetitia%' or email like '%maison%' ) and (mailing is not null) and (email!='' ) and (date_update < DATE_SUB(NOW(), INTERVAL 30 day) ) and (is_unsubscribe is null)";
		$sql = "select email,name,id from user_instagram_profil where  is_good_email=1 and email!=''  and mailing is not null and is_unsubscribe is null and (mailing < DATE_SUB(NOW(), INTERVAL 90 day))";
		#echo "Requete SQL " . $sql;exit();
		$result = mysqli_query($cstring,$sql);
		return $result;

	}



	public function getinstagrowthResendmail($cstring)
	{

		$sql = "select email,name,id,date_create,date_update from user_instagram_profil where mailing is not null and is_unsubscribe is null and email!=''  and date_update < DATE_SUB(NOW(), INTERVAL 30 day )";
		//$sql = "select email,name,id from user_instagram_profil where  email is not null and email!=''  and mailing is null and is_unsubscribe is null";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}


	public function getinstagrowthusers($cstring)
	{
	
		$sql = "select email,name,id,user_profil_img_link from user_instagram_profil where  user_nb_followers > 4500 and user_profil_img_link!='' ";
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}


	public function getinstagrowthpicture($cstring)
	{
	
		$sql = "select email,name,id,user_profil_img_link from user_instagram_profil where  user_nb_followers > 4500 and user_profil_img_link!='' and file_picture is null";
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}


	public function updatefilepicture($cstring,$cid,$mfilename)
	{
		$sql = "update user_instagram_profil set file_picture='" . $mfilename . "' where id='" . $cid . "'"; 
		#echo " La requete " . $sql;exit();
		$result = mysqli_query($cstring,$sql);
		return $result;
		
	}



	public function getinstagrowthcarderror($cstring)
	{
	
		$sql = "select email,user.id from user inner join user_card_info on user.id = user_card_info.user_id inner join user_abo_info on user.id=user_abo_info.user_id where user_abo_info.is_active=1 and user_card_info.customer_id=''";
		#echo "Requete SQL " . $sql;
		#exit();
		$result = mysqli_query($cstring,$sql);
		return $result;

	}


	public function retrieveabo($cstring)
	{
		$sql = "select user.id,user_card_info.customer_id,user_abo_info.montant_abo_ttc,user_abo_info.montant_abo,user_abo_info.ref_abo from user_abo_info  inner join user_card_info on user_card_info.user_id=user_abo_info.user_id inner join user on user.id = user_abo_info.user_id where user_abo_info.is_active=1 and user_abo_info.test_period_end < DATE_ADD(NOW(),INTERVAL 1 DAY)";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		return $result;

	}



	public function getlastabo($cstring,$user_id)
	{
		$sql = "select date_end from user_abo_paiement where user_id='" . $user_id . "' and date_end >= DATE_ADD(CURRENT_DATE(),INTERVAL 1 DAY)";
		
		//echo "Requete SQL " . $sql;
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ($row)
		{
			return $row[0];
		}
		return -1;

	}

	


}

?>
