<?php


class Msupport
{
	/*Vérifie la présence du handle*/
	public function createsupportgen($cstring,$requesttype, $description,$user_id,$subject,$cstatut)
	{

		$sql = "insert into support_request  (request_type,request_generique,id_user,date_creation, is_generic, subject,statut) values (". $requesttype . ",'" . $description . "','" . $user_id . "',NOW(), 1,'" . $subject . "'," . $cstatut . ")";
		$result = mysqli_query($cstring,$sql);
		if (!$result)
		{
			die('Invalid query: ' . mysqli_error($cstring));
			 return -1;
		 }
		 else
		 {
			 $sql = "select LAST_INSERT_ID()";
			 $result = mysqli_query($cstring,$sql);
			 $row = mysqli_fetch_row($result);
			 return $row;
		 }

		 return 0;



	 }


	 public function updaterefid($cstring, $row_id, $ref_id)
	 {
		 $sql = "update support_request set ref_id = '" . $ref_id . "' where id= '" . $row_id . "'";

		 $result = mysqli_query($cstring,$sql);
		 if (!$result)
		 {
		     die('Invalid query: ' . mysqli_error($cstring));
		     return -1;
		 }

	 }


	 public function updatefileupload($cstring, $row_id, $target_file,$name_file,$type_file)
	 {
		 $sql = "update support_request set name_file='" . $name_file. "', type_file='" . $type_file . "', file_upload = '" . $target_file . "' where id= '" . $row_id . "'";

		 $result = mysqli_query($cstring,$sql);
		 if (!$result)
		 {
		     die('Invalid query: ' . mysqli_error($cstring));
		     return -1;
		 }

	 }





	 public function retrieveall($cstring, $user_id,$position,$nbpagination,$search)
	 {
		 if (!$position)
		 {
			if (!$type)
			{
				$sql = "select * from support_request where id_user = '" . $user_id . "' and id=ref_id order by date_creation desc limit $nbpagination";
			}
			else
			{
				$sql = "select * from support_request where request_type='" . $type . "' and id_user = '" . $user_id . "' and id=ref_id order by date_creation desc limit $nbpagination";
			}
		 }
		 else 
		 {
			$positionnext  = $position + $nbpagination;
			if (!$type)
			{
				$sql = "select * from support_request where id_user = '" . $user_id . "' group by ref_id order by date_creation desc limit $nbpagination offset $position ";
			}
			else
			{
				$sql = "select * from support_request where request_type='" . $type . "'  and id_user = '" . $user_id . "' group by ref_id order by date_creation desc limit $nbpagination offset $position ";
			}
		 }
		 $result = mysqli_query($cstring,$sql);

		  if (!$result)
		  {
		     die('Invalid query XX: ' . mysqli_error());
		     return -1;
		  }
		  return $result;
	 }




	 public function retrievealladmin($cstring, $position,$nbpagination,$search,$type)
	 {

		 
		 if (!$position)
		 {
			 if (!$search)
			 { 
				if (!$type)
				{
					$sql = "select * from support_request where  (statut=1 or statut=2 or statut=5) and id=ref_id order by date_creation desc limit " . $nbpagination;
				}
				else
				{
					$sql = "select * from support_request where  (statut=1 or statut=2 or statut=5) and id=ref_id and request_type='" . $type . "'  order by date_creation desc limit " . $nbpagination;
				}
			}
			else
			{
				if (is_numeric($search))
				{
					if (!$type)
					{
						$sql = "select * from support_request where  id='" . $search . "' and (statut=1 or statut=2 or statut=5) and id=ref_id order by date_creation desc limit " . $nbpagination;
					}
					else
					{
						$sql = "select * from support_request where  request_type='" . $type . "' and id='" . $search . "' and (statut=1 or statut=2 or statut=5) and id=ref_id order by date_creation desc limit " . $nbpagination;
					}
				}
				else
				{
					if (!$type)
					{
						$sql = "select * from support_request where  request_generique like '%" . $search . "%' and (statut=1 or statut=2 or statut=5) and id=ref_id order by date_creation desc limit " . $nbpagination;
					}
					else
					{
						$sql = "select * from support_request where  request_type='" . $type . "' request_generique like '%" . $search . "%' and (statut=1 or statut=2 or statut=5) and id=ref_id order by date_creation desc limit " . $nbpagination;
					}
				}
			}
		 }
		 else 
		 {
			 $positionnext  = $position + $nbpagination;
			 if (!$search)
			 {
				if (!$type)
				{
					$sql = "select * from support_request where (statut=1 or statut=2 or statut=5) group by ref_id order by date_creation desc limit $nbpagination offset $position ";
				}
				else
				{
					$sql = "select * from support_request where request_type='" . $type . "' and (statut=1 or statut=2 or statut=5) group by ref_id order by date_creation desc limit $nbpagination offset $position ";
				}
			}
			else
			{
				if (!$type)
				{
					$sql = "select * from support_request where request_generique like '%" . $search . "%' and (statut=1 or statut=2 or statut=5) group by ref_id order by date_creation desc limit $nbpagination offset $position ";
				}
				else
				{
					$sql = "select * from support_request where request_type='" . $type . "' request_generique like '%" . $search . "%' and (statut=1 or statut=2 or statut=5) group by ref_id order by date_creation desc limit $nbpagination offset $position ";
				}
			}
		 }
		 #$result = mysql_query($sql,$cstring);
		 $result = mysqli_query($cstring, $sql);

		  if (!$result)
		  {

		     die('Erreur : ' . mysqli_error($cstring)  . ')' );

		     return -1;
		  }
		  return $result;
	 }




