<?php


set_include_path('/var/www/instagrowth-V1/class');
include_once("traduction.class.php");
set_include_path('/var/www/instagrowth-V1/utils');
include_once("defines.php");

class Mbuildmenu
{


	public function buildmenuinstagrowth($BACKOFFICE_EDIT_INSTAGRAM,$BACKOFFICE_LOGOUT,$BACKOFFICE_EDIT_ABO,$BACKOFFICE_EDIT_COMPTE,$MAIL_SUPPORT,$MAIL_SUBJECT,$BACKOFFICE_FRONT)
	{
		$menudef = "<div class=\"col-sm-5 col-sm-offset-1 col-xs-12 mt--xs--2\">";
		
		$menudef .= "<div class=\"pricing pricing-2 boxed boxed--border boxed--lg row no-gutters\">";
		$menudef .= "<div class=\"col-lg-12\">";
		$menudef .= "<h2>Votre Dashboard</h2> <span class=\"p\">Nous accompagnons plus de  50.000 Influenceurs <img class=\"rating-xs\" src=\"img/4-5-star.png\"></span>";
		#$menudef .= "<h2>Votre Dashboard</h2> <span class=\"p\">Nous accompagnons plus de  50.000 Influenceurs $BACKOFFICE_EDIT_INSTAGRAM<img class=\"rating-xs\" src=\"img/4-5-star.png\"></span>";
		$menudef .= "<hr></div>";
		$menudef .= "<div class=\"col-lg-12\">";
		$menudef .= "<ul class=\"benefits\">";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  $BACKOFFICE_FRONT  . "\"> Accueil</a></span> </li>";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  $BACKOFFICE_EDIT_INSTAGRAM  . "\"> Password Instagram</a></span> </li>";
		#$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  $BACKOFFICE_EDIT_ABO  . "\"> Votre abonnement </a></span> </li>";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"/TEST_V4/create-customer-portal.php\"> Votre abonnement </a></span> </li>";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  $BACKOFFICE_EDIT_COMPTE  . "\"> Votre compte</a></span> </li>";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"mailto:" .  $MAIL_SUPPORT  . "?subject=" . $MAIL_SUBJECT . "\"> Support</a></span> </li>";
		$menudef .= "</ul>";
		$menudef .= "<a class=\"btn btn--primary btn--lg\" href=\"" .  "/" . $BACKOFFICE_LOGOUT . "\"> <span class=\"btn__text\">Déconnexion</span> </a>";
		$menudef .= "</div></div></div>";
		return $menudef;

	}

	public function buildmenuinstagrowthstats($BACKOFFICE_EDIT_INSTAGRAM,$BACKOFFICE_LOGOUT,$BACKOFFICE_EDIT_ABO,$BACKOFFICE_EDIT_COMPTE,$MAIL_SUPPORT,$MAIL_SUBJECT,$BACKOFFICE_FRONT)
	{
		$menudef = "<div class=\"col-sm-5 col-sm-offset-1 col-xs-12 mt--xs--2\">";
		
		$menudef .= "<div class=\"pricing pricing-2 boxed boxed--border boxed--lg row no-gutters\">";
		$menudef .= "<div class=\"col-lg-12\">";
		$menudef .= "<h2>Votre Dashboard</h2> <span class=\"p\">Nous accompagnons plus de  50.000 Influenceurs <img class=\"rating-xs\" src=\"img/4-5-star.png\"></span>";
		#$menudef .= "<h2>Votre Dashboard</h2> <span class=\"p\">Nous accompagnons plus de  50.000 Influenceurs $BACKOFFICE_EDIT_INSTAGRAM<img class=\"rating-xs\" src=\"img/4-5-star.png\"></span>";
		$menudef .= "<hr></div>";
		$menudef .= "<div class=\"col-lg-12\">";
		$menudef .= "<ul class=\"benefits\">";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  $BACKOFFICE_FRONT  . "\"> Accueil</a></span> </li>";
		#$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  "backoffice-edit-stats-follow-back-fr.php"  . "\"> Analyse des Follow Back</a></span> </li>";
		#$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  $BACKOFFICE_EDIT_ABO  . "\"> Votre abonnement </a></span> </li>";
		#$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"/backoffice-edit-stats-top5.php\"> Top 5 des hashtags et comptes </a></span> </li>";
		#$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  $BACKOFFICE_EDIT_COMPTE  . "\"> A supprimer de la configuration</a></span> </li>";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"mailto:" .  $MAIL_SUPPORT  . "?subject=" . $MAIL_SUBJECT . "\"> Support</a></span> </li>";
		$menudef .= "</ul>";
		$menudef .= "<a class=\"btn btn--primary btn--lg\" href=\"" .  "/" . $BACKOFFICE_LOGOUT . "\"> <span class=\"btn__text\">Déconnexion</span> </a>";
		$menudef .= "</div></div></div>";
		return $menudef;

	}



