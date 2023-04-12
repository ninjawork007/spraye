<style type="text/css">
    th,
    td {
        text-align: center;
    }

    .pre-scrollable {
        min-height: 0px;
    }

    #myTable>tbody>tr>td:hover {
        cursor: move;
    }
</style>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
<!-- Content area -->
<div class="content">

    <!-- Form horizontal -->
    <div class="panel panel-flat">

        <div class="panel-heading">
            <h5 class="panel-title">
                <div class="form-group">
                    <a href="<?= base_url('admin/programList') ?>" id="save" class="btn btn-success"><i class=" icon-arrow-left7"> </i> Back to All Programs</a>
                </div>
            </h5>
        </div>

        <br>

        <div class="panel-body">

            <b><?php if ($this->session->flashdata()) : echo $this->session->flashdata('message');
                endif; ?></b>

            <form class="form-horizontal" action="<?= base_url('admin/addCopyProgramData') ?>" method="post" name="addcopyprogram" enctype="multipart/form-data">
                <fieldset class="content-group">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-2">Name</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="program_name" value="<?php echo set_value('program_name') ? set_value('program_name') : $programData['program_name'].' - Copy'  ?>" placeholder="Program Name">
                                    <span style="color:red;"><?php echo form_error('program_name'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-2">Pricing</label>
                                <div class="col-lg-10" style="padding-left: 5px;">


                                    <select class="form-control" name="program_price">

                                        <option value="1" <?php if ($programData['program_price'] == "1") {
                                                                echo "selected";
                                                            } ?>>One-Time Program Invoicing</option>
                                        <option value="2" <?php if ($programData['program_price'] == "2") {
                                                                echo "selected";
                                                            } ?>>Invoiced at Service Completion</option>
                                        <option value="3" <?php if ($programData['program_price'] == "3") {
                                                                echo "selected";
                                                            } ?>>Manual Billing</option>

                                    </select>
                                    <span style="color:red;"><?php echo form_error('program_price'); ?></span>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-2">Service List</label>
                                <div class="multi-select-full col-lg-10">
                                    <select class="form-control" name="program_job_tmp" id="job_list">
                                        <option value="">Select any Job</option>
                                        <?php foreach ($joblist as $value) : ?>
                                            <option value="<?= $value->job_id ?>"  > <?= $value->job_name ?> </option>
                                        <?php endforeach ?>
                                    </select>

                                    <input type="hidden" name="program_job" id="job_id_order_array" value="<?= implode(',', $selectedjobid) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                <label class="control-label col-lg-2">Property List</label>

                                <div class="multi-select-full col-lg-10">
                                    <select class="multiselect-select-all-filtering2 form-control" name="propertylistarray_temp[]" multiple="multiple" id="property_list">
                                        <?php if (!empty($propertylist)) {
                                            foreach ($propertylist as $value) {
                                                if (in_array($value->property_id, $selectedproperties)) {
                                                    $select = 'selected';
                                                } else {
                                                    $select = '';
                                                }


                                        ?>
                                                <option value="<?= $value->property_id ?>" <?= $select  ?>> <?= $value->property_title ?> </option>
                                        <?php }
                                        } ?>

                                    </select>
                                </div>
                                <div class="col-lg-2"></div>
                                <!-- Button to clear all properties in list -->
                                <br /><a id="clear-all-props" class="btn" name="clear-all-props">Remove All Properties</a>
                                    <!-- End clear all properties button -->
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="prioritydivcontainer" style="display: <?php echo !empty($selecteddata) ? 'block' : 'none'; ?>;">
                                <div class="table-responsive  pre-scrollable">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Priority</th>
                                                <th>Service Name</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody class="prioritytbody">
                                            <?php
                                            if (!empty($selecteddata)) {
                                                $a = 1;
                                                foreach ($selecteddata as $key => $value) { ?>
                                                    <tr id="trid<?= $a ?>">
                                                        <td class="index"><?= $a ?></td>
                                                        <td><?= $value->job_name ?></td>
                                                        <td class="removeclass" id="<?= $a ?>" optionValueRemove="<?= $value->job_id ?>" optionTextRemove="<?= $value->job_name ?>">
                                                            <ul class="icons-list">
                                                                <li><a href="#"><i class="icon-trash"></i></a></li>
                                                            </ul>
                                                        </td>
                                                    </tr>

                                            <?php $a++;
                                                }
                                            } ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="property-price-over-ride-container" style="display: <?php echo !empty($selectedpropertylist) ? 'block' : 'none'; ?>;">
                                <div class="table-responsive  pre-scrollable">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Property Name</th>
                                                <th>Price Override</th>
                                            </tr>
                                        </thead>
                                        <tbody class="priceoverridetbody">

                                            <?php $n2 = 1;

                                            if (!empty($selectedpropertylist)) {

                                                // die(print_r($selectedpropertylist));

                                                foreach ($selectedpropertylist as $value) {
                                                    $price_override = (isset($value->is_price_override_set) && $value->is_price_override_set == 1) ? floatval($value->price_override) : '';

                                                    echo '<tr id="tridproperty' . $value->property_id . '" >
                                                  <td>' . $value->property_title . '</td>                                                 
                                                  <td><input type="number" min="0" step="0.01" name="tmp' . $n2 . '" value="' . $price_override . '"  class="inpcl form-control" optval="' . $value->property_id . '"  ></td>                                          
                                               </tr>';

                                                    $selectedValuesProperty[] = $value->property_id;
                                                    $selectedTextsProperty[] =  $value->property_title;


                                                    $keyIds[] = array(
                                                        'property_id' => $value->property_id,
                                                        'price_override' => $value->price_override,
                                                        'is_price_override_set' => $value->is_price_override_set,

                                                    );


                                                    $n2++;
                                                }
                                            } else {

                                                $keyIds = array();
                                                $selectedValuesProperty = array();
                                                $selectedTextsProperty = array();
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <textarea name="propertylistarray" id="assign_property_ids2" style="display:none;"><?php echo json_encode($keyIds); ?></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-2">Notes</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" name="program_notes" rows="5"><?php echo set_value('program_notes') ? set_value('program_notes') : $programData['program_notes'] ?></textarea>
                                    <span style="color:red;"><?php echo form_error('program_notes'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

        </div>
        </fieldset>

        <div class="text-right">
            <button type="submit" class="btn btn-success">Save <i class="icon-arrow-right14 position-right"></i></button>
        </div>
        </form>
    </div>
</div>
<!-- /form horizontal -->

</div>
<!-- /content area -->



<script type="text/javascript">
    // var selectedValues = [];
    var selectedSortingValues = [];
    var selectedSortingTexts = [];
    var selectedValues = <?php echo json_encode($selectedjobid); ?>;
    var selectedTexts = <?php echo json_encode($selectedjobname); ?>;
    var optionValue = '';
    var optionText = '';
    var cleared = false;
    $n = Number(<?= count($selecteddata) + 1; ?>);

    /** CUSTOM CODE TO CLEAR ALL PROPERTIES IN LIST **/

    // Add click event listener to Clear All button
    $("#clear-all-props").click(function(event) {
        // prevent button from submitting form
        event.preventDefault();
        // loop through each option in multiselect 
        $('option', $('#property_list')).each(function(element) {
            // Grab the id of each list item
            var id = $(this).val();
            // remove any of the current list items that might be present in the priceoverride list
            $("#tridproperty" + id).remove();
            selectedValues = [];
            selectedTexts = [];
            $('#propertylistarray').val('');
            $('#assign_property_ids2').val('');

            // remove selected and checked properties
            $(this).removeAttr('selected').prop('selected', false);
            $(this).removeAttr('checked').prop('checked', false);
        });
        // remove checked class to hide ::after pseudo element
        $(".checker span").removeClass("checked");
        // refresh the multiselect 
        $("#property_list").multiselect('refresh');
    });

    /** END CUSTOM CODE TO CLEAR ALL PROPERTIES IN LIST **/

    $(document).on("change", "#job_list", function() {

        // alert("sds");

        optionValue = $(this).val();

        if (optionValue != '') {

            if ($.inArray(optionValue, selectedValues) != '-1') {
                // alert("al");

            } else {

                $('.prioritydivcontainer').css("display", "block");

                optionText = $("#job_list option:selected").text();
                //  alert(optionValue);
                //     alert(optionText);

                selectedValues.push(optionValue);

                selectedTexts.push(optionText);

                var $row = $('<tr id="trid' + $n + '">' +
                    '<td class="index">' + $n + '</td>' +
                    '<td>' + optionText + '</td>' +
                    '<td class="removeclass" id="' + $n + '" optionValueRemove="' + optionValue + '" optionTextRemove="' + optionText + '" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"></i></a></li></ul></td>' +
                    '</tr>');	

                $('.prioritytbody:last').append($row);
                $n = $n + 1;
                $('#job_id_order_array').val(selectedValues);
            }
        }

    });	

    $(document).on("click", ".removeclass", function() {

        // alert(selectedValues);
        // alert(selectedTexts);

        var id = $(this).attr('id');
        var optionValueRemove = $(this).attr('optionValueRemove');
        var optionTextRemove = $(this).attr('optionTextRemove');

        selectedValues.splice($.inArray(optionValueRemove, selectedValues), 1);
        selectedTexts.splice($.inArray(optionTextRemove, selectedTexts), 1);

        $("#trid" + id).remove();

        $('#job_id_order_array').val(selectedValues);

        // alert(selectedValues);
        // alert(selectedTexts);
        rearrangetable();

    });




    function rearrangetable() {

        $('.prioritytbody').empty();
        $n = 1;
        $.each(selectedValues, function(i, item) {



            var $row = $('<tr id="trid' + $n + '">' +
                '<td class="index">' + $n + '</td>' +
                '<td>' + selectedTexts[i] + '</td>' +
                '<td class="removeclass" id="' + $n + '" optionValueRemove="' + selectedValues[i] + '" optionTextRemove="' + selectedTexts[i] + '" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"></i></a></li></ul></td>' +
                '</tr>');

            $('.prioritytbody:last').append($row);

            $n = $n + 1;

        });

    }
</script>
<script type="text/javascript">
    var fixHelperModified = function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width())
            });
            return $helper;
        },
        updateIndex = function(e, ui) {

            $('td.index', ui.item.parent()).each(function(i) {
                $(this).html(i + 1);
            });
            selectedSortingValues = [];
            selectedSortingTexts = [];
            $('td.removeclass', ui.item.parent()).each(function(i) {
                console.log();
                // $(this).val(i + 1);	
                selectedSortingValues.push($(this).attr('optionValueRemove'));
                selectedSortingTexts.push($(this).attr('optionTextRemove'));
            });
            selectedValues = selectedSortingValues;
            selectedTexts = selectedSortingTexts;
            $('#job_id_order_array').val(selectedValues);

        };
    $("#myTable tbody").sortable({
        helper: fixHelperModified,
        stop: updateIndex
    }).disableSelection();

    $("#myTable tbody").sortable({
        distance: 5,
        delay: 100,
        opacity: 0.6,
        cursor: 'move',
        update: function() {}
    });
