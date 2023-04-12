<style type="text/css">
.dataTables_filter input {
  margin-bottom: 2px;
}

#invoicetablediv {
  margin-top: 2em;
  margin-bottom: 2em;
}

.selected-program {
    border-color: #00ff2b;
}

.selected-service {
    border-color: #ff0157;
}
.center-block {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
.selected-wrap {
  margin-top: 4em;
  margin-bottom: 4em;
}
.box-space {
  padding: 5px;
}
.form-border {
  border: 1px #12689b solid;
  border-radius: 3px;
  background-color: transparent;  
}
.list-selected-item {
  margin: 1px;
  padding: 0 5px !important;
  border-style: dotted;
}



input.column_filter {
    /* border: 1px #12689b solid;
    border-radius: 3px;
    background-color: transparent;  
    padding: 5px; */
    /* outline: 0;
    width: 200px;
    height: 36px;
    padding: 7px 36px 7px 12px;
    font-size: 13px;
    line-height: 1.5384616;
    color: #333;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 3px;   */
}

.content {
  padding: 20px 20px 60px !important;
}
table#search-inputs {
  border: none;
}
table {
  border-collapse: inherit;
  border: 1px solid rgb(110, 177, 253);
  border-radius: 4px;
}

table#dataTable_override {
  border-collapse: inherit;
  border: 1px solid rgb(110, 177, 253);
  border-radius: 4px;
}

#search-inputs {
  /* table-layout: auto; */
  width: 100%;  
  padding: 1em;
}

#search-inputs thead {
    background: none;
}

#search-inputs thead th {
    border: none;
}


#search-inputs td {
    padding: 2px;
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

  th , td {
  text-align: center;
  }
  .pre-scrollable {
   min-height: 0px;
  }

  .radio-inline {
    color: #333 !important;
  }

  .table.table-ellipsis tbody td.ellipsis {
  max-width: 100px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap
  }

  .table.table-ellipsis tbody td.ellipsis:hover {
  text-overflow: clip;
  white-space: normal;
  word-break: break-all;
  }  

  .btn-outline {
    background-color: transparent;
    color: inherit;
    transition: all .5s;
}

.btn-primary.btn-outline {
    color: #428bca;
}

.btn-success.btn-outline {
    color: #36c9c9;
}

.btn-info.btn-outline {
    color: #5bc0de;
}

.btn-warning.btn-outline {
    color: #f0ad4e;
}

.btn-danger.btn-outline {
    color: #d9534f;
}

.btn-primary.btn-outline:hover,
.btn-success.btn-outline:hover,
.btn-info.btn-outline:hover,
.btn-warning.btn-outline:hover,
.btn-danger.btn-outline:hover {
    color: #fff;
}

  @media (min-width: 769px){
.form-horizontal .control-label[class*=col-sm-] {
    padding-top: 0;
}}

</style>
<style type="text/css">
  th , td {
  text-align: center;
  }
  .pre-scrollable {
   min-height: 0px;
  }
	 #myTable > tbody > tr > td:hover{
    cursor:move;
    }
