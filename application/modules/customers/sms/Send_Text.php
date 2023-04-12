<?php
function Send_Text_dynamic($to,$body,$subject){
	log_message('info', '/*****************************************************************/');
		
			log_message('info', 'Send_Text_dynamic func');

		log_message('info', '/*****************************************************************/');	
	
	// old test text message code
		/*$TWILIO_ACCOUNT_SID = 'AC1a2f6cde1d32a846df3c035a574a627d';
		$TWILIO_AUTH_TOKEN = '02dcafa38846afbeb34dce008be76c42';

		$payload = [
		    'From' => '+18642074430',
		    'To' => '+15738803894',
			'MessagingServiceSid' => 'MG10b9d28bad0d802c2406cfe0999e6b19',
		    'body' => 'This is the body of the text message...'
		];

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/" . $TWILIO_ACCOUNT_SID . "/Messages.json");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERPWD, $TWILIO_ACCOUNT_SID . ':' . $TWILIO_AUTH_TOKEN);
		curl_setopt($ch, CURLOPT_POSTFIELDS, urlencode(http_build_query($payload)));

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		    log_message('error', print_r(curl_error($ch)));
		}
		curl_close($ch);*/
	
		/************************************************************************\
									THIS IS WORKING
									
				--the only hanging issue is that I don't know what to do,
					if anything, with the subject that is being passed
					in (i.e Job Completion, Service Scheduled)
		\************************************************************************/
		
		$SID = "AC1a2f6cde1d32a846df3c035a574a627d";
		$TOKEN = "02dcafa38846afbeb34dce008be76c42";
		$url = "https://api.twilio.com/2010-04-01/Accounts/$SID/SMS/Messages";
		$from = "+18642074430";
		$data = array (
			'From' => $from,
			'To' => $to,
			'Body' => $body,
		);
		$post = http_build_query($data);
		$x = curl_init($url );
		curl_setopt($x, CURLOPT_POST, true);
		curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($x, CURLOPT_USERPWD, "$SID:$TOKEN");
		curl_setopt($x, CURLOPT_POSTFIELDS, $post);
		$y = curl_exec($x);
		curl_close($x);
	
	return $y;
	
}

?>