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
   z-index: 99;
   text-align: center;
   }
  .btn-group>.btn:first-child {
    margin-left: 7px;
}
   #loading-image {
   position: absolute;
   left: 50%;
   top: 50%;
   width: 10%;
   z-index: 100;
   }
   .btn-group {
   margin-left: -7px !important;
   margin-top: -1px !important;
   }
   .dropdown-menu {
   min-width: 80px !important;
   }
   .myspan {
   width: 55px;
   }
   .label-warning, .bg-warning {
   background-color :#A9A9A9;
   background-color: #A9A9AA;
   border-color: #A9A9A9;
   }
   .toolbar {
   float: left;
   padding-left: 5px;
   }
   .dataTables_filter {
   /*text-align: center !important;*/
   margin-left: 60px !important;
   }
   #invoicetablediv{
   padding-top: 20px;
   }
   .Invoices .dataTables_filter input {

    margin-left: 11px !important;
    margin-top: 8px !important;
    margin-bottom: 5px !important;
}
.tablemodal > tbody > tr > td, .tablemodal > tbody > tr > th, .tablemodal > tfoot > tr > td, .tablemodal > tfoot > tr > th, .tablemodal > thead > tr > td, .tablemodal > thead > tr > th {
  border-top: 1px solid #ddd;
}


.label-till , .bg-till  {
    background-color: #36c9c9;
    background-color: #36c9c9;
    border-color: #36c9c9;
}

#fleet_table > thead > tr > th:last-of-type, #fleet_table > tbody > tr > td:last-of-type{
    text-align: center;
}

button#addVehicleBtn {
    background-color: #1c86d9;
    color: #fff;
    margin-top: 15px;
    margin-bottom: 15px;
}