</script>
<!-- for price override -->
<script type="text/javascript">
    var selectedValuesProperty = <?php echo json_encode($selectedValuesProperty) ?>;
    var selectedTextsProperty = <?php echo json_encode($selectedTextsProperty) ?>;
    var keyIds = <?php echo json_encode($keyIds) ?>;
    var optionValueProperty = '';
    var optionTextProperty = '';
    $n2 = <?php echo  $n2; ?>;

    $("#clear-all-props").click(function(event) {
        event.preventDefault();
        keyIds = [];
        selectedValuesProperty = [];
        selectedTextsProperty = [];
        optionValueProperty = '';
        optionTextProperty = '';
    });

    $(function() {
        reintlizeMultiselectpropertyPriceOver();
    });

    function reintlizeMultiselectpropertyPriceOver() {
        $(".multiselect-select-all-filtering2").multiselect('destroy');
        $('.multiselect-select-all-filtering2').multiselect({
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: false,
            templates: {
                filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'
            },
            onInitialized: function(select, container) {

                $(".styled, .multiselect-container input").uniform({
                    radioClass: 'checker'
                });

            },
            onSelectAll: function() {
                $(".").uniform.update();
            },

            onChange: function(option, checked, select) {

                if (checked) {



                    optionValueProperty = $(option).val();
                    if (optionValueProperty != '') {
                        if ($.inArray(optionValueProperty, selectedValuesProperty) != '-1') {
                            // alert('already');	

                        } else {

                            $('.property-price-over-ride-container').css("display", "block");

                            optionTextProperty = $(option).text();
                            // alert(optionValueProperty);	
                            //   alert(optionTextProperty);	

                            selectedValuesProperty.push(optionValueProperty);

                            keyIds.push({
                                'property_id': optionValueProperty,
                                'price_override': 0,
                                'is_price_override_set': null
                            });
                            selectedTextsProperty.push(optionTextProperty);
                            inputID = 'inpid' + $n2;
                            var $row = $('<tr id="tridproperty' + optionValueProperty + '">' +
                                '<td>' + optionTextProperty + '</td>' +
                                '<td> <input type="number" min="0" step="0.01" name="tmp' + $n2 + '"  class="inpcl form-control" optval="' + optionValueProperty + '"  ></td>' +
                                '</tr>');
                            $('.priceoverridetbody:last').append($row);
                            $n2 = $n2 + 1;
                            // $('#assign_property_ids').val(selectedValuesProperty);	

                            $('#assign_property_ids2').val(JSON.stringify(keyIds));
                        }
                    }
                } else {
                    var id = $(option).val();
                    var optionValuePropertyRemove = $(option).val();
                    var optionTextPropertyRemove = $(option).text();

                    selectedValuesProperty.splice($.inArray(optionValuePropertyRemove, selectedValuesProperty), 1);
                    selectedTextsProperty.splice($.inArray(optionTextPropertyRemove, selectedTextsProperty), 1);
                    keyIds = $.grep(keyIds, function(e) {
                        return e.property_id != optionValuePropertyRemove;
                    });
                    $("#tridproperty" + id).remove();
                    // $('#assign_property_ids').val(selectedValuesProperty);	
                    $('#assign_property_ids2').val(JSON.stringify(keyIds));
                }
            }
        });
    }
    $(document).on("input", ".inpcl", function() {
        inputvalue = $(this).val();
        property_id = $(this).attr('optval');

        $.each(keyIds, function(key, value) {

            if (property_id == value.property_id) {
                keyIds[key].price_override = inputvalue;
                if (inputvalue != "") {
                    keyIds[key].is_price_override_set = 1;
                } else {
                    keyIds[key].is_price_override_set = null;
                }
            }
            // alert( key + ": " + value.property_id );	
        });
        $('#assign_property_ids2').val(JSON.stringify(keyIds));
    });
</script>