</style>

 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>


        <!-- Content area -->
        <div class="content">
            <div id="loading" >
                <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
            </div>
          <!-- Form horizontal -->
          <div class="panel panel-flat">
         
              <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin/Estimates/bulkRenewalProgramsList') ?>"  id="previous-page-btn" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Back to Program Renewal Select</a>
                        </div>
                   </h5>
              </div>
        
              <br>
            
            <div class="panel-body">
              
			  <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

              <form class="form-horizontal" action="<?= base_url('admin/estimates/addBulkRenewalProgramData/').$programData['program_id'] ?>" method="post" name="addcopyprogram" enctype="multipart/form-data" >
                <fieldset class="content-group">      
                  
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-lg-2">Program Name</label>
                     <div class="col-lg-10">
                      <input type="text" class="form-control" name="program_name" value="<?php echo set_value('program_name')?set_value('program_name'):$programData['program_name']?> - Copy" placeholder="Program Name">
                      <span style="color:red;"><?php echo form_error('program_name'); ?></span>
                      <input type="hidden" class="form-control" name="original_program_name" value="<?php echo set_value('program_name')?set_value('program_name'):$programData['program_name']?> - Copy" >
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-lg-2">Pricing</label>
                    <div class="col-lg-10" style="padding-left: 5px;">
                      <select class="form-control" name="program_price_display" disabled> 
                        <option value="1" <?php if ($programData['program_price']=="1") { echo "selected"; } ?> >One Time Project Invoicing</option>
                        <option value="2" <?php if ($programData['program_price']=="2") { echo "selected"; } ?> >Invoiced at Job Completion</option>
                        <option value="3" <?php if ($programData['program_price']=="3") { echo "selected"; } ?> >Manual Billing</option>
                      </select>
                      <span style="color:red;"><?php echo form_error('program_price'); ?></span>
                    </div>
                    <input type="hidden" name="program_price" id="program_price" value="<?= $programData['program_price']; ?>">
                  </div>
                </div>
              </div>

                  
                <div class="row">
                    
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label col-lg-2">Service List</label>
                        <div class="multi-select-full col-lg-10">
                            <select class="form-control" name="program_job_tmp"  id="job_list">
                                <option value="" >Select any Job</option>
                                <?php foreach ($joblist as $value): ?>
                                   <option value="<?= $value->job_id ?>"  > <?= $value->job_name ?> </option>
                                <?php endforeach ?>
                            </select>
                            
                              <input type="hidden" name="program_job" id="job_id_order_array" value="<?= implode(',', $selectedjobid) ?>">
                              <input type="hidden" name="program_job_original" id="program_job_original" value="<?= implode(',', $selectedjobid) ?>">
                        </div>                     
                      </div>
                                

                        <div class="form-group">
                          <label class="control-label col-lg-2">Customer Message (Included in Email)</label>
                          <div class="col-lg-10">
                            <textarea class="form-control" name="program_notes" rows="5"><?php echo set_value('program_notes')?set_value('program_notes'):$programData['program_notes']?></textarea>
                            <span style="color:red;"><?php echo form_error('program_notes'); ?></span>
                          </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" id='source_select_row_id'>
                            <label class="control-label col-lg-2">Source</label>
                            <div class="multi-select-full col-lg-10">
                            <select class="form-control" name="source" id="select_source">
                                <option value="">Select Source</option>
                                <?php foreach ($source_list as $value) : ?>
                                    <option value="<?= $value->source_id ?>" ><?= $value->source_name ?></option>
                                <?php endforeach ?>
                            </select>
                            </div>
                        </div>
                    </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label col-lg-3 col-sm-12 col-xs-12">Apply Coupons</label>
                            <div class="multi-select-full col-lg-9" style="padding-left: 4px;">
                              <select class="multiselect-select-all-filtering form-control" name="assign_onetime_coupons[]" id="" multiple="multiple">
                                <?php foreach ($customer_one_time_discounts as $value): ?>

                                      <?php
                                      // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                                      $expiration_pass = true;
                                      if ($value->expiration_date != "0000-00-00 00:00:00") {
                                          $coupon_expiration_date = strtotime( $value->expiration_date );

                                          $now = time();
                                          if($coupon_expiration_date < $now) {
                                                $expiration_pass = false;
                                                $expiration_pass_global = false;
                                          }
                                      }

                                      if ($expiration_pass == true) {?>
                                     <option value="<?= $value->coupon_id ?>"> <?= $value->code ?> </option>
                                    <?php } ?>
                                <?php endforeach ?>
                              </select>
                            </div>
                          </div>
                        </div>

                       <div class="col-md-6">
                        <div class="prioritydivcontainer" style="display: <?php echo !empty($selecteddata) ? 'block' : 'none'; ?>;"> 
                           <div  class="table-responsive  pre-scrollable">
                             <table  class="table table-bordered">    
                                  <thead>  
                                      <tr>
                                          <th>Priority</th>                                                 
                                          <th>Service Name</th>                                                 
                                          <th>Remove</th>                                                 
                                      </tr>             
                                  </thead>
                                  <tbody class="prioritytbody">
                                  <?php 
                                  if(!empty($selecteddata)) {
                                    $a=1;
                                    foreach ($selecteddata as $key => $value) { ?>
                                  <tr id="trid<?= $a ?>">
                                         <td class="index"><?= $a ?></td>
                                         <td><?= $value->job_name ?></td>
                                         <td class="removeclass" id="<?= $a ?>" optionValueRemove="<?= $value->job_id ?>" optionTextRemove="<?= $value->job_name ?>" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"></i></a></li></ul></td>
                                       </tr>
                                                                               
                                 <?php $a++;  } } ?> 

                                  </tbody>
                             </table>
                           </div>                    
                        </div>
                     </div>
                     
              
                </div>

                <input type="hidden" id="propertylistarray_temp" name="propertylistarray_temp">
                


                <div class="row">
                  <div class="col-md-12">

                    <div id="invoicetablediv">
                      <div class="table-responsive table-spraye dash-tbl" style="min-height:auto">
                        <h3>Property List</h3>
                        <table id="search-inputs" class="table">
                          <tr>
                            <td>
                              <input type="text" class="column_filter form-control" id="col_7_filter" data-column="7" placeholder="Zip Code" size="5">
                            </td>
                            <td>
                              <input type="text" class="column_filter form-control" id="col_8_filter" data-column="8" placeholder="City">
                            </td>
                            <td>
                              <input type="text" class="column_filter form-control" id="col_5_filter" data-column="5"placeholder="Property Type">
                            </td>
                            <td>
                              <input type="text" class="column_filter form-control" id="col_10_filter" data-column="10" placeholder="Customer ID">
                            </td>
                            <td>
                              <input type="text" class="column_filter form-control" id="col_4_filter" data-column="4" placeholder="Service Area">
                            </td>
                            <td>
                              <input type="text" class="column_filter form-control" id="col_3_filter" data-column="3" placeholder="Customer Name">
                            </td>
                            <td>
                              <input type="text" class="column_filter form-control" id="col_9_filter" data-column="9" placeholder="Property Status">
                            </td>
                          </tr>
                        </table>
                        <table id="dataTable_propList" class="table datatable-filter-custom" style="max-width:100%;">
                          <thead>
                            <tr>
                              <th>Add/Remove Property</th>
                              <th>Property Title</th>
                              <th>Address</th>
                              <th id="col_custName_filter">Customer Name</th>
                              <th id="col_serviceArea_filter">Service Area</th>
                              <th id="col_propType_filter">Property Type</th>
                              <th>Price Override</th>
                              <th id="col_zipCode_filter">Zip Code</th>
                              <th id="col_propCity_filter">City</th>
                              <th id="col_propStatus_filter">Property Status</th>
                              <th id="col_customer_id">Customer ID</th>
                              <th id="col_customer_email">Customer Email</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $n2 = 1;
                            $ovrIndex = 0;
                            if(!empty($selectedpropertylist))
                            {
                              foreach($selectedpropertylist as $property)
                              {
                                  // die(print_r($property));
                                if(!empty($property->customer_details))
                                { 
                                  //$price_override = (isset($property->is_price_override_set) && $property->is_price_override_set == 1) ? floatval($property->price_override) : ''; ?>
                                  <tr class="selected-row">
                                    <td>
                                      <input type="checkbox" name="property_id" value="<?= $property->property_id; ?>" class="property-selection" checked>
                                    </td>
                                    <td>
                                      <?= $property->property_title; ?>
                                    </td>
                                    <td>
                                      <?= $property->property_address; ?>
                                    </td>
                                    <td>
                                      <?= $property->customer_details['last_name'].", ".$property->customer_details['first_name']; ?>
                                    </td>
                                    <td>
                                      <?= $property->category_area_name ?? 'N/A'; ?>
                                    </td>
                                    <td>
                                      <?= $property->property_type; ?>
                                    </td>
                                    <td class="property-services-overrides" data-property-id="<?= $property->property_id; ?>">
                                      <?php
                                        //$priceOverrideData = $property->priceOverrideData;
                                        $price_override = (isset($property->is_price_override_set) && $property->is_price_override_set == 1) ? floatval($property->price_override) : '';
                                        $overrideFlag = (isset($propertyJobPriceOverrides[$ovrIndex]->is_job_price_override_set)) ? 1 : null;
                                        // print_r($priceOverrideData);
                                        $jobIndex = 0;
                                        foreach ($selecteddata as $key => $value) 
                                        {
                                          if(isset($overrideFlag) && $overrideFlag == 1)
                                          {
                                            $override = $propertyJobPriceOverrides[$ovrIndex]->jobs[$jobIndex]; ?>
                                              <input type="number" min="0" step="0.01" name="sor-<?= $property->property_id.'-'.$value->job_id; ?>" value="<?= $override->price_override ?? ''; ?>" class="inpcl form-control service-price-overrides" placeholder="<?= $value->job_name; ?>" data-propertyjob-ids="<?= $property->property_id.'-'.$value->job_id; ?>">
                                    <?php } elseif(isset($property->is_price_override_set) && $property->is_price_override_set == 1)
                                          { ?>
                                            <input type="number" min="0" step="0.01" name="sor-<?= $property->property_id.'-'.$value->job_id; ?>" value="<?= $price_override ?? ''; ?>" class="inpcl form-control service-price-overrides" placeholder="<?= $value->job_name; ?>" data-propertyjob-ids="<?= $property->property_id.'-'.$value->job_id; ?>">
                                    <?php } else 
                                          { ?>
                                            <input type="number" min="0" step="0.01" name="sor-<?= $property->property_id.'-'.$value->job_id; ?>" value="" class="inpcl form-control service-price-overrides" placeholder="<?= $value->job_name; ?>" data-propertyjob-ids="<?= $property->property_id.'-'.$value->job_id; ?>">  
                                    <?php }
                                          $jobIndex++;
                                        }
                                      ?>
                                    </td>
                                    <td>
                                      <?= $property->property_zip; ?>
                                    </td>
                                    <td>
                                      <?= $property->property_city; ?>
                                    </td>
                                    <td>
                                      <?= $property->property_status; ?>
                                    </td>
                                    <td>
                                      <?= $property->customer_details['customer_id']; ?>
                                    </td>
                                    <td>
                                      <?= $property->customer_details['email']; ?>
                                    </td>
                                  </tr>
                                <?php }
                                $n2++;
                                $ovrIndex++;
                                $selectedValuesProperty[] = $property->property_id;
                                $selectedTextsProperty[] =  $property->property_title;

                                // die(print_r($property));
                                
                                $keyIds[] = array(
                                'property_id' => $property->property_id,
                                'price_override' => $property->price_override ?? "0.00",
                                'is_price_override_set' => $property->is_price_override_set ?? null
                                );                            
                              } 
                            } else
                            {
                              $keyIds= array();
                              $selectedValuesProperty= array();
                              $selectedTextsProperty= array();                              
                            } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>                  
                  </div>
                  <textarea name="propertylistarray" id="assign_property_ids2" style="display:none;" ><?= json_encode($keyIds); ?></textarea>
                  <textarea name="dtSelectedRows" id="dtSelectedRows" style="display:none;" ></textarea>
                  <textarea name="joblistarray" id="joblistarray" style="display: none;" ></textarea>
                  
                </div>
                 

			  <br>
					<div class="row">

					</div>
                </div>
                </fieldset>

                <!-- <div class="text-right">
                  <button type="submit" class="btn btn-success">Save <i class="icon-arrow-right14 position-right"></i></button>
                </div> -->
        <div class="row">
          <div class="form-group col-lg-6">
            <div class="row">
              <input name="status" id="status" style="display: none;">
              <input name="signwell_status" id="signwell_status" style="display: none;">
              <div class="col-lg-4">
                  <button type="submit" class="btn btn-success" id="save_draft">Save as Draft <i class="icon-arrow-right14 position-right"></i></button>
              </div>
              <div class="col-lg-4">
                  <button type="submit" class="btn btn-success" id="submit_estimate">Submit & Send<i class="icon-arrow-right14 position-right"></i></button>                
              </div>
              <?php if($setting_details->signwell_api_key != "") { ?>
                <div class="col-lg-4">
                    <button type="submit" class="btn btn-success btn-outline" id="submit_estimate_signwell">Submit & Send to SignWell<i class="icon-arrow-right14 position-right"></i></button>                
                </div>
               <?php } ?>
            </div>
          </div>          

        </div>                  
              </form>
            </div>
          </div>
          <!-- /form horizontal -->
        </div>
        <!-- /content area -->
