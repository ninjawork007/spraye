<style type="text/css">
  .toolbar {
    float: left;
    padding-left: 5px;
}
.form-control[readonly] {
  background-color: #fff;
}
select, input {
  background-color: #fff !important;
}
.fix_panel_spacing {
    min-height: 82px;
}
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
.multiselect-container>li>a>label {
    white-space: break-spaces;
}
</style>
<div class="content">
	<div class="panel panel-flat">
		<div class="panel-body">
			<b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
			<div class="panel panel-body" style="background-color:#ededed;" >
                <h1>Filter customers by:</h1>
				<form id="serchform" action="<?= base_url('admin/reports/downloadMarketingCustomerDataReport') ?>" method="post">
                    <div class="panel-group" id="panels">
                        <div class="panel panel-default p-5">
                            <div class="collapsed" data-toggle="collapse" data-parent="#panels" data-target="#firstPanel" style="cursor: pointer">
                                <h4>Sales Details</h4>    
                            </div>
                            <div id="firstPanel" class="panel-collapse collapse">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Lead Created Date Range Start</label>
                                        <input type="date" id="lead_start_date_start" name="lead_start_date_start" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Lead Created Date Range End</label>
                                        <input type="date" id="lead_start_date_end" name="lead_start_date_end" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Program Assigned Date Range Start</label>
                                        <input type="date" id="sale_start_date_start" name="sale_start_date_start" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Program Assigned Date Range End</label>
                                        <input type="date" id="sale_start_date_end" name="sale_start_date_end" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>

                                    <div class="col-md-3 mt-15">
                                        <label>Canceled Program Date Range Start</label>
                                        <input type="date" id="cancelation_date_start" name="cancelation_date_start" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="col-md-3 mt-15">
                                        <label>Canceled Program Date Range End</label>
                                        <input type="date" id="cancelation_date_end" name="cancelation_date_end" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="col-md-3 mt-15">
                                        <label>Last Purchase Date Range Start</label>
                                        <input type="date" id="last_date_program_start" name="last_date_program_start" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="col-md-3 mt-15">
                                        <label>Last Purchase Date Range End</label>
                                        <input type="date" id="last_date_program_end" name="last_date_program_end" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="col-md-4 mt-15">
                                        <label>Estimate Accepted?</label>
                                        <select class="form-control" name="estimate_accpeted" id="estimate_accpeted">
                                            <option value="">None selected</option>
                                            <option value="2"> Accepted </option>
                                            <option value="5"> Declined </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-15 multi-select-full">
                                        <label for="service_ids_filter">Source(s)</label>
                                        <select class="multiselect-select-all-filtering form-control" name="sources_multi[]" id="sources_multi" multiple="multiple">
                                            <?php foreach ($source_list as $value) : ?>
                                                <option value="<?= $value->source_id ?>"><?= $value->source_name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 multi-select-full mt-15">
                                        <label>Cancel Reason(s)</label>
                                        <select class="multiselect-select-all-filtering form-control" name="cancel_reasons_multi[]" id="cancel_reasons_multi" multiple="multiple">
                                            <?php foreach ($cancel_reasons as $cancel_reason): ?>
                                                <option value="<?= $cancel_reason->cancel_name ?>"> <?= $cancel_reason->cancel_name ?> </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mt-15">
                                        <label>YTD Revenue</label>
                                        <div class='row'>
                                            <div class="col-md-6">
                                                <input type="text" id="ytd_revenue_start" name="ytd_revenue_start" class="form-control" placeholder="Start">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" id="ytd_revenue_end" name="ytd_revenue_end" class="form-control" placeholder="End">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-15">
                                        <label>Revenue Total</label>
                                        <div class='row'>
                                            <div class="col-md-6">
                                                <input type="text" id="revenue_total_start" name="revenue_total_start" class="form-control" placeholder="Start">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" id="revenue_total_end" name="revenue_total_end" class="form-control" placeholder="End">
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="col-md-3 mt-15">
                                        <label>Projected Annual Revenue</label>
                                        <div class='row'>
                                            <div class="col-md-6">
                                                <input type="text" id="projected_annual_revenue_start" name="projected_annual_revenue_start" class="form-control" placeholder="Start">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" id="projected_annual_revenue_end" name="projected_annual_revenue_end" class="form-control" placeholder="End">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-15">
                                        <label>Annual revenue per 1000 sq/ft</label>
                                        <div class='row'>
                                            <div class="col-md-6">
                                                <input type="text" id="annual_revenue_per_1000_start" name="annual_revenue_per_1000_start" class="form-control" placeholder="Start">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" id="annual_revenue_per_1000_end" name="annual_revenue_per_1000_end" class="form-control" placeholder="End">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default p-5">
                            <div class="collapsed" data-toggle="collapse" data-parent="#panels" data-target="#secondPanel" style="cursor: pointer">
                                <h4>Customer & Property Attributes</h4>
                            </div>
                            <div id="secondPanel" class="panel-collapse collapse">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Customer Status</label>
                                        <select class="form-control" name="customer_status" id="customer_status">
                                            <option value="">None selected</option>
                                            <option value="1"> Active </option>
                                            <option value="2"> Hold </option>
                                            <option value="0"> Non-Active </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 multi-select-full">
                                        <label>Zip Code(s)</label>
                                        <select class="multiselect-select-all-filtering form-control" name="zip_codes_multi[]" id="zip_codes_multi" multiple="multiple">
                                            <?php foreach ($zip_codes as $zip): ?>
                                                <option value="<?= $zip->property_zip ?>"> <?= $zip->property_zip ?> </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 multi-select-full">
                                        <label>Service Area(s)</label>
                                        <select class="multiselect-select-all-filtering form-control" name="service_areas_multi[]" id="service_areas_multi" multiple="multiple">
                                            <?php foreach ($service_areas as $area): ?>
                                                <option value="<?= $area->property_area_cat_id ?>"> <?= $area->category_area_name ?> </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Residential or Commercial</label>
                                        <select class="form-control" name="res_or_com" id="res_or_com">
                                            <option value="">None selected</option>
                                            <option value="Residential"> Residential </option>
                                            <option value="Commercial"> Commercial </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-15">
                                        <label>Lot Size</label>
                                        <div class='row'>
                                            <div class="col-md-6">
                                                <input type="text" id="lot_size_start" name="lot_size_start" class="form-control" placeholder="Start">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" id="lot_size_end" name="lot_size_end" class="form-control" placeholder="End">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-15 multi-select-full">
                                        <label>Pre-Service Notification(s)</label>
                                        <span style='float:right'>
                                            <label class="checkbox-inline checkbox-left float-right">
                                                <input type="checkbox" name="all_pre_service" id="all_pre_service" class="" >Require All?
                                            </label>
                                        </span>
                                        <select class="multiselect-select-all-filtering form-control" name="preservice_notifications_multi[]" id="preservice_notifications_multi" multiple="multiple">
                                            <option value="1">Phone Call</option>
                                            <option value="2">Automated Email(s)</option>
                                            <option value="3">Automated Text message(s)</option>
                                            <option value="4">Text when En route</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-15 multi-select-full">    
                                        <label>Tag(s)</label>
                                        <span style='float:right'>
                                            <label class="checkbox-inline checkbox-left float-right">
                                                <input type="checkbox" name="all_tags" id="all_tags" class="" >Require All?
                                            </label>
                                        </span>
                                        <select class="multiselect-select-all-filtering form-control" name="tags_multi[]" id="tags_multi" multiple="multiple">
                                            <?php foreach ($taglist as $value): ?>
                                                <option value="<?= $value->id ?>"> <?= $value->tags_title ?> </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-15 multi-select-full">
                                        <label>Front Yard Grass Type</label>
                                        <select class="multiselect-select-all-filtering form-control" name="front_yard_grass[]" id="front_yard_grass" multiple="multiple">
                                            <option>Bent</option>
                                            <option>Bermuda</option>
                                            <option>Dichondra</option>
                                            <option>Fine Fescue</option>
                                            <option>Kentucky Bluegrass</option>
                                            <option>Ryegrass</option>
                                            <option>St. Augustine/Floratam</option>
                                            <option>Tall Fescue</option>
                                            <option>Zoysia</option>
                                            <option>Centipede</option>
                                            <option>Bluegrass/Rye/Fescue</option>
                                            <option>Warm Season</option>
                                            <option>Cool Season</option>
                                            <option>Mixed Grass</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-15 multi-select-full">
                                        <label>Back Yard Grass Type</label>
                                        <select class="multiselect-select-all-filtering form-control" name="back_yard_grass[]" id="back_yard_grass" multiple="multiple">
                                            <option>Bent</option>
                                            <option>Bermuda</option>
                                            <option>Dichondra</option>
                                            <option>Fine Fescue</option>
                                            <option>Kentucky Bluegrass</option>
                                            <option>Ryegrass</option>
                                            <option>St. Augustine/Floratam</option>
                                            <option>Tall Fescue</option>
                                            <option>Zoysia</option>
                                            <option>Centipede</option>
                                            <option>Bluegrass/Rye/Fescue</option>
                                            <option>Warm Season</option>
                                            <option>Cool Season</option>
                                            <option>Mixed Grass</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-15 multi-select-full">
                                        <label>Total Yard Grass Type</label>
                                        <select class="multiselect-select-all-filtering form-control" name="total_yard_grass[]" id="total_yard_grass" multiple="multiple">
                                            <option>Bent</option>
                                            <option>Bermuda</option>
                                            <option>Dichondra</option>
                                            <option>Fine Fescue</option>
                                            <option>Kentucky Bluegrass</option>
                                            <option>Ryegrass</option>
                                            <option>St. Augustine/Floratam</option>
                                            <option>Tall Fescue</option>
                                            <option>Zoysia</option>
                                            <option>Centipede</option>
                                            <option>Bluegrass/Rye/Fescue</option>
                                            <option>Warm Season</option>
                                            <option>Cool Season</option>
                                            <option>Mixed Grass</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default p-5">
                            <div class="collapsed" data-toggle="collapse" data-parent="#panels" data-target="#thirdPanel" style="cursor: pointer">
                                <h4>Programs & Services</h4>
                            </div>
                            <div id="thirdPanel" class="panel-collapse collapse">
                                <div class="row">
                                    <div class="col-md-3 multi-select-full">
                                        <label>Outstanding Service(s)</label>
                                        <span style='float:right'>
                                            <label class="checkbox-inline checkbox-left ">
                                                <input type="checkbox" name="all_outstanding" id="all_outstanding" class="" >Require All?
                                            </label>
                                        </span>
                                        <select class="multiselect-select-all-filtering form-control" name="outstanding_services_multi[]" id="outstanding_services_multi" multiple="multiple">
                                            <?php $already_used_job_names = array(); ?>
                                            <?php foreach ($outstanding_services as $outstanding): ?>
                                                <?php  
                                                    // do some logic so we dont get any repeated names
                                                    if(!in_array($outstanding->job_name, $already_used_job_names)) {
                                                        $already_used_job_names[] = $outstanding->job_name;
                                                ?>
                                                <option value="<?= $outstanding->job_id ?>"> <?= $outstanding->job_name ?> </option>
                                                <?php } ?>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 multi-select-full">
                                        <label>Program(s) or Service(s)</label>
                                        <span style='float:right'>
                                            <label class="checkbox-inline checkbox-left float-right">
                                                <input type="checkbox" name="all_programs" id="all_programs" class="" >Require All?
                                            </label>
                                        </span>
                                        <select class="multiselect-select-all-filtering form-control" name="programs_multi[]" id="programs_multi" multiple="multiple" style='white-space: break-spaces;'>
                                            <?php foreach ($program_details as $value): ?>
                                                <option value="<?= $value->program_id ?>"> <?= $value->program_name ?> </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 multi-select-full">
                                                <label># of Completed Services
                                                    <span data-popup="tooltip-custom" title="" data-placement="top" data-original-title="You must first select a Program for this filter"> <i class=" icon-info22 tooltip-icon"></i>

                                                    </span>
                                                </label>
                                                <input type="number" id="serviceCompleted" name="serviceCompleted" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                                            </div>

                                            <div class="col-md-1" style="margin-top: 25px">TO</div>

                                            <div class="col-md-6 multi-select-full">
                                                <label>&nbsp;</label>
                                                <input type="number" id="serviceCompletedEnd" name="serviceCompletedEnd" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-3 mt-15 multi-select-full">
                                        <label>Exclude Customers by Program</label>
                                        <select class="multiselect-select-all-filtering form-control" name="customerExclude[]" id="customerExclude" multiple="multiple" style='white-space: break-spaces;'>
                                            <?php foreach ($program_details as $value): ?>
                                                <option value="<?= $value->program_id ?>"> <?= $value->program_name ?> </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mt-15 multi-select-full">
                                        <label>Service Sold During Year Do not have now</label>
                                        <select class="multiselect-select-all-filtering form-control" name="serviceSoldNotNow[]" id="serviceSoldNotNow" multiple="multiple">
                                            <?php $already_used_job_names = array(); ?>
                                            <?php foreach ($outstanding_services as $outstanding): ?>
                                                <?php  
                                                    // do some logic so we dont get any repeated names
                                                    if(!in_array($outstanding->job_name, $already_used_job_names)) {
                                                        $already_used_job_names[] = $outstanding->job_name;
                                                ?>
                                                <option value="<?= $outstanding->job_id ?>"> <?= $outstanding->job_name ?> </option>
                                                <?php } ?>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mt-15">
                                        <label>Service Check Start Date</label>
                                        <input type="date" id="ServiceSoldNotNowStart" name="ServiceSoldNotNowStart" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="col-md-3 mt-15">
                                        <label>Service Check End Date</label>
                                        <input type="date" id="ServiceSoldNotNowEnd" name="ServiceSoldNotNowEnd" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="row">
						<div class="text-center">
							<button type="button" class="btn btn-success" onClick="searchFilter()" ><i class="icon-search4 position-left"></i> Search</button>
							<button type="button" class="btn btn-primary" onClick="resetform()" ><i class="icon-reset position-left"></i> Reset</button>
							<button type="submit" name="SendButtonEmail" value="3" class="btn btn-info"><i class="icon-file-download position-left"></i> Download CSV</button>
                            <button type="button" data-target="#modal_mass_email" data-toggle="modal" class="btn btn-info"><i class="icon-file-download position-left"></i> Send Email to List</button>
						</div>
					</div>

                    <!--start modal -->
                    <div id="modal_mass_email" class="modal fade">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h6 class="modal-title">Send Mass Email</h6>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group multi-select-full">
                                        <label>Programms</label>
                                        <select class="multiselect-select-all-filtering form-control" name="MassProgramms[]" multiple="multiple" style='white-space: break-spaces;'>
                                            <?php foreach ($program_details as $value): ?>
                                                <option value="<?= $value->program_id ?>"> <?= $value->program_name ?> </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <div class="form-group multi-select-full">
                                        <label>Email Subject</label>
                                        <input type="text" name="email_subject" class="form-control">
                                    </div>

                                    <div class="form-group multi-select-full">
                                        <label>Email Text</label>
                                        <textarea id="editor1" name="mailText"></textarea>
                                    </div>

                                    <span>Dynamic value for email : <br>
                                        <b>Customer First Name : </b> {CUSTOMER_FIRST_NAME}<br>
                                        <b>Customer Last Name : </b> {CUSTOMER_LAST_NAME}<br>
                                        <b>Property Name : </b> {PROPERTY_NAME}<br>
                                        <b>Property Address : </b> {PROPERTY_ADDRESS}<br>
                                        <b>Programm Name : </b> {PROGRAMM_NAME}<br>
                                    </span>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary" type="submit" name="SendButtonEmail" value="1">Send Email</button>
                                        <button class="btn btn-primary" type="submit" name="SendButtonEmail" value="2">Save Draft</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end modal -->
				</form>
			</div>
		</div>
	</div>
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="post-list" id="marketing-report-list" <?php if(empty($report_details)){ ?> style="padding-top:20px" <?php } ?> >
                <div class="table-responsive table-spraye">
                    <table class="table datatable-colvis-state" style="border:1px solid #6eb1fd;">
                        <thead>
                            <tr>
                                <th>Customer Number</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Second Email</th>
                                <th>Address</th>
                                <th>Cell Phone</th>
                                <th>Phone</th>
                                <th>Revenue by Program <span data-popup="tooltip-custom" data-container="body" title="This grabs the payments made on the invoices and adds them up." data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></th>
                                <th>YTD Revenue <span data-popup="tooltip-custom" data-container="body" title='Same calculation as Revenue by Program but this only includes from Jan 01 until today.' data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></th>
                                <th>Projected Annual Revenue <span data-popup="tooltip-custom" data-container="body" title='Grabs all invoice payments from the invoices for this customer going back exactly 1 year from today until today.' data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></th>
                                <th>Lot Size</th>
                                <th>Annual Revenue Per 1000 Sq Ft <span data-popup="tooltip-custom" data-container="body" title='Take the total lot size and divide that by 1000. We then take the total revenue and divide that by the new number we got from the first division.' data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center">Please filter down your search</td>
                                <td></td>
                                <td></td>
                                <td></td>
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
<div class="loading" style="display:none;">
	<center><img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/></center>