</style>
<!-- Content area -->
<div class="content invoicessss">
   <!-- Form horizontal -->
   <div id="loading" >
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
   </div>
   <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
    
      <div class="row cx-dt">
         <div class="col-md-3 col-sm-3 col-12">
            <div class=" service-bols">
               <h3 class="ser-head">Total Fleet Vehicles </h3>
               <p class="text-primary ser-num "><?= count($vehicles) ?></p>
            </div>
         </div>
         <div class="col-md-3 col-sm-3 col-12">
            <div class="service-bols">
               <h3 class="ser-head">Active Fleet Vehicles</h3>
               <p class=" ser-num text-success"><?= count($vehicles) ?></p>
            </div>
         </div>
         <?php
         echo '<pre>';
         print_r($mnt_count);
         die;
         ?>
         <div class="col-md-3 col-sm-3 col-12">
            <div class="service-bols">
               <h3 class="ser-head">Open Maintenance Tickets</h3>
               <p class="text-danger ser-num"><?=  count($mnt_count) ?></p>
            </div>
         </div>
         <div class="col-md-3 col-sm-3 col-12">
           
         </div>
      </div>

      <div class="row">
         <div class="col-md-6"></div>
         <div class="col-md-6 text-right">
            <button type="button"  class="btn" id="addVehicleBtn" data-target="#vehicle-form-wrap" data-toggle="collapse" aria-expanded="false" aria-controls="vehicle-form-wrap"> <i id="addVehicleBtnIco" class="icon-plus22"></i> Add New Vehicle</button>                            
         </div>         
      </div>

      <div id="vehicle-form-wrap" class="collapse">
         <form class="form-horizontal" action="<?= base_url('admin/addVehicle')  ?>" method="post" name="addVehicle" enctype="multipart/form-data" >
            <fieldset class="content-group">
               <div class="row invoice-form">

                   <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Vehicle VIN</label>
                        <div class="col-lg-9">
                           <input type="text" name="v_vin" class="form-control text-caps" value="" id="v_vin" maxlength="17" placeholder="1FUPDXYB3PP469921" required>
                        </div>
                     </div>
                  </div>

                 
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">License Plate</label>
                        <div class="col-lg-9">
                           <input type="text" name="v_plate" class="form-control text-caps" id="v_plate" maxlength="7" placeholder="AJFL70" required>
                        </div>
                     </div>
                  </div>

               </div>
               <div class="row invoice-form">

                   <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Primary Use</label>
                        <div class="col-lg-9">
                           <input type="text" name="v_type" class="form-control" id="v_type" required>
                           <!-- <select class="bootstrap-select form-control" data-live-search="true" name="v_type" id="v_type" required>
                              <option disabled selected value> -- Select Type -- </option>
                              <option value="car">Car</option>
                              <option value="semi-truck">Semi-Truck</option>
                              <option value="pickup-truck">Pickup Truck</option>
                              <option value="trailer">Trailer</option>
                              <option value="van">Van</option>
                              <option value="forklift">Forklift</option>
                           </select> -->
                        </div>
                     </div>
                  </div>

                 
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Vehicle Make</label>
                        <div class="col-lg-9">
                           <input type="text" name="v_make" id="v_make" class="form-control" placeholder="Ford" required>
                        </div>
                     </div>
                  </div>

               </div>
               <div class="row invoice-form">

                   <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Vehicle Model</label>
                        <div class="col-lg-9">
                           <input type="text" name="v_model" class="form-control" value="" id="v_model" placeholder="F-150" required>
                        </div>
                     </div>
                  </div>

                 
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Vehicle Year</label>
                        <div class="col-lg-9">
                           <input type="number" name="v_year" class="form-control" id="v_year" minlength="4" maxlength="4" min="1970" max="<?php echo date("Y"); ?>" placeholder="<?php echo date("Y"); ?>" required>
                        </div>
                     </div>
                  </div>

               </div>
               <div class="row invoice-form">

                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Vehicle Name</label>
                        <div class="col-lg-9">
                           <input type="text" name="v_name" class="form-control" value="" id="v_name" placeholder="Work Truck 1">
                        </div>
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Fleet Number</label>
                        <div class="col-lg-9">
                           <input type="text" name="fleet_number" class="form-control" value="" id="fleet_number" placeholder="Fleet Number">
                        </div>
                     </div>
                  </div>

               </div>               

               <br>
            </fieldset>
            <div class="text-center">
              <button type="submit" class="btn btn-warning"><i class="icon-undo position-left"></i> Reset
              </button>
              <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i>
              </button>
            </div>
          </form>
         <hr>
      </div>      

      <div id="invoicetablediv">
         <div  class="table-responsive table-spraye">
            <table class="table" id="fleet_table">
               <thead>
                  <tr>
                     <th>Fleet Number</th>
                     <th>Name</th>
                     <th>VIN #</th>
                     <th>Plate #</th>
                     <th>Primary Use</th>
                     <th>Make</th>
                     <th>Model</th>
                     <th>Year</th>
                     <th>Assigned Tech</th>
                     <th>Manage Vehicle</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if (!empty($vehicles)) { 
                     foreach ($vehicles as $vehicle) { ?>
                  <tr>
                     <td><?= $vehicle->fleet_number ?? '' ?></td>
                     <td><?= $vehicle->v_name ?? '' ?></td>
                     <td><?= $vehicle->v_vin  ?></td>
                     <td><?= $vehicle->v_plate  ?></td>
                     <td><?= $vehicle->v_type  ?></td>
                     <td><?= $vehicle->v_make  ?></td>
                     <td><?= $vehicle->v_model  ?></td>
                     <td><?= $vehicle->v_year  ?></td>
                     <td><?= (isset($vehicle->v_assigned_user)) ? $vehicle->user_first_name.' '.$vehicle->user_last_name : 'NONE'; ?></td>
                     <td><a class="btn btn-info" role="button" href="<?= base_url('admin/viewSingleVehicle/'.$vehicle->fleet_id) ?>">Select Vehicle</a></td>
                  </tr>
                  <?php  }  } ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
<!-- /form horizontal -->

<div id="modal_default" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title" style="float: left;">Product Details</h5>

         </div>
         <div class="modal-body" id="productdetails">

        
        

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>

<!-- /content area -->

<script>


var fleetTable = $('#fleet_table').DataTable({
    "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
    "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]]
   });

</script>

<script>
  $('#vehicle-form-wrap').on('show.bs.collapse', () => {
    $('#addVehicleBtnIco').removeClass('icon-plus22')
      .addClass('fa fa-minus');
  });        
  $('#vehicle-form-wrap').on('hide.bs.collapse', () => {
    $('#addVehicleBtnIco').removeClass('fa fa-minus')
      .addClass('icon-plus22');
  });   
</script>

