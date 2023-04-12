

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

  .readonly-form {
    display: grid;
    justify-content: space-around;
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
               <a href="<?= base_url('admin/viewSingleVehicle/'.$inspection->truck_number) ?>"  id="backToVehicle" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Back to Fleet Vehicle #<?= $inspection->truck_number ?></a>
            </div>
         </h5>
      </div>
      <br>
      <div class="panel-body readonly-form">
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
        

            <form name="vehicle_inspection_report" id="vehicle_inspection_report">
                    <div class="form-section">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Driver</label>
                                <input type="text" class="form-control" placeholder="" value="<?= $inspection->user_first_name.' '.$inspection->user_last_name; ?>" readonly>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Date</label>
                                <input type="text" value="<?=  explode( ' ', $inspection->insp_created_at )[0] ?>" class="form-control" id="date" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Truck #</label>
                                  <input type="number" class="form-control" id="truck_number" name="truck_number" value="<?= $inspection->truck_number ?>" readonly>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Odometer Reading</label>
                                <input type="number" class="form-control" id="odometer" name="odometer" value="<?= $inspection->odometer ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <div class="row">
                            <div class="col-sm-6">
                              <label>Vehicle:</label>
                              <div class="row">
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_ac_heater == '1') ? ' checked' : ''; ?> id="chk_ac_heater"> Air Conditioner / Heater
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_battery == '1') ? ' checked' : ''; ?> id="chk_battery"> Battery
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_vhcl_body == '1') ? ' checked' : ''; ?> id="chk_vhcl_body"> Body (check for any damage - list below)
                                            </label>
                                        </div>
                                        <input type="text" class="form-control" id="text_vhcl_body" value="<?= $inspection->text_vhcl_body ?>" readonly>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_brakes == '1') ? ' checked' : ''; ?> id="chk_brakes"> Brakes, Service
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_brakes_parking == '1') ? ' checked' : ''; ?> id="chk_brakes_parking"> Brakes, Parking
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_horn == '1') ? ' checked' : ''; ?> id="chk_horn"> Horn
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_lights == '1') ? ' checked' : ''; ?> id="chk_lights"> Lights <br>
                                                Head - Stop <br>
                                                Tail - Dash <br>
                                                Turn Indicators - Flashers
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_mirrors == '1') ? ' checked' : ''; ?> id="chk_mirrors"> Mirrors
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_oil == '1') ? ' checked' : ''; ?> id="chk_oil"> Oil level / Pressure
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_coolant == '1') ? ' checked' : ''; ?> id="chk_coolant"> Radiator / Coolant Level
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_trans_fld == '1') ? ' checked' : ''; ?> id="chk_trans_fld"> Transmission Fluid
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_windows == '1') ? ' checked' : ''; ?> id="chk_windows"> Windows / Windshield
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Other:</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_backpack == '1') ? ' checked' : ''; ?> id="chk_backpack"> Backpack (Check for leaks and Spray nozzle)
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_blower == '1') ? ' checked' : ''; ?> id="chk_blower"> Blower
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_tool_kit == '1') ? ' checked' : ''; ?> id="chk_tool_kit"> Tool kit
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Trailer:</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_trailer_brakes == '1') ? ' checked' : ''; ?> id="chk_trailer_brakes"> Brakes
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_trailer_coupling == '1') ? ' checked' : ''; ?> id="chk_trailer_coupling"> Coupling Devices
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_trailer_hitch == '1') ? ' checked' : ''; ?> id="chk_trailer_hitch"> Hitch
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_trailer_lights == '1') ? ' checked' : ''; ?> id="chktrailer_lights"> Lights (All)
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_trailer_tires == '1') ? ' checked' : ''; ?> id="chk_trailer_tires"> Tires / Wheels
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Notes:</label>
                                    <div class="col-sm-12">
                                        <textarea id="vehicle_inspection_form_notes" class="form-control" cols="" rows="10" readonly><?= $inspection->vehicle_inspection_form_notes; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <label>Pump(s):</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_pump_hoses == '1') ? ' checked' : ''; ?> id="chk_pump_hoses"> Hoses
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_pump_spray == '1') ? ' checked' : ''; ?> id="chk_pump_spray"> Spray Gun / Probe
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_pump_oil == '1') ? ' checked' : ''; ?> id="chk_pump_oil"> Oil Level
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_pump_tank == '1') ? ' checked' : ''; ?> id="chk_pump_tank"> Tank
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_pump_lid == '1') ? ' checked' : ''; ?> id="chk_pump_lid"> Lid(s)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Safety:</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_safety_gloves == '1') ? ' checked' : ''; ?> id="chk_safety_gloves"> Gloves
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_safety_boots == '1') ? ' checked' : ''; ?> id="chk_safety_boots"> Boots
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_safety_spill_kit == '1') ? ' checked' : ''; ?> id="chk_safety_spill_kit"> Spill Kit
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_safety_eye_wash == '1') ? ' checked' : ''; ?> id="chk_safety_eye_wash"> Eye Wash
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_safety_firstaid_kit == '1') ? ' checked' : ''; ?> id="chk_safety_firstaid_kit"> First Aid Kit
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_safety_glasses == '1') ? ' checked' : ''; ?> id="chk_safety_glasses"> Safety Glasses
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Spreader(s):</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_spreader_hopper == '1') ? ' checked' : ''; ?> id="chk_spreader_hopper"> Hopper
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_spreader_grease == '1') ? ' checked' : ''; ?> id="chk_spreader_grease"> Greased
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_spreader_impellor == '1') ? ' checked' : ''; ?> id="chk_spreader_impellor"> Impellor
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_spreader_cotterpins == '1') ? ' checked' : ''; ?> id="chk_spreader_cotterpins"> Cotter Pins
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_spreader_wheels == '1') ? ' checked' : ''; ?> id="chk_spreader_wheels"> Wheels
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_spreader_cover == '1') ? ' checked' : ''; ?> id="chk_spreader_cover"> Cover
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled<?= ($inspection->chk_spreader_secured == '1') ? ' checked' : ''; ?> id="chk_spreader_secured"> Secured to vehicle
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Tires:</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled id="chk_tire_left_front"<?= ($inspection->chk_tire_left_front) ? ' checked' : ''; ?>> Left Front <br><input type="number" id="tire_left_front_psi" value="<?= $inspection->tire_left_front_psi ?>" class="form-control-custom" readonly> psi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled id="chk_tire_right_front"<?= ($inspection->chk_tire_right_front) ? ' checked' : ''; ?>> Right Front <br><input type="number" id="tire_right_front_psi" value="<?= $inspection->tire_right_front_psi ?>" class="form-control-custom" readonly> psi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled id="chk_tire_left_rear"<?= ($inspection->chk_tire_left_rear) ? ' checked' : ''; ?>> Left Rear <br><input type="number" id="tire_left_rear_psi" value="<?= $inspection->tire_left_rear_psi ?>" class="form-control-custom" readonly> psi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled id="chk_tire_right_rear"<?= ($inspection->chk_tire_right_rear) ? ' checked' : ''; ?>> Right Rear <br><input type="number" id="tire_right_rear_psi" value="<?= $inspection->tire_right_rear_psi ?>" class="form-control-custom" readonly> psi
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Last Calibration Date:</label>
                                    <div class="col-sm-12">
                                        <div class="input-group" style="margin-bottom: 5px;">
                                            <input type="text" id="spreaders_calibration_date" value="<?= $inspection->spreaders_calibration_date; ?>" class="form-control-custom" readonly> Spreader(s)
                                        </div>
                                        <div class="input-group" style="margin-bottom: 5px;">
                                            <input type="text" id="pump_calibration_date" value="<?= $inspection->pump_calibration_date; ?>" class="form-control-custom" readonly> Pump
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Vehicle Registration Current:</label>
                                    <div class="col-sm-12">
                                       <?php if( $inspection->vehicle_registration_current == '1') { ?>

                                        <label class="radio-inline">
                                          <input type="radio" name="vehicle_registration_current" id="vehicle_registration_current1" value="Y" checked disabled> Y
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="vehicle_registration_current" id="vehicle_registration_current2" value="N" disabled> N
                                        </label>

                                        <?php } else { ?>

                                        <label class="radio-inline">
                                          <input type="radio" name="vehicle_registration_current" id="vehicle_registration_current1" value="Y" disabled> Y
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="vehicle_registration_current" id="vehicle_registration_current2" value="N" checked disabled> N
                                        </label>

                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Copy of Current TDA Direct Supervisor Affidavit:</label>
                                    <div class="col-sm-12">
                                       <?php if($inspection->tda_supervisor_affidavit_current == '1') { ?>

                                        <label class="radio-inline">
                                          <input type="radio" name="tda_supervisor_affidavit_current" id="tda_supervisor_affidavit_current1" value="Y" checked disabled> Y
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="tda_supervisor_affidavit_current" id="tda_supervisor_affidavit_current2" value="N" disabled> N
                                        </label>

                                        <?php } else { ?>

                                       <label class="radio-inline">
                                          <input type="radio" name="tda_supervisor_affidavit_current" id="tda_supervisor_affidavit_current1" value="Y" disabled> Y
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="tda_supervisor_affidavit_current" id="tda_supervisor_affidavit_current2" value="N" checked disabled> N
                                        </label>

                                          <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>The Condition Of The Above Vehicle Is Satisfactory:</label>
                                    <div class="col-sm-12">
                                       <?php if($inspection->vehicle_condition_satisfactory == '1') { ?>

                                        <label class="radio-inline">
                                          <input type="radio" name="vehicle_condition_satisfactory" id="vehicle_condition_satisfactory1" value="Y" checked disabled> Y
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="vehicle_condition_satisfactory" id="vehicle_condition_satisfactory2" value="N" disabled> N
                                        </label>

                                        <?php } else { ?>

                                       <label class="radio-inline">
                                          <input type="radio" name="vehicle_condition_satisfactory" id="vehicle_condition_satisfactory1" value="Y" disabled> Y
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="vehicle_condition_satisfactory" id="vehicle_condition_satisfactory2" value="N" checked disabled> N
                                        </label>

                                          <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                          <div class="col-sm-6"><b>Updated 7.23.21</b></div>
                          <div class="col-sm-6"><b>Form 9.01</b></div>
                        </div>
                    </div>
            </form>         


      </div>
   </div>
</div>
<!-- /form horizontal -->

