<?php

include_once("/utils/defines.php");

class MInstagram
{
	public function updateUserInstagram($cstring,$cid, $biography,$accountName,$followers_count,$media_count,$follows_count,$website,$profile_picture_url)
	{
		$sql = "update user  set biography='" . $biography . "', instagram='" . $accountName . "', instagram_nb_followers='" . $followers_count . "', instagram_media_count='" . $media_count . "', instagram_followers_count='" . $follows_count . "', url_web='" . $website . "', profile_picture_url='" . $profile_picture_url . "'  where id='" . $cid . "'";
		$result = mysqli_query($cstring,$sql);
		return 1;
	}

	public function CreateUserGender($cstring, $cid, $key,$mvalue,$type)
	{
		$sql = " insert into instagram_gender (user_id, genderrange, value, information_step) values ('" . $cid . "','" . $key . "','" . $mvalue . "','" . $type . "')";
		
		$result = mysqli_query($cstring,$sql);
		return 1;
 	}

	 public function CreateUserCountry($cstring, $cid, $key,$mvalue,$type)
	{
		$sql = " insert into instagram_country(user_id, country_code, value, information_step) values ('" . $cid . "','" . $key . "','" . $mvalue . "','" . $type . "')";
		echo $sql;
		$result = mysqli_query($cstring,$sql);
		return 1;
 	}

	public function CreateUserTown($cstring, $cid, $key,$mvalue,$type)
	{
		$sql = " insert into instagram_town(user_id, town, value, information_step) values ('" . $cid . "','" . $key . "','" . $mvalue . "','" . $type . "')";
		echo $sql;
		$result = mysqli_query($cstring,$sql);
		return 1;
 	}

	 public function CreateUserMetrics($cstring, $cid, $mvalue,$end_time,$period, $information_step)
	{
		// Number of view on profile (profile_views statistics)
		$sql = " insert into instagram_metrics(user_id, value, period, information_step,end_time) values ('" . $cid . "','" . $mvalue . "','" . $period . "','" . $information_step . "','" . $end_time . "')";
		echo $sql;
		$result = mysqli_query($cstring,$sql);
		return 1;
 	}

	 public function CreateUserReach($cstring, $cid, $mvalue,$end_time,$period, $information_step)
	{
		$sql = " insert into instagram_reach(user_id, value, period, information_step,end_time) values ('" . $cid . "','" . $mvalue . "','" . $period . "','" . $information_step . "','" . $end_time . "')";
		echo $sql;
		$result = mysqli_query($cstring,$sql);
		return 1;
 	}

	 public function CreateUserImpressions($cstring, $cid, $mvalue,$end_time,$period, $information_step)
	 {
		 $sql = " insert into instagram_impressions(user_id, value, period, information_step,end_time) values ('" . $cid . "','" . $mvalue . "','" . $period . "','" . $information_step . "','" . $end_time . "')";
		 echo $sql;
		 $result = mysqli_query($cstring,$sql);
		 return 1;
	  }

	 public function CreateUserContactEmail($cstring, $cid, $mvalue,$end_time,$period, $information_step)
	{
		// Number of time users click on the email contact button.
		$sql = " insert into instagram_contact_mail(user_id, value, period, information_step,end_time) values ('" . $cid . "','" . $mvalue . "','" . $period . "','" . $information_step . "','" . $end_time . "')";
		echo $sql;
		$result = mysqli_query($cstring,$sql);
		return 1;
 	}


}

?>
