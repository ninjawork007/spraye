<style>
    body, html {
        overflow-x: hidden!important;
    }
    @media (max-width: 1920px) and (max-height: 1080px) {
        .overflow-hidden {
            overflow-x: hidden;
        }
    }
    .wrapper.modal-body {
        background: white!important;
    }
    .wrapper {
        display: flex;
        width: 100%;
        align-items: flex-start;
        position: absolute;
        z-index: 2;
        justify-content: flex-end;
        height: 63vh;
        overflow-y: auto;
        width: 583px;
        left: 69%;
    }

    .wrapper.inactive {
        /*width: 50px!important;*/
        /*height: 25px!important;*/
        /*left: 98%!important;*/
        width: 155px!important;
        height: 40px!important;
        left: 93%!important;
    }


    @media (max-width: 1538px) and (max-height: 826px) {
        .wrapper {
            height: 63vh!important; /* Adjust the height as needed */
            width: 579px!important; /* Adjust the width as needed */
            left: 56%!important; /* Remove the left positioning */
            right: 10px!important; /* Position it from the right side */
        }
        .wrapper.inactive {
            width: 59px!important; /* Adjust the width as needed */
            height: 25px!important; /* Adjust the height as needed */
            left: 96%!important; /* Remove the left positioning */
            right: 10px!important; /* Position it from the right side */
        }
    }

    @media (max-width: 1920px) and (max-height: 1080px) {
        .wrapper {
            height: 63vh; /* Adjust the height as needed */
            width: 603px; /* Adjust the width as needed */
            left: 64%; /* Remove the left positioning */
            right: 10px; /* Position it from the right side */
        }
        .wrapper.inactive {
            width: 59px!important; /* Adjust the width as needed */
            height: 25px!important; /* Adjust the height as needed */
            left: 96%!important; /* Remove the left positioning */
            right: 10px!important; /* Position it from the right side */
        }
    }

    #sidebar{
        /*min-width:250px;*/
        /*max-width: 250px;*/
        background: white;
        color:#fff;
        transition: all 0.3s
    }
    #sidebar.active{
        margin-left:-250px
    }
    #sidebar .sidebar-header{
        padding:20px;
        background: #005086
    }
    #sidebar ul.components{
        padding:20px 0px;
        border-bottom:1px solid #47748b
    }
    #sidebar ul p{
        padding:10px;
        font-size:15px;
        display: block;
        color:#fff
    }
    #sidebar ul li a{
        padding:10px;
        font-size: 1.1em;
        display: block
    }
    #sidebar ul li a:hover{
        color:#fff;
        background: #318fb5
    }
    #sidebar ul li.active>a, a[aria-expanded="true"]{
        color:#fff;
        background: #318fb5
    }
    #content{
        width:100%;
        padding:20px;
        min-height: 100vh;
        transition: all 0.3s
    }
    .content-wrapper{
        /*padding:15px*/
    }
    @media(maz-width:768px){
        #sidebar{
            margin-left:-250px
        }
        #sidebar.active{
            margin-left:0px
        }
        #sidebarCollapse span{
            display:none
        }
    }

    .custom-row {
        background-color: #00689F;
        /*display: flex;*/
        /*align-items: center;*/
        /*justify-content: center;*/
        padding: 10px 5px 15px;
    }

    .custom-row .form-group {
        margin-right: 10px;
        flex: 1;
    }
    .custom-row label {
        color: white;
        display: block;
        float: left;
    }

    .custom-row .form-control {
        background-color: white;
    }
    modal-body .form-control {
        background-color: white;
    }

    .togglebutton {
        font-size: 13px;
    }

    .content {
        padding: 0px 10px 60px !important;
        margin-top: 0px!important;
    }

    .test {
        padding-left: 12px !important;
    }

    .test1 {
        padding-left: 43px !important;
    }

    .fc-scroller {
        height: 500px ! important;
    }

    .widgetmydiv header p a {
        color: #1f567c !important;
    }

    .widgetmydiv .widget-logo {
        display: none !important;
    }

    .widgetmydiv header p:last-child {
        font-size: 1.2em !important;
    }

    .alleditbtn {
        float: left;
        padding-left: 5px;
    }

    .tmpspan {
        float: left;
        margin: 8px 15px 8px 0;
    }

    table {
        border-collapse: inherit;
    }
    #unassigntbl_length {
            /* show: */
            /* float: left !important; */
            display: none;
    }

    #loading {
        width: 50%;
        height: 50%;
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
        /* position: absolute; */
        /* left: 50%; */
        padding-left: 10px;
        /* top: 50%; */
        width: 35px;
        display: inline-block;
        
        /* z-index: 100; */
    }

    @media only screen and (max-width: 600px) {
        .calederview {
            display: none;
        }

        .togglebutton {
            display: none;
        }

        .sheduletable {
            display: block;
        }
    }

    @media only screen and (min-width: 600px) {
        .calederview {
            display: block;
        }

        .sheduletable {
            display: none;
        }
    }

    @media only screen and (min-width: 1300px) {
        .table-responsive {
            height: 500px;
        }
    }

    @media only screen and (max-width: 1240px) {
        #multiple-delete-id {
            margin: 0;
        }

        .unassigned-services-element {
            /* float: unset !important; */
            margin-top: 20px;
        }

        .toolbar {
            width: 90% !important;
            /* toolbar (filters) */
            padding-bottom: 20px;
            /* margin-left: -100px; */
            display: block;
            float: left !important;
        }

        /* KT make filters stack */
        /* .toolbar td {
            display: block;
        } */

        #unassigntbl_length {
            /* show: */
            /* float: left; */
            display: block;
        }

        #unassigntbl_filter>label {
            /* search box */
            margin-bottom: 20px;
            margin-left: -10px;
        }
    }

    @media only screen and (max-width: 769px) {
        .toolbar {
            float: left !important;
        }

        .toolbar td {
            display: block;
        }

        #unassigntbl_length {
            /* show: */
            float: left !important;
        }

        #unassigntbl_filter>label {
            /* search box */
            float: left !important;
        }

        .form-group a {
            display: block;
            width: 200px;
            margin-bottom: 6px;
        }

        .form-group button {
            display: block;
            width: 200px;
        }

        #filter-criteria-id {
            float: left;
            display: none;
        }
    }

    .btndivdelete {
        float: left;
        /*padding: 0px 2px 0px 14px;*/
        /*display: none;  */
    }

    .toolbar {
        /* float: right; */
        /* width: 88%; */
        padding-bottom: 10px;
    }

    .toolbar td {
        padding-left: 4px;
    }

    #unassigntbl_filter input {
        width: 150px !important;
    }

    tfoot {
        display: table-header-group;
    }

    td.fc-day.fc-past {
        background-color: #EEEEEE;
    }

    .label-row {
        margin-bottom: 6px;
    }

    .noSpacingInput {
        display: none;
    }

    .dtatableInput::placeholder {
        font-size: 10px;
        font-weight: 400;
    }

    .rescheduled_row {
        background: #b8d1f3 !important;
    }

    tr.row_in_hold td, tr.row_in_hold td a {	
        color: #8080804f !important;	
    }

    #resetMap {
        display: none;
    }

    #update-map-div {
        min-height:65px;
    }

    /* #update-map-note { 
        vertical-align:middle;
    } */

    #notify_filter {
        vertical-align: top;
    }
    
    #service_due_filter {
        vertical-align: top;
    }

    /* #multiple-delete-id {
        float: right;
    } */

    #filter-criteria-id {
        margin-left: 20px;
    }

    #unassigntbl {
        margin-top:15px;
    }
    #unassigntbl_processing {
        margin-top: 40px;
        background-color: white;
        background: white;
        height: 100%;
        text-align: center;
        vertical-align: top;
        position: absolute;
        height: 100%;
        top:130px;
    }
    .asap_row {
        background: #FBE9E7 !important;
        border: 1px solid #FF5722;
    }
    .row {
        margin-left: -20px!important;
        margin-right: -20px!important;
    }
    .rowAssign {
        margin-left: -14px!important;
        margin-right: -14px!important;
    }

    /* Google Map legend CSS >> */
    .marker {
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 5px;
    }

    .blue {
        /* background-color: blue; */
        fill: blue;
    }

    .green {
        /* background-color: green; */
        fill: green;
    }

    .red {
        /* background-color: red; */
        fill: red;
    }

    .yellow {
        /* background-color: yellow; */
        fill: yellow;
    }
    /* << Google Map legend CSS */

    .legendbox {
        background-color: #000;
        color: #fff;
        opacity: 0.5;

        padding: 10px;
        border: 1px solid #fff;
        border-radius: 5px;
    }
</style>

<!-- Map legend >> -->
<div id="legend" class="legendbox">
    <div><svg class="marker green" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 959.35 959.35"><path d="M451 863h55v-52q61-7 95-37.5t34-81.5q0-51-29-83t-98-61q-58-24-84-43t-26-51q0-31 22.5-49t61.5-18q30 0 52 14t37 42l48-23q-17-35-45-55t-66-24v-51h-55v51q-51 7-80.5 37.5T343 454q0 49 30 78t90 54q67 28 92 50.5t25 55.5q0 32-26.5 51.5T487 763q-39 0-69.5-22T375 681l-51 17q21 46 51.5 72.5T451 809v54Zm29 113q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 31.5-156t86-127Q252 239 325 207.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 82-31.5 155T763 858.5q-54 54.5-127 86T480 976Z"/></svg> NewCustomer</div>
    <div><svg class="marker yellow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 959.35 959.35"><path d="M479 974q-74 0-139.5-28t-114-77q-48.5-49-77-114T120 615q0-74 28.5-139.5t77-114.5q48.5-49 114-77T479 256q74 0 139.5 28T733 361q49 49 77 114.5T838 615q0 75-28 140t-77 114q-49 49-114.5 77T479 974Zm121-196 42-42-130-130V416h-60v214l148 148ZM214 189l42 42L92 389l-42-42 164-158Zm530 0 164 158-42 42-164-158 42-42Z"/></svg> PastDue</div>
    <div><svg class="marker blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 37.5 37.5"><path d="m18.75,36.99C8.67,36.99.5,28.82.5,18.75S8.67.5,18.75.5s18.25,8.17,18.25,18.25-8.17,18.25-18.25,18.25Zm3.36-26.67l-1.47.51c-5.22,1.89-8.9,10.69-7.39,15.2l.51,1.47c.14.4.58.65,1.02.49l2.97-1.07c.4-.14.63-.62.49-1.02l-1.05-3.29-2.42-.94c-.36-1.03,2.38-6.65,3.44-7.06l2.36,1.28,3.11-1.17c.4-.14.63-.62.49-1.02l-1.04-2.89c-.15-.44-.58-.65-1.02-.49Z"/></svg> PreNotified / Call Ahead</div>
    <div><svg class="marker red" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 959.35 959.35"><path d="M479.982 776q14.018 0 23.518-9.482 9.5-9.483 9.5-23.5 0-14.018-9.482-23.518-9.483-9.5-23.5-9.5-14.018 0-23.518 9.482-9.5 9.483-9.5 23.5 0 14.018 9.482 23.518 9.483 9.5 23.5 9.5ZM453 623h60V370h-60v253Zm27.266 353q-82.734 0-155.5-31.5t-127.266-86q-54.5-54.5-86-127.341Q80 658.319 80 575.5q0-82.819 31.5-155.659Q143 347 197.5 293t127.341-85.5Q397.681 176 480.5 176q82.819 0 155.659 31.5Q709 239 763 293t85.5 127Q880 493 880 575.734q0 82.734-31.5 155.5T763 858.316q-54 54.316-127 86Q563 976 480.266 976Z"/></svg> ASAP</div>
</div>
<!-- << Map legend -->


<script>
    var AllMarkers = [];
    var MapInfoIcons = [];
    var MapMarkerIcons = [];
    var MapMarkerIconsAddressArray = [];
    var global_r = '';
    $priority_filter_input = '';
    var initial_load = true;
    var set_initial_center = true;
    var remove_initial_map_button = true;
    var inital_table_hide = true;
</script>


<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/responsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/clock/css/bootstrap-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/clock/css/github.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Option 1: Include in HTML -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
<div class="content">
    <div class="">
        <div class="mymessage"></div>
        <b><?php if ($this->session->flashdata()) : echo $this->session->flashdata('message');
            endif
            ?></b>
        <div id="loading">
            <img id="loading-image" src="<?= base_url() ?>assets/loader.gif" /> <!-- Loading Image -->
        </div>

<!--        <div class="panel-heading" style="padding-left: 0px;">-->
<!--            <h5 class="panel-title">-->
<!--                <div class="form-group">-->
<!--                    <a href=" base_url('admin') " id="save" class="btn btn-success"><i class=" icon-arrow-left7"> </i> Back to Dashboard</a>-->
<!--                    <a href=" base_url('admin/manageJobs') " id="save" class="btn btn-primary"></i>Manage Scheduled Services</a>-->
<!--                    <div class="pull-right">-->
<!--                                 <label class="togglebutton">-->
<!--                                 Map View&nbsp;<input id="change-view-type" type="checkbox" onclick="window.location=' base_url('admin/assignJobs') '" class="switchery-primary">-->
<!--                                 Table View-->
<!--                                 </label>-->
<!--                    </div>-->
<!--                    <button id="multiple-restore-id" disabled="disabled" class=" hidden btn btn-primary archived-services-element">Restore Services</button>-->
<!--                </div>-->
<!--            </h5>-->
<!--        </div>-->

<!--        <div class="panel panel-flat">-->
<!--            <div class="panel-heading">-->
<!--                <h5 class="panel-title">-->
<!--                    <div class="form-group">-->
<!---->
<!--                        <div class="row">-->
<!---->
<!--                            <div class="col-md-6">-->
<!---->
<!--                            </div>-->
<!---->
<!--                            <div class="col-md-6 toggle-btn">-->
<!--                                <div style="float: right;">-->
<!--                                    <label hidden>-->
<!--                                        Map view&nbsp;<input id= "changeview" name="changeview" type="checkbox" class="switchery-primary">-->
<!--                                        Table views-->
<!--                                    </label>-->
<!---->
<!--                                    -->
<!---->
<!---->
<!--                                </div>-->
<!--                                <div>-->
<!--                            -->
<!--                            </div>-->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!---->
<!---->
<!--                    </div>-->
<!---->
<!--                </h5>-->
<!--            </div>-->
<!--        </div>-->

        <!-- <div class="panel-body">
         <h5 class="panel-title">Users Details</h5>
         </div>-->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="panel panel-flat">


                        <!-- <div class="container">
                        <div class="row"> -->

                            

                            

                        <!-- </div>
                        </div> -->

                        <div class="panel-body" style="padding: 0px 0px;">

                            <!-- <div  class="table-responsive table-spraye dash-tbl" style="height: unset;">
                        <table  class="table" id="unassigntbl" >
                           <thead>
                              <tr>
                                 <th><input type="checkbox" id="select_all" /></th>
                                 <th>Priority</th>
                                 <th>Service Name</th>
                                 <th>Customer Name</th>
                                 <th>Property Name</th>
								 <th>Square Feet</th>
                                 <th>Last Service Date</th>
								 <th>Last Program Service Date</th>
                                 <th>Address</th>
                                 <th>Property Type</th>                                 
                                 <th>Service Area</th>
                                 <th>Program</th>
                                 <th>Note</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                 <td></td>
                                 <td id="priority_filter">PRIORITY</td>
                                 <td id="service_name_filter">SERVICE NAME</td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
								  <td></td>
								  <td></td>
                                 <td id="property_type_filter">PROPERTY TYPE</td>
                                 <td id="service_area_filter">SERVICE AREA</td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                              </tr>
                           </tfoot> 

                        </table>
                     </div>  -->

<!--                            <div class="row">-->
<!--                                <div class="col-md-4">-->
<!--                                    <div  id="update-map-div-two" style="padding-left: 4px; margin-bottom: 10px;">-->
                                        <!-- <span class="update-map-note text-semibold" style="color:red;background-color:yellow;">Update Map View</span> -->


<!--                                    </div>-->

<!--                                <div>-->

<!--                                </div>-->
<!--                                    -->
<!--                                </div>-->
<!--                    -->
<!--                                -->
<!--                            </div>-->

                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12" id="mapdiv">
                                    <div class="wrapper">