</div>
<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/pages/components_popups.js"></script>

  <script src="https://cdn.ckeditor.com/4.21.0/standard-all/ckeditor.js"></script>

<script>
CKEDITOR.replace('editor1');

$(document).ready(function() {
    tableInitialize();
});
function tableInitialize(argument) {
    $('.datatable-colvis-state').DataTable({
          "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
          "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
        buttons: [
            {
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon'
            }
        ],
        stateSave: true,
        columnDefs: [
            {
                targets: -1,
                visible: true
            }
        ],

          
    });
}
function searchFilter() {
	var sources_multi = $('#sources_multi').val();
	var lead_start_date_start = $('#lead_start_date_start').val();
	var lead_start_date_end = $('#lead_start_date_end').val();
    var revenue_total_start = $('#revenue_total_start').val();
    var revenue_total_end = $('#revenue_total_end').val();
    var sale_start_date_start = $('#sale_start_date_start').val();
    var sale_start_date_end = $('#sale_start_date_end').val();
    var programs_multi = $('#programs_multi').val();
    var cancelation_date_start = $('#cancelation_date_start').val();
    var cancelation_date_end = $('#cancelation_date_end').val();
    var tags_multi = $('#tags_multi').val();
    var last_date_program_start = $('#last_date_program_start').val();
    var last_date_program_end = $('#last_date_program_end').val();
    var service_areas_multi = $('#service_areas_multi').val();
    var ytd_revenue_start = $('#ytd_revenue_start').val();
    var ytd_revenue_end = $('#ytd_revenue_end').val();
    var projected_annual_revenue_start = $('#projected_annual_revenue_start').val();
    var projected_annual_revenue_end = $('#projected_annual_revenue_end').val();
    var res_or_com = $('#res_or_com').val();
    var lot_size_start = $('#lot_size_start').val();
    var lot_size_end = $('#lot_size_end').val();
    var annual_revenue_per_1000_start = $('#annual_revenue_per_1000_start').val();
    var annual_revenue_per_1000_end = $('#annual_revenue_per_1000_end').val();
    var preservice_notifications_multi = $('#preservice_notifications_multi').val();
    var zip_codes_multi = $('#zip_codes_multi').val();
    var cancel_reasons_multi = $('#cancel_reasons_multi').val();
    var outstanding_services_multi = $('#outstanding_services_multi').val();

    var front_yard_grass = $("#front_yard_grass").val();
    var customerExclude = $("#customerExclude").val();
    var back_yard_grass = $("#back_yard_grass").val();
    var total_yard_grass = $("#total_yard_grass").val();
    var serviceSoldNotNow = $("#serviceSoldNotNow").val();
    var ServiceSoldNotNowStart = $("#ServiceSoldNotNowStart").val();
    var ServiceSoldNotNowEnd = $("#ServiceSoldNotNowEnd").val();
    var serviceCompleted = $("#serviceCompleted").val();
    var serviceCompletedEnd = $("#serviceCompletedEnd").val();

    var customer_status = $('#customer_status').val();
    var estimate_accpeted = $('#estimate_accpeted').val();
    var all_tags = false;
    if($("#all_tags").is(':checked')) {
        all_tags = true;
    } else {
        all_tags = false;
    }
    var all_programs = false;
    if($("#all_programs").is(':checked')) {
        all_programs = true;
    } else {
        all_programs = false;
    }
    var all_pre_service = false;
    if($("#all_pre_service").is(':checked')) {
        all_pre_service = true;
    } else {
        all_pre_service = false;
    }
    var all_outstanding = false;
    if($("#all_outstanding").is(':checked')) {
        all_outstanding = true;
    } else {
        all_outstanding = false;
    }
    

    $('.loading').css("display", "block");
	
	$('#marketing-report-list').html('');
	
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxDataForMarketingCustomerDataReport',
        data:
            'sources_multi='+sources_multi+
            '&lead_start_date_start='+lead_start_date_start+
            '&lead_start_date_end='+lead_start_date_end+
            '&revenue_total_start='+revenue_total_start+
            '&revenue_total_end='+revenue_total_end+
            '&sale_start_date_start='+sale_start_date_start+
            '&sale_start_date_end='+sale_start_date_end+
            '&programs_multi='+programs_multi+
            '&cancelation_date_start='+cancelation_date_start+
            '&cancelation_date_end='+cancelation_date_end+
            '&tags_multi='+tags_multi+
            '&last_date_program_start='+last_date_program_start+
            '&last_date_program_end='+last_date_program_end+
            '&service_areas_multi='+service_areas_multi+
            '&ytd_revenue_start='+ytd_revenue_start+
            '&ytd_revenue_end='+ytd_revenue_end+
            '&projected_annual_revenue_start='+projected_annual_revenue_start+
            '&projected_annual_revenue_end='+projected_annual_revenue_end+
            '&res_or_com='+res_or_com+
            '&lot_size_start='+lot_size_start+
            '&lot_size_end='+lot_size_end+
            '&annual_revenue_per_1000_start='+annual_revenue_per_1000_start+
            '&annual_revenue_per_1000_end='+annual_revenue_per_1000_end+
            '&preservice_notifications_multi='+preservice_notifications_multi+
            '&zip_codes_multi='+zip_codes_multi+
            '&cancel_reasons_multi='+cancel_reasons_multi+
            '&outstanding_services_multi='+outstanding_services_multi+
            '&customer_status='+customer_status+
            '&estimate_accpeted='+estimate_accpeted+
            '&all_tags='+all_tags+
            '&all_programs='+all_programs+
            '&all_pre_service='+all_pre_service+
            '&all_outstanding='+all_outstanding+
            '&front_yard_grass='+front_yard_grass+
            '&customerExclude='+customerExclude+
            '&back_yard_grass='+back_yard_grass+
            '&total_yard_grass='+total_yard_grass+
            '&serviceCompleted='+serviceCompleted+
            '&serviceCompletedEnd='+serviceCompletedEnd+
            '&serviceSoldNotNow='+serviceSoldNotNow+
            '&ServiceSoldNotNowStart='+ServiceSoldNotNowStart+
            '&ServiceSoldNotNowEnd='+ServiceSoldNotNowEnd
        ,
        success: function (html) {
            $(".loading").css("display", "none");
            $('#marketing-report-list').html(html);
            tableInitialize();
        }
    });
}
function resetform(){
	location.reload();
}
</script>