<!-- Import DataTablesJS here so we do not have to modify the head -->
<script type="text/javascript">
 	var default_display_length = <?= $this->session->userdata('compny_details')->default_display_length  ?>; 
</script>    
<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script>
let propertyListTable;
$(function() {
  propertyListTable = $('#dataTable_propList').DataTable({
    "paging": false,
    "columnDefs": [
        {
          "targets": [ 7,8,9,10,11 ],
          "searchable": true,
          "visible": false
        }
      ]
  });
  $('input.property-selection').change(function(e) {
    $('#propertylistarray_temp').val(Array.from($('input.property-selection:checked')).map((checkbox) => checkbox.value));
    if(e.target.checked) {
      let row = $(e.target).closest('tr');
      $(row).addClass('selected-row');
      let priceInputs = Array.from($(row).find('.service-price-overrides'));
      priceInputs.forEach((input) => {
        $(input).prop('disabled', false);
      });
    } else {
      let row = $(e.target).closest('tr');
      $(row).removeClass('selected-row');
      let priceInputs = Array.from($(row).find('.service-price-overrides'));
      priceInputs.forEach((input) => {
        $(input).prop('disabled', true);
      });
    }
    getSelectedDtRows();
    buildJobListArray();
  });
  $('input.column_filter').on('keyup click', function() {
    filterColumn($(this).attr('data-column'));
  });
  function filterColumn ( i ) {
    let val = $('#col_'+i+'_filter').val();
    $('#dataTable_propList').DataTable().column( i ).search(
        $('#col_'+i+'_filter').val()
      ).draw();
  }
  function getSelectedDtRows() {
    let propIds = $('#propertylistarray_temp').val().split(",");
    let tmpData = [];
    propIds.forEach((id) => {
      for(let i=0; i<selectedpropertylist.length; i++) {
        if(id == selectedpropertylist[i].property_id) {
          tmpData.push(Object.assign({}, selectedpropertylist[i]));
          break;
        }
      }
    });
    $('#dtSelectedRows').text(JSON.stringify(tmpData));
  }
  $('#propertylistarray_temp').val(Array.from($('input.property-selection:checked')).map((checkbox) => checkbox.value));
  getSelectedDtRows();
  setOverrideEventListeners();
  // Initialize joblistarray in cases where user just goes directly to submit without any interaction.
  buildJobListArray();
});
</script>		
		 
