<style>
    .btn-technician {
        position: relative;
        color: #fff;
        background-color: #3379b740;
        border-color: #3379b740;
        display: inline-block;
        margin-bottom: 0;
        font-weight: 500;
        text-align: center;
        vertical-align: middle;
        touch-action: manipulation;
        cursor: pointer;
        border: 1px solid transparent;
        border-top-color: transparent;
        border-right-color: transparent;
        border-bottom-color: transparent;
        border-left-color: transparent;
        white-space: nowrap;
        padding: 15px 12px;
        font-size: 14px;
        line-height: 1.5384616;
        border-radius: 5px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        width: 100%;
    }

    .btn-technician:hover {
        color: #fff;
    }

    .tecnician-btn {
        padding-top: 10px;
    }

    .finish_btn_color {
        color: #fff;
        background-color: #dfdedc !important;
        border-color: #a09f9d;
    }

    .activeRoute {
        background: #29c1a8;
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
        z-index: 99;
        text-align: center;
    }

    #loading-image {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 10%;
        z-index: 100;
    }

    body {
        overflow-x: hidden;
    }
</style>

<style>
    /* Set the size of the div element that contains the map */
    /* Set the size of the div element that contains the map */
    #routeMap {
        height: 100%;
        /* The height is 400 pixels */
        /*        width: 100%;*/
        padding-top: 100%;
        /* The width is the width of the web page */
        /*margin-top: 10px;*/
        /*margin-bottom: 10px;*/
        margin: 20px;
    }

    .techmessage {
        padding-top: 5px;
        padding-left: 5px;
        padding-right: 5px;
    }

    .form-control-custom {
        border: 1px solid #12689b;
        border-radius: 3px;
        background-color: transparent;
    }
</style>
<!-- Primary modal -->
<div id="modal_mileage" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-light">Mileage Information</h6>
                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mileageInfo">Mileage</label>
                            <p id="mileageInfo" class="form-control-static"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="driveTimeInfo">Drive Time</label>
                            <p id="driveTimeInfo" class="form-control-static"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /primary modal -->