	 public function retrieveallstatut($cstring, $user_id,$statut,$position,$nbpagination,$type)
	 {


		if ($position > 0 )
		{
			if (!$type)
			{
				$sql = "select * from support_request where id_user = '" . $user_id . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination . " offset " . $position;
				
			}
			else
			{
				$sql = "select * from support_request where request_type='" . $type . "' and id_user = '" . $user_id . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination . " offset " . $position;
			}
		}
		else 
		{
			if (!$type)
			{
				$sql = "select * from support_request where id_user = '" . $user_id . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination;
			}
			else
			{
				$sql = "select * from support_request where request_type='" . $type . "' and id_user = '" . $user_id . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination;
			}
		}
		$result = mysqli_query($cstring,$sql);
		
	        if (!$result)
		{
		  echo $sql;
                  die('Invalid query XX: ' . mysqli_error($cstring));
                  return -1;
		}
		return $result;
	}




	public function retrieveallstatutadmin($cstring, $statut,$position,$nbpagination,$type,$search)
	{
	        if ($position > 0 )
		{
			if (!type)
			{
				if (!$search)
				{
					$sql = "select * from support_request where statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination . " offset " . $position;
				}
				else
				{
					if (is_numeric($search))
					{
						$sql = "select * from support_request where id='" . $search . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination . " offset " . $position;
					}
					else
					{
						$sql = "select * from support_request where request_generique like '%" . $search . "%' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination . " offset " . $position;
					}
				}
			}
			else
			{
				if (!$search)
				{
					$sql = "select * from support_request where request_type='" . $type . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination . " offset " . $position;
				}
				else
				{
					if (is_numeric($search))
					{
						$sql = "select * from support_request where id='" . $search . "' and request_type='" . $type . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination . " offset " . $position;
					}
					else
					{
						$sql = "select * from support_request where request_generique like '%" . $search . "%' and request_type='" . $type . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination . " offset " . $position;
					}
				}
			}
		}
		else 
		{
			if (!$type)
			{
				if (!$search)
				{
					$sql = "select * from support_request where statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination;
				}
				else
				{
					if (is_numeric($search))
					{
						$sql = "select * from support_request where id='" . $search . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination;
					}
					else
					{
						$sql = "select * from support_request where request_generique like '%" . $search . "%' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination;
					}
				}
			}
			else
			{
				if (!$search)
				{
					$sql = "select * from support_request where request_type='" . $type . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination;
				}
				else
				{
					if (is_numeric($search))
					{
						$sql = "select * from support_request where id='" . $search . "' and request_type='" . $type . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination;
					}
					else
					{
						$sql = "select * from support_request where request_generique like '%" . $search . "%' and request_type='" . $type . "' and statut='" . $statut . "' and id=ref_id order by date_creation asc limit " .  $nbpagination;
					}
				}
			}
		}
		$result = mysqli_query($cstring,$sql);
		
		 if (!$result)
		 {
			echo  $sql;
			die('Invalid query XX: ' . mysqli_error($cstring));
			return -1;
		 }
		 return $result;
	}






	public function nbopenrequest($cstring, $user_id)
	{
	  $sql = "select count(distinct ref_id) from support_request where id_user='" . $user_id . "' and  ((statut=1) or (statut=2) or (statut is null)) group by ref_id";
	  $result = mysqli_query($cstring,$sql);
	  $row = mysqli_fetch_row($result);
	  return $row[0]; 
	  
	}



