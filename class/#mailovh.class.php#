<?php
/**
 * This example shows how to use POP-before-SMTP for authentication.
 */

require '../PHPMailer-master/PHPMailerAutoload.php';

Class MMmail
{


	public function msendmail($toemail,$fromemail,$subjectemail,$urllink,$wmail)
	{
	
	//Authenticate via POP3.
	//After this you should be allowed to submit messages over SMTP for a while.
	//Only applies if your host supports POP-before-SMTP.
	$pop = POP3::popBeforeSmtp('ns0.ovh.net', 110, 30, 'psalnot@lesinfluenceurs.fr', 'alien756', 1);

		//Create a new PHPMailer instance
		//Passing true to the constructor enables the use of exceptions for error handling
		$mail = new PHPMailer(true);
		try {
			$mail->isSMTP();
		
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$mail->SMTPDebug = 0;
			//Ask for HTML-friendly debug output
			$mail->Debugoutput = 'html';
			$mail->SMTPSecure = "tls"; 
			//Set the hostname of the mail server
			$mail->Host = "ssl0.ovh.net";
			$mail->Username = "psalnot@lesinfluenceurs.fr";  // Gmail username
			$mail->Password = "alien756";      // Gmail password
			//Set the SMTP port number - likely to be 25, 465 or 587
			$mail->Port = 587;
			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;
			//Set who the message is to be sent from
			$mail->setFrom($fromemail, 'Support les influenceurs');
			//Set an alternative reply-to address
			$mail->addReplyTo($fromemail, 'Support les influenceurs');
			//Set who the message is to be sent to
			$mail->addAddress($toemail, '');
			//Set the subject line
			$mail->Subject = $subjectemail;
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//and convert the HTML into a basic plain-text alternative body
			//0: Mail de creation; 1: Mail de reset password
			if ( !$wmail)
				$body = file_get_contents('../PHPMailer-master/examples/activation-mail.html');
			else
				$body = file_get_contents('../PHPMailer-master/examples/reset-mail.html');
			$body  = eregi_replace("%e%" ,$urllink, $body);
			$mail->msgHTML($body, dirname(__FILE__));
			//Replace the plain text body with one created manually
			$mail->AltBody = 'Les influenceurs';
			//Attach an image file
			//$mail->addAttachment('../PHPMailer-master/examples/images/phpmailer_mini.png');
			//send the message
			//Note that we don't need check the response from this because it will throw an exception if it has trouble
			$mail->send();
			//echo "Message sent!";
			} catch (phpmailerException $e) {
				return $e->errorMessage(); //Pretty error messages from PHPMailer
			} catch (Exception $e) {
				return $e->getMessage(); //Boring error messages from anything else!
			}
			return 0;
	}
}