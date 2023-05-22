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
    #estimatetablediv{
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
    .label-orange , .bg-orange  {
        background-color: #FFA500;
        background-color: #FFA500;
        border-color: #FFA500;
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


</style>
<?php
    $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
?>

<!-- Content area -->
<div class="content invoicessss">
    <!-- Form horizontal -->
    <div id="loading" >
        <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
    </div>
    <div class="panel-body">
        <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
        <?php
        // only show the warning about the highlighted rows if some are highlighted
        $html_to_show = '';
        if (!empty($estimate_details)) {
            foreach ($estimate_details as $value) {
                if($value->signwell_id == "" && $value->signwell_status == "1") {
                    $html_to_show = '<b>Highlighted rows had an error sending to SignWell and need to be sent through SignWell again.</b>';
                }
            }
        }
        echo $html_to_show;
        ?>
        <div id="estimatetablediv">
            <div  class="table-responsive table-spraye">
                <table  id= "estimatetable"class="table datatable-filter-custom">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all"    /></th>
                        <th>Estimate #</th>
                        <th>Customer Name</th>
                        <th>Property</th>
                        <th>Total Estimate Cost</th>
                        <th>Status</th>
                        <th>Program</th>
                        <th>Estimate Date</th>
                        <th>Sales Person</th>
                        <th>Discount/Coupon</th>
                        <th>Action</th>


                    </tr>
                    </thead>

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


<!-- /content area -->

<script type="text/javascript">
    // DataTable
    var table =  $('#estimatetable').DataTable({
        "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
        "processing": true,
        "serverSide": true,
        "paging":true,
        "pageLength":<?= $this ->session->userdata('compny_details')-> default_display_length?>,
        "order":[[1,"desc"]],
        "ajax":{
            "url": "<?= base_url('admin/Estimates/ajaxGetEstimates/')?>",
            "dataType": "json",
            "type": "POST",
            "data":{
                "pageLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>
            }
        },
        "deferRender":false,
        "columnDefs": [
            {"targets": [0], "checkboxes":{"selectRow":true,"stateSave": true}},
        ],
        "select":"multi",
        "columns": [
            {"data": "checkbox", "checkboxes":true, "stateSave":true, "searchable":false, "orderable":false},
            {"data": "estimate_id_url", "name":"Estimate #", "orderable": true, "searchable": true },
            {"data": "customer_name_url", "name":"Customer Name", "orderable": true, "searchable": true },
            {"data": "property_address", "name":"Property", "orderable": true, "searchable": true },
            {"data": "cost", "name":"Total Estimate Cost", "searchable":false, "orderable": true },
            {"data": "status_html", "name":"Status", "orderable": true, "searchable": true },
            {"data": "program_name", "name":"Program", "searchable":true, "orderable": true },
            {"data": "estimate_created_date", "name":"Estimate Date", "searchable":true, "orderable": true },
            {"data": "user_complete_name", "name":"Sales Person","orderable": true },
            {"data": "coupon_details", "name":"Discount/Coupon","searchable":true, "orderable": true },
            {"data": "action", "name":"Action", "searchable":false,"orderable": false }
        ],
        language: {
            search: '<span></span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        dom: 'l<"toolbar">frtip',
        initComplete: function(data){
            console.log(data)
            $("div.toolbar")
                //        },
                //        },
                .html('<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Status <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right filter-status-estimate"><li onclick="filterSearch(4)" data-id="4" class="active"><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li onclick="filterSearch(0)" data-id="0"><a href="#"><span class="status-mark bg-warning position-left"></span> Draft</a></li><li onclick="filterSearch(1)" data-id="1"><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li><li onclick="filterSearch(2)" data-id="2"><a href="#"><span class="status-mark bg-till position-left"></span> Accepted</a></li><li onclick="filterSearch(5)" data-id="5"><a href="#"><span class="status-mark bg-orange position-left"></span> Declined</a></li> <li onclick="filterSearch(3)" data-id="3"><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>       </ul></div>&nbsp;&nbsp;<button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">Send By Email</button>&nbsp;&nbsp;<?php if($setting_details->signwell_api_key != "") { ?><button type="submit"  disabled="disabled" data-toggle="modal" onclick="send_to_signwell()" class="btn btn-success btn-outline" id="send_signwell">Send through SignWell</button>&nbsp;&nbsp;<?php } ?><button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled >Delete</button>');
        },
        buttons:[
            {
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon',
            },
        ]
    });

    $('#estimatetable').on( 'keyup', function () {
        table.search( this.value ).draw();
    } );

    $(document).ready(function() {
        table.draw();
    });

    function  filterSearch(status) {
        table.columns(5).search(status).draw();

        $(".filter-status-estimate li").removeClass("active");
        $(".filter-status-estimate li[data-id="+ status +"]").addClass("active");

    }

    //bilidDataTable();
    //
    //function bilidDataTable(argument) {
    //    $('.datatable-filter-custom').DataTable({
    //        "iDisplayLength": <?//= $this ->session->userdata('compny_details')-> default_display_length?>//,
    //        "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
    //
    //        language: {
    //            search: '<span></span> _INPUT_',
    //            lengthMenu: '<span>Show:</span> _MENU_',
    //            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
    //        },
    //
    //
    //        dom: 'l<"toolbar">frtip',
    //        initComplete: function(){
    //
    //            $("div.toolbar")
    //                .html('<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Status <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li onclick="filterSearch(4)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li onclick="filterSearch(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Draft</a></li><li onclick="filterSearch(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li><li onclick="filterSearch(2)" ><a href="#"><span class="status-mark bg-till position-left"></span> Accepted</a></li><li onclick="filterSearch(5)" ><a href="#"><span class="status-mark bg-orange position-left"></span> Declined</a></li> <li onclick="filterSearch(3)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>       </ul></div>&nbsp;&nbsp;<button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">Send By Email</button>&nbsp;&nbsp;<?php //if($setting_details->signwell_api_key != "") { ?>//<button type="submit"  disabled="disabled" data-toggle="modal" onclick="send_to_signwell()" class="btn btn-success btn-outline" id="send_signwell">Send through SignWell</button>&nbsp;&nbsp;<?php //} ?>//<button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled >Delete</button>');
    //        },
    //
    //    });
    //
    //}

    // Change status of estimate from dropdown
    $(document).on("click",".changestatus", function () {

        var estimate_id = $(this).attr('estimate_id');
        var status = $(this).val();
        $("#loading").css("display","block");
        console.log(status);
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>admin/Estimates/changeStatus',
            data: {estimate_id: estimate_id, status: status},
            success: function (data) {
                $("#loading").css("display","none");
                location.reload();
            }
        });

    });

    function pdf_signwell(estimate_id){
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/Estimates/get_signwell_status',
        data: {estimate_id: estimate_id},
        success: function (data) {
            alert(data)
            console.log(data)
            window.location.href = data;

        },
        error: function(err){
            console.log(err)
               alert(err)
        }
    });
}

    // Send estimate by email button functions
    $(document).on("click",".email", function () {

        // $('.email').click(function(){

        var estimate_id = $(this).attr('id');
        var customer_id = $(this).attr('customer_id');


        swal.mixin({
            input: 'text',
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
                var message  = result.value;

                $("#loading").css("display","block");


                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url(); ?>admin/Estimates/sendPdfMail',
                    data: {estimate_id: estimate_id, customer_id: customer_id,message : message},
                    success: function (data)
                    {

                        $("#loading").css("display","none");
                        swal(
                            'Estimate !',
                            'Sent Successfully ',
                            'success'
                        ).then(function() {
                            location.reload();
                        });

                    }
                });
            }
        })
    });

    $(document).on("change","#select_all", function () {

        // $("#select_all").change(function(){  //"select all" change
        var status = this.checked; // "select all" checked status
        if (status) {
            $('#allMessage').prop('disabled', false);
            $('#allPrint').prop('disabled', false);
            $('#deletebutton').prop('disabled', false);
            $('#send_signwell').prop('disabled', false);

        }
        else
        {
            $('#allMessage').prop('disabled', true);
            $('#allPrint').prop('disabled', true);
            $('#deletebutton').prop('disabled', true);
            $("#send_signwell").prop('disabled', true);

        }

        $('input:checkbox').not(this).prop('checked', this.checked);

    });

    $(document).on("change",".myCheckBox", function () {

        // checkBoxes.change(function () {
        // alert(checkBoxes);
        if($('.myCheckBox').filter(':checked').length < 1) {
            //  alert("if");
            $('#allMessage').prop('disabled', true);
            $('#allPrint').prop('disabled', true);
            $('#deletebutton').prop('disabled', true);
            $('#send_signwell').prop('disabled', true);
        }
        else {
            $('#allMessage').prop('disabled', false);
            $('#allPrint').prop('disabled', false);
            $('#deletebutton').prop('disabled', false);
            $('#send_signwell').prop('disabled', false);

            //  alert('else');
        }

        if(this.checked == false){ //if this item is unchecked
            $("#select_all")[0].checked = false; //change "select all" checked status to false


        }

        //check "select all" if all checkbox items are checked
        if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){
            $("#select_all")[0].checked = true; //change "select all" checked status to true


        }

    });

    $(document).on("click","#allMessage", function () {



        swal.mixin({
            input: 'textarea',
            confirmButtonText: 'Send',
            showCancelButton: true,
            progressSteps: 1
        }).queue([
            {
                title: 'Estimate Message',
                text: 'Type a message to the customer below to be included with the estimate. Then click "Send" to email the estimate to the customer.'
            },
        ]).then((result) => {

            if (result.value) {
                var message  = result.value;

                var group_id_array = $("input:checkbox[name=group_id]:checked").map(function(){
                    return $(this).val();
                }).get(); // <----

                $("#loading").css("display","block");
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url(); ?>admin/Estimates/sendPdfMailToSelected',
                    data: {group_id_array,message : message},
                    success: function (data)
                    {

                        $("#loading").css("display","none");
                        swal(
                            'Estimate !',
                            'Sent Successfully ',
                            'success'
                        ).then(function() {
                            location.reload();
                        });

                    }
                });
            }
        })
    });

    $(document).on("click","#allPrint", function () {


        var estimates_ids = $("input:checkbox[name=group_id]:checked").map(function(){
            return $(this).attr('estimate_id');
        }).get(); // <----

        var href ="<?= base_url('admin/Estimates/printEstimate/') ?>"+estimates_ids;

        var win = window.open(href, '_blank');
        win.focus();

    });

    function deletemultiple() {

        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover this !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {

            if (result.value) {

                var selectcheckbox = [];
                $("input:checkbox[name=group_id]:checked").each(function(){
                    selectcheckbox.push($(this).attr('estimate_id'));
                });


                $.ajax({
                    type: "POST",
                    url: "<?= base_url('')  ?>admin/Estimates/deletemultipleEstimates",
                    data: {estimates_ids : selectcheckbox }
                }).done(function(data){

                    if (data==1) {
                        swal(
                            'Estimates !',
                            'Deleted Successfully ',
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

    function send_to_signwell() {


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
            $("#loading").css("display","block");
            if (result.value) {
                var customer_message  = result.value;
                // $('#email_notes').val(message);
                // $('#submit_form').submit()

                var group_id_array = $("input:checkbox[name=group_id]:checked").map(function(){
                    return $(this).val();
                }).get();
                $("#loading").css("display","block");
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('')  ?>admin/Estimates/sendEstimateToSignWell",
                    data: {group_id_array: group_id_array, customer_message: customer_message }
                }).done(function(data){
                    $("#loading").css("display","none");
                    myArr = JSON.parse(data);
                    error_string = "";
                    for (let index = 0; index < myArr.length; ++index) {
                        const element = myArr[index];
                        error_string = error_string+"Estimate "+element["estimate_id"]+": Failed to send: "+element["message"]+'\n\n' ;
                    }
                    if (error_string=="") {
                        swal(
                            'Estimates !',
                            'Sent Successfully ',
                            'success'
                        ).then(function() {
                            location.reload();
                        });
                    } else {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: error_string
                        });
                    }
                });
            }
        })



    }


</script>