	public function nbopenrequestadmin($cstring)
	{
	  $sql = "select count(distinct ref_id) from support_request where ((statut=1) or (statut=2) or (statut is null)) group by ref_id";
	  $result = mysqli_query($cstring,$sql);
	  $row = mysqli_fetch_row($result);
	  return $row[0]; 
	  
	}





	public function nbencoursrequest($cstring, $user_id)
        {
          $sql = "select count(distinct ref_id) from support_request where (id_user='" . $user_id . "' and  statut=2) and id=ref_id";
          #$result = mysql_query($sql,$cstring);
	  $result = mysqli_query($cstring,$sql);
          $row = mysqli_fetch_row($result);
          return $row[0];

        }




	public function nbencoursrequestadmin($cstring)
        {
          $sql = "select count(distinct ref_id) from support_request where statut=2 and id=ref_id";
          #$result = mysql_query($sql,$cstring);
	  $result = mysqli_query($cstring,$sql);
          $row = mysqli_fetch_row($result);
          return $row[0];

        }



	public function nbcloserequest($cstring,$user_id,$isadmin)
        {
	  if ( ! $isadmin)
	  {
		$sql = "select count(distinct ref_id) from support_request where statut=4 and id=ref_id";
	  }
	  else
	  {
		 $sql = "select count(distinct ref_id) from support_request where (id_user='" . $user_id . "' and  statut=4) and id=ref_id";
	  }
          #$result = mysql_query($sql,$cstring);
	  $result = mysqli_query($cstring,$sql);
          $row = mysqli_fetch_row($result);
          return $row[0];

        }

	



	public function nbrequestall($cstring,$user_id)
	{
		 $sql = "select count(*) from support_request where id_user='" . $user_id . "'  and id=ref_id and statut in (1,2,3,4)";
          	 $result = mysqli_query($cstring,$sql);
          	 $row = mysql_fetch_row($result);
          	 return $row[0];

	}




	public function nbrequestalladmin($cstring)
	{
		 $sql = "select count(*) from support_request where id=ref_id and statut in (1,2,5)";
          	 #$result = mysql_query($sql,$cstring);
		 $result = mysqli_query($cstring,$sql);
          	 $row = mysqli_fetch_row($result);
          	 return $row[0];

	}





        public function nbrequeststatut($cstring,$user_id,$statut,$isadmin)
        {
		if ($isadmin)
		{
			$sql = "select count(*) from support_request where id=ref_id and statut='" . $statut . "'";
		}
		else
		{
			$sql = "select count(*) from support_request where id_user='" . $user_id . "'  and id=ref_id and statut='" . $statut . "'";
		}
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		return $row[0];

        }





	public function retrievesupportid($cstring,$support_id)
	{
	  $sql = "select * from support_request where ref_id=" . $support_id . "  order by id asc";
	  #$result = mysql_query($sql,$cstring);
	  $result = mysqli_query($cstring,$sql);

	  if (!$result)
                 {
		   echo "$sql  <br>";
                    die('Invalid query XX: ' . mysqli_error($ctring));
                    return -1;
                 }

	  return $result;
	  
	}


	public function retrievesupport($cstring,$support_id)
	{
	   $sql = "select statut,id_user,file_upload,name_file,type_file from support_request where id=" . $support_id . "  limit 1";
           #$result = mysql_query($sql,$cstring);
           $result = mysqli_query($cstring,$sql);
	   $row = mysqli_fetch_row($result);
	   return $row;
	   
	}



	public function retrievesupportuser($cstring,$support_id,$user_id)
	{
	   $sql = "select statut,id_user,file_upload,name_file,type_file from  support_request where id_user='" . $user_id .  "' and support_request where id=" . $support_id . "  limit 1";
           #$result = mysql_query($sql,$cstring);
           $result = mysqli_query($cstring,$sql);
	   $row = mysqli_fetch_row($result);
	   return $row;
	   
	}

	

	public function updatestatut($cstring, $cid_ref, $cstatut)
	{
	   $sql = "update support_request set date_update=NOW(),  statut ='" . $cstatut . "' where id ='" . $cid_ref . "'";
	   #$result = mysql_query($sql,$cstring);
	   $result = mysqli_query($cstring,$sql);
           if (!$result)
           {
                    die('Invalid query: ' . mysql_error());
                    return -1;
           }

	}
}


?>