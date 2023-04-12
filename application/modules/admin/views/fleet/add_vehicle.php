

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

  .text-caps {
     text-transform: uppercase;
  }

  @media (min-width: 769px){
.form-horizontal .control-label[class*=col-sm-] {
    padding-top: 0;
}}

</style>


<!-- Content area -->
<div class="content form-pg ">
   <!-- Form horizontal -->
   <div class="panel panel-flat">
      <div class="panel-heading">
         <h5 class="panel-title">
            <div class="form-group">
               <a href="<?= base_url('admin/allVehicles') ?>"  id="backToVehicles" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Fleet Vehicles</a>
            </div>
         </h5>
      </div>
      <br>
      <div class="panel-body">
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
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
                        <label class="control-label col-lg-3">Vehicle Type</label>
                        <div class="col-lg-9">
                           <select class="bootstrap-select form-control" data-live-search="true" name="v_type" id="v_type" required>
                              <option disabled selected value> -- Select Type -- </option>
                              <option value="car">Car</option>
                              <option value="semi-truck">Semi-Truck</option>
                              <option value="pickup-truck">Pickup Truck</option>
                              <option value="trailer">Trailer</option>
                              <option value="van">Van</option>
                              <option value="forklift">Forklift</option>
                           </select>
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

               <br>
            </fieldset>
            <div class="text-center">
              <button type="submit" class="btn btn-warning"><i class="icon-undo position-left"></i> Reset
              </button>
              <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i>
              </button>
            </div>
          </form>
      </div>
   </div>
</div>
<!-- /form horizontal -->