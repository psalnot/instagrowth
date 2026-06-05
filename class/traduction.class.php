<?php
set_include_path('/var/www/instagrowth-V1/utils');
include_once("defines.php");

class MTraduction
{
	
	public function menuwafconf($mlanguage)
	{
		$mtext = "Waf Configuration";
		if (!strcmp($mlanguage,"FR"))
		{
			$mtext = "Waf Configuration"; 
		}
		elseif (!strcmp($mlanguage,"EN"))
		{
			$mtext = "Waf Set up";
		}
		return $mtext;
		
	}



	public function menuwafsupport($mlanguage)
	{
		$mtext = "Support";
		if (!strcmp($mlanguage,"FR"))
		{
			$mtext = "Support"; 
		}
		elseif (!strcmp($mlanguage,"EN"))
		{
			$mtext = "Support";
		}
		return $mtext;
		
	}



	public function menuwafsupportnew($mlanguage)
	{
		$mtext = "Nouvelle demande";
		if (!strcmp($mlanguage,"FR"))
		{
			$mtext = "Nouvelle demande"; 
		}
		elseif (!strcmp($mlanguage,"EN"))
		{
			$mtext = "New ticket";
		}

		return $mtext;
		
	}




	public function menuwafcachestatus($mlanguage)
	{
		$mtext = "Cache status";
		if (!strcmp($mlanguage,"FR"))
		{
			$mtext = "Statut du cache"; 
		}
		elseif (!strcmp($mlanguage,"EN"))
		{
			$mtext = "Cache status";
		}

		return $mtext;
		
	}



	public function menupaiementaddwaf($mlanguage)
	{
		$mtext = "Ajout URL";
		if (!strcmp($mlanguage,"FR"))
		{
			$mtext = "Ajout URL"; 
		}
		elseif (!strcmp($mlanguage,"EN"))
		{
			$mtext = "Add URL";
		}

		return $mtext;		
	}



	   public function menupaiementaddgwssl($mlanguage)
	   {
		$mtext = "Ajout URL";
		if (!strcmp($mlanguage,"FR"))
		{
			$mtext = "Ajout GW SSL";
		}
		elseif (!strcmp($mlanguage,"EN"))
		{
			$mtext = "Add GWSSL";
		}

                return $mtext;
	  }


	public function menupaiementaddpricing($mlanguage)
	{
		$mtext = "Pricing";
		if (!strcmp($mlanguage,"FR"))
		{
			$mtext = "Pricing"; 
		}
		elseif (!strcmp($mlanguage,"EN"))
		{
			$mtext = "Pricing";
		}

		return $mtext;		
	}


	public function tradmonth($mlanguage)
	{
		$periode = "Mois";
		if (!strcmp($mlanguage,"EN"))
		   $periode = "Month";
		return $periode;
	}

	public function tradmontantttc($mlanguage)
	{
		$montantttc = "Montant TTC";
		if (!strcmp($mlanguage,"EN"))
		   $montantttc = "Total price";
		return $montantttc;
	}



	public function menuprofilupdate($mlanguage)
	{
		$profilupdate = "Gestion de votre Profil";
		if (!strcmp($mlanguage,"EN"))
		   $profilupdate = "Account management";
		return $profilupdate;
	}



	public function mlastname($mlanguage)
	{
		$mlastname = "Nom";
		if (!strcmp($mlanguage,"EN"))
		   $mlastname = "Last name";
		return $mlastname;
	}



	public function mfirstname($mlanguage)
	{
		$mfirstname = "Prénom";
		if (!strcmp($mlanguage,"EN"))
		   $mfirstname = "First name";
		return $mfirstname;
	}



	public function maddress($mlanguage)
	{
		$maddress = "Adresse";
		if (!strcmp($mlanguage,"EN"))
		   $maddress = "Address";
		return $maddress;
	}



	public function mzipcode($mlanguage)
	{
		$mzipcode = "Code postal";
		if (!strcmp($mlanguage,"EN"))
		   $mzipcode = "Zip code";
		return $mzipcode;
	}


