<?php
function Send_Mail($to, $from_email, $body, $subject)
//function Send_Mail($to,$from_email,$body)
{
	require_once 'class.phpmailer.php';
	$from = $from_email; //"canopus.info@gmail.com"; //from email is sending 
	$mail = new PHPMailer();
	$mail->IsSMTP(true); // use SMTP
	$mail->IsHTML(true);
	$mail->SMTPAuth = true; // enable SMTP authentication
	$mail->Host = "tls://smtp.gmail.com"; // Amazon SES server, note "tls://" protocol
	$mail->Port = 465; // set the SMTP port
	$mail->Username = "canopus.testing"; //"canopus.testing";  // SMTP  username
	$mail->Password = "canopus121"; //"canopus123";  // SMTP password
	$mail->FromName = $from;
	$mail->SetFrom($from, $from_email);
	$mail->AddReplyTo($from, 'From Name');
	//$mail->AddEmbeddedImage('https://www.mail-signatures.com/articles/wp-content/themes/emailsignatures/images/twitter-35x35.gif', 'pic-twitter', 'twitter.jpg ');
	$mail->Subject = $subject;
	$mail->MsgHTML($body);
	$address = $to;
	$mail->AddAddress($address, $to);

	$res = $mail->Send();

	// var_dump($res);
	return true;
}


