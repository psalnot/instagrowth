<?php


class Murl
{

	public function nohttp($url)
	{
		#Regexp from http://webtuts.way2tutorial.com/remove-the-http-https-wwwand-slashes-from-url-in-php/
		$nohttp = preg_replace( "#^[^:/.]*[:/]+#i", "", $url );
		//$nohttp = parse_url($url, PHP_URL_HOST);
	 	return $nohttp;
	}



}

?>