	public function buildmenuinstagrowthen($BACKOFFICE_EDIT_INSTAGRAM,$BACKOFFICE_LOGOUT,$BACKOFFICE_EDIT_ABO,$BACKOFFICE_EDIT_COMPTE,$MAIL_SUPPORT,$MAIL_SUBJECT,$BACKOFFICE_FRONT)
	{
		$menudef = "<div class=\"col-sm-5 col-sm-offset-1 col-xs-12 mt--xs--2\">";
		
		$menudef .= "<div class=\"pricing pricing-2 boxed boxed--border boxed--lg row no-gutters\">";
		$menudef .= "<div class=\"col-lg-12\">";
		$menudef .= "<h2>Dashboard</h2> <span class=\"p\"> Speed up your instagram growth without buying followers <img class=\"rating-xs\" src=\"img/4-5-star.png\"></span>";
		#$menudef .= "<h2>Votre Dashboard</h2> <span class=\"p\">Nous accompagnons plus de  50.000 Influenceurs $BACKOFFICE_EDIT_INSTAGRAM<img class=\"rating-xs\" src=\"img/4-5-star.png\"></span>";
		$menudef .= "<hr></div>";
		$menudef .= "<div class=\"col-lg-12\">";
		$menudef .= "<ul class=\"benefits\">";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  $BACKOFFICE_FRONT  . "\"> Home</a></span> </li>";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  $BACKOFFICE_EDIT_INSTAGRAM  . "\"> Password Instagram</a></span> </li>";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  $BACKOFFICE_EDIT_ABO  . "\"> Subscription fees</a></span> </li>";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"" .  $BACKOFFICE_EDIT_COMPTE  . "\"> Instagrowth Password</a></span> </li>";
		$menudef .= "<li> <span class=\"checkmark bg--green\"></span><span><a href=\"mailto:" .  $MAIL_SUPPORT  . "?subject=" . $MAIL_SUBJECT . "\"> Contact us</a></span> </li>";
		$menudef .= "</ul>";

		/*$menudef .= "<a class=\"btn btn--primary btn--lg\" href=\"" .  "/" . $BACKOFFICE_LOGOUT_EN . "\"> <span class=\"btn__text\">Logout</span> </a>";*/
		$menudef .= "<a class=\"btn btn--primary btn--lg\" href=\"" .  "/bxoffice-traitement/backoffice-logout-en.php \"> <span class=\"btn__text\">Logout</span> </a>";
		$menudef .= "</div></div></div>";
		return $menudef;

	}