<?php
$alldata = array();
$Locations = array();
$statLocation = array(
    'Name' => $currentaddress,
    'Latitude' => $currentlat,
    'Longitude' => $currentlong
);
if ($this->session->userdata['spraye_technician_login']->end_location != "") {
    $endLocation = array(
        'Name' => $this->session->userdata['spraye_technician_login']->end_location,
        'Latitude' => $this->session->userdata['spraye_technician_login']->end_location_lat,
        'Longitude' => $this->session->userdata['spraye_technician_login']->end_location_long,
    );
} else {
    $endLocation = array(
        'Name' => $setting_details->end_location,
        'Latitude' => $setting_details->end_location_lat,
        'Longitude' => $setting_details->end_location_long
    );
}
if (!empty($job_assign_details)) {
    foreach ($job_assign_details as $key => $value) {
        $Locations[$key]['Name'] = $value[0]['property_address'];
        $Locations[$key]['Latitude'] = $value[0]['property_latitude'];
        $Locations[$key]['Longitude'] = $value[0]['property_longitude'];
    }
    array_unshift($Locations, $statLocation);
    array_push($Locations, $endLocation);
}
$OptimizeParameters = array(
    "AppId" => RootAppId,
    "OptimizeType" => "distance",
    "RouteType" => "realroadcar",
    "Avoid" => "none",
    "Departure" => "2020-05-23T17:00:00"
);
$alldata['Locations'] = $Locations;
$alldata['OptimizeParameters'] = $OptimizeParameters;
?>
<div class="content">
    <div id="loading">
        <img id="loading-image" src="<?= base_url('') ?>assets/loader.gif"/> <!-- Loading Image -->
    </div>
    <div class="techmessage">
        <b><?php if ($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
    </div>
    <?php
    $class1 = !empty($job_assign_details) ? 'start_day' : '';
    $class2 = !empty($job_assign_details) ? 'next_stop' : '';
    ?>
    <div style="background:#f6f7f9;padding-left:10px;padding-right: 10px;">
        <?php $btn_class = 'col-lg-4 col-md-4 col-sm-4 col-xs-4' ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="<?= $btn_class ?>">
                        <div class="tecnician-btn tab-btn">
                            <a class="<?= $class1 ?> label btn-technician green-btn" href="#"
                               style="<?= ($is_first_job) ? '' : 'display:none;' ?>">START DAY</a>
                        </div>
                    </div>
                    <div class="<?= $btn_class ?>">
                        <div class="tecnician-btn tab-btn">
                            <a class="finishday label btn-technician btn-primary" href="#" style="background: #ef6c00;">FINISH
                                DAY</a>
                        </div>
                    </div>
                    <div class="<?= $btn_class ?>">
                        <div class="tecnician-btn tab-btn">
                            <a data-toggle="modal" class="vehicle_issue fas fa-plus label btn-technician btn-primary"
                               style="background-color:#FFBE2C;" href="#modal_vehicle_issue" id="start_vehicle_issue">VEHICLE
                                ISSUE </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php

            if (!empty($routeDetails)) {
                foreach ($routeDetails as $key => $value) {
                    if ($value['route_id'] == $current_route) {
                        $activeclass = 'activeRoute';
                    } else {
                        $activeclass = '';
                    }
                    echo  '
                    <div class="col-lg-10 col-md-10 col-sm-10">
                        <div class="tecnician-btn">
                            <a class="btn-technician '.$activeclass.'" href="'. base_url().'technician/dashboard/'.$value['route_id'].'" >'.$value['route_name'].'</a>
                        </div>
                    </div>';
                        echo  '
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <div class="tecnician-btn">
                            <a class="btn-technician '.$activeclass.'" onclick="showMileageInfo()" >Get Mileage</a>
                        </div>
                    </div>';
                }
            }
            ?>
        </div>
    </div>
    <textarea id="postTestRequest" style="display: none;"><?php echo json_encode($alldata) ?></textarea>
    <div id="geturl" class="row element" style="display: none;">
        <h5></h5>
        <div id="get_url"></div>
    </div>
    <div id="jsonresult" class="row element" style="display: none;">
        <h5></h5>
        <pre id="result"></pre>
    </div>
    <div class="row">
        <div id='routeMap' style=''></div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12" style="background-color: #e6e6e6 !important">
            <table cellspacing="5" cellpadding="5" width="50%">
                <tr>
                    <td>
                        <h6 class="text-semibold" style="padding-left:5px">Day</h6>
                    </td>
                    <td>
                        <h6 class="text-semibold">Service</h6>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <!-- Left aligned -->
            <div class="panel panel-body border-top-teal"
                 style="background-color: #f6f7f9 !important;padding-top: 0px !important;">
                <ul class="list-feed list-feed-time" id="from_jquery_sorting">
                    <?php if (empty($job_assign_details)) { ?>
                        <div class="media-body text-center">
                            <h3 class="no-margin text-semibold">No Services available</h3>
                        </div>
                    <?php } ?>
                </ul>
            </div>
            <!-- /left aligned -->
        </div>
    </div>

    <?php
    $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;
    $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();
    ?>
    <!-- Inspection Modal -->

    <div id="modal_vehicle_inspection" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Vehicle Inspection Report</h6>
                </div>
                <form action="" name="vehicle_inspection_report" id="vehicle_inspection_report" method="">
                    <div class="modal-body">
                        <div class="form-section">
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Driver</label>
                                    <input type="text" class="form-control" placeholder=""
                                           value="<?= $currentUser->user_first_name . ' ' . $currentUser->user_last_name; ?>"
                                           readonly>
                                    <input type="hidden" name="driver_id" id="driver_id"
                                           value="<?= $currentUser->id; ?>">
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Date</label>
                                    <input type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control"
                                           id="date" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 form-group">

                                    <label>Truck</label>
                                    <select class="form-control" name="truck_number" id="truck_number" required>
                                        <?php if (is_null($assigned_vehicle)) : ?>
                                            <option value="" disabled selected></option>
                                        <?php endif; ?>
                                        <?php foreach ($vehicles as $value) : ?>
                                            <option
                                                value="<?= $value->fleet_id; ?>"<?= ($assigned_vehicle == $value->fleet_id) ? ' selected' : ''; ?>><?= $value->fleet_id; ?>
                                                : <?= $value->v_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
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
                                                    <input type="checkbox" name="chk_ac_heater" id="chk_ac_heater"> Air
                                                    Conditioner / Heater
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_battery" id="chk_battery"> Battery
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_vhcl_body" id="chk_vhcl_body"> Body
                                                    (check for any damage - list below)
                                                </label>
                                            </div>
                                            <input type="text" class="form-control" id="text_vhcl_body"
                                                   name="text_vhcl_body">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_brakes" id="chk_brakes"> Brakes,
                                                    Service
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_brakes_parking"
                                                           id="chk_brakes_parking"> Brakes, Parking
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_horn" id="chk_horn"> Horn
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_lights" id="chk_lights"> Lights
                                                    <br>
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
                                                    <input type="checkbox" name="chk_oil" id="chk_oil"> Oil level /
                                                    Pressure
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_coolant" id="chk_coolant"> Radiator
                                                    / Coolant Level
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_trans_fld" id="chk_trans_fld">
                                                    Transmission Fluid
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_windows" id="chk_windows"> Windows
                                                    / Windshield
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label>Other:</label>
                                        <div class="col-sm-12">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_backpack" id="chk_backpack">
                                                    Backpack (Check for leaks and Spray nozzle)
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_blower" id="chk_blower"> Blower
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_tool_kit" id="chk_tool_kit"> Tool
                                                    kit
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label>Trailer:</label>
                                        <div class="col-sm-12">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_trailer_brakes"
                                                           id="chk_trailer_brakes"> Brakes
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_trailer_coupling"
                                                           id="chk_trailer_coupling"> Coupling Devices
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_trailer_hitch"
                                                           id="chk_trailer_hitch"> Hitch
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_trailer_lights"
                                                           id="chktrailer_lights"> Lights (All)
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_trailer_tires"
                                                           id="chk_trailer_tires"> Tires / Wheels
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Notes:</label>
                                        <div class="col-sm-12">
                                            <textarea name="vehicle_inspection_form_notes"
                                                      id="vehicle_inspection_form_notes" class="form-control" cols=""
                                                      rows="10"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <label>Pump(s):</label>
                                        <div class="col-sm-12">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_pump_hoses" id="chk_pump_hoses">
                                                    Hoses
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_pump_spray" id="chk_pump_spray">
                                                    Spray Gun / Probe
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_pump_oil" id="chk_pump_oil"> Oil
                                                    Level
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
                                                    <input type="checkbox" name="chk_safety_gloves"
                                                           id="chk_safety_gloves"> Gloves
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_safety_boots"
                                                           id="chk_safety_boots"> Boots
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_safety_spill_kit"
                                                           id="chk_safety_spill_kit"> Spill Kit
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_safety_eye_wash"
                                                           id="chk_safety_eye_wash"> Eye Wash
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_safety_firstaid_kit"
                                                           id="chk_safety_firstaid_kit"> First Aid Kit
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_safety_glasses"
                                                           id="chk_safety_glasses"> Safety Glasses
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label>Spreader(s):</label>
                                        <div class="col-sm-12">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_spreader_hopper"
                                                           id="chk_spreader_hopper"> Hopper
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_spreader_grease"
                                                           id="chk_spreader_grease"> Greased
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_spreader_impellor"
                                                           id="chk_spreader_impellor"> Impellor
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_spreader_cotterpins"
                                                           id="chk_spreader_cotterpins"> Cotter Pins
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_spreader_wheels"
                                                           id="chk_spreader_wheels"> Wheels
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_spreader_cover"
                                                           id="chk_spreader_cover"> Cover
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="chk_spreader_secured"
                                                           id="chk_spreader_secured"> Secured to vehicle
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label>Tires:</label>
                                        <div class="col-sm-12">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="chk_tire_left_front"
                                                           name="chk_tire_left_front"> Left Front <br><input
                                                        type="number" id="tire_left_front_psi"
                                                        name="tire_left_front_psi" class="form-control-custom"> psi
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="chk_tire_right_front"
                                                           name="chk_tire_right_front"> Right Front <br><input
                                                        type="number" id="tire_right_front_psi"
                                                        name="tire_right_front_psi" class="form-control-custom"> psi
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="chk_tire_left_rear"
                                                           name="chk_tire_left_rear"> Left Rear <br><input type="number"
                                                                                                           id="tire_left_rear_psi"
                                                                                                           name="tire_left_rear_psi"
                                                                                                           class="form-control-custom">
                                                    psi
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="chk_tire_right_rear"
                                                           name="chk_tire_right_rear"> Right Rear <br><input
                                                        type="number" id="tire_right_rear_psi"
                                                        name="tire_right_rear_psi" class="form-control-custom"> psi
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label>Last Calibration Date:</label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <input type="date" id="spreaders_calibration_date"
                                                       name="spreaders_calibration_date" class="form-control-custom">
                                                Spreader(s)
                                            </div>
                                            <div class="input-group">
                                                <input type="date" id="pump_calibration_date"
                                                       name="pump_calibration_date" class="form-control-custom"> Pump
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label>Vehicle Registration Current:</label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="vehicle_registration_current"
                                                       id="vehicle_registration_current1" value="Y"> Y
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vehicle_registration_current"
                                                       id="vehicle_registration_current2" value="N"> N
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label>Copy of Current TDA Direct Supervisor Affidavit:</label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="tda_supervisor_affidavit_current"
                                                       id="tda_supervisor_affidavit_current1" value="Y"> Y
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="tda_supervisor_affidavit_current"
                                                       id="tda_supervisor_affidavit_current2" value="N"> N
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label>The Condition Of The Above Vehicle Is Satisfactory:</label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="vehicle_condition_satisfactory"
                                                       id="vehicle_condition_satisfactory1" value="Y"> Y
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vehicle_condition_satisfactory"
                                                       id="vehicle_condition_satisfactory2" value="N"> N
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

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="vehicle_inspoection_submit">Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Vehicle Issue Note Modal -->
    <div id="modal_vehicle_issue" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Vehicle Issue Note</h6>
                </div>
                <!-- Form Start -->
                <div class="modal-body">
                    <form class="form-horizontal" method="post" name="vehicle_issue_note" enctype="multipart/form-data"
                          id="vehicle_issue_note">
                        <!-- <fieldset class="content-group"> -->
                        <input type="hidden" name="note_user_id" class="form-control" value="<?= $currentUser->id ?>">
                        <input type="hidden" name="note_category" class="form-control" value="3">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Note Type</label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="note_type" placeholder="">
                                            <option value="" disabled selected></option>
                                            <option value="1">Task</option>
                                            <option value="2">Vehicle General</option>
                                            <option value="3">Vehicle Maintenance</option>
                                            <?php foreach ($note_types as $type) : ?>
                                                <option value="<?= $type->type_id; ?>"><?= $type->type_name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Truck #</label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="truck_number" id="truck_number" required>
                                            <?php if (is_null($assigned_vehicle)) : ?>
                                                <option value="" disabled selected></option>
                                            <?php endif; ?>
                                            <?php foreach ($vehicles as $value) : ?>
                                                <option
                                                    value="<?= $value->fleet_id; ?>"<?= ($assigned_vehicle == $value->fleet_id) ? ' selected' : ''; ?>><?= $value->fleet_id; ?>
                                                    : <?= $value->v_name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Assign User</label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="note_assigned_user">
                                            <!-- Add Users available within company with Value = user_id / option shown user_name -->
                                            <option value="">None</option>
                                            <?php
                                            foreach ($userdata as $user) {
                                                ?>
                                                <option
                                                    value="<?= $user->id; ?>"><?= $user->user_first_name . ' ' . $user->user_last_name; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Due Date</label>
                                    <div class="col-lg-9">
                                        <input id="note_due_date" type="date" name="note_due_date"
                                               class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <input type="hidden" name="include_in_tech_view" value="1">

                        <div class="row row-extra-space">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Attach Documents</label>
                                    <div class="col-lg-8 text-left">
                                        <input id="files" type="file" name="files[]" class="form-control-file" multiple>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-lg-3">
                                        Urgent Note
                                    </label>
                                    <div class="col-lg-9">
                                        <input type="checkbox"
                                               name="is_urgent"
                                               id="is_urgent"
                                               class="checkbox checkbox-inline text-right switchery_urgent_note">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-lg-3">
                                        Notify Me
                                    </label>
                                    <div class="col-lg-9">
                                        <input type="checkbox"
                                               name="notify_me"
                                               id="notify_me"
                                               checked
                                               class="checkbox checkbox-inline text-right switchery_notify_me">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-lg-3">
                                        Enable Notification
                                    </label>
                                    <div class="col-lg-9">
                                        <input type="checkbox"
                                               name="is_enable_notifications"
                                               id="is_enable_notifications"
                                               class="checkbox checkbox-inline text-right switchery_enable_notification">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-lg-3">
                                        Notification To
                                    </label>
                                    <div class="multi-select-full col-lg-9">
                                        <select class="multiselect-select-all-filtering form-control note-filter"
                                                name="notification_to[]" id="notification_to" multiple="multiple">
                                            <?php
                                            foreach ($userdata as $user) {
                                                ?>
                                                <option value="<?= $user->id; ?>">
                                                    <?= $user->user_first_name . ' ' . $user->user_last_name; ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Note Contents</label>
                                    <div class="col-lg-12">
                                        <textarea class="form-control" name="note_contents" id="note_contents"
                                                  rows="5"></textarea>
                                        <span style="color:red;"><?php echo form_error('note_contents'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- </fieldset> -->
                        <div class="text-right btn-space">
                            <button type="submit" id="savenote" class="btn btn-success">Save <i
                                    class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </form>
                </div>
                <!-- Form End -->
            </div>
        </div>
    </div>

</div>

<script>
    $('#vehicle_issue_note').on('submit', function (e) {
        e.preventDefault();
        let form = $('#vehicle_issue_note')[0];
        let data = new FormData(form);
        $.ajax({
            url: '<?= base_url('technician/addTechVehicleIssueAjax'); ?>',
            type: 'POST',
            enctype: 'multipart/form-data',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                console.log(data);
                let responseData = JSON.parse(data);
                console.log(responseData);
                let status = responseData.status;
                console.log(status);
                if (status == 'success') {
                    $('#modal_vehicle_issue').modal('hide');
                } else if (status == 'error') {
                    $('#modal_vehicle_issue').modal('hide');
                } else {
                    $('#modal_vehicle_issue').modal('hide');
                }
            },
            error: function (e) {
                console.log("ERROR : ", e);
                $('#modal_vehicle_issue').modal('hide');
            }
        });
    });
</script>


<script>
    function showMileageInfo()
    {
        post_BasicOptimizeStopsMileageInfo();
    }
    $('#vehicle_inspection_report').on('submit', function() {
        event.preventDefault();
        let formData = $($('#vehicle_inspection_report')[0]).serialize();
        $.ajax({
            type: "POST",
            url: "<?= base_url()?>" + "technician/submitVehicleInspection",
            data: formData,
            success: function (resp) {
                console.log('Success');
                console.log(resp);
                $('#modal_vehicle_inspection').modal('hide');
                sessionStorage.setItem("VehicleInspectionCompleted", true);
                let message = '<span style="color:#16d116;font-size:1.5em;">Inspection Report Submitted Successfully...</span>';
                let subMessage = $('.techmessage b').get(0);
                $(subMessage).show();
                $(subMessage).append(message);
                $('#start_inspection').parent().parent().hide();
                $('#start-day-btn').parent().parent().show();
                setTimeout(function() {
                $(subMessage).hide();
                $(subMessage).empty();
                }, 5000);
                // $('#start-day-btn').parent().parent().show();
                // document.getElementById('start-day-btn').click();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                console.log("Status: " + textStatus); 
                console.log("Error: " + errorThrown); 
            }   
        });
    });
    $('.vehicle_issue').on('click', function () {
        console.log('Vehicle Issue btn clicked');
    });
</script>

<script type="text/javascript">
    <?php
    if ($setting_details->is_tech_vehicle_inspection_required === '0') {
        echo 'sessionStorage.setItem("VehicleInspectionCompleted", true);';
    }
    ?>
    document.onreadystatechange = function () {
        if (document.readyState == "complete") {
            $('#loading').css('display', 'block');
            post_RealRoadOptimizeStops();
            console.log('is vehicle inspection completed?');
            console.log(sessionStorage.getItem("VehicleInspectionCompleted"));
            if (sessionStorage.getItem("VehicleInspectionCompleted") === 'true') {
                $('#start_inspection').parent().parent().hide();
                <?php if($is_first_job) { ?>
                $('#start-day-btn').parent().parent().show();
                <?php } ?>

            }


            var config = {
                color: '#36c9c9',
                secondaryColor: "#dfdfdf",
            };
            var urgent_note = document.querySelector('.switchery_urgent_note');
            var switchery_urgent_note = new Switchery(urgent_note, config);

            var notify_me = document.querySelector('.switchery_notify_me');
            var switchery_notify_me = new Switchery(notify_me, config);
            var enable_notification = document.querySelector('.switchery_enable_notification');
            var switchery_enable_notification = new Switchery(enable_notification, config);
        }
    }
    var map;
    function loadMap() {
    map = new Microsoft.Maps.Map(document.getElementById('routeMap'), {});
    }
    var showmap = true;
    $('#routeMap').show();
    var resturl = 'https://optimizer3.routesavvy.com/RSAPI.svc/';
    function post_BasicOptimizeStops() {
    var requestStr = $('#postTestRequest').val();
    var request = JSON.parse(requestStr);
    //clear
    $('#result').text('');
    $('#get_url').text('');
    $('#geturl').hide();
    map.entities.clear();
    postit(resturl + 'POSTOptimize', {
        data: JSON.stringify(request),
        success: function(data) {
        $('#jsonresult').show();
        console.log(data)
        var resp = JSON.stringify(data, null, '\t');
        console.log(resp);
            // let mileage = data.Route.DriveDistance.toFixed(2);
            // let driveTime =  new Date(data.Route.DriveTime * 1000).toISOString().slice(11, 19);
            // $('#mileageInfo').text(mileage + ' ' + data.Route.DriveDistanceUnit);
            // $('#driveTimeInfo').text(driveTime);
            // $('#modal_mileage').modal('show');
            // $('#modal_mileage').modal('show');
        //process results
        if (showmap) {
            renderMap(data);
        } else {
            //show json results
            $('#loading').css('display', 'none');
            $('#result').text(resp);
        }
        },
        error: function(err) {
        $('#loading').css('display', 'none');
        $('#result').text(JSON.stringify(err, null, '\t'));
        }
    });
    }

    function kmToMiles(km) {
        return km / 1.60934;
    }

    function formatTime(seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds % 3600) / 60);
        var remainingSeconds = seconds % 60;

        var formattedTime = hours.toString().padStart(2, '0') + ':' +
            minutes.toString().padStart(2, '0') + ':' +
            remainingSeconds.toString().padStart(2, '0');

        return formattedTime;
    }

    function post_BasicOptimizeStopsMileageInfo() {
        var requestStr = $('#postTestRequest').val();
        var request = JSON.parse(requestStr);

        postit(resturl + 'POSTOptimize', {
            data: JSON.stringify(request),
            success: function(data) {
                let mileage = kmToMiles(data.Route.DriveDistance).toFixed(2);
                let driveTime =  formatTime(data.Route.DriveTime);
                $('#mileageInfo').text(mileage + ' miles');
                $('#driveTimeInfo').text(driveTime);
                $('#modal_mileage').modal('show');
            },
            error: function(err) {
                $('#loading').css('display', 'none');
                $('#result').text(JSON.stringify(err, null, '\t'));
            }
        });
    }
    var routeJobCount = <?= $routeJobCount; ?>;
    var currentStepCount = 0;
    // $(function() {
    //   if( currentStepCount != routeJobCount ) {
    //       console.log('Switching Buttons...');
    //       $('#start_inspection').get(0).parentElement.parentElement.hide();
    //       $('#next-stop-btn').get(0).parentElement.parentElement.show();
    //       $('#start_vehicle_issue').get(0).parentElement.parentElement.hide();
    //       $('#finish-day-btn').get(0).parentElement.parentElement.show();
    //       console.log('Buttons Switched!');
    //   }
    // });


function post_RealRoadOptimizeStops() {
  var requestStr = $('#postTestRequest').val();
  requestStr = requestStr.replace("basic", "realroadcar");
  var request = JSON.parse(requestStr);
  //clear
  $('#result').text('');
  $('#get_url').text('');
  $('#geturl').hide();
  map.entities.clear();
  postit(resturl + 'POSTOptimize', {
    data: JSON.stringify(request),
    success: function(data) {
      var resp = JSON.stringify(data, null, '\t');
      renderMap(data);
      sample = jQuery.map(data.OptimizedStops, function(n, i) {
        return n.Name;
      });
      $.ajax({
        type: "POST",
        url: "<?= base_url()?>" + "technician/dashboardJsonData/" + "<?= $current_route ?>",
        data: {
          OptimizedStops: sample
        },
        dataType: "json",
        success: function(response) {
          $('#loading').css('display', 'none');
          console.log(response);
          if(Array.isArray(response)) {
            currentStepCount = response.length;
          } else {
            currentStepCount = 0;
          }
          for (var i = 0; i < response.length; i++) {
			      console.log(response[i]);
            if (response[i].is_time_check == 1) {
              specific_time_html = '<br><span class="text-muted"><i class="icon-alarm"></i>  ' + response[i]
                .specific_time + '</span>';
            } else {
              specific_time_html = '';
            }
            var phone = response[i].phone.replaceAll('-', '').match(/^(\d{3})(\d{3})(\d{4})$/);
              if (phone) {
                  phone = '(' + phone[1] + ') ' + phone[2] + '-' + phone[3];
              }

                            let asap_reason = '';
                            let asap = response[i].program_job_assigned_customer_property_id;

              if (asap && response[i].reason !== null) {
                  asap_reason = '<a><span class="text-muted" style="background: red; color: white"><i class="icon-alert" ></i>&nbspASAP -  &nbsp' + response[i].reason + '</span></a><br>';
              }
            $('#from_jquery_sorting').append(
              '<li><span class="feed-time text-muted" style="color: inherit !important; font-weight: 500 !important;">' +
              response[i].date + '<br>' + response[i].day +
              '</span><div class="panel panel-body"><div class="media no-margin-top content-group" style="margin-bottom: 0px !important"><div class="media-body"><a href=" <?php echo base_url() ?>technician/jobDetails/' +
              response[i].property_id + '" > <h6 class="no-margin text-semibold">' +
              response[i].property_title +
              '</h6><span class="text-muted"><i class=" icon-location4"></i>  ' + response[i]
              .property_address + '</span>' + specific_time_html + '</a><br><a><span class="text-muted"><i class="icon-phone"></i>&nbsp' + phone + '</span></a><br>'+asap_reason+'<span class="text-muted">Service(s): '+response[i].job_label_stop+'</span><span class="text-muted">' + response[i].tags + '</span>'+response[i].pre_service_notification+'</div></div></div></li>');
            if (i == response.length) {
              $("#from_jquery_sorting").append('<li></li>');
            }
            if (i == 0) {
              $('.start_day').attr('href', '<?php echo base_url()?>' + 'technician/jobDetails/' + response[
                i].property_id);
              $('.next_stop').attr('href', '<?php echo base_url()?>' + 'technician/jobDetails/' + response[
                i].property_id);
              // $('#modal_vehicle_inspection').modal();$('#modal_vehicle_inspection').modal();
              // Array.from($('.start_day')).forEach((el) => {
              //   $(el).on('click', startInspection);
              // });
            }
            // if(notFirstStop)
            // {
            //   console.log('Switching Buttons...');
            //   $('#start_inspection').get(0).parentElement.parentElement.hide();
            //   $('#next-stop-btn').get(0).parentElement.parentElement.show();
            //   $('#start_vehicle_issue').get(0).parentElement.parentElement.hide();
            //   $('#finish-day-btn').get(0).parentElement.parentElement.show();
            //   console.log('Buttons Switched!');
            // }
          }
          // getRoutePosition();
        }
      });
    },
    error: function(err) {
      $('#loading').css('display', 'none');

                $('#result').text(JSON.stringify(err, null, '\t'));
            }
        });
    }


    function get_BasicOptimizeStops() {
        var requestStr = $('#postTestRequest').val();
        //clear
        $('#result').text('');
        map.entities.clear();
        $('#geturl').show();
        var url = resturl + 'GETOptimize?query=' + requestStr;
        $('#get_url').text(url);
        $.getJSON(url, function (data) {
            if (showmap) {
                renderMap(data);
            } else {
                //show json results
                $('#result').text(JSON.stringify(data, null, '\t'));
            }
        });
    }

    function get_RealRoadOptimizeStops() {
        var requestStr = $('#postTestRequest').val();
        requestStr = requestStr.replace("basic", "realroadcar");
        //clear
        $('#result').text('');
        map.entities.clear();
        $('#geturl').show();
        var url = resturl + 'GETOptimize?query=' + requestStr;
        $('#get_url').text(url);
        $.getJSON(url, function (data) {
            if (showmap) {
                renderMap(data);
            } else {
                //show json results
                $('#result').text(JSON.stringify(data, null, '\t'));
            }
        });
    }

    // post json data and get a json response
    function postit(url, options) {
        // extend options
        var poptions = jQuery.extend({}, {
            url: url,
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status === 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status === 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                $('#postProduct').html(msg);
            },
        }, options);
        // send it along
        return $.ajax(poptions);
    }

    function renderMap(data) {
        var requestStr2 = $('#postTestRequest').val();
        var request2 = JSON.parse(requestStr2);
        if (request2.Locations == '') {
            $('#loading').css('display', 'none');
        } else if (data.Route == null) {
            $('#loading').css('display', 'none');
            alert('Please check your address');
        }
        var points = new Array();
        for (var i in data.Route.RoutePath) {
            points[i] = new Microsoft.Maps.Location(data.Route.RoutePath[i][0], data.Route.RoutePath[i][1]);
        }
        var path = new Microsoft.Maps.Polyline(points, {
            strokeColor: 'blue',
            strokeThickness: 2
        });
        map.entities.push(path);
        for (var i in data.OptimizedStops) {
            var location = new Microsoft.Maps.Location(data.OptimizedStops[i].RouteLocation.Latitude, data.OptimizedStops[i]
                .RouteLocation.Longitude);
            var c = 'orange';
            if (i == 0) c = 'green';
            else if (i == (data.OptimizedStops.length - 1)) c = 'red';
            var label = parseInt(i) + 1;
            map.entities.push(new Microsoft.Maps.Pushpin(location, {
                color: c,
                text: label.toString(),
                title: data.OptimizedStops[i].Name
            }));
        }
        var bounds = new Microsoft.Maps.LocationRect.fromLocations(points);
        map.setView({
            bounds: bounds
        });
    }

    $('.finishday').click(function (e) {
        $.ajax({
            url: '<?= base_url("technician/finishDay") ?>',
            type: 'get',
        }).done(function (response) {
            if (response == 1) {
                swal({
                    type: 'error',
                    title: 'Please complete all Services for finish day',
                    text: ''
                })
            } else if (response == 2) {
                swal('All Jobs are completed ', '', 'success')
            } else {
                swal({
                    type: 'error',
                    title: 'Oops... No Services available for finish day',
                    text: ''
                })
            }
        });
    });
</script>
<script type="text/javascript">
    function getDetailsByAddress(property_address) {
        console.log(JSON.stringify(property_address));
    }
</script>
<script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=<?= BingMAp ?>&callback=loadMap' async
        defer></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
  integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
  integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->

<script>
    <?php
    $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;
    $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();
    ?>
    var currentUser = <?= print_r(json_encode($currentUser), TRUE); ?>;

</script>


