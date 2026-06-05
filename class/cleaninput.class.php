<?php
class Cleaninput
{
	
	public function cleanemail($email)
	{
		$temp =  trim($email);
		
		$temp_filter = filter_var($temp, FILTER_SANITIZE_EMAIL);
		//FILTER_SANITIZE_MAGIC_QUOTES is deprecated with php version 8
		$temp_filter = str_replace(array('<', '>', '"', "'", '&', '\\', '/', '`'), '', $temp_filter);
		// return filter_var($temp_filter, FILTER_SANITIZE_MAGIC_QUOTES);
		return $temp_filter;
		 //return (mysql_real_escape_string($temp_filter));
	}
	
	public function genclean($name)
	{
		$temp =  trim($name);
		$temp = str_replace(array('<', '>', '"', "'", '&', '\\', '/', '`'), '', $temp);
		return $temp;
		//return (filter_var($temp, FILTER_SANITIZE_MAGIC_QUOTES));
		
	}
}

?>