<script type="text/javascript">
   // var selectedValues = [];
   var selectedSortingValues = [];	
   var selectedSortingTexts = [];
   var selectedValues = <?php echo json_encode($selectedjobid); ?>;
   var selectedTexts = <?php echo json_encode($selectedjobname); ?>;
   var optionValue = '';
   var optionText = '';
   $n = Number(<?= count($selecteddata)+1; ?>);

   $(document).on("change","#job_list",function() { 

   // alert("sds");
         
    optionValue   = $(this).val();

    if (optionValue!='') {


     if ($.inArray(optionValue, selectedValues)!='-1') {
     // alert("al");
        
      } else {

        $('.prioritydivcontainer').css("display","block");

        optionText = $("#job_list option:selected").text();
       //  alert(optionValue);
    //     alert(optionText);
      
        selectedValues.push(optionValue);

        selectedTexts.push(optionText);

        var $row = $('<tr id="trid'+$n+'">'+
          '<td class="index">'+$n+'</td>'+
          '<td>'+optionText+'</td>'+
          '<td class="removeclass" id="'+$n+'" optionValueRemove="'+optionValue+'" optionTextRemove="'+optionText+'" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"></i></a></li></ul></td>'+
          '</tr>');    


        $('.prioritytbody:last').append($row);
        $n = $n+1;        
        $('#job_id_order_array').val(selectedValues);
      }
      modifyPriceOverrideInputs();
   } 
});

  $(document).on("click",".removeclass",function() { 

    var id = $(this).attr('id');
    var optionValueRemove = $(this).attr('optionValueRemove');
    var optionTextRemove = $(this).attr('optionTextRemove');
    
    selectedValues.splice($.inArray(optionValueRemove, selectedValues),1);
    selectedTexts.splice($.inArray(optionTextRemove, selectedTexts),1);

    $("#trid"+id).remove();

    $('#job_id_order_array').val(selectedValues);
    rearrangetable();

  });

  


  function rearrangetable() {

    $('.prioritytbody').empty();
     $n = 1;
     $.each(selectedValues, function(i, item) {

       

          var $row = $('<tr id="trid'+$n+'">'+
          '<td class="index">'+$n+'</td>'+
          '<td>'+selectedTexts[i]+'</td>'+
          '<td class="removeclass" id="'+$n+'" optionValueRemove="'+selectedValues[i]+'" optionTextRemove="'+selectedTexts[i]+'" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"></i></a></li></ul></td>'+
          '</tr>');    

        $('.prioritytbody:last').append($row);
  
    $n = $n+1;        
    });
    modifyPriceOverrideInputs();
  }

  function reassign() {
          $(".bootstrap-select").selectpicker('destroy');
          $('.bootstrap-select').selectpicker();
    }

  function modifyPriceOverrideInputs() {
    // Try to save set input values first
    let allInps = Array.from($('.service-price-overrides'));
    let valsArr = [];
    allInps.forEach((inp) => {
      valsArr.push({id:inp.name,val:inp.value});
    });

    let inputCells = Array.from($('td.property-services-overrides'));
    let tableIds = inputCells.map(td => td.dataset.propertyId);
    inputCells.forEach((cell, index) => {
      let cellInputs = '';
      for(let i=0; i<selectedValues.length; i++) {
        let inpName = `sor-${tableIds[index]}-${selectedValues[i]}`;
        let inpVal = '';
        valsArr.forEach((obj) => {
          if(obj.id == inpName) {
            inpVal = obj.val;
          }
        });
        cellInputs += `<input type="number" min="0" step="0.01" name="${inpName}" value="${inpVal}" class="inpcl form-control service-price-overrides" placeholder="${selectedTexts[i]}" data-propertyjob-ids="${tableIds[index]}-${selectedValues[i]}">`;
      }
      $(cell).html(cellInputs);
    });
    setOverrideEventListeners();
    buildJobListArray();
  }
  function setOverrideEventListeners() {
    $('.service-price-overrides').on('change', buildJobListArray);
  };
  function buildJobListArray() {
    let joblistarray = [];
    let arr = Array.from($('tr.selected-row input.service-price-overrides'));
    arr.forEach((input,index) => {
        let tmp = {};
        let ids = input.dataset.propertyjobIds.split('-');
        let propertyId = ids[0];
        let jobId = ids[1];
        tmp.property_id = propertyId;
        tmp.job_id = jobId;
        tmp.price_override = input.value;
        tmp.is_price_override_set = (input.value != '') ? true : null;
        joblistarray.push(tmp);
    });
    
    $('#joblistarray').html(JSON.stringify(joblistarray));
  }
