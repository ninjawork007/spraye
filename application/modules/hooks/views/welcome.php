<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Welcome to [Product Name], {{name}}!</title>
        <!-- 
        The style block is collapsed on page load to save you some scrolling.
        Postmark automatically inlines all CSS properties for maximum email client 
        compatibility. You can just update styles here, and Postmark does the rest.
        -->
        <style type="text/css" rel="stylesheet" media="all">
            /* Base ------------------------------ */

            *:not(br):not(tr):not(html) {
                font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                box-sizing: border-box;
            }

            body {
                width: 100% !important;
                height: 100%;
                margin: 0;
                line-height: 1.4;
                background-color: #F2F4F6;
                color: #74787E;
                -webkit-text-size-adjust: none;
            }

            p,
            ul,
            ol,
            blockquote {
                line-height: 1.4;
                text-align: left;
            }

            a {
                color: #3869D4;
            }

            a img {
                border: none;
            }

            td {
                word-break: break-word;
            }
            /* Layout ------------------------------ */

            .email-wrapper {
                width: 100%;
                margin: 0;
                padding: 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                background-color: #F2F4F6;
            }

            .email-content {
                width: 100%;
                margin: 0;
                padding: 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
            }
            /* Masthead ----------------------- */

            .email-masthead {
                /*padding: 25px 0;*/
                text-align: center;
            }

            .email-masthead_logo {
                width: 94px;
            }

            .email-masthead_name {
                font-size: 16px;
                font-weight: bold;
                color: #bbbfc3;
                text-decoration: none;
                text-shadow: 0 1px 0 white;
            }
            /* Body ------------------------------ */

            .email-body {
                width: 100%;
                margin: 0;
                padding: 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                border-top: 1px solid #EDEFF2;
                border-bottom: 1px solid #EDEFF2;
                background-color: #FFFFFF;
            }

            .email-body_inner {
                width: 570px;
                margin: 0 auto;
                padding: 0;
                -premailer-width: 570px;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                background-color: #FFFFFF;
            }

            .email-footer {
                width: 570px;
                margin: 0 auto;
                padding: 0;
                -premailer-width: 570px;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                text-align: center;
            }

            .email-footer p {
                color: #AEAEAE;
            }

            .body-action {
                width: 100%;
                margin: 30px auto;
                padding: 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                text-align: center;
            }

            .body-sub {
                margin-top: 25px;
                padding-top: 25px;
                border-top: 1px solid #EDEFF2;
            }

            .content-cell {
                padding: 35px;
            }

            .preheader {
                display: none !important;
                visibility: hidden;
                mso-hide: all;
                font-size: 1px;
                line-height: 1px;
                max-height: 0;
                max-width: 0;
                opacity: 0;
                overflow: hidden;
            }
            /* Attribute list ------------------------------ */

            .attributes {
                margin: 0 0 21px;
            }

            .attributes_content {
                background-color: #EDEFF2;
                padding: 16px;
            }

            .attributes_item {
                padding: 0;
            }
            /* Related Items ------------------------------ */

            .related {
                width: 100%;
                margin: 0;
                padding: 25px 0 0 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
            }

            .related_item {
                padding: 10px 0;
                color: #74787E;
                font-size: 15px;
                line-height: 18px;
            }

            .related_item-title {
                display: block;
                margin: .5em 0 0;
            }

            .related_item-thumb {
                display: block;
                padding-bottom: 10px;
            }

            .related_heading {
                border-top: 1px solid #EDEFF2;
                text-align: center;
                padding: 25px 0 10px;
            }
            /* Discount Code ------------------------------ */

            .discount {
                width: 100%;
                margin: 0;
                padding: 24px;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
                background-color: #EDEFF2;
                border: 2px dashed #9BA2AB;
            }

            .discount_heading {
                text-align: center;
            }

            .discount_body {
                text-align: center;
                font-size: 15px;
            }
            /* Social Icons ------------------------------ */

            .social {
                width: auto;
            }

            .social td {
                padding: 0;
                width: auto;
            }

            .social_icon {
                height: 20px;
                margin: 0 8px 10px 8px;
                padding: 0;
            }
            /* Data table ------------------------------ */

            .purchase {
                width: 100%;
                margin: 0;
                padding: 35px 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
            }

            .purchase_content {
                width: 100%;
                margin: 0;
                padding: 25px 0 0 0;
                -premailer-width: 100%;
                -premailer-cellpadding: 0;
                -premailer-cellspacing: 0;
            }

            .purchase_item {
                padding: 10px 0;
                color: #74787E;
                font-size: 15px;
                line-height: 18px;
            }

            .purchase_heading {
                padding-bottom: 8px;
                border-bottom: 1px solid #EDEFF2;
            }

            .purchase_heading p {
                margin: 0;
                color: #9BA2AB;
                font-size: 12px;
            }

            .purchase_footer {
                padding-top: 15px;
                border-top: 1px solid #EDEFF2;
            }

            .purchase_total {
                margin: 0;
                text-align: right;
                font-weight: bold;
                color: #2F3133;
            }

            .purchase_total--label {
                padding: 0 15px 0 0;
            }
            /* Utilities ------------------------------ */

            .align-right {
                text-align: right;
            }

            .align-left {
                text-align: left;
            }

            .align-center {
                text-align: center;
            }
            /*Media Queries ------------------------------ */

            @media only screen and (max-width: 600px) {
                .email-body_inner,
                .email-footer {
                    width: 100% !important;
                }
            }

            @media only screen and (max-width: 500px) {
                .button {
                    width: 100% !important;
                }
            }   
            /* Buttons ------------------------------ */

            .button {
                background-color: #3869D4;
                border-top: 10px solid #3869D4;
                border-right: 18px solid #3869D4;
                border-bottom: 10px solid #3869D4;
                border-left: 18px solid #3869D4;
                display: inline-block;
                color: #FFF;
                text-decoration: none;
                border-radius: 3px;
                box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
                -webkit-text-size-adjust: none;
            }

            .button--green {
                background-color: #22BC66;
                border-top: 10px solid #22BC66;
                border-right: 18px solid #22BC66;
                border-bottom: 10px solid #22BC66;
                border-left: 18px solid #22BC66;
            }

            .button--red {
                background-color: #FF6136;
                border-top: 10px solid #FF6136;
                border-right: 18px solid #FF6136;
                border-bottom: 10px solid #FF6136;
                border-left: 18px solid #FF6136;
            }
            /* Type ------------------------------ */

            h1 {
                margin-top: 0;
                color: #2F3133;
                font-size: 19px;
                font-weight: bold;
                text-align: left;
            }

            h2 {
                margin-top: 0;
                color: #2F3133;
                font-size: 16px;
                font-weight: bold;
                text-align: left;
            }

            h3 {
                margin-top: 0;
                color: #2F3133;
                font-size: 14px;
                font-weight: bold;
                text-align: left;
            }

            p {
                margin-top: 0;
                color: #74787E;
                font-size: 16px;
                line-height: 1.5em;
                text-align: left;
            }

            p.sub {
                font-size: 12px;
            }

            p.center {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <span class="preheader">Thanks for trying out [Product Name]. We’ve pulled together some information and resources to help you get started.</span>
        <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center">
                    <table class="email-content" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="email-masthead">
                                <center>
                                    <table>
                                        <tr>
                                            <td>
                                                <a href="#" class="email-masthead_name">
                                                    <img height="75" src=" data:image/png;base64,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"/>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" class="email-masthead_name">
                                                   HoopApp
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                            </td>
                        </tr>
                        <!-- Email Body -->
                        <tr>
                            <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                                <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0">
                                    <!-- Body content -->
                                    <tr>
                                        <td class="content-cell">
                                            <h1>Welcome, {{name}}!</h1>
                                            <p>Thanks for trying HoopApp. We’re thrilled to have you on board.</p>
                                            <p>To get the most out of [Product Name], do this primary next step for email verification:</p>

                                            <!-- Action -->
                                            <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td align="center">
                                                        <!-- Border based button
                                                   https://litmus.com/blog/a-guide-to-bulletproof-buttons-in-email-design -->
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td align="center">
                                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                                        <tr>
                                                                            <td>
                                                                                <a href="{{action_url}}" class="button button--" target="_blank">Do this Next</a>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <p>For reference, here's your login information:</p>
                                            <table class="attributes" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td class="attributes_content">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                            <!--                              <tr>
                                                            <td class="attributes_item"><strong>Login Page:</strong> {{login_url}}</td>
                                                          </tr>-->
                                                            <tr>
                                                                <td class="attributes_item"><strong>Username:</strong> {{username}}</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <p>This verification will be expire in 24 hours.</p>
                      <!--                      <table class="attributes" width="100%" cellpadding="0" cellspacing="0">
                                              <tr>
                                                <td class="attributes_content">
                                                  <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                      <td class="attributes_item"><strong>Trial Start Date:</strong> {{trial_start_date}}</td>
                                                    </tr>
                                                    <tr>
                                                      <td class="attributes_item"><strong>Trial End Date:</strong> {{trial_end_date}}</td>
                                                    </tr>
                                                  </table>
                                                </td>
                                              </tr>
                                            </table>-->
                                            <p>If you have any questions, feel free to <a href="mailto:{{support_email}}">email our customer success team</a>. (We're lightning quick at replying.) We also offer <a href="{{live_chat_url}}">live chat</a> during business hours.</p>
                                             <p>Thanks,
                                                <br>The HoopApp Team Team</p>
                                            <p><strong>P.S.</strong> Need immediate help getting started? Check out our <a href="{{help_url}}">help documentation</a>. Or, just reply to this email, the [Product Name] support team is always ready to help!</p>
                                            <!-- Sub copy -->
                                            <table class="body-sub">
                                                <tr>
                                                    <td>
                                                        <p class="sub">If you’re having trouble with the button above, copy and paste the URL below into your web browser.</p>
                                                        <p class="sub">{{action_url}}</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="content-cell" align="center">
                                            <p class="sub align-center">&copy; <?= Date('Y') ?> HoopApp Limited. All rights reserved.</p>
                                            <p class="sub align-center">
                                                <a href="www.hoopapp.co.uk">www.hoopapp.co.uk</a>
                                                <div id="navcontainer">
                                                    <ul>

                                                        <li><a href="#"><img height="35" width="35" alt="Instagram" src="data:image/png;base64,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"></a></li> 
                                                        <li><a href="#"><img height="35" width="35" alt="Twitter" src="data:image/png;base64,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"></a></li> 
                                                        <li><a href="#"><img height="35" width="35" alt="Facebook" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAADcElEQVRoge2YW0hUQRjH5y1w98zZFp/c3ZkpJYTKjIgerDQyL8czo2xpZCiWmIJlit0kTMSyKFIiMzJveUkiCIqwC0kYlEFRFFGhZCndwHqIWn3JpodY87SunnP21sP5w7zu9/vN+b6ZYQEwYsSIESP/W0z2lBgBs3KIWQPErEHArNxkl5aGmmvOiCgtERI2YFmQzmdakNAHJiSvDzWnZ2CyFWK52xu4hwhmXcCSYAk1NgAAADEyYwXEbEQtvHuJhL4Ni0hdHlJ4M05zCoSOa4V3L4HQccEhpYcGHtFCkbBfeuGnfYlJAdP8oMJDREt9BfecC7k4KPCiQy5SA2SNzOBlBxt5372n/MnzIT7w6CW/ffcxj1lT4L2lAv0lzEjepLZt2ntu8ZmSknlg9nYK1EyEOVJWQsIm1MCvlctmhOec8835NbO3EmEuk02K9S89TLZCREfV9vPhk90K6Hejn3lzZy/vunyHJzr3qRhsNuzXewIS1qNlIJs7exUCe6uadAw17fALvAlJG7QWb+2+qRDYX31e18lkttN4nwVEwh6qLXjoaDt//3GMu1wTCgGXa4KPff3GP3z6oklGIKzft923ycu07NiZlqteh9edippmTV9BiEiK1i0gYLpHS7GzrdfmFCgordM2C4iW6BaAmDX4WyB9a6U2AULrgyZgjczg4VFOj0ssp+gYD49y8vAop57TqE63gIBZuZ7To+2iUiB7R63m3/grIO/ULWB2pC3RU7S164bfBObZpEW6BQAAAGL5vtaiTReuKwS2FBzRBS9i1ucTPAAAmG1p67QWbmxRDnPW9tnfP96WgFLjfBYAAACIaYeWwqfOXVEIbMyr1rH7tNUv8AAAMH9hpigSNqy2+InTlxQCztwqbfCIDQHrKug3AQD+3MqQMJcagH9foyxb/dkPCfsRsP+QBCRTkdDJuSAqa9sUAqlZFep2ntCfEMtSQOCnJIi8bS6QvOLj/NmLN/zV4Ah/PTjKV0u71e0+knMDCu8OxHKxnlNltmVGtDAo8O4ImOaraSdVbYNoTlDhpyQcUrrawZ65Zeh3aJdSQgLvjskmxWo5YqcdlYNmlLw4pPBTsSRYtFx2IqbNIDxOCDW2R8x2Gi8Q1u8dnPX57XkQyAgRSdEQ0RJIaD0ktB4iusvnV6URI0aMGAlEfgPNx5BqBjD3VwAAAABJRU5ErkJggg=="></a></li> 
                                                    </ul> 
                                                </div>       
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>