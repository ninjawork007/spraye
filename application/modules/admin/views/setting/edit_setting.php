<?php
if (isset($this->session->userdata['is_text_message']) && $this->session->userdata['is_text_message']) {
    $text_message_field = 1;
} else {
    $text_message_field = 0;
}
?>

<link rel="stylesheet" href="<?= base_url('assets') ?>/SelectBox/mobiscroll.jquery.min.css">
<script src="<?= base_url('assets') ?>/SelectBox/mobiscroll.jquery.min.js"></script>

<style type="text/css">
    #loading {
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        position: fixed;
        display: none;
        opacity: 0.7;
        background-color: #fff;
        z-index: 2000;
        text-align: center;
    }

    .dataTables_filter input {
        margin-left: 0;
    }

    #loading-image {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 10%;
        z-index: 100;
    }

    .table-responsive {
        min-height: .01% !important;
    }

    .block_row {
        padding-top: 20px; 
    }

    .block_row form {
        background-color: #cccccca6;
        opacity: 0.5;
    }

    .checkbox-inline .checker {
        top: 0px !important;
    }

    .checkbox label,
    .radio label {
        padding-top: 0px !important;
    }

    .checkbox-inline.checkbox-right,
    .my_label {
        font-size: 16px;
    }

    .checkbox-inline.checkbox-right {
        padding-left: 10px;
    }

    .block_area {
        background-color: #cccccca6;
        opacity: 0.5;
        padding: 35px 19px;
        border-radius: 30px;
        margin-bottom: 20px;
    }

    .info {
        color: darkblue;
        font-weight: 100;
        font-size: 11px;
        text-decoration: underline;
    }

    .message_type {
        color: darkblue;
        display: block;
    }

    .message_input {
        width: 100%;
        border: none;
        min-height: 45px;
    }

    hr {
        margin-top: 10px;
        margin-bottom: 40px;
        border-top: 2px solid #CFDEE8;
    }
label#image-error {
	font-size: 13px;
font-weight: 700;
font-family: sans-serif;
}
span.requesecolo {
color: red;
}
a.paginate_button.current {
cursor: text;
}
/*-- ---*/
.toolkitout .tooltip {
position: relative;
display: inline-block;
border-bottom: 1px dotted black;
}
.toolkitout .tooltip .tooltiptext {
background-color: #555;
color: #fff;
text-align: center;
border-radius: 4px;
padding: 7px 10px;
position: relative;
font-size: 11px;
font-weight: 500;
}
.toolkitout i:hover + .tooltip {
display: inline-block;
position: absolute;
visibility: visible;
opacity: 1;
top: 30%;
left: 29%;
}
.toolkitout .tooltip .tooltiptext::after {
content: "";
position: absolute;
top: 100%;
left: 50%;
margin-left: -5px;
border-width: 5px;
border-style: solid;
border-color: #555 transparent transparent transparent;
}
.toolkitout .tooltip:hover .tooltiptext {
visibility: visible;
opacity: 1;
}
input#include_in_tech_view {
top: 19px;
position: relative;
}
.datatable-scroll {	
min-height: 0.01% !important;	
max-height: 600px;	
overflow: hidden;	
overflow-y: auto;	
display: block;	
width: 100%;	
border-bottom: 1px solid #6eb1fd;	
border-radius: 4px;	
}	
/* Scrollbar Styling */	
.datatable-scroll::-webkit-scrollbar {	
width: 5px;	
height: 100px;	
}	
.datatable-scroll::-webkit-scrollbar-track {	
background-color: #ddd;	
-webkit-border-radius: 50px;	
border-radius: 50px;	
}	
.datatable-scroll::-webkit-scrollbar-thumb {	
-webkit-border-radius: 10px;	
border-radius: 10px;	
background: #6eb1fd;	
}
/*
input[type=checkbox], input[type=radio] {	
margin: 0px 0 0;	
margin-top: 1px\9;	
line-height: normal;	
}	
*/
</style>
<script src="<?= base_url('assets/admin/assets/js/imageresize/imageresize.js') ?>"></script>
<script>
    var apiCall = function() {
        this.getCompanyInfo = function() {
            /*
            AJAX Request to retrieve getCompanyInfo
             */
            $.ajax({
                type: "GET",
                url: "apiCall.php",
            }).done(function(msg) {
                $('#apiCall').html(msg);
            });
        }
        this.refreshToken = function() {
            $.ajax({
                type: "POST",
                url: "refreshToken.php",
            }).done(function(msg) {

            });
        }
    }
    var apiCall = new apiCall();
</script>