</script>
<script type="text/javascript">	
  var fixHelperModified = function(e, tr) {	
    var $originals = tr.children();	
    var $helper = tr.clone();	
    $helper.children().each(function(index) {	
      $(this).width($originals.eq(index).width())	
    });	
    return $helper;	
  },	
    updateIndex = function(e, ui) {	
    	
      $('td.index', ui.item.parent()).each(function (i) {	
        $(this).html(i+1);	
      });	
      selectedSortingValues = [];	
      selectedSortingTexts = [];	
      $('td.removeclass', ui.item.parent()).each(function (i) {	
        
        // $(this).val(i + 1);	
        selectedSortingValues.push($(this).attr('optionValueRemove'));	
        selectedSortingTexts.push($(this).attr('optionTextRemove'));	
      });	
      selectedValues = selectedSortingValues;	
      selectedTexts = selectedSortingTexts;	
      $('#job_id_order_array').val(selectedValues);	
     	
    };	
  $("#myTable tbody").sortable({	
    helper: fixHelperModified,	
    stop: updateIndex	
  }).disableSelection();	
  	
    $("#myTable tbody").sortable({	
    distance: 5,	
    delay: 100, 	
    opacity: 0.6,	
    cursor: 'move',	
    update: function() {}	
    });	
   </script>
	<!-- for price override -->
