<?php


class Ticket
{

	

	/*Add ticket*/
	public function addticket($cstring, $ctitle, $cdescription, $remote_ip, $user_id,$request_type)
	{

		$sql = "insert into ticket (title,subject,remote_ip,user_create_id,is_last,statut,request_type) values ('" . $ctitle . "','" . $cdescription . "','" . $remote_ip . "','" . $user_id . "','1','1','" . $request_type .  "')";
		echo $sql;
		$result = mysqli_query($cstring, $sql);
		
		if (!$result) {
                          die('Invalid query: ' . mysql_error());
                          //return 0;
		}
		$last_id = mysqli_insert_id($cstring);
		$sql_last = "update ticket set id_ref='" . $last_id . "' where id='" . $last_id .  "'";
		mysqli_query($cstring,$sql_last);
		
		return 1;
		
		
	}


	/*Request ticket list*/
	public function getticketlist($cstring,$user_id)
        {
                $sql = "select * from ticket where user_create_id='" . $user_id ."' and is_last=1 order by date_create desc";
                $result = mysqli_query($cstring,$sql);

                return $result;
        }

	/* user ticket information  through id number*/
	public function retrieveticketbyid($cstring,$user_id,$ticket_id)
	{
		$sql = "select subject,date_create,date_update,statut,user_create_id,user_rep_id,title,request_type,id_ref from ticket where id='" . $ticket_id . "' and user_create_id='" . $user_id . "'";
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		return $row;
	}



	/*Update ticket information through response*/
	public function updateticket($cstring, $subject, $user_id,$ticket_id,$remote_ip)
	{
		$row = $this->retrieveticketbyid($cstring,$user_id,$ticket_id);
		$sql = "insert into ticket (title,subject,remote_ip,user_create_id,is_last,statut,request_type,date_create,id_ref,user_rep_id) values ('" . $row[6] . "','" . $subject . "','" . $remote_ip . "','" . $user_id . "','1','1','" . $row[7] .  "','" . $row[1] . "','" . $row[8] . "','" . $user_id . "')";
		//echo " La requete sql " . $sql; die();
		mysqli_query($cstring,$sql);
		$usql = "update ticket set is_last=0 where id='" . $ticket_id . "'";
		mysqli_query($cstring, $usql);
		return 1;
	}
		
	/*Retrieve id_ref*/
	public function retrieveidref($cstring,$ticket_id)
	{
		$sql = "select id_ref from ticket where id='" . $ticket_id ."' and is_last=1 limit 1";

		$result =  mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
                return $row[0];

	}
	/*Retrieve all the response information about a ticket*/
	public function retrieveticketidlist($cstring,$user_id,$ticket_id)
	{
		$id_ref = $this->retrieveidref($cstring,$ticket_id);
		
		$sql = " select * from ticket where id_ref='" . $id_ref . "' and user_create_id='" . $user_id . "' order by date_create asc";
		$result = mysqli_query($cstring,$sql);
                
                return $result;
	}
	
	/* Set ticket box color */
	public function colorbox($liduser,$iduser)
	{
		if ( !$liduser || ($liduser == $iduser) )
		{
			return "has-warning";
		}
		else
		{
			return "";
		}
	} 

	/*Show ticket name */
	public function statut_name($statut)
	{
		if (!$statut || $statut == 1)
			return "Ouvert";
		if ( $statut ==2)
		   return "Ferme";
	}
	
	/*Show last intervenant name*/
	public function lasttalk($liduser,$iduser)
	{
		if (!$liduser || ($liduser == $iduser))
		{
			return "Vous";
		}
		return "Support team";
	}




}

?>