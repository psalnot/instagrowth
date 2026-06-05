<?php

session_cache_limiter('nocache');
include_once("../../utils/defines-ubooker.php");
session_destroy();

$mlocation = "location: /";

header($mlocation);

?>