 <!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

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
  <body class="" style="">

  <div>
  <?php 
   if (!empty($invoice->company_logo)) { ?>
        <img style="width:25%" src="<?= CLOUDFRONT_URL.'uploads/company_logo/'.$invoice->company_logo ?>">
    <br>
  <?php }?>

                        <h1><?= $invoice->company_name ?></h1>

                        <?php
                           
// 
                         $invoice_pay_btn ='<a href="'.base_url('Welcome/cardConnectPayment/').base64_encode($invoice->invoice_id).'" target="_blank" ><button class="button">Pay Now</button></a>';

                         $invoice_pdf_btn ='<a href="'.base_url('admin/invoices/pdfInvoice/').$invoice->invoice_id.'" target="_blank" ><button class="button">View Invoice</button></a>';
                         
                         $html = str_replace("{CUSTOMER_NAME}",$invoice->customer_name,$invoice->late_fee_email);

                         $html = str_replace("{INVOICE_ID}",$invoice->invoice_id,$html);

                         $html = str_replace("{INVOICE_PDF_LINK}",$invoice_pdf_btn,$html);

                         $html = str_replace("{INVOICE_PAY_LINK}", $invoice_pay_btn,$html);

                         echo $html;  

                        ?>
                
</div>                    

  </body>
</html>
