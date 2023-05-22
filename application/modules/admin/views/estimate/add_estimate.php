<style type="text/css">
    #invoicetablediv {
        /* padding: 20px; */
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

    th, td {
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


    @media (min-width: 769px) {
        .form-horizontal .control-label[class*=col-sm-] {
            padding-top: 0;
        }
    }

</style>


<!-- Content area -->
<div class="content form-pg ">
    <div id="loading">
        <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"/> <!-- Loading Image -->
    </div>
    <!-- Form horizontal -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <div class="form-group">
                    <a href="<?= base_url('admin/Estimates') ?>" id="save" class="btn btn-success"><i
                                class=" icon-arrow-left7"> </i> Back to All Estimates</a>

                    <a href="<?= base_url('admin/addCustomer') ?>" id="save" class="btn btn-primary"><i
                                class="fa fa-plus"> </i>Add Customer</a>

                    <a href="<?= base_url('admin/addProperty') ?>" id="save" class="btn btn-primary"><i
                                class="fa fa-plus"> </i>Add Property</a>

                </div>
            </h5>
        </div>
        <br>
        <div class="panel-body">
            <b><?php if ($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

            <div id="invoicetablediv">
                <div class="table-responsive table-spraye dash-tbl" style="min-height:auto">
                    <h3>Property List</h3>
                    <table id="search-inputs" class="table">
                        <tr>
                            <td>
                                <input type="text" class="column_filter form-control" id="col_7_filter" data-column="7"
                                       placeholder="Zip Code" size="5">
                            </td>
                            <td>
                                <input type="text" class="column_filter form-control" id="col_8_filter" data-column="8"
                                       placeholder="City">
                            </td>
                            <td>
                                <input type="text" class="column_filter form-control" id="col_5_filter" data-column="5"
                                       placeholder="Property Type">
                            </td>
                            <!-- <td>
                              <input type="text" class="column_filter form-control" id="col_9_filter" data-column="9" placeholder="Assigned Program">
                            </td> -->
                            <td>
                                <input type="text" class="column_filter form-control" id="col_4_filter" data-column="4"
                                       placeholder="Service Area">
                            </td>
                            <td>
                                <input type="text" class="column_filter form-control" id="col_3_filter" data-column="3"
                                       placeholder="Customer Name">
                            </td>
                            <td>
                                <input type="text" class="column_filter form-control" id="col_9_filter" data-column="9"
                                       placeholder="Property Status">
                            </td>
                        </tr>
                    </table>

                    <table id="dataTable_propList" class="table datatable-filter-custom">
                        <thead>
                        <tr>
                            <!-- <th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th> -->
                            <th><input type="checkbox" name="row_select_all" id="row_select_all"></th>
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
                            <th id="col_property_source" style='display: none;'>Property Source</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($propertylist)) {
                            foreach ($propertylist as $p) {
                                ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="property_id" value="<?= $p->property_id ?>"
                                               <?= $customer_id_chosen === $p->customer_id ? 'checked' : '' ?>
                                               class="row_select"/>
                                    </td>
                                    <td>
                                        <?= $p->property_title ?>
                                    </td>
                                    <td>
                                        <?= $p->property_address ?>
                                    </td>
                                    <td>
                                        <?= $p->last_name . ", " . $p->first_name ?>
                                    </td>
                                    <td>
                                        <?= $p->category_area_name ?? 'N/A' ?>
                                    </td>
                                    <td>
                                        <?= $p->property_type ?>
                                    </td>
                                    <td>
                                        <input name="price_override" type="checkbox" value="<?= $p->property_id ?>"
                                               class="price_override" disabled/>
                                    </td>
                                    <td>
                                        <?= $p->property_zip ?>
                                    </td>
                                    <td>
                                        <?= $p->property_city ?>
                                    </td>
                                    <td>
                                        <?= $p->property_status ?>
                                    </td>
                                    <td>
                                        <?= $p->customer_id ?>
                                    </td>
                                    <td>
                                        <?= $p->email ?? NULL ?>
                                    </td>
                                    <td style='display: none;'>
                                        <input type='hidden' value="<?= $p->source ?>"
                                               id="source_<?= $p->property_id ?>"/>
                                    </td>
                                </tr>
                            <?php }
                        } else {
                            echo 'No results found...';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <form id="submit_form" action="<?= base_url('admin/Estimates/addBulkEstimateData') ?>" method="post" name=""
                  enctype="multipart/form-data">
                <div class="row invoice-form">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Estimate Date</label>
                            <div class="col-lg-9">
                                <input id="estimate_date" type="text" name="estimate_date"
                                       class="form-control pickaalldate" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Apply Coupons</label>
                            <div class="multi-select-full col-lg-9" style="">
                                <select class="multiselect-select-all-filtering form-control"
                                        name="assign_onetime_coupons[]" id="" multiple="multiple">
                                    <?php foreach ($customer_one_time_discounts as $value): ?>
                                        <?php
                                        // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                                        $expiration_pass = true;
                                        if ($value->expiration_date != "0000-00-00 00:00:00") {
                                            $coupon_expiration_date = strtotime($value->expiration_date);

                                            $now = time();
                                            if ($coupon_expiration_date < $now) {
                                                $expiration_pass = false;
                                                $expiration_pass_global = false;
                                            }
                                        }

                                        if ($expiration_pass == true) { ?>
                                             
                                            <option value="<?= $value->coupon_id ?>">
                                                <?= $value->code ?>
                                            </option>
                                        <?php } ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row invoice-form">
                    <!-- <input type="text" name="property_id_array[]" id="property_id_array" style="display: none;" > -->
                    <textarea name="property_id_array" id="property_id_array" style="display: none;">[]</textarea>
                    <textarea name="property_data_array" id="property_data_array" style="display: none;">[]</textarea>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Pricing</label>
                            <div class="col-lg-9">
                                <select class="form-control" name="program_price" id="program_price" required disabled>
                                    <option value=1>One Time Project Invoicing</option>
                                    <option value=2>Invoiced at Job Completion</option>
                                    <option value=3>Manual Billing</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Select Program(s)</label>
                            <div class="multi-select-full col-lg-9">
                                <select class="multiselect-select-all-filtering form-control" multiple="multiple"
                                        name="program_id_array[]" id="program_list">
                                    <?php
                                    if (!empty($program_details)) {
                                        foreach ($program_details as $value) { ?>
                                            <!--  <option value="$value->product_id"> $value->product_name</option> -->

                                            <option value="<?= $value->program_id ?>"
                                                    <?php if (in_array($value->program_id, $selectedprogramlist)) { ?>selected <?php } ?>
                                                      > <?= $value->program_name ?> </option>

                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- <textarea   name="joblistarray" id="assign_job_ids2" style="display: none;" >[]</textarea> -->
                        </div>
                    </div>
                </div>

                <div class="row invoice-form">
                    <div class="col-md-6">
                        <!-- <div class="form-group">
                          <label class="control-label col-lg-3">Property Status</label>
                          <div class="col-lg-9">
                            <select class="form-control" name="property_status" id="property_status" >
                              <option value="">Select Any Status</option>
                              <option value="2">Prospect</option>
                              <option value="1">Active</option>
                            </select>
                          </div>
                        </div> -->
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Sales Rep</label>
                            <div class="multi-select-full col-lg-9  col-sm-10 col-xs-10">
                                <select class=" form-control" name="sales_rep" id="sales_rep"
                                        value="<?php echo set_value('sales_rep') ?>">
                                    <option value="">Select Sales Rep</option>
                                    <?php
                                    foreach ($users as $value) :

                                        ?>
                                        <option value="<?= $value->id ?>" <?php echo $this->session->userdata['id'] == $value->id ? 'selected' : '' ?>><?= $value->user_first_name . ' ' . $value->user_last_name ?></option>
                                    <?php

                                    endforeach
                                    ?>
                                </select>
                                <span style="color:red;"><?php echo form_error('sales_rep'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row invoice-form">
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id='source_select_row_id'>
                            <label class="control-label col-lg-3">Source</label>
                            <div class="multi-select-full col-lg-9">
                                <select class="form-control" name="source" id="select_source">
                                    <option value="">Select Source</option>
                                    <?php foreach ($source_list as $value) : ?>
                                        <option value="<?= $value->source_id ?>"><?= $value->source_name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row invoice-form">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Customer Message<br>(Included in Estimate PDF)</label>
                            <div class="col-lg-9">
                                <textarea id="notes" type="text" class="form-control" name="notes"
                                          placeholder="Enter Notes" rows="5"></textarea>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Select Service(s)</label>
                            <div class="multi-select-full col-lg-9">
                                <select class="multiselect-select-all-filtering" multiple="multiple"
                                        name="standalone_job_ids[]" id="job_list">
                                    <?php
                                    if (!empty($service_details)) {
                                        foreach ($service_details as $value) { ?>
                                            <!--  <option value="$value->product_id"> $value->product_name</option> -->

                                            <option value="<?= $value->job_id ?>"
                                                    <?php if (in_array($value->job_id, $selectedjoblist)) { ?>selected <?php } ?>
                                                      > <?= $value->job_name ?> </option>

                                        <?php }
                                    }

                                    ?>

                                </select>
                            </div>
                        </div>

                        <!-- <div class="form-group">
                          <label class="control-label col-lg-3">Service(s) Pricing</label>
                          <div class="col-lg-9">
                            <select class="form-control" name="program_price" id="program_price">
                              <option value="">Select Any Pricing</option>
                              <option value=1>One-Time Service Invoicing</option>
                              <option value=2>Invoiced at Service Completion</option>
                              <option value=3>Manual Billing</option>
                            </select>
                          </div>
                        </div>             -->

                        <div class="form-group">
                            <div class="selected-wrap">
                                <label for="selected-items">Selected Program(s)/Service(s)</label>
                                <div class="center-block form-border box-space">
                                    <ul id="selected-items" class="list-inline">

                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <input id="email_notes" type="hidden" class="form-control" name="email_notes" value="start"></input>

                <div id="override_container" class="row invoice-form hidden">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Price Override</label>

                        <table id="dataTable_override" class="table table-bordered table-condensed table-ellipsis">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Prog_ID</th>
                                <th>Property Title</th>
                                <th>Property Address</th>
                                <th>Customer Name</th>
                                <th>Type(s)</th>
                                <th>Program(s)/Service(s) Name</th>
                                <th>Price Override</th>
                                <!-- <th>Price Override Value</th>
                                <th>Is Price Override Set</th> -->
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <textarea name="listarray" id="listarray" style="display: none;">[]</textarea>
                <textarea name="priceoverridearray" id="priceoverridearray" style="display: none;"></textarea>
                <div class="row">
                    <input name="status" id="status" style="display: none;">
                    <input name="signwell_status" id="signwell_status" style="display: none;">
                    <div class="form-group col-lg-6">
                        <!-- leaving empty to help with spacing -->
                    </div>
                    <div class="form-group col-lg-6">
                        <div class="row">
                            <div class="col-lg-4 ">
                                <button type="submit" class="btn btn-success" id="save_draft">Save as Draft <i
                                            class="icon-arrow-right14 position-right"></i></button>
                            </div>
                            <div class="col-lg-4 ">
                                <button type="" class="btn btn-success" id="submit_estimate">Submit & Send<i
                                            class="icon-arrow-right14 position-right"></i></button>
                            </div>
                            <?php if ($setting_details->signwell_api_key != "") { ?>
                                <div class="col-lg-4">
                                    <button type="" class="btn btn-success btn-outline" id="submit_estimate_signwell">
                                        Submit & Send to SignWell<i class="icon-arrow-right14 position-right"></i>
                                    </button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /form horizontal -->

<!-- /content area -->


<script>
    /** Global for debugging purpose ONLY **/
    let propTable, overrideTable;
    let programs, services, selectedAll;
    let overrideData = [];
    let propertyDataArray = [];
    let listData = {programs: [], services: []};

    /** Global END **/





    function filterColumn(i) {
        let val = $('#col_' + i + '_filter').val();
        $('#dataTable_propList').DataTable().column(i).search(
            $('#col_' + i + '_filter').val()
        ).draw();
    }

    function priceOverrideRedraw() {
        let indexes = Array.from(propTable.rows('.selected.override').indexes());

        let propIds = [];
        indexes.forEach((i) => {
            let rowEl = propTable.row(i).node();
            let checkVal = rowEl.children[0].children[0].value;
            propIds.push(checkVal);
        });

        let allData = Array.from(propTable.rows('.selected.override').data());


        overrideData = [];
        let programsAndServices = Array.from($('#selected-items').children());
        if (programsAndServices.length < 1) {
            allData.forEach((row, i) => {
                let tmp = [
                    propIds[i],
                    '',
                    row[1],
                    row[2],
                    row[3],
                    '',
                    '',
                    ''
                ];
                overrideData.push(tmp);
            });

        } else {

            let psSorted = [];
            let selectedPrograms = Array.from($('li.selected-program'));
            let selectedServices = Array.from($('li.selected-service'));
            if (selectedPrograms.length > 0) {

                selectedPrograms.forEach((p, i1) => {
                    let tmp = {};
                    tmp.program_id = $(p).data('programId');
                    tmp.program_name = $(p).text();
                    tmp.program_jobs = [];
                    for (let extCount = 0; extCount < programsExtended.length; extCount++) {
                        if (programsExtended[extCount].program_id == tmp.program_id) {
                            tmp.program_jobs = [...programsExtended[extCount].program_jobs];
                            break;
                        }
                    }
                    tmp.service_ids = [];
                    tmp.service_names = [];
                    let pjbs = (tmp.program_jobs.length > 0) ? tmp.program_jobs.map(job => job.job_id) : [];
                    selectedServices.forEach((s, i2) => {

                        if (!(pjbs.includes($(s).data('serviceId').toString()))) {
                            tmp.service_ids.push($(s).data('serviceId'));
                            tmp.service_names.push($(s).text());
                        }
                    });
                    psSorted.push(tmp);
                });
            } else {


                let tmp = {};
                tmp.service_ids = [];
                tmp.service_names = [];
                let pjbs = [];
                selectedServices.forEach((s, i2) => {

                    tmp.service_ids.push($(s).data('serviceId'));
                    tmp.service_names.push($(s).text());
                    psSorted.push(tmp);
                });

            }
            // debugger;
            allData.forEach((row, i1) => {
                if (selectedPrograms.length > 0) {
                    // $('#program_price').prop('disbaled', false);
                    let t = {};
                    t.program_id = psSorted.map(p => p.program_id).join('_');
                    t.program_name = psSorted.map(p => p.program_name).join(',');
                    t.program_jobs = [];
                    psSorted.forEach((p) => {
                        p.program_jobs.forEach((j) => {
                            t.program_jobs.push(j)
                        })
                    });
                    t.program_jobs = Array.from(new Set(t.program_jobs.map(a => a.job_id)))
                        .map(job_id => {
                            return t.program_jobs.find(a => a.job_id === job_id)
                        });
                    let sIds = t.program_jobs.map(p => parseInt(p.job_id));
                    let sNms = t.program_jobs.map(p => p.job_name);
                    t.service_ids = [];
                    t.service_names = [];
                    psSorted.forEach((i) => {
                        i.service_ids.forEach((s) => {
                            if (!sIds.includes(s) && !t.service_ids.includes(s)) {
                                t.service_ids.push(s);
                            }
                        });
                        i.service_names.forEach((n) => {
                            if (!sNms.includes(n) && !t.service_names.includes(n)) {
                                t.service_names.push(n);
                            }
                        });
                    });
                    let tmp = [
                        propIds[i1],
                        (typeof (t.program_id) != "undefined") ? t.program_id : t.service_ids.join('-'),
                        row[1],
                        row[2],
                        row[3],
                        (typeof (t.program_id) != "undefined") ? 'Program/Service' : 'Service',
                        (typeof (t.program_id) != "undefined") ? `${t.program_name}/${t.program_jobs.map(job => job.job_name).join()}` : t.service_ids.join(),
                        returnInputs(t)
                    ];

                    overrideData.push(tmp);
                } else {
                    $('#program_price').prop('disbaled', true);
                    let tmp = [
                        propIds[i1],
                        psSorted[0].service_ids.join('-'),
                        row[1],
                        row[2],
                        row[3],
                        'Service',
                        psSorted[0].service_names.join(),
                        returnInputs(psSorted[0])
                    ];

                    overrideData.push(tmp);
                }
            });
        }

        overrideTable.clear();
        overrideTable.rows.add(overrideData).draw();
    }

    function returnInputs(obj) {
        let tmp = [];
        if (typeof (obj.program_id) != "undefined") {
            obj.program_jobs.forEach((j, i) => {
                tmp.push(`<input type="number" class="price_override_input form-control" name="pj-${obj.program_id}-${j.job_id}" min="0" step="0.01" placeholder="${j.job_name}" value="">`);
            });
        }
        obj.service_ids.forEach((s, i) => {
            tmp.push(`<input type="number" class="price_override_input form-control" name="serv-${s}" min="0" step="0.01" placeholder="${obj.service_names[i]}" value="">`);
        });

        return tmp.join('');
    }

    $(document).ready(function () {

        propTable = $('#dataTable_propList').DataTable({
            // "sDom": "lfrtip",
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            // "serverSide": true,
            'processing': true,
            "columnDefs": [
                {
                    "targets": [0],
                    "searchable": false,
                    "orderable": false,
                },
                {
                    "targets": [7, 8, 9],
                    "visible": false,
                    "searchable": true
                },
                {
                    "targets": [10, 11],
                    "visible": false,
                    "searchable": false
                }
            ]
        });
        overrideTable = $('#dataTable_override').DataTable({
            searching: false,
            data: overrideData,
            columnDefs: [
                {
                    targets: [0, 1],
                    visible: false
                }
            ],
            "drawCallback": function () {

                if (overrideData.length > 0) {

                    $('#override_container').removeClass('hidden');
                } else {

                    $('#override_container').addClass('hidden');
                }
            }
        });
        $("#dataTable_propList").on('change', 'input.row_select[type="checkbox"]', function (e) {
            let priceOverride = $(this.parentElement.parentElement).find('input.price_override[type="checkbox"]')[0];
            if (this.checked) {
                $(this.parentElement.parentElement).addClass('selected');
                $(priceOverride).prop("disabled", false);
            } else {
                $(this.parentElement.parentElement).removeClass('selected');
                $(priceOverride).prop("disabled", true);
            }
            buildPropertyDataArray();
            priceOverrideRedraw();
        });
        $("#dataTable_propList").on('click', 'input.price_override[type="checkbox"]', function (e) {
            let thisRow = this.parentElement.parentElement;
            if (this.checked) {
                $(thisRow).addClass('override');
            } else {
                $(thisRow).removeClass('override');
            }
            priceOverrideRedraw();
        });
        $('#row_select_all').click(function (e) {
            let columnFilters = Array.from($('.column_filter'));
            let filterAreEmpty = true;
            columnFilters.forEach((inpt) => {
                if (inpt.value.trim().length != 0) {
                    filterAreEmpty = false;
                    console.log('not empty');
                } else {
                    console.log('empty');
                }
            });
            // if(filterAreEmpty) {
            //   let allChecks = propTable.$(".row_select");
            //   for(chkBx of allChecks) {
            //     let overrideChkBx = $(chkBx.parentElement.parentElement).find('input.price_override[type="checkbox"]')[0];
            //     if(this.checked) {
            //       chkBx.checked = true;
            //       $(overrideChkBx).prop( "disabled", false );
            //       $(chkBx.parentElement.parentElement).addClass('selected');
            //     } else {
            //       chkBx.checked = false;
            //       $(chkBx.parentElement.parentElement).removeClass('selected');
            //       $(overrideChkBx).prop( "disabled", true );
            //     }
            //   }
            // } else {
            let allChecks = Array.from($('.row_select'));
            for (chkBx of allChecks) {
                let overrideChkBx = $(chkBx.parentElement.parentElement).find('input.price_override[type="checkbox"]')[0];
                if (this.checked) {
                    chkBx.checked = true;
                    $(overrideChkBx).prop("disabled", false);
                    $(chkBx.parentElement.parentElement).addClass('selected');
                } else {
                    chkBx.checked = false;
                    $(chkBx.parentElement.parentElement).removeClass('selected');
                    $(overrideChkBx).prop("disabled", true);
                }
                // }
            }
            buildPropertyDataArray();
            priceOverrideRedraw();
        });
        $('input.column_filter').on('keyup click', function () {
            filterColumn($(this).attr('data-column'));
        });
        $('#row_select_all').on('click', function (e) {
            // propTable.columns(0).nodes()[0].map(cell => cell.children[0]).forEach(check => check.checked = this.checked);
            let checkSelects = propTable.columns(0).nodes()[0].map(cell => cell.children[0]);
            // let chkStatus = this.checked;
            $("#select_source").val("");

        });

        $('.row_select').change(function (e) {
            var prop_id = $(this).val();
            var source = $("#source_" + prop_id).val();
            var checkboxes = $('input:checkbox:checked').length;
            if ($("#select_source").val() == "" && source != "" && checkboxes == 1) {
                $("#select_source").val(source);
            } else {
                // you already had a place picked so set this to blank
                $("#select_source").val("");
            }
        });

        $('#confirm_selection').on('click', function () {
            let l = propTable.rows()[0].length;
            let selected = [];
            for (let i = 0; i < l; i++) {
                if (propTable.row(i).nodes()[0].children[0].children[0].checked) {
                    selected.push(propTable.row(i).nodes()[0]);
                }
            }
        });
        $("#program_list").change(populateSelectBox);
        // $("#program_list").change( function(e) {

        // });
        $("#job_list").change(populateSelectBox);

        function populateSelectBox() {
            // programs = [];
            programs = Array.from($("#program_list option:selected").map(function (a, item) {
                return `<li class="form-border list-selected-item selected-program" data-program-id="${item.value}">${item.innerText.trim()}</li>`;
            }));
            // services = [];
            services = Array.from($("#job_list option:selected").map(function (a, item) {
                return `<li class="form-border list-selected-item selected-service" data-service-id="${item.value}">${item.innerText.trim()}</li>`;
            }));
            // selectedAll = [];
            // if(services.length > 0) {
            //   $('#program_price').prop('required', true);
            // } else {
            //   $('#program_price').removeProp('required');
            // }
            selectedAll = [].concat(programs, services);
            $("#selected-items").html(selectedAll.join(''));
            if (Array.from($('.list-selected-item')).length == 1 && $(Array.from($('.list-selected-item'))[0]).hasClass('selected-program')) {
                console.log('disabling');
                $('#program_price').prop("disabled", true);
                setCorrectPricing();
            } else {
                console.log('enabling');
                $('#program_price').prop("disabled", false);
            }
            buildListArray();
            priceOverrideRedraw();
        }
    });

    function setCorrectPricing() {
        let el = Array.from($('.list-selected-item'))[0];
        let id = $(el).data('programId');
        if (id) {
            for (let i = 0; i < programDetails.length; i++) {
                if (programDetails[i].program_id == id) {
                    let programPrice = programDetails[i].program_price;
                    console.log(programDetails[i]);
                    $('#program_price').val(programPrice);
                    console.log('!!found!!');
                    break;
                }
            }
        }
    }

    function buildListArray() {
        listData = {programs: [], services: []};
        let pList = $('#program_list').val();
        let jList = $('#job_list').val();
        let pData = [];
        let jData = [];
        pList.forEach((item) => {
            let tmp = {
                program_id: item
            };
            let pExtLen = programsExtended.length;
            for (let i = 0; i < pExtLen; i++) {
                if (item == programsExtended[i].program_id) {
                    tmp.program_jobs = programsExtended[i].program_jobs.map((job) => job.job_id);
                    break;
                }
            }
            pData.push(tmp);
        });
        jList.forEach((item) => {
            let tmp = {
                job_id: item
            }
            jData.push(tmp);
        });
        listData.programs = pData;
        listData.services = jData;

        $('#listarray').val(JSON.stringify(listData));
    }

    function getAllServicesByProgram(program_id) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('admin/Estimates/getAllServicesByProgram')  ?>",
            data: {program_id: program_id},
            dataType: 'JSON',
        }).done(function (response) {
            console.log(response.result);
        });
    }

    function buildPropertyDataArray() {
        let selectedProperties = Array.from(propTable.rows('.selected').data());
        propertyDataArray = selectedProperties.map((p) => {
            let tmpP = {
                property_id: p[0].replace(/\D/g, ''),
                customer_id: p[10],
                customer_email: p[11]
            }
            return tmpP;
        });
        $('#property_data_array').val(JSON.stringify(propertyDataArray));
    }
</script>
<script>
    // Programs with associated Services data objects
    const programsExtended = <?php echo json_encode($program_details_ext); ?>
</script>
<script>
    const postData = {};
    $(document).ready(function () {
        $('#save_draft').on('click', function (e) {
            // e.preventDefault();
            $('#status').val('0');
        });
        $('#submit_estimate').on('click', function (e) {
            e.preventDefault();
            $('#status').val('1');

            swal.mixin({
                input: 'textarea',
                confirmButtonText: 'Send',
                showCancelButton: true,
                progressSteps: 1
            }).queue([
                {
                    title: 'Additional Estimate Message (Included in Email)',
                    text: 'Type a message to the customer below to be included with the estimate. Then click "Send" to email the estimate to the customer.'
                },
            ]).then((result) => {

                if (result.value) {
                    var message = result.value;
                    //alert(message)
                    $('#email_notes').val(message);
                    // $(this).attr('email_notes').val(message);
                    // alert(message);
                    // $("#loading").css("display","block");
                    $('#submit_form').submit()
                }
            })

        });
        $('#submit_estimate_signwell').on('click', function (e) {
            e.preventDefault();
            $('#status').val('1');
            $('#signwell_status').val('1');


            swal.mixin({
                input: 'textarea',
                confirmButtonText: 'Send',
                showCancelButton: true,
                progressSteps: 1
            }).queue([
                {
                    title: 'Additional Estimate Message (Included in Email)',
                    text: 'Type a message to the customer below to be included with the estimate. Then click "Send" to email the estimate to the customer.'
                },
            ]).then((result) => {
                $("#loading").css("display", "block");
                if (result.value) {
                    var message = result.value;
                    $('#email_notes').val(message);
                    $('#submit_form').submit()
                }
            })
        });
        $('#submit_form form input').keydown(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
        $('#submit_form').submit(function (e) {
            let property_ids = Array.from(propTable.rows('.selected').nodes()).map(item => item.children[0].children[0].value);
            $('#property_id_array').val(JSON.stringify(property_ids));
            let propertiesSelectedChk = (Array.from(propTable.rows('.selected').data()).length > 0) ? true : false;
            let programServicesSelectedChk = (Array.from($('#selected-items').children()).length > 0) ? true : false;
            if (propertiesSelectedChk == true || programServicesSelectedChk == true) {
                let priceOverArray = [];
                let tableData = Array.from(overrideTable.rows().data());
                if (tableData.length > 0) {
                    tableData.forEach((item, i) => {
                        let tmp = {};
                        tmp.propertyId = item[0];
                        tmp.propId = item[0];
                        if (item[1].toString().split('-').length > 1) {
                            // tmp.program_id = item[1].toString();
                            tmp.job_id = item[1].toString().split('-')[1];
                        } else {
                            tmp.program_id = item[1].toString();
                        }

                        tmp.price_override = Array.from(overrideTable.cell(i, 7).node().children).map(inp => inp.value);
                        tmp.jobIds = Array.from(overrideTable.cell(i, 7).node().children).map(inp => inp.name.split('-')[inp.name.split('-').length - 1]);
                        // tmp.is_price_override = item[8];
                        priceOverArray.push(tmp);
                    });
                }

                $('#priceoverridearray').val(JSON.stringify(priceOverArray));
            } else {
                console.log('Blocking Submission');
                e.preventDefault();
                return false;
            }
        });
    });

</script>
<script>
    var programDetails = <?= json_encode($program_details); ?>;
    var serviceDetails = <?= json_encode($service_details); ?>;

</script>