	public function mphone($mlanguage)
	{
		$mphone = "Téléphone";
		if (!strcmp($mlanguage,"EN"))
		   $mphone = "Phone";
		return $mphone;
	}


	public function mcompanyname($mlanguage)
	{
		$mcompanyname = "Société";
		if (!strcmp($mlanguage,"EN"))
		   $mcompanyname = "Company";
		return $mcompanyname;
	}



	public function updatebutton($mlanguage)
	{
		$mgen = "Mise à jour";
		if (!strcmp($mlanguage,"EN"))
		   $mgen = "Update";
		return $mgen;
	}



	public function mlanguage($mlanguage)
	{
		$mgen = "Langue";
		if (!strcmp($mlanguage,"EN"))
		   $mgen = "Language";
		return $mgen;
	}



	public function menuprofillogout($mlanguage)
	{
		$mgen = "Déconnexion";
		if (!strcmp($mlanguage,"EN"))
		   $mgen = "Logout";
		return $mgen;
	}




	public function menuprofilpassword($mlanguage)
	{
		$mgen = "Changement password";
		if (!strcmp($mlanguage,"EN"))
		   $mgen = "Update Password";
		return $mgen;
	}




	public function mpassword($mlanguage)
	{
		$mgen = "Password";
		if (!strcmp($mlanguage,"EN"))
		   $mgen = "Password";
		return $mgen;
	}



	public function moldpassword($mlanguage)
	{
		$mgen = "Renseignez votre mot de passe";
		if (!strcmp($mlanguage,"EN"))
		   $mgen = "Your current password";
		return $mgen;
	}




	public function menuprofilinvoice($mlanguage)
	{
		$mgen = "Invoice";
		if (!strcmp($mlanguage,"FR"))
		   $mgen = "Vos factures";
		if (!strcmp($mlanguage,"EN"))
		   $mgen = "Invoice";
		return $mgen;
	}



	public function mnewpassword($mlanguage, $mrepeat)
	{
		$mgen = "";
		if ($mrepeat)
			$mgen = "Retapez ";
		$mgen = "Votre nouveau password";
		if (!strcmp($mlanguage,"EN"))
		{
		   $mgen = "";
		   if ($mrepeat)
			$mgen = "Repeat ";
		   $mgen = $mgen . "Your new password";
		}
		return $mgen;
	}


	

	

	public function tradgen($location, $mlanguage, $textoption)
	{
		
		if ( !strcmp($location, MENU_WAF_CONF))
		{
			return $this->menuwafconf($mlanguage);
		}
		elseif ( !strcmp($location, MENU_WAF_SUPPORT))
		{
			return $this->menuwafsupport($mlanguage);
		}
		elseif ( !strcmp($location, MENU_WAF_SUPPORT_NEW))
                {
                        return $this->menuwafsupportnew($mlanguage);
                }
		elseif ( !strcmp($location, MENU_CACHE_STATUS))
                {
                        return $this->menuwafcachestatus($mlanguage);
                }

		elseif ( !strcmp($location, PAIEMENT_ADD_WAF))
                {
                        return $this->menupaiementaddwaf($mlanguage);
                }

		elseif ( !strcmp($location, PAIEMENT_ADD_GW_SSL))
		{
			return $this->menupaiementaddgwssl($mlanguage);
		}

		elseif ( !strcmp($location, PAIEMENT_ADD_WAF_PRICING))
                {
                        return $this->menupaiementaddpricing($mlanguage);
                }



		elseif ( !strcmp($location, PROFIL_UPDATE))
                {
                        return $this->menuprofilupdate($mlanguage);
                }




		elseif ( !strcmp($location, PROFIL_LOGOUT))
                {
                        return $this->menuprofillogout($mlanguage);
                }



		elseif ( !strcmp($location, PROFIL_PASSWORD))
                {
                        return $this->menuprofilpassword($mlanguage);
                }


		elseif ( !strcmp($location, PROFIL_INVOICE))
                {
                        return $this->menuprofilinvoice($mlanguage);
                }


		return "inconnu";
		
	}
	
}

?>
