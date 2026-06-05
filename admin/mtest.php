<?php

#require 'vendor/autoload.php';
include_once("../class/mailgun.class.php");



header('Content-type: application/json');
include_once("../class/cleaninput.class.php");
include_once("../class/user.class.php");
include_once("../class/mysql.class.php");
require_once("../utils/defines.php");
include_once("../class/cryptor.class.php");


#Mot de passe encryption
#$cpassword="b31da85706db1cf01c15f3c485162430S0POaBPa4TE=";
#$cpassword="07c5c3d8ae79ee4abc68f6e574f48060n/+Ckunh8j8=";
#$cpassword="510332a22afa0649da3e2aca851af325PMdBocfnTTxDor4lvLHI";
#$cpassword="0aad3fcf528870f6357965fbf5c892f7o4C7PGnpnHKAxq0=";
#$cpassword="fd41ff425b7052521c1cac76c258ad6cJUk5Tt2mBldGKvw=";
#$cpassword="9fa8108bbb17bfda54a1cd9048811677B0E+IbRBQ8lDeQ==";
#$cpassword="853b79a293e6247edf633e28ca4784c5EEoaV8X5bJbokzsrz/8=";
#$cpassword="0aea5d0f660658c62f06bbef496ceb1335UFX5A0LoU=";
#$cpassword="9e92babcc33eb21445e7c7e502016ba1Et8MudXuH1MFLw==";
#$cpassword="0aea5d0f660658c62f06bbef496ceb1335UFX5A0LoU=";
#$cpassword="50943ab521359953e933cf0245fdbce84EYE96Gbq32o";
#$cpassword="bcad77e1d64260dd03689832f3356384Xrp2tJy1ABycjOD+a3w+";
#$cpassword="1da3e3a33e8877312d9dbf9beb31f764jX9Hauu+5JTMmQ==";
#$cpassword="25cb32510a8291350e5befedde448c02jkt70nETDZqIQQ==";
#$cpassword="96e49a7095399842ac4170d219b83cc7VYnmaNS7RPND2B8Axwe1FH4VoHKw";
#$cpassword="9e2b7fd9b41cc8da6d1d8bb99030d1adkIejc4MK+CY=";
#$cpassword="f4fa4fd58fb93b01ebfc8ae44ef24d95Bbe4Vs+Sj5I0";
#$cpassword="b0295297d6034f595c261d11b7731f74bnBePzdL";
#$cpassword="30fe966ffb45078b8cbd6a8dfe769944MMldhPQ64Yd+SWUd";
#$cpassword="1daefba2e3a1b4e4f72a1ff0ba1da2deT4JeCrhcde1kaqPIEDQ0XEI=";
#$cpassword="596acab15e842aebec84c3cc9c80ba78dqe32h0Q7pOgTxTQ8A7x";
#$cpassword="b53388bc9192a84bb09c2b3116d47d9cekJjYBqULsJfQQ/lsRU=";
#$cpassword="e60982d1097003ec37e3cc07426d2eaa906e7YWoqYXRZiWMpg=";
#$cpassword="e60982d1097003ec37e3cc07426d2eaa906e7YWoqYXRZiWMpg==";
#$cpassword="1cff824d58780761c63e6b2bf77c3756UseI5hh3jB0jnJE1";
#$cpassword="95be53c7e563b4b38ef8d80b9f87e728zIDlzeW8z2UmUO4B";
#$cpassword="2b409bf44e1de2dc49983ce04d13444bhVOIwq4voEvoQV/x";
#$cpassword="e29a967ea3d9a51c3676fef0b622b4eeCng+NzJe7giXBKj9";
#$cpassword="95d5b57bcc72b020fdd90a3b422e54bbw7oWQCHEQtcagQqdJyUt33fef+Ql9kwIWmdhAWjw";
#$cpassword="9928621c96997d2ceaf980970a702ddbVPJQRCphN53mY92Ciq2y9po=";
#$cpassword="60a2b6eaa62de143780b68e060ddb0aaL9pmNfJVq5UqGkE6uyjgg1g=";
#$cpassword="35fc206d89912a49cb50aa01d8418530e8cKC4pOWXk=";
#$cpassword="d93c491d2f5606c500656f6391aeba69Iy0isJLrRu69aAOycSz8pg==";
#$cpassword="95202ff502591029c8e1fb723b24649c8LitU6ejQMAbYqAi7A==";
#$cpassword="c47df0a895a2829170cf6e84de9fe5d9YHXAyPT0+f7r45Xydw==";
#$cpassword="7e30be203aaac1272e2c8c3e57360d04Q9Uq8ZLfNXq1";
#$cpassword="2896a6575a881f394ddde7b729f38e18qF4q54zLc8EO5tW2";
#$cpassword="17c7d6183f95bad96f4bc1443af4435cqNuAeYbPIoMvhhLUUPKnlEODXhk=";
#$cpassword="a4fdc585155af553a1983d79a29109a9VgIaZCyQCX6ike8=";
#$cpassword="a4fdc585155af553a1983d79a29109a9VgIaZCyQCX6ike8=";
#$cpassword="60a2b6eaa62de143780b68e060ddb0aaL9pmNfJVq5UqGkE6uyjgg1g=";
#$cpassword="a9c14774003c97b3fe851593860ad87cj34OhIVWcy87s1s=";
#$cpassword="7076fb9916b9581081257589d8335f1fDoSfF0YRMs/X/tuHPznC3ok=";
//$cpassword="b1469b68995a1b3b78810a19c4e8d02f2nMymdgBN//Uk+rkRc+6xDU=";
$cpassword="a0b351c88e1403c42b467fb07b3e7576zyGADlttXHwoACPp";
#$cpassword="0ddfd6d6c2dc7cd7327ab0eb4cbd5040dBStBYq6";

	











$cryptor = new Cryptor($ENCRYPTION_KEY);
$crypted_token = $cryptor->decrypt($cpassword);
echo " Descrypt:  ->|" . $crypted_token . "\n";
unset($cpassword);
#040466lJ
#040466lJ
#040466lJ

?>