<!--                                        <div class="sidebar-header" style="margin-right: 20px">-->
<!--                                            <button id="collapse-toggler" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">-->
<!--                                                <span class="on">Collapse&nbsp;<i class="fa-solid fa-arrow-right"></i></span>-->
<!--                                                <span id="collapse-toggler" class="hide">&nbsp;Expand<i class="fa-solid fa-arrow-left"></i></span>-->
<!--                                                <span class="navbar-toggler-icon"><i class="icon-paragraph-justify3"></i></span>-->
<!--                                            </button>-->
<!--                                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">-->
<!--                                                <span>Filter Criteria<i class="fa-solid fa-arrow-right"></i></span>-->
<!--                                            </button>-->
<!--                                        </div>-->
                                        <nav id="sidebar" class="collapse in" aria-expanded="true" style="">
                                            <div class="modal-body" style="background: white!important;">
                                                <div class="form-group ">

                                                    <div class="container-mt-2">
                                                        <!-- <div class="row"> -->
                                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                                            <h6 class="modal-title" style="color: #12689b">Filter Criteria</h6>
                                                        </div>
                                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                                            <div class="priority-filter">
                                                                <label>Priority</label>
                                                                <input type="text" id = "pfilter" name = "pfilter" class="form-control dtatableInput" placeholder="PRIORITY">
                                                            </div>

                                                            <div class="property-type-filter">
                                                                <label>Property Type</label>
                                                                <input type="text" id = "propertyTypefilter" name = "propertyTypefilter" class="form-control dtatableInput" placeholder="PROPERTY TYPE">
                                                            </div>

                                                            <div class="service-name-filter">
                                                                <label>Service Name</label>
                                                                <input type="text" id = "snfilter" name = "snfilter" class="form-control dtatableInput" placeholder="SERVICE NAME">
                                                            </div>

                                                            <!-- <div class="service-area-filter">
                                                                 <label>Service Area</label>
                                                                 <input type="text" id = "safilter" name = "safilter" class="form-control dtatableInput" placeholder="SERVICE AREA">
                                                             </div>-->
                                                            <div class="multi-select-full" id="service-area-filter_parent" >
                                                                <label for="service-area-filter">Service Area
                                                                </label>
                                                                <select class="multiselect-select-all-filtering form-control" name="service-area-filter[]" id="service-area-filter" multiple="multiple">

                                                                    <?php foreach ($service_area_list as $value): ?>
                                                                        <option value="<?= $value->category_area_name ?>"> <?= $value->category_area_name ?> </option>
                                                                    <?php endforeach ?>

                                                                </select>

                                                            </div>
                                                            <div class="service-name-filter">
                                                                <label>Show Only ASAP?</label>
                                                                <select class="form-control" name="asap_reason" id="asap-reason">
                                                                    <option value="0">No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                            </div>
                                                        </div>




                                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                                            <div class="notify-customer-filter">
                                                                <label>Notify</label>
                                                                <select class="form-control dtatableInput" id = "ncfilter" name = "ncfilter" placeholder="NOTIFY CUSTOMER" ><option value="0" class="default-option">-- NOTIFY</option><option value="1">CALL AHEAD</option><option value="2">TEXT ETA</option><option value="3">PRE-NOTIFIED</option></select>
                                                            </div>

                                                            <div class="tags-filter">
                                                                <label>Tags</label>
                                                                <?php echo $filter_tags; ?>
                                                            </div>
                                                            <div class="multi-select-full" id="service_ids_filter_parent" >
                                                                <label for="service_ids_filter">Due
                                                                    <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="Choose status below you would like to filter."><i class=" icon-info22 tooltip-icon"></i></span>
                                                                </label>
                                                                <div class="service-due-filter">
                                                                    <select name="services_statuses_filter[]" id="sdfilter" multiple style="width: 100%;" class="multiselect-select-all-filtering form-control><option value="0" class="default-option" style="width: 100%;">-- DUE</option><option value="1">Due</option><option value="2">Overdue</option><option value="3">Not Due</option></select>
                                                                    <!--                                            <select class="form-control dtatableInput" id = "sdfilter" name = "sdfilter" placeholder="SERVICE DUE" ><option value="0" class="default-option">-- DUE</option><option value="1">Due</option><option    value="2">Overdue</option><option value="3">Not Due</option></select>-->
                                                                </div>
                                                            </div>

                                                            <div class="multi-select-full" id="service_ids_filter_parent" >
                                                                <label for="service_ids_filter">Filter Services
                                                                    <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="Choose the service(s) below you would like to schedule."><i class=" icon-info22 tooltip-icon"></i></span>
                                                                </label>
                                                                <select class="multiselect-select-all-filtering form-control" name="services_multi_filter[]" id="services_multi_filter" multiple="multiple">

                                                                    <?php foreach ($service_list as $value): ?>
                                                                        <option value="<?= $value['job_name'] ?>"> <?= $value['job_name'] ?> </option>
                                                                    <?php endforeach ?>

                                                                </select>

                                                            </div>
                                                            <div class="multi-select-full " id="program_service_ids_filter_parent" >
                                                                <label for="programs_service_ids_filter">Filter Properties by Outstanding Services (require all)
                                                                    <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="By choosing any of the services below, Spraye will show only properties that have all of the chosen services outstanding in their account."><i class=" icon-info22 tooltip-icon"></i></span>
                                                                </label>
                                                                <select class="multiselect-select-all-filtering form-control" name="program_services_multi_filter[]" id="program_services_multi_filter" multiple="multiple">
                                                                    <?php foreach ($service_list as $value): ?>

                                                                        <option value="<?= $value['job_name'] ?>"> <?= $value['job_name'] ?> </option>

                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                            <div class="multi-select-full col-md-12 col-lg-12 col-sm-12" id="service_ids_filter_parent" style="padding-left: 4px; margin-top: 10px; margin-bottom: 10px;">
                                                                <label for="programs_service_ids_filter">Available Days
                                                                    <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="By choosing the days below, Spraye will show only properties that are available on all of the chosen days."><i class=" icon-info22 tooltip-icon"></i></span>
                                                                </label>
                                                                <select class="multiselect-select-all-filtering form-control" name="available_days_filter[]" id="available_days_filter" multiple="multiple">
                                                                    <?php foreach ($available_days_list as $key => $value): ?>
                                                                        <option value="<?= $value ?>" > <?= $key ?> </option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>


                                                        </div>
                                                        <!-- </div> -->
                                                    </div>
                                                </div>


                                                <div class="row modal-footer" style="background-color:white">
                                                    <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
                                                    <div  id="update-map-div" class= "col-md-12 col-lg-12 col-sm-12" style="padding-top:10px">

                                                    </div>
                                                </div>
                                            </div>
                                        </nav>

                                    </div>
                                    <div id="dvMap" style="height:63vh;">map div area</div>
                                    <div style="display:none;align-items:center;justify-content:center;padding-top:10px;">
                                        <span class="chtn" ><a href="" data-toggle="modal" data-target="#help_message" >This map feature is currently in beta. Please send us your feedback here</a></span>
                                    </div>

                                </div>


                            </div>
                            <div class="row custom-row d-flex">
                                <div class="col-md-2 col-lg-2 col-sm-2">
                                    <div class="form-group d-flex">
                                        <div class="col-md-10 col-lg-10 col-sm-10">
                                            <label for="property">Property Sq.Ft.</label>
                                            <input type="text" class="form-control" id="applicationSqFt">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group d-flex">
                                        <div class="col-md-10 col-lg-10 col-sm-10">
                                            <label for="application">Application Sq.Ft</label>
                                            <input type="text" class="form-control" id="totalSqFt">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group d-flex">
                                        <div class="col-md-10 col-lg-10 col-sm-10">
                                            <label for="revenue">Revenue&nbsp;<span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="Services with no invoice won't reflect price override or coupons until invoice is created."><i class=" icon-info22 tooltip-icon"></i></span></label>
                                            <input type="text" class="form-control" id="totalApplicationCost">
                                        </div>
                                    </div>
                                </div>
                                <div class="unassigned-services-element" style="float: right;margin-right: 18px;">
                                    <button type="button" class="btn btn-success" id="resetMap">
                                        Update Map</button>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-2" style="padding-top:25px ">
                                    <div class="form-group d-flex">
                                        <button type="submit" disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" id="allMessage"  class="btn btn-success ml-2" style="float:left">Assign Technician</button>
                                    </div>
                                </div>
                            </div>
<!--                            <div class="row">-->
<!--                                <div style="background: #fafafa;padding-top: 10px;padding-bottom: 20px;color: #333;padding-left: 12px;">-->
<!--                                    <span class="unassigned-services-element text-semibold" style="font-size:20px;margin-right:18px;display:flex;align-items: center;justify-content: center;">Unassigned Services</span>-->
<!--                                    <span class="archived-services-element text-semibold hidden" style="font-size:15px;">Archived Services</span>-->
<!--                                    <span class="unassigned-services-element" style="margin-right:18px;display:flex;align-items: center;justify-content: center;">Highlighted jobs indicate this job has been skipped and needs to be rescheduled</span>-->
<!--                                    <div class="row "  style="margin-top: 10px;align-items:center;">-->
<!---->
<!--                                        <div class="unassigned-services-element " style="margin-right:18px;display:flex;align-items:center;justify-content: center;">-->
<!--                                             <div class="unassigned-services-element " style="display:flex;"> -->
<!--                                            <div data-toggle="tooltip" data-placement="top" title="Property Sq Feet combines service applications sqft that are applied on a single property, for example if 2 service applications are applied to one property the sum of the sqft will only be equivalent to one of those applications.  Application Sq Feet is the sum of all service applications sqft, no matter if being applied to multiple properties or on a single property.">-->
<!---->
<!--                                                <div class="row">-->
<!--                                                    <div class="col-md-3" style="text-align:right;">-->
<!--                                                        <label for="appllicationSqFt" >Property Sq Feet</label>-->
<!--                                                    </div>-->
<!--                                                    <div class="col-md-3"style="text-align:right;">-->
<!--                                                        <input placeholder="" id="applicationSqFt" type="text" size="15">-->
<!--                                                    </div>-->
<!--                                                    <div class="col-md-3"style="text-align:right;">-->
<!--                                                        <label for="totalSqFt" >Application Sq Feet</label>-->
<!--                                                    </div>-->
<!--                                                    <div class="col-md-3"style="text-align:right;">-->
<!--                                                        <input placeholder="" id="totalSqFt" type="text"  size="15">-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                                <div class="row">-->
<!--                                                    <div class="col-md-3" style="text-align:right;">-->
<!--                                                        <label for="appllicationCost">Application Cost</label>-->
<!--                                                    </div>-->
<!--                                                    <div class="col-md-3" style="text-align:right;">-->
<!--                                                        <input placeholder="" id="totalApplicationCost" type="text"  size="15">-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!---->
<!--                                            </div>-->
<!--                                            <button type="submit" disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage" style="margin-left:15px;">-->
<!--                                                Assign Technician</button>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="unassigned-services-element" style="float: right;margin-right: 18px;">-->
<!--                                        <button type="button" class="btn btn-success" id="resetMap">-->
<!--                                            Update Map</button>-->
<!--                                    </div>-->
<!---->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="row" style="padding-top: 15px;">
                                <div class="col-md-12 col-lg-12 col-sm-12" id="tablediv">
                                    <p id="filter_message" style="width: 100%; background-color: #F44336; padding:5px;  text-align: center; display: none; color:white">Limited locations loaded.  Please enter Filter Criteria</p>

                                    <div class="table-responsive table-spraye dash-tbl" style="height: unset;">
                                        <table class="table" id="unassigntbl">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="select_all" /></th>
                                                    <th>Priority</th>
                                                    <th>Service Name</th>
                                                    <th>Notify Customer</th>
                                                    <th>Customer Name</th>
                                                    <th>Property Name</th>
                                                    <th>Square Feet</th>
                                                    <th>Last Service Date</th>
                                                    <th>Last Program Service Date</th>
                                                    <th>Last Program Service Type Date</th>
                                                    <th>Service Due</th>
                                                    <th>Address</th>
                                                    <th>Property Type</th>
                                                    <th>Property Info</th>
                                                    <th>Service Area</th>
                                                    <th>Program</th>
                                                    <th>Rescheduled Reason</th>
                                                    <th>Tags</th>
                                                    <th>ASAP Reason</th>
                                                    <th>Available Days</th>
                                                    <th>Hold Until Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <!-- <td></td>
                                                <td id="priority_filter">PRIORITY</td>
                                                <td id="service_name_filter">SERVICE NAME</td>
                                                <td id="notify_filter">NOTIFY CUSTOMER</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td id="service_due_filter">SERVICE DUE</td>
                                                <td></td>
                                                <td id="property_type_filter">PROPERTY TYPE</td>
                                                <td></td>
                                                <td id="service_area_filter">SERVICE AREA</td>
                                                <td></td>
                                                <td></td>
                                                <td id="tag_filter">TAG</td>
                                                <td></td>
                                                <td></td> -->
                                            </tr>
                                            <!-- <tr>

                                </tr> -->
                                            </tfoot>

                                        </table>

                                    </div>

                                    <input type="hidden" name="checkbox_realvalues_array" id="checkbox_realvalues_array" value"">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Primary modal -->
<div id="modal_theme_primary" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Assign Service to Technician</h6>
            </div>
            <form action="<?= base_url('admin/tecnicianJobAssign') ?>" name="tecnicianjobassign" method="post" id="formTechnicianJobAssign">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <label>Select Technician</label>
                                <div class="multi-select-full">
                                    <select class="form-control" name="technician_id" id="technician_id">
                                        <option value="">Select Any Technician</option>

                                        <?php
                                        if (!empty($tecnician_details)) {
                                            foreach ($tecnician_details as $value) {
                                                echo '<option value="' . $value->user_id . '" >' . $value->user_first_name . ' ' . $value->user_last_name . '</option>';
                                            }
                                        }


                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6" id="assigModalDate">
                                <label>Select Date</label>
                                <input type="date" name="job_assign_date" class="form-control  pickadate" id="jobAssignDate" placeholder="YYYYY-MM-DD">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row rowAssign">
                            <div class="col-sm-7 col-md-7">
                                <div class="row rowAssign">
                                    <div class="col-sm-12 col-md-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="changerouteview" class="primary-assign styled" checked="checked" value="1">
                                            Existing route
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="changerouteview" class="primary-assign styled" value="2" id="create-new-route" >
                                            Create a new route
                                        </label>
                                    </div>
                                </div>
                                <div class="row rowAssign">
                                    <div class="col-sm-12 col-md-12">
                                        <select name="route_select" class="form-control" id="route_select">
                                        </select>
                                        <input type="text" name="route_input" class="form-control" placeholder="Route Name" id="route_input" style="display: none;">
                                        <div class="route_error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 col-md-5">
                                <label>Service Notes</label>
                                <input type="text" name="job_assign_notes" placeholder="Service Assign Notes" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="specificTimeDivision form-group">
                    </div>
                    <input type="hidden" name="group_id" id="group_id">
                    <input type="hidden" name="group_id_new" id="group_id_new">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" id="mileage" style="display: none;">Get mileage & drive time</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="job_assign_bt">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary modal -->

<!-- Primary modal -->
<div id="modal_mileage" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-light">Mileage Information</h6>
                <button type="button" class="close text-light modal-mileage-dismiss">&times;</button>
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
                <button type="button" class="btn btn-secondary modal-mileage-dismiss">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /primary modal -->


