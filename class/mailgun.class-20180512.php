<?php
/**
 * This example shows how to use POP-before-SMTP for authentication.
 */

require_once('../xtze/vendor/autoload.php');




use Mailgun\Mailgun;

Class MMmailgun 
{

	public function buildlink($urllink)
	{
		$blink = "https://www.lesinfluenceurs.net/admin/valid-email.php?msign=" . $urllink;
		return $blink;
	}

	public function mhtmlaccount($urllink)
	{

		$body = file_get_contents('../PHPMailer-master/examples/activation-mail.html');
		$body  = eregi_replace("%e%" ,$this->buildlink($urllink), $body);
		return $body;
	}


	 public function mhtmlmarque()
	 {

                $body = file_get_contents('../PHPMailer-master/examples/confirmation-prise-en-compte-marque.html');
		//$body  = eregi_replace("%e%" ,$this->buildlink($urllink), $body);
		return $body;
	}

	 public function mhtmlevent()
	 {

		$body = file_get_contents('../PHPMailer-master/examples/confirmation-prise-en-compte-event.html');
		//$body  = eregi_replace("%e%" ,$this->buildlink($urllink), $body);
		return $body;
	}




	public function msendmail($toemail,$mtypemail,$subjectemail,$urllink,$wmail)
	{
			
		# Instantiate the client.
		#$mgClient = new Mailgun('key-4d3f7268e467c52b9af7ca6c852479d8');
		$mgClient = new Mailgun('key-4d3f7268e467c52b9af7ca6c852479d8');	
		$domain = "mg.lesinfluenceurs.fr";
		
		if ($mtypemail == 1)
		{
			$mailhtml = $this->mhtmlaccount($urllink);
		}
		if ($mtypemail == 2)
		{
			$mailhtml = $urllink;
		}
		# Make the call to the client.
		$result = $mgClient->sendMessage($domain, array(
			'from'    => 'Support les influenceurs  <support@lesinfluenceurs.fr>',
			'to' => $toemail,			
			'subject' => $subjectemail,
			'text'    => 'Ceci est votre mot de passe ',
			'html' => $mailhtml
		));
		return $result;
	}


	public function msendevent($toemail,$mtypemail,$subjectemail)
        {

                # Instantiate the client.
                #$mgClient = new Mailgun('key-4d3f7268e467c52b9af7ca6c852479d8');
                $mgClient = new Mailgun('key-4d3f7268e467c52b9af7ca6c852479d8');
                $domain = "mg.lesinfluenceurs.fr";

                if ($mtypemail == 1)
                {
                        $mailhtml = $this->mhtmlevent($urllink);
                }
                if ($mtypemail == 2)
                {
                        $mailhtml = $urllink;
                }
                # Make the call to the client.
                $result = $mgClient->sendMessage($domain, array(
                        'from'    => 'Support les influenceurs  <support@lesinfluenceurs.fr>',
                        'to' => $toemail,
                        'subject' => $subjectemail,
                        'text'    => 'Confirmation de votre inscription',
                        'html' => $mailhtml
                ));
                return $result;
        }	



	public function msendrequest($toemail,$subjectemail,$mtext)
        {

		# Instantiate the client.
		#$mgClient = new Mailgun('key-4d3f7268e467c52b9af7ca6c852479d8');
		$mgClient = new Mailgun('key-4d3f7268e467c52b9af7ca6c852479d8');
                $domain = "mg.lesinfluenceurs.fr";
		//echo " Toemail " . $toemail;
		$mailhtml = $this->mhtmlmarque();
                # Make the call to the client.

                $result = $mgClient->sendMessage($domain, array(
                        'from'    => 'Support les influenceurs  <support@lesinfluenceurs.fr>',
                        'to' => $toemail,	
                        'subject' => $subjectemail,
                        'text'    => $mailhtml,
                        'html' => $mailhtml
                ));
		
                return $result;
        }

}