	public function buildmenuwaf($mlanguage,$menuactive)
	{
		$trad = new Mtraduction();

		if ($menuactive == 1)
		{
			$menudef =  "<li class=\"active\"><a href=\" " . L_URL_WAF_LIST . " \">" . $trad->tradgen(MENU_WAF_CONF, $mlanguage,"") . "</a></li>";
		}else
		{
			$menudef =  "<li><a href=\"" . L_URL_WAF_LIST . "\">" . $trad->tradgen(MENU_WAF_CONF,$mlanguage,"") . "</a></li>";
		}

		if ($menuactive == 2)
		{
			$menudef =  $menudef . "<li class=\"active\"><a href=\"" . L_TICKET_LIST . "\">" . $trad->tradgen(MENU_WAF_SUPPORT,$mlanguage,"") . "</a></li>";
		}
		else
		{
			$menudef =  $menudef . "<li><a href=\"" . L_TICKET_LIST . "\">" . $trad->tradgen(MENU_WAF_SUPPORT,$mlanguage,"") . "</a></li>";
			
		}
		


		if ($menuactive == 3)
		{
			$menudef =  $menudef . "<li class=\"active\"><a href=\"" . L_TICKET_NEW . "\">" . $trad->tradgen(MENU_WAF_SUPPORT_NEW,$mlanguage,"") . "</a></li>";
		}
		else
		{
			$menudef =  $menudef . "<li><a href=\"" . L_TICKET_NEW . "\">" . $trad->tradgen(MENU_WAF_SUPPORT_NEW,$mlanguage,"") . "</a></li>";
			
		}
		


		if ($menuactive == 4)
		{
			$menudef =  $menudef . "<li class=\"active\"><a href=\"" . L_CACHE_STATUS . "\">" . $trad->tradgen(MENU_CACHE_STATUS,$mlanguage,"") . "</a></li>";
		}
		else
		{
			$menudef =  $menudef . "<li><a href=\"" . L_CACHE_STATUS . "\">" . $trad->tradgen(MENU_CACHE_STATUS,$mlanguage,"") . "</a></li>";
			
		}
		


		/*if ($menuactive == 5)
		{
			$menudef =  $menudef . "<li class=\"active\"><a href=\"" . L_ADD_WAF . "\">" . $trad->tradgen(PAIEMENT_ADD_WAF,$mlanguage,"") . "</a></li>";
		}
		else
		{
			$menudef =  $menudef . "<li><a href=\"" . L_ADD_WAF . "\">" . $trad->tradgen(PAIEMENT_ADD_WAF,$mlanguage,"") . "</a></li>";
			
		}*/


		if ($menuactive == 5)
		{
			$menudef =  $menudef . "<li class=\"active\"><a href=\"" . L_ADD_GW_SSL . "\">" . $trad->tradgen(PAIEMENT_ADD_GW_SSL,$mlanguage,"") . "</a></li>";
		}
		else
		{
			$menudef =  $menudef . "<li><a href=\"" . L_ADD_GW_SSL . "\">" . $trad->tradgen(PAIEMENT_ADD_GW_SSL,$mlanguage,"") . "</a></li>";

                }





		if ($menuactive == 6)
		{
			$menudef =  $menudef . "<li class=\"active\"><a href=\"" . L_PROFIL_UPDATE . "\">" . $trad->tradgen(PROFIL_UPDATE,$mlanguage,"") . "</a></li>";
		}
		else
		{
			$menudef =  $menudef . "<li><a href=\"" . L_PROFIL_UPDATE . "\">" . $trad->tradgen(PROFIL_UPDATE,$mlanguage,"") . "</a></li>";
			
		}



		if ($menuactive == 7)
		{
			$menudef =  $menudef . "<li class=\"active\"><a href=\"" . L_PROFIL_PASSWORD . "\">" . $trad->tradgen(PROFIL_PASSWORD,$mlanguage,"") . "</a></li>";
		}
		else
		{
			$menudef =  $menudef . "<li><a href=\"" . L_PROFIL_PASSWORD . "\">" . $trad->tradgen(PROFIL_PASSWORD,$mlanguage,"") . "</a></li>";
			
		}



		if ($menuactive == 8)
		{
			$menudef =  $menudef . "<li class=\"active\"><a href=\"" . L_PROFIL_INVOICE . "\">" . $trad->tradgen(PROFIL_INVOICE,$mlanguage,"") . "</a></li>";
		}
		else
		{
			$menudef =  $menudef . "<li><a href=\"" . L_PROFIL_INVOICE . "\">" . $trad->tradgen(PROFIL_INVOICE,$mlanguage,"") . "</a></li>";
			
		}


		$menudef =  $menudef . "<li><a href=\"" . L_PROFIL_LOGOUT . "\">" . $trad->tradgen(PROFIL_LOGOUT,$mlanguage,"") . "</a></li>";

		
		return $menudef;
		
	}