<script type="text/javascript">
   var selectedValuesProperty = <?php echo json_encode($selectedValuesProperty) ?>;
   var selectedTextsProperty = <?php echo json_encode($selectedTextsProperty) ?>;
   var keyIds =  <?php echo json_encode($keyIds) ?>;
   var optionValueProperty = '';
   var optionTextProperty = '';
       $n2 = <?php echo  $n2; ?>;
   
   $(function() {	
  		reintlizeMultiselectpropertyPriceOver();	
   });	
	function reintlizeMultiselectpropertyPriceOver() {	
      $(".multiselect-select-all-filtering2").multiselect('destroy');	
        $('.multiselect-select-all-filtering2').multiselect({	
        includeSelectAllOption: true,	
        enableFiltering: true,	
        enableCaseInsensitiveFiltering: true,	
        includeSelectAllOption : false,	
        templates: {
            filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'	
        },	
        onInitialized: function(select, container) {	
       	
           $(".styled, .multiselect-container input").uniform({ radioClass: 'checker'});	
       	
        },	
        onSelectAll: function() {	
            $.uniform.update();	
        },	
       	
        onChange: function(option, checked, select) {	
            if (checked) {
                optionValueProperty =  $(option).val();	
                 if (optionValueProperty!='') {	
               if ($.inArray(optionValueProperty, selectedValuesProperty)!='-1') {	
                // alert('already');	
                  	
                } else {	
                  $('.property-price-over-ride-container').css("display","block");	
                  optionTextProperty = $(option).text();	
                 // alert(optionValueProperty);	
               //   alert(optionTextProperty);	
                	
                  selectedValuesProperty.push(optionValueProperty);	
                  	
                   keyIds.push({'property_id' : optionValueProperty, 'price_override' : 0,'is_price_override_set': null}); 	
                  selectedTextsProperty.push(optionTextProperty);  	
                  inputID =   'inpid'+$n2;	
                  var $row = $('<tr id="tridproperty'+optionValueProperty+'">'+	
                    '<td>'+optionTextProperty+'</td>'+	
                    '<td> <input type="number" min="0" name="tmp'+$n2+'"  class="inpcl form-control" optval="'+optionValueProperty+'"  ></td>'+                    	
                    '</tr>');    	
                  $('.priceoverridetbody:last').append($row);	
                  $n2 = $n2+1;        	
                  // $('#assign_property_ids').val(selectedValuesProperty);	
                        	
                  $('#assign_property_ids2').val(JSON.stringify(keyIds));	
                }	
             } 	
            } else {	
                var id = $(option).val();	
                var optionValuePropertyRemove = $(option).val();	
                var optionTextPropertyRemove = $(option).text();	
                	
                selectedValuesProperty.splice($.inArray(optionValuePropertyRemove, selectedValuesProperty),1);	
                selectedTextsProperty.splice($.inArray(optionTextPropertyRemove, selectedTextsProperty),1);	
                keyIds = $.grep(keyIds, function(e){ 	
                    return e.property_id != optionValuePropertyRemove; 	
                 });	
                $("#tridproperty"+id).remove();	
                // $('#assign_property_ids').val(selectedValuesProperty);	
                $('#assign_property_ids2').val(JSON.stringify(keyIds));	
            }         	
        }	
    });   	
}
 $(document).on("input",".inpcl",function() { 	
      inputvalue  = $(this).val();	
      property_id  = $(this).attr('optval');	
        	
        $.each( keyIds, function( key, value ) {	
          	
           if (property_id == value.property_id) {	
            keyIds[key].price_override = inputvalue;            	
            if(inputvalue != "") {	
              keyIds[key].is_price_override_set = 1;	
            } else {	
              keyIds[key].is_price_override_set = null;	
            }	
           }	
          // alert( key + ": " + value.property_id );	
        }); 	
    $('#assign_property_ids2').val(JSON.stringify(keyIds));	
  }); 
  $(document).ready( function () {
    $('#save_draft').on('click', function(e) {
      // e.preventDefault();
      $('#status').val('0');
    });
    $('#submit_estimate').on('click', function(e) {
      // e.preventDefault();
      $('#status').val('1');
      $('#signwell_status').val('0');
    });  
    $('#submit_estimate_signwell').on('click', function(e) {
    // e.preventDefault();
    $('#status').val('1');
    $('#signwell_status').val('1');
    $("#loading").css("display","block");
  });
    reassign();
  });
</script>
<script>
  let selectedpropertylist = <?= json_encode($selectedpropertylist); ?>;
  let priceOverrideData = <?= json_encode($propertyJobPriceOverrides); ?>;
</script>