<!-- Primary modal -->
<div id="modal_skip_reason" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-light">Skip Reasons</h6>
                <button type="button" class="close text-light modal-skip-dismiss close-modal-skip-reason">&times;</button>
            </div>
            <div class="modal-body ">
                <div class="form-group ">
                    <select class="form-control" name="skip_id" id="skip_id" >
                        <option value="" >Select Skip Reason</option>

                        <?php
                        if (!empty($skip_reasons)) {
                            foreach ($skip_reasons as $skip_reason) {
                                echo '<option value="'.$skip_reason->skip_id.'" >'.$skip_reason->skip_name.'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button id="button-skipp-reason" onclick="handleModalSkip()" type="button" class="btn btn-primary modal-skip-dismiss">Skip</button>
                <button type="button" class="btn btn-secondary modal-skip-dismiss close-modal-skip-reason" >Close</button>
            </div>
        </div>
    </div>
</div>

<!---  Add Filter Criteria template --->
<div id="modal_add_filters" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Filter Criteria</h6>
                </div>

              

                <div class="modal-body ">
                    <div class="form-group ">

                    <div class="container-mt-2">
                            <!-- <div class="row"> -->
                                <div class="col-md-6 ">
                                        <div class="priority-filter">                   
                                            <label>Priority</label>
                                            <input type="text" id = "pfilter" name = "pfilter" class="form-control dtatableInput" placeholder="PRIORITY">
                                        </div>

                                        <div class="property-type-filter">                   
                                            <label>Property Type</label>
                                            <input type="text" id = "propertyTypefilter" name = "propertyTypefilter" class="form-control dtatableInput" placeholder="PROPERTY TYPE">
                                        </div>

                                        <div class="service-name-filter">                   
                                            <label>Service Name</label>
                                            <input type="text" id = "snfilter" name = "snfilter" class="form-control dtatableInput" placeholder="SERVICE NAME">
                                        </div>

                                       <!-- <div class="service-area-filter">
                                            <label>Service Area</label>
                                            <input type="text" id = "safilter" name = "safilter" class="form-control dtatableInput" placeholder="SERVICE AREA">
                                        </div>-->
                                    <div class="multi-select-full" id="service-area-filter_parent" >
                                        <label for="service-area-filter">Service Area
                                        </label>
                                        <select class="multiselect-select-all-filtering form-control" name="service-area-filter[]" id="service-area-filter" multiple="multiple">

                                            <?php foreach ($service_area_list as $value): ?>
                                                <option value="<?= $value->category_area_name ?>"> <?= $value->category_area_name ?> </option>
                                            <?php endforeach ?>

                                        </select>

                                    </div>
                                    <div class="service-name-filter">
                                        <label>Show Only ASAP?</label>
                                        <select class="form-control" name="asap_reason" id="asap-reason">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                        
                    

                    
                                    <div class="col-md-6">
                                        <div class="notify-customer-filter">    
                                            <label>Notify</label>
                                            <select class="form-control dtatableInput" id = "ncfilter" name = "ncfilter" placeholder="NOTIFY CUSTOMER" ><option value="0" class="default-option">-- NOTIFY</option><option value="1">CALL AHEAD</option><option value="2">TEXT ETA</option><option value="3">PRE-NOTIFIED</option></select>
                                        </div>

                                        <div class="tags-filter">    
                                            <label>Tags</label>
                                                <?php echo $filter_tags; ?>
                                        </div>
                                        <div class="multi-select-full" id="service_ids_filter_parent" >
                                            <label for="service_ids_filter">Due
                                                <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="Choose status below you would like to filter."><i class=" icon-info22 tooltip-icon"></i></span>
                                            </label>
                                            <div class="service-due-filter">
                                                <select name="services_statuses_filter[]" id="sdfilter" multiple style="width: 100%;" class="multiselect-select-all-filtering form-control><option value="0" class="default-option" style="width: 100%;">-- DUE</option><option value="1">Due</option><option value="2">Overdue</option><option value="3">Not Due</option></select>
    <!--                                            <select class="form-control dtatableInput" id = "sdfilter" name = "sdfilter" placeholder="SERVICE DUE" ><option value="0" class="default-option">-- DUE</option><option value="1">Due</option><option    value="2">Overdue</option><option value="3">Not Due</option></select>-->
                                            </div>
                                        </div>

                                        <div class="multi-select-full" id="service_ids_filter_parent" >
                                            <label for="service_ids_filter">Filter Services
                                                <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="Choose the service(s) below you would like to schedule."><i class=" icon-info22 tooltip-icon"></i></span>
                                            </label>
                                            <select class="multiselect-select-all-filtering form-control" name="services_multi_filter[]" id="services_multi_filter" multiple="multiple">

                                                <?php foreach ($service_list as $value): ?>
                                                <option value="<?= $value['job_name'] ?>"> <?= $value['job_name'] ?> </option>
                                                <?php endforeach ?>

                                            </select>

                                        </div>
                                        <div class="multi-select-full " id="program_service_ids_filter_parent" >
                                            <label for="programs_service_ids_filter">Filter Properties by Outstanding Services (require all)
                                                <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="By choosing any of the services below, Spraye will show only properties that have all of the chosen services outstanding in their account."><i class=" icon-info22 tooltip-icon"></i></span>
                                            </label>
                                            <select class="multiselect-select-all-filtering form-control" name="program_services_multi_filter[]" id="program_services_multi_filter" multiple="multiple">
                                                <?php foreach ($service_list as $value): ?>

                                                    <option value="<?= $value['job_name'] ?>"> <?= $value['job_name'] ?> </option>

                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="multi-select-full col-md-12" id="service_ids_filter_parent" style="padding-left: 4px; margin-top: 10px; margin-bottom: 10px;">
                                            <label for="programs_service_ids_filter">Available Days
                                                <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="By choosing the days below, Spraye will show only properties that are available on all of the chosen days."><i class=" icon-info22 tooltip-icon"></i></span>
                                            </label>
                                            <select class="multiselect-select-all-filtering form-control" name="available_days_filter[]" id="available_days_filter" multiple="multiple">
                                                <?php foreach ($available_days_list as $key => $value): ?>
                                                    <option value="<?= $value ?>" > <?= $key ?> </option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>

                                        
                                </div>
                        <!-- </div> -->
                      </div>
				  </div>
				  
                        
                     <div class="row modal-footer">
                      <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
                            <div  id="update-map-div" class= "col-md-12" style="padding-top:10px">
                                
                            </div>
                     </div>
                </div>
               
        </div>
    </div>
</div> 
<!-------------------------------------------->

<!-- print modal -->
<div id="modal_theme_primary_print" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Do you wish to print this route’s worksheets?</h6>
            </div>
            <div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary" id="print_job_assign_bt">Print</button>
            </div>
        </div>
    </div>

</div>

<!--begin edit assign job  -->

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBH7pmiDU016Cg76ffpkYQWcFQ4NaAC2VI&libraries=drawing,geometry""></script>

<script>
    	//console.log('loading job names');	
        let serviceList = <?= json_encode($service_list) ?>


    // $('input[name=changeview]').change(function() {
    //     //$('#changeview').on('change', function() {
    //         //console.log("entered changeview listener");

    //     var mode = $(this).prop('checked');

    //     //var checkedValue = document.querySelector('input[name=changeview]:checked').value;
    //       //alert(mode);
    //       //alert($('input[name=changeview]').val());
    //       //alert(checkedValue);
    //     //if (mode == false) {
    //         switch(mode) {
    //             case false:
    //                 //$('#changeview').attr( "checked", false );
    //                 //console.log("False etay");
    //                 $('#tablediv').css('display', 'block');
    //                 $('#mapdiv').css('display', 'block');

    //                 //  $("#mapdiv").removeClass('col-md-4');
    //                 //  $("#mapdiv").addClass('col-md-12');

    //                 //  $("#tablediv").removeClass('col-md-8');
    //                 $("#tablediv").addClass('col-md-8');

    //                 break;

    //             case true:
    //                 //console.log("Beya");
    //                 //$('#changeview').attr( "checked", true );

    //                 $('#mapdiv').css('display', 'none');
    //                 $('#tablediv').css('display', 'block');
    //                 $("#tablediv").removeClass('col-md-8');
    //                 $("#tablediv").addClass('col-md-12');

    //                 //show the red update map button on left side
    //                 if (!$("#update-map-note").length > 0) {
    //                     //console.log("HEYYYYYYYYYYYYYYYYYYYYY");
    //                     $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-primary ml-5 btn btn-danger unassigned-services-element'text-semibold' style='font-size:18px;color:#FFFFFF;background-color:#F44336;'>Please click here to update the map</button");
    //                 }

    //                 break;

    //     }

    // });

    // if ($('input[name=changeview]:checked')) {

    //     var mode = $(this).prop('checked');
    //     //  alert(mode);
    //     if (mode == false) {
    //            //console.log("False etay2")
    //         $('#tablediv').css('display', 'block');
    //         $('#mapdiv').css('display', 'block');

    //         //  $("#mapdiv").removeClass('col-md-4');
    //         //  $("#mapdiv").addClass('col-md-12');

    //         //  $("#tablediv").removeClass('col-md-8');
    //         $("#tablediv").addClass('col-md-8');


    //     } else {
    //            //console.log("Beya2")

    //         $('#mapdiv').css('display', 'none');
    //         $('#tablediv').css('display', 'block');
    //         $("#tablediv").removeClass('col-md-8');
    //         $("#tablediv").addClass('col-md-12');


    //     }
    // }
</script>

<!-- KT -->
<script language="javascript" type="text/javascript">
    $(".modal-mileage-dismiss").click(function() {
        $("#modal_mileage").modal('hide');
    })
   $("#select_all").change(function(){  //"select all" change

      var status = this.checked; // "select all" checked status

      if (status) {
        $('#allMessage').prop('disabled', false);
          $('#multiple-delete-id,#multiple-restore-id').prop('disabled', false);
      }
      else
      {
         $('#allMessage').prop('disabled', true);
         $('#multiple-delete-id,#multiple-restore-id').prop('disabled', true);

      }

	   var sqftTotal = 0;

       /*$('.myCheckBox.map').each(function(){ //iterate all listed checkbox items
            //this.checked = status; //change ".checkbox" checked status

           var has_customer_in_hold=$(this).hasClass("customer_in_hold");
           console.log(has_customer_in_hold);
           if(!has_customer_in_hold){
               console.log(this)
               $(this).checked = status; //change ".checkbox" checked status
           }
           if ($(this).is(':checked')) {
               // //console.log( $(this).parent().parent().find('td').eq(5).html() );
               sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
            }
      });*/

	   $('#totalSqFt').val(sqftTotal);

      let costTotal = 0;
      let applicationSqft = 0;
      let tmpAddressArray = [];
      $('#unassigntbl tbody input:checked').each(function() {
         let currentAddress = $(this).parent().parent().find('td').eq(10).text();
         if(!tmpAddressArray.includes(currentAddress)) {
            tmpAddressArray.push(currentAddress);
            applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
         }

          let cost = $(this).data('cost');
          if (cost)
              costTotal = costTotal + parseFloat(cost);
         //console.log(applicationSqft);
         //console.log(tmpAddressArray);
      });
      $('#applicationSqFt').val(applicationSqft);

       $('#totalApplicationCost').val(costTotal.toFixed(2));

   });

</script>
<!-- KT -->

<script type="text/javascript">
    $(document).on("change", "table .myCheckBox", function() {
        if ($(".table .myCheckBox").filter(':checked').length < 1) {
            $('#allMessage').prop('disabled', true);
            $('#multiple-delete-id,#multiple-restore-id,#multiple-skip-id').prop('disabled', true);
        } else {
            $('#allMessage').prop('disabled', false);
            $('#multiple-delete-id,#multiple-restore-id,#multiple-skip-id').prop('disabled', false);
        }
        var costTotal = 0;
        $('#unassigntbl tbody input:checked').each(function() { //iterate all listed checkbox items
            if ($(this).is(':checked')) {
                let cost = $(this).data('cost');
                if (cost)
                    costTotal = costTotal + parseFloat(cost);
            }
        });
        $('#totalApplicationCost').val(costTotal.toFixed(2));
    });
</script>

<script type="text/javascript">
    function handleOneSkip(event)
    {
        $($(event).parent().parent().parent().parent().find("td:first")[0]).find('input').attr('checked', 'checked');
    }

    function handleModalSkip(e)
    {

        var group_id = [];
        var button_id = this.id;
        var url = "";

        //   $("input:checkbox[name=group_id]:checked").each(function(){
        //      group_id.push($(this).val());
        //   });

        var all_checked_boxes = $('input:checkbox[name=group_id]:checked');
        for (let i = 0; i < all_checked_boxes.length; i++) {
            var a_checked_box_val = all_checked_boxes[i].getAttribute('data-realvalue');
            group_id.push(a_checked_box_val);
        }

        var post_data =  {};
        var success_message = "";
        post_data.group_id = group_id;

        post_data.action = 'skip';
        post_data.skip_id = $("#skip_id").val();
        success_message = "Skipped Successfully";

        console.log(post_data);
        swal({
            title: 'Are you sure?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            $("#modal_skip_reason").modal('hide');
            if (result.value) {
                $("#loading").css("display","block");
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('admin/skipMultiUnassignedJobs') ?>",
                    data: post_data,
                    dataType: 'json'
                }).done(function(data){
                    $("#loading").css("display","none");
                    if (data.status==200) {
                        swal(
                            'Unassigned Service(s) !',
                            success_message,
                            'success'
                        ).then(function() {
                            location.reload();
                        });
                    } else {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        })
                    }
                });
            }
        })
    }
    $(document).on("click", "#multiple-delete-id,#multiple-restore-id", function(e) {
        var group_id = [];
        var button_id = this.id;
        var url = "";

        // $("input:checkbox[name=group_id]:checked").each(function(){
        //    group_id.push($(this).val());
        // });

        var all_checked_boxes = $('input:checkbox[name=group_id]:checked');
        for (let i = 0; i < all_checked_boxes.length; i++) {
            var a_checked_box_val = all_checked_boxes[i].getAttribute('data-realvalue');
            group_id.push(a_checked_box_val);
        }

        var post_data = {};
        var success_message = "";
        post_data.group_id = group_id;
        if (button_id == "multiple-delete-id") {
            post_data.action = 'delete';
            success_message = "Deleted Successfully";
        } else {
            post_data.action = 'restore';
            success_message = "Restored Successfully";
        }
        ////console.log(post_data);
        swal({
            title: 'Are you sure?',
            text: "",
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
                    type: "POST",
                    url: "<?= base_url('admin/deleteRestoreMultiUnassignedJobs') ?>",
                    data: post_data,
                    dataType: 'json'
                }).done(function(data) {
                    $("#loading").css("display", "none");
                    if (data.status == 200) {
                        swal(
                            'Unassigned Service(s) !',
                            success_message,
                            'success'
                        ).then(function() {
                            location.reload();
                        });
                    } else {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        })
                    }
                });
            }
        })
    });
    $(document).on("click", ".confirm_delete_unassign_job,.confirm_restore_unassign_job", function(e) {
        e.preventDefault();
        var action = "";
        var success_message = "";
        if ($(this).hasClass('confirm_delete_unassign_job')) {
            action = "delete";
            success_message = "Deleted Successfully";
        } else {
            action = "restore";
            success_message = "Restored Successfully";
        }
        var group_id = $(this).attr('grd_ids');
        swal({
            title: 'Are you sure?',
            text: "",
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
                    type: "POST",
                    url: "<?= base_url('admin/deleteRestoreUnassignedJob') ?>",
                    data: {
                        group_id: group_id,
                        action: action
                    },
                    dataType: 'json'
                }).done(function(data) {

                    $("#loading").css("display", "none");

                    if (data.status == 200) {

                        swal(
                            'Unassigned Service !',
                            success_message,
                            'success'
                        ).then(function() {
                            location.reload();
                        })


                    } else {

                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        })
                    }


                });
            }
        })


    });



 // Setup - add a text input to each footer cell