	public function buildmenu($location, $mlanguage,$menuactive)
	{
		if (!isset($mlanguage))
		{
			$mlanguage = "FR";
		}
		if ( !strcmp($location, MENU_WAF))
		{
			return $this->buildmenuwaf($mlanguage,$menuactive);
		}		
		
		
	}

	public function badpasswordheader($mlanguage)
	{
		
		$mgen =  "<div class=\"main col-md-8 col-lg-offset-1 col-md-push-4 col-lg-push-3\">";
		$mgen = $mgen .  "<div style=\"border-spacing: 0px\" class=\"alert alert-danger alert-dismissible\" role=\"alert\">";

		$mgen = $mgen . "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
		if (!strcmp($mlanguage,"EN"))
			$mgen = $mgen . "<strong> Bad current password :</strong> Current password is incorrect.";
		elseif (!strcmp($mlanguage,"FR"))
			$mgen = $mgen . "<strong> Mauvais mot de passe : </strong> Le mot de passe courant que vous avez saisi est incorrect.";
                $mgen = $mgen . "</div></div>";
		return $mgen;

	}



	public function badnewpasswordheader($mlanguage)
	{
		
		$mgen =  "<div class=\"main col-md-8 col-lg-offset-1 col-md-push-4 col-lg-push-3\">";
		$mgen = $mgen .  "<div style=\"border-spacing: 0px\" class=\"alert alert-danger alert-dismissible\" role=\"alert\">";

		$mgen = $mgen . "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
		if (!strcmp($mlanguage,"EN"))
			$mgen = $mgen . "<strong> Bad new password :</strong> New password and repeat new password must be the same. 
					Your new password must containing :<br>
					At least 6 characters;<br>
					At least on lower case letter and one upper case letter;<br>
					At least  on number and a special character (non-word character like /+#@!)   ";
		elseif (!strcmp($mlanguage,"FR"))
			$mgen = $mgen . "<strong> Nouveau mot de passe incorrect : </strong> Les nouveaux mot de passe saisis doivent être les mêmes.<br>
			      	      	Les caractéristiques attendues de votre nouveau mot de passe :<br>
					Il doit contenir au moins 6 caractères;<br>
					Il doit contenir au moins une lettre minuscule et au moins une lettre majuscule;<br>
					Il doit contenir au moins un chiffre et un caractère spécial (i.e. un charactère du type /+#@!.)";
                $mgen = $mgen . "</div></div>";
		return $mgen;

	}




	public function buildhtmlwafpriceline($montant_ht,$montant_ttc,$mlanguage)
	{
		$traduction = new Mtraduction();
		$periode =  $traduction->tradmonth($mlanguage);
	
		$mhtml = "<span>" . ($montant_ht/100) . "€HT/" . $periode . " <small> (" . ($montant_ttc/100) .  "€ TTC/" . $periode . ")</small>";
		return $mhtml;
	}

	public function selectlanguage($mlanguage)
	{
		$mselect =  "<select class=\"form-control\" name=\"mlanguage\">";
		
		if (!$mlanguage || !strcmp($mlanguage,"EN"))
		{
			$mselect = $mselect . "<option value=\"EN\">English</option>";
			$mselect = $mselect . "<option value=\"FR\">Français</option>";
			
		}
		elseif($mlanguage && !strcmp($mlanguage,"FR"))
		{
			$mselect = $mselect . "<option value=\"FR\">Français</option>";
			$mselect = $mselect . "<option value=\"EN\">English</option>";
		}
		$mselect = $mselect  . "</select>";
		return $mselect;
	}
	
}

?>
