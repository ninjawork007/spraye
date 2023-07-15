<script>
    $(document).ready(function () {
        $('#email_statment_button').click(function (e) {
            $(".email_statment").attr("required", "true");
            $(".email_statment").val("<?php echo $customerData['email'];?>");
            $('.generate_statment_modal_content').css({height: "385px"});
            $('.email_input_toggle').show();
            $('#email_statment_button').hide();
            $('#send_statment_button').show();
            $('#statement_form').removeAttr('target', '_blank');
            $('#statement_form').removeAttr('formtarget', '_blank');
        });
        $('#print_statment_button').click(function () {
            $('.generate_statment_modal_content').css({height: "305px"});
            $('.email_input_toggle').hide();
            $('#email_statment_button').show();
            $('#send_statment_button').hide();
            $(".email_statment").attr("required", "false");
            $('#statement_form').attr('target', '_blank');
            $('#statement_form').attr('formtarget', '_blank');
            $('.email_statment').val('');
            $('#statement_form').submit();

        });
        var aux = window.location;
        var aux2 = aux.toString().split("/").length;


        if (aux2 == 6) selectedProperty();


    });

</script>
<?php
$secondary_content_hieght = 10 + ((count($prop_programs) >= 6) ? 30 : count($prop_programs) * 6) + ((count($unscheduled) >= 6) ? 30 : count($unscheduled) * 6) + ((count($outstanding) >= 6) ? 30 : count($outstanding) * 6) + ((count($scheduled) >= 6) ? 30 : count($scheduled) * 6) + ((count($combined_notes) >= 4) ? 50 : count($combined_notes) * 9);

?>
<style type="text/css">

    .btndiv > * {
        margin-bottom: 4px;
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
    }

    .alerts-span {
        padding: 10px;

    }

    .alert-modal {
        padding: 2rem;
        font-size: 16px;
        height: 450px;
    }

    .alert-modal-checkbox {
        margin-left: -20px !important;
    }

    /* Disabled Menu Items */

    li.dropdown-menu-item.text-muted.dropdown-menu-item-icon.disabled > a {

        cursor: not-allowed;

        pointer-events: none;

    }

    /* End */

    .myspan {

        width: 55px;

    }


    .label-warning,
    .bg-warning {

        background-color: #A9A9A9;

        background-color: #A9A9AA;

        border-color: #A9A9A9;

    }


    .checkbox label,
    .radio label {

        padding-top: 0px !important;

    }


    .checkbox-inline .checker {

        top: 0px !important;

    }


    .adcustomerpropertydiv {

        float: left;

    }


    .addcustomeridinmodal {

        display: none;

    }


    .btn-group {

        margin-left: 4px !important;

    }


    .fa-file-pdf-o::before {

        font-family: fontAwesome;

        padding-right: 8px;

    }


    .table-spraye table#editcustmerpropertytbl {

        border: 1px solid #6eb1fd;

        border-radius: 4px;

    }


    .label-till,
    .bg-till {

        background-color: #36c9c9;

        background-color: #36c9c9;

        border-color: #36c9c9;

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


    .dash-tbl table#propertyDetails,
    .dash-tbl table#property2Details,
    .dash-tbl table#unassignedServices,
    .dash-tbl table#outstandingInvoices,
    .dash-tbl table#assignedPrograms,
    .dash-tbl table#scheduledServices,
    .dash-tbl table#qvNotes {

        border: 1px solid #F0F0F0 !important;
        border-radius: 6px !important;
        background-color: #FAFAFA;
        padding: 1% 2% 2% 1%;

    }

    #customerName {

        color: #01669A;

    }

    .tabbable {

        padding: 30px 0;

    }

    table#qvNotes thead tr th:last-child {

        width: 50%;

    }

    table#qvNotes tr td:last-child {

        max-width: 0;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

    }

    button#go_to_customer_btn {

        border-radius: 3px;

    }

    /* Note Form */

    div#note-form-wrap {

        margin-bottom: 2em;

    }

    .row.row-extra-space {

        margin-top: 10px;

        margin-bottom: 20px;

    }


    div.well.note-element {

        margin-bottom: 20px !important;

        border: 1px solid #ddd !important;

        border-radius: 3px !important;

        color: #333 !important;

        background-color: #fafafa !important;

        font-family: 'Roboto' !important;

    }

    div.well.note-element[is_urgent="1"] {
        border-radius: 3px !important;
        background-color: #FBE9E7 !important;
        border: 1px solid #FF5722 !important;
    }

    .row.note-body {

        font-size: 1.5rem;

        margin-bottom: 2rem;

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

    .text-muted, a.text-muted:hover, a.text-muted:focus {

        color: #acacac;

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

    button.properties-tab-active {

        background-color: #1d86d9 !important;

        border-color: #2196F3 !important;

        color: #fff !important;

    }

    button#addNoteBtn {

        background-color: #1c86d9;

    }

    button#addNoteBtn:hover, button#addNoteBtn:focus {

        color: #fff;

    }

    div#note-form-wrap2 {

        margin-bottom: 2em;
        border: 1px solid #F0F0F0;
        border-radius: 8px;
        padding: 2%;
        margin: 10px;


    }

    .customer-address.text-bold {

        color: #4a4a4a;

    }

    .file-attach-icon {

        font-size: 5em;

    }

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

    .modal-notes {

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

    .files-thumbnail {

        max-width: 150px;

        margin-left: auto;

        margin-right: auto;

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

    .scrollable {
        overflow-y: scroll;
        display: block;
        overflow: auto;
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

    .table-responsive {

        min-height: 0;

    }

    /* 100% Image Width on Smaller Screens */

    @media only screen and (max-width: 700px) {

        .modal-content {

            width: 100%;

        }

    }

    @media (max-width: 1024px) {

        div.go-to-customer div {

            margin-top: 0px !important;

        }

        button#go_to_customer_btn {

            padding: 9px 17px;

        }

    }

    @media (max-width: 768px) {

        .table-responsive {

            min-height: 0;

            margin-top: 10px;

            margin-bottom: 10px;

        }

    }

    @media (max-width: 768px) {

        .table-responsive {

            min-height: auto;

            margin-top: 10px;

            margin-bottom: 10px;

        }

    }

    /*custom radio for auto-send invoices*/
    .btn-group-custom .btn {
        border: 1px solid #ccc;
        border-radius: 6px;
        color: #333333;
        font-size: 14px;
        line-height: 1;
        padding: 6px 18px;
    }

    .btn-group-custom {
        margin-left: 50px !important;
    }

    .btn-group-custom .btn:not(:last-child) {
        border-right: none;
    }

    .btn-group-custom .btn.active,
    .btn-group-custom .btn:hover {
        background: #36C9C9;
        border-color: #36C9C9 !important;
        color: #fff;
    }

    .label-refunded, .bg-refunded {
        background-color: #fd7e14;
        border-color: #fd7e14;
    }

    .btn-warning-mod {
        background-color: #ffbe2c;
    }

    .properties-found-div {
        height: 50px;
        width: 100%;
        margin-left: 50%;
        transform: translateX(-50%);
        position: relative;
        z-index: 1;
    }

    .center {
        top: 50%;
        transform: translateY(-50%);
    }

    .customer-select {
        background-color: #fafafa;
    }

    .left-column-property-details {
        font-weight: 500;
        width: 30%;
    }

    .color-grey {
        color: #00000080;
    }

    .table2 > thead > tr > th {
        border-bottom: 1px solid #6eb1fd00;
        font-size: 14px;
    }

    .table2 > tfoot > tr > th {
        border-top: 1px solid #6eb1fd00;
    }

    .customer-view-link {
        color: #00000080;
        font-weight: 500;
    }

    .color-light {
        color: #00000060;
        font-weight: 400;
    }

    .due_tag {
        color: #FD7B38;
    }

    .status-label {
        background-color: #F0F8EF;
        color: #60B158;
        height: 3rem;
        width: auto;
        text-align: center;
        font-size: 15px;
        border-radius: 6px;
        padding: 3px;
    }

    .balance-green-label {
        text-align: center;
        width: 10%;
        font-weight: 500;
        background-color: #60B158;
        color: #FAFAFA;
        border: 1px solid #F0F0F0 !important;
        text-align: center;
        font-size: 15px;
        border-radius: 0 5px 5px 0 !important;
        padding: 12px;
    }

    .balance-grey-label {
        text-align: center;
        font-weight: 500;
        width: 10%;
        background-color: #FAFAFA !important;
        border: 1px solid #F0F0F0 !important;
        border-radius: 5px 0 0 5px !important;
        font-size: 15px;
        color: #030229;
        padding: 12px;
    }

    .div-account-number {
        width: 70%;
        display: inline-flex;
        justify-content: right;
    }

    .account-number-label {
        text-align: center;
        width: 20%;
        background-color: #CFDEE8 !important;
        font-size: 15px;
        color: #00000070;
        padding: 12px;
        margin-top: 5px;
    }

    .see-more-icon {
        display: none;
        color: #00000080;
    }

    .see-more-icon:hover {
        color: #166dba;
    }

    .flex-div-data {
        display: flex;
        flex-flow: column wrap;
        height: <?=$secondary_content_hieght ?>vh;
        width: 100%;
        #justify-content: space-evenly;
        margin-top: 10px;
        min-height: 500px;;
    }

    .flex-div-data > * {
        flex-basis: content;
        padding: 5px;
        margin: 5px;
    }

    .flex-div-data > * {
        flex-basis: content;
        padding: 5px;
        margin: 5px;
    }

    @media (max-width: 900px) {

        .account-number-label {
            width: auto;
        }

        .div-account-number {
            width: 100%;

        }

        .see-more-icon {
            display: inherit;
        }

        .see-more-text {
            display: none;
        }

        .table-responsive > .table > tbody > tr > td, .table-responsive > .table > tbody > tr > th, .table-responsive > .table > tfoot > tr > td, .table-responsive > .table > tfoot > tr > th, .table-responsive > .table > thead > tr > td, .table-responsive > .table > thead > tr > th {
            white-space: normal;
        }

        .td_grey {
            width: 50%;
        }

        .td_blue {
            width: 50%;
        }

        .flex-div-data {
            flex-flow: wrap !important;
            height: <?= $secondary_content_hieght + 100?>vh !important;
            margin: 0;
        }

    }

    @media (max-width: 510px) {

        .flex-div-data {
            flex-flow: wrap !important;
            height: <?= $secondary_content_hieght + 150?>vh !important;
        }

        .div-outs-inv {
            flex: 1 0 0 !important;
        }

    }

    #highlighted-justified-tab0 {
        font-family: Roboto, Helvetica Neue, Helvetica, Arial, sans-serif;
    }

    .table2 > thead > tr > th {
        vertical-align: bottom;
    }

    .table-responsive2 {
        overflow-x: hidden;
        min-height: 200px;
    }

    .table-spraye2 thead {
        background: #00000000;
    }

    thead.customer, tbody.scrollable tr {
        display: table;
        width: 100%;
        table-layout: fixed; /* even columns width , fix width of table too*/
    }

    #propertyDetails {

        font-size: 1.6rem;
    }

    .pencil-link {
        color: #00000080;
    }

    .td_prop_details_2 {
        text-align: start !important;
        padding: 12px 27px !important;
        font-size: 1.7rem;
    }

    .div-prop-det {
        width: 50%;
    }

    .div-assi-prog {
        margin-top: 10px;
    }

    .div-prop-det2 {
        width: 50%; /*flex:1 0 130px;*/
    }

    .div-outs-inv {
        margin-top: 10px;
    }

    .div-uns-ser {
        margin-top: 10px;
    }

    .div-sch-ser {
        margin-top: 10px;
    }

    .div-notes {
        margin-top: 10px;
    }

    .table#propertyDetails > tbody > tr > td {
        line-height: 1;
    }

    /*@media (max-width: 900px) {*/
    /*    .div-prop-det{order: 0}*/
    /*    .div-assi-prog{order: 2 }*/
    /*    .div-prop-det2{order: 1}*/
    /*    .div-outs-inv{order: 3}*/
    /*    .div-uns-ser{order: 4}*/

    /*}*/


    .prop-status-2 {
        color: #01669A !important;
    }

    .prop-status-0 {
        color: red
    }

    .prop-status- {
        color: red !important;
    }

    .cus-status-0 {
        color: red !important;
    }

    .cus-status-2 {
        color: red !important;
    }

    .cus-status-3 {
        color: #01669A !important;
    }

    .cus-status-4 {
        color: #01669A !important;
    }

    .cus-status-5 {
        color: #01669A !important;
    }

    .cus-status-7 {
        color: #01669A !important;
    }

    .required {
        color: #c90000;
    }

    .bit-bolder {
        font-weight: 500;
    }

</style>

<style>
    .PropertyListField {
        cursor: pointer;
        width: 100%;
    }

    .PropertyListField.selected {
        cursor: pointer;
        width: 100%;
        background-color: #bbb;
    }

    .PropertyListField:hover {
        background-color: #ccc;
    }
</style>
<style>
    .customerListField {
        cursor: pointer;
        width: 100%;
    }

    .customerListField:hover {
        background-color: #ccc;
    }

    #suggestion-box2 {
        color: #000;
    }

    .isCanceled {
        text-decoration: line-through;
        color: gray;
    }

    .asap_row {
        background: #FBE9E7 !important;
        border: 1px solid #FF5722;
    }

    .skipped_row {
        color: #8080804f !important;
    }

    .label-skipped {
        background-color: #868686 !important;
    }

    .wrapper_pagination {
        padding: 0 0 50px 10px;
    }

    li.dropdown-menu-item.text-muted.dropdown-menu-item-icon.disabled {
        pointer-events: none;
    }
</style>

