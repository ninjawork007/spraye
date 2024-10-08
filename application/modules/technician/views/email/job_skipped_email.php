 <!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */
      img {
        border: none;
       display: block;
      margin-left: auto;
      margin-right: auto;
      width: 50%;
         
        }

      body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 18px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 18px;
          vertical-align: top; }

      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

      .body {
        background-color: #f6f6f6;
        width: 100%; }

      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        Margin: 0 auto !important;
        /* makes it centered */
        max-width: 580px;
        padding: 10px;
        width: 580px; }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
        max-width: 580px;
        padding: 10px; }

      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #ffffff;
        border-radius: 3px;
        width: 100%; }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; }

      .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
      }

      .footer {
        clear: both;
        Margin-top: 10px;
        text-align: center;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 14px;
          text-align: center; }

      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        margin-bottom: 30px; }

      h1 {
        font-size: 35px;
        font-weight: normal;
        text-align: center;
        text-transform: capitalize; }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 18px;
        font-weight: normal;
        margin: 0;
        margin-bottom: 15px; }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; }

      a {
        color: #3498db;
        text-decoration: underline; }

      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          text-align: center; }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; }

      .btn-primary table td {
        background-color: #3498db; }

      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; }

      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0; }

      .first {
        margin-top: 0; }

      .align-center {
        text-align: center; }

      .align-right {
        text-align: right; }

      .align-left {
        text-align: left; }

      .clear {
        clear: both; }

      .mt0 {
        margin-top: 0; }

      .mb0 {
        margin-bottom: 0; }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; }

      .powered-by a {
        text-decoration: none; }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }

        .im {
         color: black !important;
}

      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; }
        table[class=body] .content {
          padding: 0 !important; }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; }
        table[class=body] .btn table {
          width: 100% !important; }
        table[class=body] .btn a {
          width: 100% !important; }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; }}

      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; }
        .btn-primary table td:hover {
          background-color: #34495e !important; }
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; } }

    </style>
    <style type="text/css">
      .button {
        background-color: #f44336;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
      }
    </style>
	</head>
	<body class="">
		<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
			<tr>
				<td>&nbsp;</td>
				<td class="container">
					<div class="content"><!-- START CENTERED WHITE CONTAINER -->
						<div class="header"><!-- START HEADER -->
							<table role="presentation" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td class="content-block"><img src="<?= CLOUDFRONT_URL.'uploads/company_logo/'.$company_details->company_logo ?>"></td>
								</tr>
							</table>
						</div><!-- END HEADER -->
						<table role="presentation" class="main"><!-- START MAIN CONTENT AREA -->
							<tr>
								<td class="wrapper">
									<table role="presentation" border="0" cellpadding="0" cellspacing="0">
										<tr>
										  <?php
											 $html = str_replace("{CUSTOMER_NAME}",$contactData['first_name'].' '.$contactData['last_name'],$company_email_details->job_sheduled_skipped);

											 $html3  = str_replace("{SERVICE_NAME}",'<b>Service Name</b> : '.$job_name.'<br>',$html);

											 $html4  = str_replace("{PROPERTY_ADDRESS}",'<b>Property Address</b> : '.$property_address.'<br>',$html3);

											 $html5  = str_replace("{RESCHEDULE_REASON}",'<b>Reschedule Reason</b> : '.$reschedule_reason.'<br>',$html4);

                                             $html6  = str_replace("{PROPERTY_NAME}",'<b>Property Name</b>: '.$property_details->property_title.'<br>',$html5);
                                             $html7  = str_replace("{SERVICE_DESCRIPTION}",'<b>Service Description</b>: '.$job_details->job_description.'<br>',$html6);
                                             $html8  = str_replace("{SERVICE_NOTES}",'<b>Service Notes</b>: '.$job_details->job_notes.'<br>',$html7);

                                          echo $html8;

											?>
										</tr>
										<tr class="content-block align-center">
											<span style="text-align: center!important">
                                                <?php if(isset($contactData['group_billing']) && $contactData['group_billing'] == 1){?>
                                                <a href="'.base_url('welcome/unsubscribePropertyEmail/').$contactData['contact_id'].'" target="_blank" >Unsubscribe</a>
                                                <?php }else{ ?>
                                                <a href="'.base_url('welcome/unSubscibeEmail/').$contactData['contact_id'].'" target="_blank" >Unsubscribe</a>
                                                <?php } ?>
                                            </span>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<div class="footer"><!-- START FOOTER -->
							<table role="presentation" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td class="content-block"><span class="apple-link"> &copy; <?= $company_details->company_name ?> <?= date("Y")  ?> . All rights reserved.</span></td>
								</tr>
							</table>
						</div><!-- END FOOTER -->
					</div><!-- END CENTERED WHITE CONTAINER -->
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</body>
</html>
