<?php

print("ICI");



if (isset($_POST["FirstName"]))
{
	print("FirstName  " . $_POST["FirstName"] );

	//$cposts = $cleaninput->genclean($_POST["inputPosts"]);
}
else
{
	  $cposts = "";
}


?>