<div class="content">
    <div class="">
        <div class="mymessage"></div>
        <b><?php if ($this->session->flashdata()) {
                echo $this->session->flashdata('message');
            } ?></b>
    </div>

    <!-- Form horizontal -->

    <div class="panel panel-flat">

        <div class="panel-heading">

            <h5 class="panel-title">

                <div class="form-group">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="btndiv col-md-8 ">

                                <a href="<?= base_url('admin/customerList') ?>" id="save" class="btn btn-success"><i
                                            class=" icon-arrow-left7"> </i> Back to All Customers</a>


                                <!-- <a href="<?= base_url('admin/invoices/getOpenInvoiceByCustomer/') . $customerData['customer_id'] ?>"  id="" class="btn btn-warning" target="_blank"  ><i class=" icon-file-pdf"> </i> Generate Statement</a> -->


                                <button type="button" class="btn btn-warning-mod"

                                        data-target="#modal_statement_email" data-toggle="modal"><i

                                        class=" icon-file-pdf"> </i> Generate Statement
                                </button>

                                <button type="button" class="btn btn-primary" id="updatePayment"

                                        data-target="<?php if ($cardconnect_details && $cardconnect_details->status == 1) { ?> #clover_update_payment <?php } elseif ($basys_details && $basys_details->status == 1) { ?> #modal_update_payment <?php } ?>"

                                        data-toggle="modal"

                                    <?php if (!$basys_details && !$cardconnect_details) { ?> disabled

                                    <?php } else if (($customerData['clover_autocharge'] != 1 && $customerData['basys_autocharge'] != 1)) { ?>

                                        disabled <?php } ?>><i class=" icon-plus22"></i> Update Payment Method
                                </button>

                                <button type="button" class="btn btn-primary" id="add_alert_btn"
                                        data-target="#modal_add_alert" data-toggle="modal">
                                    <i class=" icon-plus22"> </i> Add Alert
                                </button>
                                <button type="button" class="btn btn-success" id="add_credit_btn"
                                        data-target="#modal_add_credit" data-toggle="modal">
                                    <i class=" icon-plus22"> </i> Add Credit
                                </button>

                                <a href="<?= base_url('admin/Estimates/addEstimate?customer_id=') . $customerData['customer_id'] ?>"
                                   type="button" class="btn btn-success">
                                    <i class="icon-plus22"> </i> New Estimate
                                </a>

                                <button type="button" class="btn btn-info" id="addServiceButton" onclick="$('#modal_add_service').modal('show')">
                                    <i class=" icon-plus22"></i>
                                    Add Standalone Service
                                </button>
                            </div>

                            <div class="form-group col-md-4 col-xs-12 go-to-customer"
                                 style="float:right; display:inline-block;">

                                <!--<label class="control-label col-md-4">Select a Customer</label>-->

                                <div class="col-md-10 col-xs-10" style="min-width:200px;">


                                    <input autocomplete="off" type="text" class="form-control" id="go_to_customer_field"
                                           placeholder="Select any customer"/>
                                    <div
                                        style="z-index: 999; width: 100%; display: none; position: absolute; left: 0px; top: 40px; background-color: #ffffff; overflow-y: scroll; height: 25em; max-height: 25em;"
                                        id="suggestion-box2"></div>

                                    <input type="text" style="display: none !important;" id="go_to_customer"
                                           name="go_to_customer"/>


                                </div>

                                <div class="col-md-2 col-xs-2">

                                    <button class="btn btn-primary" onclick="goToCustomer()"
                                            id="go_to_customer_btn">Go
                                    </button>

                                </div>

                            </div>

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

            <div id="customerName">
                <div style="width: 100%; display: none/*inline-flex*/; justify-content: right;">
                    <div class="balance-grey-label" style="font-weight: 500; ">Balance</div>
                    <div class="balance-green-label"></div>
                </div>

                <h2 style="font-weight: 500"><?php echo $customerData['first_name'] . " " . $customerData['last_name'] ?>
                    <!--                    <span  style="font-weight: 500; font-size: 15px; margin-left: 2%; color: black">Status <label class="status-label cus-status--->
                    <? //=$customerData['customer_status']?><!--" >-->
                    <? //= ($customerData['customer_status'] == 1)?'Active':(($customerData['customer_status'] == 2)?'Hold':'Non-Active')?><!--</label>-->
                    <!--                    <span  style="font-weight: 500; font-size: 15px; margin-left: 2%; color: black">Status <label class="status-label cus-status--->
                    <? //=$customerData['customer_status']?><!--" >-->
                    <? //= ($customerData['customer_status'] == 1)?'Active':(($customerData['customer_status'] == 2)?'Prospect':'Non-Active')?><!--</label>-->
                    <span style="font-weight: 500; font-size: 15px; margin-left: 2%; color: black">Status <label
                            class="status-label cus-status-<?= $customerData['customer_status'] ?>">
                            <?= ($customerData['customer_status'] == 1) ? 'Active' : (($customerData['customer_status'] == 2) ? 'Hold' : (($customerData['customer_status'] == 4) ? 'Sales Call Scheduled' : (($customerData['customer_status'] == 5) ? 'Estimate Sent' : (($customerData['customer_status'] == 6) ? 'Estimate Declined' : (($customerData['customer_status'] == 7) ? 'Prospect' : 'Non-Active')))))
                            ?></label>
                    </span>
                    <div class="div-account-number">
                        <div class="account-number-label"><span
                                style="font-weight: 500; color: black;margin-right: 5px">Account #</span> <?= @$customer_properties[0]->customer_id ?>
                        </div>
                    </div>
                </h2>


            </div>

            <?php if (isset($alerts) && Count($alerts) > 0) { ?>
                <div class="alerts">
                    <?php foreach ($alerts

                    as $index => $alert) { ?>
                    <span class="alerts-span"><?php echo $alert->text;
                        if ($alert->show_tech == 1) echo ' <span class="badge badge-info" style="margin-left: 10px;">Tech Visible </span>';
                        echo '</span><a style="color: #9c1f1f;text-align: end" class="btn" href="' . base_url('admin/removeCustomerAlert/') . $index . '-' . $customerData['customer_id'] . '"> ';
                        echo '<i class="icon-trash-alt"> </i></a>' ?>
                        <?php } ?>
                </div>
            <?php } ?>
            <div class="tabbable">

                <ul class="nav nav-tabs nav-tabs-solid nav-justified">

                    <li class="liquick <?php echo $active_nav_link == '0' ? 'active' : '' ?> "><a

                                href="#highlighted-justified-tab0" data-toggle="tab">Quickview</a></li>

                    <li class="lione <?php echo $active_nav_link == '1' ? 'active' : '' ?> "><a

                            href="#highlighted-justified-tab1" data-toggle="tab">Billing Profile</a></li>

                    <li class="litwo <?php echo $active_nav_link == '2' ? 'active' : '' ?>"><a

                                href="#highlighted-justified-tab2" data-toggle="tab">Services</a></li>

                    <li class="lithree <?php echo $active_nav_link == '3' ? 'active' : '' ?>"><a

                                href="#highlighted-justified-tab3" data-toggle="tab">Invoices</a></li>

                    <li class="lifour <?php echo $active_nav_link == '4' ? 'active' : '' ?>"><a

                                href="#highlighted-justified-tab4" data-toggle="tab">Properties</a></li>

                    <li class="lifive <?php echo $active_nav_link == '5' ? 'active' : '' ?>"><a
                            href="#highlighted-justified-tab5" data-toggle="tab" id="note_tab_btn">Notes</a></li>

                </ul>


                <div class="tab-content">
                    <!-- QUICKVIEW  TAB-->
                    <div class="tab-pane <?php echo $active_nav_link == '0' ? 'active' : '' ?>"
                         id="highlighted-justified-tab0">

                        <!-- Customer Property selection -->
                        <div class="row properties-found-div ">

                            <div class="col-md-1 col-xs-1 center" hidden>

                                <span class="control-label col-md-4" style="font-size: 16px; font-weight: 500; ">Property</span>

                            </div>
                            <div class="col-md-6 col-xs-8 center" style="min-width:200px; ">

                                <select class="bootstrap-select form-control customer-select" data-live-search="true"
                                        onchange="selectedProperty()"

                                        name="selectProperty" id="selectProperty">

                                    <!--                            <option value="">Select a Property</option>-->

                                    <?php
                                    $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

                                    if (!empty($customer_properties)) {

                                        foreach ($customer_properties as $key => $value) {

                                            echo '<option value="' . $value->property_id . '"';
                                            if (isset($uriSegments[4]) && $uriSegments[4] == $value->property_id) echo 'selected';
                                            echo '>' . $value->property_title . ' - ' . $value->property_address . '</option>';

                                        }
                                    }
                                    ?>

                                </select>


                            </div>
                            <div class="col-md-2 col-xs-4 center">

                                <span><?php echo count($customer_properties) . ' properties found'; ?></span>

                            </div>

                        </div>
                        <br>

                        <!-- All tables -->
                        <div class="row">
                            <!-- Property Details Programs -->
                            <div class="col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-12">
                                        <div
                                            class="table-responsive table-spraye table-responsive2 table-spraye2 dash-tbl">

                                            <table class="table table2  dataTable" id="propertyDetails" role="grid">
                                                <thead>
                                                <tr role="row">
                                                    <th colspan="4" class="left-column-property-details"><span
                                                            class="text-semibold" style="">Property Details</span>
                                                    </th>

                                                    <th class="color-grey"><span
                                                            style="font-weight: 500; font-size: 15px">Status <label
                                                                class="status-label prop-status-<?= @$customer_property->property_status ?>"><?= (@$customer_property->property_status == 1) ? 'Active' : ((@$customer_property->property_status == 2) ? 'Prospect' : ((@$customer_property->property_status == 4) ? 'Sales Call Scheduled' : ((@$customer_property->property_status == 5) ? 'Estimate Sent' : ((@$customer_property->property_status == 6) ? 'Estimate Decline' : 'Non-Active')))) ?></label></span>
                                                    </th>
                                                    <th class="text-right" style="vertical-align: top;"><a
                                                            class='pencil-link'
                                                            href="<?= base_url("admin/editProperty/") . @$customer_property->property_id ?>"><i
                                                                class="icon-pencil"></i> </a></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr role="row">
                                                    <td colspan="3" class="left-column-property-details">Address</td>
                                                    <td colspan="3"
                                                        class="color-grey"><?= (isset($customer_property->property_address)) ? $customer_property->property_address : ''; ?></td>
                                                </tr>
                                                <tr role="row">
                                                    <td colspan="3" class="col-12 left-column-property-details">Property
                                                        Type
                                                    </td>
                                                    <td colspan="3"
                                                        class="col-12 color-grey"><?= (isset($customer_property->property_type)) ? $customer_property->property_type : '' ?></td>
                                                </tr>
                                                <tr role="row">
                                                    <td colspan="3" class="left-column-property-details">Email</td>
                                                    <td colspan="3"
                                                        class="color-grey"><?= (isset($customerData['email'])) ? $customerData['email'] : print_r() ?></td>
                                                </tr>
                                                <tr role="row">
                                                    <td colspan="3" class="left-column-property-details">Phone</td>
                                                    <td colspan="3"
                                                        class="color-grey"><?= ($customerData['phone'] > 0 ? 'M: ' . formatPhoneNum($customerData['phone']) : '') . ($customerData['home_phone'] > 0 ? '  H: ' . formatPhoneNum($customerData['home_phone']) : '') . ($customerData['work_phone'] > 0 ? '  W: ' . formatPhoneNum($customerData['work_phone']) : '') ?></td>
                                                </tr>
                                                <tr role="row">
                                                    <td colspan="3" class="left-column-property-details">Property Info
                                                    </td>
                                                    <td colspan="3"
                                                        class="color-grey"><?= (isset($customer_property->property_notes)) ? $customer_property->property_notes : '' ?></td>
                                                </tr>
                                                <tr role="row">
                                                    <td colspan="3" class="left-column-property-details">Source</td>
                                                    <td colspan="3"
                                                        class="color-grey"><?= (isset($customer_property->source)) ? $customer_property->source_name: 'None' ?></td>
                                                </tr>
                                                <tr role="row">
                                                    <td colspan="3" class="left-column-property-details">Customer
                                                        Billing Type
                                                    </td>
                                                    <td colspan="3"
                                                        class="color-grey"><?= (isset($customerData['billing_type'])) ? ($customerData['billing_type'] == 0) ? 'Standard' : 'Group Billing' : ''; ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Property Details 2-->
                            <div class="col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-12">
                                        <div class="table-responsive table-spraye table-responsive2 table-spraye2 "
                                             style="min-height: 100px!important;">

                                            <table class="table table2 dataTable" id="property2Details" role="grid">
                                                <style>
                                                    .td_grey {
                                                        background-color: #FAFAFA;
                                                        border: 1px solid #F0F0F0 !important;
                                                        border-radius: 5px 0 0 5px !important;
                                                        text-align: center;
                                                        font-weight: 500;
                                                        width: 50%;
                                                        font-size: 1.5rem;
                                                    }

                                                    .td_blue {
                                                        background-color: #01669A;
                                                        #border: 1px solid #F0F0F0 !important;
                                                        border-radius: 0 5px 5px 0 !important;
                                                        color: #FAFAFA;
                                                        text-align: center;
                                                        font-weight: 500;
                                                        width: 50%;
                                                        font-size: 1.5rem;
                                                    }

                                                    .label-quick {
                                                        font-size: 1.5rem;
                                                    }

                                                    .spacer {
                                                        height: 20px;

                                                    }

                                                    .condition-span {
                                                        background-color: #E6F2FF;
                                                        color: #01669A;
                                                        border-radius: 3px;
                                                        min-height: 20px;
                                                        margin-right: 10px;
                                                        padding: 6px;

                                                    }

                                                    .discount-span {
                                                        margin-right: .5rem;

                                                    }

                                                    .table2 > row {
                                                        margin-bottom: 10px;
                                                    }
                                                </style>
                                                <tbody class="table2">
                                                <!--                                                <tr style="height: 3.4rem"></tr>-->
                                                <?php if (isset($customerData['created_at'])) { ?>

                                                    <tr role="row">
                                                        <td class="td_grey td_prop_details_2">Start Date</td>
                                                        <td class="td_blue"><?= date("Y-m-d", strtotime($customerData['created_at'])) ?></td>
                                                    </tr>
                                                    <tr class="spacer"></tr>
                                                <?php } ?>
                                                <tr role="row">
                                                    <td class="td_grey td_prop_details_2">Service Area</td>
                                                    <td class="td_blue"><?= (isset($all_services[0]->category_area_name)) ? $all_services[0]->category_area_name : '' ?></td>

                                                </tr>
                                                <tr class="spacer"></tr>
                                                <tr role="row">
                                                    <td class="td_grey td_prop_details_2">Call Ahead</td>
                                                    <td class="td_blue"><?php
                                                        //var_dump($customerData['pre_service_notification']);
                                                        if (strpos($customerData['pre_service_notification'], "1") !== false) echo "<div class='label label-primary myspan m-y-1 label-quick' style=' padding: 0 2px; margin-right: 0.5rem'>Call</div>";
                                                        if (strpos($customerData['pre_service_notification'], "4") !== false) echo "<div class='label label-success myspan label-quick' style=' padding: 0 2px; margin-right: 0.5rem'>Text ETA</div>";
                                                        if (strpos($customerData['pre_service_notification'], "2") !== false || strpos($customerData['pre_service_notification'], "3") !== false) echo "<div class='label label-info myspan label-quick' style=' padding: 0 2px; margin-right: 0.5rem'>Pre-Notified</div>";

                                                        if (strpos($customerData['pre_service_notification'], "1") === false &&
                                                            strpos($customerData['pre_service_notification'], "4") === false &&
                                                            strpos($customerData['pre_service_notification'], "2") === false &&
                                                            strpos($customerData['pre_service_notification'], "3") === false) echo "<div class='label label-success myspan label-quick'>None</div>"; ?></td>
                                                </tr>
                                                <tr class="spacer"></tr>
                                                <tr role="row">
                                                    <td class="td_grey td_prop_details_2">Discount</td>
                                                    <td class="td_blue"><?php
                                                        if (!empty($coupon_customers)) {

                                                            foreach ($coupon_customers as $value) {
                                                                $now = date('Y-m-d');
                                                                if ($value->expiration_date == '0000-00-00 00:00:00' || date('Y-m-d', strtotime($value->expiration_date)) <= $now) {
                                                                    echo '<span class="discount-span">' . $value->code . '</span>';
                                                                }
                                                            }
                                                        }
                                                        ?></td>

                                                </tr>
                                                <tr class="spacer"></tr>
                                                <tr role="row">
                                                    <td class="td_grey td_prop_details_2">Conditions</td>
                                                    <td class="td_blue">
                                                        <?php

                                                        if (!empty($propertyselectedconditions)) {
                                                            foreach ($propertyselectedconditions as $key => $value) {
                                                                echo '<span class="condition-span">' . $value->condition_name . '</span>';
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr class="spacer"></tr>
                                                <tr role="row">
                                                    <td class="td_grey td_prop_details_2">Custom Tags</td>
                                                    <td class="td_blue">
                                                        <?php
                                                        $tag_lists = explode(',', @$customer_property->tags);


                                                        $tagHtml = '<div class="wrapper-tags" style="padding-top:5px">';
                                                        if (!empty($tag_lists)) {
                                                            // print_r($tag_lists);
                                                            foreach ($taglist as $tag) {
                                                                // print_r($tag);
                                                                $tag_name = $tag->tags_title;

                                                                if (isset($tag) && in_array($tag->id, $tag_lists)) {

                                                                    if (isset($tag_name) && $tag_name == "New Customer") {
                                                                        // die(print_r($tag_name));
                                                                        $tagHtml .= '<span class="badge badge-success">' . $tag_name . '</span>&nbsp;&nbsp;';
                                                                    } else {
                                                                        $tagHtml .= '<span class="badge badge-primary">' . $tag_name . '</span>&nbsp;&nbsp;';
                                                                    }
                                                                } else {
                                                                    $tagHtml .= '';
                                                                }
                                                            }
                                                        }
                                                        $tagHtml .= '</div>';

                                                        print_r($tagHtml);
                                                        ?>

                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Assigned Program -->
                            <div class="col-md-6 col-sm-12">
                                <div class="div-assi-prog">
                                    <div class="table-responsive table-spraye table-responsive2 table-spraye2 dash-tbl">
                                        <table class="table table2 dataTable" id="assignedPrograms" role="grid">
                                            <thead class="customer">
                                            <tr>
                                                <th style="width: 50%"><span class="text-semibold">Assigned Programs</span>
                                                </th>
                                                <th class="text-right"
                                                    style="vertical-align: middle;width: 50%"><?php echo '<a href="#highlighted-justified-tab2" data-toggle="tab" onclick="openTab(\'litwo\')"><i class="see-more-icon icon-list"></i><span class="see-more-text">See more</span></a>' ?></th>

                                            </tr>
                                            <tr role="row">
                                                <th>Program Name</th>
                                                <th>Sales Rep</th>
                                            </tr>
                                            </thead>
                                            <tbody class="scrollable" style="max-height: 35vh;">

                                            <?php
                                            if (isset($prop_programs)) {

                                                foreach ($prop_programs as $k => $program) {
                                                    ?>


                                                    <tr role="row">

                                                        <td><?php echo '<a  class="customer-view-link" href="' . base_url('admin/editProperty/') . $program['property_id'] . '" target="_blank">' . $program['program_name'] . '</a>' ?></td>
                                                        <td><?php echo '<a  class="customer-view-link" onclick="salesRepNote(' . $program['id_sales_rep'] . ')">' . $program['sales_rep'] . '</a>' ?></td>


                                                    </tr>


                                                <?php }
                                            } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Unscheduled Services -->
                            <div class="col-md-6 col-sm-12">
                                <div class="div-uns-ser">
                                    <div class="table-responsive table-spraye table-responsive2 table-spraye2 dash-tbl">
                                        <table class="table table2 dataTable" id="unassignedServices" role="grid">
                                            <thead class="customer">
                                            <tr>
                                                <th colspan="2"><span class="text-semibold" style="">Unscheduled Services</span>
                                                </th>
                                                <th class="text-right"
                                                    style="vertical-align: middle;"><?php echo '<a href="#highlighted-justified-tab4" data-toggle="tab" onclick="openTab(\'lifour\')"><i class="see-more-icon icon-list"></i><span class="see-more-text">See more</span></a>' ?></th>
                                                
                                            </tr>
                                            <tr role="row">
                                                <th>Property Name</th>
                                                <th>Service Name</th>
                                                <th>Program Name</th>
                                            </tr>
                                            </thead>
                                            <tbody class="scrollable" style="max-height: 35vh">
                                            <?php if (isset($unscheduled)) {
                                                foreach ($unscheduled as $k => $uService) {
                                                    ?>
                                                    <tr role="row">
                                                        <td><?php echo '<a class="color-light" href="' . base_url('admin/editProperty/') . $uService->property_id . '" target="_blank">' . $uService->property_title . '</a>' ?></td>
                                                        <td><?php echo '<a class="color-light" href="' . base_url('admin/job/editJob/') . $uService->job_id . '" target="_blank">' . $uService->job_name . '</a>' ?></td>
                                                        <td><?php echo '<a class="color-light" href="' . base_url('admin/job/editJob/') . $uService->job_id . '" target="_blank">' . $uService->program_name . '</a>' ?></td>
                                                    </tr>
                                                <?php }
                                            } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Outstanding Invoices -->
                            <div class="col-md-6 col-sm-12">
                                <div class="div-outs-inv">
                                    <div class="table-responsive table-spraye table-responsive2 table-spraye2 dash-tbl">

                                        <table class="table table2 dataTable" id="outstandingInvoices" role="grid">

                                            <thead class="customer">
                                            <tr>
                                                <th colspan="2"><span class="text-semibold"
                                                                    style="">Outstanding Invoices</span></th>

                                                <th class="text-right"
                                                    style="vertical-align: middle;"><?php echo '<a href="#highlighted-justified-tab3" data-toggle="tab" onclick="openTab(\'lithree\')"><i class="see-more-icon icon-list"></i><span class="see-more-text">See more</span></a>' ?></th>
                                            </tr>


                                            <tr role="row">

                                                <th style="width: 33%">Invoice #</th>

                                                <th style="width: 33%">Amount Due</th>

                                                <th style="width: 33%">Invoice Date</th>

                                            </tr>

                                            </thead>

                                            <tbody class="scrollable" style="max-height: 29vh">

                                            <?php if (isset($outstanding)) {

                                                foreach ($outstanding as $k => $inv) {
                                                    ?>

                                                    <tr role="row">

                                                        <td><?php echo '<a class="color-light" href="' . base_url('admin/Invoices/editInvoice/') . $inv['invoice_id'] . '" target="_blank">#' . $inv['invoice_id'] . '</a>' ?>

                                                        </td>

                                                        <td class="color-light"><?= $inv['amount_due'] ?></td>

                                                        <td class="color-light"><?= (strtotime(date('d-m-Y', strtotime($inv['due_date']))) < strtotime(date('d-m-Y'))) ? "<span class='due_tag'> <i class='icon-flag3'></i> <span >DUE</span> " : '<span>'; ?><?php echo date('m-d-Y', strtotime($inv['due_date'])) . '</span>'; ?></td>

                                                    </tr>

                                                <?php }
                                            } ?>


                                            </tbody>


                                        </table>

                                    </div>
                                </div>
                            </div>
                            <!-- Scheduled Services -->
                            <div class="col-md-6 col-sm-12">
                                <div class="div-sch-ser">

                                    <div class="table-responsive table-spraye table-responsive2 table-spraye2 dash-tbl">

                                        <table class="table table2 dataTable" id="scheduledServices" role="grid">

                                            <thead class="customer">
                                            <tr>
                                                <th colspan="2"><span class="text-semibold"
                                                                    style="">Scheduled Services</span></th>
                                                <th class="text-right"
                                                    style="vertical-align: middle;"><?php echo '<a href="#highlighted-justified-tab2" data-toggle="tab" onclick="openTab(\'litwo\')"><i class="see-more-icon icon-list"></i><span class="see-more-text">See more</span></a>' ?></th>
                                            </tr>


                                            <tr role="row">

                                                <th>Property Name</th>

                                                <th>Service Name</th>

                                                <th>Date Scheduled</th>

                                            </tr>

                                            </thead>

                                            <tbody class="scrollable" style="max-height: 29vh;">

                                            <?php if (isset($scheduled)) {

                                                foreach ($scheduled as $k => $service) {
                                                    ?>

                                                    <tr role="row">

                                                        <td><?php echo '<a class="color-light" href="' . base_url('admin/editProperty/') . $service->property_id . '" target="_blank">' . $service->property_title . '</a>' ?>

                                                        </td>

                                                        <td><?php echo '<a class="color-light" href="' . base_url('admin/job/editJob/') . $service->job_id . '" target="_blank">' . $service->job_name . '</a>' ?>

                                                        </td>

                                                        <td class="color-light"><?php echo date('m-d-Y', strtotime($service->job_assign_date)); ?>

                                                        </td>

                                                    </tr>

                                                <?php }
                                            } ?>

                                            </tbody>


                                        </table>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="div-notes">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-12">

                                        <div
                                            class="table-responsive table-spraye table-responsive2 table-spraye2 dash-tbl">

                                            <table class="table table2 dataTable" id="qvNotes" role="grid"
                                            ">

                                            <thead class="customer">

                                            <tr>
                                                <th colspan="2"><span class="text-semibold" style="">Notes</span></th>
                                                <th colspan="2" class="text-right"
                                                    style="vertical-align: middle;"><?php echo '<a href="#highlighted-justified-tab5" data-toggle="tab" onclick="openTab(\'lifive\')"><i class="see-more-icon icon-list"></i><span class="see-more-text">See more</span></a>' ?></th>
                                            </tr>

                                            <tr role="row">

                                                <th>Created</th>

                                                <th>Creator</th>

                                                <th>Due Date</th>

                                                <th>Contents</th>

                                            </tr>

                                            </thead>

                                            <tbody class="scrollable" style="max-height: 29vh;">

                                            <?php if (isset($combined_notes)) {

                                                $count = 0;

                                                foreach ($combined_notes as $k => $note) {
                                                    ?>

                                                    <tr role="row">

                                                        <td class="color-light"><?= $note->note_created_at; ?></td>

                                                        <td class="color-light"><?= $note->user_first_name . ' ' . $note->user_last_name; ?></td>

                                                        <td class="color-light"><?= ($note->note_due_date != '0000-00-00' && !empty($note->note_due_date)) ? $note->note_due_date : 'None'; ?></td>

                                                        <td><a class="color-light"
                                                               href="javascript:goToViewNote('<?= $note->note_id; ?>')"><?= $note->note_contents; ?></a>
                                                        </td>

                                                    </tr>

                                                <?php }
                                            } ?>

                                            </tbody>

                                            </table>

                                        </div>


                                    </div>

                                </div>
                                <div class="row">
                                    <!-- New Note -->
                                    <div class="col-8">
                                        <div id="note-form-wrap2" class="collapse">
                                            <span class="text-semibold">New Note</span>

                                            <form class="form-horizontal" style="margin-top: 2%;"
                                                  action="<?= base_url('admin/createNote') ?>" method="post"
                                                  name="createnoteform" enctype="multipart/form-data"
                                                  id="createnoteform" onSubmit="formFileSizeValidate(this)">

                                                <fieldset class="content-group">

                                                    <input type="hidden" name="note_customer_id" class="form-control"
                                                           value="<?= $customerData['customer_id']; ?>">

                                                    <input type="hidden" name="note_category" class="form-control"
                                                           value="1">

                                                    <div class="row">

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label col-lg-3">Assign Property</label>
                                                                <div class="col-lg-9">
                                                                    <select class="form-control" name="note_property_id"
                                                                            placeholder="None">
                                                                        <?php
                                                                        if (!empty($customer_properties)) {
                                                                            foreach ($customer_properties as $value) {
                                                                                if (in_array($value->property_id, $selectedpropertylist)) { ?>
                                                                                    <option
                                                                                        value="<?= $value->property_id; ?>" <?= ($uriSegments[5] == $value->property_id) ? 'selected' : '' ?>><?= $value->property_title; ?></option>
                                                                                <?php }

                                                                            }
                                                                        } ?>
                                                                    </select>
                                                                    <span
                                                                        style="color:red;"><?php echo form_error('note_property_id'); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">

                                                            <div class="form-group">

                                                                <label class="control-label col-lg-3">Note Type</label>

                                                                <div class="col-lg-9">

                                                                    <select class="form-control" name="note_type"
                                                                            required>

                                                                        <option value="" disabled selected></option>

                                                                        <option value="1">Task</option>

                                                                        <?php foreach ($note_types as $type) : ?>

                                                                            <option
                                                                                value="<?= $type->type_id; ?>"><?= $type->type_name; ?></option>

                                                                        <?php endforeach; ?>

                                                                    </select>

                                                                    <span
                                                                        style="color:red;"><?php echo form_error('note_type'); ?></span>

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="col-md-4">

                                                            <div class="form-group">

                                                                <label class="control-label col-lg-3">Assign
                                                                    User</label>

                                                                <div class="col-lg-9">

                                                                    <select class="form-control"
                                                                            name="note_assigned_user">

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

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-10">

                                                            <div class="form-group">

                                                                <label class="control-label col-lg-5">Due Date</label>

                                                                <div class="col-lg-7">

                                                                    <input id="note_due_date" type="text"
                                                                           name="note_due_date"
                                                                           class="form-control pickaalldate"
                                                                           placeholder="YYYY-MM-DD">

                                                                </div>

                                                            </div>

                                                        </div>


                                                        <div class="col-md-10">

                                                            <div class="form-group">

                                                                <label class="control-label col-lg-5 text-right">Attach
                                                                    Documents</label>

                                                                <div class="col-lg-7 text-left">

                                                                    <input id="files" type="file" name="files[]"
                                                                           class="form-control-file" multiple
                                                                           onChange="fileValidationCheck(this)">
                                                                    <span
                                                                        style="color:red;"><?php echo form_error('files'); ?></span>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group checkbox">
                                                                <label class="control-label col-lg-3 text-left">Include
                                                                    in Technician View?</label>
                                                                <div class="col-lg-2">
                                                                    <input id="include_in_tech_view" type="checkbox"
                                                                           name="include_in_tech_view"
                                                                           class="checkbox text-left" value="1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group checkbox">
                                                                <label class="control-label col-lg-3 text-left">Include
                                                                    in Customer View?</label>
                                                                <div class="col-lg-2">
                                                                    <input id="include_in_customer_view" type="checkbox"
                                                                           name="include_in_customer_view"
                                                                           class="checkbox text-left" value="1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 3%">


                                                        <div class="col-md-12">

                                                            <div class="form-group">

                                                                <label class="control-label col-lg-1">Note
                                                                    Contents</label>

                                                                <div class="col-lg-11">

                                                                    <textarea class="form-control" name="note_contents"
                                                                              id="note_contents" rows="5"></textarea>

                                                                    <span
                                                                        style="color:red;"><?php echo form_error('note_contents'); ?></span>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </fieldset>

                                                <div class="text-right btn-space">

                                                    <button type="submit" id="savenote" class="btn btn-success">Save <i
                                                            class="icon-arrow-right14 position-right"></i></button>

                                                </div>

                                            </form>

                                        </div>
                                    </div>

                                    <div class="col-sm-3 pull-right text-right text-white" style="margin-top: 15px">

                                        <button type="button" class="btn" id="addNoteBtn" data-target="#modal_new_note"
                                                data-toggle="modal" style="margin-left=15px;"><i id="addNoteBtnIco"
                                                                                                 class="icon-plus22"></i>
                                            Add New Note
                                        </button>

                                    </div>
                                </div>
                            </div>
                    </div>

                    <!-- PROFILE TAB-->
                    <div class="tab-pane <?php echo $active_nav_link == '1' ? 'active' : '' ?>"
                         id="highlighted-justified-tab1">

                        <form class="form-horizontal" action="<?= base_url('admin/updateCustomer') ?>" method="post"
                              name="addcustomer" enctype="multipart/form-data">

                            <fieldset class="content-group">

                                <input type="hidden" name="customer_id" id="customer_id" class="form-control"
                                       value="<?= $customerData['customer_id']; ?>">

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3 bit-bolder">First Name<span
                                                    class="required"> *</span></label>

                                            <div class="col-lg-9">

                                                <input type="text" class="form-control" name="first_name"

                                                       value="<?php echo set_value('first_name') ? set_value('first_name') : $customerData['first_name'] ?>"

                                                       placeholder="FName">

                                                <span style="color:red;"><?php echo form_error('first_name'); ?></span>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3 bit-bolder">Last Name<span
                                                    class="required"> *</span></label>

                                            <div class="col-lg-9">

                                                <input type="text" class="form-control" name="last_name"

                                                       value="<?php echo set_value('last_name') ? set_value('last_name') : $customerData['last_name'] ?>"

                                                       placeholder="Last Name">

                                                <span style="color:red;"><?php echo form_error('last_name'); ?></span>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3">Company Name</label>

                                            <div class="col-lg-9">

                                                <input type="text" class="form-control"

                                                       value="<?= $customerData['customer_company_name'] ?>"

                                                       placeholder="Company Name" name="customer_company_name">

                                            </div>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3 bit-bolder">Email<span
                                                    class="required"> *</span></label>

                                            <div class="col-lg-9">


                                                <div class="row">

                                                    <div class="col-md-6">

                                                        <input type="text" class="form-control" name="email"

                                                               value="<?php echo set_value('email') ? set_value('email') : $customerData['email'] ?>"

                                                               placeholder="Email">

                                                        <span
                                                            style="color:red;"><?php echo form_error('email'); ?></span>

                                                    </div>


                                                    <div class="col-md-6">

                                                        <div id="subscribeTooltip" class="checkbox"
                                                             data-popup="tooltip-custom"

                                                             title="Turning this option off will cause you to not receive any pre-service or post-service notifications when schedule services at your property. However, you will still receive invoices by email."

                                                             data-placement="top">

                                                            <label class="checkbox-inline checkbox-right">

                                                                <input id="subscribeButton" type="checkbox"
                                                                       name="is_email"

                                                                       class="switchery-is-email"

                                                                    <?php echo $customerData['is_email'] == 1 ? 'checked' : '' ?>>

                                                                Subscribe


                                                            </label>

                                                        </div>

                                                    </div>


                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3 col-sm-12 col-xs-12">Secondary
                                                Email(s)</label>

                                            <div class="multi-select-full col-lg-8  col-sm-10 col-xs-10 pl-15">

                                                <textarea cols="60" disabled="disabled" style="max-width: 100%" ;

                                                          id="secondary_email_list"><?php echo $customerData['secondary_email'] ?></textarea>

                                                <input type="hidden" id="secondary_email_list_hid"

                                                       name="secondary_email_list_hid"

                                                       value="<?php echo $customerData['secondary_email'] ?>"/>

                                            </div>

                                            <div class="col-lg-1 col-sm-2 col-xs-2 addbuttonmanage">

                                                <?php

                                                $hide_reset_link = "";

                                                $add_link_padding = "";

                                                if ($customerData['secondary_email'] == "") {

                                                    $hide_reset_link = "class='hidden'";

                                                    $add_link_padding = " pt-5";

                                                }

                                                ?>

                                                <div class="form-group mb-5">

                                                    <center>

                                                        <a href="#" id="add_secondary_email_link" data-toggle="modal"

                                                           data-target="#modal_add_secondary_emails"><i

                                                                    class="icon-add text-success<?php echo $add_link_padding ?>"

                                                                    style="font-size:25px;"></i></a>

                                                    </center>

                                                </div>

                                                <div class="form-group ">

                                                    <center>

                                                        <a <?php echo $hide_reset_link ?>id="reset_secondary_email_link"

                                                           href="#"><i
                                                                class="icon-reset text-success pt-6"

                                                                    style="font-size:25px;"></i></a>

                                                    </center>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3 bit-bolder">Mobile<span
                                                    class="required"> *</span></label>

                                            <div class="col-lg-9">

                                                <div class="row">

                                                    <div class="col-md-6">

                                                        <input type="text" class="form-control" name="phone"

                                                               value="<?= $customerData['phone'] == 0 ? '' : $customerData['phone'] ?>"

                                                               placeholder="Mobile">

                                                        <span
                                                            style="color:red;"><?php echo form_error('phone'); ?></span>

                                                        <span>Please do not use dashes</span>

                                                    </div>

                                                    <div class="col-md-6">

                                                        <div class="checkbox">

                                                            <label class="checkbox-inline checkbox-right">

                                                                <input type="checkbox" name="is_mobile_text"

                                                                       class="switchery-is-mobile-text" <?php if ($this->session->userdata['is_text_message']) {

                                                                    echo $customerData['is_mobile_text'] == 1 ? 'checked' : '';

                                                                } else {

                                                                    echo 'disabled';
                                                                } ?>>

                                                                Text Alerts

                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>


                                            </div>

                                        </div>

                                    </div>


                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3">Home</label>

                                            <div class="col-lg-9">

                                                <input type="text" class="form-control" name="home_phone"

                                                       value="<?= $customerData['home_phone'] == 0 ? '' : $customerData['home_phone'] ?>"

                                                       placeholder="Home">

                                                <span style="color:red;"><?php echo form_error('home_phone'); ?></span>


                                                <span>Please do not use dashes</span>


                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3">Work</label>

                                            <div class="col-lg-9">

                                                <input type="text" class="form-control" name="work_phone"

                                                       value="<?= $customerData['work_phone'] == 0 ? '' : $customerData['work_phone'] ?>"

                                                       placeholder="Work">

                                                <span style="color:red;"><?php echo form_error('work_phone'); ?></span>


                                                <span>Please do not use dashes</span>


                                            </div>

                                        </div>

                                    </div>


                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3 bit-bolder">Billing Address<span
                                                    class="required"> *</span></label>

                                            <div class="col-lg-9">

                                                <input type="text" id="autocomplete" class="form-control"
                                                       name="billing_street"

                                                       value="<?php echo set_value('billing_street') ? set_value('billing_street') : $customerData['billing_street'] ?>"

                                                       placeholder="Address">

                                                <span
                                                    style="color:red;"><?php echo form_error('billing_street'); ?></span>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3">Billing Address 2</label>

                                            <div class="col-lg-9">

                                                <input type="text" class="form-control" id="billing_street_2"

                                                       name="billing_street_2"

                                                       value="<?php echo set_value('billing_street_2') ? set_value('billing_street_2') : $customerData['billing_street_2'] ?>"

                                                       placeholder="Address 2">

                                                <span
                                                    style="color:red;"><?php echo form_error('billing_street_2'); ?></span>

                                            </div>

                                        </div>

                                    </div>


                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3 bit-bolder">City<span class="required"> *</span></label>

                                            <div class="col-lg-9">

                                                <input type="text" class="form-control" id="locality"
                                                       name="billing_city"

                                                       value="<?php echo set_value('billing_city') ? set_value('billing_city') : $customerData['billing_city'] ?>"

                                                       placeholder="City">

                                                <span
                                                    style="color:red;"><?php echo form_error('billing_city'); ?></span>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3 bit-bolder">Billing State /
                                                Territory<span class="required"> *</span></label>

                                            <div class="col-lg-9">


                                                <select class="form-control" name="billing_state" id="region">


                                                    <option value="">Select State</option>
                                                    <optgroup label="Canadian Provinces">
                                                        <option
                                                            value="AB" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Alberta
                                                        </option>
                                                        <option
                                                            value="BC" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >British Columbia
                                                        </option>
                                                        <option
                                                            value="MB" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Manitoba
                                                        </option>
                                                        <option
                                                            value="NB" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >New Brunswick
                                                        </option>
                                                        <option
                                                            value="NF" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Newfoundland
                                                        </option>
                                                        <option
                                                            value="NT" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Northwest Territories
                                                        </option>
                                                        <option
                                                            value="NS" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Nova Scotia
                                                        </option>
                                                        <option
                                                            value="NU" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Nunavut
                                                        </option>
                                                        <option
                                                            value="ON" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Ontario
                                                        </option>
                                                        <option
                                                            value="PE" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Prince Edward Island
                                                        </option>
                                                        <option
                                                            value="QC" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Quebec
                                                        </option>
                                                        <option
                                                            value="SK" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Saskatchewan
                                                        </option>
                                                        <option
                                                            value="YT" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Yukon Territory
                                                        </option>
                                                    </optgroup>

                                                    <optgroup label="U.S. States/Territories">
                                                        <option
                                                            value="AL" <?php if ($customerData['billing_state'] == 'AL') {
                                                            echo "selected";
                                                        } ?> >Alabama
                                                        </option>
                                                        <option
                                                            value="AK" <?php if ($customerData['billing_state'] == 'AK') {
                                                            echo "selected";
                                                        } ?> >Alaska
                                                        </option>
                                                        <option
                                                            value="AZ" <?php if ($customerData['billing_state'] == 'AZ') {
                                                            echo "selected";
                                                        } ?> >Arizona
                                                        </option>
                                                        <option
                                                            value="AR" <?php if ($customerData['billing_state'] == 'AR') {
                                                            echo "selected";
                                                        } ?> >Arkansas
                                                        </option>
                                                        <option
                                                            value="CA" <?php if ($customerData['billing_state'] == 'CA') {
                                                            echo "selected";
                                                        } ?> >California
                                                        </option>
                                                        <option
                                                            value="CO" <?php if ($customerData['billing_state'] == 'CO') {
                                                            echo "selected";
                                                        } ?> >Colorado
                                                        </option>
                                                        <option
                                                            value="CT" <?php if ($customerData['billing_state'] == 'CT') {
                                                            echo "selected";
                                                        } ?> >Connecticut
                                                        </option>
                                                        <option
                                                            value="DE" <?php if ($customerData['billing_state'] == 'DE') {
                                                            echo "selected";
                                                        } ?> >Delaware
                                                        </option>
                                                        <option
                                                            value="DC" <?php if ($customerData['billing_state'] == 'DC') {
                                                            echo "selected";
                                                        } ?> >District Of Columbia
                                                        </option>
                                                        <option
                                                            value="FL" <?php if ($customerData['billing_state'] == 'FL') {
                                                            echo "selected";
                                                        } ?> >Florida
                                                        </option>
                                                        <option
                                                            value="GA" <?php if ($customerData['billing_state'] == 'GA') {
                                                            echo "selected";
                                                        } ?> >Georgia
                                                        </option>
                                                        <option
                                                            value="HI" <?php if ($customerData['billing_state'] == 'HI') {
                                                            echo "selected";
                                                        } ?> >Hawaii
                                                        </option>
                                                        <option
                                                            value="ID" <?php if ($customerData['billing_state'] == 'ID') {
                                                            echo "selected";
                                                        } ?> >Idaho
                                                        </option>
                                                        <option
                                                            value="IL" <?php if ($customerData['billing_state'] == 'IL') {
                                                            echo "selected";
                                                        } ?> >Illinois
                                                        </option>
                                                        <option
                                                            value="IN" <?php if ($customerData['billing_state'] == 'IN') {
                                                            echo "selected";
                                                        } ?> >Indiana
                                                        </option>
                                                        <option
                                                            value="IA" <?php if ($customerData['billing_state'] == 'IA') {
                                                            echo "selected";
                                                        } ?> >Iowa
                                                        </option>
                                                        <option
                                                            value="KS" <?php if ($customerData['billing_state'] == 'KS') {
                                                            echo "selected";
                                                        } ?> >Kansas
                                                        </option>
                                                        <option
                                                            value="KY" <?php if ($customerData['billing_state'] == 'KY') {
                                                            echo "selected";
                                                        } ?> >Kentucky
                                                        </option>
                                                        <option
                                                            value="LA" <?php if ($customerData['billing_state'] == 'LA') {
                                                            echo "selected";
                                                        } ?> >Louisiana
                                                        </option>
                                                        <option
                                                            value="ME" <?php if ($customerData['billing_state'] == 'ME') {
                                                            echo "selected";
                                                        } ?> >Maine
                                                        </option>
                                                        <option
                                                            value="MD" <?php if ($customerData['billing_state'] == 'MD') {
                                                            echo "selected";
                                                        } ?> >Maryland
                                                        </option>
                                                        <option
                                                            value="MA" <?php if ($customerData['billing_state'] == 'MA') {
                                                            echo "selected";
                                                        } ?> >Massachusetts
                                                        </option>
                                                        <option
                                                            value="MI" <?php if ($customerData['billing_state'] == 'MI') {
                                                            echo "selected";
                                                        } ?> >Michigan
                                                        </option>
                                                        <option
                                                            value="MN" <?php if ($customerData['billing_state'] == 'MN') {
                                                            echo "selected";
                                                        } ?> >Minnesota
                                                        </option>
                                                        <option
                                                            value="MS" <?php if ($customerData['billing_state'] == 'MS') {
                                                            echo "selected";
                                                        } ?> >Mississippi
                                                        </option>
                                                        <option
                                                            value="MO" <?php if ($customerData['billing_state'] == 'MO') {
                                                            echo "selected";
                                                        } ?> >Missouri
                                                        </option>
                                                        <option
                                                            value="MT" <?php if ($customerData['billing_state'] == 'MT') {
                                                            echo "selected";
                                                        } ?> >Montana
                                                        </option>
                                                        <option
                                                            value="NE" <?php if ($customerData['billing_state'] == 'NE') {
                                                            echo "selected";
                                                        } ?> >Nebraska
                                                        </option>
                                                        <option
                                                            value="NV" <?php if ($customerData['billing_state'] == 'NV') {
                                                            echo "selected";
                                                        } ?> >Nevada
                                                        </option>
                                                        <option
                                                            value="NH" <?php if ($customerData['billing_state'] == 'NH') {
                                                            echo "selected";
                                                        } ?> >New Hampshire
                                                        </option>
                                                        <option
                                                            value="NJ" <?php if ($customerData['billing_state'] == 'NJ') {
                                                            echo "selected";
                                                        } ?> >New Jersey
                                                        </option>
                                                        <option
                                                            value="NM" <?php if ($customerData['billing_state'] == 'NM') {
                                                            echo "selected";
                                                        } ?> >New Mexico
                                                        </option>
                                                        <option
                                                            value="NY" <?php if ($customerData['billing_state'] == 'NY') {
                                                            echo "selected";
                                                        } ?> >New York
                                                        </option>
                                                        <option
                                                            value="NC" <?php if ($customerData['billing_state'] == 'NC') {
                                                            echo "selected";
                                                        } ?> >North Carolina
                                                        </option>
                                                        <option
                                                            value="ND" <?php if ($customerData['billing_state'] == 'ND') {
                                                            echo "selected";
                                                        } ?> >North Dakota
                                                        </option>
                                                        <option
                                                            value="OH" <?php if ($customerData['billing_state'] == 'OH') {
                                                            echo "selected";
                                                        } ?> >Ohio
                                                        </option>
                                                        <option
                                                            value="OK" <?php if ($customerData['billing_state'] == 'OK') {
                                                            echo "selected";
                                                        } ?> >Oklahoma
                                                        </option>
                                                        <option
                                                            value="OR" <?php if ($customerData['billing_state'] == 'OR') {
                                                            echo "selected";
                                                        } ?> >Oregon
                                                        </option>
                                                        <option
                                                            value="PA" <?php if ($customerData['billing_state'] == 'PA') {
                                                            echo "selected";
                                                        } ?> >Pennsylvania
                                                        </option>
                                                        <option
                                                            value="RI" <?php if ($customerData['billing_state'] == 'RI') {
                                                            echo "selected";
                                                        } ?> >Rhode Island
                                                        </option>
                                                        <option
                                                            value="SC" <?php if ($customerData['billing_state'] == 'SC') {
                                                            echo "selected";
                                                        } ?> >South Carolina
                                                        </option>
                                                        <option
                                                            value="SD" <?php if ($customerData['billing_state'] == 'SD') {
                                                            echo "selected";
                                                        } ?> >South Dakota
                                                        </option>
                                                        <option
                                                            value="TN" <?php if ($customerData['billing_state'] == 'TN') {
                                                            echo "selected";
                                                        } ?> >Tennessee
                                                        </option>
                                                        <option
                                                            value="TX" <?php if ($customerData['billing_state'] == 'TX') {
                                                            echo "selected";
                                                        } ?> >Texas
                                                        </option>
                                                        <option
                                                            value="UT" <?php if ($customerData['billing_state'] == 'UT') {
                                                            echo "selected";
                                                        } ?> >Utah
                                                        </option>
                                                        <option
                                                            value="VT" <?php if ($customerData['billing_state'] == 'VT') {
                                                            echo "selected";
                                                        } ?> >Vermont
                                                        </option>
                                                        <option
                                                            value="VA" <?php if ($customerData['billing_state'] == 'VA') {
                                                            echo "selected";
                                                        } ?> >Virginia
                                                        </option>
                                                        <option
                                                            value="WA" <?php if ($customerData['billing_state'] == 'WA') {
                                                            echo "selected";
                                                        } ?> >Washington
                                                        </option>
                                                        <option
                                                            value="WV" <?php if ($customerData['billing_state'] == 'WV') {
                                                            echo "selected";
                                                        } ?> >West Virginia
                                                        </option>
                                                        <option
                                                            value="WI" <?php if ($customerData['billing_state'] == 'WI') {
                                                            echo "selected";
                                                        } ?> >Wisconsin
                                                        </option>
                                                        <option
                                                            value="WY" <?php if ($customerData['billing_state'] == 'WY') {
                                                            echo "selected";
                                                        } ?> >Wyoming
                                                        </option>
                                                    </optgroup>


                                                </select>

                                                <span
                                                    style="color:red;"><?php echo form_error('billing_state'); ?></span>

                                            </div>

                                        </div>

                                    </div>


                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3 bit-bolder">Postal Code<span
                                                    class="required"> *</span></label>

                                            <div class="col-lg-9">

                                                <input type="text" class="form-control" id="postal-code"
                                                       name="billing_zipcode"

                                                       value="<?php echo set_value('billing_zipcode') ? set_value('billing_zipcode') : $customerData['billing_zipcode'] ?>"

                                                       placeholder="Postal Code">

                                                <span
                                                    style="color:red;"><?php echo form_error('billing_zipcode'); ?></span>

                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">Billing Type</label>
                                            <div class="col-lg-9">
                                                <input type="hidden" name="billing_type"
                                                       value="<?= $customerData['billing_type'] ?>">
                                                <select class="form-control" name="billing_type_select" disabled>
                                                    <option value=0 <?php if ($customerData['billing_type'] == 0) {
                                                        echo "selected";
                                                    } ?> >Standard
                                                    </option>
                                                    <option value=1 <?php if ($customerData['billing_type'] == 1) {
                                                        echo "selected";
                                                    } ?> >Group Billing
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">


                                        <div class="form-group">

                                            <label class="control-label col-lg-3 col-sm-12 col-xs-12">Assign
                                                Properties</label>

                                            <div class="multi-select-full col-lg-8  col-sm-10 col-xs-10"
                                                 style=" padding-left: 4px;">


                                                <?php
                                                $id = [];
                                                $propTitle = [];
                                                $status = [];

                                                foreach ($selectedPropertyDetailsList as $value) {

                                                    array_push($id, $value->id);
                                                    array_push($propTitle, $value->title);
                                                    array_push($status, $value->status);
                                                }
                                                ?>


                                                <input autocomplete="off" type="text" class="form-control"
                                                       id="property_list_field"
                                                       value="<?php $lengthOfArray = count($id);
                                                       for ($i = 0; $i < $lengthOfArray; $i++) {
                                                           echo $propTitle[$i] . ", ";

                                                       }
                                                       ?>" placeholder="Assign Property"/>
                                                <div
                                                    style="z-index: 999; width: 100%; display: none; position: absolute; left: 0px; top: 40px; background-color: #ffffff; overflow-y: scroll; height: 25em; max-height: 25em;"
                                                    id="suggestion-box"></div>


                                                <div style="display: none;" id="property_list_div">
                                                    <select style="display: none;" name="assign_property[]"
                                                            multiple="multiple" id="property_list"
                                                            data-existingproperties="<?php echo implode(',', $selectedpropertylist); ?>">

                                                        <?php
                                                        $lengthOfArray = count($id);
                                                        for ($i = 0; $i < $lengthOfArray; $i++) {
                                                            echo "<option value='" . $id[$i] . "' title='" . $propTitle[$i] . "' selected>" . $propTitle[$i] . "</option>";

                                                        }

                                                        ?>

                                                    </select>
                                                </div>


                                                <span
                                                    style="color:red;"><?php echo form_error('assign_property'); ?></span>

                                            </div>


                                            <div class="col-lg-1 col-sm-2 col-xs-2 addbuttonmanage">

                                                <div class="form-group">

                                                    <center>

                                                        <a href="#" data-toggle="modal"
                                                           data-target="#modal_add_property"><i

                                                                class="icon-add text-success"

                                                                style="padding-top:6px;font-size:25px;"></i></a>


                                                    </center>

                                                </div>

                                            </div>


                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">Customer Status</label>
                                            <div class="col-lg-9">
                                                <select class="form-control" name="customer_status">
                                                    <option value="">Select Any Status</option>
                                                    <option value="1" <?php if ($customerData['customer_status'] == 1) {
                                                        echo "selected";
                                                    } ?>>
                                                        Active
                                                    </option>
                                                    <option value="0" <?php if ($customerData['customer_status'] == 0) {
                                                        echo "selected";
                                                    } ?>>
                                                        Non-Active
                                                    </option>
                                                    <option value="2" <?php if ($customerData['customer_status'] == 2) {
                                                        echo "selected";
                                                    } ?> >Hold
                                                    </option>
                                                    <option value="4" <?php if ($customerData['customer_status'] == 4) {
                                                        echo "selected";
                                                    } ?> >Sales Call Scheduled
                                                    </option>
                                                    <option value="5" <?php if ($customerData['customer_status'] == 5) {
                                                        echo "selected";
                                                    } ?> >Estimate Sent
                                                    </option>
                                                    <option value="6" <?php if ($customerData['customer_status'] == 6) {
                                                        echo "selected";
                                                    } ?> >Estimate Declined
                                                    </option>
                                                    <option value="7" <?php if ($customerData['customer_status'] == 7) {
                                                        echo "selected";
                                                    } ?> >Prospect
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $notify_flag = json_decode($customerData['pre_service_notification']) ?: []; ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">Pre-Service Notification</label>
                                            <div class="multi-select-full col-lg-9  col-sm-10 col-xs-10">
                                                <select class="multiselect-select-all-filtering form-control"
                                                        id="pre_service_notification" name="pre_service_notification[]"
                                                        multiple="multiple" value="">
                                                    <option
                                                        value="1" <?= in_array(1, $notify_flag) ? "selected" : ""; ?> >
                                                        Phone Call
                                                    </option>
                                                    <option
                                                        value="2" <?= in_array(2, $notify_flag) ? "selected" : ""; ?> >
                                                        Automated Email(s)
                                                    </option>
                                                    <option
                                                        value="3" <?= in_array(3, $notify_flag) ? "selected" : ""; ?> >
                                                        Automated Text message(s)
                                                    </option>
                                                    <option
                                                        value="4" <?= in_array(4, $notify_flag) ? "selected" : ""; ?> >
                                                        Text when En route
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="padding: 20px 0;">


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3 col-sm-12 col-xs-12">Apply Permanent

                                                Coupons<small style="font-size: 13px;"><br>this will apply to all unpaid
                                                    and

                                                    future invoices</small></label>

                                            <div class="multi-select-full col-lg-9" style="padding-left: 4px;">

                                                <select class="multiselect-select-all-filtering form-control"

                                                        name="assign_coupons[]" id="" multiple="multiple">

                                                    <?php foreach ($customer_perm_coupons as $value): ?>

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


                                                        if ($expiration_pass == true) {


                                                            ?>

                                                            <option value="<?= $value->coupon_id ?>"

                                                                    <?php if (in_array($value->coupon_id, $customer_existing_perm_coupons)) { ?>selected

                                                                <?php } ?>> <?= $value->code ?> </option>

                                                        <?php } ?>

                                                    <?php endforeach ?>

                                                </select>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3 col-sm-12 col-xs-12">Apply One-Time

                                                Coupons<small style="font-size: 13px;"><br>this will apply to all unpaid

                                                    invoices</small></label>

                                            <div class="multi-select-full col-lg-9" style="padding-left: 4px;">

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


                                                        if ($expiration_pass == true) {


                                                            ?>


                                                            <option value="<?= $value->coupon_id ?>"

                                                                    <?php if (in_array($value->coupon_id, $customer_existing_perm_coupons)) { ?>selected

                                                                <?php } ?>> <?= $value->code ?> </option>

                                                        <?php } ?>

                                                    <?php endforeach ?>

                                                </select>

                                            </div>

                                        </div>

                                    </div>


                                </div>
                                <?php if (isset($send_daily_invoice_mail) && $send_daily_invoice_mail == 1) {
                                    if (isset($customerData['autosend_invoices']) && $customerData['autosend_invoices'] != 1) {
                                        $autoSend = 0;
                                    } else {
                                        $autoSend = 1;
                                    }

                                    if (isset($customerData['autosend_frequency']) && $customerData['autosend_frequency'] == 'monthly') {
                                        $sendDaily = 0;
                                    } else {
                                        $sendDaily = 1;
                                    }
                                    ?>
                                    <div class="row" id="auto-send-invoices-div">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="hidden" name="send_daily_invoice_mail" value=1/>
                                                <label class="control-label col-lg-3">Auto Send Invoices?</label>
                                                <label class="togglebutton" style="font-size:16px">Off</label>
                                                <input name="autosend_invoices" type="checkbox"
                                                       class="switchery-is-autosend-invoices" <?php if ($autoSend == 1) {
                                                    echo "checked";
                                                } ?> />
                                                <label class="togglebutton" style="font-size:16px">On</label>
                                                <div class="btn-group btn-group-custom"
                                                     data-toggle="buttons" <?php if ($autoSend != 1) {
                                                    echo "style='display:none;'";
                                                } ?> id="autosend-freq-div">
                                                    <label class="btn btn-default" for="autosend_frequency1"
                                                           id="autosend_frequency1-label">
                                                        <input type="radio" class="form-check-input"
                                                               name="autosend_frequency" id="autosend_frequency1"
                                                               value="daily" <?php if ($sendDaily == 1) {
                                                            echo "checked";
                                                        } ?>/>Daily
                                                    </label>
                                                    <label class="btn btn-default" for="autosend_frequency2"
                                                           id="autosend_frequency2-label">
                                                        <input type="radio" class="form-check-input"
                                                               name="autosend_frequency" id="autosend_frequency2"
                                                               value="monthly" <?php if ($sendDaily != 1) {
                                                            echo "checked";
                                                        } ?>/>Monthly
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label class="control-label col-lg-3">Auto-Charge

                                                <label class="togglebutton" style="font-size:16px">Do you want to run
                                                    customer

                                                    credit card automatically upon job completion? <span

                                                            data-popup="tooltip-custom"

                                                            title="When this option is on, your customer\'s credit card on file will be charged when a service is completed. Their invoice will then automatically be marked as paid."

                                                        data-placement="top"> <i
                                                            class=" icon-info22 tooltip-icon"></i>

                                                    </span></label></label>

                                            <div class="col-lg-4">

                                                <label class="togglebutton" style="font-size:16px">Off</label>

                                                <?php if ($cardconnect_details && $cardconnect_details->status != 0) { ?>

                                                    <input name="clover_autocharge" type="checkbox"
                                                           class="switchery-autocharge"

                                                        <?php echo $customerData['clover_autocharge'] == 1 ? 'checked' : ''; ?>>

                                                <?php } else if ($basys_details && $basys_details->status != 0) { ?>

                                                    <input name="basys_autocharge" type="checkbox"
                                                           class="switchery-autocharge"

                                                        <?php echo $customerData['basys_autocharge'] == 1 ? 'checked' : ''; ?>>

                                                <?php } else { ?>

                                                    <input name="autocharge" type="checkbox"
                                                           class="switchery-autocharge"

                                                           disabled>

                                                <?php } ?>

                                                <label class="togglebutton" style="font-size:13px">On</label>


                                                <input type="hidden" name="clover_status"

                                                       value="<?php echo $cardconnect_details && $cardconnect_details->status == 1 ? 1 : 0 ?>">

                                                <input type="hidden" name="basys_status"

                                                       value="<?php echo $basys_details && $basys_details->status == 1 ? 1 : 0 ?>">

                                                <?php

                                                if ($cardconnect_details && $cardconnect_details->status != 0) { ?>

                                                    <input type="hidden" name="customer_clover_token"

                                                           value="<?php echo isset($customerData['customer_clover_token']) ? $customerData['customer_clover_token'] : '' ?>">

                                                    <input type="hidden" name="clover_acct_id"

                                                           value="<?php echo isset($customerData['clover_acct_id']) ? $customerData['clover_acct_id'] : '' ?>">

                                                <?php } else if ($basys_details && $basys_details->status != 0) { ?>

                                                    <input type="hidden" name="basys_customer_id"

                                                           value="<?php echo isset($customerData['basys_customer_id']) ? $customerData['basys_customer_id'] : '' ?>">

                                                <?php } ?>


                                            </div>

                                        </div>


                                    </div>

                                </div>


                            </fieldset>


                            <div class="text-right">

                                <button type="submit" class="btn btn-success">Save <i

                                        class="icon-arrow-right14 position-right"
                                        id="submit-update-customer-form"></i></button>

                            </div>

                        </form>

                    </div>

                    <!-- SERVICES TAB -->
                    <div class="tab-pane <?php echo $active_nav_link == '2' ? 'active' : '' ?>"
                         id="highlighted-justified-tab2">

                        <div id="modal_apply_discount_services" class="modal fade">

                            <div class="modal-dialog">

                                <div class="modal-content">

                                    <div class="modal-header bg-primary"
                                         style="background: #36c9c9;border-color: #36c9c9;">

                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                        <h6 class="modal-title">Apply Coupon</h6>

                                    </div>

                                    <form action="" method="post" id="apply_discount_form">

                                        <div class="modal-body">

                                            <div class="form-group">

                                                <div class="row">

                                                    <div class="col-sm-12">


                                                        <div style="color: red;" id="apply_discount_form_errors"></div>


                                                        <label>Select Coupon</label>

                                                        <select name="coupon_id" class="form-control">

                                                            <option value=''>Select a Coupon</option>

                                                            <?php

                                                            foreach ($customer_one_time_discounts as $discount) {


                                                                // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000

                                                                $expiration_pass = true;

                                                                if ($discount->expiration_date != "0000-00-00 00:00:00") {

                                                                    $coupon_expiration_date = strtotime($discount->expiration_date);


                                                                    $now = time();

                                                                    if ($coupon_expiration_date < $now) {

                                                                        $expiration_pass = false;

                                                                        $expiration_pass_global = false;

                                                                    }

                                                                }


                                                                if ($expiration_pass == true) {

                                                                    $disc_coupon_id = $discount->coupon_id;

                                                                    $disc_name = $discount->code;

                                                                    echo "<option value='$disc_coupon_id'>$disc_name</option>";

                                                                }

                                                            }

                                                            ?>

                                                            <option value='REMOVE-ALL'>Remove coupons from selected
                                                                services

                                                            </option>

                                                        </select>


                                                        <input type="hidden" name="job_data" id="coupon_apply_id_csv"
                                                               value="">


                                                    </div>

                                                </div>

                                            </div>

                                            <div class="modal-footer" style="padding: 0;">

                                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                    Close
                                                </button>

                                                <button type="submit" id="savearea" class="btn btn-success">Apply Coupon
                                                    to

                                                    Selected Services
                                                </button>

                                            </div>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                        <?= $this->load->view('partials/hold_until_modal') ?>

                        <div class="table-responsive table-spraye">

                            <table class="table datatable-basic" id="customer-services">

                                <button type="button" class="btn btn-primary" data-toggle="modal" style="margin-right: 10px;"
                                        data-target="#modal_apply_discount_services" onclick="applyDiscount()">Apply Coupon
                                </button>

                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal_hold_until_services" onclick="applyHoldService()">Hold Until
                                </button>

                                <script>

                                    $("#apply_discount_form").submit(function () {

                                        $("#loading").css("display", "block");

                                        $.ajax({

                                            url: "<?= base_url('admin/setting/applyCouponData') ?>",

                                            data: $("#apply_discount_form").serialize(),

                                            type: "POST",

                                            dataType: 'json',

                                            success: function (e) {


                                                console.log(e);


                                                $("#loading").css("display", "none");

                                                if (e != 0 && e != 1) {

                                                    document.querySelector('#apply_discount_form_errors')

                                                        .innerHTML = e;

                                                } else {

                                                    $("#modal_apply_discount_services").css("display", "none");

                                                    $('.modal-backdrop').css("display", "none");


                                                    // getCouponList();


                                                    if ($("#apply_discount_form").serialize().includes(
                                                        'REMOVE-ALL')) {

                                                        swal(
                                                            'Coupon',

                                                            'Removed Successfully ',

                                                            'success'
                                                        )

                                                    } else {

                                                        swal(
                                                            'Coupon',

                                                            'Added Successfully ',

                                                            'success'
                                                        )

                                                    }


                                                    location.reload();


                                                }

                                            },

                                            error: function (e) {

                                                $("#loading").css("display", "none");

                                                alert("Something went wrong");

                                            }

                                        });

                                        return false;

                                    });

                                    $("#apply_hold_until_date").submit(function () {
                                        $.ajax({
                                            url: "<?= base_url('admin/setting/applyHoldUntilDate') ?>",
                                            data: $("#apply_hold_until_date").serialize(),
                                            type: "POST",
                                            dataType: 'json',
                                            beforeSend: function () {
                                                $("#loading").css("display", "block");
                                            },
                                            success: function (e) {
                                                $("#loading").css("display", "none");
                                                if (e != 0 && e != 1) {
                                                    document.querySelector('#apply_hold_until_date_errors').innerHTML = e;
                                                } else {
                                                    $("#modal_hold_until_services").css("display", "none");
                                                    $('.modal-backdrop').css("display", "none");

                                                    if ($("#apply_hold_until_date").serialize().includes('REMOVE-ALL')) {
                                                        swal(
                                                            'Hold Until',
                                                            'Removed Successfully ',
                                                            'success'
                                                        )
                                                    } else {
                                                        swal(
                                                            'Hold Until',
                                                            'Added Successfully ',
                                                            'success'
                                                        )
                                                    }
                                                    window.location.reload();
                                                }
                                            },
                                            error: function (e) {
                                                $("#loading").css("display", "none");
                                                swal(
                                                    'Hold Until',
                                                    'Something went wrong',
                                                    'error'
                                                )
                                            }
                                        });

                                        return false;

                                    });

                                    // get all checked services, and insert data into form

                                    function applyDiscount() {

                                        all_service_data = [];

                                        $('#customer_services_tbody input:checked').each(function () {

                                            all_service_data.push($(this).val());

                                        });

                                        document.querySelector('#coupon_apply_id_csv').value = JSON.stringify(all_service_data);
                                        document.querySelector('#hold_date_job_data_csv').value = JSON.stringify(all_service_data);

                                    }
                                    function applyHoldService() {
                                        applyDiscount();
                                        $('#modal_hold_until_services').find('.hold_service_text label').text('Hold these services until the following date:');
                                    }

                                </script>

                                <thead>
                                <tr>

                                    <th>
                                        <!--<input type="checkbox" id="select_all" />-->
                                    </th>
                                    <th>Technician Name</th>
                                    <th>Service Name</th>
                                    <th>Scheduled Date</th>
                                    <th>Address</th>
                                    <th>Service Area</th>
                                    <th>Program</th>
                                    <th> Cost <span data-popup="tooltip-custom"
                                                    title="Services with no invoice won't reflect price override until invoice is created."
                                                    data-placement="top">  <i
                                                class=" icon-info22 tooltip-icon"></i> </span>
                                    </th>
                                    <th>Completion Status</th>
                                    <th>Sales Rep</th>
                                    <th>Coupons</th>
                                    <th>Hold Until Date</th>
                                    <th>Actions</th>
                                    <?php if (isset($customerData['billing_type']) && $customerData['billing_type'] == 1) { ?>
                                        <th>Action</th><?php } ?>
                                </tr>
                                </thead>

                                <tbody id="customer_services_tbody">

                                <?php
                                if (!empty($all_services)) {

                                    foreach ($all_services as $value) {
                                        $isSkipped = !is_null($value->skip_name) && $value->skip_name != '';
                                        $asapClass = empty($value->on_hold_status) && $value->asap == 1 ? 'asap_row' : '';
                                        $skippedClass = $isSkipped ? 'skipped_row' : '';
                                        $disabled = $skippedClass != '' ? "disabled='disabled'" : '';
                                        ?>

                                        <tr class="<?= $asapClass ?> <?=$skippedClass?>">

                                            <td><input name='group_id' type='checkbox'

                                                       value="<?php echo $value->customer_id . "," . $value->job_id . "," . $value->program_id . "," . $value->property_id ?>"
                                                       <?= $disabled ?>"
                                                       class='myCheckBox'/></td>

                                            <td><?= $value->user_first_name . ' ' . $value->user_last_name; ?></td>

                                            <td><?= $value->job_name; ?></td>

                                            <td><?php if (isset($value->job_assign_date)) {
                                                    echo date('m-d-Y', strtotime($value->job_assign_date));
                                                } ?>

                                            </td>

                                            <td><?= $value->property_address ?></td>

                                            <td><?php if (isset($value->category_area_name)) {
                                                    echo $value->category_area_name;
                                                } else {
                                                    echo 'None';
                                                } ?></td>

                                            <td><?= $value->program_name ?></td>
                                            <td><?= $value->job_cost ?></td>

                                            <td>

                                                <?php
                                                $strStatus = '';
                                                if ($value->skip_name != '') {
                                                    $strStatus = 'Skipped - '.$value->skip_name;
                                                } else if ($value->technician_job_assign_id != 0){
                                                    switch ($value->is_job_mode) {
                                                        case 0:
                                                            $strStatus = 'Scheduled';
                                                            break;
                                                        case 1:
                                                            $strStatus = "Completed";
                                                            break;
                                                        case 2:
                                                            $strStatus = "Rescheduled";
                                                            break;
                                                        default:
                                                            $strStatus = "Default";
                                                            break;
                                                    }
                                                } else {
                                                    $strStatus = "Pending";
                                                }
                                                $isCanceledClass = '';
                                                if (isset($value->cancelled) && $value->cancelled == 1) {
                                                    $strStatus = 'Canceled';
                                                    $isCanceledClass = "isCanceled";
                                                } else if (!$isSkipped) {
                                                    if(!empty($value->on_hold_status))
                                                        $strStatus = $strStatus .' - '. 'On Hold';
                                                    else if($value->asap == 1)
                                                        $strStatus = $strStatus .' - '. 'ASAP';
                                                }
                                                echo $strStatus;
                                                ?>
                                            </td>
                                            <td><?php if (isset($value->sales_rep_name)) {
                                                    echo $value->sales_rep_name;
                                                } ?></td>

                                            <td><?= $value->coupon_code_csv ?></td>
                                            <td class="<?=$isCanceledClass?>"><?php if (isset($value->hold_until_date) && $value->hold_until_date != '0000-00-00') {
                                                    echo date('m-d-Y', strtotime($value->hold_until_date));
                                                } ?>
                                            </td>
                                            <td class="table-action">
                                                <ul style="list-style-type: none; padding-left: 0px;">
                                                    <li>
                                                        <?php if (isset($value->cancelled) && $value->cancelled == 1) { ?>
<!--                                                            <span class="label label-danger">Canceled</span>-->
                                                        <?php } else { ?>
                                                            <?php if (!empty($value->on_hold_status)) { ?>
<!--                                                                <span class="label label-refunded">On Hold</span>-->
                                                            <?php } else if ($isSkipped) { ?>
<!--                                                                <span class="label label-skipped">Skipped</span>-->
                                                            <?php } else if($value->asap == 1) { ?>
<!--                                                                <span class="label label-danger">ASAP</span>-->
                                                            <?php } else { ?>
<!--                                                                <span class="label label-success">Active</span>-->
                                                            <?php } ?>
                                                        <?php }
                                                        ?>
                                                    </li>
                                                    <?php if (!$isSkipped): ?>
                                                        <li style="display: flex; gap: 10px;">
                                                        <?php if ($value->cancelled == 0 && $value->is_job_mode != 1) { ?>
                                                            <a href="#" class="confirm_cancellation"
                                                               onclick="cancelService(<?= $value->property_id ?>,<?= $value->customer_id ?>,<?= $value->program_id ?>,<?= $value->job_id ?>)">
                                                                <i class="fa fa-remove position-center"
                                                                   title="Cancel Service"
                                                                   style="color: #9a9797; size: 16px"></i>
                                                            </a>
                                                            <?php if (empty($value->on_hold_status) && $value->asap == 0) { ?>
                                                                <a href="#" class="confirm_asap"
                                                                   onclick="markAsAsap(<?= $value->property_id ?>,<?= $value->customer_id ?>,<?= $value->program_id ?>,<?= $value->job_id ?>)">
                                                                    <i class="fa fa-level-up position-center"
                                                                       title="Mark as ASAP"
                                                                       style="color: #9a9797; size: 16px"></i>
                                                                </a>
                                                            <?php } ?>
                                                            <?php if (empty($value->on_hold_status)) { ?>
                                                                <a href="#" class="confirm_asap"
                                                                   onclick="holdUntilService(<?= $value->property_id ?>,<?= $value->customer_id ?>,<?= $value->program_id ?>,<?= $value->job_id ?>)">
                                                                    <i class="fa fa-pause position-center"
                                                                       title="Hold This Service"
                                                                       style="color: #9a9797; size: 16px"></i>
                                                                </a>
                                                            <?php } else { ?>
                                                                <a href="#" class="confirm_asap"
                                                                   onclick="stopHoldingService(<?= $value->property_id ?>,<?= $value->customer_id ?>,<?= $value->program_id ?>,<?= $value->job_id ?>)">
                                                                    <i class="fa fa-play position-center"
                                                                       title="Stop Holding Service"
                                                                       style="color: #9a9797; size: 16px"></i>
                                                                </a>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        </li>
                                                    <?php endif;?>
                                                </ul>
                                            </td>
                                            <?php if (isset($value->billing_type) && $value->billing_type == 1) { ?>
                                                <td>
                                                    <?php if (isset($value->invoice_id) && !empty($value->invoice_id)) { ?>
                                                        <a href="<?= base_url('admin/invoices/groupBillingPdf/') . $value->invoice_id ?>"
                                                           target="_blank" title="Print Work Order"
                                                           class=" button-next"><i
                                                                class=" icon-file-pdf position-center"
                                                                style="color: #9a9797;"></i></a>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                        </tr>

                                    <?php }
                                } ?>

                                </tbody>

                            </table>

                        </div>


                    </div>

                    <!-- INVOICES TAB -->
                    <div class="tab-pane <?php echo $active_nav_link == '3' ? 'active' : '' ?>"
                         id="highlighted-justified-tab3">


                        <div class="table-responsive table-spraye ">
                            <table class="table datatable-basic" id="DataTables_Table_0">
                                <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Amount</th>
                                    <th>Sent Status</th>
                                    <th>Payment Status</th>
                                    <th>Property Name</th>
                                    <th>Invoice Date</th>
                                    <th>Sent Date</th>
                                    <th>Opened Date</th>
                                    <th>Payment Date</th>
                                    <th>Refund Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php if (!empty($invoice_details)) {

                                    foreach ($invoice_details as $value) { ?>

                                        <tr>

                                            <td>
                                                <a href="<?= base_url('admin/Invoices/editInvoice/') . $value->invoice_id ?>"

                                                   target="_blank"
                                                   rel="noopener noreferrer"><?= $value->invoice_id; ?></a></td>

                                            <td><?php

                                                // echo $value->invoice_id;

                                                if (isset($value->total_cost_actual)) {

                                                    echo $value->total_cost_actual;

                                                } else {

                                                    echo '$ ' . $value->cost;

                                                }

                                                // echo $value->total_cost_actual;

                                                ?></td>


                                            <td><?php switch ($value->status) {

                                                    case 0:

                                                        echo '<span  class="label label-warning myspan">Unsent</span>';


                                                        break;

                                                    case 1:

                                                        echo '<span  class="label label-danger myspan">Sent</span>';


                                                        break;


                                                    case 2:

                                                        echo '<span  class="label label-success myspan">Opened</span>';


                                                        break;

                                                } ?>


                                            </td>

                                            <td><?php switch ($value->payment_status) {

                                                    case 0:

                                                        echo '<span  class="label label-warning myspan">Unpaid</span>';


                                                        break;

                                                    case 1:

                                                        echo '<span  class="label label-till myspan">Partial</span>';


                                                        break;


                                                    case 2:

                                                        echo '<span  class="label label-success myspan">Paid</span>';


                                                        break;

                                                    case 3:

                                                        echo '<span  class="label label-danger myspan">Past Due</span>';


                                                        break;
                                                    case 4:
                                                        echo '<span  class="label label-refunded myspan">Refunded</span>';
                                                        break;

                                                } ?>


                                            </td>


                                            <td><?= $value->property_title ?></td>

                                            <td><?= date('m-d-Y', strtotime($value->invoice_date)) ?></td>
                                            <td><?php if (isset($value->sent_date) && $value->sent_date !== '0000-00-00 00:00:00') {
                                                    echo date('m-d-Y', strtotime($value->sent_date));
                                                } else {
                                                    echo '';
                                                } ?></td>
                                            <td><?php if (isset($value->opened_date) && $value->opened_date !== '0000-00-00 00:00:00') {
                                                    echo date('m-d-Y', strtotime($value->opened_date));
                                                } else {
                                                    echo '';
                                                } ?></td>
                                            <td><?php if (isset($value->payment_created) && $value->payment_created !== '0000-00-00 00:00:00') {
                                                    echo date('m-d-Y', strtotime($value->payment_created));
                                                } else {
                                                    echo '';
                                                } ?></td>
                                            <td><?php if (isset($value->refund_datetime) && $value->refund_datetime !== '0000-00-00 00:00:00') {
                                                    echo date('m-d-Y', strtotime($value->refund_datetime));
                                                } else {
                                                    echo '';
                                                } ?></td>
                                            <td>


                                                <ul style="list-style-type: none; padding-left: 0px;">


                                                    <li style="display: inline; padding-right: 10px;">

                                                        <a href="<?= base_url('admin/invoices/printInvoice/') . $value->invoice_id ?>"

                                                           target="_blank" class=" button-next"><i

                                                                class=" icon-file-pdf position-center"

                                                                style="color: #9a9797;"></i></a>

                                                    </li>

                                                    <li style="display: inline; padding-right: 10px;">

                                                        <a href="<?= base_url('admin/invoices/printInvoice/') . $value->invoice_id ?>"

                                                           target="_blank" class=" button-next"><i

                                                                class="icon-printer2 position-center"

                                                                style="color: #9a9797;"></i></a>

                                                    </li>

                                                </ul>

                                            </td>


                                        </tr>

                                    <?php }
                                } ?>

                                </tbody>

                            </table>

                        </div>


                    </div>

                    <!-- PROPERTIES TAB -->
                    <div class="tab-pane <?php echo $active_nav_link == '4' ? 'active' : '' ?> "
                         id="highlighted-justified-tab4">

                        <div class="table-responsive table-spraye">

                            <table class="table" id="editcustmerpropertytbl">
                                <thead>
                                <tr>

                                    <th>Property Name</th>
                                    <th>Address</th>
                                    <th>Status</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                if (!empty($selectedPropertyDetailsList)) {
                                    foreach ($selectedPropertyDetailsList as $value) {
                                        // die(print_r($value));


                                        $prop_stat = '';

                                        if ($value->status == 2) {
                                            $prop_stat = '<span class="label label-gray" style= "background-color: #808080!important; border-color: #808080;">Prospect</span>';
                                        } elseif ($value->status == 1) {
                                            $prop_stat = '<span class="label label-success">Active</span>';
                                        } elseif ($value->status == 4) {
                                            $prop_stat = '<span class="label label-primary">Sales Call Scheduled</span>';
                                        } elseif ($value->status == 5) {
                                            $prop_stat = '<span class="label label-primary">Estimate Sent</span>';
                                        } elseif ($value->status == 6) {
                                            $prop_stat = '<span class="label label-primary">Estimate Decline</span>';
                                        } else {
                                            $prop_stat = '<span class="label label-danger">Non-active</span>';
                                        }

                                        echo '<tr>
                                              <td> <a href="' . base_url("admin/editProperty/") . $value->id . '"> ' . $value->title . '</a>  </td>
                                              <td>' . $value->address . '</td>
                                              <td>' . $prop_stat . '</td>
                                            </tr>';


                                    }
                                } ?>


                                </tbody>
                            </table>

                        </div>

                    </div>

                    <!-- NOTES TAB -->
                    <div class="tab-pane<?php echo $active_nav_link == '5' ? ' active' : '' ?>"
                         id="highlighted-justified-tab5">

                        <div id="note-form-wrap" class="collapse">

                            <form class="form-horizontal" action="<?= base_url('admin/createNote') ?>" method="post"
                                  name="createnoteform" enctype="multipart/form-data" id="createnoteform"
                                  onSubmit="formFileSizeValidate(this)">
                                <fieldset class="content-group">
                                    <input type="hidden" name="note_customer_id" class="form-control"
                                           value="<?= $customerData['customer_id']; ?>">
                                    <input type="hidden" name="note_category" class="form-control" value="1">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Assign Property</label>
                                                <div class="col-lg-9">
                                                    <select class="form-control" name="note_property_id"
                                                            placeholder="None">
                                                        <?php
                                                        if (!empty($all_customer_properties)) {
                                                            foreach ($all_customer_properties as $value) {
                                                                if (isset($value->property_status) && $value->property_status != 0 && in_array($value->property_id, $selectedpropertylist)) { ?>
                                                                    <option
                                                                        value="<?= $value->property_id; ?>"><?= $value->property_title; ?></option>
                                                                <?php }
                                                            }
                                                        } ?>
                                                    </select>
                                                    <span
                                                        style="color:red;"><?php echo form_error('note_property_id'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Note Type</label>
                                                <div class="col-lg-9">

                                                    <select class="form-control" name="note_type" required
                                                            id="notetypenotcusedi">

                                                        <option value="" disabled selected></option>

                                                        <?php foreach ($note_types as $type) : ?>

                                                            <option
                                                                value="<?= $type->type_id; ?>"><?= $type->type_name; ?></option>

                                                        <?php endforeach; ?>

                                                    </select>

                                                    <span
                                                        style="color:red;"><?php echo form_error('note_type'); ?></span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 assignservicesedicustumer" id="assignservicesedicustumer">
                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Assign Services</label>
                                                <div class="col-lg-9">
                                                    <select class="form-control" name="note_assigned_services">
                                                        <!-- Add Users available within company with Value = user_id / option shown user_name -->
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
                                                        style="color:red;"><?php echo form_error('note_assigned_services'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 assignservicesedicustumer">
                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Note Duration</label>
                                                <div class="col-lg-9">
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
                                        <div class="col-md-3">
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

                                                    <span
                                                        style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Due Date</label>
                                                <div class="col-lg-9">

                                                    <input id="note_due_date" type="text" name="note_due_date"
                                                           class="form-control pickaalldate" placeholder="YYYY-MM-DD">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-lg-3" style="font-size:16px;">Attach
                                                    Documents</label>
                                                <input id="files" type="file" name="files[]"
                                                       class="form-control-file col-lg-9" multiple
                                                       onChange="fileValidationCheck(this)"
                                                       style="display:inline-block; padding-top: 12px;">
                                                <span style="color:red;"><?php echo form_error('files'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-lg-9" style="font-size:16px;">Include in Technician View?</label>
                                                <input id="include_in_tech_view" type="checkbox"
                                                       name="include_in_tech_view"
                                                       class="checkbox checkbox-inline text-right col-lg-3 switchery_technician_view" value="1">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-lg-9" style="font-size:16px;">Include in Customer View?</label>
                                                <input id="include_in_customer_view" type="checkbox"
                                                       name="include_in_customer_view"
                                                       class="checkbox checkbox-inline text-right col-lg-3 switchery_customer_view" value="1">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-lg-9" style="font-size: 16px;">Urgent Note</label>
                                                <input type="checkbox"
                                                       name="is_urgent"
                                                       id="is_urgent"
                                                       class=" col-lg-3 checkbox checkbox-inline text-right switchery_urgent_note">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-lg-9" style="font-size: 16px;">Notify Me</label>
                                                <input type="checkbox"
                                                       name="notify_me"
                                                       id="notify_me"
                                                       checked
                                                       class="col-lg-3 checkbox checkbox-inline text-right switchery_notify_me">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-lg-9" style="font-size: 16px;">Enable Notification</label>
                                                <input type="checkbox" onchange="toggle_notification_to();"
                                                       name="is_enable_notifications"
                                                       id="is_enable_notifications"
                                                       class="col-lg-3 checkbox checkbox-inline text-right switchery_enable_notification">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group toggle_notification_to" style="display: none;">
                                                <label class="control-label col-lg-3" style="font-size: 16px;">
                                                    Notification To</label>
                                                <div class="multi-select-full col-lg-9">
                                                    <select class="multiselect-select-all-filtering form-control note-filter" name="notification_to[]" id="notification_to" multiple="multiple">
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
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label col-lg-1" style="font-size:16px;">Note Contents</label>
                                                <div class="col-lg-11">
                                                <textarea class="form-control" name="note_contents"
                                                          id="note_contents" rows="5" required></textarea>
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

                        <hr>


                        <div id="note-type-filter">

                            <div class="form-group">

                                <div class="col-md-3 pull-right text-right text-white">
                                    <button type="button" class="btn" id="addNoteBtn"
                                            data-target="#note-form-wrap" data-toggle="collapse"
                                            aria-expanded="false" aria-controls="note-form-wrap"
                                            style="margin-left=15px;"><i id="addNoteBtnIco"
                                                                         class="icon-plus22"></i> Add New
                                        Note
                                    </button>
                                </div>
                                <div class="row">

                                    <div class="col-md-6" style="padding-top: 35px">

                                        <div class="btn-group btn-group-justified" role="group" aria-label="tab-select" style="padding-top: 28px;">

                                            <div class="btn-group properties-tab-parent" role="group">

                                                <button type="button"
                                                        class="btn btn-default properties-tab-btn properties-tab-active"
                                                        id="note-filter-all">All Notes
                                                </button>

                                            </div>

                                            <div class="btn-group properties-tab-parent" role="group">

                                                <button type="button" class="btn btn-default properties-tab-btn"
                                                        id="note-filter-customer">Customer Notes
                                                </button>

                                            </div>

                                            <div class="btn-group properties-tab-parent" role="group">

                                                <button type="button" class="btn btn-default properties-tab-btn"
                                                        id="note-filter-property">Property Notes
                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6 col-sm-12">

                                        <div class="row">

                                            <div class="col-md-6" style="padding-left:15px;">

                                                <label for="note_property_filter">Property Filter</label>

                                                <select class="form-control" name="note_property_filter"
                                                        id="note_property_filter" placeholder="None">

                                                    <option value="0">None</option>

                                                    <?php

                                                    if (!empty($customer_properties)) {
                                                        foreach ($customer_properties as $value) {

                                                            if (in_array($value->property_id, $selectedpropertylist)) { ?>


                                                                <option
                                                                    value="<?= $value->property_id; ?>"><?= $value->property_title; ?></option>


                                                            <?php }

                                                        }
                                                    } ?>

                                                </select>

                                            </div>
                                            <div class="col-md-6" style="padding-left:15px;">
                                                <label for="note_status_filter">Status</label>
                                                <select class="form-control" name="note_status_filter"
                                                        id="note_status_filter">
                                                    <option value="0" selected>None</option>
                                                    <option value="1">Open</option>
                                                    <option value="2">Closed</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>


                                </div>

                            </div>

                        </div>

                        <!-- Notes -->

                        <?php

                        $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

                        $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

                        ?>

                        <div class="row" id="note_tab_contents">

                            <div class="col-md-12">


                                <?php if (!empty($combined_notes)) {

                                    foreach ($combined_notes as $note) { ?>

                                        <div class="well note-element<?= ($note->note_category == 0) ? ' property-note' : ' customer-note' ?>"
                                             data-note-id="<?= $note->note_id; ?>"
                                             data-note-status="<?= (!empty($note->note_assigned_user)) ? $note->note_status : '0'; ?>"
                                             is_urgent="<?= $note->is_urgent; ?>"
                                        >

                                            <div class="row note-header">

                                                <div class="col-md-8 user-info">
                                                    <div class="note-details">

                                                        <?php if (!empty($note->note_customer_id)) : ?>
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

                                                            <li class="dropdown-header text-bold text-uppercase">
                                                                Actions
                                                            </li>

                                                            <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2') ? ' disabled' : ''; ?>"
                                                                id="note-assign-btn-<?= $note->note_id; ?>"><a
                                                                    href="javascript:showAssignUserSelect(<?= $note->note_id; ?>)"><i
                                                                        class="fa fa-user-circle-o"
                                                                        aria-hidden="true"></i>Assign Specific User</a>
                                                            </li>

                                                            <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2') ? ' disabled' : ''; ?>">
                                                                <a href="javascript:showDueDateSelect(<?= $note->note_id; ?>)"><i
                                                                        class="fa fa-calendar"
                                                                        aria-hidden="true"></i>Edit Due Date</a>
                                                            </li>

                                                            <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2') ? ' disabled' : ''; ?>">
                                                                <a href="javascript:showNoteTypeSelect(<?= $note->note_id; ?>)"><i
                                                                        class="fa fa-pencil-square-o"
                                                                        aria-hidden="true"></i>Change Note Type</a>
                                                            </li>

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
                                                                            aria-hidden="true"></i>Mark Complete</a>
                                                                </li>
                                                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon">
                                                                    <a href="<?= base_url('admin/toggleUrgentMarker/') . $note->note_id . '/' . ($note->is_urgent ? 0 : 1); ?>"><i
                                                                            class="fa fa-check-square-o" aria-hidden="true"></i><?= $note->is_urgent ? 'Remove Urgent Status' : 'Mark Urgent'; ?></a>
                                                                </li>

                                                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon">
                                                                    <a href="<?= base_url('admin/deleteNote/') . $note->note_id; ?>"><i
                                                                            class="fa fa-trash-o"
                                                                            aria-hidden="true"></i>Delete Note</a>
                                                                </li>

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
                                                              class="text-bold text-success" style="font-size: 1.2em">Task</span>

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
                                                                   style="margin-right: 5px;"></i> <input
                                                        type="checkbox"
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
                                                    <div class="form-group hidden"
                                                         id="update-notetype-<?= $note->note_id; ?>">
                                                        <label class="control-label">Note Type</label>
                                                        <select class="form-control" name="note_edit_type"
                                                                id="note_edit_type_<?= $note->note_id; ?>"
                                                                data-note-id="<?= $note->note_id; ?>"
                                                                data-note-typeid="<?= $note->note_type; ?>"
                                                                onchange="getNoteTypeUpdateVars(this)" value="">
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
                                                        <span
                                                            style="color:red;"><?php echo form_error('note_type'); ?></span>
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
                                                        <select class="form-control"
                                                                name="edit_assigned_service_note_duration"
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
                                                        <div class="creator-name col-sm-12 col-md-4 col-lg-3 ">
                                                            <span>Created by&nbsp;</span>
                                                            <span class="text-bold">
                                                                <?= $note->user_first_name; ?> <?= $note->user_last_name; ?>
                                                            </span>
                                                        </div>
                                                        <?php

                                                        if (isset($note->property_address) && isset($note->property_city)) {

                                                            ?>

                                                            <div
                                                                class="customer-address col-sm-12 col-md-8 col-lg-6 text-bold"
                                                                data-propertyid="<?= $note->property_id; ?>">

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
                                                                id="note_due_date_<?= $note->note_id; ?>"
                                                                type="text" name="note_due_date"
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

                                            <div class="note-comments collapse"
                                                 id="note-comments-<?= $note->note_id; ?>">

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

                                                                <form
                                                                    action="<?= base_url('admin/Note/addNoteComment') ?>"
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

                                                                        <input class="form-control"
                                                                               name="add-comment-input" id=""
                                                                               placeholder="Add Comment">

                                                                        <div class="input-group-btn">

                                                                            <!-- Buttons -->

                                                                            <button type="submit"
                                                                                    class="btn btn-primary pull-right">
                                                                                Post Comment
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
                                                                    class="icon-cloud-upload btn-ico"
                                                                    aria-hidden="true" data-toggle="collapse"
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
                                                                  onSubmit="formFileSizeValidate(this)">

                                                                <input type="hidden"
                                                                       value="<?= $this->session->userdata('id'); ?>"
                                                                       name="user_id">

                                                                <input type="hidden" value="<?= $note->note_id; ?>"
                                                                       name="note_id">

                                                                <div class="row row-extra-space">

                                                                    <div class="col-xs-12">

                                                                        <div class="form-group">

                                                                            <label
                                                                                class="control-label col-lg-4 text-right">Attach
                                                                                Documents</label>

                                                                            <div class="col-lg-8 text-left">

                                                                                <input id="files" type="file"
                                                                                       name="files[]"
                                                                                       class="form-control-file"
                                                                                       multiple
                                                                                       onchange="fileValidationCheck(this)">
                                                                                <span
                                                                                    style="color:red;"><?php echo form_error('files'); ?></span>
                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                    <button type="submit"
                                                                            class="btn btn-primary pull-right">Save
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

                                                                        <img
                                                                            src="<?= CLOUDFRONT_URL . $file->file_key; ?>"
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

                            <div class="wrapper_pagination">
                                <?= $pagination_links; ?>
                                <div class="dataTables_length">
                                    <label><span>Show:</span>
                                        <select name="per_page" onchange="customer_notes_filter()">
                                            <?php foreach ($per_page_arr as $value) { ?>
                                                <option value="<?= $value ?>" <?= isset($filter['per_page']) && $filter['per_page'] == $value ? 'selected' : '' ?>><?= $value ?></option>
                                                <?php
                                            } ?>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- /form horizontal -->

</div>

<!-- /content area -->

<div class="mydiv" style="display: none;">


</div>

<?= $this->load->view('partials/add_standalone_service_customer_modal') ?>

<!-- clover create payment modal -->

<div id="clover_payment_method" class="modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h6 class="modal-title">Add Payment Method</h6>

            </div>


            <form name="add_clover_payment" id="add_clover_payment" method="POST" enctype="multipart/form-data"

                  form_ajax="ajax">

                <div class="modal-body">

                    <div class="form-group">

                        <div class="row">

                            <div class="col-sm-12 col-md-9">

                                <label>Card Number</label>

                                <input type="text" class="form-control" name="clover_card_number"

                                       placeholder="Card Number"

                                       required>

                            </div>

                            <div class="col-sm-6 col-md-3" width="50%">

                                <label>Expiration Month</label>

                                <select class="form-control" name="clover_card_exp_m"

                                        required>

                                    <option value="">Select Month</option>

                                    <option value="1">January</option>

                                    <option value="2">February</option>

                                    <option value="3">March</option>

                                    <option value="4">April</option>

                                    <option value="5">May</option>

                                    <option value="6">June</option>

                                    <option value="7">July</option>

                                    <option value="8">August</option>

                                    <option value="9">September</option>

                                    <option value="10">October</option>

                                    <option value="11">November</option>

                                    <option value="12">December</option>

                                </select>

                            </div>

                            <div class="col-sm-6 col-md-3">

                                <label>Expiration Year</label>

                                <select class="form-control" name="clover_card_exp_y"

                                        required>

                                    <option value="">Select Year</option>

                                    <?php $cur_year = date('Y');

                                    for ($i = 0; $i <= 10; $i++) { ?>

                                        <option
                                            value="<?php echo $cur_year + $i; ?>"><?php echo $cur_year + $i; ?></option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="col-sm-6 col-md-3">

                                <label>CVV</label>

                                <input type="text" class="form-control number-only" name="clover_card_cvv"

                                       placeholder="CVV"

                                       maxlength="4" pattern="\d{4}"

                                       required>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                        <button type="submit" id="submitCloverPaymentMethod" class="btn btn-success"

                                data-customer="<?php echo $customerData['customer_id']; ?>">Save
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- /clover create payment modal -->


<!-- basys create payment modal -->

<div id="basys_payment_method" class="modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h6 class="modal-title">Add Payment Method</h6>

            </div>


            <form name="add_basys_payment" id="add_basys_payment" method="POST" enctype="multipart/form-data"

                  form_ajax="ajax">

                <div class="modal-body">

                    <div class="form-group">

                        <div class="row">

                            <div class="col-sm-12 col-md-9">

                                <label>Card Number</label>

                                <input type="text" class="form-control" name="card_number" placeholder="Card Number"

                                       required>

                            </div>

                            <div class="col-sm-12 col-md-3">

                                <label>Card Exp</label>

                                <input type="text" class="form-control" name="card_exp" placeholder="MM/YY" required>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                        <button type="submit" id="submitPaymentMethod" class="btn btn-success"

                                data-customer="<?= $customerData['customer_id'] ?>">Save
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- /basys create payment modal -->


<!-- clover update payment modal -->

<div id="clover_update_payment" class="modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h6 class="modal-title">Update Payment Method</h6>

            </div>


            <form name="update_clover_payment" id="update_clover_payment" method="POST" enctype="multipart/form-data"

                  form_ajax="ajax">

                <div class="modal-body">

                    <div class="form-group">

                        <div class="row">

                            <div class="col-sm-12 col-md-9">

                                <label>Card Number</label>

                                <input type="text" class="form-control" name="clover_card_number"

                                       placeholder="Card Number"

                                       required>

                            </div>

                            <div class="col-sm-3 col-md-3" width="50%">

                                <label>Expiration Month</label>

                                <select class="form-control" name="clover_card_exp_m"

                                        required>

                                    <option value="">Select Month</option>

                                    <option value="1">January</option>

                                    <option value="2">February</option>

                                    <option value="3">March</option>

                                    <option value="4">April</option>

                                    <option value="5">May</option>

                                    <option value="6">June</option>

                                    <option value="7">July</option>

                                    <option value="8">August</option>

                                    <option value="9">September</option>

                                    <option value="10">October</option>

                                    <option value="11">November</option>

                                    <option value="12">December</option>

                                </select>

                            </div>

                            <div class="col-sm-3 col-md-3">

                                <label>Expiration Year</label>

                                <select class="form-control" name="clover_card_exp_y"

                                        required>

                                    <option value="">Select Year</option>

                                    <?php $cur_year = date('Y');

                                    for ($i = 0; $i <= 10; $i++) { ?>

                                        <option
                                            value="<?php echo $cur_year + $i; ?>"><?php echo $cur_year + $i; ?></option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="col-sm-6 col-md-3">

                                <label>CVV</label>

                                <input type="text" class="form-control number-only" name="clover_card_cvv"

                                       placeholder="CVV"

                                       maxlength="4" pattern="\d{4}"

                                       required>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                        <button type="submit" id="submitCloverUpdatePayment" class="btn btn-success"

                                data-customer="<?= $customerData['customer_id'] ?>">Save
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- /clover update payment modal -->


<!-- basys update payment modal -->

<div id="modal_update_payment" class="modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h6 class="modal-title">Update Payment Method</h6>

            </div>


            <form name="update_basys_payment" id="update_basys_payment" method="POST" enctype="multipart/form-data"

                  form_ajax="ajax">

                <div class="modal-body">

                    <div class="form-group">

                        <div class="row">

                            <div class="col-sm-12 col-md-9">

                                <label>Card Number</label>

                                <input type="text" class="form-control" name="card_number" placeholder="Card Number"

                                       required>

                            </div>

                            <div class="col-sm-12 col-md-3">

                                <label>Card Exp</label>

                                <input type="text" class="form-control" name="card_exp" placeholder="MM/YY" required>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                        <button type="submit" id="submitUpdatePayment" class="btn btn-success"

                                data-customer="<?= $customerData['customer_id'] ?>">Save
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- /basys payment modal -->

<!-- add alert modal -->
<div id="modal_add_alert" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Alert</h6>
            </div>

            <form method="POST"
                  action="<?= base_url('admin/addAlert/') . $customerData['customer_id'] ?>"
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
                <div class="form-group">
                    <label for="property">Choose Property</label>
                    <select class="form-control" id="property" name="property">
                        <option value="">None</option>
                        <?php foreach ($all_customer_properties as $property) {
                            if (isset($property->property_status) && $property->property_status != 0) { ?>
                                <option
                                    value="<?= $property->property_id; ?>"><?= $property->property_title; ?></option>
                            <?php }
                        } ?>
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
<!-- end add alert modal -->

<!-- start add credit modal -->
<div id="modal_add_credit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Credit Payment</h6>
            </div>
            <div class="modal-body">
                <form method="POST"
                      action="<?= base_url('technician/addCreditPayment/') . $customerData['customer_id'] ?>">
                    <div class="form-group">
                        <span data-popup="tooltip-custom" title="" data-placement="top"
                              data-original-title="Enter the amount you want to add as credit for this customer."> <i
                                class=" icon-info22 tooltip-icon"></i> </span>
                        <label for="credit_amount">Enter Credit Amount</label>
                        <input class="form-control" required type="number" step="0.01" id="credit_amount"
                               name="credit_amount" maxlength="100" size="100" spellcheck="true">
                        <input class="form-control" required type="hidden" id="property_id" name="property_id"
                               value="<?= $selectedpropertylist ? $selectedpropertylist['0'] : 0 ?>">
                        <label for="payment_type">Payment Type</label>
                        <select class="form-control" id="payment_type" name="payment_type">
                            <option <?= $customerData['payment_type'] == "check" ? "selected" : "" ?> value="check">
                                Check
                            </option>
                            <option <?= $customerData['payment_type'] == "cash" ? "selected" : "" ?> value="cash">
                                Cash
                            </option>
                            <option <?= $customerData['payment_type'] == "other" ? "selected" : "" ?> value="other">
                                Other
                            </option>
                            <!-- <?php
                            //if($customerData['clover_autocharge'] == 1 || $customerData['basys_autocharge'] == 1):?>
               <option <?= $customerData//['payment_type'] == "card" ? "selected" : ""   ?> value="card" > Card </option>
             <?php //endif; ?> -->
                        </select>
                    </div>
                    <div class="col">
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">
                                <i class="icon-plus22"></i> Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end add credit modal -->

<!-- generate statement modal -->
<div id="modal_generate_statement" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content" style="height: 305px;">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Generate Customer Statement</h6>
            </div>
            <form method="POST"
                  action="<?= base_url('admin/invoices/getOpenInvoiceByCustomer/') . $customerData['customer_id'] ?>"
                  target="_blank" formtarget="_blank" style="margin-top: 20px; padding: 0 20px;">
                <div class="col">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control pickaalldate" placeholder="MM-DD-YYYY">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control pickaalldate" placeholder="MM-DD-YYYY">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <!-- <a style="margin-top: 26px; margin-left: 25px;" class="btn btn-warning" target="_blank" type="submit"  ><i class=" icon-file-pdf"> </i> Generate Statement</a> -->
                        <button class="btn btn-warning" type="submit"><i class=" icon-file-pdf"></i> Generate
                            Statement
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /generate statement modal -->
<!-- email statement modal -->
<div id="modal_statement_email" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content generate_statment_modal_content" style="height: 305px;">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Email Customer Statement</h6>
            </div>

            <form id="statement_form" method="POST"
                  action="<?= base_url('admin/invoices/getOpenInvoiceByCustomer/') . $customerData['customer_id'] ?>"
                  style="margin-top: 20px; padding: 0 20px;">

                <div class="col">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control pickaalldate" placeholder="MM-DD-YYYY">
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control pickaalldate" placeholder="MM-DD-YYYY">
                    </div>
                </div>
                <div class="col email_input_toggle" style="display: none;">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control email_statment" placeholder="Email">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <!-- <a style="margin-top: 26px; margin-left: 25px;" class="btn btn-warning"  ><i class=" icon-file-pdf"> </i> Generate Statement</a> -->
                        <button id='print_statment_button' class="btn btn-warning" type="button"><i
                                class=" icon-file-pdf"></i> Print
                            Statement
                        </button>
                        <!-- <a style="margin-top: 26px; margin-left: 25px;" class="btn btn-success"  ><i class=" icon-file-pdf"> </i> Generate Statement</a> -->
                        <button id='email_statment_button' class="btn btn-success" type="button"><i
                                class=" icon-file-pdf"></i> Send
                            To Email
                        </button>
                        <button id='send_statment_button' class="btn btn-success" type="submit" style="display: none;">
                            <i class=" icon-file-pdf"></i> Send
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- /email statement modal -->
<!-- Secondary email modal -->

<div id="modal_add_secondary_emails" class="modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h6 class="modal-title">Add Secondary Email</h6>

            </div>


            <form name="add_secondary_email" id="my_form" action="<?= base_url('admin/addSecondaryEmailDataJson') ?>"

                  method="post"

                  enctype="multipart/form-data" form_ajax="ajax">

                <div class="modal-body">

                    <div class="form-group">

                        <div class="row">

                            <div class="col-sm-12 col-md-12">

                                <label>Email</label>

                                <input type="email" class="form-control" name="secondary_email" placeholder="Email">

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                        <button type="submit" id="assignjob" class="btn btn-success">Save</button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>



<?php
$data = array(
    'polygon_bounds' => $polygon_bounds
);
?>
<?= $this->load->view('partials/add_property_modal', $data) ?>

<!-- Files Modal -->

<div id="file-display-modal" class="modal-files">

    <span class="close" id="close-file-display">&times;</span>

    <img class="modal-content" id="modal-file-image">

    <div id="caption"></div>

</div>

<?= $this->load->view('partials/add_customer_note_modal', $data) ?>

<!-- Cancel Property Modal -->
<div class="modal fade" id="modal_cancel_service">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Cancel Service</h6>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/cancelService') ?>" method="POST" enctype="multipart/form-data"
                      id="cancel-service-form">
                    <input type="hidden" name="property_id" id="cancel_property_id" value="">
                    <input type="hidden" name="customer_id" id="cancel_customer_id" value="">
                    <input type="hidden" name="program_id" id="cancel_program_id" value="">
                    <input type="hidden" name="job_id" id="cancel_job_id" value="">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>What is the reason for canceling?</label>
                                <select class="form-control" name="cancel_reasons" id="cancel_reasons" required>
                                    <option value="">Select an option</option>
                                    <?php if ($cancel_reasons) {
                                        foreach ($cancel_reasons as $reason) { ?>
                                            <option
                                                value="<?= $reason->cancel_name ?>"><?= $reason->cancel_name ?></option>
                                        <?php }
                                    } ?>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="other-reason-div" style="display:none; margin-top: 10px;">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Other Reason:</label>
                                <input type="text" class="form-control" name="other_reason">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="form-group">
                            <div class="col-md-12 checkbox-inline">
                                <label class="control-label col-md-11">Send cancellation email to customer?</label>
                                <input class="checkbox col-md-1" type="checkbox" name="customer_email"
                                       id="customer_email"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button id="submit-cancel-property" type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </div>
</div>
<!-- End Cancel Property Modal -->

<!-- Mark as ASAP -->
<div class="modal fade" id="modal_asap_service">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Mark Service ASAP</h6>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/markAsAsap') ?>" method="POST" enctype="multipart/form-data"
                      id="mark-as-asap-service-form">
                    <input type="hidden" name="property_id" id="asap_property_id" value="">
                    <input type="hidden" name="customer_id" id="asap_customer_id" value="">
                    <input type="hidden" name="program_id" id="asap_program_id" value="">
                    <input type="hidden" name="job_id" id="asap_job_id" value="">
                    <input type="hidden" name="original_customer" id="original_customer"
                           value="<?= $customerData['customer_id'] ?>">
                    <div class="row" id="reason-div" style="margin-top: 10px;">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Reason:</label>
                                <input type="text" class="form-control" name="reason">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button id="submit-mark-asap" type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </div>
</div>
<!-- End mark as ASAP Modal -->

<script>


    $('#subscribeButton').click(
        function () {

            if ($('#subscribeButton').prop('checked') == false) {

                $('#subscribeTooltip').removeAttr("data-popup");
                $('#subscribeTooltip').removeAttr("title");
                $('#subscribeTooltip').removeAttr("data-placement");


            } else {
                $('#subscribeTooltip').attr("data-popup", "tooltip-custom");
                $('#subscribeTooltip').attr("title", "Turning this option off will cause you to not receive any pre-service or post-service notifications when schedule services at your property. However, you will still receive invoices by email.");
                $('#subscribeTooltip').attr("data-placement", "top");


            }

        });


</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key=<?= GoogleMapKey ?>&libraries=geometry,drawing,places&callback=initAutocomplete"
    async defer></script>

<script>

    $(function () {

        // Property Notes

        $('#note-form-wrap').on('show.bs.collapse', () => {

            $('#addNoteBtnIco').removeClass('icon-plus22')

                .addClass('fa fa-minus');

        });

        $('#note-form-wrap').on('hide.bs.collapse', () => {

            $('#addNoteBtnIco').removeClass('fa fa-minus')

                .addClass('icon-plus22');

        });
        $('#note-form-wrap2').on('show.bs.collapse', () => {

            $('#addNoteBtnIco').removeClass('icon-plus22')
                .addClass('fa fa-minus');
            // $('.flex-div-data').height('185vh');

        });

        $('#note-form-wrap2').on('hide.bs.collapse', () => {

            $('#addNoteBtnIco').removeClass('fa fa-minus')

                .addClass('icon-plus22');
            // $('.flex-div-data').height('100vh');
        });
    });

    function salesRepNote(user_id) {
        $('#note_assigned_user').val(108).change();
        $('#modal_new_note').modal('show')

    }

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

</script>


<script>

    function goToCustomer() {

        var customer = $('#go_to_customer').val();

        var path = window.location.href.split("/")

        path.pop();
        path.pop();

        path.push(customer);

        var url = path.toString();

        url = url.replaceAll(",", "/");


        return window.location.href = url;

    }

    /**
     * Name: selectedProperty
     * Creator: Alvaro Muñoz
     * Related:
     * @returns redirect to same page with added property value.
     */
    function selectedProperty() {
        var customerSplit = window.location.pathname.split("/");
        //alert(customerSplit);
        var customer = 0;
        customer = customerSplit[3];


        var property = $('#selectProperty').val();

        var path = window.location.href.split("/")

        path.pop();
        if (path.length > 5) {
            path.pop()
        }
        ;


        path.push(customer);

        path.push(property);

        var url = path.toString();

        url = url.replaceAll(",", "/");


        return window.location.href = url;

    }


    // This example displays an address form, using the autocomplete feature

    // of the Google Places API to help users fill in the information.


    var placeSearch, autocomplete, autocomplete2;

    var componentForm = {

        street_number: 'short_name',

        route: 'long_name',

        locality: 'long_name',

        administrative_area_level_1: 'short_name',

        country: 'long_name',

        postal_code: 'short_name'

    };


    var place;
    $(document).on("click", "#auto-assign-service-area", () => {
        var polygon_array = <?php print $polygon_bounds ? json_encode($polygon_bounds) : "''";?>;
        console.log(polygon_array);
        console.log(place);

        polygon_array.forEach(elm => {

            var poly_draw = new google.maps.Polygon({
                paths: [JSON.parse(elm.latlng)]

            });

            if (typeof place.geometry['location'].lat === "function") {
                var lattitude = place.geometry['location'].lat();
                var longitude = place.geometry['location'].lng();
            } else {
                var lattitude = place.geometry['location'].lat;
                var longitude = place.geometry['location'].lng;
            }
            const propertyInPolygon = google.maps.geometry.poly.containsLocation({
                lat: lattitude,
                lng: longitude
            }, poly_draw) ? 1 : 0;
            if (propertyInPolygon) {
                $("select[name='property_area']").val(elm.property_area_cat_id);
            }
        });
    });

    function initAutocomplete() {

        // Create the autocomplete object, restricting the search to geographical

        // location types.


        autocomplete2 = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */

            (document.getElementById('autocomplete2')), {

                types: ['geocode']

            });

        autocomplete2.addListener('place_changed', function () {

            fillInAddress(autocomplete2, "2");

        });


    }


    function fillInAddress(autocomplete, unique) {

        // Get the place details from the autocomplete object.

        place = autocomplete.getPlace();


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

    function openTab(tab) {

        $("li." + tab).addClass('active');

        $("li").not("." + tab).removeClass('active');

    }

    function openNotesTab() {

        $('#note_tab_btn').click();

    }

    $('#reset_secondary_email_link').click(function () {

        swal({

            title: 'Email',

            text: "Do you want to reset field?",

            type: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#009402',

            cancelButtonColor: '#FFBE2C',

            confirmButtonText: 'Yes',

            cancelButtonText: 'No'

        }).then((result) => {

            if (result.value) {

                $('#secondary_email_list').html("");

                $("#secondary_email_list_hid").val("");

                $("#add_secondary_email_link i").addClass("pt-5");

                $("#reset_secondary_email_link").addClass("hidden");

            }


        });


    });

    $('#autofill').click(function () {

        if ($(this).prop("checked") == true) {

            var result = $('#autocomplete').val();

            $('#autocomplete2').val(result);


            $('#property_address_2').val($('#billing_street_2').val());

            $('#locality2').val($('#locality').val());

            $('#region2').val($('#region').val());

            $('#postal-code2').val($('#postal-code').val());

            var geocode_url = 'https://maps.googleapis.com/maps/api/geocode/json?key=<?=GoogleMapKey?>&address=' + $('#billing_street_2').val() + $('#locality').val() + $('#region').val() + $('#postal-code').val();

            fetch(geocode_url).then(response => response.json()).then(data => {
                //console.log(data.results[0])
                if (!data.results[0].formatted_address.substring(0, 6).toUpperCase().match($('#billing_street_2').val().substring(0, 6).toUpperCase())) {
                    alert('Please enter a valid property address');
                } else {
                    place = data.results[0];
                }
            })


        } else if ($(this).prop("checked") == false) {

            $('#autocomplete2').val('');

            $('#property_address_2').val('');

            $('#locality2').val('');

            $('#region2').val('');

            $('#postal-code2').val('');


        }


    });


    function keydownAddress2() {

        $("#autofill")[0].checked = false;

        $("#uniform-autofill").find("span").removeClass("checked");

    }


    function assignProperty(type) {


        if (type == 1) {


            var customer_id = '<?php echo $customerData['customer_id'] ?>';


            $('.addcustomeridinmodal').html(
                '<select name="assign_customer[]" id="multipleCutomerId" multiple="multiple"><option value="' +

                customer_id + '" selected >Customer</option></select>')

            $('#sales_tax').html(
                '<select name="assign_customer[]" id="multipleCutomerId" multiple="multiple"><option value="' +

                customer_id + '" selected >Customer</option></select>')


        } else {


            $('.addcustomeridinmodal').html('');


        }

    }


    $('#editcustmerpropertytbl').DataTable({

        dom: 'l<"adcustomerpropertydiv">frtip',

        initComplete: function () {

            $("div.adcustomerpropertydiv")

                .html(
                    '<div class="btn-group"><div class=""><a data-toggle="modal" data-target="#modal_add_property"><button type="submit" onclick="assignProperty(1)"  class="btn btn-success"><i class="icon-add"  ></i> Add Property</button></a></div></div>'
                );

        }

    });


    $('#modal_add_property').on('hidden.bs.modal', function () {

        assignProperty(2);

    });


    ///BASYS AUTOCHARGE

    $(function () {

        var autocharge = document.querySelector('.switchery-autocharge');

        var switchery = new Switchery(autocharge, {

            color: '#36c9c9',

            secondaryColor: "#dfdfdf",

        });

        var is_email = document.querySelector('.switchery-is-email');

        var switchery = new Switchery(is_email, {

            color: '#36c9c9',

            secondaryColor: "#dfdfdf",

        });

        var is_mobile = document.querySelector('.switchery-is-mobile-text');

        var switchery = new Switchery(is_mobile, {

            color: '#36c9c9',

            secondaryColor: "#dfdfdf",

        });
        var is_autosend = document.querySelector('.switchery-is-autosend-invoices');
        var switchery = new Switchery(is_autosend, {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        });


    });
    $(document).ready(function () {
        if ($('input#autosend_frequency1').prop('checked') == true) {
            $('label#autosend_frequency1-label').addClass('active');
        }
        if ($('input#autosend_frequency2').prop('checked') == true) {
            $('label#autosend_frequency2-label').addClass('active');
        }
    });
    $('input[name="autosend_invoices"]').change(function () {
        if ($(this).prop("checked") == true) {
            var checked = true;
        } else {
            var checked = false;
        }
        if (checked != true) {
            $('#auto-send-invoices-div .btn-group-custom').css('display', 'none');
        } else {
            $('#auto-send-invoices-div .btn-group-custom').css('display', 'inline-block');
        }
    });


    var changeAutocharge = document.querySelector('.switchery-autocharge');

    changeAutocharge.onchange = function () {

        var clover_status = $('input[name="clover_status"]').val();

        var clover_token = $('input[name="customer_clover_token"]').val();

        var basys_status = $('input[name="basys_status"]').val();

        var basys_customer = $('input[name="basys_customer_id"]').val();


        if (clover_status == 1) {

            console.log('Clover Token: ' + clover_token);

            if (changeAutocharge.checked == true && clover_token == "") {

                $('#clover_payment_method').modal('show');

                $('#basys_payment_method').modal('hide');

            }

        } else if (basys_status == 1) {

            console.log('Basys Token: ' + basys_customer);

            if (changeAutocharge.checked == true && basys_customer == "") {


                $('#basys_payment_method').modal('show');

                $('#clover_payment_method').modal('hide');

            }

        } else {

            console.log('Neither is true');

            $('#basys_payment_method').modal('hide');

            $('#clover_payment_method').modal('hide');

        }


        //alert(changeAutocharge.checked);

    };

    $('button#submitCloverPaymentMethod').on('click', function (e) {

        e.preventDefault();

        var month = $('form#add_clover_payment select[name="clover_card_exp_m"]').val();

        var year = $('form#add_clover_payment select[name="clover_card_exp_y"]').val();

        var customer_id = $('#submitCloverPaymentMethod').data('customer');

        var card_number = $('form#add_clover_payment input[name="clover_card_number"]').val();

        var card_exp = Number(year + month);

        var card_cvv = $('form#add_clover_payment input[name="clover_card_cvv"]').val();


        //card_exp = card_exp.replace("/","");


        $.ajax({


            type: 'POST',

            url: "<?=base_url('admin/cloverAddCustomer')?>",

            data: {

                customer_id: customer_id,

                tokenData: {

                    account: card_number,

                    expiry: card_exp,

                    cvv: card_cvv

                }

            },

            dataType: "JSON",

            success: function (data) {

                console.log(data)

                // alert(data.status);

                if (data.status == 200) {

                    swal(
                        'Success!',

                        data.msg,

                        'success'
                    );

                    $('#clover_payment_method').modal('hide');

                } else if (data.status == "failed") {

                    if (data.msg) {

                        var msg = data.msg;

                        msg = msg.toUpperCase();

                        $('div#swal2-content').css('text-transform', 'capitalize');

                    } else {

                        msg = "Something went wrong. Please try again.";

                    }


                    swal({

                        confirmButtonColor: '#d9534f',

                        type: 'error',

                        title: 'Oops...',

                        text: msg

                    });

                } else {

                    swal({

                        confirmButtonColor: '#d9534f',

                        type: 'error',

                        title: 'Oops...',

                        text: 'Something went wrong. Please try again.'

                    });

                }

            }


        });


    });


    $('button#submitPaymentMethod').on('click', function (e) {

        e.preventDefault();

        var customer_id = $('#submitPaymentMethod').data('customer');

        var card_number = $('form#add_basys_payment input[name="card_number"]').val();

        var card_exp = $('form#add_basys_payment input[name="card_exp"]').val();


        //card_exp = card_exp.replace("/","");


        $.ajax({


            type: 'POST',

            url: "<?=base_url('admin/basysAddCustomer')?>",

            data: {

                customer_id: customer_id,

                card_number: card_number,

                card_exp: card_exp

            },

            dataType: "JSON",

            success: function (data) {

                console.log(data)

                // alert(data.status);

                if (data.status == "success") {

                    swal(
                        'Success!',

                        'Payment Method Added Successfully ',

                        'success'
                    );

                    $('#basys_payment_method').modal('hide');

                } else if (data.status == "failed") {

                    if (data.msg) {

                        var msg = data.msg;

                        msg = msg.toUpperCase();

                        $('div#swal2-content').css('text-transform', 'capitalize');

                    } else {

                        msg = "Something went wrong. Please try again.";

                    }


                    swal({

                        confirmButtonColor: '#d9534f',

                        type: 'error',

                        title: 'Oops...',

                        text: msg

                    });

                } else {

                    swal({

                        confirmButtonColor: '#d9534f',

                        type: 'error',

                        title: 'Oops...',

                        text: 'Something went wrong. Please try again.'

                    });

                }

            }


        });


    });


    $('button#submitUpdatePayment').on('click', function (e) {

        e.preventDefault();

        var basys_customer_id = $('input[name="basys_customer_id"]').val();

        var customer_id = $('#submitUpdatePayment').data('customer');

        var card_number = $('form#update_basys_payment input[name="card_number"]').val();

        var card_exp = $('form#update_basys_payment input[name="card_exp"]').val();


        //card_exp = card_exp.replace("/","");


        $.ajax({


            type: 'POST',

            url: "<?=base_url('admin/basysUpdateCustomerPayment')?>",

            data: {

                customer_id: customer_id,

                card_number: card_number,

                card_exp: card_exp,

                basys_customer_id: basys_customer_id

            },

            dataType: "JSON",

            success: function (data) {

                console.log(data)

                // alert(data.status);

                if (data.status == "success") {

                    swal(
                        'Success!',

                        'Payment Method Updated Successfully ',

                        'success'
                    );

                    $('#modal_update_payment').modal('hide');

                } else if (data.status == "failed") {

                    if (data.msg) {

                        var msg = data.msg;

                        msg = msg.toUpperCase();

                        $('div#swal2-content').css('text-transform', 'capitalize');

                    } else {

                        msg = "Something went wrong. Please try again.";

                    }


                    swal({

                        confirmButtonColor: '#d9534f',

                        type: 'error',

                        title: 'Oops...',

                        text: msg

                    });

                } else {

                    swal({

                        confirmButtonColor: '#d9534f',

                        type: 'error',

                        title: 'Oops...',

                        text: 'Something went wrong. Please try again.'

                    });

                }

            }


        });


    });

    $('button#submitCloverUpdatePayment').on('click', function (e) {

        e.preventDefault();

        var clover_token = $('input[name="customer_clover_token"]').val();

        var clover_acct = $('input[name="clover_acct_id"]').val();

        var month = $('form#update_clover_payment select[name="clover_card_exp_m"]').val();

        var year = $('form#update_clover_payment select[name="clover_card_exp_y"]').val();

        var customer_id = $('#submitCloverUpdatePayment').data('customer');

        var card_number = $('form#update_clover_payment input[name="clover_card_number"]').val();

        var card_exp = Number(year + month);

        var card_cvv = $('form#update_clover_payment input[name="clover_card_cvv"]').val();


        //card_exp = card_exp.replace("/","");


        $.ajax({


            type: 'POST',

            url: "<?=base_url('admin/cloverUpdateCustomerPayment')?>",

            data: {

                customer_id: customer_id,

                clover_token: clover_token,

                clover_acct: clover_acct,

                tokenData: {

                    account: card_number,

                    expiry: card_exp,

                    cvv: card_cvv

                }

            },

            dataType: "JSON",

            success: function (data) {

                console.log(data)

                // alert(data.status);

                if (data.status == 200) {

                    swal(
                        'Success!',

                        'Payment Method Updated Successfully ',

                        'success'
                    );

                    $('#clover_update_payment').modal('hide');

                } else if (data.status == 400) {

                    if (data.msg) {

                        var msg = data.msg;

                        msg = msg.toUpperCase();

                        $('div#swal2-content').css('text-transform', 'capitalize');

                    } else {

                        msg = "Something went wrong. Please try again.";

                    }


                    swal({

                        confirmButtonColor: '#d9534f',

                        type: 'error',

                        title: 'Oops...',

                        text: msg

                    });

                } else {

                    swal({

                        confirmButtonColor: '#d9534f',

                        type: 'error',

                        title: 'Oops...',

                        text: 'Something went wrong. Please try again.'

                    });

                }

            }


        });


    });

</script>


<!-- START script to ensure that only numbers are entered in Clover CVV field -->

<script>

    $("input.number-only").bind({

        keydown: function (e) {

            if (e.shiftKey === true) {

                if (e.which == 9) {

                    return true;

                }

                return false;

            }

            if (e.which > 57) {

                return false;

            }

            if (e.which == 32) {

                return false;

            }

            return true;

        }

    });

</script>

<!-- END script to ensure that only numbers are entered in Clover CVV field -->


<script>

    $(document).ready(function () {

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
        //this constant is used to only show below prompt for prop list change 1 time
        var PROMPT_SHOWN = "false";
        $('#property_list').on('change', function () {
            if (PROMPT_SHOWN == "false") {
                let billing_type = $('input[name="billing_type"]').val();
                let existing_properties = $(this).data('existingproperties');
                //	console.log("existing properties =" + existing_properties);
                existing_properties = numbersToArray(existing_properties);
                let properties = $(this).val();
                let selected_properties = numbersToArray(properties);
                //	console.log("selected properties =" + properties);
                let new_properties = [];
                $(selected_properties).each(function (index) {
                    if (!existing_properties.includes(selected_properties[index])) {
                        new_properties.push(selected_properties[index]);
                    }
                });
                //	console.log('new properties = '+ new_properties);
                if (billing_type == 1 && new_properties.length > 0) {
                    swal({
                        confirmButtonColor: '#3fc3ee',
                        type: 'info',
                        title: 'FYI',
                        html: '<p>This customer uses Group Billing.<br>Please review the property contact information when assigning an existing property to this customer type.</p>',
                    });
                    PROMPT_SHOWN = "true";
                }
            }
        });

        function numbersToArray(numbers) {
            let array = numbers.toString().split(",");
            return array.map(x => parseInt(x));
        }

        //check pre_service_notification
        //     if($("input[name='is_email']").prop("checked") == true){
        //         $("#pre_service_notification option[value=2]").attr('selected', 'selected');
        //         reinitMultiselect();
        //     }else{
        //         $("#pre_service_notification option[value=2]").removeAttr('selected');
        //      reinitMultiselect();
        // }

        //if($("input[name='is_mobile_text']").prop("checked") == true){$("#pre_service_notification option[value=3]").attr('selected', 'selected'); reinitMultiselect();}else{$("#pre_service_notification option[value=3]").removeAttr('selected'); reinitMultiselect();}
    });
    //flag for pre_service_notification email
    //$(document).on("click","input[name='is_email']",function(){if($(this).prop("checked") == true){$("#pre_service_notification option[value=2]").attr('selected', 'selected'); reinitMultiselect();}else{$("#pre_service_notification option[value=2]").attr('selected', false); reinitMultiselect();}})
    $(document).on("click", "input[name='is_email']", function () {
        if ($(this).prop("checked") == false) {
            $("#pre_service_notification option[value=2]").attr('selected', false);
            reinitMultiselect();
        }
    })

    //flag for pre_service_notification phone
    //$(document).on("click","input[name='is_mobile_text']",function(){if($(this).prop("checked") == true){$("#pre_service_notification option[value=3]").attr('selected', 'selected'); reinitMultiselect();}else{$("#pre_service_notification option[value=3]").attr('selected', false); reinitMultiselect();}})
    $(document).on("click", "input[name='is_mobile_text']", function () {
        if ($(this).prop("checked") == false) {
            $("#pre_service_notification option[value=3]").attr('selected', false);
            reinitMultiselect();
        }
    })


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
        var customer_view = document.querySelector('.switchery_customer_view');
        var switchery_customer_view = new Switchery(customer_view, config);
        var switchery_add_to_service_specific_note = document.querySelector('.switchery_add_to_service_specific_note');
        var switchery_add_to_service_specific_note = new Switchery(switchery_add_to_service_specific_note, config);

        register_switchery_create_note_modal();
        $('#note-filter-all, #note-filter-customer, #note-filter-property').click(function (e) {
            Array.from($('.properties-tab-btn')).forEach((btn) => $(btn).removeClass('properties-tab-active'));
            $(this).addClass('properties-tab-active');
            customer_notes_filter();

        });
        $('#note_property_filter').on('change', function (e) {
            customer_notes_filter();
        });
        $('#note_status_filter').on('change', function (e) {
            customer_notes_filter();
        });

        $('.page-link').on('click', function (e) {
            e.preventDefault();
            let page = parseInt($(this).text()) ? $(this).text() : $(this).attr('data-ci-pagination-page');
            customer_notes_filter(page);
        });

    });

    function register_switchery_create_note_modal() {
        var config = {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        };
        var urgent_note_modal = document.querySelector('.switchery_urgent_note_modal');
        var switchery_urgent_note_modal = new Switchery(urgent_note_modal, config);
        var notify_me_modal = document.querySelector('.switchery_notify_me_modal');
        var switchery_notify_me_modal = new Switchery(notify_me_modal, config);
        var enable_notification_modal = document.querySelector('.switchery_enable_notification_modal');
        var switchery_enable_notification_modal = new Switchery(enable_notification_modal, config);
        var technician_view_modal = document.querySelector('.switchery_technician_view_modal');
        var switchery_technician_view_modal = new Switchery(technician_view_modal, config);
        var customer_view_modal = document.querySelector('.switchery_customer_view_modal');
        var switchery_customer_view_modal = new Switchery(customer_view_modal, config);

        $('#note_type_modal').change(function () {
            var selected = $('#note_type_modal option:selected').text();
            if (selected == "Service-Specific") {
                $(".with_note_type_service_specific").show();
                $(".with_note_type_service_specific select").attr('required', true);
                $('#include_in_tech_view_modal').prop('checked', true);
            } else {
                $(".with_note_type_service_specific").hide();
                $(".with_note_type_service_specific select").attr('required', false);
                $('#include_in_tech_view_modal').prop('checked', false);
            }
        })
    }
    function toggle_notification_to(suffix = '') {
        if ($('#is_enable_notifications' + suffix).is(':checked')) {
            $('.toggle_notification_to' + suffix).show();
        } else {
            $('.toggle_notification_to' + suffix).hide();
        }
    }

    function customer_notes_filter(page = 1) {
        let note_category = $('.properties-tab-active')[0].id.split('-')[2];
        let note_status = $('#note_status_filter').val();
        let note_property = $('#note_property_filter').val();

        $.ajax({
            type: 'POST',
            url: '<?= base_url(); ?>admin/ajaxCustomerNotes?page=' + page,
            data: {
                note_category: note_category,
                note_status: note_status,
                note_property: note_property,
                customer_id: $('#customer_id').val(),
                page: page,
                per_page: $('[name="per_page"]').val(),
            },
            beforeSend: function () {
                $('#note_tab_contents').html('');
                $('.loading').css("display", "block");
            },
            success: function (html) {
                $(".loading").css("display", "none");
                $('#note_tab_contents').html(html);
            }
        });
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

    function techVisibleSwtch(el, id) {

        let num = (el.checked) ? 1 : 0;

        $.post("<?= base_url('admin/updateNoteTechView'); ?>", {'noteId': id, 'tech_view': num}, function (result) {

            console.log(result);

        });

    }

    function goToViewNote(id) {

        openNotesTab();

        $('#note-filter-all').click();

        let noteEl = $(`div.note-element[data-note-id="${id}"]`)[0];

        noteEl.scrollIntoView(true, {behavior: 'smooth'});

    }

    function addStandaloneService() {
        $(".loading").css("display", "block");
        var propertyId = $('#modal_add_service #property_id').val();
        var propertyName = $('#modal_add_service #property_id option:selected').text();
        var serviceId = $('#modal_add_service #service_id').val();
        var serviceName = $('#modal_add_service #service_id option:selected').text();
        var programPrice = $('#modal_add_service #program_price').val();

        $('#add_service_program_price').parent().children('.error').remove();
        $('#selected_job_id').parent().children('.error').remove();
        $('#modal_add_service').find('.error').remove();
        let error_label = '';
        let is_valid = true;
        if (propertyId === '') {
            error_label = '<label id="property-error" class="error" for="property_id">Please select a property</label>';
            $('#modal_add_service #property_id').parent().append(error_label);
            is_valid = false;
        }
        if (serviceId === '') {
            error_label = '<label id="service-error" class="error" for="job_id">Please select a service</label>';
            $('#modal_add_service #service_id').parent().append(error_label);
            is_valid = false;
        }
        if (programPrice === '') {
            error_label = '<label id="program-price-error" class="error" for="program_price">Please select a pricing method</label>';
            $('#modal_add_service #program_price').parent().append(error_label);
            is_valid = false;
        }
        let add_to_service_specific_note = $('#modal_add_service #add_to_service_specific_note').is(':checked');
        let note_contents = $('#modal_add_service #note_contents').val();
        if (add_to_service_specific_note && note_contents == '') {
            error_label = '<label id="note_contents-error" class="error" for="note_contents">Please input your note contents</label>';
            $('#modal_add_service #note_contents').parent().append(error_label);
            is_valid = false;
        }
        if (!is_valid) return;

        var post = [];
        var programName = serviceName + " - Standalone";
        var priceOverride = $('#modal_add_service #price_override').val();

        var property = {
            service_id: serviceId,
            hold_until_date: $('#modal_add_service #hold_until_date').val(),
            add_to_service_specific_note: add_to_service_specific_note,
            note_contents: note_contents,
            service_name: serviceName,
            property_id: propertyId,
            property_name: propertyName,
            program_name: programName,
            is_price_override_set: priceOverride ? 1 : 0,
            price_override: priceOverride,
            program_price: programPrice
        };
        post.push(property);

        $.ajax({
            type: 'POST',
            url: "<?=base_url('admin/job/addJobToProperty')?>",
            data: {post},
            dataType: "JSON",
        }).done(function(data){
            $(".loading").css("display", "none");
            if (data.status === "success") {
                $('#modal_add_service').modal('hide');
                swal(
                    'Success!',
                    'Service Added Successfully',
                    'success'
                ).then(function() {
                    window.location.reload();
                })
            } else {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Please select a service and a pricing method'
                })
            }
        });
    }
</script>
<!-- Debug Var Dumps -->
<script>

    var currentUser = <?= print_r(json_encode($currentUser), TRUE); ?>;

    var notes = <?= print_r(json_encode($combined_notes), TRUE); ?>;

</script>
<script>
    function markAsAsap(propertyId, customerId, programId, jobId) {
        $('input#asap_property_id').val(propertyId);
        $('input#asap_customer_id').val(customerId);
        $('input#asap_program_id').val(programId);
        $('input#asap_job_id').val(jobId);
        $('#modal_asap_service').modal('show');
    }

    function holdUntilService(propertyId, customerId, programId, jobId) {
        let job_data = "" + customerId + "," + jobId + "," + programId + "," + propertyId + "";
        let all_service_data = [];
        all_service_data.push(job_data);
        document.querySelector('#hold_date_job_data_csv').value = JSON.stringify(all_service_data);

        $('#modal_hold_until_services').find('.hold_service_text label').text('Hold this service until the following date:');
        $('#modal_hold_until_services').modal('show');
    }

    function stopHoldingService(propertyId, customerId, programId, jobId) {
        swal({
            title: 'Are you sure?',
            text: "Stop Holding This Service.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?= base_url('admin/setting/stopHoldingService') ?>",
                    data: {
                        property_id: propertyId,
                        customer_id: customerId,
                        program_id: programId,
                        job_id: jobId,
                    },
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function () {
                        $("#loading").css("display", "block");
                    },
                    success: function (e) {
                        $("#loading").css("display", "none");
                        if (e != 0 && e != 1) {
                            swal(
                                'Stop Holding Service',
                                'Stop Holding Failed',
                                'error'
                            )
                        } else {
                            swal(
                                'Stop Holding Service',
                                'Stopped Holding Successfully',
                                'success'
                            )
                        }
                        window.location.reload();
                    },
                    error: function (e) {
                        $("#loading").css("display", "none");
                        swal(
                            'Stop Holding Service',
                            'Something went wrong',
                            'error'
                        )
                    }
                });
            }
        });
    }

    // handle cancel property
    function cancelService(propertyId, customerId, programId, jobId) {
        $('input#cancel_property_id').val(propertyId);
        $('input#cancel_customer_id').val(customerId);
        $('input#cancel_program_id').val(programId);
        $('input#cancel_job_id').val(jobId);
        $('#modal_cancel_service').modal('show');
    }

    $('select#cancel_reasons').change(function () {
        let selected = $(this).val();
        if (selected == 'other') {
            $('div#other-reason-div').show();
        } else {
            $('div#other-reason-div').hide();
        }
    });
    $('#submit-cancel-property').click(function (e) {
        e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover this service.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $('form#cancel-service-form').submit();
            } else {
                $('#modal_cancel_service').modal('hide');
            }
        });
    });

    $('#submit-mark-asap').click(function (e) {
        e.preventDefault();
        $('form#mark-as-asap-service-form').submit();
    });

    // Select and show Div
    $(document).ready(function () {
        $(".assignservicesedicustumer").hide();
    });
    $("#notetypenotcusedi").change(function () {
        var selected = $('#notetypenotcusedi option:selected').text();
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

    //assign properties autocomplete code:


    $(document).ready(function () {

        var hovered = false;
        var inputFocus = false;


        $("#suggestion-box").bind("mouseover", function () {
            hovered = true;
        }).bind("mouseout", function () {
            hovered = false;
        });


        $("#property_list_field").keyup(function () {

            if ($("#property_list_field").val() == "" || $("#property_list_field").val() == null) {
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
                    url: "<?php echo base_url('admin/assignPropertyList') ?>",
                    data: 'keyword=' + snippet,
                    /* beforeSend: function() {
                        $("#property_list_field").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                    }, */
                    success: function (data) {
                        if (data != false) {
                            $("#suggestion-box").show();
                            $("#suggestion-box").html(data);
                            //$("#property_list_field").css("background", "#FFF");


                            let properties = $('#property_list').val();


                            for (let i = 0; i < properties.length; i++) {

                                var ul = document.getElementById("suggestion-box");
                                var items = ul.getElementsByTagName("li");

                                //console.log(items);

                                for (var k = 0; k < items.length; k++) {
                                    if (items[k].getAttribute("data-id") == properties[i]) {
                                        items[k].classList.add("selected");
                                    }
                                }

                            }

                        }
                    }
                });
            }
        });

        $("#property_list_field").focusout(function () {
            inputFocus = false;


            if (!hovered) {
                $("#suggestion-box").hide();
            }


            $('#property_list_field').val("");

            let selectedValues = $('#property_list').val();

            for (let i = 0; i < selectedValues.length; i++) {
                $('#property_list_field').val($('#property_list_field').val() + $('#property_list_div select option[value="' + selectedValues[i] + '"]').text() + ", ");

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


        $("#property_list_field").focusin(function () {

            inputFocus = true;

            if ($("#property_list_field").val() != "" && $("#property_list_field").val() != null) {


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
                        url: "<?php echo base_url('admin/assignPropertyList') ?>",
                        data: 'keyword=' + snippet,
                        /* beforeSend: function() {
                                $("#property_list_field").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                            }, */
                        success: function (data) {
                            if (data != false) {
                                $("#suggestion-box").show();
                                $("#suggestion-box").html(data);
                                //$("#property_list_field").css("background", "#FFF");

                                let properties = $('#property_list').val();


                                for (let i = 0; i < properties.length; i++) {


                                    var ul = document.getElementById("suggestion-box");
                                    var items = ul.getElementsByTagName("li");

                                    //console.log(items);

                                    for (var k = 0; k < items.length; k++) {
                                        if (items[k].getAttribute("data-id") == properties[i]) {
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

    });

    //this constant is used to only show below prompt for prop list change 1 time
    var PROMPT_SHOWN = "false";


    //To select a property name
    function selectProperty(obj, val, title) {

        if (obj.hasClass('selected')) {

            var SelectedPropNames = $('#property_list_field').val().split(", ");

            SelectedPropNames.pop();

            for (let i = 0; i < SelectedPropNames.length; i++) {
                if (SelectedPropNames[i] == title) {
                    SelectedPropNames.splice(i, 1);
                }
            }

            $("#property_list_field").val("");

            for (let i = 0; i < SelectedPropNames.length; i++) {
                if (i == 0) {
                    $("#property_list_field").val(SelectedPropNames[i] + ", ");
                } else {
                    $("#property_list_field").val($("#property_list_field").val() + SelectedPropNames[i] + ", ");
                }
            }

            $('#property_list_div select option[value="' + val + '"]').remove();


            obj.removeClass('selected');

        } else {
            let valueOfPropertyListField = $("#property_list_field").val();

            if (valueOfPropertyListField == "" || !(valueOfPropertyListField.includes(', '))) {
                $("#property_list_field").val(title + ", ");
            } else {

                $("#property_list_field").val($("#property_list_field").val() + title + ", ");
            }


            $('#property_list_div select').append(`<option selected value="` + val + `" title="` + title + `">` + title + `</option>`);


            obj.addClass('selected');

        }


        if (PROMPT_SHOWN == "false") {
            let billing_type = $('input[name="billing_type"]').val();
            let existing_properties = $('#property_list').data('existingproperties');
            //	console.log("existing properties =" + existing_properties);
            existing_properties = numbersToArray(existing_properties);
            let properties = $('#property_list').val();
            let selected_properties = numbersToArray(properties);
            //	console.log("selected properties =" + properties);
            let new_properties = [];
            $(selected_properties).each(function (index) {
                if (!existing_properties.includes(selected_properties[index])) {
                    new_properties.push(selected_properties[index]);
                }
            });
            //	console.log('new properties = '+ new_properties);
            if (billing_type == 1 && new_properties.length > 0) {
                swal({
                    confirmButtonColor: '#3fc3ee',
                    type: 'info',
                    title: 'FYI',
                    html: '<p>This customer uses Group Billing.<br>Please review the property contact information when assigning an existing property to this customer type.</p>',
                });
                PROMPT_SHOWN = "true";
            }
        }


    }

    function numbersToArray(numbers) {
        let array = numbers.toString().split(",");
        return array.map(x => parseInt(x));
    }

    //this constant is used to only show below prompt for prop list change 1 time
    /* var PROMPT_SHOWN = "false";
    $('#property_list').on('change',function(){
        if(PROMPT_SHOWN == "false"){
            let billing_type = $('input[name="billing_type"]').val();
            let existing_properties = $(this).data('existingproperties');
            //	console.log("existing properties =" + existing_properties);
            existing_properties = numbersToArray(existing_properties);
            let properties = $(this).val();
            let selected_properties = numbersToArray(properties);
            //	console.log("selected properties =" + properties);
            let new_properties = [];
            $(selected_properties).each(function(index){
                if(!existing_properties.includes(selected_properties[index])){
                    new_properties.push(selected_properties[index]);
                }
            });
            	//	console.log('new properties = '+ new_properties);
            if(billing_type == 1 && new_properties.length > 0){
                swal({
                    confirmButtonColor:'#3fc3ee',
                    type: 'info',
                    title: 'FYI',
                    html: '<p>This customer uses Group Billing.<br>Please review the property contact information when assigning an existing property to this customer type.</p>',
                });
                PROMPT_SHOWN = "true";
            }
        }
    }); */

</script>
<script>
    //select a customer autocomplete code:


    $(document).ready(function () {
        $("#go_to_customer_field").keyup(function () {

            if ($("#go_to_customer_field").val() == "" || $("#go_to_customer_field").val() == null) {
                $("#suggestion-box2").hide();
                return;
            }

            $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin/assignCustomerListEditCustomer') ?>",
                data: 'keyword=' + $(this).val(),
                /* beforeSend: function() {
                    $("#go_to_customer_field").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                }, */
                success: function (data) {
                    if (data != false) {
                        $("#suggestion-box2").show();
                        $("#suggestion-box2").html(data);
                        //$("#go_to_customer_field").css("background", "#FFF");
                    }
                }
            });
        });

        $("#go_to_customer_field").focusout(function () {
            setTimeout(() => {
                $("#suggestion-box2").hide();
            }, "300");
        });


        $("#go_to_customer_field").focusin(function () {

            if ($("#go_to_customer_field").val() != "" && $("#go_to_customer_field").val() != null) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('admin/assignCustomerListEditCustomer') ?>",
                    data: 'keyword=' + $(this).val(),
                    /* beforeSend: function() {
                        $("#go_to_customer_field").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                    }, */
                    success: function (data) {
                        if (data != false) {
                            $("#suggestion-box2").show();
                            $("#suggestion-box2").html(data);
                            //$("#go_to_customer_field").css("background", "#FFF");
                        }
                    }
                });


            }

        });


    });

    //To select a customer name
    function selectCustomer(val, name) {

        $("#go_to_customer").val(val);

        $("#go_to_customer_field").val(name);


        $("#suggestion-box2").hide();
    }

</script>







