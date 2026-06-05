<?php
class Cleaninput
{
	
	public function cleanemail($email)
	{
		$temp =  trim($email);
		
		 $temp_filter = filter_var($temp, FILTER_SANITIZE_EMAIL);
		 return filter_var($temp_filter, FILTER_SANITIZE_MAGIC_QUOTES);
		 //return (mysql_real_escape_string($temp_filter));
	}
	
	public function genclean($name)
	{
		$temp =  trim($name);
		return (filter_var($temp, FILTER_SANITIZE_MAGIC_QUOTES));
		
	}
}

?>