function Send_Mail_dynamic($smtparray = array(), $to, $company_data, $body, $subject, $secondary_email = '', $file = [])
{
	if (GLOBAL_EMAIL_ON != 'true') {
		return array('status' => false, 'message' => 'GLOBAL MAIL SETTINGS TURNED OFF');
	}

	require_once 'class.phpmailer.php';
	$from_name = $company_data["name"];
	$from_email = "no-reply@spraye.io";
	$reply_name = $company_data["name"];
	$reply_email = $company_data["email"];


	//$from_email = 'spraye@contentexecutive.com';
	//$to = 'blance@blayzer.com';
	//$reply_email     = 'spraye@contentexecutive.com';


	$mail = new PHPMailer();
	$mail->IsSMTP(true); // use SMTP
	$mail->IsHTML(true);
	//$mail->SMTPDebug = 2; //Alternative to above constant
	$mail->SMTPAuth = true; // enable SMTP authentication

	if (isset($smtparray['smtp_host']) && $smtparray['smtp_host'] != '' && $smtparray['smtp_username'] != 'no-reply@spraye.io') {
		//die(print_r($smtparray));
		//$to = 'blance@blayzer.com';

		$from_email = $company_data["email"];

		if (strstr($smtparray['smtp_host'], 'tls://'))
			$mail->SMTPSecure = 'tls';

		$mail->Host = str_replace('tls://', '', $smtparray['smtp_host']); // Amazon SES server, note "tls://" protocol
		$mail->Port = $smtparray['smtp_port']; // set the SMTP port
		$mail->Username = $smtparray['smtp_username']; //"canopus.testing";  // SMTP  username
		$mail->Password = $smtparray['smtp_password']; //"canopus123";  // SMTP password



		//$mail->Host       = "tls://email-smtp.us-east-2.amazonaws.com"; // Amazon SES server, note "tls://" protocol
		//$mail->Port       = 465;                    // set the SMTP port
		//$mail->Username   = "AKIA6CNNS7BW4V6LYBHB";//"canopus.testing";  // SMTP  username
		//$mail->Password   = "BCtKUE8mJxXV+IY1C6Pm6AozI5kUFbnHbuDl1t0m18+/";//"canopus123";  // SMTP password
		$mail->FromName = $from_name;
		$mail->SetFrom($from_email, $from_name);
		$mail->ClearReplyTos();
		$mail->AddReplyTo($reply_email, $reply_name);
		//$mail->AddEmbeddedImage('https://www.mail-signatures.com/articles/wp-content/themes/emailsignatures/images/twitter-35x35.gif', 'pic-twitter', 'twitter.jpg ');
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		if ($file) {
			$mail->AddStringAttachment($file['file'], $file['file_name'], $file['encoding'], $file['type']);
		}
		$address = $to;
		$mail->AddAddress($address, $to);
		if ($secondary_email != "") {
			$secondary_email_list = explode(',', $secondary_email);
			foreach ($secondary_email_list as $secondary_email_ele) {
				$mail->AddCC($secondary_email_ele, $secondary_email_ele);
			}
		}
		$res = $mail->Send();


		if ($res) {
			$errorLog = fopen($_SERVER['DOCUMENT_ROOT'] . '/logemail_' . date("m-d-Y") . '.csv', 'a');
			fwrite($errorLog, $from_email . "," . $to . "," . $subject . "," . @$smtparray['smtp_host'] . "," . date("m-d-Y H:i:s") . ",Passed\n");
			fclose($errorLog);
			return array('status' => true, 'message' => 'Email send succefully');
		} else {

			$errorLog = fopen($_SERVER['DOCUMENT_ROOT'] . '/logemail_' . date("m-d-Y") . '.csv', 'a');
			fwrite($errorLog, $from_email . "," . $to . "," . $subject . "," . @$smtparray['smtp_host'] . "," . date("m-d-Y H:i:s") . "," . $mail->ErrorInfo . "\n");

			fclose($errorLog);
			return array('status' => false, 'message' => $mail->ErrorInfo);
		}


	} else {

		/*$mail->Host       = "tls://email-smtp.us-east-2.amazonaws.com"; // Amazon SES server, note "tls://" protocol
		$mail->Port       = 465;                    // set the SMTP port
		$mail->Username   = "AKIA6CNNS7BW4V6LYBHB";//"canopus.testing";  // SMTP  username
		$mail->Password   = "BCtKUE8mJxXV+IY1C6Pm6AozI5kUFbnHbuDl1t0m18+/";//"canopus123";  // SMTP password
		*/


		if (isset($company_data["email"])) {
			$reply_email = $company_data["email"];

		}
		//$body = "<strong>I'm HTML!</strong>";
		if ($file) {
			$url = "https://api.sendgrid.com/v3/mail/send";
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$headers = array(
				//"authorization: Bearer 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", old key from before hack
				"authorization: Bearer " . SEND_G,
				"Content-Type: application/json",
			);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			$data = [
				'personalizations' => [
					0 => [
						'to' => [
							0 => [
								'email' => $to,
							],
						],
					],
				],
				'from' => [
					'email' => $from_email,
					'name' => $from_name,
				],
				'reply_to' => [
					'email' => $reply_email,
					'name' => $reply_name,
				],
				'subject' => $subject,
				'content' => [
					0 => [
						'type' => 'text/html',
						'value' => $body,
					],
				],
				'attachments' => [
					0 => [
						'content' => $file['file'],
						'type' => 'text/plain',
						'filename' => $file['file_name'],
					],
				],
			];

			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			$res = curl_exec($curl);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
			if ($httpcode == 202) {
				return array('status' => true, 'message' => 'Email send succefully');
			} else {
				return array('status' => false, 'message' => print_r($res));
			}
		} else {
			//die('{"personalizations":[{"to":[{"email":"'.$to.'","name":"'.$to.'"}],"subject":"'.$subject.'"}],"content": [{"type": "text/html", "value": "'.$body.'"}],"from":{"email":"'.trim($from_email).'","name":"'.$from_name.'"},"reply_to":{"email":"'.$reply_email .'","name":"'.$reply_name .'"}}');
			//die('from email '.$from_email. ' after');
			$url = 'https://api.sendgrid.com/';
			$request = $url . 'api/mail.send.json';
			// Generate curl request
			$session = curl_init($request);
			// Tell PHP not to use SSLv3 (instead opting for TLS)
			curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
			//curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . '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')); old key
			curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . SEND_G));
			// Tell curl to use HTTP POST
			curl_setopt($session, CURLOPT_POST, true);
			// Tell curl that this is the body of the POST
			//curl_setopt ($session, CURLOPT_POSTFIELDS, '{"personalizations":[{"to":[{"email":"'.$to.'","name":"'.$to.'"}],"subject":"'.$subject.'"}],"content": [{"type": "text/html", "value": "'.$body.'"}],"from":{"email":"'.trim($from_email).'","name":"'.$from_name.'"},"reply_to":{"email":"'.$reply_email .'","name":"'.$reply_name .'"}}');
			curl_setopt($session, CURLOPT_POSTFIELDS, 'to=' . $to . '&toname=' . $to . '&subject=' . $subject . '&html=' . urlencode($body) . '&from=' . $from_email . '&fromname=' . $from_name . '&replyto=' . $reply_email . '&replytoname=' . $reply_name);
			// Tell curl not to return headers, but do return the response
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

			// obtain response
			$res = curl_exec($session);
			curl_close($session);
			// print everything out
			//die(print_r($secondary_email));
			if ($secondary_email != "") {
				$secondary_email_list = explode(',', $secondary_email);
				foreach ($secondary_email_list as $secondary_email_ele) {
					$session = curl_init($request);
					// Tell PHP not to use SSLv3 (instead opting for TLS)
					curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
					//curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . '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')); old key
					curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . SEND_G));
					// Tell curl to use HTTP POST
					curl_setopt($session, CURLOPT_POST, true);
					// Tell curl that this is the body of the POST
					//curl_setopt ($session, CURLOPT_POSTFIELDS, '{"personalizations":[{"to":[{"email":"'.$to.'","name":"'.$to.'"}],"subject":"'.$subject.'"}],"content": [{"type": "text/html", "value": "'.$body.'"}],"from":{"email":"'.trim($from_email).'","name":"'.$from_name.'"},"reply_to":{"email":"'.$reply_email .'","name":"'.$reply_name .'"}}');
					curl_setopt($session, CURLOPT_POSTFIELDS, 'to=' . $secondary_email_ele . '&toname=' . $secondary_email_ele . '&subject=' . $subject . '&html=' . urlencode($body) . '&from=' . $from_email . '&fromname=' . $from_name . '&replyto=' . $reply_email . '&replytoname=' . $reply_name);
					// Tell curl not to return headers, but do return the response
					curl_setopt($session, CURLOPT_HEADER, false);
					curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

					// obtain response
					$res = curl_exec($session);
					curl_close($session);
				}
			}
			$res_obj = json_decode($res);
			if ($res_obj->message == "error") {
				return array('status' => false, 'message' => print_r($res));
			} else if ($res_obj->message == "success") {
				return array('status' => true, 'message' => 'Email send succefully');
			}
		}
		// 

		//$res = array();

		//die($_SERVER['DOCUMENT_ROOT']);



	}
}

?>