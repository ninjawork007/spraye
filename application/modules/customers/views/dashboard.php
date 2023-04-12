<!-- Core JS files --> 
<!-- <script 
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>-->
    
    <!-- Add tokenizer.js file to head -->
    <script language="javascript" src="<?= BASYS_URL ?>tokenizer/tokenizer.js"></script>
   
        <!-- <script src="<?= base_url() ?>/assets/popup/js/sweetalert2.all.js"></script> -->

    <!-- /theme JS files -->
<style type="text/css">
    .myspan {
        width: 55px;
    }
    .label-warning, .bg-warning {
        background-color :#A9A9A9;
        border-color: #A9A9A9;
    }
    .label-warning-2 {
        background-color :#FFC53B;
        border-color: #FFC53B;
        color: black;
        text-transform: none;
    }
    .checkbox label, .radio label {
        padding-top : 0px  !important;
    }

    .checkbox-inline  .checker {
        top: 0px !important;
    }
    .adcustomerpropertydiv {
        float: left;
    }
    .addcustomeridinmodal {
        display: none;
    }
    .btn-group {
        margin-left: 4px !important;
    }

    .fa-file-pdf-o::before {
        font-family: fontAwesome;
        padding-right: 8px;
    }
    .table-spraye  table#editcustmerpropertytbl {
        border: 1px solid #6eb1fd;
        border-radius: 4px;
    }
    .label-till , .bg-till  {
        background-color: #36c9c9;
        background-color: #36c9c9;
        border-color: #36c9c9;
    }
    .nav-tabs.nav-tabs-solid>.active>a,.nav-tabs.nav-tabs-solid>.active>a:focus,.nav-tabs.nav-tabs-solid>.active>a:hover{
        background-color:<?=$setting_details->invoice_color?>;
        border-color:<?=$setting_details->invoice_color?>;
        color:#fff
    }
    .navbar-inverse{
        border-bottom: 2px solid <?=$setting_details->invoice_color?>;
    }
    .navbar-brand {
        padding: 10px;
    }
    /* .portal_btn{
        background:<?=$setting_details->invoice_color?>;
 
    } */

    .modal_head{
        background:<?=$setting_details->invoice_color?>;
        border-color:<?=$setting_details->invoice_color?>;
    }
    a { 
        color: inherit; 
    } 

    #loading {
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        position: fixed;
        display: none;
        opacity: 0.7;
        background-color: #fff;
        z-index: 9999;
        text-align: center;
    }

    #loading-image {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 10%;
        z-index: 100;
    }
	
    .dash-tbl table#unassignedServices, 
    .dash-tbl table#outstandingInvoices,
    .dash-tbl table#assignedPrograms,
    .dash-tbl table#scheduledServices,
    .dash-tbl table#notes
	{
        border: 1px solid #6eb1fd !important;
        border-radius: 4px !important;
    }
    #customerName {
        color: #01669a;
    }
	.tabbable {
        padding: 30px 0;
    }

    button#go_to_property{
        border-radius: 3px;
    }

    .btn-paid-status{
        background-color: #4CAF50;
        color: white;
    }
    .btn-paid-status:hover, .btn-paid-status:active, .btn-paid-status:focus{
        color: white;
    }
	
    @media(max-width:1024px){
	    div.go-to-customer div {
		    margin-top:0px!important;
	    } 
	    button#go_to_property{
		    padding: 9px 17px;
	    }
        .portal_btn{
            display:block ;
            margin: 5px 0;
            width: 100%;
        }
        .pay-buttons{
            margin-top: 0px!important;
        }
    }	
    @media (max-width: 768px){
        .table-responsive {
            min-height: auto;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .contact-us{
            background: none!important;
            border-color: none!important;
            text-decoration: underline;
        }

    }

    @media (max-width: 1280px){
        .portal_btn{
            display:block ;
            margin: 5px 0;
            width: 100%;
        }

    }

    .label-refunded, .bg-refunded {
        background-color : #fd7e14;
        border-color : #fd7e14;
    }

</style>

<!-- Content area --> 
<div class="content">

    <!-- Form horizontal -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <div class="form-group">
                    <div class="row">
                        <div  class="col-md-12">
                            <div class="btndiv col-md-6 ">
                                <a href="#highlighted-justified-tab1" data-toggle="tab">

                                    <button
                                        type="button"
                      
                                        class="btn btn-primary portal_btn" 
                                        id="updateInfo"   
                                    >
                                        <i class=" fa fa-address-book" aria-hidden="true"></i>
                                        Update Information
                                    </button>
                                </a>
                                <script>
                                    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                                        var target = this.href.split('#');
                                        $('.nav a').filter('[href="#'+target[1]+'"]').tab('show');
                                    })
                    
                                </script>
                                <?php
                                if($setting_details->is_text_message == 1){ ?>
                  
                                    <button 
                                        type="button"  
                                        class="btn btn-primary portal_btn"  
                                        data-target="#text_msg_alert" 
                                        data-toggle="modal"
                                    > 
                                        <i class=" fa fa-mobile" aria-hidden="true"></i>
                                        Manage Text Messages
                                    </button>
                                <?php } ?>
  
                                <button type="button" class="btn btn-primary portal_btn" id="updatePayment"
                                    data-target="<?php if ($cardconnect_details && $cardconnect_details->status == 1) :?> #clover_update_payment <?php elseif ($basys_details && $basys_details->status == 1): ?> #modal_update_payment <?php endif; ?>"
                                    data-toggle="modal"
                                    <?php if(!$basys_details && !$cardconnect_details){ ?> disabled
                                    <?php } else if($customerData['clover_autocharge'] != 1 && $customerData['basys_autocharge'] != 1){?>
                                    disabled <?php } ?>
                                > 
                                    <i class=" fa fa-credit-card-alt" aria-hidden="true"></i> Update Payment Method
                                </button>
                                <button 
                                    type="button"  
                                    class="btn btn-primary portal_btn" 
                                    id="generate_statement_btn" 
                                    data-target="#modal_generate_statement" 
                                    data-toggle="modal"
                                > 
                                    <i class=" fa fa-history" aria-hidden="true"></i> View History 
                                </button>
                  
                            </div>
                            <div class="btndiv col-md-3 ">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6 pay-buttons ">
                                        <h5 class="card-text" style="color:black; "><b>Balance Due:</b></h5>
                                        <p class="card-text" style="color:black;">$ <?= number_format($total_due,2, '.', '') ?> </p>
                    
                                        <?php
                                        $due_in_array = [];
                                        foreach($due_invoices as $due_inv) {
                                            array_push($due_in_array, $due_inv);
                                        }
                                        $o_string = implode(",", $due_in_array);
                            
                                        ?>
                                        <?php
                                        if(isset($due_invoices) && !empty($due_invoices)){
                                        ?>
                                            <?php if($cardconnect_details){?>
                                                <a href="<?= base_url('customers/Welcome/paymentTotal/').base64_encode($o_string)  ?>" target="_blank">
                                                    <button type="button"  class="btn  btn-paid-status due_payment portal_btn" id="payCloverBalance" 

                              
                                                        <?php if(!$cardconnect_details){ ?> disabled <?php } else if(isset($customerData['clover_autocharge']) && $customerData['clover_autocharge'] != 1){?>  <?php } ?> >
                              
                                                        Pay  Balance
                                                    </button>
                                                </a>

                                            <?php } else if ($basys_details){ ?>
                                                <button type="button"  class="btn btn-paid-status due_payment portal_btn" id="payBasysBalance" 
                                                    data-target="#modal_due" 
                                                    data-toggle="modal"
                                                    href="<?= base_url('customers/Welcome/paymentTotal/').base64_encode($o_string)  ?>"
                                                    <?php if(!$basys_details){ ?> disabled <?php } else if(isset($customerData['basys_autocharge']) && $customerData['basys_autocharge'] != 1){?>  <?php } ?> 
                                                >
                              
                                                    Pay  Balance
                                                </button>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6 col-xs-6 pay-buttons">

                                        <?php
                                        if (isset($past_due_invoices) && !empty($past_due_invoices)){
                                        ?>
                                            <h5 class="card-text" style="color:red;"><b>Past Due:</b></h5>
                                            <p class="card-text" style="color:red;" >$ <?= number_format($total_past_due,2, '.', '') ?>  </p>
                                            <?php
                                            if (isset($past_due_invoices) && !empty($past_due_invoices)){
                                                $pass_in_array = [];
                                                foreach($past_due_invoices as $past) {
                                                    array_push($pass_in_array, $past);
                                                }
                                                $p_string = implode(",", $pass_in_array);
                                                if($cardconnect_details){?>
                                                    <a href="<?= base_url('customers/Welcome/paymentPastDue/').base64_encode($p_string)  ?>" target="_blank">
                                                        <button type="button"  class="btn btn-danger past_due_payment portal_btn" id="cloverPastDue"
                          
                                                            <?php if(!$cardconnect_details){ ?> disabled <?php } else if (isset($customerData['clover_autocharge']) && $customerData['clover_autocharge'] != 1){?>  <?php } ?> 
                                                        >
                          
                                                            Pay Past Due
                                                        </button>
                                                    </a>

                                                <?php } else if($basys_details){ ?>
                                                    <button type="button"  class="btn btn-danger past_due_payment portal_btn" id="basysPastDue" 
                                                        data-target="#modal_past" 
                                                        data-toggle="modal"
                                                        href="<?= base_url('customers/Welcome/paymentPastDue/').base64_encode($p_string)  ?>"
                          
                                                        <?php if(!$basys_details){ ?> disabled <?php } else if(isset($customerData['basys_autocharge']) && $customerData['basys_autocharge'] != 1){?>  <?php } ?> 
                                                    >
                          
                                                        Pay Past Due
                                                    </button>
                                                <?php } ?> 
                                            <?php } ?> 
                                   <?php } ?> 
                            
                       
                                </div>
                            </div>
                        </div>
              
                        <div class="form-group col-md-3 col-xs-12 go-to-customer" style="float:right; display:inline-block;">
                  
                            <div class="col-md-10 col-xs-10" style="min-width:200px;">
                                <select class="bootstrap-select form-control" data-live-search="true" name="go_to_property"       id="go_to_account">
                                    <?php if (!empty($all_customers)){
                                        foreach ($all_customers as $key => $value){ ?>
                                            <option value="<?php echo $value->customer_id; ?> " <?php echo ($customerData['customer_id'] == $value->customer_id) ? 'selected' : ''; ?>><?php echo $value->first_name.' '.$value->last_name.' - '.$value->customer_company_name ?></option>
                          
                                        <?php 
                                        }
                                    } ?>
                        

                                </select>
                            </div>
                            <div class="col-md-2 col-xs-2">
                                <button class="btn btn-primary" onclick="goToAccount()" id="go_to_account_btn">Go</button>
                            </div>
                        </div>
                    </div>
                </div>
            </h5> 
        </div>   

      
        <div id="loading" > 
        <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
        </div>
    
        <br>
        <!-- START OF Quickview -->      
        <div class="panel-body">
        <div id="customerName">
          <h2><?php echo "<b>".$customerData['first_name']." ".$customerData['last_name'] ."</b>&nbsp;#". $customerData['customer_id']?></h2>
        </div>
            <?php if($cardconnect_details && $cardconnect_details->status != 1){ ?>
                <span class="label label-warning-2"><i class=" icon-info22 tooltip-icon"  style="color: black;"></i>&nbsp;Auto Pay is not set. Click the Billing Info tab to sign up for Auto Pay &nbsp;&nbsp;
                    <a href="#highlighted-justified-tab1"  data-toggle="tab" style="color: #2D67CB">Click Here</a></span>
            <?php }?>
            <?php if($customerData['customer_status'] == 2 && $company_notifications['is_hold_notification']==1){ ?>
			<center class="bg-danger"><h1><?php echo $company_notifications['hold_notification'];?></h1></center>
		<?php }?>
        <div class="tabbable">
          <ul class="nav nav-tabs nav-tabs-solid nav-justified">
            <li class="liquick <?php echo $active_nav_link == '0' ? 'active' : ''  ?> "><a href="#highlighted-justified-tab0" data-toggle="tab">Quickview</a></li>
            <li class="lione <?php echo $active_nav_link == '1' ? 'active' : ''  ?> "><a href="#highlighted-justified-tab1" data-toggle="tab">Billing Info</a></li>
            <li class="litwo <?php echo $active_nav_link == '2' ? 'active' : ''  ?>"><a href="#highlighted-justified-tab2" data-toggle="tab">My Services</a></li>
            <li class="lithree <?php echo $active_nav_link == '3' ? 'active' : ''  ?>"><a href="#highlighted-justified-tab3" data-toggle="tab">Invoices</a></li>
            <li class="lifour <?php echo $active_nav_link == '4' ? 'active' : ''  ?>"><a href="#highlighted-justified-tab4" data-toggle="tab">My Properties</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane <?php echo $active_nav_link=='0' ? 'active' : ''  ?>" id="highlighted-justified-tab0">
              <div class="row">
                <div class="col-md-4 col-sm-12">  
                  <span class="text-semibold" style="">Outstanding Invoices</span>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="table-responsive table-spraye dash-tbl">
                        <table class="table dataTable" id="outstandingInvoices" role="grid">
                          <thead>
                            <tr role="row">
                              <th>Invoice #</th>
                              <th>Service Name</th>
                              <th>Amount Due</th>
                              <th>Invoice Date</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php	if(isset($outstanding)){
                            $count = 0;
                            foreach($outstanding as $k=>$inv){ 
                            if ($count < 8) { ?>
                              <tr role="row">
                                <td><?php echo '<a href="'.base_url('customers/Invoices/printInvoice/').$inv['invoice_id'].'" target="_blank">'.$inv['invoice_id'].'</a>'?></td>
                                <td><?= $inv['service_name'] ?></td>
                                <td><?= '$' . number_format($inv['amount_due'], 2, '.', ',') ?></td>
                                <td><?= $inv['due_date'] ?></td>
                                <td>
                                  <?php 
                                  switch ($inv['status']) {
                                    case "0":
                                        echo 'Unpaid';
                                        break;
                                    case "1":
                                      echo 'Partial';
                                      break;
                                    case "2":
                                      echo 'Paid';
                                      break;
                                    case "3":
                                      echo 'Past Due';
                                      break;
                                    case "4":
                                      echo 'Refunded';
                                      break;
                                    default:
                                      break;
                                  } 
                                  ?>
                                </td>
                              </tr>
                            <?php $count++; } } }?>
                            
                          </tbody>
                          <tfoot>
                            <tr role="row">
                              <th colspan="5" style="text-align: center; border-top:1px solid #6eb1fd;"><?php echo '<a href="#highlighted-justified-tab3" data-toggle="tab" onclick="openTab(\'lithree\')">View All</a>'?></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                        
                <div class="col-md-4 col-sm-12">  
                  <span class="text-semibold" style="">Scheduled Services</span>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="table-responsive table-spraye dash-tbl">
                        <table class="table dataTable" id="scheduledServices" role="grid">
                          <thead>
                            <tr role="row">
                              <th>Property Address</th>
                              <th>Service Name</th>
                              <th>Date Scheduled</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php	if(isset($scheduled)){
                            $count = 0;
                            foreach($scheduled as $k=>$service){ 
                            if ($count < 8) {?>
                              <tr role="row">
                                <td><?php echo $service->property_address?></td>
                                <!-- <td><?php echo '<a href="'.base_url('customers/editProperty/').$service->property_id.'" target="_blank">'.$service->property_title.'</a>'?></td> -->
                                <td><?php echo $service->job_name?></td>
                                <!-- <td><?php echo '<a href="'.base_url('customers/job/editJob/').$service->job_id.'" target="_blank">'.$service->job_name.'</a>'?></td> -->
                                <td><?= $service->job_assign_date ?></td>
                              </tr>
                            <?php $count++; } } }?>
                          </tbody>
                          <tfoot>
                            <tr role="row">
                              <th colspan="3" style="text-align: center; border-top:1px solid #6eb1fd;"><?php echo '<a href="#highlighted-justified-tab2" data-toggle="tab" onclick="openTab(\'litwo\')">View All</a>'?></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                        
                <div class="col-md-4 col-sm-12">  
                  <span class="text-semibold" style="">Upcoming Services</span>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="table-responsive table-spraye dash-tbl">
                        <table class="table dataTable" id="unassignedServices" role="grid">
                          <thead>
                            <tr role="row">
                              <th>Property Address</th>
                              <th>Service Name</th>
                              <th>Program Name</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php	if(isset($unscheduled)){
                              $count = 0;
                              foreach($unscheduled as $k => $uService){ 
                              if ($count < 8) {?>
                              <tr role="row">
                                <!-- <td><?php echo '<a href="'.base_url('customers/editProperty/').$uService->property_id.'" target="_blank">'.$uService->property_title.'</a>'?></td> -->
                                <td><?= $uService->property_address ?></td>
                                <!-- <td><?php echo '<a href="'.base_url('customers/job/editJob/').$uService->job_id.'" target="_blank">'.$uService->job_name.'</a>'?></td> -->
                                <td><?= $uService->job_name ?></td>
                                <td><?= $uService->program_name ?></td>
                              </tr>
                              <?php $count++; } } }?>
                              
                          </tbody>
                          <tfoot>
                            <tr role="row">
                              <th colspan="3" style="text-align: center; border-top:1px solid #6eb1fd;"><?php echo '<a href="#highlighted-justified-tab2" data-toggle="tab" onclick="openTab(\'litwo\')">View All</a>'?></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 col-sm-12">  
                  <span class="text-semibold" style="">My Programs</span>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="table-responsive table-spraye dash-tbl">
                        <table class="table dataTable" id="assignedPrograms" role="grid">
                          <thead>
                            <tr role="row">
                              <th>Property Address</th>
                              <th>Program Name</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php	if(isset($prop_programs)){
                              $count = 0;
                                foreach($prop_programs as $k=>$program){ 
                                if ($count < 8) {?>
                                
                                  <tr role="row">
                                    <!-- <td><?php echo '<a href="'.base_url('customers/editProperty/').$program['property_id'].'" target="_blank">'.$program['property_title'].'</a>'?></td> -->
                                    <td><?= $program['property_address'] ?></td>
                                    <td><?= $program['program_name'] ?></td>
                                  </tr>
                            <?php $count++; } } } ?>
                            
                          </tbody>
                          <tfoot>
                            <tr role="row">
                              <th colspan="3" style="text-align: center; border-top:1px solid #6eb1fd;"><?php echo '<a href="#highlighted-justified-tab4" data-toggle="tab" onclick="openTab(\'lifour\')">View All</a>'?></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-8 col-sm-12" style="display:none;">  
                  <span class="text-semibold">Notes</span>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="table-responsive table-spraye dash-tbl">
                        <table class="table dataTable" id="notes" role="grid">
                          <thead>
                            <tr role="row">
                              <th>Date</th>
                              <th>Property Name</th>
                              <th>Notes</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr role="row">
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- END OF Quickview --> 	
            <!-- START Billing Info TAB HERE -->
              <div class="tab-pane <?php echo $active_nav_link=='1' ? 'active' : ''  ?>" id="highlighted-justified-tab1">
                
                <form class="form-horizontal" action="<?= base_url('customers/updateCustomer') ?>" method="post" name="addcustomer" enctype="multipart/form-data" >
                  <fieldset class="content-group">

                      <input type="hidden" name="customer_id" id="customer_id" class="form-control" value="<?= $customerData['customer_id'];?>" >
                      
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">First Name</label>
                          <div class="col-lg-9">
                            <input type="text" class="form-control" name="first_name" value="<?php echo set_value('first_name')?set_value('first_name'):$customerData['first_name']?>" placeholder="FName">
                            <span style="color:red;"><?php echo form_error('first_name'); ?></span>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">Last Name</label>
                          <div class="col-lg-9">
                            <input type="text" class="form-control" name="last_name" value="<?php echo set_value('last_name')?set_value('last_name'):$customerData['last_name']?>" placeholder="Last Name">
                            <span style="color:red;"><?php echo form_error('last_name'); ?></span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">Company Name</label>
                          <div class="col-lg-9">
                            <input type="text" class="form-control"  value="<?= $customerData['customer_company_name'] ?>" placeholder="Company Name" name="customer_company_name" >
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">Email</label>
                          <div class="col-lg-9">

                          <div class="row">
                              <div class="col-md-6" >
                                <input type="text" class="form-control" name="email" value="<?php echo set_value('email')?set_value('email'):$customerData['email']?>" placeholder="Email">
                                <span style="color:red;"><?php echo form_error('email'); ?></span>
                                
                              </div>

                               <div class="col-md-6">                                        
                                <div id="CustomerSubscribeTooltip" class="checkbox" data-popup="tooltip-custom"

                                    title="Turning this option off will cause you to not receive any pre-service or post-service notifications when schedule services at your property. However, you will still receive invoices by email."

                                    data-placement="top">
                                  <label class="checkbox-inline checkbox-right" >
                                    <input id="CustomerSubscribeButton" type="checkbox" name="is_email" class="switchery-is-email" <?php echo $customerData['is_email']==1 ? 'checked' : '' ?>  >
                                    Subscribe
                                  </label>
                                </div>
                              </div> 
                            
                            </div>
                          </div>
                        </div>
                      </div>  
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                          <label class="control-label col-lg-3 col-sm-12 col-xs-12">Secondary Email(s)</label>
                          <div class="multi-select-full col-lg-8  col-sm-10 col-xs-10 pl-15">
                            <textarea cols="60" disabled="disabled"  id="secondary_email_list"><?php echo $customerData['secondary_email'] ?></textarea>
                            <input type="hidden" id="secondary_email_list_hid" name="secondary_email_list_hid" value="<?php echo $customerData['secondary_email'] ?>"  />                  
                          </div>
                          <div class="col-lg-1 col-sm-2 col-xs-2 addbuttonmanage">
                            <?php
                            $hide_reset_link = "";
                            $add_link_padding = "";
                            if($customerData['secondary_email'] == "") {
                            $hide_reset_link = "class='hidden'";
                            $add_link_padding = " pt-5";
                            }
                            ?>
                            <div class="form-group mb-5">
                            <center>
                              <a href="#" id="add_secondary_email_link" data-toggle="modal" data-target="#modal_add_secondary_emails"><i class="icon-add text-success<?php echo $add_link_padding ?>"
                                style="font-size:25px;"></i></a>
                            </center>
                            </div>
                            <div class="form-group ">
                            <center>
                              <a <?php echo $hide_reset_link ?> id="reset_secondary_email_link" href="#"><i class="icon-reset text-success pt-6"
                                style="font-size:25px;"></i></a>
                            </center>
                            </div>
                          </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                          <label class="control-label col-lg-3">Mobile</label>
                          <div class="col-lg-9">
                        <div class="row">
                          <div class="col-md-6" >
                            <input type="text" class="form-control" name="phone" value="<?= $customerData['phone']==0 ? '' : $customerData['phone'] ?>" placeholder="Mobile">
                            <span style="color:red;"><?php echo form_error('phone'); ?></span>
                                  
                            <span>Please do not use dashes</span>
                          </div>
                          <?php
                            if($setting_details->is_text_message == 1){
                            ?>
                          <div class="col-md-6">                                        
                            <div class="checkbox">
                            <!-- <label class="togglebutton" style="font-size:13px">Off</label> -->
                              <input name="is_mobile_text" id="mobile_text" type="checkbox" class="switchery-is-mobile-text"
                                <?php echo $customerData['is_mobile_text'] == 1 ? 'checked' : '';  ?> 
                                                                  
                                >
                              <label class="togglebutton" style="font-size:13px">Sign up for text alerts</label>
                        
                            </div>
                            </div>
                            <?php } ?>

                          </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">Home</label>
                          <div class="col-lg-9">
                            <input type="text" class="form-control" name="home_phone" value="<?= $customerData['home_phone']==0 ? '' : $customerData['home_phone'] ?>" placeholder="Home">
                                <span style="color:red;"><?php echo form_error('home_phone'); ?></span>
                                
                                <span>Please do not use dashes</span>

                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">Work</label>
                          <div class="col-lg-9">
                            <input type="text" class="form-control" name="work_phone" value="<?= $customerData['work_phone']==0 ? '' : $customerData['work_phone'] ?>" placeholder="Work">
                                <span style="color:red;"><?php echo form_error('work_phone'); ?></span>
                                
                                <span>Please do not use dashes</span>

                          </div>
                        </div>
                      </div>
                    </div>    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">Billing Address</label>
                          <div class="col-lg-9">
                            <input type="text" id="autocomplete"   class="form-control" name="billing_street" value="<?php echo set_value('billing_street')?set_value('billing_street'):$customerData['billing_street']?>"  placeholder="Address">
                            <span style="color:red;"><?php echo form_error('billing_street'); ?></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">Billing Address 2</label>
                          <div class="col-lg-9">
                            <input type="text" class="form-control" id="billing_street_2" name="billing_street_2" value="<?php echo set_value('billing_street_2')?set_value('billing_street_2'):$customerData['billing_street_2']?>" placeholder="Address 2">
                            <span style="color:red;"><?php echo form_error('billing_street_2'); ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">City</label>
                          <div class="col-lg-9">
                            <input type="text" class="form-control" id="locality" name="billing_city" value="<?php echo set_value('billing_city')?set_value('billing_city'):$customerData['billing_city']?>" placeholder="City">
                            <span style="color:red;"><?php echo form_error('billing_city'); ?></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">Billing State / Territory</label>
                          <div class="col-lg-9">
                            
                            <select class="form-control" name="billing_state" id="region" >

                              <option value="">Select State</option>
                              <optgroup label="Canadian Provinces">
                                <option value="AB" <?php  if($customerData['billing_state']=='AB') { echo "selected"; } ?> >Alberta</option>
                                <option value="BC" <?php  if($customerData['billing_state']=='BC') { echo "selected"; } ?> >British Columbia</option>
                                <option value="MB" <?php  if($customerData['billing_state']=='MB') { echo "selected"; } ?> >Manitoba</option>
                                <option value="NB" <?php  if($customerData['billing_state']=='NB') { echo "selected"; } ?> >New Brunswick</option>
                                <option value="NF" <?php  if($customerData['billing_state']=='NF') { echo "selected"; } ?> >Newfoundland</option>
                                <option value="NT" <?php  if($customerData['billing_state']=='NT') { echo "selected"; } ?> >Northwest Territories</option>
                                <option value="NS" <?php  if($customerData['billing_state']=='NS') { echo "selected"; } ?> >Nova Scotia</option>
                                <option value="NU" <?php  if($customerData['billing_state']=='NU') { echo "selected"; } ?> >Nunavut</option>
                                <option value="ON" <?php  if($customerData['billing_state']=='ON') { echo "selected"; } ?> >Ontario</option>
                                <option value="PE" <?php  if($customerData['billing_state']=='PE') { echo "selected"; } ?> >Prince Edward Island</option>
                                <option value="QC" <?php  if($customerData['billing_state']=='QC') { echo "selected"; } ?> >Quebec</option>
                                <option value="SK" <?php  if($customerData['billing_state']=='SK') { echo "selected"; } ?> >Saskatchewan</option>
                                <option value="YT" <?php  if($customerData['billing_state']=='YT') { echo "selected"; } ?> >Yukon Territory</option>
                              </optgroup>
                              <optgroup label="U.S. States/Territories">
                                <option value="AL" <?php  if($customerData['billing_state']=='AL') { echo "selected"; } ?> >Alabama</option>
                                <option value="AK" <?php  if($customerData['billing_state']=='AK') { echo "selected"; } ?> >Alaska</option>
                                <option value="AZ" <?php  if($customerData['billing_state']=='AZ') { echo "selected"; } ?> >Arizona</option>
                                <option value="AR" <?php  if($customerData['billing_state']=='AR') { echo "selected"; } ?> >Arkansas</option>
                                <option value="CA" <?php  if($customerData['billing_state']=='CA') { echo "selected"; } ?> >California</option>
                                <option value="CO" <?php  if($customerData['billing_state']=='CO') { echo "selected"; } ?> >Colorado</option>
                                <option value="CT" <?php  if($customerData['billing_state']=='CT') { echo "selected"; } ?> >Connecticut</option>
                                <option value="DE" <?php  if($customerData['billing_state']=='DE') { echo "selected"; } ?> >Delaware</option>
                                <option value="DC" <?php  if($customerData['billing_state']=='DC') { echo "selected"; } ?> >District Of Columbia</option>
                                <option value="FL" <?php  if($customerData['billing_state']=='FL') { echo "selected"; } ?> >Florida</option>
                                <option value="GA" <?php  if($customerData['billing_state']=='GA') { echo "selected"; } ?> >Georgia</option>
                                <option value="HI" <?php  if($customerData['billing_state']=='HI') { echo "selected"; } ?> >Hawaii</option>
                                <option value="ID" <?php  if($customerData['billing_state']=='ID') { echo "selected"; } ?> >Idaho</option>
                                <option value="IL" <?php  if($customerData['billing_state']=='IL') { echo "selected"; } ?> >Illinois</option>
                                <option value="IN" <?php  if($customerData['billing_state']=='IN') { echo "selected"; } ?> >Indiana</option>
                                <option value="IA" <?php  if($customerData['billing_state']=='IA') { echo "selected"; } ?> >Iowa</option>
                                <option value="KS" <?php  if($customerData['billing_state']=='KS') { echo "selected"; } ?> >Kansas</option>
                                <option value="KY" <?php  if($customerData['billing_state']=='KY') { echo "selected"; } ?> >Kentucky</option>
                                <option value="LA" <?php  if($customerData['billing_state']=='LA') { echo "selected"; } ?> >Louisiana</option>
                                <option value="ME" <?php  if($customerData['billing_state']=='ME') { echo "selected"; } ?> >Maine</option>
                                <option value="MD" <?php  if($customerData['billing_state']=='MD') { echo "selected"; } ?> >Maryland</option>
                                <option value="MA" <?php  if($customerData['billing_state']=='MA') { echo "selected"; } ?> >Massachusetts</option>
                                <option value="MI" <?php  if($customerData['billing_state']=='MI') { echo "selected"; } ?> >Michigan</option>
                                <option value="MN" <?php  if($customerData['billing_state']=='MN') { echo "selected"; } ?> >Minnesota</option>
                                <option value="MS" <?php  if($customerData['billing_state']=='MS') { echo "selected"; } ?> >Mississippi</option>
                                <option value="MO" <?php  if($customerData['billing_state']=='MO') { echo "selected"; } ?> >Missouri</option>
                                <option value="MT" <?php  if($customerData['billing_state']=='MT') { echo "selected"; } ?> >Montana</option>
                                <option value="NE" <?php  if($customerData['billing_state']=='KS') { echo "selected"; } ?> >Nebraska</option>
                                <option value="NV" <?php  if($customerData['billing_state']=='NV') { echo "selected"; } ?> >Nevada</option>
                                <option value="NH" <?php  if($customerData['billing_state']=='NH') { echo "selected"; } ?> >New Hampshire</option>
                                <option value="NJ" <?php  if($customerData['billing_state']=='NJ') { echo "selected"; } ?> >New Jersey</option>
                                <option value="NM" <?php  if($customerData['billing_state']=='NM') { echo "selected"; } ?> >New Mexico</option>
                                <option value="NY" <?php  if($customerData['billing_state']=='NY') { echo "selected"; } ?> >New York</option>
                                <option value="NC" <?php  if($customerData['billing_state']=='NC') { echo "selected"; } ?> >North Carolina</option>
                                <option value="ND" <?php  if($customerData['billing_state']=='ND') { echo "selected"; } ?> >North Dakota</option>
                                <option value="OH" <?php  if($customerData['billing_state']=='OH') { echo "selected"; } ?> >Ohio</option>
                                <option value="OK" <?php  if($customerData['billing_state']=='KS' || $customerData['billing_state']=='OK') { echo "selected"; } ?> >Oklahoma</option>
                                <option value="OR" <?php  if($customerData['billing_state']=='OR') { echo "selected"; } ?> >Oregon</option>
                                <option value="PA" <?php  if($customerData['billing_state']=='PA') { echo "selected"; } ?> >Pennsylvania</option>
                                <option value="RI" <?php  if($customerData['billing_state']=='RI') { echo "selected"; } ?> >Rhode Island</option>
                                <option value="SC" <?php  if($customerData['billing_state']=='SC') { echo "selected"; } ?> >South Carolina</option>
                                <option value="SD" <?php  if($customerData['billing_state']=='SD') { echo "selected"; } ?> >South Dakota</option>
                                <option value="TN" <?php  if($customerData['billing_state']=='TN') { echo "selected"; } ?> >Tennessee</option>
                                <option value="TX" <?php  if($customerData['billing_state']=='TX') { echo "selected"; } ?> >Texas</option>
                                <option value="UT" <?php  if($customerData['billing_state']=='UT') { echo "selected"; } ?> >Utah</option>
                                <option value="VT" <?php  if($customerData['billing_state']=='VT') { echo "selected"; } ?> >Vermont</option>
                                <option value="VA" <?php  if($customerData['billing_state']=='VA') { echo "selected"; } ?> >Virginia</option>
                                <option value="WA" <?php  if($customerData['billing_state']=='WA') { echo "selected"; } ?> >Washington</option>
                                <option value="WV" <?php  if($customerData['billing_state']=='WV') { echo "selected"; } ?> >West Virginia</option>
                                <option value="WI" <?php  if($customerData['billing_state']=='WI') { echo "selected"; } ?> >Wisconsin</option>
                                <option value="WY" <?php  if($customerData['billing_state']=='WY') { echo "selected"; } ?> >Wyoming</option>
                              </optgroup>
                            </select>
                            <span style="color:red;"><?php echo form_error('billing_state'); ?></span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">Postal Code</label>
                          <div class="col-lg-9">
                            <input type="text" class="form-control" id="postal-code" name="billing_zipcode" value="<?php echo set_value('billing_zipcode')?set_value('billing_zipcode'):$customerData['billing_zipcode']?>" placeholder="Postal Code">
                            <span style="color:red;"><?php echo form_error('billing_zipcode'); ?></span>
                          </div>
                        </div>
                      </div>
                      

                    </div> 
                      <?php
                      if(($cardconnect_details && $cardconnect_details->status == 1) || ($basys_details && $basys_details->status == 1)){
                        // die(print_r($cardconnect_details));
                        ?>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="control-label col-lg-3">Sign up for Auto Pay
                              <label class="togglebutton" style="font-size:13px">Do you want us to run your credit card automatically upon job completion? <span data-popup="tooltip-custom"
                              title="When this option is on, your credit card on file will be charged when a service is completed. Your invoice will then automatically be marked as paid." data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></label></label>
                              <div class="col-lg-4">
                                <label class="togglebutton" style="font-size:13px">Off</label>
                                <?php if($cardconnect_details && $cardconnect_details->status != 0){ ?>
                                <input name="clover_autocharge" type="checkbox" class="switchery-autocharge" id="switchery-autocharge"
                                    <?php echo $customerData['clover_autocharge'] == 1 ? 'checked' : '';  ?>>
                                <?php } else if ($basys_details && $basys_details->status != 0){ ?>
                                <input name="basys_autocharge" type="checkbox" class="switchery-autocharge" id="switchery-autocharge"
                                    <?php echo $customerData['basys_autocharge'] == 1 ? 'checked' : '';  ?>>
                                <?php } else { ?>
                                <input name="autocharge" type="checkbox" class="switchery-autocharge" id="switchery-autocharge"
                                    disabled>
                                <?php } ?>
                                <label class="togglebutton" style="font-size:13px">On</label>

                                <input type="hidden" name="clover_status"
                                    value="<?php echo $cardconnect_details && $cardconnect_details->status == 1 ? 1 : 0 ?>">
                                <input type="hidden" name="basys_status"
                                    value="<?php echo $basys_details && $basys_details->status == 1 ? 1 : 0 ?>">
                                <?php
                                if ($cardconnect_details && $cardconnect_details->status != 0){ ?>
                                <input type="hidden" name="customer_clover_token"
                                    value="<?php echo isset($customerData['customer_clover_token']) ? $customerData['customer_clover_token'] : '' ?>">
                                <input type="hidden" name="clover_acct_id"
                                    value="<?php echo isset($customerData['clover_acct_id']) ? $customerData['clover_acct_id'] : '' ?>">
                                <?php }
                                else if ($basys_details && $basys_details->status != 0){ ?>
                                <input type="hidden" name="basys_customer_id"
                                    value="<?php echo isset($customerData['basys_customer_id']) ? $customerData['basys_customer_id'] : '' ?>">
                                <?php } ?>
                      
              
                              </div>
                            </div>
                          </div> 
                        </div>
                      <?php  
                      }
                      ?>    
                    

                  </fieldset>

                  <div class="text-right">
                    <button type="submit" class="btn btn-success">Submit Changes <i class="icon-arrow-right14 position-right"></i></button>
                  </div>
                </form>
              </div>
            <!-- ENd Billing Info TAB HERE -->
            <!-- START SERVICES TAB -->
              <div class="tab-pane <?php echo $active_nav_link == '2' ? 'active' : ''  ?>" id="highlighted-justified-tab2">
                <div id="modal_apply_discount_services" class="modal fade">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Apply Coupon</h6>
                      </div>
                      <form action="" method="post" id="apply_discount_form">
                        <div class="modal-body">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-sm-12">
                                <div style="color: red;" id="apply_discount_form_errors">
                                </div>

                                <label>Select Coupon</label>
                                <select name="coupon_id" class="form-control">
                                  <option value=''>Select a Coupon</option>
                                  <?php
                                      foreach($customer_one_time_discounts as $discount) {
                  
                                        // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                                        $expiration_pass = true;
                                        if ($discount->expiration_date != "0000-00-00 00:00:00") {
                                            $coupon_expiration_date = strtotime( $discount->expiration_date );
          
                                            $now = time();
                                            if($coupon_expiration_date < $now) {
                                                $expiration_pass = false;
                                                $expiration_pass_global = false;
                                            }
                                        }
          
                                        if ($expiration_pass == true) {
                                            $disc_coupon_id = $discount->coupon_id;
                                            $disc_name = $discount->code;
                                            echo "<option value='$disc_coupon_id'>$disc_name</option>";
                                        }
                                      }
                                  ?>
                                  <option value='REMOVE-ALL'>Remove coupons from selected services</option>
                                </select>

                                <input type="hidden" name="job_data" id="coupon_apply_id_csv" value="">

                              </div>
                            </div>
                          </div>
                          <div class="modal-footer" style="padding: 0;">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" id="savearea" class="btn btn-success">Apply Coupon to Selected Services</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <div  class="table-responsive table-spraye">
                  <table  class="table datatable-basic">   
                    <!-- <button type="button" class="btn btn-primary" data-toggle="modal"       data-target="#modal_apply_discount_services" onclick="applyDiscount()">Apply Coupon
                    </button> -->
                      <script>
                        $("#apply_discount_form").submit(function(){
                          $("#loading").css("display", "block");
                            $.ajax({
                                    url: "<?= base_url('customers/setting/applyCouponData') ?>", 
                                    data: $("#apply_discount_form").serialize(),
                                    type: "POST",
                                    dataType: 'json',
                                    success: function (e) {

                                        console.log(e);

                                    $("#loading").css("display", "none");
                                    if (e != 0 && e != 1) {
                                        document.querySelector('#apply_discount_form_errors').innerHTML = e;
                                      } else {
                                        $("#modal_apply_discount_services").css("display", "none");
                                        $('.modal-backdrop').css("display", "none");

                                        // getCouponList();

                                        if ($("#apply_discount_form").serialize().includes('REMOVE-ALL')) {
                                            swal(
                                              'Coupon',
                                              'Removed Successfully ',
                                              'success'
                                            )
                                          } else {
                                            swal(
                                              'Coupon',
                                              'Added Successfully ',
                                              'success'
                                            )
                                          }

                                            location.reload();

                                          }
                                    },
                                      error: function (e) {
                                        $("#loading").css("display", "none");
                                        alert("Something went wrong");
                                      }
                            });
                                  return false;
                        });
                        // get all checked services, and insert data into form
                        function applyDiscount() {
                          all_service_data = [];
                          $('#customer_services_tbody input:checked').each(function() {
                              all_service_data.push($(this).val());
                          });
                          document.querySelector('#coupon_apply_id_csv').value = JSON.stringify(all_service_data);
                        }
                      </script>
                      <thead>  
                            <tr>
                                <!-- <th><input type="checkbox" id="select_all" /></th> -->
                                <th>Technician Name</th>
                                <th>Service Name</th>
                                <!-- <th>Assign Date</th> -->
                                <th>Address</th>
                                <th>Cost</th>
                                <!-- <th>Service Area</th> -->
                                <th>Program</th>
                                <th>Status</th>
                                <!-- <th>Coupons</th> -->
                            </tr>  
                      </thead>
                      <tbody id="customer_services_tbody">
                          <?php                  
                                if (!empty($all_services)) {
                                
                                      foreach ($all_services as $value) {
                                  ?>
                                          <tr>
                                              <td><?= $value->user_first_name.' '.$value->user_last_name; ?></td>
                                              <td><?=$value->job_name; ?></td>
                                             
                                              <td><?= $value->property_address ?></td>
                                              <td>$<?=  $cost = number_format($value->job_cost, 2); ?></td>
                                              <td><?=$value->program_name ?></td>
                                              <td>
                                              <?php 
                                                switch ($value->is_job_mode) {
                                                  case 0:
                                                  echo 'Pending';
                                                    break;
                                                  
                                                  case 1:
                                                    echo "Completed";
                                                  break;

                                                  case 2:
                                                    echo "Rescheduled";
                                                  break;

                                                  default:
                                                  echo "Default";
                                                  break;
                    
                                                }

                                                ?>
                                                </td>
                                                <!-- <td><?= $value->coupon_code_csv ?></td> -->

                                              
                                          </tr>
                                        <?php } } ?>
                      </tbody>
                  </table>
                </div>
              </div>
            <!-- END SERVICES TAB -->
            <!-- START Invoice Tab -->  
              <div class="tab-pane <?php echo $active_nav_link == '3' ? 'active' : ''  ?>" id="highlighted-justified-tab3">
                <div  class="table-responsive table-spraye ">
                  <table  class="table datatable-basic"  id="DataTables_Table_0">    
                    <thead>  
                      <tr>
                        <th>Invoice</th>
                        <th>Amount</th>
                        <th>Amt. Due</th>
                        <th>Payment Status</th>
                        <th>Property Address</th>
                        <th>Invoice Date</th>
                        <th>Sent Date</th>
                        <th>Opened Date</th>
                        <th>Payment Date</th>
                        <th>Refund Date</th>
                        <th>Action</th>                        
                      </tr>  
                    </thead>
                    <tbody>
                      <?php if (!empty($invoice_details)) { 
                        foreach ($invoice_details as $value) { 
                          $total_amount = $value->total_cost_actual;
                          $partial_payments = $value->partial_payment;
                          // $due_amount = $total_amount - $value->partial_payment;
                        //  $due_amount = $due_amount < 0 ? 0 : $due_amount;
                          ?>      
                          <tr>
                            <td><?= $value->invoice_id; ?></td>
                            <td><?php
                            // echo $value->invoice_id;
                            if (isset($value->total_cost_actual)) {
                              echo '$ '.number_format($value->total_cost_actual,2, '.', '');
                            } else {
                              echo '$ '.number_format($value->cost,2, '.', '');
                            }
                            // echo $value->total_cost_actual;
                            ?></td>
                            
                            <td>
                              <?php
                              if (isset($value->amount_due) ) {
                            echo '$ '.number_format($value->amount_due,2, '.', '');
                            
                            // if (isset($value->total_cost_actual) && isset($value->partial_payment)) {
                            //   $total_amount = $value->total_cost_actual;
                            //   $partial_payments = $value->partial_payment;
                            //   $due_amount = $total_amount - $value->partial_payment;
                            //   $due_amount = $due_amount < 0 ? 0 : $due_amount;
                            //   echo $due_amount;
                            // } else {
                            //   echo '$ '.$value->total_cost_actual;
                            // }
                            // echo $value->total_cost_actual;
                              } else {
                                // echo '$ '.number_format($value->total_cost_actual,2, '.', '');
                              //  die(print_r($value));
                              //  die(print_r($value->total_cost_actual));
                              }
                            ?>
                            </td>
                            <td>
                              <?php switch ($value->payment_status) {
                              case 0:
                                echo '<span  class="label label-warning myspan">Unpaid</span>';
                                
                                break;
                              case 1:
                                echo '<span  class="label label-till myspan">Partial</span>';
                                
                                break;
                              
                              case 2:
                                echo '<span  class="label label-success myspan">Paid</span>';
                                
                                break;
                              case 3:
                                echo '<span  class="label label-danger myspan">Past Due</span>';
                                
                                break;
                              case 4:
                                echo '<span  class="label label-refunded myspan">Refunded</span>';
                                
                                break;
                              default:
                                break;
                              } ?>
                              
                            </td>
                          
                            <td><?= $value->property_address ?></td>
                            <td><?= date('m-d-Y', strtotime($value->invoice_date)) ?></td>
                            <td><?php if(isset($value->sent_date) && $value->sent_date !== '0000-00-00 00:00:00'){
                                    echo date('m-d-Y', strtotime($value->sent_date));
                                } else {
                                    echo '';
                                } ?></td>
                                <td><?php if(isset($value->opened_date) && $value->opened_date !== '0000-00-00 00:00:00'){
                                    echo date('m-d-Y', strtotime($value->opened_date));
                                } else {
                                    echo '';
                                } ?></td>
                                <td><?php if(isset($value->payment_created) && $value->payment_created !== '0000-00-00 00:00:00'){ echo date('m-d-Y', strtotime($value->payment_created)); } else {
                                    echo '';
                                }  ?></td>
                                <td><?php if(isset($value->refund_datetime) && $value->refund_datetime !== '0000-00-00 00:00:00'){ echo date('m-d-Y', strtotime($value->refund_datetime)); } else {
                                    echo '';
                                }  ?></td>
                            <td>
                              <ul style="list-style-type: none; padding-left: 0px;">
                                <li style="display: inline; padding-right: 10px;">
                                    <a href="<?= base_url('admin/invoices/printInvoice/').$value->invoice_id ?>" target="_blank" class=" button-next"><i class=" icon-file-pdf position-center" style="color: #9a9797;"></i></a>
                                </li>
                                <li style="display: inline; padding-right: 10px;">
                                  <a href="<?= base_url('admin/invoices/printInvoice/').$value->invoice_id ?>" target="_blank" class=" button-next"><i class="icon-printer2 position-center" style="color: #9a9797;"></i></a>
                                </li>                             
                                <li style="display: inline; padding-right: 10px;">
                                    <a href="<?= base_url('admin/invoices/printInvoice/').$value->invoice_id ?>" target="_blank" class=" button-next"><i class="bi-credit-card position-center" style="color: #9a9797;"></i></a>
                                </li>
                                <li style="display: inline; padding-right: 10px;">
                                <a href="<?= base_url('customers/Welcome/payment/').base64_encode($value->invoice_id)  ?>" target="_blank">
                                <button type="button"  class="btn btn-danger partial_payment"  
                                
                                <?php if($value->payment_status == 2 || $value->payment_status == 4){ ?> 
                                  disabled 
                                  <?php }  ?> >Pay Now</button>
                                </a>
                                </li>                             
                              </ul>
                            </td>
                            
                          </tr>                     
                      <?php  }  } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            <!-- END Invoice Tab -->                                      
            <!-- START PROPERTY Tab -->
              <div class="tab-pane <?php echo $active_nav_link == '4' ? 'active' : ''  ?> " id="highlighted-justified-tab4">
                <div  class="table-responsive table-spraye">
                  <table  class="table" id="editcustmerpropertytbl"  >    
                    <thead>  
                        <tr> 
                          <th>Property Title</th>
                          <th>Address</th>      
                        </tr>  
                    </thead>
                    <tbody>
                      <?php 

                      if (!empty($propertylist)) { foreach ($propertylist as $value) { 
                      
                        if(in_array($value->property_id, $selectedpropertylist )) {

                        echo '<tr>

                                <!-- <td> <a href="'.base_url("customers/editProperty/").$value->property_id.'"> '.$value->property_title.'</a>  </td> -->

                                <td>'.$value->property_title.'</td>
                                <td>'.$value->property_address.'</td>
                              </tr>';

                        }

                        } } ?>

                  
                    </tbody>
                  </table>
                </div>
              </div>
            <!-- END PROPERTY Tab -->  
            <!-- START NOTES TAB  -->
            
            <!-- END NOTES TAB  -->
          </div>
        </div>
        </div>
    </div>
    <!-- /form horizontal -->

</div>
<!-- /content area -->

<div class="mydiv" style="display: none;">
            
</div>

<!-- text message alert modal -->
<div id="text_msg_alert" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary modal_head" >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Sign up for text alerts</h6>
            </div>

            <form name="add_text_alert" id="add_text_alert" method="POST" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="modal-body" id="text_alerts_modal">
                                    By clicking the button below, you agree to receive text messages from
                                    <strong><?=$setting_details->company_name?></strong> in order to update you about your upcoming and completed services. You may receive up to 4 messages per service scheduled. Message and data rates may apply to all messages received. You are not required to receive these messages as a condition of receiving services from <strong><?=$setting_details->company_name?></strong>. If you have any questions about these text messages, you can reach out to us at <strong><?=$setting_details->company_email?></strong>. You can opt out of these text message updates at any time by replying ‘STOP’ to any message or unchecking the option in my customer account.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="TextMessageOff()" data-dismiss="modal">Close</button>
                        <button type="submit" id="submitTextAlert" name="accept" value="1" class="btn btn-success" data-customer="<?= $customerData['customer_id']?>">I Accept</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /text message alert modal -->

<!-- Clover auto on message alert modal -->
<div id="clover_auto_on" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary modal_head" >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Auto Pay is Switched On</h6>
            </div>

            <form name="clover_on" id="clover_on" method="POST" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="modal-body" id="clover_on_modal">
                                    You have set Auto-Pay to ON. The credit card on file with Clover Connect will automatically run on job completion. To save these settings, click "Submit Changes".
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Clover auto on message alert modal -->

<!-- Basys auto on message alert modal -->
<div id="basys_auto_on" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary modal_head" >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Auto Pay is Switched On</h6>
            </div>

            <form name="basys_on" id="basys_on" method="POST" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="modal-body" id="basys_on_modal">
                                    You have set Auto-Pay to ON. The credit card on file with Basys will automatically run on job completion. To save these settings, click "Submit Changes".
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Basys auto on message alert modal -->

<!-- auto off message alert modal -->
<div id="auto_off" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary modal_head" >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Auto Pay is Switched Off</h6>
            </div>

            <form name="auto_off_modal" id="auto_off_modal" method="POST" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="modal-body" id="auto_off_text">
                                    You have set Auto-Pay to OFF. To save these settings, click "Submit Changes".
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /auto off message alert modal -->

<!-- clover create payment modal -->
<div id="clover_payment_method" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Payment Method</h6>
            </div>

            <form name="add_clover_payment" id="add_clover_payment" method="POST" enctype="multipart/form-data"
                form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-9">
                                <label>Card Number</label>
                                <input type="text" class="form-control" name="clover_card_number"
                                    placeholder="Card Number"
                                    required>
                            </div>
                            <div class="col-sm-6 col-md-3" width="50%">
                                <label>Expiration Month</label>
                                <select class="form-control" name="clover_card_exp_m"
                                    required>
                                    <option value="">Select Month</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label>Expiration Year</label>
                                <select class="form-control" name="clover_card_exp_y"
                                    required>
                                    <option value="">Select Year</option>
                                    <?php $cur_year = date('Y');
                                    for($i = 0; $i <= 10; $i++) {?>
                                    <option value="<?php echo $cur_year + $i; ?>"><?php echo $cur_year + $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label>CVV</label>
                                <input type="text" class="form-control number-only" name="clover_card_cvv"
                                    placeholder="CVV"
                                    maxlength="4" pattern="\d{4}"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="AutoPayOff()" data-dismiss="modal">Close</button>
                        <button type="submit" id="submitCloverPaymentMethod" class="btn btn-success"
                            data-customer="<?php echo $customerData['customer_id']; ?>">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /clover create payment modal -->

<!-- basys create payment modal -->
<div id="basys_payment_method" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary modal_head" >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Payment Method</h6>
            </div>

            <form name="add_basys_payment" id="add_basys_payment" method="POST" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-9">
                                <label>Card Number</label>
                                <input type="text" class="form-control" name="card_number" placeholder="Card Number" required>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label>Card Exp</label>
                                <input type="text" class="form-control" name="card_exp" placeholder="MM/YY" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="AutoPayOff()" data-dismiss="modal">Close</button>
                        <button type="submit" id="submitPaymentMethod" class="btn btn-success" data-customer="<?= $customerData['customer_id']?>">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /basys create payment modal -->

<!-- clover update payment modal -->
<div id="clover_update_payment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Update Payment Method</h6>
            </div>

            <form name="update_clover_payment" id="update_clover_payment" method="POST" enctype="multipart/form-data"
                form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-9">
                                <label>Card Number</label>
                                <input type="text" class="form-control" name="clover_card_number"
                                    placeholder="Card Number"
                                    required>
                            </div>
                            <div class="col-sm-3 col-md-3" width="50%">
                                <label>Expiration Month</label>
                                <select class="form-control" name="clover_card_exp_m"
                                    required>
                                    <option value="">Select Month</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label>Expiration Year</label>
                                <select class="form-control" name="clover_card_exp_y"
                                    required>
                                    <option value="">Select Year</option>
                                    <?php $cur_year = date('Y');
                                    for($i = 0; $i <= 10; $i++) {?>
                                    <option value="<?php echo $cur_year + $i; ?>"><?php echo $cur_year + $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label>CVV</label>
                                <input type="text" class="form-control number-only" name="clover_card_cvv"
                                    placeholder="CVV"
                                    maxlength="4" pattern="\d{4}"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="submitCloverUpdatePayment" class="btn btn-success"
                            data-customer="<?= $customerData['customer_id']?>">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /clover update payment modal -->

<!-- basys update payment modal -->
<div id="modal_update_payment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary modal_head">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Update Payment Method</h6>
            </div>

            <form name="update_basys_payment" id="update_basys_payment" method="POST" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-9">
                                <label>Card Number</label>
                                <input type="text" class="form-control" name="card_number" placeholder="Card Number" required>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label>Card Exp</label>
                                <input type="text" class="form-control" name="card_exp" placeholder="MM/YY" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="submitUpdatePayment" class="btn btn-success" data-customer="<?= $customerData['customer_id']?>">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /basys payment modal -->  

<!-- generate statement modal -->
<div id="modal_generate_statement" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content" style="height: 305px;">
            <div class="modal-header bg-primary modal_head" >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Select Dates To View History</h6>
            </div>

            <form method="POST" action="<?= base_url('customers/invoices/getOpenInvoiceByCustomer/').$customerData['customer_id'] ?>" target="_blank" formtarget="_blank" style="margin-top: 20px; padding: 0 20px;">

                <div class="col">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <!-- <a style="margin-top: 26px; margin-left: 25px;" class="btn btn-warning" target="_blank" type="submit"  ><i class=" icon-file-pdf"> </i> Generate Statement</a> -->
                        <button class="btn btn-warning" type="submit" ><i class=" icon-file-pdf"></i> Generate Statement</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- generate statement modal -->  

<!-- Secondary email modal -->
<div id="modal_add_secondary_emails" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Secondary Email</h6>
            </div>

            <form name="add_secondary_email" id="my_form" action="<?= base_url('customers/addSecondaryEmailDataJson') ?>" method="post"
                enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <label>Email</label>
                                <input type="email" class="form-control" name="secondary_email" placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="assignjob" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Secondary email modal -->

<!-- Primary modal -->
<div id="modal_add_property" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h6 class="modal-title">Add Property</h6>
        </div>

        <form  name="addproperty" id="my_form2"  action="<?= base_url('customers/addPropertyDataJson') ?>" method="post" enctype="multipart/form-data" form_ajax="ajax" >

            <div class="modal-body">
            
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">                 
                            <label>Property Name</label><br>
                            <input type="text" class="form-control" name="property_title" placeholder="Property Name">                      
                        </div>

                        <div class="col-md-4 col-sm-4">
                            <label>Same as Billing Address</label><br>
                            <center>
                                <input type="checkbox" class="form-control styled" id="autofill" >
                            </center>			
                        </div>
                    <div class="col-md-4 col-sm-4">                   
                        <label>Address</label>
                        <input type="text" class="form-control" name="property_address" id="autocomplete2" onFocus="geolocate()"  placeholder="Address" onkeydown="keydownAddress2()" >
                    </div>

                    <div id="map"></div>
                        <input type="hidden" name="property_latitude" id="latitude" />
                        <input type="hidden" name="property_longitude" id="longitude" />
                    </div>
                </div>


                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-sm-6"> 
                            <label>Address 2</label>
                            <input type="text" class="form-control" name="property_address_2" id="property_address_2" placeholder="Address 2">
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label>City</label>
                            <input type="text" class="form-control" name="property_city" id="locality2" placeholder="City">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-sm-6"> 
                            <label>State</label>
                                <select class="form-control" id="region2" name="property_state">
                                    <option value="">Select State</option>                      
                                    <option value="AL">Alabama</option>
                                    <option value="AK">Alaska</option>
                                    <option value="AZ">Arizona</option>
                                    <option value="AR">Arkansas</option>
                                    <option value="CA">California</option>
                                    <option value="CO">Colorado</option>
                                    <option value="CT">Connecticut</option>
                                    <option value="DE">Delaware</option>
                                    <option value="DC">District Of Columbia</option>
                                    <option value="FL">Florida</option>
                                    <option value="GA">Georgia</option>
                                    <option value="HI">Hawaii</option>
                                    <option value="ID">Idaho</option>
                                    <option value="IL">Illinois</option>
                                    <option value="IN">Indiana</option>
                                    <option value="IA">Iowa</option>
                                    <option value="KS">Kansas</option>
                                    <option value="KY">Kentucky</option>
                                    <option value="LA">Louisiana</option>
                                    <option value="ME">Maine</option>
                                    <option value="MD">Maryland</option>
                                    <option value="MA">Massachusetts</option>
                                    <option value="MI">Michigan</option>
                                    <option value="MN">Minnesota</option>
                                    <option value="MS">Mississippi</option>
                                    <option value="MO">Missouri</option>
                                    <option value="MT">Montana</option>
                                    <option value="NE">Nebraska</option>
                                    <option value="NV">Nevada</option>
                                    <option value="NH">New Hampshire</option>
                                    <option value="NJ">New Jersey</option>
                                    <option value="NM">New Mexico</option>
                                    <option value="NY">New York</option>
                                    <option value="NC">North Carolina</option>
                                    <option value="ND">North Dakota</option>
                                    <option value="OH">Ohio</option>
                                    <option value="OK">Oklahoma</option>
                                    <option value="OR">Oregon</option>
                                    <option value="PA">Pennsylvania</option>
                                    <option value="RI">Rhode Island</option>
                                    <option value="SC">South Carolina</option>
                                    <option value="SD">South Dakota</option>
                                    <option value="TN">Tennessee</option>
                                    <option value="TX">Texas</option>
                                    <option value="UT">Utah</option>
                                    <option value="VT">Vermont</option>
                                    <option value="VA">Virginia</option>
                                    <option value="WA">Washington</option>
                                    <option value="WV">West Virginia</option>
                                    <option value="WI">Wisconsin</option>
                                    <option value="WY">Wyoming</option>

                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label>Zip Code</label>
                                <input type="text" class="form-control" id="postal-code2" name="property_zip" placeholder="Zip Code">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 col-sm-6"> 
                                <label>Area</label>
                                <select class="form-control" name="property_area" value="<?php echo set_value('property_area')?>">
                                    <option value="">Select Area</option>
                                    <?php if (!empty($propertyarealist)) {
                
                                        foreach ($propertyarealist as $value){ ?>
                                            <option value="<?= $value->property_area_cat_id ?>"><?= $value->category_area_name ?></option>  
                                        <?php } 
                                    }?>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="col-md-12 col-sm-12">
                                    <label>Property Type</label>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <label class="radio-inline">
                                        <input name="property_type" value="Commercial" type="radio" checked="checked" />Commercial
                                    </label>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <label class="radio-inline">
                                        <input name="property_type" value="Residential" type="radio" />Residential
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label class="control-label">Property Difficulty Level</label>
                                <div class="multi-select-full">
                                    <select class="form-control" name="difficulty_level">
                                        <option value="">Select Difficulty Level</option>
                                        <option value="1">Level 1</option>
                                        <option value="2">Level 2</option>
                                        <option value="3">Level 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6" style="display:<?= $setting_details->is_sales_tax==1 ? 'block' : 'none' ?> " >
                                <label>Sales Tax Area</label>
                                <div class="multi-select-full" >
                                    <select class="multiselect-select-all-filtering form-control" name="sale_tax_area_id[]" multiple="multiple" id="sales_tax" >

                
                                        <?php if (!empty($sales_tax_details)) { 
                                            foreach ($sales_tax_details as $key => $value) {
                                            ?>    
                                                <option value="<?= $value->sale_tax_area_id ?>"  ><?= $value->tax_name  ?>  </option>
                                            <?php  } 
                                        } ?>
                
                                    </select>
                                </div>
                            </div>
            
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label>Total Yard Square Feet</label>
                                <input type="text" class="form-control" name="yard_square_feet" id="yard_square_feet" placeholder="Yard Square Feet">
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label>Total Yard Grass Type</label>
                                <select class="form-control" name="total_yard_grass" id="total_yard_grass">
                                    <option value="">Select Yard Grass Type</option>
                                    <option value="Bent">Bent</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Dichondra">Dichondra</option>
                                    <option value="Fine Fescue">Fine Fescue</option>
                                    <option value="Kentucky Bluegrass">Kentucky Bluegrass</option>
                                    <option value="Ryegrass">Ryegrass</option>
                                    <option value="St. Augustine/Floratam">St. Augustine/Floratam</option>
                                    <option value="Tall Fescue">Tall Fescue</option>
                                    <option value="Zoysia">Zoysia</option>
                                    <option value="Centipede">Centipede</option>
                                    <option value="Bluegrass/Rye/Fescue">Bluegrass/Rye/Fescue</option>
                                    <option value="Warm Season">Warm Season</option>
                                    <option value="Cool Season">Cool Season</option>
                                </select>
                            </div>
                        </div>
                    </div>
        
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 col-sm-6"> 
                                <label>Front Yard Square Feet</label>
                                <input type="text" class="form-control" name="front_yard_square_feet" id="front_yard_square_feet" placeholder="Front Yard Square Feet">
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label>Front Yard Grass Type</label>
                                <select class="form-control" name="front_yard_grass" id="front_yard_grass">
                                    <option value="">Select Front Yard Grass Type</option>
                                    <option value="Bent">Bent</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Dichondra">Dichondra</option>
                                    <option value="Fine Fescue">Fine Fescue</option>
                                    <option value="Kentucky Bluegrass">Kentucky Bluegrass</option>
                                    <option value="Ryegrass">Ryegrass</option>
                                    <option value="St. Augustine/Floratam">St. Augustine/Floratam</option>
                                    <option value="Tall Fescue">Tall Fescue</option>
                                    <option value="Zoysia">Zoysia</option>
                                    <option value="Centipede">Centipede</option>
                                    <option value="Bluegrass/Rye/Fescue">Bluegrass/Rye/Fescue</option>
                                    <option value="Warm Season">Warm Season</option>
                                    <option value="Cool Season">Cool Season</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 col-sm-6"> 
                                <label>Back Yard Square Feet</label>
                                <input type="text" class="form-control" name="back_yard_square_feet" id="back_yard_square_feet" placeholder="Back Yard Square Feet">
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label>Back Yard Grass Type</label>
                                <select class="form-control" name="back_yard_grass" id="back_yard_grass">
                                    <option value="">Select Back Yard Grass Type</option>
                                    <option value="Bent">Bent</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Dichondra">Dichondra</option>
                                    <option value="Fine Fescue">Fine Fescue</option>
                                    <option value="Kentucky Bluegrass">Kentucky Bluegrass</option>
                                    <option value="Ryegrass">Ryegrass</option>
                                    <option value="St. Augustine/Floratam">St. Augustine/Floratam</option>
                                    <option value="Tall Fescue">Tall Fescue</option>
                                    <option value="Zoysia">Zoysia</option>
                                    <option value="Centipede">Centipede</option>
                                    <option value="Bluegrass/Rye/Fescue">Bluegrass/Rye/Fescue</option>
                                    <option value="Warm Season">Warm Season</option>
                                    <option value="Cool Season">Cool Season</option>
                                </select>

            
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-6 col-sm-6">
                                <label>Property Status</label>
                                <select class="form-control" name="property_status">
                                    <option value="">Select Any Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Non-Active</option>
                                </select>                    
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 col-sm-6">
                                <label class="control-label">Assign Program</label>
                                <div class="multi-select-full">
                                    <select class="multiselect-select-all-filtering form-control" name="assign_program[]" multiple="multiple" id="program_list" value="<?php echo set_value('assign_program') ?>">

                                        <?php foreach ($programlist as $value) : ?>
                                            <option value="<?= $value->program_id ?>"><?= $value->program_name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-12 col-sm-12">
                                <label>Property Info</label>
                                <div style="border: 1px solid #12689b;" >
                                    <textarea class="summernote_property" name="property_notes" > </textarea>
                    
                                </div>                 

                            </div>

            
                        </div>
                    </div>


                    <div class="addcustomeridinmodal">                
                    </div>
            
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="assignjob" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary modal -->

  <!-- Pay Balance -->
    
<div id="modal_pay_outstanding_payment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Pay Total Outstanding Due</h6>
            </div>
            <form id="pastDue_payment_form"  method="post" style="padding: 10px;" >
                <?php
                if (isset($past_due_invoices) && !empty($past_due_invoices)) {
                    $pass_in_array = [];
                    foreach($past_due_invoices as $past) {
                        array_push($pass_in_array, $past);
                        ?>
                        <input type="text" name="past_invoice_ids[]" value="<?= $past ?>">
                    <?php }
                }
                $p_string = implode(",", $pass_in_array);
                ?>
                
                <div style="height: 10px;"></div>
                <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Total Amount Past Due:</label>
                <input type="text" class="form-control" name="partial_payment" value="<?= number_format($total_past_due,2, '.', '') ?>" readonly>
                <input type="hidden" name="payment_status" value="3">
                <input type="hidden" name="total_due" value="<?= number_format($total_due,2, '.', '') ?>">
                <div style="height: 10px;"></div>
                <select class="bootstrap-select form-control" name="payment_method" id="paid_modal_select" style="border: 1px solid #12689b; margin-top: 5px;">
                    <option value="0">Select A Payment Method</option> 
                    <option value="0">Cash</option>
                    <option value="1">Check</option>
                    <option value="2">Credit Card</option>
                    <option value="3">Other</option>
                </select>
                <div style="height: 10px;"></div>
                <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Payment Info:</label>
                <input type="text" class="form-control" name="payment_info" id="payment_info" placeholder="" value="">

                <div style="height: 20px;"></div>
                <button type="submit" class="btn label-success">
                    <a
                    href="<?= base_url('customers/welcome/paymentPastDue/').base64_encode($p_string)  ?>"
                    target="_blank">Pay</a>
                    
                </button>

            </form>
        </div>
    </div>
</div>        
<!-- /Pay Balance -->

<!-- Outstanding Modal -->
<div id="modal_due" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div id="modal_partial" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>       
<!-- /outstanding modal -->
 
<!-- Past Modal -->
<div id="modal_past" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div id="modal_partial" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<!-- /past modal -->



<script>   

    $('#CustomerSubscribeButton').click(
        function(){
            
                if ($('#CustomerSubscribeButton').prop('checked') == false) {
                       
                        $('#CustomerSubscribeTooltip').removeAttr("data-popup");
                        $('#CustomerSubscribeTooltip').removeAttr("title");
                        $('#CustomerSubscribeTooltip').removeAttr("data-placement");

                
                }else{
                    $('#CustomerSubscribeTooltip').attr("data-popup","tooltip-custom");
                    $('#CustomerSubscribeTooltip').attr("title","Turning this option off will cause you to not receive any pre-service or post-service notifications when schedule services at your property. However, you will still receive invoices by email.");
                    $('#CustomerSubscribeTooltip').attr("data-placement","top");
                           
                            
                }
            
        });  

</script>
        
  <!-- /past modal -->
<script>

	 
    function goToAccount(){

        var account = $('#go_to_account').val();
    // console.log(account);
        var path = window.location.href.split("/")
        path.pop();
        path.push(account);
        var url = path.toString();
        url = url.replaceAll(",","/");

    //   $('#go_to_account').on('click', function() {
    //     // Save value in localstorage
    //     localStorage.setItem("go_to_account", $(this).val());
    //  });
    //  console.log(localStorage.setItem());
        
        return window.location.href = url;
    
    }


    // This example displays an address form, using the autocomplete feature
    // of the Google Places API to help users fill in the information.

    var placeSearch, autocomplete, autocomplete2;
    var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
    };

    function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    

    autocomplete2 = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */
        (document.getElementById('autocomplete2')), {
        types: ['geocode']
        });
    autocomplete2.addListener('place_changed', function() {
        fillInAddress(autocomplete2, "2");
    });

    }

    function fillInAddress(autocomplete, unique) {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();

    $('.mydiv').html(place.adr_address);
        return_locality = $('.locality').text();
        return_region = $('.region').text();
        return_postal_code = $('.postal-code').text();
        res = return_postal_code.split("-");
            
        $('#locality'+unique).val(return_locality);
        $('#region'+unique).val(return_region);
        $('#postal-code'+unique).val(res[0]);



    for (var component in componentForm) {
        if (!!document.getElementById(component + unique)) {
        document.getElementById(component + unique).value = '';
        document.getElementById(component + unique).disabled = false;
        }
    }

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType] && document.getElementById(addressType + unique)) {
        var val = place.address_components[i][componentForm[addressType]];
        document.getElementById(addressType + unique).value = val;

    //   alert(val);
        }
    }

    
    }