<!-- Content area -->
<div class="content">
    <!-- Form horizontal -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <div class="form-group">
                    <a href="<?= base_url('admin') ?>" id="save" class="btn btn-success"><i class="icon-arrow-left7"></i> Go
                        Back</a>
                </div>
            </h5>
        </div>
        <div id="loading">
            <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>" /> <!-- Loading Image -->
        </div>
        <br>
        <div class="panel-body">
            <b><?php if ($this->session->flashdata()) : echo $this->session->flashdata('message');
                endif; ?></b>
            <form class="form-horizontal form1" action="<?= base_url('admin/setting/updateCompanyDetailsData') ?>" method="post" name="companydetails" enctype="multipart/form-data">
                <fieldset class="content-group">
                    <legend class="text-bold">Company Details</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Company Name<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="company_name" placeholder="Company Name" value="<?= $setting_details->company_name ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Company Address<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" onFocus="geolocate()" class="form-control" name="company_address" placeholder="Company Address" value="<?= $setting_details->company_address ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Company Phone Number<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="company_phone_number" placeholder="Company Phone Number" value="<?= $setting_details->company_phone_number ?>">
                                    <br>
                                    <span>Please do not use dashes</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Company Email<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="company_email" placeholder="Company Email" value="<?= $setting_details->company_email ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Company Web Address</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="web_address" placeholder="Company Web" value="<?= $setting_details->web_address ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Company Logo</label>
                                <div class="col-lg-9">
                                    <input type="file" title="select" class="upload" id="image" name="company_logo" accept="image/*">
                                    <br>
                                    <span><b>(suggested image size: height: 46px; width: 180px)</b></span>
                                    <input type="hidden" id="resized_image" name="resized_image" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Currency</label>
                                <div class="col-lg-9">                                    
                                    <input type="text" class="form-control" name="web_address" placeholder="" value="<?= $setting_details->company_currency ?>" disabled>
                                    <small id="companyCurrencyHelp" class="form-text text-muted">Currency company will charge customers.</small>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Invoice Color<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="invoice_color" class="form-control colorpicker-show-input" data-preferred-format="hex3" value="<?= $setting_details->invoice_color ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3"></label>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="editimgdiv">
                                                <img height="50" id="preview_img" src="" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <?php
                                            if (!empty($setting_details->company_logo)) { ?>
                                                <img height="50" src="<?php echo CLOUDFRONT_URL ?>uploads/company_logo/<?php echo $setting_details->company_logo ?>">
                                            <?php }  ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Default # of entries<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                   <!-- <input type="text" class="form-control" value="<?/*= $setting_details->default_display_length */?>">-->
                                    <select class="form-control"  name="default_display_length">
                                        <option value="10" <?= 10 == $setting_details->default_display_length ? 'selected' : '' ?>>10</option>
                                        <option value="20" <?= 20 == $setting_details->default_display_length ? 'selected' : '' ?>>20</option>
                                        <option value="50" <?= 50 == $setting_details->default_display_length ? 'selected' : '' ?>>50</option>
                                        <option value="100" <?= 100 == $setting_details->default_display_length ? 'selected' : '' ?>>100</option>
                                        <option value="200" <?= 200 == $setting_details->default_display_length ? 'selected' : '' ?>>200</option>
                                        <option value="500" <?= 500 == $setting_details->default_display_length ? 'selected' : '' ?>>500</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Default time zone</label>
                                <div class="col-lg-9">
                                    <select class="form-control" name="time_zone">
                                        <?php
                                        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                                        if ($tzlist && is_array($tzlist) && !empty($tzlist)) {
                                            foreach ($tzlist as $key => $value) {
                                        ?>
                                                <option value="<?= $value ?>" <?= $value == $setting_details->time_zone ? 'selected' : '' ?>>
                                                    <?= $value  ?></option>
                                        <?php  }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix">&nbsp;</div>
                        <?php
                        $FetchEmails = explode(",", $setting_details->ready_for_payment_po_email);
                        $FetchEmails = array_map('trim', $FetchEmails);
                        ?>

                        <label>
                            Email to send PO (when changes to ready for payment)
                            <input mbsc-input id="demo-multiple-select-input" placeholder="Please select..." data-dropdown="true" data-input-style="outline" data-label-style="stacked" data-tags="true" name="ready_for_payment_po_email"/>
                        </label>
                        <select id="demo-multiple-select" multiple>
                            <?php
                            foreach($customers as $Cusom){
                            ?>
                            <option <?php if(in_array($Cusom->email, $FetchEmails)){ echo 'selected'; }?>><?php echo $Cusom->email ?></option>
                            <?php
                            }
                            ?>

                            <?php
                            foreach($vendors as $Cusom){
                            ?>
                            <option <?php if(in_array($Cusom->vendor_email_address, $FetchEmails)){ echo 'selected'; }?>><?php echo $Cusom->vendor_email_address ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </fieldset>
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </form>

            <?php
            $block_class = '';
            if ($subscription_details) {
                if ($subscription_details->is_quickbooks_price == 0) {
                    $block_class = 'block_row';
                }
            } else {
                $block_class = 'block_row';
            }
            ?>
            <div class="row <?= $block_class  ?>">
                <div class="col-md-12">
                    <form class="form-horizontal form2" action="<?= base_url('admin/Quickbook/AuthSetInVariable') ?>" method="post" name="quickbookauth" enctype="multipart/form-data">
                        <fieldset class="content-group">
                            <legend class="text-bold">Quickbooks (BETA)
                                <?php
                                if ($setting_details->is_quickbook == 1) { ?>
                                    <label class="togglebutton">
                                        disable&nbsp;<input name="quickbook_status" type="checkbox" class="switchery-primary" <?php echo $setting_details->quickbook_status == 1 ? 'checked' : '';  ?>>enable
                                    </label>
                                <?php } ?>
                            </legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Client ID</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" name="quickbook_client_id" placeholder="Client ID" value="<?= $setting_details->quickbook_client_id ?>" <?= $block_class == '' ? '' : 'disabled' ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Client Secret</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" name="quickbook_client_secret" placeholder="Client Secret" value="<?= $setting_details->quickbook_client_secret ?>" <?= $block_class == '' ? '' : 'disabled' ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php
                                    if ($block_class == '') { ?>
                                        <span style="font-size: 13px;">Note : Please provide production authorization and set redirect url
                                            <?= base_url() ?>admin/quickbook/processCode
                                        </span>
                                    <?php } else { ?>
                                        <span style="font-size: 15px;">Note : <u>Please contact support to unlock this feature.</u></span>
                                    <?php  } ?>
                                </div>
                            </div>
                        </fieldset>
                        <div class="text-right">
                            <button type="submit" class="btn btn-success" <?= $block_class == '' ? '' : 'disabled' ?>><img width="100px" height="30px" src="<?= base_url('assets/img/download.png') ?>"></button>
                        </div>
                    </form>
                </div>
            </div>
            <form class="form-horizontal form2" action="<?= base_url('admin/setting/updateSettingData') ?>" method="post" name="settings" enctype="multipart/form-data">
                <fieldset class="content-group">
                    <legend class="text-bold">Maps and Routes</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Start Location</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="start_location" id="autocomplete" onFocus="geolocate()" placeholder="Start Locations" value="<?= $setting_details->start_location ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">End Location</label>
                                <div class="col-lg-9">
                                    <input type="text" id="autocomplete2" onFocus="geolocate()" class="form-control" name="end_location" placeholder="End Locations" value="<?= $setting_details->end_location ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </form>
            <!-- ///////////////////////////////////////// end company details  //////////////////////////// -->
            <!-- ///////////////////////////////////////// Automated emails ////////////////////////////////  -->
            <form class="form-horizontal form2" action="<?= base_url('admin/setting/updateEmailAutomated') ?>" method="post" name="automatedemail" enctype="multipart/form-data">
                <fieldset class="content-group">
                    <legend class="text-bold">Automated Emails & Text Messaging</legend>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Service Scheduled
                                    <span class="message_type">(email message)</span>
                                    <label class="togglebutton" style="font-size:13px">
                                        Off&nbsp;<input name="job_sheduled_status" type="checkbox" class="switchery-service-scheduled" <?php echo $company_email_details->job_sheduled_status == 1 ? 'checked' : '';  ?>>&nbsp;On
                                    </label>

                                </label>
                                <div class="col-lg-9" style="border: 1px solid #12689b;">
                                    <textarea class="summernote" name="job_sheduled"><?php echo $company_email_details->job_sheduled  ?> </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--new text message row-->
                    <?php if ($text_message_field) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Service Scheduled
                                        <span class="message_type">(text message)</span>
                                        <label class="togglebutton" style="font-size:13px">
                                            Off&nbsp;<input name="job_sheduled_status_text" type="checkbox" class="switchery-service-scheduled-text" <?php echo $company_email_details->job_sheduled_status_text == 1 ? 'checked' : '';  ?>>&nbsp;On
                                        </label>
                                    </label>
                                    <div class="col-lg-9" style="border: 1px solid #12689b;">
                                        <input type="text" class="message_input" id="count_job_scheduled" maxlength="160" name="job_sheduled_text" value="<?php echo $company_email_details->job_sheduled_text  ?>">
                                    </div>
                                    <span>Max number of characters: 160. No merge fields except: {mm/dd/yyyy}</span><span data-popup="tooltip-custom" title="Insert the following to show scheduled service date: {mm/dd/yyyy}." data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Rescheduled Service <span class="message_type">(email message)</span>

                                    <label class="togglebutton" style="font-size:13px">
                                        Off&nbsp;<input name="job_sheduled_skipped_status" type="checkbox" class="switchery-service-skipped" <?php echo $company_email_details->job_sheduled_skipped_status == 1 ? 'checked' : '';  ?>>&nbsp;On
                                    </label>

                                </label>
                                <div class="col-lg-9" style="border: 1px solid #12689b;">
                                    <textarea class="summernote" name="job_sheduled_skipped"><?php echo $company_email_details->job_sheduled_skipped  ?> </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--new text message row-->
                    <?php if ($text_message_field) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Rescheduled Service
                                        <span class="message_type">(text message)</span>
                                        <label class="togglebutton" style="font-size:13px">
                                            Off&nbsp;<input name="job_sheduled_skipped_status_text" type="checkbox" class="switchery-service-skipped-text" <?php echo $company_email_details->job_sheduled_skipped_status_text == 1 ? 'checked' : '';  ?>>&nbsp;On
                                        </label>

                                    </label>
                                    <div class="col-lg-9" style="border: 1px solid #12689b;">
                                        <input type="text" class="message_input" id="count_service_skipped" maxlength="160" name="job_sheduled_skipped_text" value="<?php echo $company_email_details->job_sheduled_skipped_text  ?>">
                                    </div>
                                    <span>Max number of characters: 160. No merge fields allowed.</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-lg-3" id="one_day_prior">1 Day Prior To Scheduled Date
                                    <span class="message_type">(email message)</span>
                                    <label class="togglebutton" style="font-size:13px">
                                        Off&nbsp;<input name="one_day_prior_status" type="checkbox" class="switchery-service-prior" <?php echo $company_email_details->one_day_prior_status == 1 ? 'checked' : '';  ?>>&nbsp;On
                                    </label>
                                </label>
                                <div class="col-lg-9" style="border: 1px solid #12689b;">
                                    <textarea class="summernote2" name="one_day_prior"><?php echo $company_email_details->one_day_prior  ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--new text message row-->
                    <?php if ($text_message_field) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3" id="one_day_prior_text">1 Day Prior To Scheduled Date
                                        <span class="message_type">(text message)</span>
                                        <label class="togglebutton" style="font-size:13px">
                                            Off&nbsp;<input name="one_day_prior_status_text" type="checkbox" class="switchery-service-prior-text" <?php echo $company_email_details->one_day_prior_status_text == 1 ? 'checked' : '';  ?>>&nbsp;On
                                        </label>
                                    </label>
                                    <div class="col-lg-9" style="border: 1px solid #12689b;">
                                        <input type="text" class="message_input" id="count_one_day_prior" maxlength="160" name="one_day_prior_text" value="<?php echo $company_email_details->one_day_prior_text  ?>">
                                    </div>
                                    <span>Max number of characters: 160. No merge fields allowed.</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Service Completion
                                    <span class="message_type">(email message)</span>
                                    <label class="togglebutton" style="font-size:13px">
                                        Off&nbsp;<input name="job_completion_status" type="checkbox" class="switchery-service-complete" <?php echo $company_email_details->job_completion_status == 1 ? 'checked' : '';  ?>>&nbsp;On
                                    </label>
                                </label>
                                <div class="col-lg-9" style="border: 1px solid #12689b;">
                                    <textarea class="summernote3" name="job_completion"><?php echo $company_email_details->job_completion  ?></textarea>
                                </div>
                                <div class="col-lg-9 pull-right" style="padding:0px;">
                                    <span style="text-align:left;">New Feature! Insert the following to show Property Conditions in Service Completion email: {PROPERTY_CONDITIONS}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--new text message row-->
                    <?php if ($text_message_field) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Service Completion
                                        <span class="message_type">(text message)</span>
                                        <label class="togglebutton" style="font-size:13px">
                                            Off&nbsp;<input name="job_completion_status_text" type="checkbox" class="switchery-service-complete-text" <?php echo $company_email_details->job_completion_status_text == 1 ? 'checked' : '';  ?>>&nbsp;On
                                        </label>
                                    </label>
                                    <div class="col-lg-9" style="border: 1px solid #12689b;">
                                        <input type="text" class="message_input" id="count_job_completed" maxlength="160" name="job_completion_text" value="<?php echo $company_email_details->job_completion_text  ?>">
                                    </div>
                                    <span>Max number of characters: 160. No merge fields allowed.</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <hr>

                    <!--Adding 4 new fields-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Program Assigned
                                    <span class="message_type">(email message)</span>
                                    <label class="togglebutton" style="font-size:13px">
                                        Off&nbsp;<input name="program_assigned_status" type="checkbox" class="switchery-program-assigned" <?php echo $company_email_details->program_assigned_status == 1 ? 'checked' : '';  ?>>&nbsp;On
                                    </label>
                                </label>
                                <div class="col-lg-9" style="border: 1px solid #12689b;">
                                    <textarea class="summernote3" name="program_assigned"><?php echo $company_email_details->program_assigned  ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($text_message_field) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Program Assigned
                                        <span class="message_type">(text message)</span>
                                        <label class="togglebutton" style="font-size:13px">
                                            Off&nbsp;<input name="program_assigned_status_text" type="checkbox" class="switchery-program-assigned-text" <?php echo $company_email_details->program_assigned_status_text == 1 ? 'checked' : '';  ?>>&nbsp;On
                                        </label>
                                    </label>
                                    <div class="col-lg-9" style="border: 1px solid #12689b;">
                                        <input type="text" class="message_input" id="count_program_assigned" maxlength="160" name="program_assigned_text" value="<?php echo $company_email_details->program_assigned_text  ?>">
                                    </div>
                                    <span>Max number of characters: 160. No merge fields allowed.</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Estimate Accepted
                                    <span class="message_type">(email message)</span>
                                    <label class="togglebutton" style="font-size:13px">
                                        Off&nbsp;<input name="estimate_accepted_status" type="checkbox" class="switchery-estimate-accepted" <?php echo $company_email_details->estimate_accepted_status == 1 ? 'checked' : '';  ?>>&nbsp;On
                                    </label>
                                </label>
                                <div class="col-lg-9" style="border: 1px solid #12689b;">
                                    <textarea class="summernote3" name="estimate_accepted"><?php echo $company_email_details->estimate_accepted  ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($text_message_field) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Estimate Accepted
                                        <span class="message_type">(text message)</span>
                                        <label class="togglebutton" style="font-size:13px">
                                            Off&nbsp;<input name="estimate_accepted_status_text" type="checkbox" class="switchery-estimate-accepted-text" <?php echo $company_email_details->estimate_accepted_status_text == 1 ? 'checked' : '';  ?>>&nbsp;On
                                        </label>
                                    </label>
                                    <div class="col-lg-9" style="border: 1px solid #12689b;">
                                        <input type="text" class="message_input" id="count_estimate_accepted" maxlength="160" name="estimate_accepted_text" value="<?php echo $company_email_details->estimate_accepted_text  ?>">
                                    </div>
                                    <span>Max number of characters: 160. No merge fields allowed.</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Purchase Order Accepted
                                    <span class="message_type">(email message)</span>
                                    <label class="togglebutton" style="font-size:13px">
                                        Off&nbsp;<input name="purchase_order_accepted_status" type="checkbox" class="switchery-purchase-order-accepted" <?php echo $company_email_details->purchase_order_accepted_status == 1 ? 'checked' : '';  ?>>&nbsp;On
                                    </label>
                                </label>
                                <div class="col-lg-9" style="border: 1px solid #12689b;">
                                    <textarea class="summernote3" name="purchase_order_accepted"><?php echo $company_email_details->purchase_order_accepted  ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($text_message_field) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Purchase Order  Accepted
                                        <span class="message_type">(text message)</span>
                                        <label class="togglebutton" style="font-size:13px">
                                            Off&nbsp;<input name="purchase_order_accepted_status_text" type="checkbox" class="switchery-purchase-order-accepted-text" <?php echo $company_email_details->purchase_order_accepted_status_text == 1 ? 'checked' : '';  ?>>&nbsp;On
                                        </label>
                                    </label>
                                    <div class="col-lg-9" style="border: 1px solid #12689b;">
                                        <input type="text" class="message_input" id="count_purchase_order_accepted" maxlength="160" name="purchase_order_accepted_text" value="<?php echo $company_email_details->purchase_order_accepted_text  ?>">
                                        </div>
                                    <span>Max number of characters: 160. No merge fields allowed.</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
				</fieldset>
                    <hr>
					<fieldset>
						<legend class="text-bold">Customer Account Hold</legend>
                   			 <div class="row">
								<div class="col-md-12">
										<div class="form-group">
											<label class="control-label col-lg-3">
											Automatic Customer Hold <span data-popup="tooltip-custom" title='If turned on, will automatically place a customer account on hold.' data-placement="right"><i class=" icon-info22 tooltip-icon"></i></span><span class="message_type">(Service) </span>
												<label class="togglebutton" style="font-size:13px">
												Off&nbsp;<input name="is_email_scheduling_indays" type="checkbox" class="switchery-customer-hold" <?php echo $company_email_details->is_email_scheduling_indays == 1 ? 'checked' : '';  ?>>&nbsp;On </label>
											</label>
											<div class="col-lg-9">
												<select class="form-control" name="email_scheduling_indays">
												<option value="14" <?php if ($company_email_details->email_scheduling_indays==14){echo 'selected="selected"';} ?>>14 Days</option>
												<option value="30" <?php if ($company_email_details->email_scheduling_indays==30){echo 'selected="selected"';} ?>>30 Days</option>
                                                <option value="45" <?php if ($company_email_details->email_scheduling_indays==45){echo 'selected="selected"';} ?>>45 Days</option>
												<option value="60" <?php if ($company_email_details->email_scheduling_indays==60){echo 'selected="selected"';} ?>>60 Days</option>
												<option value="90"<?php if ($company_email_details->email_scheduling_indays==90){echo 'selected="selected"';} ?>>90 Days</option>
												<option value="180"<?php if ($company_email_details->email_scheduling_indays==180){echo 'selected="selected"';} ?>>180 Days</option>
												</select>


											</div>
										</div>
									</div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Customer Hold Notification
                                    <span class="message_type">(email message)</span>
                                    <label class="togglebutton" style="font-size:13px">
                                        Off&nbsp;<input name="is_email_hold_templete" type="checkbox" class="switchery-email-service-hold" <?php echo $company_email_details->is_email_hold_templete == 1  ? 'checked' : '';  ?>>&nbsp;On
                                    </label>
                                </label>
                                <div class="col-lg-9" style="border: 1px solid #12689b;">
                                    <textarea class="summernote3" name="email_hold_templete"><?php echo $company_email_details->email_hold_templete  ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Customer Hold Notification
                                        <span class="message_type">(Customer Portal)</span>
                                        <label class="togglebutton" style="font-size:13px">
                                            Off&nbsp;<input name="is_hold_notification" type="checkbox" class="switchery-hold_customer_ntification" <?php echo $company_email_details->is_hold_notification == 1 ? 'checked' : '';  ?>>&nbsp;On
                                        </label>
                                    </label>
                                    <div class="col-lg-9" style="border: 1px solid #12689b;">
                                        <input type="text" class="message_input" id="hold_status_completed" maxlength="160" name="hold_notification" value="<?php echo $company_email_details->hold_notification  ?>">
                                
                                    </div>
                                    <span>Max number of characters: 160. No merge fields allowed.</span>
                                </div>
                            </div>
                        </div>
                    
                </fieldset>
                <fieldset>
                    <legend class="text-bold">Select the fields below that you would like to include in all "Service Completion"
                        emails to your customers.</legend>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_product_name" class="styled" <?php echo $company_email_details->is_product_name == 1 ? 'checked' : '' ?>>
                                        Product Name
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_epa" class="styled" <?php echo $company_email_details->is_epa == 1 ? 'checked' : '' ?>>
                                        EPA #
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_active_ingredients" class="styled" <?php echo $company_email_details->is_active_ingredients == 1 ? 'checked' : '' ?>>
                                        Active Ingredients
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_application_rate" class="styled" <?php echo $company_email_details->is_application_rate == 1 ? 'checked' : '' ?>>
                                        Application Rate
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_estimated_chemical_used" class="styled" <?php echo $company_email_details->is_estimated_chemical_used == 1 ? 'checked' : '' ?>>
                                        Estimated Chemical Used
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_chemical_type" class="styled" <?php echo $company_email_details->is_chemical_type == 1 ? 'checked' : '' ?>>
                                        Chemical Type
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_re_entry_time" class="styled" <?php echo $company_email_details->is_re_entry_time == 1 ? 'checked' : '' ?>>
                                        Re-Entry Time
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_weed_pest_prevented" class="styled" <?php echo $company_email_details->is_weed_pest_prevented == 1 ? 'checked' : '' ?>>
                                        Weed/Pest Prevented
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_application_type" class="styled" <?php echo $company_email_details->is_application_type == 1 ? 'checked' : '' ?>>
                                        Application Type
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_wind_speed" class="styled" <?php echo $company_email_details->is_wind_speed == 1 ? 'checked' : '' ?>>
                                        Wind Speed
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_wind_direction" class="styled" <?php echo $company_email_details->is_wind_direction == 1 ? 'checked' : '' ?>>
                                        Wind Direction
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_temperature" class="styled" <?php echo $company_email_details->is_temperature == 1 ? 'checked' : '' ?>>
                                        Temperature
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_applicator_name" class="styled" <?php echo $company_email_details->is_applicator_name == 1 ? 'checked' : '' ?>>
                                        Applicator’s Name
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_applicator_number" class="styled" <?php echo $company_email_details->is_applicator_number == 1 ? 'checked' : '' ?>>
                                        Applicator’s #
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_applicator_phone" class="styled" <?php echo $company_email_details->is_applicator_phone == 1 ? 'checked' : '' ?>>
                                        Applicator's Contact
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_property_address" class="styled" <?php echo $company_email_details->is_property_address == 1 ? 'checked' : '' ?>>
                                        Property Address
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_property_size" class="styled" <?php echo $company_email_details->is_property_size == 1 ? 'checked' : '' ?>>
                                        Property Size
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_date" class="styled" <?php echo $company_email_details->is_date == 1 ? 'checked' : '' ?>>
                                        Date
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input type="checkbox" name="is_time" class="styled" <?php echo $company_email_details->is_time == 1 ? 'checked' : '' ?>>
                                        Time
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </form>
            <!-- ///////////////////////////////////////// end Automated emails //////////////////////////////// -->
            <form class="form-horizontal form2" action="<?= base_url('admin/setting/updateSmtp') ?>" method="post" name="smtpcredential" enctype="multipart/form-data">
                <fieldset class="content-group">
                    <legend class="text-bold">Company SMTP Details
                        <a href="http://support.spraye.io/support/solutions/articles/47001135041-smtp-settings-how-to-send-emails-from-your-company-domain" target="_blank" class="info">More info</a>
                    </legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">SMTP Host</label>
                                <?php
                                $smtp_host_ex =  explode("://", $company_email_details->smtp_host);
                                ?>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <select class="form-control" name="smtp_host_type">
                                                <option value="tls://" <?php if ($smtp_host_ex[0] . '://' == "tls://") { ?> selected <?php } ?>>
                                                    tls://</option>
                                                <option value="ssl://" <?php if ($smtp_host_ex[0] . '://' == "ssl://") { ?> selected <?php } ?>>
                                                    ssl://</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" name="smtp_host" placeholder="Host" value="<?= array_key_exists(1, $smtp_host_ex) ? $smtp_host_ex[1] : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">SMTP Port</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="smtp_port" placeholder="Port" value="<?= $company_email_details->smtp_port ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">SMTP Username</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="smtp_username" placeholder="SMTP Username" value="<?= $company_email_details->smtp_username ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">SMTP Password</label>
                                <div class="col-lg-9">
                                    <input type="password" class="form-control" name="smtp_password" placeholder="SMTP Password" value="<?= $company_email_details->smtp_password ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </form>
            <!-- ///////////////////////////////////////// INVICE SETTING ////////////////////////////// -->
            <div id="credit_card_processing" style="padding:35px 19px;">
                <fieldset class="content-group">
                    <legend class="text-bold">Credit Card Processing</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3" style="padding-left:0px;">Credit Card
                                    Processor</label>
                                <div class="col-lg-9">
                                    <?php if ($cardconnect_details && $cardconnect_details->status != 0){ ?>
                                    <select class="form-control" name="cc_processor_cc">
                                        <option value="cardconnect" selected>
                                            Clover Connect</option>
                                    </select>
                                    <?php } else if ($basys_details && $basys_details->status != 0){ ?>
                                    <select class="form-control" name="cc_processor_bas">
                                        <option value="cardconnect">
                                            Clover Connect</option>
                                        <option value="basys" selected>BASYS</option>
                                    </select>
                                    <?php } else { ?>
                                    <select class="form-control" name="cc_processor">
                                        <option value="cardconnect">
                                            CloverConnect</option>
                                    </select>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <?php if ($cardconnect_details && $cardconnect_details->status != 0){ ?>
                <fieldset class="content-group" id="cardconnect_form_cc">
                    <legend class="text-bold">Clover Connect   </legend><a href="https://integrate.clover.com/partner/spraye-software" target="_blank"><button type="button" class="btn btn-success btn-rounded">
                        Click here to sign up for CloverConnect </button></a>
                        <br><br>
                    <form class="form-horizontal" action="<?= base_url('admin/setting/checkCardConnectApi') ?>"
                        method="post" name="cardconnect_intrigation">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Merchant ID</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" name="cardconnect_mid"
                                            placeholder="CardConnect Merchant ID"
                                            value="<?= $cardconnect_details ? $cardconnect_details->merchant_id : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Username</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" name="cardconnect_username"
                                            placeholder="CardConnect Username" autocomplete="off"
                                            value="<?= $cardconnect_details ? $cardconnect_details->username : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Password</label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control" name="cardconnect_password"
                                            placeholder="CardConnect Password" autocomplete="new-password"
                                            value="<?= $cardconnect_details ? decryptPassword($cardconnect_details->password) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-success">Submit <i
                                    class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </form>
                </fieldset>
                <?php } else if ($basys_details && $basys_details->status != 0){ ?>
                <fieldset class="content-group" id="basys_form_bas">
                    <legend class="text-bold">BASYS</legend>
                    <form class="form-horizontal" action="<?= base_url('admin/setting/checkBasysApi') ?>" method="post"
                        name="basysintrigation">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">BASYS API Key</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" name="api_key"
                                            placeholder="BASYS API Key"
                                            value="<?= $basys_details ? $basys_details->api_key : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-success">Submit <i
                                    class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </form>
                </fieldset>
                <fieldset class="content-group" id="cardconnect_form_bas" style="display:none">
                <legend class="text-bold">Clover Connect   </legend><a href="https://integrate.clover.com/partner/spraye-software" target="_blank"><button type="button" class="btn btn-success btn-rounded">
                        Click here to sign up for CloverConnect </button></a>
                        <br><br>
                    <form class="form-horizontal" action="<?= base_url('admin/setting/checkCardConnectApi') ?>"
                        method="post" name="cardconnect_intrigation">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Merchant ID</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" name="cardconnect_mid"
                                            placeholder="CardConnect Merchant ID"
                                            value="<?= $cardconnect_details ? $cardconnect_details->merchant_id : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Username</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" name="cardconnect_username"
                                            placeholder="CardConnect Username" autocomplete="off"
                                            value="<?= $cardconnect_details ? $cardconnect_details->username : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Password</label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control" name="cardconnect_password"
                                            placeholder="CardConnect Password" autocomplete="new-password"
                                            value="<?= $cardconnect_details ? decryptPassword($cardconnect_details->password) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-success">Submit <i
                                    class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </form>
                </fieldset>
                <?php } else { ?>
                <fieldset class="content-group" id="cardconnect_form">
                <legend class="text-bold">Clover Connect   </legend><a href="https://integrate.clover.com/partner/spraye-software" target="_blank"><button type="button" class="btn btn-success btn-rounded">
                        Click here to sign up for CloverConnect </button></a>
                        <br><br>
                    <form class="form-horizontal" action="<?= base_url('admin/setting/checkCardConnectApi') ?>"
                        method="post" name="cardconnect_intrigation">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Merchant ID</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" name="cardconnect_mid"
                                            placeholder="CardConnect Merchant ID"
                                            value="<?= $cardconnect_details ? $cardconnect_details->merchant_id : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Username</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" name="cardconnect_username"
                                            placeholder="CardConnect Username" autocomplete="off"
                                            value="<?= $cardconnect_details ? $cardconnect_details->username : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Password</label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control" name="cardconnect_password"
                                            placeholder="CardConnect Password" autocomplete="new-password"
                                            value="<?= $cardconnect_details ? decryptPassword($cardconnect_details->password) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-success">Submit <i
                                    class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </form>
                </fieldset>
                <?php } ?>
            </div>
    <!-- ///////////////////////////////////////// INVICE SETTING ////////////////////////////// -->
    <form class="form-horizontal form2" action="<?= base_url('admin/setting/updateEstimateDetails') ?>" method="post" name="estimatesetting" enctype="multipart/form-data">
        <fieldset class="content-group">
            <legend class="text-bold">Estimates Settings</legend>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Terms & Conditions of Service</label>
                        <div class="col-lg-9">
                            <textarea class="form-control" name="tearm_condition"><?= $setting_details->tearm_condition  ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="text-right">
            <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
    </form>
    <!-- ///////////////////////////////////////// service area //////////////////////////////// -->
    <!-- ///////////////////////////////////////// INVICE SETTING ////////////////////////////// -->
    <?php
    if (($basys_details &&  $basys_details->status == 1) || ($cardconnect_details && $cardconnect_details->status == 1)) {
        $block_class = 'block_area';
    } else {
        $block_class = '';
    }
    ?>
    <form class="form-horizontal form2 " action="<?= base_url('admin/setting/updateInvoiceDetails') ?>" method="post" name="invoicedetails" enctype="multipart/form-data">
        <fieldset class="content-group">
            <legend class="text-bold">Invoice Settings</legend>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Payment Terms</label>
                        <div class="col-lg-9">
                            <select class="form-control" name="payment_terms">
                                <option value="">Select any payment term</option>
                                <option value="1" <?= $setting_details->payment_terms == 1 ? 'selected' : ''  ?>>Due Upon Receipt
                                </option>
                                <option value="2" <?= $setting_details->payment_terms == 2 ? 'selected' : ''  ?>>Net 7</option>
                                <option value="3" <?= $setting_details->payment_terms == 3 ? 'selected' : ''  ?>>Net 10</option>
                                <option value="4" <?= $setting_details->payment_terms == 4 ? 'selected' : ''  ?>>Net 14</option>
                                <option value="5" <?= $setting_details->payment_terms == 5 ? 'selected' : ''  ?>>Net 15</option>
                                <option value="6" <?= $setting_details->payment_terms == 6 ? 'selected' : ''  ?>>Net 20</option>
                                <option value="7" <?= $setting_details->payment_terms == 7 ? 'selected' : ''  ?>>Net 30</option>
                                <option value="8" <?= $setting_details->payment_terms == 8 ? 'selected' : ''  ?>>Net 45</option>
                                <option value="9" <?= $setting_details->payment_terms == 9 ? 'selected' : ''  ?>>Net 60</option>
                                <option value="10" <?= $setting_details->payment_terms == 10 ? 'selected' : ''  ?>>Net 90</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Default Invoice Message</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="default_invoice_message" placeholder="Thank you for your business!" value="<?= $setting_details->default_invoice_message ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-5 my_label">Hide/Show Sales Tax</label>
                        <div class="col-lg-7">
                            <label class="togglebutton">
                                Hide&nbsp;<input name="is_sales_tax" type="checkbox" class="switchery-sales-tax" <?php echo $setting_details->is_sales_tax == 1 ? 'checked' : '';  ?>>&nbsp;Show
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Convenience Fee <span data-popup="tooltip-custom" title="Charge a convenience fee when customers choose to pay for invoices online." data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-success">%</span>
                                </span>
                                <input type="text" class="form-control" name="convenience_fee" placeholder="Convenience Fee" value="<?= $setting_details->convenience_fee ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-5 my_label">Auto-Send Invoices by Email <span data-popup="tooltip-custom" title='When this setting is set to "On" all unsent invoices will be sent daily (near the end of each day) to the billing email address on file. You can switch this setting to "Monthly" or "Off" for specific customers on their customer profile.' data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></label>
                        <label class="togglebutton col-lg-7" style="font-size:13px">
                            Off&nbsp;<input name="send_daily_invoice_mail" type="checkbox" class="switchery-send-daily-invoice-mail" <?php echo $company_email_details->send_daily_invoice_mail == 1 ? 'checked' : '';  ?>>&nbsp;On
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-5 my_label">Ability for Technician to add Standalone Service to Property</label>
                        <label class="togglebutton col-lg-7" style="font-size:13px">
                            Off&nbsp;<input name="tech_add_standalone_service" type="checkbox" class="switchery-tech-add-standalone-service" <?php echo $setting_details->tech_add_standalone_service == 1 ? 'checked' : '';  ?>>&nbsp;On
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" >
                        <label class="control-label col-lg-5 my_label">Automatically Send Monthly Statement<span data-popup="tooltip-custom" title='When this setting is set to "On", Spraye will send out statements on the 1st of each month to any customers with open invoices.' data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></label>
                        <label class="togglebutton col-lg-7" style="font-size:13px">
                            Off&nbsp;<input name="send_monthly_invoice_statement" type="checkbox" class="switchery-send_monthly_invoice_statement" <?php if(isset($setting_details->send_monthly_invoice_statement)){
                                echo $setting_details->send_monthly_invoice_statement == 1 ? 'checked' : '';
                            }  ?>>&nbsp;On
                        </label>
                    </div>
                </div>
            </div>

            <div class="<?= $block_class ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="checkbox">
                                <label class="checkbox-inline checkbox-right">
                                    <input type="checkbox" name="pay_now_btn" class="styled" <?php echo $setting_details->pay_now_btn == 1 ? 'checked' : '' ?>>
                                    Add a Pay Now option to all invoices
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 btn_url_container" style="display:<?= $setting_details->pay_now_btn == 1 ? 'block' : 'none' ?> ">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Please enter your Pay Link here</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="pay_now_btn_link" placeholder="Please enter your pay link here" value="<?= $setting_details->pay_now_btn_link ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <span style="font-size: 13px;">Note : Only use this option if you are using a credit card processor
                            other than Basys or CloverConnect. If you are using Basys or CloverConnect, do not check this box.
                        </span>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend class="text-bold">Select the fields below that you would like to include on all Invoices to your
                customers.</legend>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_product_name" class="styled" <?php echo $setting_details->is_product_name == 1 ? 'checked' : '' ?>>
                                Product Name
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_epa" class="styled" <?php echo $setting_details->is_epa == 1 ? 'checked' : '' ?>>
                                EPA #
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_active_ingredients" class="styled" <?php echo $setting_details->is_active_ingredients == 1 ? 'checked' : '' ?>>
                                Active Ingredients
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_application_rate" class="styled" <?php echo $setting_details->is_application_rate == 1 ? 'checked' : '' ?>>
                                Application Rate
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_estimated_chemical_used" class="styled" <?php echo $setting_details->is_estimated_chemical_used == 1 ? 'checked' : '' ?>>
                                Estimated Chemical Used
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_chemical_type" class="styled" <?php echo $setting_details->is_chemical_type == 1 ? 'checked' : '' ?>>
                                Chemical Type
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_re_entry_time" class="styled" <?php echo $setting_details->is_re_entry_time == 1 ? 'checked' : '' ?>>
                                Re-Entry Time
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_weed_pest_prevented" class="styled" <?php echo $setting_details->is_weed_pest_prevented == 1 ? 'checked' : '' ?>>
                                Weed/Pest Prevented
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_application_type" class="styled" <?php echo $setting_details->is_application_type == 1 ? 'checked' : '' ?>>
                                Application Type
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_wind_speed" class="styled" <?php echo $setting_details->is_wind_speed == 1 ? 'checked' : '' ?>>
                                Wind Speed
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_wind_direction" class="styled" <?php echo $setting_details->is_wind_direction == 1 ? 'checked' : '' ?>>
                                Wind Direction
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_temperature" class="styled" <?php echo $setting_details->is_temperature == 1 ? 'checked' : '' ?>>
                                Temperature
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_applicator_name" class="styled" <?php echo $setting_details->is_applicator_name == 1 ? 'checked' : '' ?>>
                                Applicator’s Name
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_applicator_number" class="styled" <?php echo $setting_details->is_applicator_number == 1 ? 'checked' : '' ?>>
                                Applicator’s #
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_applicator_phone" class="styled" <?php echo $setting_details->is_applicator_phone == 1 ? 'checked' : '' ?>>
                                Applicator's Contact
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_property_address" class="styled" <?php echo $setting_details->is_property_address == 1 ? 'checked' : '' ?>>
                                Property Address
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_property_size" class="styled" <?php echo $setting_details->is_property_size == 1 ? 'checked' : '' ?>>
                                Property Size
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_date" class="styled" <?php echo $setting_details->is_date == 1 ? 'checked' : '' ?>>
                                Date
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_time" class="styled" <?php echo $setting_details->is_time == 1 ? 'checked' : '' ?>>
                                Time
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="text-right">
            <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
    </form>


<!-- ///////////////////////////////////////// start toggle customer portal //////////////////////////////// -->



            <form class="form-horizontal form2" action="<?= base_url('admin/Admin/updateCustomerPortalPreference') ?>" method="post" name="customerportalsetting" enctype="multipart/form-data">
        <fieldset class="content-group">
            <legend class="text-bold">Customer Portal</legend>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                        <label class="togglebutton col-lg-7" style="font-size:13px">
                            Off&nbsp;<input name="toggle_customer_portal" type="checkbox" class="switchery-customer-portal" <?php echo $setting_details->slug == !null ? 'checked' : '';  ?>>&nbsp;On
                        </label>
                        </div>
                    </div>
                </div>
            </div>

        </fieldset>
        <div class="text-right">
            <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
        </form>

    <!-- ///////////////////////////////////////// end toggle customer portal //////////////////////////////// -->

    <!-- ///////////////////////////////////////// start toggle assign job //////////////////////////////// -->



    <form class="form-horizontal form2" action="<?= base_url('admin/Admin/updateAssignJobPreference') ?>" method="post" name="assignjobsetting" enctype="multipart/form-data">
        <fieldset class="content-group">
            <legend class="text-bold">Default Assign Services View</legend>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                        <label class="togglebutton" style="font-size:13px">
                            Table View&nbsp;<input name="toggle_assign_job" type="checkbox" class="switchery-assign-job" <?php echo $setting_details->assign_job_view == 1 ? 'checked' : '';  ?>>&nbsp;Map View
                        </label>
                        </div>
                    </div>
                </div>
            </div>

        </fieldset>
        <div class="text-right">
            <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
        </form>

    <!-- ///////////////////////////////////////// end toggle assign job //////////////////////////////// -->

    <!-- ///////////////////////////////////////// Invoice fee area //////////////////////////////// -->
    <form class="form-horizontal form2" action="<?= base_url('admin/setting/invoiceFees') ?>" method="post" name="setservicefees" enctype="multipart/form-data">

        <fieldset class="content-group">
            <legend class="text-bold">Invoice Late Fee <span data-popup="tooltip-custom" title="Use this setting to assign late fees for invoices" data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></legend>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Flat Late Fee <span data-popup="tooltip-custom" title="Add a flat fee to the invoice amount" data-placement="top"> <i class=" icon-info22 tooltip-icon"></i></label>

                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-btn"><span class="btn btn-success"><i class="fa fa-usd" aria-hidden="true"></i></span></span>
                                <input type="text" class="form-control" name="late_fee_flat" placeholder="Eg: 25" value="<?= $setting_details->late_fee_flat ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Percentage Late Fee <span data-popup="tooltip-custom" title="Add a percentage of the invoice amount as late fee" data-placement="top"> <i class=" icon-info22 tooltip-icon"></i></label>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-btn"><span class="btn btn-success"><i class="fa fa-percent" aria-hidden="true"></i></span></span>
                                <input type="text" class="form-control" name="late_fee_percent" placeholder="Eg: 10" value="<?= $setting_details->late_fee_percent ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Days past before late fee is added <span data-popup="tooltip-custom" title="Eg: 10 means late fee will be automatically added 10 days past the invoice sent date" data-placement="top"> <i class=" icon-info22 tooltip-icon"></i></label>

                        <div class="col-lg-6">
                            <input type="number" class="form-control valid" name="late_fee_due" placeholder="Eg: 30" value="<?= $setting_details->late_fee_due ?>" aria-invalid="false">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-lg-5 my_label">Monthly recurring late fee <span data-popup="tooltip-custom" title='When this option is set late fee will be recurring every month until paid' data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></label>
                        <label class="togglebutton col-lg-7" style="font-size:13px">
                            Off&nbsp;<input name="late_fee_is_recurring" type="checkbox" class="switchery-late-fee-recurring" <?=$setting_details->late_fee_is_recurring == 1 ? 'checked' : '';?>>&nbsp;On
                        </label>
                    </div>
                </div>
            </div>
        </fieldset>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Invoice Late Fee
                                    <span class="message_type">(email message)</span>
                                    <label class="togglebutton" style="font-size:13px">
                                        Off&nbsp;<input name="late_fee_email_status" type="checkbox" class="switchery-invoice-late-fee" <?php echo $company_email_details->late_fee_email_status == 1 ? 'checked' : '';  ?>>&nbsp;On
                                    </label>
                                </label>
                                <div class="col-lg-9" style="border: 1px solid #12689b;">
                                    <textarea class="summernote" name="late_fee_email"><?php echo $company_email_details->late_fee_email ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                </fieldset>
        <div class="text-right">
            <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
    </form>


    <!-- ///////////////////////////////////////// sales //////////////////////////////// -->
    <div class="row sales_container form2">
        <div class="col-md-12">
            <fieldset class="content-group">
                <legend class="text-bold">Sales Settings</legend>
                <div class="row">

                    <label class="control-label col-lg-3">Source</label>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_source">Add Source</a>
                        </div>
                    </div>
                </div>
                <div class="sourcediv">
                </div>

                <div class="row">
                    <label class="control-label col-lg-3">Service Type</label>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_service_type">Add Service Type</a>
                        </div>
                    </div>
                </div>
                <div class="servicetypediv">
                </div>

                <div class="row">
                            <label class="control-label col-lg-3">Commissions</label>
				</div>
				 <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <?php
                                if(count($all_commission) < 2){
                            ?>
                                <a class="btn btn-success" id="addCommissionButton" data-toggle="modal" data-target="#modal_add_commission">Add Commission</a>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="commissiondiv"></div>
               
                <div class="row">
                	<label class="control-label col-lg-3">Bonus</label>
				</div>
				<div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                        <?php
                            if(count($all_bonuses) < 2){
                        ?>
                            <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_bonus">Add Bonus</a>
                            <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <div class="bonusdiv"></div>

                <!-- <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_secondary_bonus">Add Secondary Bonus</a>
                        </div>
                    </div>
                </div>
                <div class="s_bonusdiv">
                </div> -->

            </fieldset>
        </div>
    </div>
    <!-- ///////////////////////////////////////// end sales//////////////////////////////// -->


    <!-- ///////////////////////////////////////// sales tax area //////////////////////////////// -->

    <div class="row sales_container form2"  >

        <div class="col-md-12">
            <fieldset class="content-group">
                <legend class="text-bold">Sales Tax Areas</legend>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_sale_tax_area">Add Sales Tax
                                Area</a>
                        </div>
                    </div>
                    <div class="col-md-8 text-right">
                        <form class="form-horizontal " action="<?= base_url('admin/setting/updateDefaultSalesTaxArea') ?>" method="post" name="setservicefees" enctype="multipart/form-data">

                            <label class="control-label col-lg-3 col-sm-12 col-xs-12" style="margin-top: 0.2rem">Default Sales Tax Area</label>
                            <div class="multi-select-full col-lg-7  col-sm-10 col-xs-10" style="padding-left: 6px;">
                                <select class="multiselect-select-all-filtering form-control" name="sale_tax_area_id[]" multiple="multiple" id="sales_tax">
                                    <?php if (!empty($sales_tax_details)) {
                                        foreach ($sales_tax_details as $key => $value) {
                                            $checked = '';

                                                ?>
                                            <option value="<?= $value->sale_tax_area_id ?>" <?= ( in_array($value->sale_tax_area_id,  json_decode($setting_details->default_sales_tax_area)))?'selected':'' ?>><?= $value->tax_name  ?> </option>
                                        <?php  } } ?>
                                </select>


                            </div>
                            <button type="submit" class="btn btn-success col-lg-2 col-sm-12 col-xs-12">Submit <i class="icon-arrow-right14 position-right"></i></button>

                        </form>
                    </div>
                </div>

                <div class="texareadiv">
                </div>
            </fieldset>

        </div>
    </div>
    <!-- ///////////////////////////////////////// end sales tax area //////////////////////////////// -->

    

    <!-- ///////////////////////////////////////// service area //////////////////////////////// -->
    <fieldset class="content-group form2">
        <legend class="text-bold">Service Areas</legend>
        <div class="row" style="margin-top: -30px;">
            <div class="col-md-6">
                <div class="form-group">
                    <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_service_area">Add Service Area</a>
                </div>
            </div>
        </div>
        <div class="serviceareadiv">
        </div>
    </fieldset>
    <!-- ///////////////////////////////////////// end service area //////////////////////////////// -->
    <!-- ///////////////////////////////////////// property conditions //////////////////////////////// -->
    <fieldset class="content-group form2">
        <legend class="text-bold">Property Conditions</legend>
        <div class="row" style="margin-top: -30px;">
            <div class="col-md-6">
                <div class="form-group">
                    <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_property_conditions">Add Property Condition</a>
                </div>
            </div>
        </div>
        <div class="property-conditions-container">
           <div  class="table-responsive spraye-table">
             <table  class="table datatable-basic dataTable table-spraye" id="property-conditions-table" style="border:1px solid #6eb1fd; border-radius:4px;">
                  <thead>
                      <tr>
                          <th>Condition ID</th>
                          <th>Condition Name</th>
                          <th>Message</th>
                          <th>Include in Service Completion Email</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php if(!empty($property_conditions)){foreach($property_conditions as $value){?>
                      <tr>
                          <td><a onclick="editPropertyCondition(<?= $value->property_condition_id ?>)" data-toggle="modal" data-target="#modal_edit_property_condition" id="#edit_property_condition"><?= $value->property_condition_id ?></a></td>
                          <td><?= $value->condition_name ?></td>
                          <td><?= $value->message ?></td>
                          <td><?php if(isset($value->in_email) && $value->in_email == 1){echo "Yes";}else{echo "No";} ?></td>
                          <td>
                              <ul style="list-style-type:none; padding-left:0px;">
                                  <li style="display:inline; padding-right:10px;">
                                       <a class="button-next" onclick="editPropertyCondition(<?= $value->property_condition_id ?>)" data-toggle="modal" data-target="#modal_edit_property_condition"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                                    </li>
                                    <li style="display: inline; padding-right: 10px;">
                                        <a  class="button-next" onclick="deletePropertyCondition(<?= $value->property_condition_id ?>)"><i class="icon-trash position-center" style="color: #9a9797;"></i></a>
                                    </li>
                              </ul>
                          </td>
                      </tr>
                      <?php } } ?>
                  </tbody>
              </table>
           </div>
        </div>
    </fieldset>
    <!-- ///////////////////////////////////////// end property conditions //////////////////////////////// -->
    <!-- ///////////////////////////////////////// coupon //////////////////////////////// -->
    <fieldset class="content-group form2">
        <legend class="text-bold">Coupons</legend>
        <div class="row" style="margin-top: -30px;">
            <div class="col-md-6">
                <div class="form-group">
                    <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_coupon" id="add_coupon_first_btn">Add Coupon</a>
                </div>
            </div>
        </div>
        <div class="coupondiv">
        </div>
    </fieldset>
    <!-- ///////////////////////////////////////// end coupon //////////////////////////////// -->

  <!-- ///////////////////////////////////////// Tags //////////////////////////////// -->
  <fieldset class="content-group form2">
        <legend class="text-bold">Custom Tags</legend>
        <div class="row" style="margin-top: -30px;">
            <div class="col-md-6">
                <div class="form-group">
                    <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_tag" id="add_tag_first_btn" >Add Tags</a>
                </div>
            </div>
        </div>
        <div class="tagdiv">
        </div>
    </fieldset>
    <!-- ///////////////////////////////////////// end Tags //////////////////////////////// -->

    <!-- ///////////////////////////////////////// service fees area //////////////////////////////// -->
    <form class="form-horizontal form2" action="<?= base_url('admin/setting/setServiceFees') ?>" method="post" name="setservicefees" enctype="multipart/form-data">

        <fieldset class="content-group">
            <legend class="text-bold">Service Fees</legend>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Base Service Fee</label>

                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-btn"><span class="btn btn-success"><i class="fa fa-usd" aria-hidden="true"></i></span></span>
                                <input type="text" class="form-control" name="base_service_fee" placeholder="Base Fee" value="<?= $setting_details->base_service_fee ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Minimum Service Fee</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-btn"><span class="btn btn-success"><i class="fa fa-usd" aria-hidden="true"></i></span></span>
                                <input type="text" class="form-control" name="minimum_service_fee" placeholder="Mininum Fee" value="<?= $setting_details->minimum_service_fee ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="text-right">
            <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
    </form>

    <!-- ///////////////////////////////////////// end service fees area //////////////////////////////// -->

    <!-- ///////////////////////////////////////// property difficulty area //////////////////////////////// -->
    <form class="form-horizontal form2" action="<?= base_url('admin/setting/setDifficultyMultipliers') ?>" method="post" name="setservicefees" enctype="multipart/form-data">
        <fieldset class="content-group">
            <legend class="text-bold">Difficulty Level Price Multipliers<label class="togglebutton" style="font-size:13px">&nbsp;<span data-popup="tooltip-custom" title="Level 1 will automatically use standard pricing. For levels 2 and 3, please enter the multiplier you'd like to use for per square ft invoice price -- 1.5 = charge 1.5 times your normal rate | 2.0 = charge 2 times your normal rate." data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></label></legend>
            <div class="row">
                <div class="col-md-6">
                    <div class="property_difficulty_price_multiplier" style="display: block;">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Difficulty Level</th>
                                        <th>Price Multiplier</th>
                                    </tr>
                                </thead>
                                <tbody class="propery_difficulty_body">
                                    <tr>
                                        <td>Level 1</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="dlmult_1" class="form-control" placeholder="Level 1 multiplier" readonly value=1.0>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Level 2</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="dlmult_2" min="0" class="form-control" step="0.00001" placeholder="Level 2 multiplier" value=<?= $setting_details->dlmult_2 ?>>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Level 3</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="dlmult_3" min=0 class="form-control" step="0.00001" placeholder="Level 3 multiplier" value=<?= $setting_details->dlmult_3 ?>>

                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="text-right">
            <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
    </form>
    <!-- ///////////////////////////////////////// end property difficulty area //////////////////////////////// -->

    <!-- ///////////////////////////////////////// notes area //////////////////////////////// -->
    <form class="form-horizontal form2" action="<?= base_url('admin/setting/updateCompanyNoteSetting') ?>" method="post" name="notesetting" enctype="multipart/form-data">
        <fieldset class="content-group">
            <legend class="text-bold">Enhanced Notes Settings</legend>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_tech_customer_note_required" class="styled" <?php echo $setting_details->is_tech_customer_note_required == 1 ? 'checked' : ''; ?> value="1">
                                Require Technician Customer Notes
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="checkbox-inline checkbox-left">
                                <input type="checkbox" name="is_tech_vehicle_inspection_required" class="styled" <?php echo $setting_details->is_tech_vehicle_inspection_required == 1 ? 'checked' : ''; ?> value="1">
                                Require Technician Vehicle Inspection Notes
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </fieldset>
        <div class="text-right">
            <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
    </form>

    <div class="row form2">
        <div class="col-md-12">
            <fieldset class="content-group">
                <legend class="text-bold">Note Types</legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_notetype">Add Note Type</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive table-spraye">
                            <table id="datatable_notetypes" class="table datatable-basic2" style="border: 1px solid #6eb1fd; border-radius: 4px;">
                                <thead>
                                    <tr>
                                        <th>Type Name</th>
                                        <th class="text-right" style="width:15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="notetype_row_1">
                                        <td>
                                            Task
                                        </td>
                                        <td class="text-right" style="width:15%">
                                            N/A
                                        </td>
                                    </tr>
                                    <tr id="notetype_row_2">
                                        <td>
                                            Vehicle General
                                        </td>
                                        <td class="text-right" style="width:15%">
                                            N/A
                                        </td>
                                    </tr>
                                    <tr id="notetype_row_3">
                                        <td>
                                            Vehicle Maintenance
                                        </td>
                                        <td class="text-right" style="width:15%">
                                            N/A
                                        </td>
                                    </tr>
									<tr id="notetype_row_4">
                                        <td>
                                            Service-Specific
                                        </td>
                                        <td class="text-right" style="width:15%">
                                            N/A
                                        </td>
                                    </tr>
                                <?php 
                                    if (!empty($note_types)) 
                                    { 
                                        foreach ($note_types as $type) 
                                        {
                                            if($type->type_id > 3 && $type->type_name != "Service-Specific")
                                            {
                                ?>

                                    <tr id="notetype_row_<?= $type->type_id; ?>">
                                        <td>
                                            <?= $type->type_name; ?>
                                        </td>
                                        <td class="text-right" style="width:15%">
                                            <ul  style="list-style-type: none; padding-left: 0px;">
                                                <li style="display: inline; padding-right: 10px;">
                                                    <a class="button-next" onclick="editNoteType(<?= $type->type_id; ?>,'<?= $type->type_name; ?>')" data-toggle="modal" data-target="#modal_edit_notetype" ><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                                                </li>

                                                <li style="display: inline; padding-right: 10px;">
                                                    <a class="button-next" onclick="deleteNoteType(<?= $type->type_id; ?>)" ><i class="icon-trash position-center" style="color: #9a9797;"></i></a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>

                                <?php
                                            }
                                        }
                                    }
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <!-- ///////////////////////////////////////// end notes  area //////////////////////////////// -->
<!-- Crud table cancel reasons -->
	<div class="row form2">
        <div class="col-md-12">
            <fieldset class="content-group">
                <legend class="text-bold">Cancel Reasons</legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_cancelreason">Add Cancel Reason</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive table-spraye">
                            <table id="datatable_cancelreasons" class="table datatable-basic2" style="border: 1px solid #6eb1fd; border-radius: 4px;">
                                <thead>  
                                    <tr>
                                        <th>Cancel Reason</th>                
                                        <th class="text-right" style="width:15%">Action</th>
                                    </tr>  
                                </thead>
                                <tbody>                                    
                                <?php if (!empty($cancel_reasons)){ 
                                        foreach($cancel_reasons as $reason) {?>
                                    <tr>
                                        <td>
                                        <?= $reason->cancel_name; ?>
                                        </td>
                                        <td class="text-right" style="width:15%">
                                            <ul  style="list-style-type: none; padding-left: 0px;">
                                                <li style="display: inline; padding-right: 10px;">
                                                    <a href="#" class="edit-cancel-reason" onclick="editCancelReason(<?= $reason->cancel_id ?>,'<?=$reason->cancel_name?>')" data-cancelid="<?= $reason->cancel_id; ?>"><i class="icon-pencil position-center" title="Edit Cancel Reason" style="color: #9a9797;"></i></a>
                                                </li>
                                                <li style="display: inline; padding-right: 10px;">
                                                	<a href="#" class="delete-cancel-reason" onclick="deleteCancelReason(<?= $reason->cancel_id ?>,'<?=$reason->cancel_name?>')"><i class="icon-trash position-center" title="Delete Cancel Reason" style="color: #9a9797;"></i></a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php  } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>    
    <!-- / end crud cancel reasons  area / -->
    <!-- Crud table cancel reasons -->
    <div class="row form2">
        <div class="col-md-12">
            <fieldset class="content-group">
                <legend class="text-bold">Reschedule Reasons</legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_reschedulereason">Add Reschedule Reason</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive table-spraye">
                            <table id="datatable_reschedulereasons" class="table datatable-basic2" style="border: 1px solid #6eb1fd; border-radius: 4px;">
                                <thead>
                                <tr>
                                    <th>Reschedule Reason</th>
                                    <th class="text-right" style="width:15%">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php if (!empty($reschedule_reasons)){
                                    foreach($reschedule_reasons as $reason) {?>
                                        <tr>
                                            <td>
                                                <?= $reason->reschedule_name; ?>
                                            </td>
                                            <td class="text-right" style="width:15%">
                                                <ul  style="list-style-type: none; padding-left: 0px;">
                                                    <li style="display: inline; padding-right: 10px;">
                                                        <a href="#" class="edit-cancel-reason" onclick="editRescheduleReason(<?= $reason->reschedule_id ?>,'<?=$reason->reschedule_name?>')" data-cancelid="<?= $reason->reschedule_id; ?>"><i class="icon-pencil position-center" title="Edit Cancel Reason" style="color: #9a9797;"></i></a>
                                                    </li>
                                                    <li style="display: inline; padding-right: 10px;">
                                                        <a href="#" class="delete-cancel-reason" onclick="deleteRescheduleReason(<?= $reason->reschedule_id ?>,'<?=$reason->reschedule_name?>')"><i class="icon-trash position-center" title="Delete Cancel Reason" style="color: #9a9797;"></i></a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php  } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <!-- / end crud cancel reasons  area / -->
    <form class="form-horizontal form2" action="<?= base_url('admin/setting/setSignwellAPI') ?>" method="post" name="setsignwellapi" enctype="multipart/form-data">
        <fieldset class="content-group">
            <legend class="text-bold">SignWell API</legend>
            <a href="https://www.signwell.com/?via=spraye" target="_blank"><button type="button" class="btn btn-success btn-rounded">Click here to sign up for e-signatures with Signwell.</button></a>
            <br /><br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Your API Key</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="signwell_api_key" placeholder="API Key" value="<?= $setting_details->signwell_api_key ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="text-right">
            <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
    </form>
</div>
<!-- /form horizontal -->
</div>

<!-- Primary modal -->
<div id="modal_add_notetype" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add New Type</h6>
            </div>
            <form action="<?= base_url('admin/createNoteType'); ?>" method="post" id="add_notetype_form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">

                                <div style="color: red;" id="add_notetype_form_errors"></div>

                                <label for="notetype_name">Type Name</label>
                                <input type="text" class="form-control" name="notetype_name" placeholder="" id="notetype_name">
                                <input type="hidden" name="company_id" value="<?= $company_id; ?>">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" id="add_notetype_submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_edit_notetype" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Edit Type</h6>
            </div>
            <form action="<?= base_url('admin/editNoteType'); ?>" method="post" id="edit_notetype_form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">

                                <div style="color: red;" id="edit_notetype_form_errors"></div>

                                <label for="edit_type_name">Type Name</label>
                                <input type="text" class="form-control" name="edit_type_name" placeholder="" id="edit_type_name">
                                <input type="hidden" id="edit_type_id" name="edit_type_id">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" id="edit_notetype_submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->load->view('partials/add_property_conditions_modal') ?>
<?= $this->load->view('partials/edit_property_conditions_modal') ?>
</div> <!--end content -->
<div id="modal_add_coupon" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Coupon</h6>
            </div>
            <!--<form action="<?= base_url('admin/setting/addCouponData') ?>" method="post" name="addcoupon"
        enctype="multipart/form-data" form_ajax="ajax" id="new_coupon_form">-->
            <form action="" method="post" id="new_coupon_form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">

                                <div style="color: red;" id="add_coupon_form_errors"></div>

                                <label>Coupon Code</label>
                                <input type="text" class="form-control" name="coupon_code" placeholder="Coupon Code" id="coupon_code_input">

                                <label style="margin-top: 10px;">Amount</label>
                                <input type="text" class="form-control" name="coupon_amount" placeholder="Amount" id="coupon_amount_input">

                                <label style="margin-top: 10px;">Amount Type</label>
                                <select name="coupon_amount_type" class="form-control" id="coupon_amount_type_input">
                                    <option value="">Select Amount Type</option>
                                    <option value="0">Fixed Amount Off</option>
                                    <option value="1">Percentage Discount</option>
                                </select>

                                <label style="margin-top: 10px;">Coupon Type</label>
                                <select name="coupon_type" class="form-control" id="coupon_type_input">
                                    <option value="">Select Coupon Type</option>
                                    <option value="0">One Time</option>
                                    <option value="1">Permanent</option>
                                </select>

                                <label style="margin-top: 10px;">Description</label>
                                <textarea class="form-control" name="coupon_description" rows="4" placeholder="A short coupon description" required></textarea>


                                <label style="margin-top: 10px;">Expiration Date (leave blank for no expiration)</label>
                                <input type="date" id="coupon_expire_date_input" name="coupon_expire_date" class="form-control pickaalldate" placeholder="MM-DD-YYYY">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="savearea" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_edit_coupon" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Update Coupon</h6>
            </div>
            <form action="" method="post" id="edit_coupon_form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">

                                <div style="color: red;" id="update_coupon_form_errors"></div>

                                <label>Coupon Code</label>
                                <input type="text" class="form-control" name="coupon_code" placeholder="Coupon Code" id="coupon_code_input_edit">

                                <label style="margin-top: 10px;">Amount</label>
                                <input type="text" class="form-control" name="coupon_amount" placeholder="Amount" id="coupon_amount_input_edit">

                                <label style="margin-top: 10px;">Amount Type</label>
                                <select name="coupon_amount_type" class="form-control" id="coupon_amount_type_input_edit">
                                    <option value="">Select Amount Type</option>
                                    <option value="0">Fixed Amount Off</option>
                                    <option value="1">Percentage Discount</option>
                                </select>

                                <label style="margin-top: 10px;">Coupon Type</label>
                                <select name="coupon_type" class="form-control" id="coupon_type_input_edit">
                                    <option value="">Select Coupon Type</option>
                                    <option value="0">One Time</option>
                                    <option value="1">Permanent</option>
                                </select>

                                <label style="margin-top: 10px;">Description</label>
                                <textarea class="form-control" name="coupon_description" id="coupon_description_input_edit" rows="4" placeholder="A short coupon description" ></textarea>

                                <label style="margin-top: 10px;">Expiration Date (leave blank for no expiration)</label>
                                <input type="date" id="coupon_expire_date_input_edit" name="coupon_expire_date" class="form-control pickaalldate" placeholder="MM-DD-YYYY">

                                <input type="hidden" name="coupon_id" id="coupon_edit_id_from_form" value="">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="savearea" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Cancel Reason Modal -->
<div id="modal_add_cancelreason" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add New Cancel Reason</h6>
            </div>
            <form action="<?= base_url('admin/createCancelReason'); ?>" method="post" id="add_cancelreason_form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">

                               

                                <label for="cancel_reason_name">Cancel Reason Name</label>
                                <input type="text" class="form-control" name="cancel_name" 
                                 placeholder="" id="cancel_name">
                               
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Add Cancel Reason modal/ -->
<!-- Edit Cancel Reason modal -->
<div id="modal_edit_cancelreason" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Edit Cancel Reason</h6>
            </div>
            <form action="<?= base_url('admin/editCancelReason'); ?>" method="post" id="edit_cancelreason_form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">

                                <label for="edit_cancel_name">Edit Cancel Reason Below</label>
                                <input type="text" class="form-control" name="edit_cancel_name" id="edit_cancel_name">
                                <input type="hidden" id="edit_cancel_id" name="edit_cancel_id" value="">
                         
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Edit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Edit Cancel Reason modal/ -->
<!-- Delete Cancel Reason modal -->
<div id="modal_delete_cancelreason" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Delete Cancel Reason</h6>
            </div>
            <form action="<?= base_url('admin/deleteCancelReason'); ?>" method="post" id="delete_cancelreason_form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">

                                <label for="delete_cancel_name">Are You Sure You Want To Delete The Cancel Reason Below?</label>
                                <input type="text" class="form-control" name="delete_cancel_name" id="delete_cancel_name" value="" readonly >
                                <input type="hidden" class="form-control" name="delete_cancel_id" id="delete_cancel_id" value="<?php echo ($reason->cancel_id); ?>">
                         
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Delete Cancel Reason modal -->
<!-- Add reschedule Reason Modal -->
<div id="modal_add_reschedulereason" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add New Reschedule Reason</h6>
            </div>
            <form action="<?= base_url('admin/createRescheduleReason'); ?>" method="post" id="add_reschedulereason_form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">



                                <label for="reschedule_name">Reschedule Name</label>
                                <input type="text" class="form-control" name="reschedule_name"
                                       placeholder="" id="reschedule_name">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Add reschedule Reason modal/ -->
<!-- Edit reschedule Reason modal -->
<div id="modal_edit_reschedulereason" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Edit Reschedule Reason</h6>
            </div>
            <form action="<?= base_url('admin/editRescheduleReason'); ?>" method="post" id="edit_reschedulereason_form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">

                                <label for="edit_reschedule_name">Edit Reschedule Reason Below</label>
                                <input type="text" class="form-control" name="edit_reschedule_name" id="edit_reschedule_name">
                                <input type="hidden" id="edit_reschedule_id" name="edit_reschedule_id" value="">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Edit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Edit reschedule Reason modal/ -->
<!-- Delete reschedule Reason modal -->
<div id="modal_delete_reschedulereason" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Delete Cancel Reason</h6>
            </div>
            <form action="<?= base_url('admin/deleteRescheduleReason'); ?>" method="post" id="delete_reschedulereason_form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">

                                <label for="delete_reschedule_name">Are You Sure You Want To Delete The Cancel Reason Below?</label>
                                <input type="text" class="form-control" name="delete_reschedule_name" id="delete_reschedule_name" value="" readonly >
                                <input type="hidden" class="form-control" name="delete_reschedule_id" id="delete_reschedule_id" value="<?php echo ($reason->reschedule_id); ?>">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Delete reschedule Reason modal -->
<!-- Add Tag Modal -->
<div id="modal_add_tag" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Tag</h6>
            </div>
            <!--<form action="<?= base_url('admin/setting/addCouponData') ?>" method="post" name="addcoupon"
        enctype="multipart/form-data" form_ajax="ajax" id="new_coupon_form">-->
            <form action="" method="post"  id="new_tag_form" required>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">

                                <div style="color: red;" id="add_tag_form_errors"></div>

                                <label>Tag <span class="requesecolo">*</span></label>
                                <input type="text" class="form-control" autocomplete="off" name="tags_title" maxlength="30" placeholder="Tag" id="tags_input" required>
                                <span style="color:red;"><?php echo form_error('tags_title'); ?></span>
                              

                            </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group checkbox">
                                    <input id="include_in_tech_view" type="checkbox" name="include_in_tech_view" class="checkbox text-left">
                                    <label class="requesecolo">Include in Technician View</label>
                                </div>
                            </div>       
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="savearea" class="btn btn-success">Save</button>
                        <button type="button" id="btn_tag_close" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- / Add Tag Modal -->
<!-- Edit Tag Modal -->
<div id="modal_edit_tag" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Update Tag</h6>
            </div>
            <form action="" method="post" id="edit_tag_form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">

                                <div style="color: red;" id="update_tag_form_errors"></div>

                                <label>Tag</label>
                                <input id="tag_edit_tags_title" type="text" maxlength="30"  onkeypress="return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32)&& (event.charCode != 86)&& (event.charCode != 17))"  class="form-control" name="tags_title" placeholder="Tag" >

                                <input id="tag_edit_id" type="hidden" name="id"  value="">

                            </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                                <div class="checkbox">
                                    <label class="checkbox-inline checkbox-left">
                                        <input id="include_in_tech_view" type="checkbox" name="include_in_tech_view" class="styled">
                                        Include in Technician View
                                    </label>
                                </div>
                            </div>
                        </div>    
                    </div>
                    <div class="modal-footer">
                         <button type="submit" id="save" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- / Edit Tag Modal -->	
<script>
$(document).ready(function() {	
	$("#add_tag_first_btn").click(function() {	
		$("#tags_input").val('');	
		$("#include_in_tech_view").val('');	
//  $('.modal-backdrop').css("display", "unset");	
		$("#modal_add_tag").css("display", "unset");	
	});	
	$("#new_tag_form").submit(function() {	
		$("#loading").css("display", "block");	
		$.ajax({	
			url: "<?= base_url('admin/setting/addTagsData') ?>",	
			data: $("#new_tag_form").serialize(),	
			type: "POST",	
			dataType: 'json',	
			success: function(e) {	
				$("#loading").css("display", "none");	
				if (e != 0 && e != 1) {	
					document.querySelector('#add_tag_form_errors').innerHTML = e;	
				} else {	
					$("#btn_tag_close").click();	
					//  $('.modal-backdrop').css("display", "none");	
					getTagList();	
					swal(	
					'Tag',	
					'Added Successfully ',	
					'success'	
					);	
				}	
			},	
			error: function(e) {	
				$("#modal_add_tag").css("display", "none");	
				$('.modal-backdrop').css("display", "none");	
				getTagList();	
				//alert("Please Enter Alphabets");	
			}	
		});	
		return false;	
	});	
	$("#edit_tag_form").submit(function() {	
		$("#loading").css("display", "block");	
		$.ajax({	
			url: "<?= base_url('admin/setting/editTagData') ?>",	
			data: $("#edit_tag_form").serialize(),	
			type: "POST",	
			dataType: 'json',	
			success: function(e) {	
			$("#loading").css("display", "none");	
			if (e != 0 && e != 1) {	
				document.querySelector('#update_edit_form_errors').innerHTML = e;	
			} else {	
				$("#modal_edit_tag").css("display", "none");	
				$('.modal-backdrop').css("display", "none");	
				getTagList();	
				swal(	
					'Tag',	
					'Updated Successfully ',	
					'success'	
				)	
			}	
		},	
		error: function(e) {	
			$("#loading").css("display", "none");	
				alert("Please Enter Alphabets");	
			}	
		});	
		return false;	
	});	
});	
window.onload = () => {	
const tags_input = document.getElementById('tags_input');	
tags_input.onpaste = e => e.preventDefault();	
}	
</script>
<script>
    $(document).ready(function() {
        $("#add_coupon_first_btn").click(function() {
            $('.modal-backdrop').css("display", "unset");
            $("#modal_add_coupon").css("display", "unset");

            $("#coupon_code_input").val('');
            $("#coupon_amount_input").val('');
            $("#coupon_amount_type_input").val('');
            $("#coupon_type_input").val('');
            $("#coupon_expire_date_input").val('');
        });
        $("#new_coupon_form").submit(function() {
            $("#loading").css("display", "block");
            $.ajax({
                url: "<?= base_url('admin/setting/addCouponData') ?>",
                data: $("#new_coupon_form").serialize(),
                type: "POST",
                dataType: 'json',
                success: function(e) {

                    $("#loading").css("display", "none");
                    if (e != 0 && e != 1) {
                        document.querySelector('#add_coupon_form_errors').innerHTML = e;
                    } else {
                        $("#modal_add_coupon").css("display", "none");
                        $('.modal-backdrop').css("display", "none");
                        getCouponList();
                        swal(
                            'Coupon',
                            'Added Successfully ',
                            'success'
                        )
                    }
                },
                error: function(e) {
                    $("#loading").css("display", "none");
                    alert("Something went wrong");
                }
            });
            return false;
        });
        $("#edit_coupon_form").submit(function() {
            $("#loading").css("display", "block");
            $.ajax({
                url: "<?= base_url('admin/setting/editCouponData') ?>",
                data: $("#edit_coupon_form").serialize(),
                type: "POST",
                dataType: 'json',
                success: function(e) {
                    $("#loading").css("display", "none");
                    if (e != 0 && e != 1) {
                        document.querySelector('#update_coupon_form_errors').innerHTML = e;
                    } else {
                        $("#modal_edit_coupon").css("display", "none");
                        $('.modal-backdrop').css("display", "none");
                        getCouponList();
                        swal(
                            'Coupon',
                            'Updated Successfully ',
                            'success'
                        )
                    }
                },
                error: function(e) {
                    $("#loading").css("display", "none");
                    alert("Something went wrong");
                }
            });
            return false;
        });
    });
</script>
<!-- /primary modal -->

<!-- Primary modal -->
<div id="modal_add_service_area" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Service Area</h6>
            </div>
            <form action="<?= base_url('admin/setting/addServicrAreaData') ?>" method="post" name="addservicearea" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Service Area</label>
                                <input type="text" class="form-control" name="category_area_name" placeholder="Service Area">
                            </div>
                            <div class="col-sm-12">
                                <label>Description</label>
                                <textarea type="text" class="form-control" name="category_description" placeholder="Short description..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="savearea" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary modal -->
<!-- Primary modal -->
<div id="modal_request_for_basys" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Request a BASYS Account</h6>
            </div>
            <form action="<?= base_url('admin/setting/requsetForBasys') ?>" method="post" name="basysrequest" enctype="multipart/form-data">
                <div class="modal-body">
                    <p>Please complete the form below to request an account with our credit card processing partner, BASYS. After
                        you submit the form, you should expect a call from BASYS to start your account setup process.</p>
                    <h6 class="text-semibold">Company Details</h6>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-md-6 ">
                                <label>Company Name</label>
                                <input type="text" class="form-control" name="company_name" readonly="" value="<?= $setting_details->company_name ?>">
                            </div>
                            <div class="col-sm-6 col-md-6 ">
                                <label>Company Email</label>
                                <input type="text" class="form-control" name="company_email" readonly="" value="<?= $setting_details->company_email ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 ">
                                <label>Company Address</label>
                                <input type="text" class="form-control" name="company_address" readonly="" value="<?= $setting_details->company_address ?>">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6 class="text-semibold">User Details</h6>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-md-6 ">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="user_first_name" readonly="" value="<?= $user_details->user_first_name ?>">
                            </div>
                            <div class="col-sm-6 col-md-6 ">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="user_last_name" readonly="" value="<?= $user_details->user_last_name ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-md-6 ">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" readonly="" value="<?= $user_details->email ?>">
                            </div>
                            <div class="col-sm-6 col-md-6 ">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" name="phone" readonly="" value="<?= $user_details->phone ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="savearea" class="btn btn-success">Send Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary modal -->
<!-- Source modal -->
<div id="modal_add_source" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Add Source</h6>
                </div>
                <form action="<?= base_url('admin/setting/addSourceData') ?>" method="post" name="addsource" enctype="multipart/form-data" form_ajax="ajax">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Source</label>
                                    <input type="text" class="form-control" name="source_name" placeholder="Source Name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Source Type</label>
                                    <input type="text" class="form-control" name="source_type" placeholder="Source Type">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" id="savesource" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- /source modal -->
<!-- Service Type modal -->
<div id="modal_add_service_type" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Add Service Type</h6>
                </div>
                <form action="<?= base_url('admin/setting/addServiceTypeData') ?>" method="post" name="addservicetype" enctype="multipart/form-data" form_ajax="ajax">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Service Type Name</label>
                                    <input type="text" class="form-control" name="service_type_name" placeholder="Service Type Name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Service Type</label>
                                    <!-- <input type="text" class="form-control" name="service_type" placeholder="Service Type"> -->
                                      <select class="bootstrap-select  form-control" id="service_type" name="service_type" placeholder="Commission Type">
                                        <option value="">Select Type</option>
                                        <option value="1">Primary</option>
                                        <option value="2">Secondary</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" id="saveservicetype" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- /service type modal -->
<!-- Primary Commission modal -->
<div id="modal_add_commission" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Commission</h6>
            </div>
            <form action="<?= base_url('admin/setting/addCommissionData') ?>" method="post" name="addcommission" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Commission</label>
                                <input type="text" class="form-control" name="commission_name" placeholder="Commission Name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Commission Percentage</label>
                                <input type="number" class="form-control" name="commission_value" placeholder="Commission Percentage">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Commission Type</label>
                                <!-- <input type="number" class="form-control" name="commission_type" placeholder="Commission Type"> -->
                                    <select class="selectpicker form-control" id="add_commission_type" name="commission_type" placeholder="Commission Type">
                                        <option value="">Select Type</option>
                                        <option value="1" id="add_commission_type_primary">Primary</option>
                                        <option value="2" id="add_commission_type_secondary">Secondary</option>
                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="savecommission" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary commission modal -->
<!-- Bonus modal -->
<div id="modal_add_bonus" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Bonus</h6>
            </div>
            <form action="<?= base_url('admin/setting/addBonusData') ?>" method="post" name="addbonus" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Bonus</label>
                                <input type="text" class="form-control" name="bonus_name" placeholder="Bonus Name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Bonus Percentage</label>
                                <input type="number" class="form-control" name="bonus_value" placeholder="Bonus Percentage">
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Bonus Type</label>
                                <!-- <input type="number" class="form-control" name="commission_type" placeholder="Commission Type"> -->
                                    <select class="bootstrap-select  form-control" id="bonus_type" name="bonus_type" placeholder="Bonus Type">
                                        <option value="">Select Type</option>
                                        <option value="1">Primary</option>
                                        <option value="2">Secondary</option>
                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="saveprimarybonus" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary bonus modal -->
<!-- Primary modal -->
<div id="modal_add_sale_tax_area" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Sales Tax Area</h6>
            </div>
            <form action="<?= base_url('admin/setting/addSalesTaxAreaData') ?>" method="post" name="addsalestexarea" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Sales Tax Area Name</label>
                                <input type="text" class="form-control" name="tax_name" placeholder="Sales Tax Area Name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Sales Tax Area Percentage</label>
                                <input type="number" class="form-control" name="tax_value" placeholder="Sales Tax Area Percentage">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="savearea" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary modal -->
<!-- end service area model  -->
<!-- Primary modal -->
<div id="modal_edit_service_area" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Update Service Area</h6>
            </div>
            <form action="<?= base_url('admin/setting/editServicrAreaData') ?>" method="post" name="editservicearea" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Service Area</label>
                                <input type="text" class="category_area_name form-control" name="category_area_name" placeholder="Service Area" value="">
                            </div><div class="col-sm-12">
                                <label>Description</label>
                                <textarea type="text" class="category_description form-control" name="category_description" placeholder="Short description" value=""></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="property_area_cat_id" class="property_area_cat_id" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="savearea" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- update source modal -->
<div id="modal_source" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Update Source</h6>
                </div>
                <form action="<?= base_url('admin/setting/editSourceData') ?>" method="post" name="editsource" enctype="multipart/form-data" form_ajax="ajax">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Source Name</label>
                                    <input type="text" class="form-control" name="source_name" id="source_name" placeholder="Source Name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Source Type</label>
                                    <input type="text" class="form-control" name="source_type" id="source_type" placeholder="Source Type">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="source_id" id="source_id" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" id="savesource" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- /update source modal -->
<!-- update service type modal -->
    <div id="modal_service_type" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Update Service Type</h6>
                </div>
                <form action="<?= base_url('admin/setting/editServiceTypeData') ?>" method="post" name="editservicetype" enctype="multipart/form-data" form_ajax="ajax">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Service Type Name</label>
                                    <input type="text" class="form-control" name="service_type_name" id="service_type_name" placeholder="Service Type Name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Service Type</label>
                                    <!-- <input type="text" class="form-control" name="service_type" id="service_type" placeholder="Service Type"> -->
                                    <select class="bootstrap-select  form-control" id="service_type" name="service_type" placeholder="Service Type">
                                        <option value="">Select Type</option>
                                        <option value="1">Primary</option>
                                        <option value="2">Secondary</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="service_type_id" id="service_type_id" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" id="saveservicetype" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- /update service type modal -->
<!-- update commission modal -->
<div id="modal_commission" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Update Commission</h6>
            </div>
            <form action="<?= base_url('admin/setting/editCommissionData') ?>" method="post" name="editcommission" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Commission Name</label>
                                <input type="text" class="form-control" name="commission_name" id="commission_name" placeholder="Commission Name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Commission Percentage</label>
                                <input type="number" class="form-control" name="commission_value" id="commission_value" placeholder="Commission Percentage" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 commission_select">
                                <label>Commission Type</label>
                                <!-- <input type="number" class="form-control" name="commission_percentage" id="commission_percentage" placeholder="Commission Percentage"> -->
                                  <select class="selectpicker form-control" id="commission_type" name="commission_type" placeholder="Commission Type">
                                        <option value="">Select Type</option>
                                        <option value="1" id="edit_commission_type_primary">Primary</option>
                                        <option value="2" id="edit_commission_type_secondary">Secondary</option>
                                    </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="commission_id" id="commission_id" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="savescommission" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /update  commission modal -->
<!-- update bonus modal -->
<div id="modal_bonus" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Update Bonus</h6>
            </div>
            <form action="<?= base_url('admin/setting/editBonusData') ?>" method="post" name="editbonus" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Bonus Name</label>
                                <input type="text" class="form-control" name="bonus_name" id="bonus_name" placeholder="Bonus Name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 ">
                                <label>Bonus Percentage</label>
                                <input type="number" class="form-control" name="bonus_value" id="bonus_value" placeholder="Bonus Percentage">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 bonus_select">
                                <label>Bonus Type</label>
                                <!-- <input type="number" class="form-control" name="commission_percentage" id="commission_percentage" placeholder="Commission Percentage"> -->
                                  <select class="form-control" id="bonus_type" name="bonus_type" placeholder="Bonus Type">
                                        <option value="">Select Type</option>
                                        <option value="1">Primary</option>
                                        <option value="2">Secondary</option>
                                    </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="bonus_id" id="bonus_id" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="savescommission" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /update  bonus modal -->
<!-- /primary modal -->
<div id="modal_sales_tax_area" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Update Sales Tax Area</h6>
            </div>
            <form action="<?= base_url('admin/setting/editSalesTaxAreaData') ?>" method="post" name="editsalestexarea" enctype="multipart/form-data" form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Sales Tax Area Name</label>
                                <input type="text" class="form-control" name="tax_name" id="tax_name" placeholder="Sales Tax Area Name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Sales Tax Area Percentage</label>
                                <input type="number" class="form-control" name="tax_value" id="tax_value" placeholder="Sales Tax Area Percentage">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="sale_tax_area_id" id="sale_tax_area_id" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="savearea" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary modal -->
<div id="modal_smtp_info" class="modal fade" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">How To Enable Email Sending In Gmail?</h5>
            </div>
            <div class="modal-body">
                <p>1. Before sending emails using the Gmail's SMTP Server, you to make some of the security and permission level
                    settings under your <a class="text-semibold" href="https://myaccount.google.com/security" target="_blank">Google Account Security Settings.</a></p>
                <p>2. Make sure that <b>2-Step-Verification</b> is disabled.</p>
                <p>3. Turn ON the "Less Secure App" access or click <a class="text-semibold" href="https://myaccount.google.com/u/0/lesssecureapps" target="_blank">here.</a>
                <p>
                <p>4. If 2-step-verification is enabled, then you will have to create app password for your application or
                    device.
                <p>
                <p>5. For security measures, Google may require you to complete this additional step while signing-in.
                    Click here to allow access to your Google account using the new device/app.
                <p>

                    <hr>
                <p>Note: It may take an hour or more to reflect any security changes</p>
            </div>
        </div>
    </div>
</div>
<!-- /disabled keyboard interaction -->
<!-- end service area model  -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?= GoogleMapKey ?>&libraries=places&callback=initAutocomplete" async defer></script>

<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/counters/maxlength/maxlength.js"></script>

<script>
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
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */
            (document.getElementById('autocomplete')), {
                types: ['geocode']
            });

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', function() {
            fillInAddress(autocomplete, "");
        });
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

                alert(val);
            }
        }
    }
    google.maps.event.addDomListener(window, "load", initAutocomplete);

    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });

                //alert(position.coords.latitude);
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        //Image file input change event
        $("#image").change(function() {
            readImageData(this); //Call image read and render function
            resizeImage({
                file: this.files[0],
                maxSize: 300
            });
        });
    });

    function readImageData(imgData) {
        if (imgData.files && imgData.files[0]) {
            var readerObj = new FileReader();
            readerObj.onload = function(element) {
                $('#preview_img').attr('src', element.target.result);
            }
            readerObj.readAsDataURL(imgData.files[0]);
        }
        readerObj.readAsDataURL(imgData.files[0]);
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        getServiceAreaList();
		getSourceList();
		getServiceTypeList();
		getCommissionList();
		getBonusList();
        getSalesTexAreaList();
        getCouponList();
		getTagList();	
    });
