<style type="text/css">
    /* Disabled Menu Items */
    li.dropdown-menu-item.text-muted.dropdown-menu-item-icon.disabled > a {
        cursor: not-allowed;
        pointer-events: none;
    }

    /* End */

    th,
    td {
        text-align: center;
    }

    .pre-scrollable {

        min-height: 0px;

    }


    #modal_add_service .row {

        margin-bottom: 5px;

    }


    /* Note Form */

    button#addNoteBtn {

        background-color: #1c86d9;

    }

    button#addNoteBtn:hover, button#addNoteBtn:focus {

        color: #fff;

    }

    div#note-form-wrap {

        margin-bottom: 2em;

    }

    .row.row-extra-space {

        margin-top: 10px;

        margin-bottom: 20px;

    }

    /* Notes */

    button.properties-tab-active {

        background-color: #1d86d9 !important;

        border-color: #2196F3 !important;

        color: #fff !important;

    }

    div.well.property-note {

        margin-bottom: 20px !important;

        border: 1px solid #ddd !important;

        border-radius: 3px !important;

        color: #333 !important;

        background-color: #fafafa !important;

        font-family: 'Roboto' !important;

    }

    div.well.property-note[is_urgent="1"] {
        border-radius: 3px !important;
        background-color: #FBE9E7 !important;
        border: 1px solid #FF5722 !important;
    }


    .row.note-body {

        font-size: 1.5rem;

        margin-bottom: 2rem;

    }

    .customer-address.text-bold {

        color: #4a4a4a;

    }

    div.user-info {

        display: -webkit-box;

    }

    div.user-image {

        margin-right: 1em;

    }

    #note-header-right {

        display: flex;

        flex-direction: row;

        justify-content: flex-end;

        align-items: center;

        font-size: 1.5rem;

    }

    #note-header-right i.fa {

        margin: 0 1em;

        font-size: 2rem;

    }

    ul.dropdown-menu li.dropdown-header {

        color: black;

        text-align: center;

        font-size: 1.5rem;

    }

    li.dropdown-menu-item {

        border-bottom: 1px solid #acacac80;

        font-size: 1rem;

    }

    div.note-details {

        font-size: 1.25rem;

        font-weight: bold;

    }

    li.dropdown-menu-item.text-muted.dropdown-menu-item-icon a {

        padding-left: 0;

    }

    div.note-footer {

        font-size: 1.5rem;

        font-weight: bold;

        color: #4a4a4a;

    }

    div.note-footer i.fa {

        margin-right: 1rem;

        font-size: 1.75rem;

    }

    div.note-footer-left {

        /* display: -webkit-box; */

    }

    div.note-footer div.customer-name {

        border-left: 1px solid #ddd;

        border-right: 1px solid #ddd;

    }

    .list-group.comments-list-group {

        background-color: #fff;

    }

    .list-group-item.comment-list-item {

        border-bottom: 1px solid #ddd;

    }


    .list-group-item {

        /* position: relative;

      display: block;

      padding: 10px 15px; */

        /* margin-bottom: -1px; */

        /* background-color: #fff; */

        /* border: 1px solid #ddd; */

    }

    /* Testing */

    .media-block .media-left {

        display: block;

        float: left

    }


    .media-block .media-right {

        float: right

    }


    .media-block .media-body {

        display: block;

        overflow: hidden;

        width: auto

    }


    .middle .media-left,
    .middle .media-right,
    .middle .media-body {

        vertical-align: middle

    }

    .text-muted, a.text-muted:hover, a.text-muted:focus {

        color: #acacac;

    }

    .file-attach-icon {

        font-size: 5em;

    }

    .files-thumbnail {

        max-width: 150px;

        margin-left: auto;

        margin-right: auto;

    }

    /* Test End */

    /* The Modal (background) */

    .modal-files {

        display: none; /* Hidden by default */

        position: fixed; /* Stay in place */

        z-index: 1; /* Sit on top */

        padding-top: 100px; /* Location of the box */

        left: 0;

        top: 0;

        width: 100%; /* Full width */

        height: 100%; /* Full height */

        overflow: auto; /* Enable scroll if needed */

        background-color: rgb(0, 0, 0); /* Fallback color */

        background-color: rgba(0, 0, 0, 0.9); /* Black w/ opacity */

    }


    /* Modal Content (image) */

    .modal-content {

        margin: auto;

        display: block;

        width: 80%;

        max-width: 700px;

    }


    /* Caption of Modal Image */

    #caption {

        margin: auto;

        display: block;

        width: 80%;

        max-width: 700px;

        text-align: center;

        color: #ccc;

        padding: 10px 0;

        height: 150px;

    }


    /* Add Animation */

    .modal-content, #caption {

        -webkit-animation-name: zoom;

        -webkit-animation-duration: 0.6s;

        animation-name: zoom;

        animation-duration: 0.6s;

    }


    @-webkit-keyframes zoom {

        from {
            -webkit-transform: scale(0)
        }

        to {
            -webkit-transform: scale(1)
        }

    }


    @keyframes zoom {

        from {
            transform: scale(0)
        }

        to {
            transform: scale(1)
        }

    }


    /* The Close Button */

    .close {

        position: absolute;

        top: 15px;

        right: 35px;

        color: #f1f1f1;

        font-size: 40px;

        font-weight: bold;

        transition: 0.3s;

    }


    .close:hover,
    .close:focus {

        color: #bbb;

        text-decoration: none;

        cursor: pointer;

    }


    #file-display-modal {

        z-index: 999;

    }

    #modal-file-image {

        max-height: 80vh;

        object-fit: contain;

    }


    /* 100% Image Width on Smaller Screens */

    @media only screen and (max-width: 700px) {

        .modal-content {

            width: 100%;

        }

    }

    @media (min-width: 520px) {
        #desktop-frame,
        .desktop-col {
            display: block !important;
        }

        #mobile-frame {
            display: none !important;
        }
    }

    @media (max-width: 519px) {
        #desktop-frame,
        .desktop-col {
            display: none !important;
        }

        #mobile-frame {
            display: block !important;
        }
    }

    #hidden_source {
        display: none;
    }

    .alert-modal {
        padding: 2rem;
        font-size: 16px;
        height: 300px;
    }

    .alert-modal-checkbox {
        margin-left: -20px !important;
    }

    .alerts {
        display: grid;
        grid-template-columns: 25fr 1fr;
        font-size: 16px;
        padding: 10px;
        border-radius: 3px;
        background-color: #FBE9E7;
        color: #9c1f1f;
        border: 1px solid #FF5722;
        position: relative;
        margin-bottom: 1rem;
    }

    .alerts-span {
        padding: 10px;
    }

    .new-sectionarea {
        position: absolute;
        margin: 0;
        padding: 0px 20px;
        width: auto;
        text-align: right;
        display: inline-block;
        bottom: -30px;
        right: 0px;
    }

    .new-sectionarea h5.qun {
        width: auto;
        position: relative;
        margin: 0;
        padding: 0;
        display: inline-block;
    }

    .new-sectionarea span.chtn {
        padding: 0;
        margin: 0;
        width: auto;
        display: inline-block;
        position: relative;
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

    li.dropdown-menu-item.text-muted.dropdown-menu-item-icon.disabled {
        pointer-events: none;
    }
</style>


<style>
    .customerListField {
        cursor: pointer;
        width: 100%;
    }

    .customerListField.selected {
        cursor: pointer;
        width: 100%;
        background-color: #bbb;
    }

    .customerListField:hover {
        background-color: #ccc;
    }
</style>

<!-- Content area -->

<div class="content">


    <!-- Form horizontal -->

    <div class="panel panel-flat">

        <div class="panel-heading">

            <h5 class="panel-title">

                <div class="form-group">

                    <div class="row">

                        <div class="col-md-12">


                            <div class="btndiv">


                                <a href="<?= base_url('admin/propertyList') ?>" id="save" class="btn btn-success"><i

                                        class="icon-arrow-left7"></i> Back to All Properties</a>

                                <a href="<?= base_url('admin/editCustomer/' . $selectedcustomerlist[0] . '/' . $propertyData['property_id']) ?>"
                                   id="save" class="btn btn-success"><i class="icon-arrow-left7"></i> Back to Customer
                                    Quickview</a>


                                <a href="<?= base_url('admin/Estimates/addEstimate?pr_id=') . $propertyData['property_id'] ?>"

                                   id="" class="btn btn-warning"><i class="icon-plus2"> </i> Create Estimate</a>


                                <button type="button" class="btn btn-info" id="addServiceButton"

                                        data-target="#modal_add_service" data-toggle="modal"><i

                                        class=" icon-plus22"></i> Add Standalone Service
                                </button>
                                <button type="button" class="btn btn-primary" id="add_alert_btn"
                                        data-target="#modal_add_alert" data-toggle="modal">
                                    <i class=" icon-plus22"> </i> Add Alert
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ((isset($customer_alerts) && Count($customer_alerts) > 0) || ((isset($property_alerts) && Count($property_alerts) > 0))) { ?>
                    <div class="alerts">
                        <?php if (isset($customer_alerts) && Count($customer_alerts) > 0) { ?>
                        <?php foreach ($customer_alerts

                        as $index => $alert) { ?>
                        <span class="alerts-span"><?php echo $alert->text;
                            echo '</span><a style="color: #9c1f1f;" class="btn" href="' . base_url('admin/removeCustomerAlert/') . $index . '-' . $customer_id . '"> <i class="icon-trash-alt"> </i></a>' ?>
                            <?php } ?>
                            <?php } ?>
                            <?php if (isset($property_alerts) && Count($property_alerts) > 0){ ?>
                            <?php foreach ($property_alerts

                            as $index => $alert) { ?>
                                        <span class="alerts-span"><?php echo $alert->text;
                                            echo '</span><a style="color: #9c1f1f;" class="btn" href="' . base_url('admin/removePropertyAlert/') . $index . '-' . $propertyData['property_id'] . '"> <i class="icon-trash-alt"> </i></a>' ?>
                                            <?php } ?>
                                            <?php } ?>
                    </div>
                <?php } ?>
                <div class="form-group">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="btn-group btn-group-justified" role="group" aria-label="tab-select">

                                <div class="btn-group properties-tab-parent" role="group">

                                    <button type="button"
                                            class="btn btn-default properties-tab-btn properties-tab-active"
                                            id="properties-tabbtn-1">Properties Profile
                                    </button>

                                </div>

                                <div class="btn-group properties-tab-parent" role="group">

                                    <button type="button" class="btn btn-default properties-tab-btn"
                                            id="properties-tabbtn-2">Notes
                                    </button>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6 text-right">

                            <button type="button" class="btn" id="addNoteBtn" data-target="#note-form-wrap"
                                    data-toggle="collapse" aria-expanded="false" aria-controls="note-form-wrap"
                                    style="display: none;"><i id="addNoteBtnIco" class="icon-plus22"></i> Add New Note
                            </button>

                        </div>


                    </div>

                </div>

            </h5>

        </div>

        <div id="loading">
            <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"/> <!-- Loading Image -->
        </div>
        <br>

        <div class="panel-body">
            <b><?php if ($this->session->flashdata()) : echo $this->session->flashdata('message'); endif; ?></b>
            <form class="form-horizontal properties-tab" action="<?= base_url('admin/updateProperty') ?>" method="post"
                  name="addproperty" enctype="multipart/form-data" id="properties-tab-1">
                <fieldset class="content-group">
                    <input type="hidden" name="property_id" class="form-control"
                           value="<?= $propertyData['property_id']; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Property Name</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="property_title"
                                           value="<?php echo set_value('property_title') ? set_value('property_title') : $propertyData['property_title'] ?>"
                                           placeholder="Property Name">
                                    <span style="color:red;"><?php echo form_error('property_title'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Assign Customer</label>
                                <div class="multi-select-full col-lg-9">


                                    <?php
                                    $id = [];
                                    $billingType = [];
                                    $billingStreet = [];
                                    $firstName = [];
                                    $lastName = [];


                                    foreach ($customerlist as $value) {
                                        if (in_array($value->customer_id, $selectedcustomerlist)) {

                                            array_push($id, $value->customer_id);
                                            array_push($billingType, $value->billing_type);
                                            array_push($billingStreet, $value->billing_street);
                                            array_push($firstName, $value->last_name);
                                            array_push($lastName, $value->first_name);
                                        }
                                    }
                                    ?>


                                    <input autocomplete="off" type="text" class="form-control" id="customer_list_field"
                                           value="<?php $lengthOfArray = count($id);
                                           for ($i = 0; $i < $lengthOfArray; $i++) {
                                               echo $firstName[$i] . " " . $lastName[$i] . ", ";

                                           }
                                           ?>" placeholder="Assign Customer"/>
                                    <div
                                        style="z-index: 999; width: 100%; display: none; position: absolute; left: 0px; top: 40px; background-color: #ffffff; overflow-y: scroll; height: 25em; max-height: 25em;"
                                        id="suggestion-box"></div>


                                    <div style="display: none;" id="customer_list_div">
                                        <select style="display: none;" name="assign_customer[]" multiple="multiple"
                                                id="customer_list">

                                            <?php
                                            $lengthOfArray = count($id);
                                            for ($i = 0; $i < $lengthOfArray; $i++) {
                                                echo "<option value='" . $id[$i] . "' data-billingtype='" . $billingType[$i] . "' selected ";
                                                echo "title='" . $billingStreet[$i] . "'>" . $firstName[$i] . " " . $lastName[$i] . "</option>";

                                            }

                                            ?>

                                        </select>
                                    </div>


                                    <span style="color:red;"><?php echo form_error('assign_customer'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($is_group_billing) && $is_group_billing == 1) {
                        $hide = 1; ?>
                        <input type="hidden" name="is_group_billing" value=1/>
                    <?php } else {
                        $hide = 0; ?>
                        <input type="hidden" name="is_group_billing" value=0/>
                    <?php } ?>
                    <div class="row group_billing_contact_info" <?php if ($hide != 1) {
                        echo 'style="display:none;"';
                    } ?>>
                        <?php if (isset($groupBilling['group_billing_id'])) { ?>
                            <input type="hidden" name="group_billing_id"
                                   value="<?= $groupBilling['group_billing_id']; ?>"/>
                        <?php } ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Property First Name</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="property_first_name"
                                           value="<?php echo isset($groupBilling['first_name']) ? $groupBilling['first_name'] : '' ?>"
                                           placeholder="Property First Name"
                                           required>
                                    <span style="color:red;"><?php echo form_error('property_first_name'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Property Last Name</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="property_last_name"
                                           value="<?= isset($groupBilling['last_name']) ? $groupBilling['last_name'] : '' ?>"
                                           placeholder="Property Last Name"
                                           required>
                                    <span style="color:red;"><?php echo form_error('property_last_name'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row group_billing_contact_info" <?php if ($hide != 1) {
                        echo 'style="display:none;"';
                    } ?>>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Property Phone</label>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="property_phone"
                                                   value="<?= isset($groupBilling['phone']) ? $groupBilling['phone'] : '' ?>"
                                                   placeholder="Property Phone"
                                                   required>
                                            <span style="color:red;"><?php echo form_error('property_phone'); ?></span>
                                            <span>Please do not use dashes</span>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label class="checkbox-right">
                                                    <input type="checkbox" name="property_is_text"
                                                           class="switchery-is-text" <?php echo isset($groupBilling['phone_opt_in']) && $groupBilling['phone_opt_in'] == 1 ? 'checked' : '' ?>/>&nbsp;Opt-In
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Property Email</label>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="property_email"
                                                   value="<?php echo set_value('property_email') ? set_value('property_email') : (isset($groupBilling['email']) ? $groupBilling['email'] : '') ?>"
                                                   placeholder="Property Email" required>
                                            <span style="color:red;"><?php echo form_error('property_email'); ?></span>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label class="checkbox-right">
                                                    <input type="checkbox" name="property_is_email"
                                                           class="switchery-property-is-email" <?php echo isset($groupBilling['email_opt_in']) && $groupBilling['email_opt_in'] == 1 ? 'checked' : '' ?>/>&nbsp;Opt-In
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row group_billing_contact_info" <?php if ($hide != 1) {
                        echo 'style="display:none;"';
                    } ?>>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Secondary Phone</label>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="secondary_phone"
                                                   value="<?= isset($groupBilling['secondary_phone']) ? $groupBilling['secondary_phone'] : '' ?>"
                                                   placeholder="Secondary Phone">
                                            <span>Please do not use dashes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Secondary Email</label>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="secondary_email"
                                                   value="<?php echo set_value('secondary_email') ? set_value('secondary_email') : (isset($groupBilling['secondary_email']) ? $groupBilling['secondary_email'] : '') ?>"
                                                   placeholder="Secondary Email">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="control-label col-lg-3">Address</label>

                                <div class="col-lg-9">

                                    <input type="text" class="form-control" name="property_address" id="autocomplete"

                                           onFocus="geolocate()" value="<?= $propertyData['property_address'] ?>"

                                           placeholder="Address">


                                </div>

                            </div>

                        </div>

                        <div id="map"></div>

                        <input type="hidden" name="property_latitude" id="latitude"

                               value="<?= $propertyData['property_latitude'] ?>"/>

                        <input type="hidden" name="property_longitude" id="longitude"

                               value="<?= $propertyData['property_longitude'] ?>"/>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="control-label col-lg-3">Address 2</label>

                                <div class="col-lg-9">

                                    <input type="text" class="form-control" name="property_address_2"

                                           value="<?php echo set_value('property_address_2') ? set_value('property_address_2') : $propertyData['property_address_2'] ?>"

                                           placeholder="Address 2">

                                    <span style="color:red;"><?php echo form_error('property_address_2'); ?></span>

                                </div>

                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">City</label>

                                    <div class="col-lg-9">

                                        <input type="text" class="form-control" name="property_city"

                                               value="<?php echo set_value('property_city') ? set_value('property_city') : $propertyData['property_city'] ?>"

                                               placeholder="City" id="locality">

                                        <span style="color:red;"><?php echo form_error('property_city'); ?></span>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">State / Territory</label>

                                    <div class="col-lg-9">


                                        <select class="form-control" name="property_state" id="region">

                                            <option value="">Select State</option>
                                            <optgroup label="Canadian Provinces">
                                                <option value="AB" <?php if ($propertyData['property_state'] == 'AB') {
                                                    echo "selected";
                                                } ?>>Alberta
                                                </option>
                                                <option value="BC" <?php if ($propertyData['property_state'] == 'BC') {
                                                    echo "selected";
                                                } ?>>British Columbia
                                                </option>
                                                <option value="MB" <?php if ($propertyData['property_state'] == 'MB') {
                                                    echo "selected";
                                                } ?>>Manitoba
                                                </option>
                                                <option value="NB" <?php if ($propertyData['property_state'] == 'NB') {
                                                    echo "selected";
                                                } ?>>New Brunswick
                                                </option>
                                                <option value="NF" <?php if ($propertyData['property_state'] == 'NF') {
                                                    echo "selected";
                                                } ?>>Newfoundland
                                                </option>
                                                <option value="NT" <?php if ($propertyData['property_state'] == 'NT') {
                                                    echo "selected";
                                                } ?>>Northwest Territories
                                                </option>
                                                <option value="NS" <?php if ($propertyData['property_state'] == 'NS') {
                                                    echo "selected";
                                                } ?>>Nova Scotia
                                                </option>
                                                <option value="NU" <?php if ($propertyData['property_state'] == 'NU') {
                                                    echo "selected";
                                                } ?>>Nunavut
                                                </option>
                                                <option value="ON" <?php if ($propertyData['property_state'] == 'ON') {
                                                    echo "selected";
                                                } ?>>Ontario
                                                </option>
                                                <option value="PE" <?php if ($propertyData['property_state'] == 'PE') {
                                                    echo "selected";
                                                } ?>>Prince Edward Island
                                                </option>
                                                <option value="QC" <?php if ($propertyData['property_state'] == 'QC') {
                                                    echo "selected";
                                                } ?>>Quebec
                                                </option>
                                                <option value="SK" <?php if ($propertyData['property_state'] == 'SK') {
                                                    echo "selected";
                                                } ?>>Saskatchewan
                                                </option>
                                                <option value="YT" <?php if ($propertyData['property_state'] == 'YT') {
                                                    echo "selected";
                                                } ?>>Yukon Territory
                                                </option>
                                            </optgroup>
                                            <optgroup label="U.S. States/Territories">
                                                <option value="AL" <?php if ($propertyData['property_state'] == 'AL') {
                                                    echo "selected";
                                                } ?>>Alabama
                                                </option>
                                                <option value="AK" <?php if ($propertyData['property_state'] == 'AK') {
                                                    echo "selected";
                                                } ?>>Alaska
                                                </option>
                                                <option value="AZ" <?php if ($propertyData['property_state'] == 'AZ') {
                                                    echo "selected";
                                                } ?>>Arizona
                                                </option>
                                                <option value="AR" <?php if ($propertyData['property_state'] == 'AR') {
                                                    echo "selected";
                                                } ?>>Arkansas
                                                </option>
                                                <option value="CA" <?php if ($propertyData['property_state'] == 'CA') {
                                                    echo "selected";
                                                } ?>>California
                                                </option>
                                                <option value="CO" <?php if ($propertyData['property_state'] == 'CO') {
                                                    echo "selected";
                                                } ?>>Colorado
                                                </option>
                                                <option value="CT" <?php if ($propertyData['property_state'] == 'CT') {
                                                    echo "selected";
                                                } ?>>Connecticut
                                                </option>
                                                <option value="DE" <?php if ($propertyData['property_state'] == 'DE') {
                                                    echo "selected";
                                                } ?>>Delaware
                                                </option>
                                                <option value="DC" <?php if ($propertyData['property_state'] == 'DC') {
                                                    echo "selected";
                                                } ?>>District Of Columbia
                                                </option>
                                                <option value="FL" <?php if ($propertyData['property_state'] == 'FL') {
                                                    echo "selected";
                                                } ?>>Florida
                                                </option>
                                                <option value="GA" <?php if ($propertyData['property_state'] == 'GA') {
                                                    echo "selected";
                                                } ?>>Georgia
                                                </option>
                                                <option value="HI" <?php if ($propertyData['property_state'] == 'HI') {
                                                    echo "selected";
                                                } ?>>Hawaii
                                                </option>
                                                <option value="ID" <?php if ($propertyData['property_state'] == 'ID') {
                                                    echo "selected";
                                                } ?>>Idaho
                                                </option>
                                                <option value="IL" <?php if ($propertyData['property_state'] == 'IL') {
                                                    echo "selected";
                                                } ?>>Illinois
                                                </option>
                                                <option value="IN" <?php if ($propertyData['property_state'] == 'IN') {
                                                    echo "selected";
                                                } ?>>Indiana
                                                </option>
                                                <option value="IA" <?php if ($propertyData['property_state'] == 'IA') {
                                                    echo "selected";
                                                } ?>>Iowa
                                                </option>
                                                <option value="KS" <?php if ($propertyData['property_state'] == 'KS') {
                                                    echo "selected";
                                                } ?>>Kansas
                                                </option>
                                                <option value="KY" <?php if ($propertyData['property_state'] == 'KY') {
                                                    echo "selected";
                                                } ?>>Kentucky
                                                </option>
                                                <option value="LA" <?php if ($propertyData['property_state'] == 'LA') {
                                                    echo "selected";
                                                } ?>>Louisiana
                                                </option>
                                                <option value="ME" <?php if ($propertyData['property_state'] == 'ME') {
                                                    echo "selected";
                                                } ?>>Maine
                                                </option>
                                                <option value="MD" <?php if ($propertyData['property_state'] == 'MD') {
                                                    echo "selected";
                                                } ?>>Maryland
                                                </option>
                                                <option value="MA" <?php if ($propertyData['property_state'] == 'MA') {
                                                    echo "selected";
                                                } ?>>Massachusetts
                                                </option>
                                                <option value="MI" <?php if ($propertyData['property_state'] == 'MI') {
                                                    echo "selected";
                                                } ?>>Michigan
                                                </option>
                                                <option value="MN" <?php if ($propertyData['property_state'] == 'MN') {
                                                    echo "selected";
                                                } ?>>Minnesota
                                                </option>
                                                <option value="MS" <?php if ($propertyData['property_state'] == 'MS') {
                                                    echo "selected";
                                                } ?>>Mississippi
                                                </option>
                                                <option value="MO" <?php if ($propertyData['property_state'] == 'MO') {
                                                    echo "selected";
                                                } ?>>Missouri
                                                </option>
                                                <option value="MT" <?php if ($propertyData['property_state'] == 'MT') {
                                                    echo "selected";
                                                } ?>>Montana
                                                </option>
                                                <option value="NE" <?php if ($propertyData['property_state'] == 'NE') {
                                                    echo "selected";
                                                } ?>>Nebraska
                                                </option>
                                                <option value="NV" <?php if ($propertyData['property_state'] == 'NV') {
                                                    echo "selected";
                                                } ?>>Nevada
                                                </option>
                                                <option value="NH" <?php if ($propertyData['property_state'] == 'NH') {
                                                    echo "selected";
                                                } ?>>New Hampshire
                                                </option>
                                                <option value="NJ" <?php if ($propertyData['property_state'] == 'NJ') {
                                                    echo "selected";
                                                } ?>>New Jersey
                                                </option>
                                                <option value="NM" <?php if ($propertyData['property_state'] == 'NM') {
                                                    echo "selected";
                                                } ?>>New Mexico
                                                </option>
                                                <option value="NY" <?php if ($propertyData['property_state'] == 'NY') {
                                                    echo "selected";
                                                } ?>>New York
                                                </option>
                                                <option value="NC" <?php if ($propertyData['property_state'] == 'NC') {
                                                    echo "selected";
                                                } ?>>North Carolina
                                                </option>
                                                <option value="ND" <?php if ($propertyData['property_state'] == 'ND') {
                                                    echo "selected";
                                                } ?>>North Dakota
                                                </option>
                                                <option value="OH" <?php if ($propertyData['property_state'] == 'OH') {
                                                    echo "selected";
                                                } ?>>Ohio
                                                </option>
                                                <option value="OK" <?php if ($propertyData['property_state'] == 'OK') {
                                                    echo "selected";
                                                } ?>>Oklahoma
                                                </option>
                                                <option value="OR" <?php if ($propertyData['property_state'] == 'OR') {
                                                    echo "selected";
                                                } ?>>Oregon
                                                </option>
                                                <option value="PA" <?php if ($propertyData['property_state'] == 'PA') {
                                                    echo "selected";
                                                } ?>>Pennsylvania
                                                </option>
                                                <option value="RI" <?php if ($propertyData['property_state'] == 'RI') {
                                                    echo "selected";
                                                } ?>>Rhode Island
                                                </option>
                                                <option value="SC" <?php if ($propertyData['property_state'] == 'SC') {
                                                    echo "selected";
                                                } ?>>South Carolina
                                                </option>
                                                <option value="SD" <?php if ($propertyData['property_state'] == 'SD') {
                                                    echo "selected";
                                                } ?>>South Dakota
                                                </option>
                                                <option value="TN" <?php if ($propertyData['property_state'] == 'TN') {
                                                    echo "selected";
                                                } ?>>Tennessee
                                                </option>
                                                <option value="TX" <?php if ($propertyData['property_state'] == 'TX') {
                                                    echo "selected";
                                                } ?>>Texas
                                                </option>
                                                <option value="UT" <?php if ($propertyData['property_state'] == 'UT') {
                                                    echo "selected";
                                                } ?>>Utah
                                                </option>
                                                <option value="VT" <?php if ($propertyData['property_state'] == 'VT') {
                                                    echo "selected";
                                                } ?>>Vermont
                                                </option>
                                                <option value="VA" <?php if ($propertyData['property_state'] == 'VA') {
                                                    echo "selected";
                                                } ?>>Virginia
                                                </option>
                                                <option value="WA" <?php if ($propertyData['property_state'] == 'WA') {
                                                    echo "selected";
                                                } ?>>Washington
                                                </option>
                                                <option value="WV" <?php if ($propertyData['property_state'] == 'WV') {
                                                    echo "selected";
                                                } ?>>West Virginia
                                                </option>
                                                <option value="WI" <?php if ($propertyData['property_state'] == 'WI') {
                                                    echo "selected";
                                                } ?>>Wisconsin
                                                </option>
                                                <option value="WY" <?php if ($propertyData['property_state'] == 'WY') {
                                                    echo "selected";
                                                } ?>>Wyoming
                                                </option>
                                            </optgroup>

                                        </select>

                                        <span style="color:red;"><?php echo form_error('property_state'); ?></span>


                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Postal Code</label>

                                    <div class="col-lg-9">

                                        <input type="text" class="form-control" name="property_zip"

                                               value="<?php echo set_value('property_zip') ? set_value('property_zip') : $propertyData['property_zip'] ?>"

                                               placeholder="Postal Code" id="postal-code">

                                        <span style="color:red;"><?php echo form_error('property_zip'); ?></span>

                                    </div>

                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Property Type</label>

                                    <div class="form-group">

                                        <div class="col-sm-3">

                                            <label class="radio-inline">

                                                <input name="property_type" value="Commercial"
                                                       type="radio" <?php if ($propertyData['property_type'] == "Commercial") {

                                                    echo 'checked';

                                                } else {

                                                } ?> />Commercial

                                            </label>

                                        </div>

                                        <div class="col-sm-3">

                                            <label class="radio-inline">

                                                <input name="property_type" value="Residential"
                                                       type="radio" <?php if ($propertyData['property_type'] == "Residential") {

                                                    echo 'checked';

                                                } else {

                                                } ?> />Residential

                                            </label>

                                        </div>


                                    </div>

                                    <span style="color:red;"><?php echo form_error('property_type'); ?></span>

                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Service Area</label>

                                    <div class="col-lg-9">

                                        <select class="form-control" name="property_area"

                                                value="<?php echo set_value('property_area') ?>">

                                            <option value="">Select Any Service Area</option>


                                            <?php if (!empty($propertyarealist)) {


                                                foreach ($propertyarealist as $value) {

                                                    if ($propertyData['property_area'] == $value->property_area_cat_id) {

                                                        $selected = 'selected';

                                                    } else {

                                                        $selected = '';

                                                    }


                                                    ?>


                                                    <option
                                                        value="<?= $value->property_area_cat_id ?>" <?= $selected; ?>>

                                                        <?= $value->category_area_name ?></option>

                                                <?php }

                                            } ?>

                                        </select>

                                        <span style="color:red;"><?php echo form_error('property_area'); ?></span>
                                        <br/>
                                        <div class="btn btn-success m-y-1" id="auto-assign-service-area">Auto Assign
                                            Service Area
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-6"

                                 style="display:<?= $setting_details->is_sales_tax == 1 ? 'block' : 'none' ?> ">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Sales Tax Area</label>

                                    <div class="multi-select-full col-lg-9" style="padding-left: 6px;">

                                        <select class="multiselect-select-all-filtering form-control"

                                                name="sale_tax_area_id[]" multiple="multiple" id="sales_tax">


                                            <?php if (!empty($sales_tax_details)) {

                                                foreach ($sales_tax_details as $key => $value) {

                                                    ?>


                                                    <option value="<?= $value->sale_tax_area_id ?>"

                                                            <?php if (in_array($value->sale_tax_area_id, $assign_sales_tax)) { ?>selected

                                                        <?php } ?>> <?= $value->tax_name ?> </option>


                                                <?php }

                                            } ?>


                                        </select>

                                    </div>

                                </div>


                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Total Yard Square Feet</label>

                                    <div class="col-lg-9" style="padding-left: 8px;">

                                        <input type="text" class="form-control" name="yard_square_feet"

                                               id="yard_square_feet"

                                               value="<?php echo set_value('yard_square_feet') ? set_value('yard_square_feet') : $propertyData['yard_square_feet'] ?>"

                                               placeholder="Total Yard Square Feet">

                                        <span style="color:red;"><?php echo form_error('yard_square_feet'); ?></span>

                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Total Yard Grass Type</label>

                                    <div class="col-lg-9">

                                        <select class="form-control" name="total_yard_grass" id="total_yard_grass">

                                            <option value="">Select Yard Grass Type</option>

                                            <option
                                                value="Bent" <?php if ($propertyData['total_yard_grass'] == 'Bent') {

                                                echo "selected";

                                            } ?>>Bent
                                            </option>

                                            <option
                                                value="Bermuda" <?php if ($propertyData['total_yard_grass'] == 'Bermuda') {

                                                echo "selected";

                                            } ?>>Bermuda
                                            </option>

                                            <option
                                                value="Dichondra" <?php if ($propertyData['total_yard_grass'] == 'Dichondra') {

                                                echo "selected";

                                            } ?>>Dichondra
                                            </option>

                                            <option
                                                value="Fine Fescue" <?php if ($propertyData['total_yard_grass'] == 'Fine Fescue') {

                                                echo "selected";

                                            } ?>>Fine Fescue
                                            </option>

                                            <option
                                                value="Kentucky Bluegrass" <?php if ($propertyData['total_yard_grass'] == 'Kentucky BluegrassAL') {

                                                echo "selected";

                                            } ?>>Kentucky Bluegrass
                                            </option>

                                            <option
                                                value="Ryegrass" <?php if ($propertyData['total_yard_grass'] == 'Ryegrass') {

                                                echo "selected";

                                            } ?>>Ryegrass
                                            </option>

                                            <option
                                                value="St. Augustine/Floratam" <?php if ($propertyData['total_yard_grass'] == 'St. Augustine/Floratam') {

                                                echo "selected";

                                            } ?>>St. Augustine/Floratam
                                            </option>

                                            <option
                                                value="Tall Fescue" <?php if ($propertyData['total_yard_grass'] == 'Tall Fescue') {

                                                echo "selected";

                                            } ?>>Tall Fescue
                                            </option>

                                            <option
                                                value="Zoysia" <?php if ($propertyData['total_yard_grass'] == 'Zoysia') {

                                                echo "selected";

                                            } ?>>Zoysia
                                            </option>

                                            <option
                                                value="Centipede" <?php if ($propertyData['total_yard_grass'] == 'Centipede') {

                                                echo "selected";

                                            } ?>>Centipede
                                            </option>

                                            <option
                                                value="Bluegrass/Rye/Fescue" <?php if ($propertyData['total_yard_grass'] == 'Bluegrass/Rye/Fescue') {

                                                echo "selected";

                                            } ?>>Bluegrass/Rye/Fescue
                                            </option>

                                            <option
                                                value="Warm Season" <?php if ($propertyData['total_yard_grass'] == 'Warm Season') {

                                                echo "selected";

                                            } ?>>Warm Season
                                            </option>

                                            <option
                                                value="Cool Season" <?php if ($propertyData['total_yard_grass'] == 'Cool Season') {

                                                echo "selected";

                                            } ?>>Cool Season
                                            </option>

                                            <option
                                                value="Mixed Grass" <?php if ($propertyData['total_yard_grass'] == 'Mixed Grass') {

                                                echo "selected";

                                            } ?>>Mixed Grass
                                            </option>

                                        </select>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Front Yard Square Feet</label>

                                    <div class="col-lg-9" style="padding-left: 11px;">

                                        <input type="text" class="form-control" name="front_yard_square_feet"

                                               id="front_yard_square_feet"

                                               value="<?php echo set_value('front_yard_square_feet') ? set_value('front_yard_square_feet') : $propertyData['front_yard_square_feet'] ?>"

                                               placeholder="Front Yard Square Feet">

                                        <span
                                            style="color:red;"><?php echo form_error('front_yard_square_feet'); ?></span>

                                    </div>

                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Front Yard Grass Type</label>

                                    <div class="col-lg-9">

                                        <select class="form-control" name="front_yard_grass" id="front_yard_grass">

                                            <option value="">Select Front Yard Grass Type</option>

                                            <option
                                                value="Bent" <?php if ($propertyData['front_yard_grass'] == 'Bent') {

                                                echo "selected";

                                            } ?>>Bent
                                            </option>

                                            <option
                                                value="Bermuda" <?php if ($propertyData['front_yard_grass'] == 'Bermuda') {

                                                echo "selected";

                                            } ?>>Bermuda
                                            </option>

                                            <option
                                                value="Dichondra" <?php if ($propertyData['front_yard_grass'] == 'Dichondra') {

                                                echo "selected";

                                            } ?>>Dichondra
                                            </option>

                                            <option
                                                value="Fine Fescue" <?php if ($propertyData['front_yard_grass'] == 'Fine Fescue') {

                                                echo "selected";

                                            } ?>>Fine Fescue
                                            </option>

                                            <option
                                                value="Kentucky Bluegrass" <?php if ($propertyData['front_yard_grass'] == 'Kentucky BluegrassAL') {

                                                echo "selected";

                                            } ?>>Kentucky Bluegrass
                                            </option>

                                            <option
                                                value="Ryegrass" <?php if ($propertyData['front_yard_grass'] == 'Ryegrass') {

                                                echo "selected";

                                            } ?>>Ryegrass
                                            </option>

                                            <option
                                                value="St. Augustine/Floratam" <?php if ($propertyData['front_yard_grass'] == 'St. Augustine/Floratam') {

                                                echo "selected";

                                            } ?>>St. Augustine/Floratam
                                            </option>

                                            <option
                                                value="Tall Fescue" <?php if ($propertyData['front_yard_grass'] == 'Tall Fescue') {

                                                echo "selected";

                                            } ?>>Tall Fescue
                                            </option>

                                            <option
                                                value="Zoysia" <?php if ($propertyData['front_yard_grass'] == 'Zoysia') {

                                                echo "selected";

                                            } ?>>Zoysia
                                            </option>

                                            <option
                                                value="Centipede" <?php if ($propertyData['front_yard_grass'] == 'Centipede') {

                                                echo "selected";

                                            } ?>>Centipede
                                            </option>

                                            <option
                                                value="Bluegrass/Rye/Fescue" <?php if ($propertyData['front_yard_grass'] == 'Bluegrass/Rye/Fescue') {

                                                echo "selected";

                                            } ?>>Bluegrass/Rye/Fescue
                                            </option>

                                            <option
                                                value="Warm Season" <?php if ($propertyData['front_yard_grass'] == 'Warm Season') {

                                                echo "selected";

                                            } ?>>Warm Season
                                            </option>

                                            <option
                                                value="Cool Season" <?php if ($propertyData['front_yard_grass'] == 'Cool Season') {

                                                echo "selected";

                                            } ?>>Cool Season
                                            </option>


                                        </select>

                                    </div>

                                </div>

                            </div>


                        </div>

                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Back Yard Square Feet</label>

                                    <div class="col-lg-9" style="padding-left: 11px;">

                                        <input type="text" class="form-control" name="back_yard_square_feet"

                                               id="back_yard_square_feet"

                                               value="<?php echo set_value('back_yard_square_feet') ? set_value('back_yard_square_feet') : $propertyData['back_yard_square_feet'] ?>"

                                               placeholder="Back Yard Square Feet">

                                        <span
                                            style="color:red;"><?php echo form_error('back_yard_square_feet'); ?></span>

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Back Yard Grass Type</label>


                                    <div class="col-lg-9">

                                        <select class="form-control" name="back_yard_grass" id="back_yard_grass">

                                            <option value="">Select Back Yard Grass Type</option>

                                            <option value="Bent" <?php if ($propertyData['back_yard_grass'] == 'Bent') {

                                                echo "selected";

                                            } ?>>Bent
                                            </option>

                                            <option
                                                value="Bermuda" <?php if ($propertyData['back_yard_grass'] == 'Bermuda') {

                                                echo "selected";

                                            } ?>>Bermuda
                                            </option>

                                            <option
                                                value="Dichondra" <?php if ($propertyData['back_yard_grass'] == 'Dichondra') {

                                                echo "selected";

                                            } ?>>Dichondra
                                            </option>

                                            <option
                                                value="Fine Fescue" <?php if ($propertyData['back_yard_grass'] == 'Fine Fescue') {

                                                echo "selected";

                                            } ?>>Fine Fescue
                                            </option>

                                            <option
                                                value="Kentucky Bluegrass" <?php if ($propertyData['back_yard_grass'] == 'Kentucky BluegrassAL') {

                                                echo "selected";

                                            } ?>>Kentucky Bluegrass
                                            </option>

                                            <option
                                                value="Ryegrass" <?php if ($propertyData['back_yard_grass'] == 'Ryegrass') {

                                                echo "selected";

                                            } ?>>Ryegrass
                                            </option>

                                            <option
                                                value="St. Augustine/Floratam" <?php if ($propertyData['back_yard_grass'] == 'St. Augustine/Floratam') {

                                                echo "selected";

                                            } ?>>St. Augustine/Floratam
                                            </option>

                                            <option
                                                value="Tall Fescue" <?php if ($propertyData['total_yard_grass'] == 'Tall Fescue') {

                                                echo "selected";

                                            } ?>>Tall Fescue
                                            </option>

                                            <option
                                                value="Zoysia" <?php if ($propertyData['total_yard_grass'] == 'Zoysia') {

                                                echo "selected";

                                            } ?>>Zoysia
                                            </option>

                                            <option
                                                value="Centipede" <?php if ($propertyData['back_yard_grass'] == 'Centipede') {

                                                echo "selected";

                                            } ?>>Centipede
                                            </option>

                                            <option
                                                value="Bluegrass/Rye/Fescue" <?php if ($propertyData['back_yard_grass'] == 'Bluegrass/Rye/Fescue') {

                                                echo "selected";

                                            } ?>>Bluegrass/Rye/Fescue
                                            </option>

                                            <option
                                                value="Warm Season" <?php if ($propertyData['back_yard_grass'] == 'Warm Season') {

                                                echo "selected";

                                            } ?>>Warm Season
                                            </option>

                                            <option
                                                value="Cool Season" <?php if ($propertyData['back_yard_grass'] == 'Cool Season') {

                                                echo "selected";

                                            } ?>>Cool Season
                                            </option>

                                        </select>

                                    </div>


                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Property Difficulty Level</label>

                                    <div class="col-lg-9">

                                        <select class="form-control" name="difficulty_level">

                                            <option value="">Select Difficulty Level</option>

                                            <option value="1"

                                                <?php echo $propertyData['difficulty_level'] == 1 ? 'selected' : '' ?>>

                                                Level 1
                                            </option>

                                            <option value="2"

                                                <?php echo $propertyData['difficulty_level'] == 2 ? 'selected' : '' ?>>

                                                Level 2
                                            </option>

                                            <option value="3"

                                                <?php echo $propertyData['difficulty_level'] == 3 ? 'selected' : '' ?>>

                                                Level 3
                                            </option>

                                        </select>

                                    </div>

                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Property Conditions</label>
                                    <div class="multi-select-full col-lg-9">
                                        <select class="multiselect-select-all-filtering form-control"
                                                name="property_conditions[]" multiple="multiple"
                                                id="property_conditions_list">
                                            <?php if (is_array($propertyconditionslist)) {
                                                foreach ($propertyconditionslist as $condition) {
                                                    if (in_array($condition->property_condition_id, $selectedpropertyconditions)) {
                                                        $selectedPC = "selected";
                                                    } else {
                                                        $selectedPC = "";
                                                    }
                                                    ?>
                                                    <option
                                                        value=<?= $condition->property_condition_id ?> <?= $selectedPC ?>><?= $condition->condition_name ?></option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Property Status</label>
                                    <div class="col-lg-9" style="    padding-left: 6px;">
                                        <select class="form-control" name="property_status" id="property_status"
                                                onchange="showDiv('hidden_source', this)">
                                            <option value="">Select Any Status</option>
                                            <option
                                                value="2" <?php echo $propertyData['property_status'] == 2 ? 'selected' : '' ?>>
                                                Prospect
                                            </option>
                                            <option
                                                value="1" <?php echo $propertyData['property_status'] == 1 ? 'selected' : '' ?>>
                                                Active
                                            </option>
                                            <option
                                                value="0" <?php echo $propertyData['property_status'] == 0 ? 'selected' : '' ?>>
                                                Non-Active
                                            </option>
                                            <option
                                                value="3" <?php echo $propertyData['property_status'] == 3 ? 'selected' : '' ?>>
                                                Estimate
                                            </option>
                                            <option
                                                value="4" <?php echo $propertyData['property_status'] == 4 ? 'selected' : '' ?>>
                                                Sales Call Scheduled
                                            </option>
                                            <option
                                                value="5" <?php echo $propertyData['property_status'] == 5 ? 'selected' : '' ?>>
                                                Estimate Sent
                                            </option>
                                            <option
                                                value="6" <?php echo $propertyData['property_status'] == 6 ? 'selected' : '' ?>>
                                                Estimate Declined
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Assign Tag</label>
                                    <div class="multi-select-full col-lg-9">
                                        <select class="multiselect-select-all-filtering4 form-control" name="tags[]"
                                                multiple="multiple" id="tags_list">
                                            <option value="">Select any Tags</option>
                                            <?php foreach ($taglist as $value) : ?>
                                                <option
                                                    value="<?= $value->id ?>"<?php if (in_array($value->id, $selected_tag_ids)) { ?> selected <?php } ?>
                                                      ><?= $value->tags_title ?> </option>
                                            <?php endforeach ?>
                                        </select>
                                        <span style="color:red;"><?php echo form_error('tags'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" id="hidden_source">
                                    <label class="control-label col-lg-3">Source</label>
                                    <div class="multi-select-full col-lg-9  col-sm-10 col-xs-10">
                                        <select class=" form-control" name="source" id="source"
                                                value="<?php echo set_value('source') ?>">
                                            <option value="">Select Source</option>
                                            <?php foreach ($source_list as $value) : ?>
                                                <option
                                                    value="<?= $value->source_id ?>" <?php echo $propertyData['source'] == $value->source_id ? 'selected' : '' ?>><?= $value->source_name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span style="color:red;"><?php echo form_error('assign_program'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function showDiv(divId, element) {
                                    document.getElementById(divId).style.display = element.value == 2 ? 'block' : 'none';
                                }

                                if ($("#property_status").val() == 2) {
                                    $("#hidden_source").show();
                                } else {
                                    $("#hidden_source").hide();
                                }
                            </script>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Property Info</label>
                                    <div class="col-lg-9" style="    padding-left: 6px;border: 1px solid #12689b;">
                                        <textarea class="summernote_property"
                                                  name="property_notes"> <?= $propertyData['property_notes'] ?> </textarea>
                                        <span style="color:red;"><?php echo form_error('property_notes'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="original_progs" id="original_progs"
                                       value="<?php echo implode(',', $original_program_ids); ?>">

                                <div class="form-group">
                                    <label class="control-label col-lg-3">Assign Program</label>
                                    <div class="multi-select-full col-lg-9">
                                        <select class="multiselect-select-all-filtering2 form-control"
                                                name="assign_program_tmp[]" multiple="multiple" id="program_list">
                                            <option value="">Select any program</option>
                                            <?php foreach ($programlist as $value) : ?>
                                                <?php if (!strstr($value->program_name, '- Standalone')) { ?>
                                                    <option value="<?= $value->program_id ?>"
                                                            <?php if (in_array($value->program_id, $selected_program_ids)) { ?>selected
                                                        <?php } ?>> <?= $value->program_name ?> </option>
                                                <?php } ?>
                                            <?php endforeach ?>
                                        </select>
                                        <span style="color:red;"><?php echo form_error('assign_program'); ?></span>
                                    </div>
                                </div>
                                <div class="program-price-over-ride-container"
                                     style="display: <?php echo !empty($selectedprogramlist) ? 'block' : 'none'; ?>;">
                                    <div class="table-responsive  pre-scrollable">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Program Name</th>
                                                <th>Price Override Per Service</th>
                                            </tr>
                                            </thead>
                                            <tbody class="priceoverridetbody">
                                            <?php $n = 1;
                                            $keyIds = array();
                                            $selectedValues = array();
                                            $selectedTexts = array();
                                            if (!empty($selectedprogramlist)) {
                                                foreach ($selectedprogramlist as $value) {
                                                    if (!strstr($value->program_name, "- Standalone") && !strstr($value->program_name, "-Standalone Service")) {
                                                        $price_override = (isset($value->is_price_override_set) && $value->is_price_override_set == 1) ? floatval($value->price_override) : '';
                                                        echo '<tr id="trid' . $value->program_id . '" ><td>' . $value->program_name . '</td><td><input type="number" name="tmp' . $n . '" min="0" step="0.01" value="' . $price_override . '"  class="inpcl form-control" optval="' . $value->program_id . '"  ></td></tr>';
                                                    }
                                                    $selectedValues[] = $value->program_id;
                                                    $selectedTexts[] = $value->program_name;
                                                    $keyIds[] = array(
                                                        'program_id' => $value->program_id,
                                                        'price_override' => $value->price_override,
                                                        'is_price_override_set' => $value->is_price_override_set,
                                                    );
                                                    $n++;

                                                }
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <textarea name="assign_program" id="assign_program_ids2"
                                      style="display: none;"><?php echo json_encode($keyIds); ?></textarea>
                        </div>


                        <!-- Property Available Days -->
                        <div class="row">
                            <div class="col-md-6" style="margin: 16px auto">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Property Available Days</label>
                                    <div class="col-lg-9" style="padding-left: 11px;">
                                <span style="color:red;">
                                    <?php echo form_error('checkbox_available_monday') ?>
                                    <?php echo form_error('checkbox_available_tuesday') ?>
                                    <?php echo form_error('checkbox_available_wednesday') ?>
                                    <?php echo form_error('checkbox_available_thursday') ?>
                                    <?php echo form_error('checkbox_available_friday') ?>
                                    <?php echo form_error('checkbox_available_saturday') ?>
                                    <?php echo form_error('checkbox_available_sunday') ?>
                                </span>

                                        <?php $daysOfWeek = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']; ?>
                                        <?php $availableDays = json_decode($propertyData['available_days'], true); ?>
                                        <?php foreach ($daysOfWeek as $day): ?>
                                            <?php $checkboxid = 'available_' . $day;
                                            $formattedDay = ucfirst($day); ?>
                                            <div class="col-md-6">
                                                <div id="subscribeTooltip" class="checkbox" data-popup="tooltip-custom"
                                                     title="Turning this option off will indicate this property is not available on this day"
                                                     data-placement="left">
                                                    <label class="checkbox-inline checkbox-right">
                                                        <input id="checkbox_<?= $checkboxid ?>" type="checkbox"
                                                               name="checkbox_<?= $checkboxid ?>"
                                                               class="switchery_checkbox_<?= $checkboxid ?>"
                                                            <?php if ($availableDays[$day] == 'true') {
                                                                echo 'checked';
                                                            } else {
                                                                echo '';
                                                            } ?> >
                                                        <?= $formattedDay ?></label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <script>
                                            <?php foreach($daysOfWeek as $day): ?>
                                            <?php $checkboxid = 'available_' . $day; ?>
                                            var checkbox_<?= $checkboxid ?> = document.querySelector('.switchery_checkbox_<?= $checkboxid ?>');
                                            var switchery = new Switchery(checkbox_<?= $checkboxid ?>, {
                                                color: '#36c9c9', secondaryColor: "#dfdfdf",
                                            });
                                            <?php endforeach; ?>
                                        </script>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- \Property Available Days -->


                        <!-- Start Measure Map Scaffolding -->


                        <?php if ($propertyData['measure_map_project_id'] != NULL) {

                            $mmpid = $propertyData['measure_map_project_id'];

                        } else {

                            $mmpid = '';

                        } ?>


                        <div class="row">

                            <div class="col-md-6" style="margin: 16px auto">

                                <div class="col-lg-5"></div>

                                <div class="form-group">

                                    <a href="https://app.measuremaponline.com/" target="_blank"
                                       rel="noopener noreferrer"

                                       class="btn btn-info"><i class="icon-plus2"></i>Add

                                        Measure Map Online Lawn

                                        Measurement</a>

                                </div>

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-6" style="margin: 16px auto">

                                <div class="form-group">

                                    <label class="control-label col-lg-3">Measure Map ID</label>

                                    <div class="col-lg-9" style="padding-left: 11px;">

                                        <input type="text" data-toggle="tooltip"

                                               title="You can copy this by simply opening your Measure Map project and clicking on the project name at the top of the screen. If you are accessing Measuremap Online from your mobile phone, you will need to turn your phone to landscape view to see the project name at the top of your screen. You can then tap to copy the project ID."

                                               class="form-control" name="measure_map_project_id"
                                               id="measure_map_project_id"

                                               value="<?php echo set_value('measure_map_project_id') ? set_value('measure_map_project_id') : $propertyData['measure_map_project_id']; ?>"

                                               placeholder="Please enter the Measure Map Online Project ID"/>

                                        <span
                                            style="color:red;"><?php echo form_error('measure_map_project_id') ?></span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div id="desktop-frame">

                            <div class="row"

                                 style="display: <?php echo $propertyData['measure_map_project_id'] == NULL ? 'none' : 'block'; ?>">

                                <div class="col-lg-1 desktop-col" style="margin-left: 40px;"></div>

                                <div class="col-md-6">

                                    <iframe type="text/html"

                                            src="https://app.measuremaponline.com/iframe_api/?pid=<?= $mmpid ?>&key=0txedklOpKxvyalu0leSUODuIZkvfPIW_LCjbk4axk2_DKLw3_v0fc5cjwKWZXAH&ms=imperial&maptype=satellite"

                                            width="520" height="520" frameborder="0" crossorigin="anonymous">

                                    </iframe>

                                </div>

                            </div>

                        </div>

                        <div id="mobile-frame">

                            <div class="row"

                                 style="display: <?php echo $propertyData['measure_map_project_id'] == NULL ? 'none' : 'block'; ?>">

                                <div class="col-md-6">

                                    <iframe type="text/html"

                                            src="https://app.measuremaponline.com/iframe_api/?pid=<?= $mmpid ?>&key=0txedklOpKxvyalu0leSUODuIZkvfPIW_LCjbk4axk2_DKLw3_v0fc5cjwKWZXAH&ms=imperial&maptype=satellite"

                                            width="375" height="375" frameborder="0" crossorigin="anonymous">

                                    </iframe>

                                </div>

                            </div>

                        </div>


                        <!-- End Measure Map Scaffolding -->

                    </div>
                </fieldset>


                <div class="text-right btn-space">

                    <button type="submit" id="saveaddress" class="btn btn-success">Save <i

                                class="icon-arrow-right14 position-right"></i></button>

                </div>

            </form>


            <?php

            $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

            $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

            ?>

            <!-- Notes Tab Start -->

            <div class="properties-tab hidden" id="properties-tab-2">


                <div id="note-form-wrap" class="collapse">

                    <form class="form-horizontal" action="<?= base_url('admin/createNote') ?>" method="post"
                          name="createnoteform" enctype="multipart/form-data" id="createnoteform"
                          onSubmit="formFileSizeValidate(this)">
                        <fieldset class="content-group">
                            <input type="hidden" name="note_property_id" class="form-control"
                                   value="<?= $propertyData['property_id']; ?>">
                            <input type="text" hidden name="note_customer_id" value="<?= $customer_id; ?>">
                            <input type="hidden" name="note_category" class="form-control" value="0">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Note Type</label>
                                        <div class="col-lg-8">
                                            <select class="form-control" name="note_type" required
                                                    id="notetypenotcusedi">
                                                <option value="" disabled selected></option>
                                                <?php foreach ($note_types as $type) : ?>
                                                    <option
                                                        value="<?= $type->type_id; ?>"><?= $type->type_name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 assignservicesedicustumer" id="assignservicesedicustumer">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Assign Services</label>
                                        <div class="col-lg-8">
                                            <select class="form-control" name="note_assigned_services">
                                                <option value="">None</option>
                                                <?php
                                                foreach ($servicelist as $service) {
                                                    ?>
                                                    <option
                                                        value="<?= $service->job_id; ?>"><?= $service->job_name; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <span
                                                style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 assignservicesedicustumer">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Note Duration</label>
                                        <div class="col-lg-8">
                                            <select class="form-control" name="assigned_service_note_duration">
                                                <option value="">None</option>
                                                <option value=1>Permanent</option>
                                                <option value=2>Next Service Only</option>
                                            </select>
                                            <span
                                                style="color:red;"><?php echo form_error('assigned_service_note_duration'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Assign User</label>
                                        <div class="col-lg-8">
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
                                            <span
                                                style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Due Date</label>
                                        <div class="col-lg-8">
                                            <input id="note_due_date" type="text" name="note_due_date"
                                                   class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-extra-space">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Attach Documents</label>
                                        <div class="col-lg-8 text-left">
                                            <input id="files" type="file" name="files[]" class="form-control-file"
                                                   multiple onChange="fileValidationCheck(this)">
                                            <span style="color:red;"><?php echo form_error('files'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-lg-9">Include in Technician View?</label>
                                        <input id="include_in_tech_view" type="checkbox" name="include_in_tech_view"
                                               class="checkbox col-lg-3 switchery_technician_view" value="1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-lg-9">
                                            Urgent Note
                                        </label>
                                        <input type="checkbox"
                                               name="is_urgent"
                                               id="is_urgent"
                                               class="col-lg-3 checkbox checkbox-inline switchery_urgent_note">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4">
                                            Notify Me
                                        </label>
                                        <input type="checkbox"
                                               name="notify_me"
                                               id="notify_me"
                                               checked
                                               class="col-lg-8 checkbox checkbox-inline text-right switchery_notify_me">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-lg-9">
                                            Enable Notification
                                        </label>
                                        <input type="checkbox" onchange="toggle_notification_to();"
                                               name="is_enable_notifications"
                                               id="is_enable_notifications"
                                               class="col-lg-3 checkbox checkbox-inline text-right switchery_enable_notification">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group toggle_notification_to" style="display: none;">
                                        <label class="control-label col-lg-4">
                                            Notification To</label>
                                        <div class="multi-select-full col-lg-8">
                                            <select class="multiselect-select-all-filtering form-control note-filter"
                                                    name="notification_to[]" id="notification_to" multiple>
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-6">Note Contents</label>
                                        <div class="col-lg-12">
                                            <textarea class="form-control" name="note_contents" id="note_contents"
                                                      rows="5"></textarea>
                                            <span style="color:red;"><?php echo form_error('note_contents'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="text-right btn-space">
                            <button type="button" class="btn" id="addNoteBtn"
                                    data-target="#note-form-wrap" data-toggle="collapse"
                                    aria-expanded="false" aria-controls="note-form-wrap"
                                    style="margin-left=15px;">Cancel
                            </button>
                            <button type="submit" id="savenote" class="btn btn-success">Save <i
                                    class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </form>
                </div>

                <?php

                $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

                $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

                ?>


                <div class="row">

                    <div class="col-xs-12">


                        <div class="row">


                            <div class="col-xs-4">

                                <div class="form-group">

                                    <label for="note_status_filter">Status</label>

                                    <select class="form-control" name="note_status_filter" id="note_status_filter">

                                        <option value="0" selected>None</option>

                                        <option value="1">Open</option>

                                        <option value="2">Closed</option>

                                    </select>

                                </div>

                            </div>
                        </div>


                    </div>


                </div>


                <div class="row" id="note_tab_contents">
                    <div class="col-md-12">

                        <?php if (!empty($property_notes)) {

                            foreach ($property_notes as $note) { ?>

                                <div class="well property-note" data-note-id="<?= $note->note_id; ?>"
                                     data-note-status="<?= (!empty($note->note_assigned_user)) ? $note->note_status : '0'; ?>"
                                     is_urgent="<?= $note->is_urgent; ?>"
                                >

                                    <div class="row note-header">

                                        <div class="col-md-8 user-info">
                                            <div class="note-details">
                                                <?php if ($note->note_customer_id) : ?>
                                                    <a href="<?= base_url() . 'admin/editCustomer/' . $note->note_customer_id ?>">
                                                        <h3 class="text-bold media-heading box-inline text-primary"><?= $note->customer_full_name; ?></h3>
                                                    </a>
                                                <?php endif ?>
                                                <p class="text-muted"><?= date_format(date_create($note->note_created_at), "H:i A | F j, Y"); ?></p>

                                            </div>

                                        </div>

                                        <div id="note-header-right" class="col-md-4 pull-right text-right">

                                            <?php if (!empty($note->note_assigned_user)) : ?>

                                                <span id="note-assigned-user-wrap-<?= $note->note_id; ?>"><span>Assigned to&nbsp;</span><span
                                                        class="text-success text-bold">
                                                        <?= $note->user_assigned_full_name; ?>

                                            </span></span>

                                            <?php endif; ?>

                                            <div class="form-group hidden"
                                                 id="update-assignuser-<?= $note->note_id; ?>">

                                                <label class="control-label col-lg-3">Assign User</label>

                                                <div class="col-lg-12">

                                                    <select class="form-control" name="note_assigned_user"
                                                            id="note_assigned_user_<?= $note->note_id; ?>"
                                                            data-note-id="<?= $note->note_id; ?>"
                                                            data-note-userid="<?= $note->note_user_id; ?>"
                                                            onchange="getNoteAssignUserUpdateVars(this)">

                                                        <!-- Add Users available within company with Value = user_id / option shown user_name -->

                                                        <option
                                                            value="" <?= (empty($note->note_assigned_user)) ? 'selected' : ''; ?>>
                                                            None
                                                        </option>

                                                        <?php

                                                        foreach ($userdata as $user) {

                                                            if ($note->note_user_id) { ?>

                                                                <option
                                                                    value="<?= $user->id; ?>" <?= ($user->id == $note->note_assigned_user) ? 'selected' : ''; ?>><?= $user->user_first_name . ' ' . $user->user_last_name; ?></option>

                                                            <?php }

                                                        }

                                                        ?>

                                                    </select>

                                                    <span
                                                        style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>

                                                </div>

                                            </div>

                                            <div class="dropdown">

                              <span class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="true">

                                <i class="fa fa-ellipsis-h fa-xl" aria-hidden="true"></i>

                              </span>

                                                <ul class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenu1">

                                                    <li class="dropdown-header text-bold text-uppercase">Actions</li>

                                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>"
                                                        id="note-assign-btn-<?= $note->note_id; ?>"><a
                                                            href="javascript:showAssignUserSelect(<?= $note->note_id; ?>)"><i
                                                                class="fa fa-user-circle-o" aria-hidden="true"></i>Assign
                                                            Specific User</a></li>

                                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>">
                                                        <a href="javascript:showDueDateSelect(<?= $note->note_id; ?>)"><i
                                                                class="fa fa-calendar" aria-hidden="true"></i>Edit
                                                            Due Date</a></li>

                                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>">
                                                        <a href="javascript:showNoteTypeSelect(<?= $note->note_id; ?>)"><i
                                                                class="fa fa-pencil-square-o"
                                                                aria-hidden="true"></i>Change Note Type</a></li>

                                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2') ? ' disabled' : ''; ?>">
                                                        <a href="javascript:$('.note_edit_notification_to_<?= $note->note_id; ?>').toggleClass('hidden')"><i
                                                                class="fa fa-pencil-square-o"
                                                                aria-hidden="true"></i>Change Note Notification To</a>
                                                    </li>

                                                    <?php

                                                    if (isset($currentUser->role_id) && $currentUser->role_id < 3) {

                                                        ?>

                                                        <li class="dropdown-menu-item text-muted dropdown-menu-item-icon">
                                                            <a href="<?= base_url('admin/markNoteComplete/') . $note->note_id; ?>"><i
                                                                    class="fa fa-check-square-o"
                                                                    aria-hidden="true"></i>Mark Complete</a></li>
                                                        <li class="dropdown-menu-item text-muted dropdown-menu-item-icon">
                                                            <a href="<?= base_url('admin/toggleUrgentMarker/') . $note->note_id . '/' . ($note->is_urgent ? 0 : 1); ?>"><i
                                                                    class="fa fa-check-square-o" aria-hidden="true"></i><?= $note->is_urgent ? 'Remove Urgent Status' : 'Mark Urgent'; ?></a>
                                                        </li>

                                                        <li class="dropdown-menu-item text-muted dropdown-menu-item-icon">
                                                            <a href="<?= base_url('admin/deleteNote/') . $note->note_id; ?>"><i
                                                                    class="fa fa-trash-o" aria-hidden="true"></i>Delete
                                                                Note</a></li>

                                                        <?php

                                                    } else {
                                                        $not_owner_note = ($currentUser->id !== $note->note_assigned_user && $currentUser->id !== $note->note_user_id);
                                                        ?>
                                                        <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2' || $not_owner_note) ? ' disabled' : ''; ?>">
                                                            <a href="<?= !($note->note_status == '2' || $not_owner_note) ? base_url('admin/markNoteComplete/') . $note->note_id : 'javascript:void(0)'; ?>"><i
                                                                    class="fa fa-check-square-o" aria-hidden="true"></i>Mark
                                                                Complete</a></li>
                                                        <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2' || $not_owner_note) ? ' disabled' : ''; ?>">
                                                            <a href="<?= !($note->note_status == '2' || $not_owner_note) ? base_url('admin/toggleUrgentMarker/') . $note->note_id . '/' . ($note->is_urgent ? 0 : 1) : 'javascript:void(0)'; ?>"><i
                                                                    class="fa fa-check-square-o" aria-hidden="true"></i><?= $note->is_urgent ? 'Remove Urgent Status' : 'Mark Urgent'; ?></a>
                                                        </li>
                                                        <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= $not_owner_note ? ' disabled' : ''; ?>">
                                                            <a href="<?= base_url('admin/deleteNote/') . $note->note_id; ?>"><i
                                                                    class="fa fa-trash-o" aria-hidden="true"></i>Delete Note</a>
                                                        </li>
                                                        <?php

                                                    }

                                                    ?>

                                                </ul>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row note-body">

                                        <div class="col-md-12">

                                            <p><?= $note->note_contents; ?></p>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6"
                                             id="note-assigned-type-wrapper-div-<?= $note->note_id; ?>">


                                            <?php

                                            if ($note->note_type == 1) {

                                                ?>

                                                <span id="note-assigned-type-wrap-<?= $note->note_id; ?>"
                                                      class="text-bold text-success"
                                                      style="font-size: 1.2em">Task</span>

                                                <?php

                                            } else {

                                                $type_name = null;

                                                foreach ($note_types as $type) {

                                                    if ($note->note_type == $type->type_id) {

                                                        $type_name = $type->type_name;

                                                    }

                                                }

                                                if (isset($type_name)) {

                                                    ?>
                                                    <span id="note-assigned-type-wrap-<?= $note->note_id; ?>"
                                                          class="text-bold text-success"
                                                          style="font-size: 1.2em"><?= (isset($type_name)) ? $type_name : ''; ?></span>

                                                    <?php if ($type_name == "Service-Specific") {
                                                        $duration = "";
                                                        if (isset($note->assigned_service_note_duration) && $note->assigned_service_note_duration == 1) {
                                                            $duration = " (Permanent)";
                                                        } elseif (isset($note->assigned_service_note_duration) && $note->assigned_service_note_duration == 2) {
                                                            $duration = " (Next Service Only)";
                                                        }
                                                        if (isset($note->job_name) && !empty($note->job_name)) { ?>
                                                            <br><span
                                                                id="note-assigned-service-wrap-<?= $note->note_id; ?>"
                                                                class="text-success"
                                                                style="font-size: 1.2em"><?= $note->job_name ?><?= $duration ?></span>
                                                        <?php }
                                                    }
                                                }
                                            }

                                            ?>

                                        </div>

                                        <div class="col-md-3 pull-right text-right">

                                            Tech Visible<i class="fa fa-question" aria-hidden="true"
                                                           style="margin-right: 5px;"></i> <input type="checkbox"
                                                                                                  onclick="techVisibleSwtch(this,<?= $note->note_id; ?>)"<?= ($note->include_in_tech_view == 1) ? 'checked' : ''; ?>>

                                        </div>

                                    </div>

                                    <div class="row hidden note_edit_notification_to_<?= $note->note_id;?>">
                                        <label class="control-label col-lg-12" style="font-size: 16px;">
                                            Notification To</label>
                                        <div class="multi-select-full col-lg-3">
                                            <select class="multiselect-select-all-filtering form-control note-filter"
                                                    name="note_edit_notification_to[]"
                                                    id="note_edit_notification_to"
                                                    multiple="multiple">
                                                <?php
                                                foreach ($userdata as $user) {
                                                    ?>
                                                    <option value="<?= $user->id; ?>" <?= !empty($note->notification_to) && in_array($user->id, explode(',', $note->notification_to)) ? 'selected' : '' ?>>
                                                        <?= $user->user_first_name . ' ' . $user->user_last_name; ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 text-white">
                                            <button type="button" class="btn btn-success" onclick="changeNoteNotificationTo(this, <?= $note->note_id; ?>)">
                                                Save Changes
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group hidden" id="update-notetype-<?= $note->note_id; ?>">
                                                <label class="control-label">Note Type</label>
                                                <select class="form-control" name="note_edit_type"
                                                        id="note_edit_type_<?= $note->note_id; ?>"
                                                        data-note-id="<?= $note->note_id; ?>"
                                                        data-note-typeid="<?= $note->note_type; ?>"
                                                        onchange="getNoteTypeUpdateVars(this)">
                                                    <!-- Add types available within company with Value = type_id / option shown type_name -->
                                                    <option value="" disabled selected>None</option>
                                                    <?php
                                                    $service_specific = 'false';
                                                    foreach ($note_types as $type) :
                                                        if ($type->type_id == $service_specific_note_type_id) {
                                                            $service_specific = 'true';
                                                        }
                                                        ?>
                                                        <option value="<?= $type->type_id; ?>"
                                                                data-servicespecific=<?= $service_specific ?>><?= $type->type_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span style="color:red;"><?php echo form_error('note_type'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group hidden"
                                                 id="update-notetype-services<?= $note->note_id; ?>">
                                                <label class="control-label">Assign Services</label>
                                                <select class="form-control" name="note_edit_assigned_services"
                                                        id="note_edit_assigned_services<?= $note->note_id; ?>"
                                                        data-note-id="<?= $note->note_id; ?>">
                                                    <option value="">None</option>
                                                    <?php
                                                    foreach ($servicelist as $service) {
                                                        ?>
                                                        <option
                                                            value="<?= $service->job_id; ?>"><?= $service->job_name; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span
                                                    style="color:red;"><?php echo form_error('note_edit_assigned_user'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group hidden"
                                                 id="update-notetype-duration<?= $note->note_id; ?>">
                                                <label class="control-label">Note Duration</label>
                                                <select class="form-control" name="edit_assigned_service_note_duration"
                                                        id="edit_assigned_service_note_duration<?= $note->note_id; ?>"
                                                        data-note-typeid="<?= $note->note_type; ?>"
                                                        data-note-id="<?= $note->note_id; ?>"
                                                        onchange="getNoteTypeUpdateServiceSpecificVars(this)">
                                                    <option value="">None</option>
                                                    <option value=1>Permanent</option>
                                                    <option value=2>Next Service Only</option>
                                                </select>
                                                <span
                                                    style="color:red;"><?php echo form_error('edit_assigned_service_note_duration'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row note-footer note-footer-flex">

                                        <div class="col-md-7 note-footer-left">

                                            <div class="row">

                                                <div class="status col-sm-12 col-md-4 col-lg-3 text-bold">

                                                    <?php

                                                    if (!empty($note->note_status)) {

                                                        ?>

                                                        Status: <?= ($note->note_status == 1) ? '<span class="text-success">OPEN</span>' : '<span class="text-warning">CLOSED</span>'; ?>

                                                        <?php

                                                    } else {

                                                        ?>

                                                        Status: <span class="text-muted">None</span>

                                                        <?php

                                                    }

                                                    ?>

                                                </div>

                                                <?php

                                                if (isset($note->note_user_id)) {
                                                    ?>
                                                    <div class="creator-name col-sm-12 col-md-4 col-lg-3 ">
                                                        <span>Created by&nbsp;</span>
                                                        <span class="text-bold">
                                                                <?= $note->user_first_name; ?> <?= $note->user_last_name; ?>
                                                            </span>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                                <?php

                                                if (isset($note->property_address) && isset($note->property_city)) {

                                                    ?>

                                                    <div class="customer-address col-sm-12 col-md-8 col-lg-6 text-bold">

                                                        <i class="fa fa-map-marker"
                                                           aria-hidden="true"></i> <?= $note->property_address; ?>
                                                        , <?= $note->property_city; ?>

                                                    </div>

                                                    <?php

                                                }

                                                ?>

                                            </div>

                                        </div>

                                        <div class="col-md-4 note-footer-right pull-right">

                                            <div class="row">

                                                <div
                                                    class="col-md-8 note-due-date text-warning text-uppercase text-bold text-center">

                                                    <i class="fa fa-flag" aria-hidden="true"></i> DUE: <span
                                                        id="note-duedate-<?= $note->note_id; ?>"><?= ($note->note_due_date != '0000-00-00' && !empty($note->note_due_date)) ? $note->note_due_date : 'None Set'; ?></span><input
                                                        id="note_due_date_<?= $note->note_id; ?>" type="text"
                                                        name="note_due_date"
                                                        class="form-control pickaalldate hidden"
                                                        placeholder="YYYY-MM-DD"
                                                        data-noteid="<?= $note->note_id; ?>"
                                                        onchange="updateNoteDueDate(this)">

                                                </div>

                                                <div class="col-md-3 pull-right">

                                                    <div class="row">

                                                        <div class="col-md-6 note-comments">

                                                            <i class="fa fa-comment-o" aria-hidden="true"
                                                               data-toggle="collapse"
                                                               data-target="#note-comments-<?= $note->note_id; ?>"
                                                               aria-expanded="false"
                                                               aria-controls="note-comments-<?= $note->note_id; ?>"></i>
                                                            <span
                                                                id="comment-count-value-<?= $note->note_id; ?>"><?= count($note->comments); ?></span>

                                                        </div>

                                                        <div class="col-md-6 note-attachments">

                                                            <i class="fa fa-paperclip" aria-hidden="true"
                                                               data-toggle="collapse"
                                                               data-target="#note-files-<?= $note->note_id; ?>"
                                                               aria-expanded="false"
                                                               aria-controls="note-files-<?= $note->note_id; ?>"></i> <?= count($note->files); ?>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="note-comments collapse" id="note-comments-<?= $note->note_id; ?>">

                                        <hr>

                                        <div class="row">

                                            <div class="col-md-12">

                                                <h4><strong>Comments</strong></h4>

                                                <ul class="list-group comments-list-group">

                                                    <?php foreach ($note->comments as $comment) { ?>

                                                        <li class="list-group-item comment-list-item">

                                                            <small
                                                                class="text-muted"><?= $comment->comment_created_at ?></small>
                                                            <strong><?= $comment->user_first_name . ' ' . $comment->user_last_name; ?>
                                                                : </strong><?= $comment->comment_body; ?>

                                                        </li>

                                                    <?php } ?>

                                                    <li class="list-group-item comment-list-item">

                                                        <form action="<?= base_url('admin/addNoteComment') ?>"
                                                              method="post" name="add-note-comment-form"
                                                              enctype="multipart/form-data"
                                                              id="add-note-comment-form-<?= $note->note_id; ?>"
                                                              onsubmit="addCommentAjax('<?= $note->note_id; ?>')">

                                                            <input type="hidden"
                                                                   value="<?= $this->session->userdata('id'); ?>"
                                                                   name="comment-userid">

                                                            <input type="hidden" value="<?= $note->note_id; ?>"
                                                                   name="comment-noteid">

                                                            <div class="input-group">

                                                                <input class="form-control" name="add-comment-input"
                                                                       id="" placeholder="Add Comment">

                                                                <div class="input-group-btn">

                                                                    <!-- Buttons -->

                                                                    <button type="submit"
                                                                            class="btn btn-primary pull-right">Post
                                                                        Comment
                                                                    </button>

                                                                </div>

                                                            </div>

                                                        </form>

                                                    </li>

                                                </ul>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="note-files collapse" id="note-files-<?= $note->note_id; ?>">

                                        <hr>

                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="row">

                                                    <div class="col-md-6">

                                                        <h4><strong>Attachments</strong></h4>

                                                    </div>

                                                    <div class="col-md-6 text-right add-files-right">

                                                        <strong>Attach Additional Files</strong> <i
                                                            class="icon-cloud-upload btn-ico" aria-hidden="true"
                                                            data-toggle="collapse"
                                                            data-target="#note-fileupload-<?= $note->note_id; ?>"
                                                            aria-expanded="false"
                                                            aria-controls="note-fileupload-<?= $note->note_id; ?>"
                                                            style="font-size: 2em; padding-left: 10px;"></i>

                                                    </div>

                                                </div>

                                                <div class="col-md-4 col-lg-3 pull-right collapse"
                                                     id="note-fileupload-<?= $note->note_id; ?>">

                                                    <form action="<?= base_url('admin/addToNoteFiles'); ?>"
                                                          method="post" enctype="multipart/form-data"
                                                          onsubmit="formFileSizeValidate(this)">

                                                        <input type="hidden"
                                                               value="<?= $this->session->userdata('id'); ?>"
                                                               name="user_id">

                                                        <input type="hidden" value="<?= $note->note_id; ?>"
                                                               name="note_id">

                                                        <div class="row row-extra-space">

                                                            <div class="col-xs-12">

                                                                <div class="form-group">

                                                                    <label class="control-label col-lg-4 text-right">Attach
                                                                        Documents</label>

                                                                    <div class="col-lg-8 text-left">

                                                                        <input id="files" type="file" name="files[]"
                                                                               class="form-control-file" multiple
                                                                               onchange="fileValidationCheck(this)">
                                                                        <span
                                                                            style="color:red;"><?php echo form_error('files'); ?></span>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                            <button type="submit" class="btn btn-primary pull-right">
                                                                Save
                                                            </button>

                                                        </div>

                                                    </form>

                                                </div>

                                                <div class="row">

                                                    <?php foreach ($note->files as $file) {

                                                        $ext = pathinfo(CLOUDFRONT_URL . $file->file_key, PATHINFO_EXTENSION);

                                                        if ($ext == 'pdf') { ?>

                                                            <div class="col-xs-4 col-md-2 text-center">

                                                                <label><?= $file->file_name; ?></label><br>

                                                                <a href="<?= CLOUDFRONT_URL . $file->file_key; ?>"
                                                                   target="_blank">

                                                                    <i class="fa fa-file-code-o file-attach-icon"
                                                                       aria-hidden="true"></i>

                                                                </a>

                                                            </div>

                                                        <?php } else { ?>

                                                            <div class="col-xs-4 col-md-2 text-center">

                                                                <label><?= $file->file_name; ?></label><br>

                                                                <img src="<?= CLOUDFRONT_URL . $file->file_key; ?>"
                                                                     alt="<?= $file->file_name; ?>"
                                                                     class="img-responsive thumbnail files-thumbnail"
                                                                     onclick="displayFileModal(this)">

                                                            </div>

                                                        <?php }

                                                    } ?>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php }

                        } else { ?>

                            <div class="well property-note">

                                <p>No Notes to Display Yet...</p>

                            </div>

                        <?php } ?>

                    </div>
                    <div class="col-md-12">
                        <?= $pagination_links; ?>
                        <div class="dataTables_length">
                            <label><span>Show:</span>
                                <select name="per_page" onchange="property_notes_filter()">
                                    <?php foreach ($per_page_arr as $value) { ?>
                                        <option
                                            value="<?= $value ?>" <?= isset($filter['per_page']) && $filter['per_page'] == $value ? 'selected' : '' ?>><?= $value ?></option>
                                        <?php
                                    } ?>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>


            </div>

        </div>


        <!-- Notes Tab End -->

    </div>

</div>

<!-- /form horizontal -->


</div>

<!-- /content area -->


<div class="mydiv" style="display: none;">


</div>

<!-- add alert modal -->
<div id="modal_add_alert" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Alert</h6>
            </div>

            <form method="POST"
                  action="<?= base_url('admin/addAlert/') . $customer_id ?>"
                  class="alert-modal">

                <div class="form-group">
                    <label for="alert_type">Alert Type</label>
                    <select class="form-control" id="alert_type" name="alert_type">
                        <option selected> General</option>
                        <option> Payment</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="alert_text">Alert Text</label>
                    <input class="form-control" required type="text" id="alert_text" name="alert_text" maxlength="100"
                           size="100" spellcheck="true">
                </div>
                <div style="display: none;" class="form-group">
                    <label for="property">Choose Property</label>
                    <select class="form-control" id="property" name="property">
                        <option selected
                                value="<?php echo $propertyData['property_id'] ?>"> <?php echo $propertyData['property_title'] ?> </option>
                    </select>
                </div>
                <div class="form-group checkbox">
                    <label for="show_tech" class="control-label col-lg-9 text-left">Show on technician screen</label>
                    <input class="checkbox text-right" type="checkbox" id="show_tech" name="show_tech">
                </div>
                <div class="col">
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">
                            <i class="icon-plus22"></i> Add Alert
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- add alert modal -->

<!---  Add Service Modal --->

<div id="modal_add_service" class="modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h6 class="modal-title">Add Service</h6>

            </div>


            <form name="addService" method="post" enctype="multipart/form-data">


                <div class="modal-body">


                    <div class="form-group">

                        <div class="row">

                            <div class="col-sm-12">

                                <label>Add Service</label>


                                <select class="form-control" name="job_id" id="selected_job_id" required>

                                    <option value="">Select Any Service</option>

                                    <?php if ($servicelist) {

                                        foreach ($servicelist as $value) { ?>

                                            <option value="<?= $value->job_id ?>"><?= $value->job_name ?></option>

                                        <?php }

                                    } ?>

                                </select>

                                <input type="hidden" name="add_service_property_id"

                                       value="<?= $propertyData['property_id']; ?>">


                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-12">

                                <label>Pricing</label>

                                <select class="form-control" name="program_price" id="add_service_program_price"

                                        required>

                                    <option value="">Select Any Pricing</option>

                                    <option value=1>One-Time Service Invoicing</option>

                                    <option value=2>Invoiced at Service Completion</option>

                                    <option value=3>Manual Billing</option>

                                </select>


                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-12">

                                <label>Price Override Per Service</label>

                                <input type="number" class="form-control" min="0" step="0.01"

                                       name="add_job_price_override" value="">

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                        <button type="submit" id="addServiceSubmit" class="btn btn-success">Save</button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<!-------------------------------------------->

<!---  File Display Modal --->

<!-- <div id="modal_file_display" class="modal fade">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h6 class="modal-file-title"></h6>

      </div>

      <img id="modal-file-img" src="">

    </div>

  </div>

</div>       -->

<!-- Files Modal -->

<div id="file-display-modal" class="modal-files">

    <span class="close" id="close-file-display">&times;</span>

    <img class="modal-content" id="modal-file-image">

    <div id="caption"></div>

</div>

<!-------------------------------------------->
<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?libraries=geometry,drawing&key=<?php echo GoogleMapKey; ?>"></script>


<!-- Notes -->


<script>
    $(document).on("click", "#auto-assign-service-area", () => {

        var polygon_array = <?php print $polygon_bounds ? json_encode($polygon_bounds) : "";?>;

        polygon_array.forEach(elm => {

            var poly_draw = new google.maps.Polygon({
                paths: [JSON.parse(elm.latlng)]
            });
            const propertyInPolygon = google.maps.geometry.poly.containsLocation({
                lat: <?=$propertyData['property_latitude']?>,
                lng: <?=$propertyData['property_longitude']?>
            }, poly_draw) ? 1 : 0;
            if (propertyInPolygon) {
                $("select[name='property_area']").val(elm.property_area_cat_id);
            }
        });
    });

    // Note Files
    function formFileSizeValidate(form) {
        let fileEl = $(form).find('input[type="file"]').get(0);
        console.log(fileEl);
        let totalMbSize = 0;
        if (fileEl.files.length > 0) {
            for (let i = 0; i <= fileEl.files.length - 1; i++) {
                let mbSize = bytesToMb(fileEl.files[i].size);
                console.log(mbSize);
                totalMbSize += mbSize;
            }
            console.log(totalMbSize);
            if (totalMbSize > 5) {
                event.preventDefault();
                console.log('ERROR! File Upload Limit Exceeded!');
            } else {
                console.log('File Size Good!');
            }
        }
    }

    function fileValidationCheck(el) {
        let totalMbSize = 0;
        if (el.files.length > 0) {
            for (let i = 0; i <= el.files.length - 1; i++) {
                let mbSize = bytesToMb(el.files[i].size);
                console.log(mbSize);
                totalMbSize += mbSize;
            }
            console.log(totalMbSize);
            if (totalMbSize > 5) {
                $(el).next().text('file(s) exceed the max 5MB limit');
            } else {
                $(el).next().text('');
            }
        } else {
            $(el).next().text('');
        }
    }

    function bytesToMb(bytes) {
        if (bytes === 0) return 0;
        let mb = (bytes / (1024 * 1024));
        return mb;
    }

    function displayFileModal(imgEl) {

        let src = imgEl.src;

        let fileName = imgEl.alt;

        let modal = document.getElementById('file-display-modal');

        let modalImg = document.getElementById('modal-file-image');

        let captionText = document.getElementById('caption');

        modal.style.display = "block";

        modalImg.src = src;

        captionText.innerHTML = fileName;

        // $('#modal-file-title').text(fileName);

        // $('#model-file-img').attr('src', src);

        // $('#modal-file-display').dialog('open');

        // Get the <span> element that closes the modal

        var span = document.getElementById('close-file-display');


        // When the user clicks on <span> (x), close the modal

        span.onclick = function () {

            modal.style.display = "none";

        }

    }

</script>

<script>

    $(document).ready(function () {

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
        var technician_view = document.querySelector('.switchery_technician_view');
        var switchery_technician_view = new Switchery(technician_view, config);
        // Property Notes

        $('#properties-tabbtn-1').click(function (e) {

            if ($(this).hasClass('properties-tab-active') == false) {

                $('#properties-tabbtn-2').removeClass('properties-tab-active');

                $(this).addClass('properties-tab-active');

                $('#properties-tab-2').addClass('hidden');

                $('#properties-tab-1').removeClass('hidden');

                $("#addNoteBtn").hide(100);

                $("#note-form-wrap").collapse('hide');

            }

        });

        $('#properties-tabbtn-2').click(function (e) {
            if ($(this).hasClass('properties-tab-active') == false) {

                $('#properties-tabbtn-1').removeClass('properties-tab-active');

                $(this).addClass('properties-tab-active');

                $('#properties-tab-1').addClass('hidden');

                $('#properties-tab-2').removeClass('hidden');

                $("#addNoteBtn").show(100);

            }

        });

        $('#note-form-wrap').on('show.bs.collapse', () => {

            $('#addNoteBtnIco').removeClass('icon-plus22')

                .addClass('fa fa-minus');

        });

        $('#note-form-wrap').on('hide.bs.collapse', () => {

            $('#addNoteBtnIco').removeClass('fa fa-minus')

                .addClass('icon-plus22');

        });
        var front_yard = $('#front_yard_square_feet').val();

        front_yard = Number.isInteger(Number.parseInt(front_yard)) ? Number.parseInt(front_yard) : 0;


        if (front_yard == 0) {

            $("#front_yard_grass").prop('disabled', true);

        }


        var back_yard = $('#back_yard_square_feet').val();

        back_yard = Number.isInteger(Number.parseInt(back_yard)) ? Number.parseInt(back_yard) : 0;


        if (back_yard == 0) {

            $("#back_yard_grass").prop('disabled', true);

        }


        $("#front_yard_square_feet").keyup(function () {

            var first_yard = $('#front_yard_square_feet').val();

            first_yard = Number.isInteger(Number.parseInt(first_yard)) ? Number.parseInt(first_yard) : 0;

            var second_yard = 0;


            second_yard = $('#back_yard_square_feet').val();

            second_yard = Number.isInteger(Number.parseInt(second_yard)) ? Number.parseInt(second_yard) : 0;


            var total_yard = first_yard + second_yard;

            total_yard = Number.isInteger(Number.parseInt(total_yard)) ? total_yard : 0;


            $('#yard_square_feet').val(total_yard);


            if (first_yard == 0) {

                $("#front_yard_grass").prop('disabled', true);

            } else {

                $("#front_yard_grass").prop('disabled', false);

            }

        });


        $("#back_yard_square_feet").keyup(function () {

            var first_yard = $('#back_yard_square_feet').val();

            first_yard = Number.isInteger(Number.parseInt(first_yard)) ? Number.parseInt(first_yard) : 0;

            var second_yard = 0;


            second_yard = $('#front_yard_square_feet').val();

            second_yard = Number.isInteger(Number.parseInt(second_yard)) ? Number.parseInt(second_yard) : 0;


            var total_yard = first_yard + second_yard;

            total_yard = Number.isInteger(Number.parseInt(total_yard)) ? total_yard : 0;


            $('#yard_square_feet').val(total_yard);


            if (first_yard == 0) {

                $("#back_yard_grass").prop('disabled', true);

            } else {

                $("#back_yard_grass").prop('disabled', false);

            }

        });

    });

</script>


<script>

    // This example displays an address form, using the autocomplete feature

    // of the Google Places API to help users fill in the information.


    var placeSearch, autocomplete;

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

        autocomplete.addListener('place_changed', function () {

            fillInAddress(autocomplete, "");

        });


    }


    function fillInAddress(autocomplete, unique) {

        // Get the place details from the autocomplete object.

        var place = autocomplete.getPlace();


        $('.mydiv').html(place.adr_address);

        return_locality = $('.locality').text();

        return_region = $('.region').text();

        return_postal_code = $('.postal-code').text();

        res = return_postal_code.split("-");


        $('#locality' + unique).val(return_locality);

        $('#region' + unique).val(return_region);

        $('#postal-code' + unique).val(res[0]);

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


                //   alert(val);

            }

        }


    }

    google.maps.event.addDomListener(window, "load", initAutocomplete);


    function geolocate() {

        if (navigator.geolocation) {

            navigator.geolocation.getCurrentPosition(function (position) {

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

    var selectedValues = <?php echo json_encode($selectedValues) ?>;

    var selectedTexts = <?php echo json_encode($selectedTexts) ?>;

    var keyIds = <?php echo json_encode($keyIds) ?>;

    var optionValue = '';

    var optionText = '';

    $n = <?php echo $n; ?>;


    $(function () {
        reintlizeMultiselectprogramPriceOver();
        $('.multiselect-select-all-filtering3').multiselect();
        //tags
        $('.multiselect-select-all-filtering4').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: false,
            onInitialized: function (select, container) {
                $(".styled, .multiselect-container input").uniform({
                    radioClass: 'checker'
                });
            }
        });
    });


    function reintlizeMultiselectprogramPriceOver() {


        $(".multiselect-select-all-filtering2").multiselect('destroy');


        $('.multiselect-select-all-filtering2').multiselect({

            includeSelectAllOption: true,

            enableFiltering: true,

            enableCaseInsensitiveFiltering: true,

            includeSelectAllOption: false,

            templates: {

                filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'

            },


            onInitialized: function (select, container) {


                $(".styled, .multiselect-container input").uniform({

                    radioClass: 'checker'

                });


            },


            onSelectAll: function () {


                $.uniform.update();

            },


            onChange: function (option, checked, select) {


                if (checked) {


                    optionValue = $(option).val();


                    if (optionValue != '') {


                        if ($.inArray(optionValue, selectedValues) != '-1') {

                            // alert('already');


                        } else {


                            $('.program-price-over-ride-container').css("display", "block");


                            optionText = $(option).text();

                            // alert(optionValue);

                            //   alert(optionText);


                            selectedValues.push(optionValue);


                            keyIds.push({

                                'program_id': optionValue,

                                'price_override': 0,

                                'is_price_override_set': null

                            });


                            selectedTexts.push(optionText);


                            inputID = 'inpid' + $n;

                            var $row = $('<tr id="trid' + optionValue + '">' +

                                '<td>' + optionText + '</td>' +

                                '<td> <input type="number" name="tmp' + $n +

                                '" min="0"  step="0.01" class="inpcl form-control" optval="' + optionValue +

                                '"  ></td>' +

                                '</tr>');


                            $('.priceoverridetbody:last').append($row);

                            $n = $n + 1;

                            // $('#assign_program_ids').val(selectedValues);


                            $('#assign_program_ids2').val(JSON.stringify(keyIds));

                        }

                    }


                } else {


                    var id = $(option).val();

                    var optionValueRemove = $(option).val();

                    var optionTextRemove = $(option).text();


                    selectedValues.splice($.inArray(optionValueRemove, selectedValues), 1);


                    selectedTexts.splice($.inArray(optionTextRemove, selectedTexts), 1);


                    keyIds = $.grep(keyIds, function (e) {

                        return e.program_id != optionValueRemove;

                    });


                    $("#trid" + id).remove();


                    // $('#assign_program_ids').val(selectedValues);


                    $('#assign_program_ids2').val(JSON.stringify(keyIds));


                }

            }

        });

    }


    $(document).on("input", ".inpcl", function () {


        inputvalue = $(this).val();

        program_id = $(this).attr('optval');


        $.each(keyIds, function (key, value) {

            if (program_id == value.program_id) {

                keyIds[key].price_override = inputvalue;

                if (inputvalue != "") {

                    keyIds[key].is_price_override_set = 1;

                } else {

                    keyIds[key].is_price_override_set = null;

                }


            }

            // alert( key + ": " + value.program_id );

        });


        $('#assign_program_ids2').val(JSON.stringify(keyIds));


    });

    $('form[name="addService"] button[type="submit"]').on('click', function (e) {

        e.preventDefault();


        var serviceId = $('#selected_job_id').val();

        var propertyId = $('input[name="add_service_property_id"]').val();

        var serviceName = $('#selected_job_id option:selected').text();

        var propertyName = $('input[name="property_title"]').val();

        var programName = serviceName + "- Standalone";

        var programPrice = $('select#add_service_program_price').val();

        var priceOverride = $('input[name="add_job_price_override"]').val();


        if (priceOverride > 0) {

            var price_override_set = 1;

        } else {

            var price_override_set = 0;

        }

        var post = [];

        var property = {

            service_id: serviceId,

            property_id: propertyId,

            program_name: programName,

            program_price: programPrice,

            price_override: priceOverride,

            is_price_override_set: price_override_set

        }

        post.push(property);


        $.ajax({


            type: 'POST',

            url: "<?= base_url('admin/job/addJobToProperty') ?>",

            data: {

                post

            },

            dataType: "JSON",

            success: function (data) {


            }


        }).done(function (data) {

            $('#modal_add_service').modal('hide');

            if (data.status == "success") {


                swal(
                    'Success!',

                    'Service Added Successfully',

                    'success'
                ).then(function () {
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


    });

</script>

<script>

    function getNoteAssignUserUpdateVars(el) {

        let userId = $(el).val();

        let noteId = $(el).data('note-id');

        let noteOwnerId = $(el).data('note-userid');

        let userName = $(el.options[el.options.selectedIndex]).text();

        updateAssignUser(userId, noteId, userName);

    }

    function getNoteTypeUpdateVars(el) {

        let typeId = $(el).val();

        let noteId = $(el).data('note-id');

        let currentTypeId = $(el).data('note-typeid');

        let typeName = $(el.options[el.options.selectedIndex]).text();

        let idMatch = (typeId == currentTypeId);

        let serviceSpec = $(el.options[el.options.selectedIndex]).data('servicespecific');
        if (serviceSpec == true) {
            $('#update-notetype-services' + noteId).removeClass('hidden');
            $('#update-notetype-duration' + noteId).removeClass('hidden');
            $('#note_edit_assigned_services' + noteId).val('');
            $('#edit_assigned_service_note_duration' + noteId).val('');
        } else {
            $('#update-notetype-services' + noteId).addClass('hidden');
            $('#update-notetype-duration' + noteId).addClass('hidden');
            $('#note_edit_assigned_services' + noteId).val('');
            $('#edit_assigned_service_note_duration' + noteId).val('');
            updateAssignType(typeId, noteId, typeName, idMatch);
        }
    }

    function getNoteTypeUpdateServiceSpecificVars(el) {
        let noteId = $(el).data('note-id');
        let typeId = $('#note_edit_type_' + noteId).val();
        var assignedService = $('#note_edit_assigned_services' + noteId).val();
        let serviceName = $('#note_edit_assigned_services' + noteId + ' option:selected').text();
        var noteDuration = $('#edit_assigned_service_note_duration' + noteId).val();
        //console.log("Note ID: "+noteId+", Type ID: "+typeId+", Selected Service: "+assignedService+", Selected Service Name: "+serviceName+", Duration: "+noteDuration);
        if (assignedService != "" && noteDuration != "") {
            updateAssignTypeServiceSpecific(typeId, noteId, assignedService, serviceName, noteDuration);
        }
    }

    function showAssignUserSelect(noteId) {

        $(`#update-assignuser-${noteId}`).removeClass('hidden');

    }

    function showNoteTypeSelect(noteId) {

        $(`#update-notetype-${noteId}`).removeClass('hidden');

    }

    function updateAssignUser(userId, noteId, userName) {

        $.post("<?= base_url('admin/updateAssignUser'); ?>", {'noteId': noteId, 'userId': userId}, function (result) {

            $(`#note-assigned-user-wrap-${noteId}`).remove();

            if (userId != '') {

                $(`<span id="note-assigned-user-wrap-${noteId}"><span>Assigned to&nbsp;</span><span class="text-success text-bold"> ${userName}</span></span>`).insertBefore(`#update-assignuser-${noteId}`);

            }

            $(`#update-assignuser-${noteId}`).addClass('hidden');

        });

    }

    function updateAssignType(typeId, noteId, typeName, match) {

        $.post("<?= base_url('admin/updateAssignType'); ?>", {'noteId': noteId, 'typeId': typeId}, function (result) {

            $(`#note-assigned-type-wrap-${noteId}`).remove();
            $(`#note-assigned-service-wrap-${noteId}`).remove();
            $('#note_edit_type_' + noteId).data('note-typeid', typeId);
            if (!match) {

                $(`#note-assigned-type-wrapper-div-${noteId}`).prepend(`<span id="note-assigned-type-wrap-${noteId}" class="text-bold text-success" style="font-size: 1.2em">${typeName}</span>`);

            }

            $(`#update-notetype-${noteId}`).addClass('hidden');

        });

    }

    function updateAssignTypeServiceSpecific(typeId, noteId, assignedService, serviceName, noteDuration) {
        if (noteDuration == 1) {
            var durationName = "Permanent";
        } else {
            var durationName = "Next Service Only";
        }
        $.post("<?= base_url('admin/updateAssignTypeForServiceSpecific'); ?>", {
            'noteId': noteId,
            'typeId': typeId,
            'assignedService': assignedService,
            'noteDuration': noteDuration
        }, function (result) {
            $(`#note-assigned-type-wrap-${noteId}`).remove();
            $(`#note-assigned-service-wrap-${noteId}`).remove();
            $('#note_edit_type_' + noteId).data('note-typeid', typeId);
            $(`#note-assigned-type-wrapper-div-${noteId}`).prepend(`<span id="note-assigned-type-wrap-${noteId}" class="text-bold text-success" style="font-size: 1.2em">Service-Specific</span><span id="note-assigned-service-wrap-${noteId}" class="text-success" style="font-size: 1.2em"><br>${serviceName} (${durationName})</span>`);
            $(`#update-notetype-${noteId}`).addClass('hidden');
            $(`#update-notetype-services${noteId}`).addClass('hidden');
            $(`#update-notetype-duration${noteId}`).addClass('hidden');
        });
    }

    function addCommentAjax(noteId) {

        event.preventDefault();

        let comtVal = $(`#add-note-comment-form-${noteId} input[name="add-comment-input"]`).val().trim();

        if (comtVal != '') {

            let form = $(`#add-note-comment-form-${noteId}`)[0];

            let data = new FormData(form);

            $(`#add-note-comment-form-${noteId} input[name="add-comment-input"]`).val('');

            $.ajax({

                type: 'POST',

                enctype: 'multipart/form-data',

                url: '<?= base_url('admin/addNoteCommentAjax'); ?>',

                data: data,

                processData: false,

                contentType: false,

                cache: false,

                success: function (data) {

                    console.log("Success : ", data);

                    let result = JSON.parse(data);

                    console.log("Success : ", result);

                    let comment = `<li class="list-group-item comment-list-item">

                            <small class="text-muted">${result.timestamp}</small> <strong>${result.user_first_name} ${result.user_last_name}: </strong>${result.comment_body}

                          </li>`;

                    $(form).parent().before(comment);

                    $(`#comment-count-value-${result.note_id}`).text(`${result.comment_count}`);

                },

                error: function (e) {

                    console.log("ERROR : ", e);

                }

            });

        }

    }

    function showDueDateSelect(noteId) {

        $(`#note_due_date_${noteId}`).removeClass('hidden');

    }

    function updateNoteDueDate(el) {

        let noteId = $(el).data('noteid');

        let dueDate = $(el).val();

        if (dueDate != '') {

            $.post("<?= base_url('admin/updateNoteDueDate'); ?>", {
                'noteId': noteId,
                'dueDate': dueDate
            }, function (result) {

                $(`#note-duedate-${noteId}`).text(dueDate);

            });

        }

        $(el).val('').addClass('hidden');

    }

    function techVisibleSwtch(el, id) {

        let num = (el.checked) ? 1 : 0;

        $.post("<?= base_url('admin/updateNoteTechView'); ?>", {'noteId': id, 'tech_view': num}, function (result) {

            console.log(result);

        });

    }

</script>
<!-- Debug Var Dumps -->
<script>
    var currentUser = <?= print_r(json_encode($currentUser), TRUE); ?>;
    var notes = <?= print_r(json_encode($property_notes), TRUE); ?>;
</script>
<script>
    $(function () {
        var property_is_email = document.querySelector('.switchery-property-is-email');
        var switchery = new Switchery(property_is_email, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
        var property_is_text = document.querySelector('.switchery-is-text');
        var switchery = new Switchery(property_is_text, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });
    });

</script>
<script>
    $(document).ready(function () {
        $(".assignservicesedicustumer").hide();
    });
    $("#notetypenotcusedi").change(function () {
        var selected = $('#notetypenotcusedi option:selected').text();
        //alert(selected);
        if (selected == "Service-Specific") {
            $(".assignservicesedicustumer").show();
            $(".assignservicesedicustumer select").attr('required', true);
            $('#include_in_tech_view').prop('checked', true);
        } else {
            $(".assignservicesedicustumer").hide();
            $(".assignservicesedicustumer select").attr('required', false);
            $('#include_in_tech_view').prop('checked', false);
        }
    });
</script>


<script>

    $(document).ready(function () {

        var hovered = false;
        var inputFocus = false;


        $("#suggestion-box").bind("mouseover", function () {
            hovered = true;
        }).bind("mouseout", function () {
            hovered = false;
        });


        $("#customer_list_field").keyup(function () {

            if ($("#customer_list_field").val() == "" || $("#customer_list_field").val() == null) {
                $("#suggestion-box").hide();
                return;
            }

            var snippet = $(this).val();

            if (snippet.includes(', ')) {
                var snippetParts = $(this).val().split(", ");
                snippet = snippetParts.pop();

                snippet = snippet.split(" ");
                snippet = snippet.shift();

            } else {
                snippet = snippet.split(" ");
                snippet = snippet.shift();
            }


            if (snippet != "" && snippet != null) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('admin/assignCustomerList') ?>",
                    data: 'keyword=' + snippet,
                    /* beforeSend: function() {
                        $("#customer_list_field").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                    }, */
                    success: function (data) {
                        if (data != false) {
                            $("#suggestion-box").show();
                            $("#suggestion-box").html(data);
                            //$("#customer_list_field").css("background", "#FFF");


                            let customers = $('#customer_list').val();


                            for (let i = 0; i < customers.length; i++) {

                                var ul = document.getElementById("suggestion-box");
                                var items = ul.getElementsByTagName("li");

                                //console.log(items);

                                for (var k = 0; k < items.length; k++) {
                                    if (items[k].getAttribute("data-id") == customers[i]) {
                                        items[k].classList.add("selected");
                                    }
                                }

                            }

                        }
                    }
                });
            }
        });

        $("#customer_list_field").focusout(function () {
            inputFocus = false;


            if (!hovered) {
                $("#suggestion-box").hide();
            }


            $('#customer_list_field').val("");

            let selectedValues = $('#customer_list').val();

            for (let i = 0; i < selectedValues.length; i++) {
                $('#customer_list_field').val($('#customer_list_field').val() + $('#customer_list_div select option[value="' + selectedValues[i] + '"]').text() + ", ");

            }


        });

        $(document).mouseup(function (e) {
            var container = $("#suggestion-box");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                if (!inputFocus) {

                    container.hide();

                }
            }
        });


        $("#customer_list_field").focusin(function () {

            inputFocus = true;

            if ($("#customer_list_field").val() != "" && $("#customer_list_field").val() != null) {


                var snippet = $(this).val();

                if (snippet.includes(', ')) {
                    var snippetParts = $(this).val().split(", ");
                    snippet = snippetParts.pop();

                    snippet = snippet.split(" ");
                    snippet = snippet.shift();

                } else {
                    snippet = snippet.split(" ");
                    snippet = snippet.shift();
                }


                if (snippet != "" && snippet != null) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('admin/assignCustomerList') ?>",
                        data: 'keyword=' + snippet,
                        /* beforeSend: function() {
                                $("#customer_list_field").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                            }, */
                        success: function (data) {
                            if (data != false) {
                                $("#suggestion-box").show();
                                $("#suggestion-box").html(data);
                                //$("#customer_list_field").css("background", "#FFF");

                                let customers = $('#customer_list').val();


                                for (let i = 0; i < customers.length; i++) {

                                    var ul = document.getElementById("suggestion-box");
                                    var items = ul.getElementsByTagName("li");

                                    //console.log(items);

                                    for (var k = 0; k < items.length; k++) {
                                        if (items[k].getAttribute("data-id") == customers[i]) {
                                            items[k].classList.add("selected");
                                        }
                                    }

                                }


                            }
                        }
                    });
                }
            }

        });

        $('#note_status_filter').on('change', function (e) {
            property_notes_filter();
        });

        $('.page-link').on('click', function (e) {
            e.preventDefault();
            let page = parseInt($(this).text()) ? $(this).text() : $(this).attr('data-ci-pagination-page');
            property_notes_filter(page);
        });

    });

    function toggle_notification_to() {
        if ($('#is_enable_notifications').is(':checked')) {
            $('.toggle_notification_to').show();
        } else {
            $('.toggle_notification_to').hide();
        }
    }

    function changeNoteNotificationTo(e, id) {

        $.ajax({
            url: "<?= base_url('admin/updateNoteNotificationTo') ?>",
            data: {
                note_id: id,
                notification_to: $(e).parent().parent().find('select').val()
            },
            type: "POST",
            dataType: 'json',
            beforeSend: function () {
                $('#loading').css("display", "block");
            },
            success: function (response) {
                $('#loading').css("display", "none");
                if (!response.error) {
                    Swal(
                        'Notification Updated',
                        response.message,
                        'success'
                    )
                } else {
                    Swal(
                        'Notification Fail',
                        response.message,
                        'error'
                    )
                }
                $('.note_edit_notification_to_' + id).toggleClass('hidden');
            }

        });
    }

    function property_notes_filter(page = 1) {
        let note_status = $('#note_status_filter').val();
        $.ajax({
            type: 'POST',
            url: '<?= base_url(); ?>admin/ajaxPropertyNotes?page=' + page,
            data: {
                note_status: note_status,
                property_id: $('[name="property_id"]').val(),
                page: page,
                per_page: $('[name="per_page"]').val()
            },
            beforeSend: function () {
                $('#note_tab_contents').html('');
                $('#loading').css("display", "block");
            },
            success: function (html) {
                $("#loading").css("display", "none");
                $('#note_tab_contents').html(html);
            }
        });
    }

    //To select a customer name
    function selectCustomer(obj, val, billing, title, name) {

        if (obj.hasClass('selected')) {

            var SelectedCustNames = $('#customer_list_field').val().split(", ");

            SelectedCustNames.pop();

            for (let i = 0; i < SelectedCustNames.length; i++) {
                if (SelectedCustNames[i] == name) {
                    SelectedCustNames.splice(i, 1);
                }
            }

            $("#customer_list_field").val("");

            for (let i = 0; i < SelectedCustNames.length; i++) {
                if (i == 0) {
                    $("#customer_list_field").val(SelectedCustNames[i] + ", ");
                } else {
                    $("#customer_list_field").val($("#customer_list_field").val() + SelectedCustNames[i] + ", ");
                }
            }

            $('#customer_list_div select option[value="' + val + '"]').remove();


            obj.removeClass('selected');

        } else {
            let valueOfCustomerListField = $("#customer_list_field").val();

            if (valueOfCustomerListField == "" || !(valueOfCustomerListField.includes(', '))) {
                $("#customer_list_field").val(name + ", ");
            } else {

                $("#customer_list_field").val($("#customer_list_field").val() + name + ", ");
            }


            $('#customer_list_div select').append(`<option selected value="` + val + `" data-billingtype="` + billing + `" title="` + title + `">` + name + `</option>`);


            obj.addClass('selected');

        }


        var is_group_billing = 0;
        let customers = $('#customer_list').val();
        let selected_customers = JSON.stringify(customers);
        selected_customers = JSON.parse(selected_customers);
        $.each(selected_customers, function (key, customer) {
            var getOption = billing;
            if (getOption == 1) {
                is_group_billing = 1;
            }
        });
        if (is_group_billing == 1) {
            $('div.group_billing_contact_info').show();
        } else {
            $('div.group_billing_contact_info').hide();
        }
        $('input[name="is_group_billing"]').val(is_group_billing);


    }


    /* $('#customer_list').on('change',function(){
	var is_group_billing = 0;
	let customers = $(this).val();
	let selected_customers = JSON.stringify(customers);
	selected_customers = JSON.parse(selected_customers);
	$.each(selected_customers, function(key,customer){
		var getOption = $('#customer_list option[value='+customer+']').data('billingtype');
		if(getOption == 1){
			is_group_billing = 1;
		}
	});
	if(is_group_billing == 1){
		$('div.group_billing_contact_info').show();
	}else{
		$('div.group_billing_contact_info').hide();
	}
	$('input[name="is_group_billing"]').val(is_group_billing);

}); */
</script>