//  $('#unassigntbl tfoot td').each(function() {
//             var title = $(this).text();
//             if (title == 'PRIORITY' ) {
//                 $(this).html('<input type="text" id = "pfilter" name = "pfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
//             } else if (title == 'SERVICE NAME' ) {
//                 $(this).html('<input type="text" id = "snfilter" name = "snfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
//             } else if (title == 'PROPERTY TYPE') {
//                 $(this).html('<input type="text" id = "ptfilter" name = "ptfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
//             } else if (title == 'SERVICE AREA') {
//                 $(this).html('<input type="text" id = "safilter" name = "safilter" class="form-control dtatableInput" placeholder="' + title + '" />');
//             }  else if (title == 'SERVICE DUE') { //Adding select option for service due filter
//                 $(this).html('<select class="form-control dtatableInput" id = "sdfilter" name = "sdfilter" placeholder="' + title + '" ><option value="0" class="default-option">-- DUE</option><option value="1">Due</option><option    value="2">Overdue</option><option value="3">Not Due</option></select>');
//             }  else if (title == 'NOTIFY CUSTOMER') { //Adding select option for notify customer filter
//                 $(this).html('<select class="form-control dtatableInput" id = "ncfilter" name = "ncfilter" placeholder="' + title + '" ><option value="0" class="default-option">-- NOTIFY</option><option value="1,4">CALL AHEAD</option></select>' );
//             } else if(title=='TAG' ){ //Adding select option for service due filter	
//                 var filterTags = '<?php echo $filter_tags; ?>';	
//                 $(this).html(filterTags);	
//             } else {
//                 $(this).addClass('noSpacingInput');
//             }
//         });




    $("#filter-criteria-id").unbind('click');
    $(document).on("click", "#filter-criteria-id", function(e) {
        // set it
        // var detached_filter = $("#pfilter").detach();
        // console.log("Detached Filter:");
        // console.log(detached_filter);

        // // back it
        // //$('#modal_add_filters').append( $("#pfilter"));
        // detached_filter.appendTo('div.priority-filter');
        //$("#pfilter").show();

            //    $("#modal_add_filters").append(global_r);
                $('#modal_add_filters').modal('show');
    });
</script>


<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>