</script>
<script type="text/javascript">
    function editServiceArea(property_area_cat_id) {
        $(".property_area_cat_id").val('');
        $(".category_area_name").val('');
        $(".category_description").val('');
        $("#loading").css("display", "block");
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>admin/setting/editServiceArea/' + property_area_cat_id,
            dataType: 'json',
            success: function(response) {
                $("#loading").css("display", "none");
                $(".property_area_cat_id").val(response['property_area_cat_id']);
                $(".category_area_name").val(response['category_area_name']);
                $(".category_description").val(response['category_description']);
            }
        });
    }
	
	function editSource(source_id) {
        $("#source_name").val('');
        $("#source_type").val('');
        $("#source_id").val('');
        $("#loading").css("display", "block");
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>admin/setting/editSource/' + source_id,
            dataType: 'json',
            success: function(response) {
                $("#loading").css("display", "none");
                $("#source_id").val(response.source_id);
                $("#source_name").val(response.source_name);
                $("#source_type").val(response.source_type);
            }
        });
    }

    function editServiceType(service_type_id) {
        $("#service_type_name").val('');
        $("#service_type_type").val('');
        $("#service_type_id").val('');
        $("#loading").css("display", "block");
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>admin/setting/editServiceType/' + service_type_id,
            dataType: 'json',
            success: function(response) {
                $("#loading").css("display", "none");
                $("#service_type_id").val(response.service_type_id);
                $("#service_type_name").val(response.service_type_name);
                $("#service_type").val(response.service_type);
            }
        });
    }

    function editCommission(commission_id) {
        $("#commission_name").val('');
        $("#commission_value").val('');
        $("#commission_id").val('');
        // $("#commission_type").val('');
        $("#loading").css("display", "block");
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>admin/setting/editCommission/' + commission_id,
            dataType: 'json',
            success: function(response) {
                $("#loading").css("display", "none");
                $("#commission_id").val(response.commission_id);
                $("#commission_name").val(response.commission_name);
                $("#commission_value").val(response.commission_value);
                if(response.commission_type == 1){
                    $("div.commission_select select").val("1");
                } else if(response.commission_type == 2){
                    $("div.commission_select select").val("2");
                } else {
                    $("#commission_type").val(response.commission_type);
                }
                // $("#commission_type").val(response.commission_type);
				$('select#commission_type').selectpicker('refresh');
            }
        });
    }
    function editBonus(bonus_id) {
        $("#bonus_name").val('');
        $("#bonus_value").val('');
        $("#bonus_id").val('');
        // $("#bonus_type").val('');
        $("#loading").css("display", "block");
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>admin/setting/editBonus/' + bonus_id,
            dataType: 'json',
            success: function(response) {
                $("#loading").css("display", "none");
                $("#bonus_id").val(response.bonus_id);
                $("#bonus_name").val(response.bonus_name);
                $("#bonus_value").val(response.bonus_value);
                if(response.bonus_type == 1){
                    $("div.bonus_select select").val("1");
                } else if(response.bonus_type == 2){
                    $("div.bonus_select select").val("2");
                } else {
                    $("#bonus_type").val(response.bonus_type);
                }
                // $("#bonus_type").val(response.bonus_type);
            }
        });
    }
    function editSalesTaxArea(sale_tax_area_id) {
        $("#tax_name").val('');
        $("#tax_value").val('');
        $("#sale_tax_area_id").val('');
        $("#loading").css("display", "block");
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>admin/setting/editSalesTaxArea/' + sale_tax_area_id,
            dataType: 'json',
            success: function(response) {
                $("#loading").css("display", "none");
                $("#sale_tax_area_id").val(response.sale_tax_area_id);
                $("#tax_name").val(response.tax_name);
                $("#tax_value").val(response.tax_value);
            }
        });
    }

    function deleteServiceArea(property_area_cat_id) {
        //e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover this !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $("#loading").css("display", "block");
                $.ajax({
                    type: 'GET',
                    url: '<?php echo base_url(); ?>admin/setting/deleteServiceArea/' + property_area_cat_id,
                    success: function(response) {
                        $("#loading").css("display", "none");
                        getServiceAreaList();
                        swal(
                            'Service Area',
                            'Deleted Successfully ',
                            'success'
                        )
                    }
                });
            }
        })
    }

    function deleteSource(source_id) {
        //e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover this !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $("#loading").css("display", "block");
                $.ajax({
                    type: 'GET',
                    url: '<?php echo base_url(); ?>admin/setting/deleteSource/' + source_id,
                    success: function(response) {
                        $("#loading").css("display", "none");
                        getSourceList();
                        swal(
                            'Source',
                            'Deleted Successfully ',
                            'success'
                        )
                    }
                });
            }
        })
    }

    function deleteServiceType(service_type_id) {
        //e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover this !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $("#loading").css("display", "block");
                $.ajax({
                    type: 'GET',
                    url: '<?php echo base_url(); ?>admin/setting/deleteServiceType/' + service_type_id,
                    success: function(response) {
                        $("#loading").css("display", "none");
                        getServiceTypeList();
                        swal(
                            'Service Type',
                            'Deleted Successfully ',
                            'success'
                        )
                    }
                });
            }
        })
    }
    function deleteCommission(commission_id) {
        //e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover this !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $("#loading").css("display", "block");
                $.ajax({
                    type: 'GET',
                    url: '<?php echo base_url(); ?>admin/setting/deleteCommission/' + commission_id,
                    success: function(response) {
                        $("#loading").css("display", "none");
                        getCommissionList();
                        swal(
                            'Commission',
                            'Deleted Successfully ',
                            'success'
                        );
						location.reload();
                    }
                });
            }
        })
    }
    function deleteBonus(bonus_id) {
        //e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover this !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $("#loading").css("display", "block");
                $.ajax({
                    type: 'GET',
                    url: '<?php echo base_url(); ?>admin/setting/deleteBonus/' + bonus_id,
                    success: function(response) {
                        $("#loading").css("display", "none");
                        getBonusList();
                        swal(
                            'Bonus',
                            'Deleted Successfully ',
                            'success'
                        );
                        location.reload();
                    }
                });
            }
        })
    }

    function deleteSalesTaxArea(sale_tax_area_id) {
        //e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover this !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $("#loading").css("display", "block");
                $.ajax({
                    type: 'GET',
                    url: '<?php echo base_url(); ?>admin/setting/deleteSalesTaxArea/' + sale_tax_area_id,
                    success: function(response) {
                        $("#loading").css("display", "none");
                        getSalesTexAreaList();
                        swal(
                            'Sales Tax Area',
                            'Deleted Successfully ',
                            'success'
                        )
                    }
                });
            }
        })
    }
    function deleteCoupon(coupon_id) {
        //e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover this !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $("#loading").css("display", "block");
                $.ajax({
                    type: 'GET',
                    url: '<?php echo base_url(); ?>admin/setting/deleteCoupon/' + coupon_id,
                    success: function(response) {
                        $("#loading").css("display", "none");
                        getCouponList();
                        swal(
                            'Coupon',
                            'Deleted Successfully ',
                            'success'
                        )
                    }
                });
            }
        })
    }

    function editCoupon(coupon_id) {

        $("#coupon_code_input_edit").val('');
        $("#coupon_amount_input_edit").val('');
        $("#coupon_amount_type_input_edit").val('');
        $("#coupon_type_input_edit").val('');
        $("#coupon_description_input_edit").val('');
        $("#coupon_expire_date_input_edit").val('');
        $("#coupon_edit_id_from_form").val('');

        $("#loading").css("display", "block");
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>admin/setting/editCoupon/' + coupon_id,
            success: function(response) {
                response = JSON.parse(response);
                $("#loading").css("display", "none");

                $("#coupon_code_input_edit").val(response['code']);
                $("#coupon_amount_input_edit").val(response['amount']);
                $("#coupon_description_input_edit").val(response['description']);
                $("#coupon_expire_date_input_edit").val(response['expiration_date']);
                $("#coupon_edit_id_from_form").val(response['coupon_id']);

                if (response['amount_calculation'] == 0) {
                    document.getElementById("coupon_amount_type_input_edit").selectedIndex = 1
                } else if (response['amount_calculation'] == 1) {
                    document.getElementById("coupon_amount_type_input_edit").selectedIndex = 2
                } else {
                    document.getElementById("coupon_amount_type_input_edit").selectedIndex = 0
                }

                if (response['type'] == 0) {
                    document.getElementById("coupon_type_input_edit").selectedIndex = 1
                } else if (response['type'] == 1) {
                    document.getElementById("coupon_type_input_edit").selectedIndex = 2
                } else {
                    document.getElementById("coupon_type_input_edit").selectedIndex = 0
                }

            },
            error: function(error) {
                $("#loading").css("display", "none");
                alert("Something went wrong");
            }
        });
    }
              
    function editTag(tag_id) {

            $("#tag_edit_id").val('');
            $("#tag_edit_tags_title").val('');
            $('#edit_in_include_in_tech_view').prop('checked', false); 

         
            $("#loading").css("display", "block");
            $.ajax({
                type: 'GET',
                url: '<?php echo base_url(); ?>admin/setting/editTag/' + tag_id,
                success: function(response) {
                    response = JSON.parse(response);
                    $("#loading").css("display", "none");

                    $("#tag_edit_id").val(response['id']);
                    $("#tag_edit_tags_title").val(response['tags_title']);
                    //$("#edit_in_include_in_tech_view").val(response['include_in_tech_view']);
                    if(response['include_in_tech_view']== 1 ){
                        $('#modal_edit_tag input#include_in_tech_view').prop('checked', true); 
						$('#modal_edit_tag div#uniform-include_in_tech_view > span').addClass('checked'); 
                    }
                   

                 

                },
                error: function(error) {
                    $("#loading").css("display", "none");
                    alert("Something went wrong");
                }
            });
    }


    $('input[name=quickbook_status]').click(function() {
        $("#loading").css("display", "block");
        var quickbook_status = 0;
        if ($(this).prop("checked") == true) {
            quickbook_status = 1;
        } else if ($(this).prop("checked") == false) {
            var quickbook_status = 0;
        }
        $.ajax({
            url: '<?php echo base_url(); ?>admin/quickbook/quickBookStatus',
            data: {
                quickbook_status: quickbook_status
            },
            type: 'POST',
            success: function(response) {
                $("#loading").css("display", "none");

            }
        });
    });
    $(function() {
        var sales_tax = document.querySelector('.switchery-sales-tax');
        var switchery = new Switchery(sales_tax, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });


        // SERVICE SCHDULED
        var accepted = document.querySelector('.switchery-service-scheduled');
        var switchery = new Switchery(accepted, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
        var accepted_text = document.querySelector('.switchery-service-scheduled-text');
        if (accepted_text) {
            var switchery = new Switchery(accepted_text, {
                color: '#36c9c9',
                secondaryColor: "#dfdfdf",
            });
        }

        // SKIPPED SERVICE
        var accepted = document.querySelector('.switchery-service-skipped');
        var switchery = new Switchery(accepted, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
        var accepted_text = document.querySelector('.switchery-service-skipped-text');
        if (accepted_text) {
            var switchery = new Switchery(accepted_text, {
                color: '#36c9c9',
                secondaryColor: "#dfdfdf",
            });
        }

        // 1 DAY PRIOR TO SCHEDULED DATE
        var accepted = document.querySelector('.switchery-service-prior');
        var switchery = new Switchery(accepted, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
        var accepted_text = document.querySelector('.switchery-service-prior-text');
        if (accepted_text) {
            var switchery = new Switchery(accepted_text, {
                color: '#36c9c9',
                secondaryColor: "#dfdfdf",
            });
        }

        // SERVICE COMPLETED
        var accepted = document.querySelector('.switchery-service-complete');
        var switchery = new Switchery(accepted, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
        var accepted_text = document.querySelector('.switchery-service-complete-text');
        if (accepted_text) {
            var switchery = new Switchery(accepted_text, {
                color: '#36c9c9',
                secondaryColor: "#dfdfdf",
            });
        }

        // PROGRAM ASSIGNED
        var assigned = document.querySelector('.switchery-program-assigned');
        var switchery = new Switchery(assigned, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
        var assigned_text = document.querySelector('.switchery-program-assigned-text');
        if (assigned_text) {
            var switchery = new Switchery(assigned_text, {
                color: '#36c9c9',
                secondaryColor: "#dfdfdf",
            });
        }

        // ESTIMATE ACCEPTED
        var accepted = document.querySelector('.switchery-estimate-accepted');
        var switchery = new Switchery(accepted, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
        var accepted_text = document.querySelector('.switchery-estimate-accepted-text');
        if (accepted_text) {
            var switchery = new Switchery(accepted_text, {
                color: '#36c9c9',
                secondaryColor: "#dfdfdf",
            });
        }
        // INVOICE LATE FEE
        var late_fee = document.querySelector('.switchery-invoice-late-fee');
        var switchery = new Switchery(late_fee, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
        var late_fee_text = document.querySelector('.switchery-invoice-late-fee-text');
        if (late_fee_text) {
            var switchery = new Switchery(late_fee_text, {
                color: '#36c9c9',
                secondaryColor: "#dfdfdf",
            });
        }
        var late_fee_is_recurring = document.querySelector('.switchery-late-fee-recurring');
        if (late_fee_is_recurring) {
            var switchery = new Switchery(late_fee_is_recurring, {
                color: '#36c9c9',
                secondaryColor: "#dfdfdf",
            });
        }

        // PURCHASE ORDER ACCEPTED
        var accepted_purchase_order = document.querySelector('.switchery-purchase-order-accepted');
        var switchery = new Switchery(accepted_purchase_order, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
        var accepted_purchase_order_text = document.querySelector('.switchery-purchase-order-accepted-text');
        if (accepted_text) {
            var switchery = new Switchery(accepted_purchase_order_text, {
                color: '#36c9c9',
                secondaryColor: "#dfdfdf",
            });
        }




        var send_invoice_mail = document.querySelector('.switchery-send-daily-invoice-mail');
        var switchery = new Switchery(send_invoice_mail, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
        var sales_tax = document.querySelector('.switchery-service-completion');
        var switchery = new Switchery(sales_tax, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });


	});
		// CUSTOMER HOLD
		var is_email_scheduling_indays = document.querySelector('.switchery-customer-hold');	
		var switchery = new Switchery(is_email_scheduling_indays, {	
		color: '#36c9c9',	
		secondaryColor: "#dfdfdf",	
		});	
		var is_email_hold_templete = document.querySelector('.switchery-email-service-hold');	
		var switchery = new Switchery(is_email_hold_templete, {	
		color: '#36c9c9',	
		secondaryColor: "#dfdfdf",	
		});	
		var is_hold_notification = document.querySelector('.switchery-hold_customer_ntification');	
		var switchery = new Switchery(is_hold_notification, {	
		color: '#36c9c9',	
		secondaryColor: "#dfdfdf",	
		});
		// CUSTOMER HOLD
		var cust_portal = document.querySelector('.switchery-customer-portal');
        var switchery = new Switchery(cust_portal, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });

    var job_assign = document.querySelector('.switchery-assign-job');
    var switchery = new Switchery(job_assign, {
        color: '#36c9c9',
        secondaryColor: "#36c9c9",
    });

    var tech_standalone = document.querySelector('.switchery-tech-add-standalone-service');
    var switchery = new Switchery(tech_standalone, {
			color: '#36c9c9',
			secondaryColor: "#dfdfdf",
		});

    var auto_inv_statement = document.querySelector('.switchery-send_monthly_invoice_statement');
    var switchery = new Switchery(auto_inv_statement, {
			color: '#36c9c9',
			secondaryColor: "#dfdfdf",
		});

   /* $('input[name=is_sales_tax]').click(function() {

        if ($(this).prop("checked") == true) {
            $('.sales_container').css('display', 'block')
        } else if ($(this).prop("checked") == false) {
            $('.sales_container').css('display', 'none')
        }
    });*/
    $('input[name=pay_now_btn]').click(function() {
        if ($(this).prop("checked") == true) {
            $('.btn_url_container').css('display', 'block')
        } else if ($(this).prop("checked") == false) {
            $('.btn_url_container').css('display', 'none')
        }
    });
	
	$('select[name="cc_processor_cc"]').change(function() {
    var selected = $(this).val();

    if (selected == 'cardconnect') {
        $('fieldset#cardconnect_form_cc').show();
    } else {
        $('fieldset#cardconnect_form_cc').hide();
    }

    if (selected == 'basys') {
        $('fieldset#basys_form_cc').show();
    } else {
        $('fieldset#basys_form_cc').hide();
    }
});

$('select[name="cc_processor_bas"]').change(function() {
    var selected = $(this).val();

    if (selected == 'cardconnect') {
        $('fieldset#cardconnect_form_bas').show();
    } else {
        $('fieldset#cardconnect_form_bas').hide();
    }

    if (selected == 'basys') {
        $('fieldset#basys_form_bas').show();
    } else {
        $('fieldset#basys_form_bas').hide();
    }
});
function editPropertyCondition(property_condition_id){
    $("#loading").css("display", "block");
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url(); ?>admin/setting/editPropertyCondition/' + property_condition_id,
        success: function(response) {
            $("#loading").css("display", "none");
            response = JSON.parse(response);
            console.log(response);
            $("#update_property_condition_id").val(response['property_condition_id']);
            $("#update_condition_name").val(response['condition_name']);
            $("#update_message").val(response['message']);
            if(response['in_email'] == 1){
                $('#update_in_email').prop('checked', true);
            }
            var in_email = document.querySelector('.switchery-update-in-email');
            var switchery = new Switchery(in_email,{
                color: '#36c9c9',
                secondaryColor: "#dfdfdf",
            });
        },
        error: function(error) {
            $("#loading").css("display", "none");
            alert("Something went wrong");
        }
    });
}
function deletePropertyCondition(property_condition_id){
    swal({
        title: 'Are you sure?',
        text: "You won't be able to recover this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#009402',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    }).then((result)=>{
        if(result.value){
            $("#loading").css("display", "block");
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url(); ?>admin/setting/deletePropertyCondition',
                data:{property_condition_id:property_condition_id},
                success: function(response) {
                    $("#loading").css("display", "none");
                    swal({
                        title:'Property Condition',
                        text:'Deleted Successfully',
                        type:'success'
                    }).then(function(){
                        window.location.reload();
                    });
                },
                error:function(error){
                   $("#loading").css("display", "none");
                    alert("Something went wrong");
                }
            });
        }
    });
}
$(document).ready(function(){
    $('#property-conditions-table').dataTable().fnDestroy()
    $('#property-conditions-table').DataTable({
        columnDefs:[{
            targets:2,
            render:function(data,type,row){
                return data.substr(0,40)+'...';
            }
        }] 
    });
    $('.property-conditions-container .datatable-footer #property-conditions-table_info').text("New Feature! Insert the following to show Property Conditions in Service Completion email: {PROPERTY_CONDITIONS}");
});
</script>
<!-- Notes Settings -->
<script>
$(document).ready(function() {
    $('#datatables_notetypes').DataTable();
    var note_types = <?= json_encode( $note_types ); ?>;
    var typesArr = note_types.map(t=>t.type_name);
    $('#add_notetype_submit').on('click', function(event) {
        let inpt = $('#notetype_name').val().trim();
        if(inpt == 'Task' || inpt == 'task') {
            $('#add_notetype_form_errors').text('Cannot be the same as the Default Type "Task"').show();
        } else if(typesArr.includes(inpt)) {
            $('#add_notetype_form_errors').text('Cannot submit a type name that already exists.').show();
        } else {
            console.log('yay i made it')
            $('#add_notetype_form_errors').text('').hide();
            $('#add_notetype_form').submit();
        }
    });
    $('#edit_notetype_submit').on('click', function(event) {
        let inpt =  $('#edit_type_name').val().trim();
        if(inpt == 'Task' || inpt == 'task' || inpt == 'Vehicle General' || inpt == 'Vehicle Maintenance') {
            $('#edit_notetype_form_errors').text('Cannot be the same as any of the Default Types').show();
        } else if(typesArr.includes(inpt)) {
            $('#edit_notetype_form_errors').text('Cannot submit a type name that already exists.').show();
        } else {
            console.log('yay i editting');
            $('#edit_notetype_form_errors').text('').hide();
            $('#edit_notetype_form').submit();
        }
    });
    $("#add_notetype_form").on("keypress", function (event) {
        var keyPressed = event.keyCode || event.which;
        if (keyPressed === 13) {
            event.preventDefault();
            return false;
        }
    });
    $("#edit_notetype_form").on("keypress", function (event) {
        var keyPressed = event.keyCode || event.which;
        if (keyPressed === 13) {
            event.preventDefault();
            return false;
        }
    });
});

function editNoteType(typeId,typeName) {
    $('#edit_notetype_form_errors').text('').hide();
    $('#edit_type_id').val(typeId);
    $('#edit_type_name').val(typeName);
}
function deleteNoteType(typeId) {
    let data = {'type_id': typeId};
    $.ajax({
    type: 'POST',
    url: '<?= base_url('admin/deleteNoteType'); ?>',
    data: data,
    success: function(data) {
        console.log("Success : ", data);
        let result = JSON.parse(data);
        if(result.status == 'success') {
            let table = $('#datatable_notetypes').DataTable();
            table.row(`#notetype_row_${typeId}`).remove().draw();
        }
    },
    error: function(e) {
        console.log("ERROR : ", e);
    }
    });
}

/* Add Commission */
$('#addCommissionButton').click(function(){
	let disable_primary_comission = $('input[name="disable_primary_commission"]').val();
	let disable_secondary_comission = $('input[name="disable_secondary_commission"]').val();
	if(disable_primary_comission == 1){
		$('select#add_commission_type option#add_commission_type_primary').attr('disabled',true);
	}
	if(disable_secondary_comission == 1){
		$('select#add_commission_type option#add_commission_type_secondary').attr('disabled',true);
	}
	$('select#add_commission_type').selectpicker('refresh');
});
$(document).click('a.edit-commission-btn',function(){
	let disable_primary_comission = $('input[name="disable_primary_commission"]').val();
	let disable_secondary_comission = $('input[name="disable_secondary_commission"]').val();
	if(disable_primary_comission == 1){
		$('select#commission_type option#edit_commission_type_primary').attr('disabled',true);
	}
	if(disable_secondary_comission == 1){
		$('select#commission_type option#edit_commission_type_secondary').attr('disabled',true);
	}
	$('select#commission_type').selectpicker('refresh');
});

$(document).on("change", "input[name=late_fee_flat]", function(){
    if($(this).val() > 0){
        $("input[name=late_fee_percent]").val(0);
    }
});
$(document).on("change", "input[name=late_fee_percent]", function(){
    if($(this).val() > 0){
        $("input[name=late_fee_flat]").val(0);
    }

});
// handle edit and delete cancel reason crud
function editCancelReason(cancelId,cancelName){
	$('input#edit_cancel_name').val(cancelName);
	$('input#edit_cancel_id').val(cancelId);
	$('#modal_edit_cancelreason').modal('show');
}
function deleteCancelReason(cancelId,cancelName){
	$('input#delete_cancel_name').val(cancelName);
	$('input#delete_cancel_id').val(cancelId);
	$('#modal_delete_cancelreason').modal('show');
}
function editRescheduleReason(rescheduleId,rescheduleName){
    $('input#edit_reschedule_name').val(rescheduleName);
    $('input#edit_reschedule_id').val(rescheduleId);
    $('#modal_edit_reschedulereason').modal('show');
}
function deleteRescheduleReason(rescheduleId,rescheduleName){
    $('input#delete_reschedule_name').val(rescheduleName);
    $('input#delete_reschedule_id').val(rescheduleId);
    $('#modal_delete_reschedulereason').modal('show');
}
</script>

<script>
        
        mobiscroll.setOptions({
    locale: mobiscroll.localeEn,                                             // Specify language like: locale: mobiscroll.localePl or omit setting to use default
    theme: 'ios',                                                            // Specify theme like: theme: 'ios' or omit setting to use default
        themeVariant: 'light'                                                // More info about themeVariant: https://docs.mobiscroll.com/5-24-0/select#opt-themeVariant
});

$(function () {
    // Mobiscroll Select initialization
    $('#demo-multiple-select').mobiscroll().select({
        filter: true,
        inputElement: document.getElementById('demo-multiple-select-input')  // More info about inputElement: https://docs.mobiscroll.com/5-24-0/select#opt-inputElement
    });
});
</script>