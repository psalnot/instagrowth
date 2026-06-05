<?php

include_once("../utils/menulanguages.php");


class Minvoice
{

	public function invoicetitle($mlanguage)
	{
		if (!strcmp($mlanguage,"FR"))
		   return "Vos factures";
		elseif (!strcmp($mlanguage,"EN"))
		       return "Invoice";
		return "Invoice";
	}

	public function mlinvoice($mlanguage)
	{
		
		if (!strcmp($mlanguage,"FR"))
		   return "Facture";
		if (!strcmp($mlanguage,"EN"))
		   return "Invoice";
		   
		return "Facture";
	}



	public function mdescription($mlanguage)
	{
		if (!strcmp($mlanguage,"FR"))
		   return "Designation";
		if (!strcmp($mlanguage,"EN"))
		   return "Description";
		return "Designation";
	}
		



	public function mquant($mlanguage)
	{
		if (!strcmp($mlanguage,"FR"))
		   return "Quantite";
		if (!strcmp($mlanguage,"EN"))
		   return "Quantity";
		return "Quantity";
	}



	public function mamountexvat($mlanguage)
	{
		if (!strcmp($mlanguage,"FR"))
		   return "Montant H.T";
		if (!strcmp($mlanguage,"EN"))
		   return "Amount ex VAT";
		return "Montant H.T";
	}



	public function mamountvat($mlanguage)
	{
		if (!strcmp($mlanguage,"FR"))
		   return "Montant TTC";
		if (!strcmp($mlanguage,"EN"))
		   return "Tax Price";
		return "Montant TTC";
	}



	public function mtva($mlanguage)
	{
		if (!strcmp($mlanguage,"FR"))
		   return "TVA";
		if (!strcmp($mlanguage,"EN"))
		   return "VAT";
		return "TVA";
	}



	public function mdateformat($mlanguage,$mtimestamp)
	{
		$mdate = "";
		if ( !$mlanguage)
		   $mlanguage = "EN";
		if (!strcmp($mlanguage,"FR"))
			$mdate  = date("Y-m-d",$mtimestamp);
		if (!strcmp($mlanguage,"EN"))
		   $mdate  = date("d-m-Y",$mtimestamp);

		return $mdate;
	}

	public function mtotalprice($mlanguage)
	{
		$mgen = "Total price";
		if (!strcmp($mlanguage,"FR"))
		   $mgen = "Total TTC";
		elseif (!strcmp($mlanguage,"EN"))
			$mgen = "Total price";
		return $mgen;
	}




	public function mtotaldue($mlanguage)
	{
		$mgen = "Total due";
		if (!strcmp($mlanguage,"FR"))
		   $mgen = "Net a payer";
		elseif (!strcmp($mlanguage,"EN"))
			$mgen = "Total due";
		return $mgen;
	}




	public function buildinvoicelabel($mlanguage,$mstring)
	{
		if ( !isset($mlanguage) || !$mlanguage)
		{
			$mlanguage = "EN";
		}
		$mgen = "unknown";
		if (!strcmp($mstring,INVOICE_LANG))
		{
		   
		   $mgen = $this->mlinvoice($mlanguage);
		}
		
		elseif (!strcmp($mstring,INVOICE_DESCRIPTION))
			$mgen =  $this->mdescription($mlanguage);

		
		elseif (!strcmp($mstring,INVOICE_QUANT))
			$mgen =  $this->mquant($mlanguage);


		elseif (!strcmp($mstring,INVOICE_AMOUNT_HT))
			$mgen =  $this->mamountexvat($mlanguage);


		elseif (!strcmp($mstring,INVOICE_AMOUNT_TTC))
			$mgen =  $this->mamountvat($mlanguage);


		elseif (!strcmp($mstring,INVOICE_TVA))
			$mgen =  $this->mtva($mlanguage);


		elseif (!strcmp($mstring,INVOICE_TOTALPRICE))
			$mgen =  $this->mtotalprice($mlanguage);



		elseif (!strcmp($mstring,INVOICE_TOTALDUE))
			$mgen =  $this->mtotaldue($mlanguage);



		


		return $mgen;		   
	}

	public function getlastnumber($cstring)
	{
		$sql = "select invoice_last_number from invoice_number limit 1";
		$result = mysqli_query($cstring,$sql);
		$row = mysqli_fetch_row($result);
		if ($row)
                {
                        $last_number = $row[0] + 1;
			$sql = "update invoice_number set invoice_last_number='" . $last_number . "' where invoice_last_number='" . $row[0] . "'";
                }
                else
                {
                        $last_number = "14201";
			$sql = "insert into invoice_number (invoice_last_number) values ('" . $last_number . "')";
                }
		$result = mysqli_query($cstring,$sql);
		return $last_number;
		
	}
}

?>