</script>
  
<script>
    $(function() {
                
        var is_email = document.querySelector('.switchery-is-email');

        var switchery = new Switchery(is_email, {

            color: '#36c9c9',

            secondaryColor: "#dfdfdf",

        });
    });
</script>


<script type="text/javascript">
	function openTab(tab){
		$("li."+tab).addClass('active');
		$("li").not("."+tab).removeClass('active');
	}
	
    $('#reset_secondary_email_link').click(function() {
        swal({
            title: 'Email',
            text: "Do you want to reset field?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#FFBE2C',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if(result.value) {
                $('#secondary_email_list').html("");
                $("#secondary_email_list_hid").val("");
                $("#add_secondary_email_link i").addClass("pt-5");
                $("#reset_secondary_email_link").addClass("hidden");
            }
    
        });
  
    });
         
    $('#autofill').click(function(){
        if($(this).prop("checked") == true){
            var result = $('#autocomplete').val();
            $('#autocomplete2').val(result);

            $('#property_address_2').val($('#billing_street_2').val());
            $('#locality2').val($('#locality').val());
            $('#region2').val($('#region').val());
            $('#postal-code2').val($('#postal-code').val());

        }
        else if($(this).prop("checked") == false){
            $('#autocomplete2').val('');
             
            $('#property_address_2').val('');
            $('#locality2').val('');
            $('#region2').val('');
            $('#postal-code2').val('');

        }

    });

    function keydownAddress2() {      
        $("#autofill")[0].checked = false;       
        $("#uniform-autofill").find("span").removeClass( "checked" );
    }

    function assignProperty(type){

        if (type==1) {
         
            var customer_id = '<?php echo $customerData['customer_id'] ?>';

            $('.addcustomeridinmodal').html('<select name="assign_customer[]" id="multipleCutomerId" multiple="multiple"><option value="'+customer_id+'" selected >Customer</option></select>')

        } else {
         
            $('.addcustomeridinmodal').html('');


        }
    }


    $('#editcustmerpropertytbl').DataTable({
        dom: 'lfrtip',
        initComplete: function(){
            $("div.adcustomerpropertydiv")
            .html('<div class="btn-group"><div class=""><a data-toggle="modal" data-target="#modal_add_property"><button type="submit" onclick="assignProperty(1)"  class="btn btn-success"><i class="icon-add"  ></i> Add Property</button></a></div></div>');           
        }       
    });  
	
    //BASYS AUTOCHARGE 
    $(function() {
        var autocharge = document.querySelector('.switchery-autocharge');
        var switcheryAutocharge = new Switchery(autocharge, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
    
        var is_mobile = document.querySelector('.switchery-is-mobile-text');    
        var switcheryIsMobile = new Switchery(is_mobile, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
    });

    function TextMessageOff(){
        $('#mobile_text').trigger('click');

    }
    // Start text message
    var changeTextAlert = document.querySelector('#mobile_text');
    if (changeTextAlert){
        changeTextAlert.onchange = function() {
	
	        if(changeTextAlert.checked == true ){
		 
		        $('#text_msg_alert').modal('show');
		 
	        }else{
		        $('#text_msg_alert').modal('hide');
	        }
        }
    };
 
    $('button#submitTextAlert').on('click', function(e){
	    e.preventDefault();   
	    var customer_id = $('#submitPaymentMethod').data('customer');
	    var accept = $('form#add_text_alert button[name="accept"]').val();
	
        $.ajax({

		    type: 'POST',
		    url: "<?=base_url('customers/updateTextAlert')?>",
		    data: {customer_id: customer_id, accept: accept},
		    dataType: "JSON",
		    success: function (data){
			    console.log(data)
			    // alert(data.status);
				if (data.status=="success") {
					swal(
                        'Success!',
                        'Text Alerts Added Successfully ',
                        'success'
                    )
                    .then(function () {
						location.reload();
					});
					$('#text_msg_alert').modal('hide');
				}else if (data.status=="failed"){
					if(data.msg){
						var msg = data.msg;
						msg = msg.toUpperCase();
					    $('div#swal2-content').css('text-transform', 'capitalize');
					}else{
						msg = "Something went wrong. Please try again.";
					}
					
					
					swal({
						confirmButtonColor: '#d9534f',
                        type: 'error',
                        title: 'Oops...',
                        text: msg
                    });
				} else {
				    swal({
						confirmButtonColor: '#d9534f',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.'
                    });
				}
		    }

		});
	
    });	
    // end of text message

    function AutoPayOff(){
        // $('#switchery-autocharge').attr('checked', false);
        $('#switchery-autocharge').trigger('click');

    }

    var changeAutocharge = document.querySelector('#switchery-autocharge');
    if (changeAutocharge){
        changeAutocharge.onchange = function() {
            var clover_status = $('input[name="clover_status"]').val();
            var clover_token = $('input[name="customer_clover_token"]').val();
            var basys_status = $('input[name="basys_status"]').val();
            var basys_customer = $('input[name="basys_customer_id"]').val();

            if (clover_status == 1) {
                console.log('Clover Token: ' + clover_token);
                if (changeAutocharge.checked == true && clover_token == "") {
                    $('#clover_payment_method').modal('show');
                    $('#basys_payment_method').modal('hide');
                    $('#clover_auto_on').modal('hide');
                    $('#auto_off').modal('hide');
                    $('#basys_auto_on').modal('hide');
                } else if (changeAutocharge.checked == true && clover_token != "") {
                    $('#clover_payment_method').modal('hide');
                    $('#basys_payment_method').modal('hide');
                    $('#clover_auto_on').modal('show');
                    $('#auto_off').modal('hide');
                    $('#basys_auto_on').modal('hide');
                } else if (changeAutocharge.checked == false && clover_token != "") {
                    $('#clover_payment_method').modal('hide');
                    $('#basys_payment_method').modal('hide');
                    $('#clover_auto_on').modal('hide');
                    $('#auto_off').modal('show');
                    $('#basys_auto_on').modal('hide');
                }
            } else if (basys_status == 1) {
                console.log('Basys Token: ' + basys_customer);
                if (changeAutocharge.checked == true && basys_customer == "") {
                    $('#basys_payment_method').modal('show');
                    $('#clover_payment_method').modal('hide');
                    $('#clover_auto_on').modal('hide');
                    $('#basys_auto_on').modal('hide');
                    $('#auto_off').modal('hide');
                } else if (changeAutocharge.checked == true && basys_customer != "") {
                    $('#clover_payment_method').modal('hide');
                    $('#basys_payment_method').modal('hide');
                    $('#basys_auto_on').modal('show');
                    $('#clover_auto_on').modal('hide');
                    $('#auto_off').modal('hide');
                } else if (changeAutocharge.checked == false && basys_customer != "") {
                    $('#clover_payment_method').modal('hide');
                    $('#basys_payment_method').modal('hide');
                    $('#clover_auto_on').modal('hide');
                    $('#auto_off').modal('show');
                    $('#basys_auto_on').modal('hide');
                }
            }  else {
                console.log('Neither is true');
                $('#basys_payment_method').modal('hide');
                $('#clover_payment_method').modal('hide');
                $('#clover_auto_on').modal('hide');
                $('#basys_auto_on').modal('hide');
                $('#auto_off').modal('hide');
            }
        }


        //alert(changeAutocharge.checked);
    };
    $('button#submitCloverPaymentMethod').on('click', function(e) {
        e.preventDefault();
        var month = $('form#add_clover_payment select[name="clover_card_exp_m"]').val();
        var year = $('form#add_clover_payment select[name="clover_card_exp_y"]').val();
        var customer_id = $('#submitCloverPaymentMethod').data('customer');
        var card_number = $('form#add_clover_payment input[name="clover_card_number"]').val();
        var card_exp = Number(year + month);
        var card_cvv = $('form#add_clover_payment input[name="clover_card_cvv"]').val();

        //card_exp = card_exp.replace("/","");

        $.ajax({

            type: 'POST',
            url: "<?=base_url('customers/cloverAddCustomer')?>",
            data: {
                customer_id: customer_id,
                tokenData: {
                    account: card_number,
                    expiry: card_exp,
                    cvv: card_cvv
                }
            },
            dataType: "JSON",
            success: function(data) {
                console.log(data)
                // alert(data.status);
                if (data.status == 200) {
                    swal(
                        'Success!',
                        data.msg,
                        'success'
                    );
                    $('#clover_payment_method').modal('hide');
                } else if (data.status == "failed") {
                    if (data.msg) {
                        var msg = data.msg;
                        msg = msg.toUpperCase();
                        $('div#swal2-content').css('text-transform', 'capitalize');
                    } else {
                        msg = "Something went wrong. Please try again.";
                    }


                    swal({
                        confirmButtonColor: '#d9534f',
                        type: 'error',
                        title: 'Oops...',
                        text: msg
                    });
                } else {
                    swal({
                        confirmButtonColor: '#d9534f',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.'
                    });
                }
            }

        });

    });

    $('button#submitPaymentMethod').on('click', function(e){
	    e.preventDefault();   
	    var customer_id = $('#submitPaymentMethod').data('customer');
	    var card_number = $('form#add_basys_payment input[name="card_number"]').val();
	    var card_exp = $('form#add_basys_payment input[name="card_exp"]').val();
	
	    //card_exp = card_exp.replace("/","");
	
        $.ajax({

		    type: 'POST',
		    url: "<?=base_url('customers/basysAddCustomer')?>",
		    data: {customer_id: customer_id, card_number: card_number, card_exp:card_exp},
		    dataType: "JSON",
		    success: function (data){
			    console.log(data)
			    // alert(data.status);
				if (data.status=="success") {
					swal(
                        'Success!',
                        'Payment Method Added Successfully ',
                        'success'
                    );
					$('#basys_payment_method').modal('hide');
				}else if (data.status=="failed"){
					if(data.msg){
						var msg = data.msg;
						msg = msg.toUpperCase();
					    $('div#swal2-content').css('text-transform', 'capitalize');
					}else{
						msg = "Something went wrong. Please try again.";
					}
					
					
					swal({
						confirmButtonColor: '#d9534f',
                        type: 'error',
                        title: 'Oops...',
                        text: msg
                    });
				} else {
				    swal({
						confirmButtonColor: '#d9534f',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.'
                    });
				}
		    }

		});
	
    });	
    $('button#submitUpdatePayment').on('click', function(e){
	    e.preventDefault(); 
	    var basys_customer_id = $('input[name="basys_customer_id"]').val();
	    var customer_id = $('#submitUpdatePayment').data('customer');
	    var card_number = $('form#update_basys_payment input[name="card_number"]').val();
	    var card_exp = $('form#update_basys_payment input[name="card_exp"]').val();
	
	    //card_exp = card_exp.replace("/","");
	
        $.ajax({

		    type: 'POST',
		    url: "<?=base_url('customers/basysUpdateCustomerPayment')?>",
		    data: {customer_id: customer_id, card_number: card_number, card_exp:card_exp, basys_customer_id:basys_customer_id},
		    dataType: "JSON",
		    success: function (data){
			    console.log(data)
			    // alert(data.status);
				if (data.status=="success") {
					swal(
                        'Success!',
                        'Payment Method Updated Successfully ',
                        'success'
                    );
					$('#modal_update_payment').modal('hide');
				}else if (data.status=="failed"){
					if(data.msg){
						var msg = data.msg;
						msg = msg.toUpperCase();
					    $('div#swal2-content').css('text-transform', 'capitalize');
					}else{
						msg = "Something went wrong. Please try again.";
					}
					
					
					swal({
						confirmButtonColor: '#d9534f',
                        type: 'error',
                        title: 'Oops...',
                        text: msg
                    });
				} else {
				    swal({
						confirmButtonColor: '#d9534f',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.'
                    });
				}
		    }

		});
	
    });

    $('button#submitCloverUpdatePayment').on('click', function(e) {
        e.preventDefault();
        var clover_token = $('input[name="customer_clover_token"]').val();
        var clover_acct = $('input[name="clover_acct_id"]').val();
        var month = $('form#update_clover_payment select[name="clover_card_exp_m"]').val();
        var year = $('form#update_clover_payment select[name="clover_card_exp_y"]').val();
        var customer_id = $('#submitCloverUpdatePayment').data('customer');
        var card_number = $('form#update_clover_payment input[name="clover_card_number"]').val();
        var card_exp = Number(year + month);
        var card_cvv = $('form#update_clover_payment input[name="clover_card_cvv"]').val();

        //card_exp = card_exp.replace("/","");

        $.ajax({

            type: 'POST',
            url: "<?=base_url('customers/cloverUpdateCustomerPayment')?>",
            data: {
                customer_id: customer_id,
                clover_token: clover_token,
                clover_acct: clover_acct,
                tokenData: {
                    account: card_number,
                    expiry: card_exp,
                    cvv: card_cvv
                }
            },
            dataType: "JSON",
            success: function(data) {
                console.log(data)
                // alert(data.status);
                if (data.status == 200) {
                    swal(
                        'Success!',
                        'Payment Method Updated Successfully ',
                        'success'
                    );
                    $('#clover_update_payment').modal('hide');
                } else if (data.status == 400) {
                    if (data.msg) {
                        var msg = data.msg;
                        msg = msg.toUpperCase();
                        $('div#swal2-content').css('text-transform', 'capitalize');
                    } else {
                        msg = "Something went wrong. Please try again.";
                    }


                    swal({
                        confirmButtonColor: '#d9534f',
                        type: 'error',
                        title: 'Oops...',
                        text: msg
                    });
                } else {
                    swal({
                        confirmButtonColor: '#d9534f',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.'
                    });
                }
            }

        });

    });
    
</script>

<script>

    $(document).ready(function(){  
        var front_yard = $('#front_yard_square_feet').val();
        front_yard = Number.isInteger(Number.parseInt(front_yard)) ? Number.parseInt(front_yard) : 0;

        if (front_yard == 0) {
            $("#front_yard_grass").prop('disabled', true);
        }

        var back_yard = $('#back_yard_square_feet').val();
        back_yard = Number.isInteger(Number.parseInt(back_yard)) ? Number.parseInt(back_yard) : 0;

        if (back_yard == 0) {
            $("#back_yard_grass").prop('disabled', true);
        }

        $("#front_yard_square_feet").keyup(function(){  
            var first_yard = $('#front_yard_square_feet').val();
            first_yard = Number.isInteger(Number.parseInt(first_yard)) ? Number.parseInt(first_yard) : 0;
            var second_yard = 0;

            second_yard = $('#back_yard_square_feet').val();
            second_yard = Number.isInteger(Number.parseInt(second_yard)) ? Number.parseInt(second_yard) : 0;

            var total_yard = first_yard+second_yard;
            total_yard = Number.isInteger(Number.parseInt(total_yard)) ? total_yard : 0;

            $('#yard_square_feet').val(total_yard);

            if (first_yard == 0) {
                $("#front_yard_grass").prop('disabled', true);
            } else {
                $("#front_yard_grass").prop('disabled', false);
            }
        });

        $("#back_yard_square_feet").keyup(function(){  
            var first_yard = $('#back_yard_square_feet').val();
            first_yard = Number.isInteger(Number.parseInt(first_yard)) ? Number.parseInt(first_yard) : 0;
            var second_yard = 0;

            second_yard = $('#front_yard_square_feet').val();
            second_yard = Number.isInteger(Number.parseInt(second_yard)) ? Number.parseInt(second_yard) : 0;

            var total_yard = first_yard+second_yard;
            total_yard = Number.isInteger(Number.parseInt(total_yard)) ? total_yard : 0;

            $('#yard_square_feet').val(total_yard);

            if (first_yard == 0) {
                $("#back_yard_grass").prop('disabled', true);
            } else {
                $("#back_yard_grass").prop('disabled', false);
            }
        });
    });  
</script>
 