<script>
    //Reset the map within modal
    $('body').on("click", "#collapse-toggler", function(){

        if ($(this).find('span').hasClass("on"))
        {
            $('.wrapper').hide();
            $('.collapse').collapse("hide");
            const html = 'Show Filters&nbsp;<i class="fa-solid fa-arrow-down">';
            $(this).find('span').removeClass("on");
            $(this).find('span').addClass("off");
            $(this).find('span').html(html);
        } else {
            $('.wrapper').show();
            $('.collapse').collapse("show");
            const html = 'Hide Filters&nbsp;<i class="fa-solid fa-arrow-up">';
            $(this).find('span').addClass("on");
            $(this).find('span').removeClass("off");
            $(this).find('span').html(html);
        }
    });

    $(window).on('resize', function() {
        if($(window).width() < 1024) {
            $(".custom-row").removeClass("d-flex");
            $("#allMessage").attr("style", "left: 22%!important");
            $(".custom-row").addClass("d-flex align-content-stretch flex-wrap-wrap flex-column align-items-stretch");
        } else {
            $(".custom-row").attr('style', 'display: flex!important; align-items: center!important; justify-content: center!important;');
        }
    });

    $(document).ready(function() {
        // $('#service_statuses_filter_filter').select2({
        //     allowClear: true,
        //     placeholder: "-- DUE",
        // });
        $('.collapse').collapse("show");



        $(".close-modal-skip-reason").click(function(){
            $("#modal_skip_reason").modal('hide');
        })

        $("#formTechnicianJobAssign").submit(function(event) {
            let technicianName = $("#technician_id option:selected").text();
            Swal.fire({
                title: 'Please Wait !',
                html: 'Assigning all selected jobs to '+technicianName+'. It may take a while...',// add html attribute if you want or remove
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            });
        });

        $(".service-due-filter").find(".btn-group").css("width", "100%");

        function getRowNum() {
            let e = new Error();
            e = e.stack.split("\n")[2].split(":");
            e.pop();
            return e.pop();
        }
        $( "#mileage" ).click(function(event) {
            event.stopPropagation();
            event.preventDefault();
            let route_id = $("#route_select").val();
            let strLocations = $("#route_select option").filter(":selected").data('row-locations');
            let locations = [];
            if (strLocations) {
                let arrLocations = strLocations.split(';');

                for (i = 0; i < arrLocations.length; i++)
                {
                    if (arrLocations[i] !== '')
                    {
                        let address = arrLocations[i].split(':');

                        locations.push({
                            property_address: address[0],
                            property_latitude: address[1],
                            property_longitude: address[2]
                        });
                    }
                }
            }

            $('#unassigntbl tbody input:checked').each(function() {
                let address = $(this).data('address').split(':');
                locations.push({
                    property_address: address[0],
                    property_latitude: address[1],
                    property_longitude: address[2]
                });
            });

            let technician_id = $("#technician_id").val();
            Swal.fire({
                title: 'Please Wait !',
                html: 'Getting mileage information for you...',// add html attribute if you want or remove
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            });
            $.ajax({
                type: "POST",
                url: "<?= base_url('admin/getMileageAndDriveTimeForRoute') ?>",
                data: {technician_id : technician_id , locations :  JSON.stringify(locations) },
                dataType : "json",
            }).done(function(data){

                if (data.length===0) {
                    $('#'+route_select_id).append('<option value="">No route found</option>');
                } else {
                    post_BasicOptimizeStops(data);
                }
                // Swal.close();
            });
        });

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

        function post_BasicOptimizeStops(request) {
            var resturl = 'https://optimizer.routesavvy.com/RSAPI.svc/';
            postit(resturl + 'POSTOptimize', {
                data: JSON.stringify(request),
                success: function(data) {
                    let mileage = kmToMiles(data.Route.DriveDistance).toFixed(2);
                    let driveTime =  formatTime(data.Route.DriveTime);
                    $('#mileageInfo').text(mileage + ' miles');
                    $('#driveTimeInfo').text(driveTime);
                    $('#modal_mileage').modal('show');
                    console.log(data)
                    var resp = JSON.stringify(data, null, '\t');
                    console.log(resp);
                    Swal.close();
                },
                error: function(err) {
                    Swal.close();
                    $('#loading').css('display', 'none');
                    $('#result').text(JSON.stringify(err, null, '\t'));
                }
            });
        }


        var MapMarkers = [];
        var addressMarkers = {};

        var map;
        var marker;
        var markers = [];
        var filteredMarkers = [];

        var tableData = [];
        var filteredData = [];
        var mapFilteredData = [];
        var furtherFilteredData = [];

        var allChecked = false;
        var priority = $('#priority_filter'),
            name = $('#service_name_filter'),
            property = $('#property_type_filter'),
            area = $('#service_area_filter');



        // // Setup - add a text input to each footer cell
        // $('#unassigntbl tfoot td').each(function() {
        //     var title = $(this).text();
        //     if (title == 'PRIORITY' ) {
        //         $(this).html('<input type="text" id = "pfilter" name = "pfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
        //     } else if (title == 'SERVICE NAME' ) {
        //         $(this).html('<input type="text" id = "snfilter" name = "snfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
        //     } else if (title == 'PROPERTY TYPE') {
        //         $(this).html('<input type="text" id = "ptfilter" name = "ptfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
        //     } else if (title == 'SERVICE AREA') {
        //         $(this).html('<input type="text" id = "safilter" name = "safilter" class="form-control dtatableInput" placeholder="' + title + '" />');
        //     }  else if (title == 'SERVICE DUE') { //Adding select option for service due filter
        //         $(this).html('<select class="form-control dtatableInput" id = "sdfilter" name = "sdfilter" placeholder="' + title + '" ><option value="0" class="default-option">-- DUE</option><option value="1">Due</option><option    value="2">Overdue</option><option value="3">Not Due</option></select>');
        //     }  else if (title == 'NOTIFY CUSTOMER') { //Adding select option for notify customer filter
        //         $(this).html('<select class="form-control dtatableInput" id = "ncfilter" name = "ncfilter" placeholder="' + title + '" ><option value="0" class="default-option">-- NOTIFY</option><option value="1,4">CALL AHEAD</option></select>' );
        //     } else if(title=='TAG' ){ //Adding select option for service due filter	
        //         var filterTags = '<?php echo $filter_tags; ?>';	
        //         $(this).html(filterTags);	
        //     } else {
        //         $(this).addClass('noSpacingInput');
        //     }
        // });

        //$('input[name=changeview]').change(function() {  
        $(document).on("input paste", "input[name=changeview]", function() {
            ////console.log("entered changeview listener");
        
            //var mode = $(this).prop('checked');
            //alert(mode);

            //if (mode == false) {
            if (!$(this).is(':checked')) {
            
                //console.log("False etay");

                $('#tablediv').css('display', 'block');
                $('#mapdiv').css('display', 'block');

                //  $("#mapdiv").removeClass('col-md-4');
                //  $("#mapdiv").addClass('col-md-12');
                //  $("#tablediv").removeClass('col-md-8');

                // $("#tablediv").addClass('col-md-8');
                


                //show the red update map button on left side
                // if (!$("#update-map-note").length > 0) {
                //     $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-primary ml-5 btn btn-danger unassigned-services-element'text-semibold' style='font-size:18px;color:#FFFFFF;background-color:#F44336;'>Update Map View</button");
                // }


            } else {
               //console.log("Beya");
            //    $('#unassigntbl').DataTable( {
            //     dom: 'Bl<"toolbar">frtip',
            //     } );


                $('#mapdiv').css('display', 'none');
                $('#tablediv').css('display', 'block');
                $("#tablediv").removeClass('col-md-8');
                $("#tablediv").addClass('col-md-12');

                $("#update-map-note").remove();

            }
        
        });

        
        //Should add a clear filters button
        //This function breaks pagination and server-side loading
        function LoadMap() {

            //render the filter searches "footer"
            global_r = $('#unassigntbl tfoot td');
            

            
            var mapOptions = {
                //center: new google.maps.LatLng(41.881832, -87.623177), //coordinates for Chicago
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableAutoPan: true,
				maxZoom:25,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.LEFT_BOTTOM
                },
                streetViewControl: true,
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.LEFT_BOTTOM,
                },
            };


            map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);

            const drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: null,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        google.maps.drawing.OverlayType.POLYGON
                    ],
                },
                poligonOptions: {
                    fillColor: "#C0C0C0",
                    fillOpacity: 1,
                    strokeWeight: 5,
                    clickable: true,
                    editable: true,
                    draggable: true,
                    zIndex: 10,
                },
            });
            // Define custom styles for the map
            var styles = [
                {
                    featureType: 'poi',
                    stylers: [{ visibility: 'off' }] // Remove points of interest (POI)
                },
                {
                    featureType: 'poi.business',
                    stylers: [{ visibility: 'off' }] // Remove business markers
                }
            ];

            // Apply the custom styles to the map
            map.setOptions({ styles: styles });

            drawingManager.setMap(map);

            // Map Legend
            const mapLegend = document.getElementById('legend');
            //map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(mapLegend);
            //map.controls[google.maps.ControlPosition.TOP_LEFT].push(mapLegend);
            map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(mapLegend);

           var markers = MapMarkers;
           let selectedMarkers = [];
           let toRemoveMarkers = [];
           let allOverlays = [];
           let polygons = [];
           function deleteAllShape() {
                for (var i=0; i < allOverlays.length; i++)
                {
                    allOverlays[i].setMap(null);
                }
               allOverlays = [];
            }

            function reSelectVisibleMarkers()
            {
                if (allOverlays && allOverlays.length > 0)
                {
                    selectedMarkers = [];
                    Swal.fire({
                        title: 'Please Wait !',
                        html: 'Selecting all properties inside this polygon for you...',// add html attribute if you want or remove
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        },
                    });

                    // Delay execution of the rest of the code by 10 milliseconds
                    setTimeout(() => {
                        for (i = 0; i < allOverlays.length; i++)
                            allOverlays[i].setMap(map);

                        selectMarkersInOverlay(map, allOverlays[0], markers);

                        // Close the Sweet Alert loader in the callback function
                        swal.close();

                    }, 10);
                }
            }
            function uniqueNumber()  {
                return Math.floor((1 + Math.random()) * 0x10000)
                    .toString(16)
                    .substring(1);
            }
            google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {

                console.log('drawing Overlay');

                let overlay = event.overlay;
                allOverlays.push(overlay);
                google.maps.event.addListener(overlay, 'click', function() {
                    overlay.setEditable(true); // permite a edição do overlay
                });

                google.maps.event.addListener(overlay, 'set_at', function(index) {
                    console.log("Set");
                    selectedMarkers = [];
                    unCheckAll();
                    Swal.fire({
                        title: 'Please Wait !',
                        html: 'Selecting all properties inside this polygon for you...',// add html attribute if you want or remove
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        },
                    });
                    // Delay execution of the rest of the code by 10 milliseconds
                    setTimeout(() => {
                        selectMarkersInOverlay(map, overlay, markers);

                        // Close the Sweet Alert loader in the callback function
                        swal.close();

                    }, 10);
                });

                google.maps.event.addListener(overlay, 'insert_at', function(index) {

                    selectedMarkers = [];
                    unCheckAll();
                    Swal.fire({
                        title: 'Please Wait !',
                        html: 'Selecting all properties inside this polygon for you...',// add html attribute if you want or remove
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        },
                    });
                    // Delay execution of the rest of the code by 10 milliseconds
                    setTimeout(() => {

                        selectMarkersInOverlay(map, overlay, markers);

                        // Close the Sweet Alert loader in the callback function
                        swal.close();

                    }, 10);
                });
                function onPolygonEdit() {
                    // Encontre o índice do polígono editado no array polygons
                    var editedPolygonIndex = -1;
                    for (var i = 0; i < allOverlays.length; i++) {
                        if (allOverlays[i] === overlay.getPath()) {
                            editedPolygonIndex = i;
                            break;
                        }
                    }
                }
                Swal.fire({
                    title: 'Please Wait !',
                    html: 'Selecting all properties inside this polygon for you...',// add html attribute if you want or remove
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                });
                // Delay execution of the rest of the code by 10 milliseconds
                setTimeout(() => {
                    selectMarkersInOverlay(map, overlay, markers);

                    // Close the Sweet Alert loader in the callback function
                    swal.close();

                }, 10);
            });
           function checkPositionInAllOverlays(position) {
               for(let j = 0; j < allOverlays.length; j++)
               {
                   if (google.maps.geometry.poly.containsLocation(position, allOverlays[j])) {
                       return j;
                   }
               }
               return -1;
           }
            function containsObject(obj, list) {
                var x;
                for (x in list) {
                    if (list.hasOwnProperty(x) && list[x] === obj) {
                        return true;
                    }
                }

                return false;
            }
            function selectMarkersInOverlay(map, overlay, markers) {
                if(markers.length == 0)
                    markers = MapMarkers;
                let timer = 0;
                const start = Date.now();
                let keys = [];
                $(selectedMarkers).each(function(key, value) {
                    if (value) {
                        if (!keys.includes(key))
                            keys.push(key);
                    }
                })
                if(overlay instanceof google.maps.Polygon) {
                    for (var i = 0; i < markers.length; i++) {
                        if (markers[i].getMap())
                        {
                            var position = markers[i].getPosition();
                            if (!keys.includes(i)) {
                                let location = checkPositionInAllOverlays(position);
                                console.log(location);
                                if (location >= 0) {
                                    if (!selectedMarkers[i]) {
                                        let parentElementOnHold = $("#"+i).parent().parent().hasClass('row_in_hold');
                                        // if (markers[i].index)
                                        //     $("#"+markers[i].index).click();
                                        // else
                                        //     $("#"+i).click();
                                        if (!parentElementOnHold)
                                            selectedMarkers[i] = markers[i];
                                    }
                                } else {
                                    if (selectedMarkers[i]) {
                                        // if (selectedMarkers[i].index)
                                        //     $("#"+selectedMarkers[i].index).click();
                                        // else
                                        //     $("#"+i).click();
                                        selectedMarkers[i] = null;
                                        toRemoveMarkers[i] = markers[i];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    console.log('Unsupported overlay type.');
                }
                const end = Date.now();
                // let keys2 = Array.from(selectedMarkers.keys());
                let str = '';
                let ids = [];
                let str2 = '';
                let index;
                
                if (selectedMarkers.length > 0)
                    $('#allMessage').prop('disabled', false);

                $(selectedMarkers).each(function(key, value) {
                    if (value) {
                        if (!ids.includes(key)) {
                            ids.push(key);
                            index = key;
                            str += "#"+key+',';
                        }
                    }
                })
                $(toRemoveMarkers).each(function(key, value) {
                    if (value) {
                        str2 += "#"+key+',';
                    }
                })

                var answer = str.substring(0, str.length-1);
                var answer2 = str2.substring(0, str2.length-1);

                // $(answer).click();
                // $(answer2).click();

                $(answer).prop('checked', true);
                $(answer2).prop('checked', false);
                setTimeout(() => {
                    checkMany(ids);
                    sumSqrFeet();
                }, 10);
                console.log(`Execution time: ${end - start} ms`);
                console.log(`Selected markers:`);
                console.log(selectedMarkers.keys());
            }
            google.maps.event.addListener(map, 'dragend', function boundsChanged() {

                console.log("");
                console.log("Start");
                console.log("This is initial load(dragend): " + initial_load);
                var validmarkers = [];
                var passmarkers = [];
                var count3 = 0;

                var latArray = [];
                var lngArray = [];
                var gData = '';
                // Construct an empty list to fill with onscreen markers.
                var inBounds = [],
                    // Get the map bounds - the top-left and bottom-right locations.
                    bounds = map.getBounds();
                
                /////
                console.log("table == "+ (typeof table) );
				if(typeof table !== 'undefined')
					{
						// console.log("this is our filteredMarkers:"+filteredMarkers);
                        console.log("table NOT undefined");

                        filteredMarkers = [];

                        MapMarkers.forEach(item => {
                            item.setMap(null);
                            item = null;
                        });
                        MapMarkers = [];


                        var tableData = table.data();
                        var latlngbounds = new google.maps.LatLngBounds();


                        // Get current map bounds
                        var bounds = map.getBounds();
                        var southWest = bounds.getSouthWest();
                        var northEast = bounds.getNorthEast();
                        var northEastLng = northEast.lng();
                        var northEastLat = northEast.lat();
                        var southWestLng = southWest.lng();
                        var southWestLat = southWest.lat();
                        var northWestLng = northEastLng;
                        var northWestLat = southWestLat;
                        var southEastLng = southWestLng;
                        var southEastLat = northEastLat;
                        console.log("Typeof");

                        for (let i = 0; i < tableData.length; i++) {

                            var data = tableData[i];
                            filteredMarkers.push(data);
                            
                            var lancedata = JSON.parse(JSON.stringify(tableData[i]));
                            
                            var splitstring = lancedata.action.split("grd_ids='");
                            var split2 = splitstring[1].split("'");
                            var gudid = split2[0];


                            //var marker = new google.maps.Marker({
                            //    icon: '<?php //= base_url("assets/img/default.png") ?>//',
                            //    position: new google.maps.LatLng(tableData[i].property_latitude, tableData[i].property_longitude),
                            //    lat: tableData[i].property_latitude,
                            //    lng: tableData[i].property_longitude,
                            //    map: map,
                            //    title: tableData[i].address,
                            //    realval: gudid,
                            //    index: i
                            //
                            //});

                            // latlngbounds.extend(marker.position);
							//console.log("latlngbounds: ");
							
                            // MapMarkers.push(marker);
                            // AllMarkers.push(marker);
							//console.log('placing marker '+marker.position);


						}

                    }
				
				//////
							
							var markers = MapMarkers;

                            //console.log(MapMarkers);

				
				
				for (var i = 0; i < markers.length; i++) {
                    if(markers[i].getMap())
                    {
                        if (map.getBounds().contains(markers[i].getPosition())) {
                        
                            passmarkers[i] = markers[i];
                        
                            //need to get an array of valid markers here
                            validmarkers[i] = markers[i].realval;
                         
                            count3++;
                        } else {
                            //console.log("property out of bounds "+markers[i].realval);
                        }
                    }
                }

              

                var markerarray = [];
                var count = 0;
                var count2 = 0;
				
				//console.log('validmarker length '+validmarkers.length);

                for (var i = 0; i < validmarkers.length; i++) {

                    if (typeof validmarkers[i] !== 'undefined') {
                        
                        markerarray.push(validmarkers[i]);
                       
                        count++;

                        // console.log("VALID MARKER "+validmarkers[i]);
                        

                    } else {
                        //console.log("BAD ADDRESS");
                    }

                    
                    
                }
                //console.log("makearray MARKER "+markerarray);
                console.log("THIS IS COUNT OF VALID MARKERS " + count);
               // //console.log(count);
                // for (var i = 0; i < validmarkers.length; i++) {

                //     if (typeof validmarkers[i] !== 'undefined') {
                       
                //         count2++;
                        
                //     } 
                    
                // }

               
                    //console.log(markerarray);
           

                    
				//console.log("inside load map table init");

                
                table = $('#unassigntbl').on( 'draw.dt', function () {
                   
                } ).DataTable({
                    destroy: true,
                    serverSide: true,
                    ajax: {
                        url: "<?= base_url('admin/ajaxGetRoutingFORMAPS/') ?>",
                        dataType: "json",
                        type: "POST",
                        data: {
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                            ,
                            'markerarray': JSON.stringify(markerarray),
                            northEastLng: northEastLng,
                            northEastLat: northEastLat,
                            northWestLng: northWestLng,
                            northWestLat: northWestLat,
                            southWestLng: southWestLng,
                            southWestLat: southWestLat,
                            southEastLng: southEastLng,
                            southEastLat: southEastLat
                        }
                    },

                    stateSave: true, //keeps data table from removing filters. breaks updating based on map movement
                    deferRender: true, //false
                    columnDefs: [{
                        "targets": [0],
                        "checkboxes": {
                            "selectRow": true,
                            "stateSave": true
                        }
                    }, ],
                    select: "multi",
                    columns: [{
                            "data": "checkbox",
                            "checkboxes": true,
                            "stateSave": true,
                            "searchable": false,
                            "orderable": false
                        },
                        {
                            "data": "priority",
                            "name": "Priority",
                            "orderable": true,
                            "searchable": true
                        },
                        {
                            "data": "job_name",
                            "name": "Service Name",
                            "searchable": true,
                            "orderable": true
                        },
                        {   "data": "pre_service_notification", 
                            "name":"Notify Customer", 
                            "orderable": true, 
                            "searchable": true 
                        },
                        {
                            "data": "customer_name",
                            "name": "Customer Name",
                            "searchable": true,
                            "orderable": true
                        },
                        {
                            "data": "property_name",
                            "name": "Property Name",
                            "searchable": true,
                            "orderable": true
                        },
                        {
                            "data": "square_feet",
                            "name": "Square Feet",
                            "orderable": true
                        },
                        {
                            "data": "last_service_date",
                            "name": "Last Service Date",
                            "orderable": true
                        },
                        {
                            "data": "last_program_service_date",
                            "name": "Last Program Service Date",
                            "orderable": true
                        },
                        {
                            "data": "completed_date_last_service_by_type",
                            "name": "Last Service Type Date",
                            "orderable": true
                        },
                        {
                            "data": "service_due",
                            "name": "Service Due",
                            "orderable": true
                        },
                        {
                            "data": "address",
                            "name": "Address",
                            "searchable": true,
                            "orderable": true
                        },
                        {
                            "data": "property_type",
                            "name": "Property Type",
                            "orderable": true
                        },
                        {
                            "data": "property_notes",
                            "name": "Property Info",
                            "searchable": true,
                            "orderable": true
                        },
                        {
                            "data": "category_area_name",
                            "name": "Service Area",
                            "orderable": true
                        },
                        {
                            "data": "program",
                            "name": "Program",
                            "orderable": true
                        },
                        {
                            "data": "reschedule_message",
                            "name": "Note",
                            "orderable": true
                        },
                        {
                            "data": "tags",	
                            "name":"Tags",	
                            "orderable": true	
                        },
                        {
                            "data": "asap_reason",
                            "name":"Asap Reason",
                            "orderable": true
                        },
                        {
                            "data": "available_days",
                            "name":"Available Days",
                            "orderable": false
                        },
                        {
                            "data": "hold_until_date",
                            "name":"Hold Until Date",
                            "orderable": true
                        },
                        {
                            "data": "action",
                            "name": "Action",
                            "searchable": false,
                            "orderable": false
                        },
                        {
                            "data": "program_services", //
                            "name": "Program Service Name",
                            "searchable": true,
                            "visible": false
                        }
                    ],
                    language: {
                        search: '<span></span> _INPUT_',
                        lengthMenu: '<span>Show:</span> _MENU_',
                        paginate: {
                            'first': 'First',
                            'last': 'Last',
                            'next': '&rarr;',
                            'previous': '&larr;'
                        }
                    },
                    pagingType: "simple_numbers",

                    paging: true,
                    pageLength: 10000, //not pulling all data. Only the first 100 (because client side)
                    order: [
                        [1, 'asc'],
                        [2, 'asc']
                    ], 
                    processing: true,
                     
                    dom: 'Bl<"toolbar">frti',
                    initComplete: function(data2) {
                        // $('#loading').show();

                        //console.log("this is our filteredMarkers2");

                        console.log("datatable initComplete function");

                        // deleteAllShape();

                        addressMarkers = {};
                        let sameProperty = [];

                        MapMarkers.forEach(item => {
                            item.setMap(null);
                            item.setVisible(false);
                            item = null
                        })

                        MapMarkers = [];
                        clearMapInfoIcons();
                        clearMapMarkerIcons();
                        clearMapMarkerClusters();
                        removeAllMapActiveMarkerIcon();

                        var tableData = table.data();
                        var latlngbounds = new google.maps.LatLngBounds();

                        //console.log("This is tableData: ");
                        //console.log(tableData);

                        // console.log("This is tableData.length: ");
                        // console.log(tableData.length);
                        console.log("Initcomplete");
                        for (let i = 0; i < tableData.length; i++) {

                            var data = tableData[i];
                            filteredMarkers.push(data);
                            
                            var lancedata = JSON.parse(JSON.stringify(tableData[i]));
                            
                            var splitstring = lancedata.action.split("grd_ids='");
                            var split2 = splitstring[1].split("'");
                            var gudid = split2[0];

                            var infoWindow = new google.maps.InfoWindow({
                                content: tableData[i].address
                            });

                            addMapInactiveMarkerIcon(map,
                                tableData[i].property_latitude,
                                tableData[i].property_longitude,
                                tableData[i].address,
                                tableData[i].service_type_color,
                            );
                            addMapInfoIcons(tableData[i]);

                            var marker = getOverlayMapMarker(map,i,
                                tableData[i].property_latitude,
                                tableData[i].property_longitude
                            );

                            var address = tableData[i].address;

                            if (address in addressMarkers) {
                                addressMarkers[address].push(data.index);
                            } else {
                                addressMarkers[address] = [data.index];
                            }

                            (function(marker, data) {
                                google.maps.event.addListener(marker, "click", function(e) {
                                    console.log("clicked");
                                    infoWindow.setContent(tableData[i].address);
                                    if (tableData[i].address in addressMarkers)
                                    {
                                        for(j = 0; j < addressMarkers[tableData[i].address].length; j++)
                                        {
                                            if (i !== tableData[addressMarkers[tableData[i].address][j]].index)
                                                $("#"+tableData[addressMarkers[tableData[i].address][j]].index).click();
                                        }
                                    }
                                    $("#"+tableData[i].index).click();
                                    infoWindow.open(map, marker);
                                });
                            })(marker, data);

                            latlngbounds.extend(marker.position);
							//console.log("latlngbounds: ");

                            MapMarkers.push(marker);
                            AllMarkers.push(marker);

							//console.log('placing marker '+marker.position);

                        }
                        console.log("For datatable data complete.");

                        addMapMarkerClusters(map,MapMarkers);


                        if(data2.json.possible_errors != "") {
                            swal("Verizon Connect Error", data2.json.possible_errors, "error");
                        }
                        var full_vehicle_data = data2.json.full_vehicle_data
                        full_vehicle_data.forEach(function(vehicle) {
                            var marker = new google.maps.Marker({
                                icon: '<?= base_url("assets/img/driver.png") ?>',
                                position: new google.maps.LatLng(vehicle.Latitude, vehicle.Longitude),
                                lat: vehicle.Latitude,
                                lng: vehicle.Longitude,
                                map: map,
                                title: vehicle.DriverName,
                                realval: gudid,
                                index: i

                            });
                            latlngbounds.extend(marker.position);
                            MapMarkers.push(marker);
                        });


                        if (set_initial_center == true) {
                            latlngbounds.extend(marker.position);
                            map.fitBounds(latlngbounds);
                            map.setZoom(4);
                        
                            set_initial_center = false;
                        
                        }
                        //console.log(addressMarkers);
						//console.log("zoom level "+map.getZoom());
                     
                        // $("div.toolbar").html('');

                        $('#filter-criteria-id').remove();
                        $('#multiple-delete-id').remove();
                        $('#multiple-skip-id').remove();

                        // $(".dataTables_filter")
                        //     .append('<button id="filter-criteria-id" class="btn btn-primary ">Filters</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button disabled="disabled" id="multiple-delete-id" class="btn btn-danger unassigned-services-element">Delete Services</button>');

                        $(".dataTables_filter")
                            .append('&nbsp;&nbsp;&nbsp;<button disabled="disabled" id="multiple-delete-id" class="btn btn-danger unassigned-services-element">Delete Services</button>');

                            //.append('<button id="filter-criteria-id" class="btn btn-primary ">Filters</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button disabled="disabled" id="multiple-delete-id" class="btn btn-danger unassigned-services-element">Delete Services</button>');
                        $(".dataTables_filter")
                            .append('<button disabled="disabled" id="multiple-skip-id" class="ml-5 btn btn-warning" data-toggle="modal" data-target="#modal_skip_reason">Skip</button>');
                        // $("#maintable_filter")
                        //     .append('<button id="filter-criteria-id" class="btn btn-primary ">Filters</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button disabled="disabled" id="multiple-delete-id" class="btn btn-danger unassigned-services-element">Delete Services</button>');
                        // $("div.toolbar")
                        //     .append($('#multiple-delete-id'));

                        //     $("div.toolbar")
                        //     .append($('#filter-criteria-id'));

                        // $("div.toolbar")
                        //     .append(global_r);

                        //$(global_r).hide();
            
                        var sqftTotal = 0;
                        var applicationSqft = 0;
                        var costTotal = 0;
                        $('#totalSqFt').val(sqftTotal);
                        $('#applicationSqFt').val(applicationSqft);
                        $('#totalApplicationCost').val(costTotal);

                        //console.log("This is application square feet:");
                        //console.log(applicationSqft);

                        //remove update map button on drag because map updates on drag.
                        $("#update-map-note").remove();
                        $("#update-map-note-two").remove();
                        

                        //Get priority input value
                        if (sessionStorage.getItem("prio_input")) {
                        //console.log("This is prio input");
                        //console.log(sessionStorage.getItem("prio_input"));
                        document.getElementById("pfilter").value = sessionStorage.getItem("prio_input");
                        }

                        //Get service name input value
                        if (sessionStorage.getItem("serv_name_input")) {
                        //console.log(sessionStorage.getItem("serv_name_input"));
                        document.getElementById("snfilter").value = sessionStorage.getItem("serv_name_input");
                        }

                        //Get service due input value
                        if (sessionStorage.getItem("serv_due_input")) {
                        //console.log(sessionStorage.getItem("serv_due_input"));
                        document.getElementById("sdfilter").selectedIndex = sessionStorage.getItem("serv_due_input");
                        }

                        //Get property type input value
                        if (sessionStorage.getItem("prop_type_input")) {
                        //console.log(sessionStorage.getItem("prop_type_input"));
                        document.getElementById("propertyTypefilter").value = sessionStorage.getItem("prop_type_input");
                        }

                        //Get service area input value
                        if (sessionStorage.getItem("serv_area_input")) {
                        //console.log(sessionStorage.getItem("serv_area_input"));
                        document.getElementById("safilter").value = sessionStorage.getItem("serv_area_input");
                        }

                        // Get "Notify" input value
                        if (sessionStorage.getItem("notify_input")) {
                        //console.log(sessionStorage.getItem("notify_input"));
                        
                        }

                        //Get 'Search" input value
                        if (sessionStorage.getItem("search_input")) {
                        //console.log(sessionStorage.getItem("search_input"));
                        
                        }

                        //Get 'Search" input value
                        if (sessionStorage.getItem("serv_multi_filter_input")) {
                        //console.log(sessionStorage.getItem("serv_multi_filter_input"));
                        
                        }

                        // $("#unassigntbl").DataTable().columns(1).search("6").draw();
                        // Connect the filter inputs to filter data

                        // 'Search' filter input
                        $('.dataTables_filter input').on('input', function(e) {
                           
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("search_input", filter_input_val);
                            
                            $('#filter_message').hide();
                            $("#update-map-note").remove();
                            $("#update-map-note-two").remove();
                            if (!$('input[name=changeview]').is(':checked')) {

                                //modal note
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger 'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                                //above map note
                                if (!$("#update-map-note-two").length > 0) {
                                    $("#update-map-div-two").append("<button type='button' id='update-map-note-two' class ='btn btn-danger 'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div-two").append($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note-two").prop("disabled",true);
                                }
                            }
                        });

                        // red click to update map button on "Show:" dropdown change
                        $('select[name="unassigntbl_length"]').on('change', function() { // SERVICE DUE
                            var filter_input_val = this.querySelector('input').value;
                            sessionStorage.setItem("serv_due_input", filter_input_val);
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });
                        
                        $('#pfilter').on('blur', function priority() { // PRIORITY
                            //console.log("priority input changed");
                            //var filter_input_val = this.querySelector('input').value;
                            var filter_input_val = $(this).val();
                            //console.log(filter_input_val);
                            sessionStorage.setItem("prio_input", filter_input_val);
                            
                                table.columns(1).search(filter_input_val).draw();
                                $('#unassigntbl_info').show();
                                $('#unassigntbl').show();
                                $('#filter_message').hide();
                                
                           
                            
                            $("#update-map-note").remove();
                            //$("#update-map-note").hide();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    //$("#update-map-note").show();
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                            //console.log("this is priority filter val");
                            //console.log( $('#priority_filter').val());
                        });

                        // Connect the filter inputs to filter data
                        $('#snfilter').on('blur', function() { // SERVICE NAME
                            //var filter_input_val = this.querySelector('input').value;
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("serv_name_input", filter_input_val);
                            table.columns(2).search(filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        // Connect the filter inputs to filter data
                        $('#ncfilter').on('blur', function() { // NOTIFY
                            //var filter_input_val = this.querySelector('select').value;
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("notify_input", filter_input_val);
                            table.columns( 3 ).search( filter_input_val ).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        // Connect the filter inputs to filter data
                        $('#sdfilter').on('change', function() { // SERVICE DUE
                            //var filter_input_val = $('#service_due_filter option:selected').val();
                            var filter_input_val = $('#sdfilter').val();
                            let filter_input_val_arr = filter_input_val.join('|');

                            //console.log(filter_input_val);
                            sessionStorage.setItem("serv_due_input", filter_input_val);
                            table.columns(10).search(filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });


                        // Connect the filter inputs to filter data
                        $('#propertyTypefilter').on('blur', function() { // PROPERTY TYPE
                            //var filter_input_val = this.querySelector('input').value;
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("prop_type_input", filter_input_val);
                            table.columns(12).search(filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        // Connect the filter inputs to filter data
                        // $('#safilter').on('blur', function() { // SERVICE AREA
                        //     //var filter_input_val = this.querySelector('input').value;
                        //     var filter_input_val = $(this).val();
                        //     sessionStorage.setItem("serv_area_input", filter_input_val);
                        //     table.columns(13).search(filter_input_val).draw();
                        //     $("#update-map-note").remove();
                        //     if (!$('input[name=changeview]').is(':checked')) {
                        //         if (!$("#update-map-note").length > 0) {
                        //             $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                        //             $("#update-map-div").prepend($("#loading-image"));
                        //             $("#loading-image").show();
                        //             $("#update-map-note").prop("disabled",true);
                        //         }
                        //     }
                        // });

                        // Connect the filter inputs to filter data
                        $('#tag_filter').on('change', function() { // PROPERTY TYPE
                            //var filter_input_val = this.querySelector('select').value;
                            var filter_input_val = $('#tag_filter option:selected').val();
                            // sessionStorage.setItem("tags_filter_input", filter_input_val);
                            table.columns( 17 ).search( filter_input_val ).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        //Connect Multi-Select input to filter data
                        $('#services_multi_filter').on('change', function() { // Service Name
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("serv_multi_filter_input", filter_input_val);
                            $('#applicationSqFt').val('');
                            $('#totalSqFt').val('');
                            $('#totalApplicationCost').val('');
                            let multi_service_val = $(this).val();
                            
                            table.columns( 2 ).search( multi_service_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });
                        //Connect Multi-Select input to filter data
                        $('#service-area-filter').on('change', function() { // Service Name
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("service-area-filter", filter_input_val);
                            $('#applicationSqFt').val('');
                            $('#totalSqFt').val('');
                            let multi_service_val = $(this).val();

                            table.columns( 14 ).search( multi_service_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });
                        $('#program_services_multi_filter').on('change', function() { // Service Name
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("program_services_multi_filter", filter_input_val);
                            $('#applicationSqFt').val('');
                            $('#totalSqFt').val('');
                            table.columns( 22 ).search( filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });
                        $('#asap-reason').on('blur', function() { // PROPERTY TYPE
                            //var filter_input_val = this.querySelector('input').value;
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("asap-reason", filter_input_val);
                            table.columns(18).search(filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        $('#available_days_filter').on('change', function() { // PROPERTY TYPE
                            //var filter_input_val = this.querySelector('input').value;
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("available_days_filter", filter_input_val);
                            table.columns(19).search(filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        // BLUE ROWS for rescheduled on page load
                        $('.myCheckBox').each(function() {
                              var row_job_mode = $(this).data('row-job-mode');
                              if (row_job_mode == 2) {
                                 $(this).parent().parent().addClass('rescheduled_row');
                              }
                              let asap_job_mode = $(this).data('row-asap');

                              if (asap_job_mode == 1){
                                $(this).parent().parent().addClass('asap_row');
                              }
                        });

                        
                        // CALCULATE total square feet
                        //KT
                        $('.myCheckBox').change(function(){

                            var sqftTotal = 0;
                            $('#unassigntbl tbody input:checked').each(function() {
                                sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
                            });
                            $('#totalSqFt').val(sqftTotal);

                            let applicationSqft = 0;
                            let tmpAddressArray = [];
                            $('#unassigntbl tbody input:checked').each(function() {
                                let currentAddress = $(this).parent().parent().find('td').eq(10).text();
                                if(!tmpAddressArray.includes(currentAddress)) {
                                    tmpAddressArray.push(currentAddress);
                                    applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
                                }
                                //console.log(applicationSqft);
                                //console.log(tmpAddressArray);
                            });
                            $('#applicationSqFt').val(applicationSqft);

                           
                            if(this.checked == false){ //if this item is unchecked
                                    $("#select_all")[0].checked = false; //change "select all" checked status to false
                            }

                            //check "select all" if all checkbox items are checked
                            if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){
                                    $("#select_all")[0].checked = true; //change "select all" checked status to true
                            }
                        });
                        //KT

                        //addCheckBoxEvent();
						
						

                        // FIRE EVERYTIME AFTER TABLE HAS RENDERED
                        table.on('draw', function() {


                            $('#loading-image').hide();

                            // BLUE ROWS for rescheduled on page load
                            $('.myCheckBox').each(function() {
                                var row_job_mode = $(this).data('row-job-mode');
                                if (row_job_mode == 2) {
                                    $(this).parent().parent().addClass('rescheduled_row');
                                }
                                let asap_job_mode = $(this).data('row-asap');

                                if (asap_job_mode == 1){
                                    $(this).parent().parent().addClass('asap_row');
                                }
                            });

                            // Modal Update Map button dark red
                            if ($("#update-map-note").length > 0) {
                                $("#update-map-note").remove();
                                $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger'text-semibold' style='color:#FFFFFF;background-color:#F44336;'>Update Map View</button>");
                            }

                            //above map Update Map button dark red
                            if ($("#update-map-note-two").length > 0) {
                                $("#update-map-note-two").remove();
                                $("#update-map-div-two").append("<button type='button' id='update-map-note-two' class ='btn btn-danger'text-semibold' style='color:#FFFFFF;background-color:#F44336;'>Update Map View</button>");
                            }


                            // CALCULATE total square feet on ajax table refresh
                            $('.myCheckBox').change(function() {

                                var sqftTotal = 0;
                                $('#unassigntbl tbody input:checked').each(function() {
                                sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
                                });
                                $('#totalSqFt').val(sqftTotal);

                                let applicationSqft = 0;
                                let tmpAddressArray = [];
                                $('#unassigntbl tbody input:checked').each(function() {
                                    let currentAddress = $(this).parent().parent().find('td').eq(10).text();
                                    if(!tmpAddressArray.includes(currentAddress)) {
                                        tmpAddressArray.push(currentAddress);
                                        applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
                                    }
                                    //console.log(applicationSqft);
                                    
                                });
                                $('#applicationSqFt').val(applicationSqft);

                                //uncheck "select all", if one of the listed checkbox item is unchecked
                                if(this.checked == false){ //if this item is unchecked
                                    $("#select_all")[0].checked = false; //change "select all" checked status to false
                                }

                                //check "select all" if all checkbox items are checked
                                if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){
                                    $("#select_all")[0].checked = true; //change "select all" checked status to true
                                }
                            });

                            //after search
                            $('.customer_in_hold').each(function() {
                                    var _col=$(this).parent();
                                    var _row=$(_col).parent();
                                    $(_row).addClass('row_in_hold');
                            });
                            setTimeout(() => {
                                //console.log("Delayed for 1 second.");
                                reSelectVisibleMarkers();
                            }, 500);
                        });

                        //on draw
                        $('.customer_in_hold').each(function() {
                            var _col=$(this).parent();
                            var _row=$(_col).parent();
                            $(_row).addClass('row_in_hold');
                        });

                    } // end InitComplete


                });

                /////////////////////////////////////////



                ////////////////////////////////////////////////////////////////


            });
            google.maps.event.addListener(map, 'idle', function() {
            //console.log("Initial load entering idle listener");
                $("#select_all")[0].checked = false;
                 //google.maps.event.trigger(this, 'dragend');
            });


            //table.state.clear();
            var switched = true; //false;
            var filterInput = false;
            var filterArray = [false, false, false, false, false];
            var filterArray2 = [false, false, false, false, false];
            var triggers = [
                [false, false],
                [false, false],
                [false, false],
                [false, false],
                [false, false],
            ];







        } //end LoadMap()
		

		$( "#resetMap" ).click(function() {
			//console.log('in resetMap');

			setTimeout(() => {
  				//console.log("Delayed for 1 second.");
				google.maps.event.trigger(map, 'dragend');
			}, 1000);
			//map.setZoom(10);
            $("#update-map-note").remove();
            $("#update-map-note-two").remove();
            $('#unassigntbl_info').show();
            $('#unassigntbl tbody').show();
            $('#filter_message').hide();
            $(".close").click();
		});

        //Reset the map within modal
        $('body').on("click", "#update-map-note", function(){
            $( ".navbar-toggler").trigger('click');
            $( "#resetMap" ).trigger('click');
        });

        //Reset the map outside modal
        $('body').on("click", "#update-map-note-two", function(){
            $( "#resetMap" ).trigger('click');
        });

        //not being used
        function styleNote() {
            const updateMapNote = document.querySelector('#update-map-note');
            updateMapNote.classList.toggle('show');
        }

        function sumSqrFeet(){
            var sqftTotal = 0;
            $('#unassigntbl tbody input:checked').each(function() {
                sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
            });
            $('#totalSqFt').val(sqftTotal);

            var costTotal = 0;
            let applicationSqft = 0;
            let tmpAddressArray = [];
            $('#unassigntbl tbody input:checked').each(function() {
                let currentAddress = $(this).parent().parent().find('td').eq(11).text();
                if(!tmpAddressArray.includes(currentAddress)) {
                    tmpAddressArray.push(currentAddress);
                    applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
                }

                let cost = $(this).data('cost');
                if (cost)
                    costTotal = costTotal + parseFloat(cost);

                //console.log(applicationSqft);
                //console.log(tmpAddressArray);
            });
            $('#applicationSqFt').val(applicationSqft);
            $('#totalApplicationCost').val(costTotal.toFixed(2));

        
            if(this.checked == false){ //if this item is unchecked
                $("#select_all")[0].checked = false; //change "select all" checked status to false
            }

            //check "select all" if all checkbox items are checked
            if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){
                $("#select_all")[0].checked = true; //change "select all" checked status to true
            }
        }
        
        function checkMany(ids){
            console.log("checkMany()");
            console.log(ids);
            for (j = 0; j < ids.length; j++)
            {
                var checked_realvalue = [];
                $('input:checkbox.map').each(function(index, element) {
                    ////console.log("REACHED INPUT CHECKBOX MAP");
                    if ($("#"+ids[j]).is(":checked")) {
                        // //console.log("if input checkbox map .each is checked");
                        // //console.log("Index = " + index);
                        var checked_value = $(this).data('realvalue');
                        checked_realvalue.push(checked_value);
                        ////console.log('checked: '+ $(this).val());

                    }

                });
                ////console.log('array of checked indexes = ' + checked_realvalue);
                $('#checkbox_realvalues_array').val(checked_realvalue);

                /// END LF
                position = $("#"+ids[j]).val();

                for (let i = 0; i < filteredMarkers.length; i++) {
                    // //console.log("position: " + position);
                    // //console.log("filteredMarkers.index = " + filteredMarkers[i].index);
                    if (position == filteredMarkers[i].index) {
                        // //console.log(filteredMarkers[i]);
                        var data = filteredMarkers[i];
                        ////console.log(data);
                    }
                }

                const image = "http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-poi.png";

                let mapMarker = MapMarkers[position];
                let myLatlng = new google.maps.LatLng(data.lat, data.lng);
                let title = data.title;

                ////console.log(MapMarkers);
                if (mapMarker != null) {
                    mapMarker.setMap(null);
                }

                if (!$("#"+ids[j]).is(":checked")) {

                    console.log("[] inactive marker");

                    removeMapActiveMarkerIcon(data.lat, data.lng, data.job_id);

                    var marker = getOverlayMapMarker(map,null,
                        data.lat, data.lng
                    );


                    MapMarkers[position] = marker;
                } else {
                    console.log("[] active marker");

                    addMapActiveMarkerIcon(map,
                        data.lat, data.lng, data.address,
                        data.service_type_color, data.job_id
                    );

                    var marker = getOverlayMapMarker(map,null,
                        data.lat, data.lng
                    );

                    MapMarkers[position] = marker;

                }
                var tableData = table.data();
                google.maps.event.addListener(marker, "click", function(e) {
                    $("#"+tableData[position].index).click();
                });
            }

        }
        function addCheckBoxEvent() {
            console.log("addCheckBoxEvent() >>");



            $(document).on("input paste", "input:checkbox.map", function() {
                //$(document).on("click", "input:checkbox.map", function(){
                //$(document).on("click", ".myCheckBox", function(){

                // LF - shouldnt we get values of all checked checkboxes each time an event occurs and store
                var checked_realvalue = [];
                var costTotal = 0;

                $('input:checkbox.map').each(function(index, element) {
                    ////console.log("REACHED INPUT CHECKBOX MAP");
                    if ($(this).is(":checked")) {
                       // //console.log("if input checkbox map .each is checked");
                       // //console.log("Index = " + index);
                        var checked_value = $(this).data('realvalue');
                        checked_realvalue.push(checked_value);
                        ////console.log('checked: '+ $(this).val());

                        let cost = $(this).data('cost');
                        if (cost)
                            costTotal = costTotal + parseFloat(cost);

                    }

                });
                ////console.log('array of checked indexes = ' + checked_realvalue);
                $('#checkbox_realvalues_array').val(checked_realvalue);

                $('#totalApplicationCost').val(costTotal.toFixed(2));

                /// END LF		 
                position = $(this).val();

                // //console.log($(this).val());

                ////console.log("checked value = " + position);
                ////console.log("filteredMarkers.length = " + filteredMarkers.length);


                ////console.log(filteredMarkers);

                for (let i = 0; i < filteredMarkers.length; i++) {
                    // //console.log("position: " + position);
                    // //console.log("filteredMarkers.index = " + filteredMarkers[i].index);
                    if (position == filteredMarkers[i].index) {
                        // //console.log(filteredMarkers[i]);
                        var data = filteredMarkers[i];
                        ////console.log(data);
                    }
                }

                //  let data = filteredMarkers[position];

                // //console.log(JSON.stringify(data));
                //console.log('data.lat: ' + data.lat);
                //console.log('data.lng: ' + data.lng);

                //  //console.log(MapMarkers);

                const image = "http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-poi.png";

                let mapMarker = MapMarkers[position];
                let myLatlng = new google.maps.LatLng(data.lat, data.lng);
                let title = data.title;

                ////console.log(MapMarkers);
                if (mapMarker != null) {
                    mapMarker.setMap(null);
                }

                clearMapMarkerClusters();

                if (!$(this).is(":checked")) {
                    console.log("in inactive marker");

                    removeMapActiveMarkerIcon(data.lat, data.lng, data.job_id);

                    var marker = getOverlayMapMarker(map,null,
                        data.lat, data.lng
                    );

                    MapMarkers[position] = marker;


                } else {
                    console.log("in active marker");
                    //var data = markers[position];
                    //var myLatlng = new google.maps.LatLng(data.lat, data.lng);


                    addMapActiveMarkerIcon(map,
                        data.lat, data.lng, data.address,
                        data.service_type_color, data.job_id
                    );


                    var marker = getOverlayMapMarker(map,null,
                        data.lat, data.lng
                    );

                    MapMarkers[position] = marker;

                }
                var tableData = table.data();
                // Additional Event Listener
                google.maps.event.addListener(marker, "click", function(e) {
                    $("#"+tableData[position].index).click();
                });

            });

            // CALCULATE total square feet
            $('.myCheckBox').change(function() {
                //console.log(".myCheckBox changed");

                var sqftTotal = 0;
                $('#unassigntbl tbody input:checked').each(function() {
                    sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
                });
                $('#totalSqFt').val(sqftTotal);
                var sqftTotal = 0;
                let applicationSqft = 0;
                let tmpAddressArray = [];
                var costTotal = 0;
                $('.myCheckBox.map').each(function() { //iterate all listed checkbox items
                    // this.checked = status; //change ".checkbox" checked status
                    if ($(this).is(':checked')) {

                        sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
                        let currentAddress = $(this).parent().parent().find('td').eq(11).text();
                        if(!tmpAddressArray.includes(currentAddress)) {
                            tmpAddressArray.push(currentAddress);
                            applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
                        }

                        let cost = $(this).data('cost');
                        if (cost)
                            costTotal = costTotal + parseFloat(cost);

                    }
                });

                $('#totalSqFt').val(sqftTotal);

                $('#applicationSqFt').val(applicationSqft);

                $('#totalApplicationCost').val(costTotal.toFixed(2));


                //uncheck "select all", if one of the listed checkbox item is unchecked
                if (this.checked == false) { //if this item is unchecked
                    $("#select_all")[0].checked = false; //change "select all" checked status to false
                }

                //check "select all" if all checkbox items are checked
                if ($('.myCheckBox:checked').length == $('.myCheckBox').length) {
                    $("#select_all")[0].checked = true; //change "select all" checked status to true
                }
            });

            console.log("<< addCheckBoxEvent");
        }

       
        //sets starting map position
        function SetMarker(position) {
            //Remove previous Marker.
            if (marker != null) {
                marker.setMap(null);
            }
            //Set Marker on Map.
            if (position) {

            } else {
                if (markers.length > 0) {
                    allunchecked();
                }
            }
        }

        //brings select all checkbox functionality
        $("#select_all").change(function() { //"select all" change 

            var status = this.checked; // "select all" checked status
            //console.log("Select all status is" + JSON.stringify(status));
            if (status) {
                $('#allMessage').prop('disabled', false);
                $('#multiple-delete-id,#multiple-restore-id,#multiple-skip-id').prop('disabled', false);
                checkAll();
            } else {
                $('#allMessage').prop('disabled', true);
                $('#multiple-delete-id,#multiple-restore-id,#multiple-skip-id').prop('disabled', true);
                unCheckAll();
            }

            var costTotal = 0;

            var sqftTotal = 0;
            $('.myCheckBox.map').each(function() { //iterate all listed checkbox items
                this.checked = status; //change ".checkbox" checked status
                if ($(this).is(':checked')) {

                    sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
                    let cost = $(this).data('cost');
                    if (cost)
                        costTotal = costTotal + parseFloat(cost);

                }
            });

            $('#totalApplicationCost').val(costTotal.toFixed(2));

            $('#totalSqFt').val(sqftTotal);

            let applicationSqft = 0;
            let tmpAddressArray = [];

            $('#unassigntbl tbody input:checked').each(function() {
                let currentAddress = $(this).parent().parent().find('td').eq(11).text();
                if(!tmpAddressArray.includes(currentAddress)) {
                    tmpAddressArray.push(currentAddress);
                    applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
                }
                //console.log(applicationSqft);
                //console.log(tmpAddressArray);
            });
            $('#applicationSqFt').val(applicationSqft);
            // //check "select all" if all checkbox items are checked
            // if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){
            //     $("#select_all")[0].checked = true; //change "select all" checked status to true
            // }
            $('.myCheckBox.map').each(function(){ //iterate all listed checkbox items
                //this.checked = status; //change ".checkbox" checked status
                var has_customer_in_hold=$(this).hasClass("customer_in_hold");
                //console.log(has_customer_in_hold);
                if(!has_customer_in_hold){
                    //console.log(this)
                    $(this).checked = status; //change ".checkbox" checked status
                }
                if ($(this).is(':checked')) {
                    // //console.log( $(this).parent().parent().find('td').eq(5).html() );
                    sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
                }
            });

        });

        //changes map marker highlights back to original
        function unCheckAll() {
            console.log("unCheckAll()");
            allchecked = false;
            var infoWindow = new google.maps.InfoWindow();

            MapMarkers.forEach(item => {
                item.setMap(null);
                item = null;
            });

            MapMarkers = [];

            for (i = 0; i < filteredMarkers.length; i++) {
                var data = filteredMarkers[i]
                var myLatlng = new google.maps.LatLng(data.lat, data.lng);

                removeMapActiveMarkerIcon(data.lat, data.lng, data.job_id);

                var marker = getOverlayMapMarker(map,i,
                    data.lat, data.lng
                );

                MapMarkers.push(marker);
            }

            // disable buttons that need checkboxes clicked
            $('#allMessage').prop('disabled', true);
            $('#multiple-delete-id').prop('disabled', true);

        }


        function allunchecked() {
            console.log("entered allunchecked");
            allchecked = false;

            var infoWindow = new google.maps.InfoWindow();
            var lat_lng = new Array();

            var latlngbounds = new google.maps.LatLngBounds();
            

            filteredMarkers = [];

            //console.log("allunchecked markers.length " + markers.length);
			//console.log(markers);

            for (i = 0; i < markers.length; i++) {
                var data = markers[i];
                
                filteredMarkers.push(data);
                var myLatlng = new google.maps.LatLng(data.lat, data.lng);

                var lancedata = JSON.parse(data.lancedata)
                var splitstring = lancedata.action.split("grd_ids='");
                var split2 = splitstring[1].split("'");
                var gudid = split2[0];

                removeMapActiveMarkerIcon(data.lat, data.lng, data.job_id);

                var marker = getOverlayMapMarker(map,i,
                    data.lat, data.lng
                );

                latlngbounds.extend(marker.position);

                MapMarkers.push(marker);
            }
           
            map.fitBounds(latlngbounds);
        }

        function checkAll() {
            allchecked = true;
            var infoWindow = new google.maps.InfoWindow();

           
            MapMarkers.forEach(item => {
                item.setMap(null);
                item = null;
            });

            MapMarkers = [];

            const image = "http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-poi.png";

            for (i = 0; i < filteredMarkers.length; i++) {
                var data = filteredMarkers[i];
                var myLatlng = new google.maps.LatLng(data.lat, data.lng);

                addMapActiveMarkerIcon(map,
                    data.lat, data.lng, data.address,
                    data.service_type_color, data.job_id
                );

                var marker = getOverlayMapMarker(map,i,
                    data.lat, data.lng
                );

                MapMarkers.push(marker);

            }
        }

        $('#unassigntbl').DataTable().state.clear();
        $('#unassigntbl').DataTable().destroy();
        $('#unassigntbl').DataTable().search('').columns().search('').draw();
        
		
		LoadMap();
        
        
        $(document).on('click', '.myCheckBox', function() {
            //console.log("checkbox document on click");
            addCheckBoxEvent();
        });

        //render the table view on page load
        if (!$('input[name=changeview]').is(':checked')) {
            
            //console.log("False etay");

            $('#tablediv').css('display', 'block');
            $('#mapdiv').css('display', 'block');

            //  $("#mapdiv").removeClass('col-md-4');
            //  $("#mapdiv").addClass('col-md-12');
            //  $("#tablediv").removeClass('col-md-8');

            // $("#tablediv").addClass('col-md-8');
           

            //show the red update map button on left side
            // if (!$("#update-map-note").length > 0) {
            //     $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-primary ml-5 btn btn-danger unassigned-services-element'text-semibold' style='font-size:18px;color:#FFFFFF;background-color:#F44336;'>Please click here to update the map</button");
            // }


        } else {
            //console.log("Beya");
           
            $('#mapdiv').css('display', 'none');
            $('#tablediv').css('display', 'block');
            $("#tablediv").removeClass('col-md-8');
            $("#tablediv").addClass('col-md-12');

            $("#update-map-note").remove();

        }

        //map is in beta note
        //$(".qun").append('<span class="chtn" style="padding-left: 100px; padding-right:400px;"><a href="" data-toggle="modal" data-target="#help_message" >This map feature is currently in beta. Please send us your feedback here</a></span>');
		
        // google.maps.event.trigger(map, 'dragend');

        // $('#unassigntbl').DataTable().state.clear();
        // $('#unassigntbl').DataTable().destroy();
        // $('#unassigntbl').DataTable().search('').columns().search('').draw();\notifications.html


        // //setTimeout(() => {
        
         google.maps.event.trigger(map, 'dragend');
        //  
                   

        if (sessionStorage) {
            sessionStorage.clear();
        }
        
        sessionStorage.setItem("serv_name_input", "");
        sessionStorage.removeItem("serv_name_input");


        /**
         * This function returns the definition of a
         * google map marker for an ACTIVE property.
         */
        function __getActiveMapMarker(map,index,latitude,longitude,title,markerColor='#c9c9c9')
        {
            const image = "http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-poi.png";

            var svgActivePinMarker = {
                // with middle circle
                //path: 'M12,2L12,2C8.13,2,5,5.13,5,9c0,1.74,0.5,3.37,1.41,4.84c0.95,1.54,2.2,2.86,3.16,4.4c0.47,0.75,0.81,1.45,1.17,2.26 C11,21.05,11.21,22,12,22h0c0.79,0,1-0.95,1.25-1.5c0.37-0.81,0.7-1.51,1.17-2.26c0.96-1.53,2.21-2.85,3.16-4.4 C18.5,12.37,19,10.74,19,9C19,5.13,15.87,2,12,2z M12,11.75c-1.38,0-2.5-1.12-2.5-2.5s1.12-2.5,2.5-2.5s2.5,1.12,2.5,2.5 S13.38,11.75,12,11.75z',
                // without middle circle
                path: 'M 12 2 L 12 2 C 8.13 2 5 5.13 5 9 c 0 1.74 0.5 3.37 1.41 4.84 c 0.95 1.54 2.2 2.86 3.16 4.4 c 0.47 0.75 0.81 1.45 1.17 2.26 C 11 21.05 11.21 22 12 22 h 0 c 0.79 0 1 -0.95 1.25 -1.5 c 0.37 -0.81 0.7 -1.51 1.17 -2.26 c 0.96 -1.53 2.21 -2.85 3.16 -4.4 C 18.5 12.37 19 10.74 19 9 C 19 5.13 15.87 2 12 2 z z',

                strokeColor: 'black',  // highlight
                strokeWeight: 1.2,

                fillColor: markerColor,
                fillOpacity: 0, // no fill colour
                scale: 1.5,

                origin: new google.maps.Point(0,0),
                anchor: new google.maps.Point(12, 24),

                labelOrigin: new google.maps.Point(10,10),
            };

            return new google.maps.Marker({
                icon: svgActivePinMarker,
                position: new google.maps.LatLng(latitude,longitude),
                map: map,
                title: title,
                index: index,
            });
        }

        /**
         * PRIVATE FUNCTION
         * This function returns the definition of a
         * google map marker for an IN-ACTIVE property.
         *
         * default marker color #c9c9c9
         */
        function __getInactiveMapMarker(map,index,latitude,longitude,title,markerColor='#c9c9c9')
        {
            var svgInactivePinMarker = {
                path: 'M12,2L12,2C8.13,2,5,5.13,5,9c0,1.74,0.5,3.37,1.41,4.84c0.95,1.54,2.2,2.86,3.16,4.4c0.47,0.75,0.81,1.45,1.17,2.26 C11,21.05,11.21,22,12,22h0c0.79,0,1-0.95,1.25-1.5c0.37-0.81,0.7-1.51,1.17-2.26c0.96-1.53,2.21-2.85,3.16-4.4 C18.5,12.37,19,10.74,19,9C19,5.13,15.87,2,12,2z M12,11.75c-1.38,0-2.5-1.12-2.5-2.5s1.12-2.5,2.5-2.5s2.5,1.12,2.5,2.5 S13.38,11.75,12,11.75z',

                strokeColor: 'black',  // highlight
                strokeWeight: 0.0,

                fillColor: markerColor,
                fillOpacity: 1,
                scale: 1.5,

                origin: new google.maps.Point(0,0),
                anchor: new google.maps.Point(12, 24),

                labelOrigin: new google.maps.Point(10,10),
            };

            return new google.maps.Marker({
                icon: svgInactivePinMarker,
                position: new google.maps.LatLng(latitude,longitude),
                map: map,
                title: title,
                index: index
            });
        }

        /**
         * PRIVATE FUNCTION
         * This function returns the definition dot circle
         */
        function __getDotCircleMapMarker(map,latitude,longitude,dotColour,dotScale)
        {
            var svgSmallCircleMarker = {
                path: 'M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Z',
                fillColor: dotColour,
                fillOpacity: 1,
                scale: dotScale,
                strokeColor: 'white',
                strokeWeight: 0.5,
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(12, 12),
            };

            return new google.maps.Marker({
                icon: svgSmallCircleMarker,
                position: new google.maps.LatLng(latitude, longitude),
                map: map,
            });
        }

        /**
         * PUBLIC
         * This function returns an overlay marker
         * This marker is used to act as a click on ALL elements.
         */
        function getOverlayMapMarker(map,index,latitude,longitude)
        {
            var svgOverlayPinMarker = {
                path: 'M12,2L12,2C8.13,2,5,5.13,5,9c0,1.74,0.5,3.37,1.41,4.84c0.95,1.54,2.2,2.86,3.16,4.4c0.47,0.75,0.81,1.45,1.17,2.26 C11,21.05,11.21,22,12,22h0c0.79,0,1-0.95,1.25-1.5c0.37-0.81,0.7-1.51,1.17-2.26c0.96-1.53,2.21-2.85,3.16-4.4 C18.5,12.37,19,10.74,19,9C19,5.13,15.87,2,12,2z M12,11.75c-1.38,0-2.5-1.12-2.5-2.5s1.12-2.5,2.5-2.5s2.5,1.12,2.5,2.5 S13.38,11.75,12,11.75z',

                strokeColor: 'black',  // highlight
                strokeWeight: 0.0,

                fillColor: 'yellow',
                fillOpacity: 0.1,
                scale: 1.5,

                origin: new google.maps.Point(0,0),
                anchor: new google.maps.Point(12, 24),

            };

            return new google.maps.Marker({
                icon: svgOverlayPinMarker,
                position: new google.maps.LatLng(latitude,longitude),
                map: map,
                index: index
            });
        }

        /**
         * NewCustomer (NewSale)
         */
        function __getNewCustomerMapMarkerIcon(map,latitude,longitude)
        {
            const symbolNewCustomer = {
                path: 'M451 863h55v-52q61-7 95-37.5t34-81.5q0-51-29-83t-98-61q-58-24-84-43t-26-51q0-31 22.5-49t61.5-18q30 0 52 14t37 42l48-23q-17-35-45-55t-66-24v-51h-55v51q-51 7-80.5 37.5T343 454q0 49 30 78t90 54q67 28 92 50.5t25 55.5q0 32-26.5 51.5T487 763q-39 0-69.5-22T375 681l-51 17q21 46 51.5 72.5T451 809v54Zm29 113q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 31.5-156t86-127Q252 239 325 207.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 82-31.5 155T763 858.5q-54 54.5-127 86T480 976Z',
                fillColor: 'green',
                fillOpacity: 1,
                strokeColor: 'white',
                strokeWeight: 0.2,
                scale: 0.015,
                anchor: new google.maps.Point(1400, 2500)
            }

            return new google.maps.Marker({
                icon: symbolNewCustomer,
                position: new google.maps.LatLng(latitude,longitude),
                map: map,
            });
        }

        /**
         * PastDue
         * service_due == "Overdue"
         */
        function __getPastDueMapMarkerIcon(map,latitude,longitude)
        {
            const symbolPastDue = {
                path: 'M479 974q-74 0-139.5-28t-114-77q-48.5-49-77-114T120 615q0-74 28.5-139.5t77-114.5q48.5-49 114-77T479 256q74 0 139.5 28T733 361q49 49 77 114.5T838 615q0 75-28 140t-77 114q-49 49-114.5 77T479 974Zm121-196 42-42-130-130V416h-60v214l148 148ZM214 189l42 42L92 389l-42-42 164-158Zm530 0 164 158-42 42-164-158 42-42Z',
                fillColor: 'yellow',
                fillOpacity: 1,
                strokeColor: 'black',
                strokeWeight: 0.2,
                scale: 0.015,
                anchor: new google.maps.Point(-200, 2500)
            }

            return new google.maps.Marker({
                icon: symbolPastDue,
                position: new google.maps.LatLng(latitude,longitude),
                map: map,
            });
        }

        /**
         * PRIVATE PreNotified (CallAhead)
         */
        function __getPreNotifiedMapMarkerIcon(map,latitude,longitude)
        {
            const symbolPreNotified = {
                path: 'm18.75,36.99C8.67,36.99.5,28.82.5,18.75S8.67.5,18.75.5s18.25,8.17,18.25,18.25-8.17,18.25-18.25,18.25Zm3.36-26.67l-1.47.51c-5.22,1.89-8.9,10.69-7.39,15.2l.51,1.47c.14.4.58.65,1.02.49l2.97-1.07c.4-.14.63-.62.49-1.02l-1.05-3.29-2.42-.94c-.36-1.03,2.38-6.65,3.44-7.06l2.36,1.28,3.11-1.17c.4-.14.63-.62.49-1.02l-1.04-2.89c-.15-.44-.58-.65-1.02-.49Z',
                fillColor: 'blue',
                fillOpacity: 1,
                strokeColor: 'white',
                strokeWeight: 0.4,
                scale: 0.3,
                anchor: new google.maps.Point(23,132)
            }

            return new google.maps.Marker({
                icon: symbolPreNotified,
                position: new google.maps.LatLng(latitude,longitude),
                map: map,
            });
        }

        /**
         * PRIVATE ASAP
         */
        function __getAsapMapMarkerIcon(map,latitude,longitude)
        {
            const symbolAsap = {
                path: 'M479.982 776q14.018 0 23.518-9.482 9.5-9.483 9.5-23.5 0-14.018-9.482-23.518-9.483-9.5-23.5-9.5-14.018 0-23.518 9.482-9.5 9.483-9.5 23.5 0 14.018 9.482 23.518 9.483 9.5 23.5 9.5ZM453 623h60V370h-60v253Zm27.266 353q-82.734 0-155.5-31.5t-127.266-86q-54.5-54.5-86-127.341Q80 658.319 80 575.5q0-82.819 31.5-155.659Q143 347 197.5 293t127.341-85.5Q397.681 176 480.5 176q82.819 0 155.659 31.5Q709 239 763 293t85.5 127Q880 493 880 575.734q0 82.734-31.5 155.5T763 858.316q-54 54.316-127 86Q563 976 480.266 976Z',
                fillColor: 'red',
                fillOpacity: 1,
                strokeColor: 'white',
                strokeWeight: 0.2,
                scale: 0.015,
                anchor: new google.maps.Point(-200, 2500)
            }

            return new google.maps.Marker({
                icon: symbolAsap,
                position: new google.maps.LatLng(latitude,longitude),
                map: map,
            });
        }

        /**
         *  PRIVATE Days Since
         */
        function __daysSince(map,latitude,longitude,numberValue=' ')
        {
            const daysSinceNumberBackground = {
                path: 'M480 976q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 31.5-156t86-127Q252 239 325 207.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 82-31.5 155T763 858.5q-54 54.5-127 86T480 976Z',
                fillColor: 'white',
                fillOpacity: 1,
                strokeColor: 'black',
                strokeWeight: 0.2,
                scale: 0.02,
                anchor: new google.maps.Point(500, 1600),
                labelOrigin: new google.maps.Point(450,530),
            }
            //console.log("number value = "+numberValue);
            return new google.maps.Marker({
                icon: daysSinceNumberBackground,
                position: new google.maps.LatLng(latitude,longitude),
                map: map,

                label: {
                    text: numberValue,
                    color: 'black',
                    fontSize: "8.5px",
                },
            });
        }

        /**
         *  PRIVATE
         */
        function __dateDaysDifference(dateValue)
        {
            var diffInDays = NaN;
            var daysValue = ' ';

            if(!dateValue){return daysValue;}

            var lastProgramServiceDate = new Date(dateValue);

            if (isNaN(lastProgramServiceDate)) {
                return daysValue;
            }

            var today = new Date();
            const diffInMs = today - lastProgramServiceDate;
            diffInDays = Math.ceil( diffInMs / (1000 * 60 * 60 * 24) );

            // convert to string, or return blank string.
            if(diffInDays){ daysValue = ''+diffInDays.toString();}

            return daysValue;
        }

        /**
         * PUBLIC
         */
        function addMapInfoIcons(data)
        {
            if(data.newcustomer==1) {
                var markerNewCustomer = __getNewCustomerMapMarkerIcon(map,
                    data.property_latitude, data.property_longitude);
                MapInfoIcons.push(markerNewCustomer);
            }
            if(data.pastdue==1) {
                var markerPastDue = __getPastDueMapMarkerIcon(map,
                    data.property_latitude, data.property_longitude);
                MapInfoIcons.push(markerPastDue);
            }

            if(data.prenotified==1) {
                var markerPreNotified = __getPreNotifiedMapMarkerIcon(map,
                    data.property_latitude, data.property_longitude);
                MapInfoIcons.push(markerPreNotified);
            }

            if(data.asap==1) {
                var markerAsap = __getAsapMapMarkerIcon(map,
                    data.property_latitude, data.property_longitude);
                MapInfoIcons.push(markerAsap);
            }

            //daysValue = dateDaysDifference(data.last_program_service_date);
            daysValue  = ' ';
            daysValue1 = __dateDaysDifference(data.completed_date_last_service_by_type);
            //daysValue2 = __dateDaysDifference(data.last_service_date);
            //daysValue2 = __dateDaysDifference(data.last_program_service_date);
            daysValue2 = __dateDaysDifference(data.property_program_date_value);

            daysValue=daysValue1;
            if(daysValue1==' ')
            {daysValue=daysValue2;}

            var markerDaysSince = __daysSince(map,
                data.property_latitude, data.property_longitude,
                daysValue
            );

            MapInfoIcons.push(markerDaysSince);

        }


        /**
         * PUBLIC
         */
        function clearMapInfoIcons()
        {
            MapInfoIcons.forEach(item => {
                item.setMap(null);
                item.setVisible(false);
                item = null
            })
            MapInfoIcons=[]
        }

        /**
         * PUBLIC
         */
        var propertyArray=[];
        var MapMarkerDotIcons=[];
        function addMapInactiveMarkerIcon(map,latitude,longitude,title,markerColor)
        {
            console.log("addMapInactiveMarkerIcon() >>");

            if(propertyArray.hasOwnProperty(title))
            {
                var result = propertyArray[title];
                propertyArray[title] = result+1;

                var dotColour=markerColor;
                var dotScale=0.6;

                if(propertyArray[title] ==2){
                    //dotColour='red';
                    dotScale=0.6;
                }

                if(propertyArray[title] == 3){
                    //dotColour='green';
                    dotScale=0.4;
                }

                if(propertyArray[title] == 4){
                    //dotColour='blue';
                    dotScale=0.2;
                }

                if(propertyArray[title] > 4){
                    return null;
                }

                var dot = __getDotCircleMapMarker(map,latitude,longitude,dotColour,dotScale);
                MapMarkerDotIcons.push(dot);

            }else {
                propertyArray[title] = 1;

                // normal execution
                var markerImage = __getInactiveMapMarker(map, null,
                    latitude,
                    longitude,
                    title,
                    markerColor,
                );
                MapMarkerIcons.push(markerImage);

            }
        }

        /**
         * PUBLIC
         */
        function addMapActiveMarkerIcon(map,latitude,longitude,title,markerColor,jobid)
        {
            var existingMarkerArray = MapMarkerIconsAddressArray[latitude+longitude];

            if(existingMarkerArray==undefined)
            {
                var existingMarker = undefined;
                existingMarkerArray=[];
            }
            else {
                var existingMarker = existingMarkerArray[jobid]
            }


            if(existingMarker==undefined)
            {
                var markerImage = __getActiveMapMarker(map,null,
                    latitude,
                    longitude,
                    title,
                    markerColor
                );
                existingMarkerArray[jobid] = markerImage;
            }
            else
            {
                var markerImage = existingMarkerArray[jobid];
            }

            MapMarkerIcons.push(markerImage);
            MapMarkerIconsAddressArray[latitude+longitude]=existingMarkerArray;

        }

        function removeMapActiveMarkerIcon(latitude,longitude,jobid)
        {
            console.log("removeMapActiveMarkerIcon >>");

            var existingMarkerArray = MapMarkerIconsAddressArray[latitude+longitude]

            if(existingMarkerArray==undefined)
            {}
            else
            {
                for (let index = 0; index < existingMarkerArray.length; ++index) {
                    const item = existingMarkerArray[index];

                    if(item) {
                        item.setMap(null);
                        item.setVisible(false);
                        //item = null;
                    }

                }

                MapMarkerIconsAddressArray[latitude+longitude] = undefined;
            }

            console.log("<< removeMapActiveMarkerIcon");
        }


        function removeAllMapActiveMarkerIcon()
        {
            for (var key in MapMarkerIconsAddressArray)
            {
                console.log(MapMarkerIconsAddressArray[key]);
                var existingMarkerArray = MapMarkerIconsAddressArray[key];

                for (var key1 in existingMarkerArray)
                {
                    var item = existingMarkerArray[key1];

                    if (item) {
                        item.setMap(null);
                        item.setVisible(false);
                        //item = null;
                    }
                }
            }

            MapMarkerIconsAddressArray=[];
        }


        /**
         * PUBLIC
         */
        function clearMapMarkerIcons()
        {
            MapMarkerIcons.forEach(item => {
                item.setMap(null);
                item.setVisible(false);
                item = null
            })
            MapMarkerIcons=[];

            // Also, clear dot icons
            MapMarkerDotIcons.forEach(item => {
                item.setMap(null);
                item.setVisible(false);
                item = null
            })

            MapMarkerDotIcons=[];

            // reset property array
            propertyArray=[];
        }

        /**
         * PUBLIC
         */
        var MapMarkerCluster=null;
        function addMapMarkerClusters(map,mapMarkers)
        {
            // COMMENTED OUT // TURNED OFF
            /*
            MapMarkerCluster = new markerClusterer.MarkerClusterer({ map, markers: mapMarkers,
                algorithmOptions: {
                    maxZoom: 10,
                }
            });
            */
        }

        function clearMapMarkerClusters()
        {
            try {
                MapMarkerCluster.clearMarkers();
            }
            catch(err){}

            MapMarkerCluster=null;
        }

	});



   

    $('.primary-assign').click(function() {

        if ($(this).val() == 1) {
            $('#route_input').css('display', 'none');
            $('#route_select').css('display', 'block');
        } else if ($(this).val() == 2) {
            $('#route_input').css('display', 'block');
            $('#route_select').css('display', 'none');
        }

    });


    $(document).on("click", "#changespecifictime", function(e) {
        if ($(this).prop("checked") == true) {
            $('#specific_time_input').css('display', 'block');
        } else if ($(this).prop("checked") == false) {
            $('#specific_time_input').css('display', 'none');
        }

    });

    
</script>

<script language="javascript" type="text/javascript">

</script>
<!-- /////  for multiple delete  -->
<script type="text/javascript">
    $('#allMessage').click(function() { //iterate all listed checkbox items    

        var numberOfChecked = $('input:checkbox[name=group_id]:checked').length;
        if (numberOfChecked == 1) {
            $('.specificTimeDivision').html('<div class="row"><div class="col-sm-6"><label>Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" class="form-control styled" name="specific_time_check" value="1" id="changespecifictime" ></label><div id="specific_time_input" style="display:none;" ><div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" readonly name="specific_time" placeholder="Specific Time"  >        <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div></div></div></div>');

            reassignCheckboxAnTimePicker();


        } else {
            $('.specificTimeDivision').html('');

        }

        // insert group id
        // var group_id_new = [];
        var group_id_new = '';
        var all_checked_boxes = $('input:checkbox[name=group_id]:checked');
        for (let i = 0; i < all_checked_boxes.length; i++) {
            var a_checked_box_val = all_checked_boxes[i].getAttribute('data-realvalue');
            // group_id_new.push($(this).val());
            if (i == 0) {
                group_id_new = a_checked_box_val;
            } else {
                group_id_new += ',' + a_checked_box_val;
            }
        }

        $("#group_id").val(group_id_new);
        $("#group_id_new").val(group_id_new);

    });

    // for assign job
    $("#technician_id").change(function() {
        technician_id = $(this).val();
        jobAssignDate = $('#jobAssignDate').val();
        route_select_id = 'route_select';
        routeMange(technician_id, jobAssignDate, route_select_id);
    });

    // for assign job
    $( "#create-new-route" ).click(function() {
        technician_id = $( "#technician_id").val();
        jobAssignDate =$('#jobAssignDate').val();
        route_select_id = 'route_select';
        routeMangeNewRoute(technician_id,jobAssignDate,route_select_id);
    });

    $("#jobAssignDate").change(function() {

        $("#technician_id").trigger("change");
    });


    //  for multiple edit assign job
    function routeMange(technician_id, jobAssignDate, route_select_id, selected_id = '') {
        $('#' + route_select_id).html('');
        if (technician_id != '' && jobAssignDate != '') {
            Swal.fire({
                title: 'Please Wait !',
                html: "Checking technician's active routes...",// add html attribute if you want or remove
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            });
            $.ajax({
                type: "POST",
                url: "<?= base_url('admin/getTexhnicianRoute') ?>",
                data: {
                    technician_id: technician_id,
                    job_assign_date: jobAssignDate
                },
                dataType: "json",
            }).done(function(data) {
                if (data.length === 0) {
                    $('#' + route_select_id).append('<option value="">No route found</option>');
                    $('#mileage').hide();
                } else {
                    $.each(data, function(index, value) {
                        if (value.route_id == selected_id) {
                            selected = 'selected';
                        } else {
                            selected = '';
                        }
                        let locations = '';
                        for(i = 0; i < value.locations.length; i++)
                        {
                            locations += value.locations[i].property_address+':'+value.locations[i].property_latitude+':'+value.locations[i].property_longitude+';';
                        }
                        $('#'+route_select_id).append('<option data-row-locations="'+locations+'" value="'+value.route_id+'" '+selected+' >'+value.route_name+'</option>');
                        $('#mileage').show();
                    });
                }
                Swal.close();
            });
        }
    }

    function routeMangeNewRoute(technician_id,jobAssignDate,route_select_id)
    {
        $('#' + route_select_id).html('');
        if (technician_id != '' && jobAssignDate != '') {
            $('#mileage').show();
        }
    }

    // $('.clockpicker').clockpicker();
    hljs.configure({
        tabReplace: '    '
    });
    hljs.initHighlightingOnLoad();
</script>
<!-- /////  -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/clock/js/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/clock/js/highlight.min.js"></script>
