<?php


class Varnish
{

	public function getvarnishlist($cstring)
	{
		$sql = "select id,ip,varnish_port from  varnish_list";
		$result = mysqli_query($cstring,$sql);
		if (!$result)
		{
			echo "Erreur " . $sql; die();
		}
		return $result;
		//return mysql_fetch_object($result);
	}




	public function getfwaitinglist($cstring)
	{
		$sql = "select id,remote_ip,remote_host, remote_user_agent,is_wildcard,id_waf,is_last,status,id_user,url,date_create,date_update from  cache_flush_list where status=0 and is_last=1 and url is not null";
		$result = mysqli_query($cstring,$sql);
		if (!$result)
		{
			echo "Erreur " . $sql; die();
		}
		return $result;
		//return mysql_fetch_object($result);
	}





	public function addvarnishflush($cstring, $id_waf, $id_user, $remote_host, $remote_ip, $remote_user_agent, $url,$is_wildcard)
	{
		$sql = "insert into cache_flush_list(id_user,remote_ip,remote_host,remote_user_agent,url,id_waf,status,is_last, is_wildcard) values ('" . $id_user . "','" . $remote_ip .  "','" . $remote_host . "','" . $remote_user_agent . "','" . $url . "','" . $id_waf . "','0','1','" . $is_wildcard . "')";
		//echo $sql;
		$result = mysqli_query($cstring,$sql);
		
		return $result;
	}


	public function banurl($url)
	{
		$cmd = "BANURL / HTTP/1.0\r\n";
		$cmd .= "Host: " . $url . "\r\n";
		$cmd .= "Connection: Close\r\n";
		$cmd .= "\r\n";
		return $cmd;

	}

	public function updatewaitinglist($cstring,$id)
	{
		$sql = "update cache_flush_list set status=1 where id='" . $id . "'";
		$result = mysqli_query($cstring,$sql);
		return $result;
	}

	public function addtrackvarnish($cstring,$id_cache_flush_list, $id_varnish_list, $response)
	{
		$sql = "insert into track_varnish_response (id_cache_flush_list, id_varnish_list, response) values ('" . $id_cache_flush_list . "','" . $id_varnish_list . "','" . $response ."')";
		//echo "Requete SQL" . $sql;

		$result = mysqli_query($cstring,$sql);

		if (!$result)
		{
			echo " erreur sql " . $sql;
		}

		return $result;
	}

	public function gettrackvarnishcachestatuslist($cstring,$id_user)
	{
		$sql = "select cache_flush_list.url as url, waf.ipv4 as ipv4, cache_flush_list.date_update as date_update,cache_flush_list.status as status from cache_flush_list inner join waf on cache_flush_list.id_waf = waf.id where cache_flush_list.is_last=1 and waf.is_last=1 and waf.id_user='" . $id_user . "'";
		$result = mysqli_query($cstring,$sql);
		return $result;
		
	}

	public function statustoword($status)
	{
		if (!$status)
			return "In progress";
		else
			return "Done";
	
	}


}

?>
