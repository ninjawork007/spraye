<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Vehicle Inspection</title>
</head>
<body>
   <?php
     $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;
      $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();
      // die(var_dump($insp_report));
  ?>

                <div class="modal-body">
                    <div class="form-section">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Driver</label>
                                <input type="text" class="form-control" placeholder="" value="<?= $currentUser->user_first_name.' '.$currentUser->user_last_name; ?>" readonly>
                                <input type="hidden" name="driver_id" id="driver_id" value="<?= $currentUser->id; ?>">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Date</label>
                                <input type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control" id="date" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Truck #</label>
                                <input type="number" class="form-control" id="truck_number" name="truck_number" value="" required>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Odometer Reading</label>
                                <input type="number" class="form-control" id="odometer" name="odometer" required>
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
                                                <input type="checkbox" name="chk_ac_heater" id="chk_ac_heater"> Air Conditioner / Heater
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_battery" id="chk_battery"> Battery
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_vhcl_body" id="chk_vhcl_body"> Body (check for any damage - list below)
                                            </label>
                                        </div>
                                        <input type="text" class="form-control" id="text_vhcl_body" name="text_vhcl_body">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_brakes" id="chk_brakes"> Brakes, Service
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_brakes_parking" id="chk_brakes_parking"> Brakes, Parking
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_horn" id="chk_horn"> Horn
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_lights" id="chk_lights"> Lights <br>
                                                Head - Stop <br>
                                                Tail - Dash <br>
                                                Turn Indicators - Flashers
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_mirrors" id="chk_mirrors"> Mirrors
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_oil" id="chk_oil"> Oil level / Pressure
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_coolant" id="chk_coolant"> Radiator / Coolant Level
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_trans_fld" id="chk_trans_fld"> Transmission Fluid
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_windows" id="chk_windows"> Windows / Windshield
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Other:</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_backpack" id="chk_backpack"> Backpack (Check for leaks and Spray nozzle)
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_blower" id="chk_blower"> Blower
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_tool_kit" id="chk_tool_kit"> Tool kit
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Trailer:</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_trailer_brakes" id="chk_trailer_brakes"> Brakes
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_trailer_coupling" id="chk_trailer_coupling"> Coupling Devices
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_trailer_hitch" id="chk_trailer_hitch"> Hitch
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_trailer_lights" id="chktrailer_lights"> Lights (All)
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_trailer_tires" id="chk_trailer_tires"> Tires / Wheels
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Notes:</label>
                                    <div class="col-sm-12">
                                        <textarea name="vehicle_inspection_form_notes" id="vehicle_inspection_form_notes" class="form-control" cols="" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <label>Pump(s):</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_pump_hoses" id="chk_pump_hoses"> Hoses
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_pump_spray" id="chk_pump_spray"> Spray Gun / Probe
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_pump_oil" id="chk_pump_oil"> Oil Level
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_pump_tank" id="chk_pump_tank"> Tank
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_pump_lid" id="chk_pump_lid"> Lid(s)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Safety:</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_safety_gloves" id="chk_safety_gloves"> Gloves
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_safety_boots" id="chk_safety_boots"> Boots
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_safety_spill_kit" id="chk_safety_spill_kit"> Spill Kit
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_safety_eye_wash" id="chk_safety_eye_wash"> Eye Wash
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_safety_firstaid_kit" id="chk_safety_firstaid_kit"> First Aid Kit
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_safety_glasses" id="chk_safety_glasses"> Safety Glasses
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Spreader(s):</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_spreader_hopper" id="chk_spreader_hopper"> Hopper
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_spreader_grease" id="chk_spreader_grease"> Greased
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_spreader_impellor" id="chk_spreader_impellor"> Impellor
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_spreader_cotterpins" id="chk_spreader_cotterpins"> Cotter Pins
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_spreader_wheels" id="chk_spreader_wheels"> Wheels
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_spreader_cover" id="chk_spreader_cover"> Cover
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="chk_spreader_secured" id="chk_spreader_secured"> Secured to vehicle
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Tires:</label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="chk_tire_left_front" name="chk_tire_left_front"> Left Front <input type="number" id="tire_left_front_psi" name="tire_left_front_psi"> psi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="chk_tire_right_front" name="chk_tire_right_front"> Right Front <input type="number" id="tire_right_front_psi" name="tire_right_front_psi"> psi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="chk_tire_left_rear" name="chk_tire_left_rear"> Left Rear <input type="number" id="tire_left_rear_psi" name="tire_left_rear_psi"> psi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="chk_tire_right_rear" name="chk_tire_right_rear"> Right Rear <input type="number" id="tire_right_rear_psi" name="tire_right_rear_psi"> psi
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Last Calibration Date:</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input type="date" id="spreaders_calibration_date" name="spreaders_calibration_date"> Spreader(s)
                                        </div>
                                        <div class="input-group">
                                            <input type="date" id="pump_calibration_date" name="pump_calibration_date"> Pump
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Vehicle Registration Current:</label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                          <input type="radio" name="vehicle_registration_current" id="vehicle_registration_current1" value="Y"> Y
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="vehicle_registration_current" id="vehicle_registration_current2" value="N"> N
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Copy of Current TDA Direct Supervisor Affidavit:</label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                          <input type="radio" name="tda_supervisor_affidavit_current" id="tda_supervisor_affidavit_current1" value="Y"> Y
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="tda_supervisor_affidavit_current" id="tda_supervisor_affidavit_current2" value="N"> N
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>The Condition Of The Above Vehicle Is Satisfactory:</label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                          <input type="radio" name="vehicle_condition_satisfactory" id="vehicle_condition_satisfactory1" value="Y"> Y
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="vehicle_condition_satisfactory" id="vehicle_condition_satisfactory2" value="N"> N
                                        </label>
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
                    

</body>
</html>