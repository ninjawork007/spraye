<style type="text/css">
    .toolbar {
        float: left;
        padding-left: 5px;
    }

    .form-control[readonly] {
        background-color: #ededed;
    }
    .row {
        margin-left:-5px;
        margin-right:-5px;
    }
  
    .column {
        float: left;
        width: 50%;
        padding: 5px;
    }
</style>
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-body">
            <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
            <div class="panel panel-body" style="background-color:#ededed;" >
                <form id="searchform" action="<?= base_url('inventory/Frontend/Purchases/downloadMaterialResourceCsv') ?>" method="post">            
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row"> 
                                <div class="form-group multiselect-select-all-filtering2" >
                                    <label>Service List <span data-popup="tooltip-custom" title="Select Jobs for the report." data-placement="top"><i class=" icon-info22 tooltip-icon"></i></span></label>
                                    <div class="multi-select-full ">
                                        <select class="multiselect-select-all-filtering" multiple="multiple" name="material_job_tmp[]"  id="job_list" data-live-search="true">
                                            <?php foreach ($joblist as $value) : ?>
                                            <option value="<?= $value->job_id ?>"><?= $value->job_name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="row">
                                <div class="form-group">
                                    <label>Filter by a Grass Type</label>
                                    <div >
                                        <select class="form-control" name="grass_type" id="grass_type">
                                            <option value="">Select Yard Grass Type</option>
                                            <option value="Bent">Bent</option>
                                            <option value="Bermuda">Bermuda</option>
                                            <option value="Dichondra">Dichondra</option>
                                            <option value="Fine Fescue">Fine Fescue</option>
                                            <option value="Kentucky Bluegrass">Kentucky Bluegrass</option>
                                            <option value="Mixed">Mixed</option>
                                            <option value="Ryegrass">Ryegrass</option>
                                            <option value="St. Augustine/Floratam">St. Augustine/Floratam</option>
                                            <option value="Tall Fescue">Tall Fescue</option>
                                            <option value="Zoysia">Zoysia</option>
                                            <option value="Centipede">Centipede</option>
                                            <option value="Bluegrass/Rye/Fescue">Bluegrass/Rye/Fescue</option>
                                            <option value="Warm Season">Warm Season</option>
                                            <option value="Cool Season">Cool Season</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-success" onClick="searchFilter()" ><i class="icon-search4 position-left"></i> Search</button>
                        <button type="button" class="btn btn-primary" onClick="resetForm()" ><i class="icon-reset position-left"></i> Reset</button>
                        <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
                    </div>
                </form>
            </div>
    
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="loading" style="display: none;">
                            <center>
                                <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
                            </center>
                        </div>
                        <div class="post-list" id="postList"> 
                            <div class="table-responsive table-spraye" id="material-resources">
                                <table  class="table" style="border: 1px solid #6eb0fe; border-radius: 12px;"  id="material-resource-table" width="100%">
                                    <thead>  
                                        <tr>
                                            <th>Products Names</th>
                                            <th>Outstanding Services</th>
                                            <th>Outstanding Square Feet</th>
                                            <th>Estimate Amount of Product Needed</th>
                                            <th>Amount of Product on Hand</th>
                                            <th>Amount of Product Ordered</th>
                                            <th>Overage/Shortfall</th>
                                        </tr>  
                                    </thead>
                                    <tbody id="material_resource_tbody">
                                        <?php 
                                            if (!empty($product_objs)) {
                                            $total_outstanding = 0;
                                            $outstanding_sqft = 0;

                                            foreach ($product_objs as $value) { 
                                                // die(print_r($value));
                                        ?>

                                        <tr>
                                            
                                            <td><?= $value->product_name ?></td>
                                            <td style="text-align:center;"><?= $value->outstanding_ct ?></td>
                                            <td style="text-align:center;"><?= $value->outstanding_sqft ?></td>
                                            <td style="text-align:center;"><?= $value->product_needed ?></td>
                                            <td style="text-align:center;"><?= $value->onhand ?></td>
                                            <td style="text-align:center;"><?= $value->ordered ?></td>
                                            <td style="text-align:center;"><?= $value->overage ?></td>
                                        </tr>
                                        <?php
                                            
                                            }
                                            
                                            } else { 
                                            ?> 

                                        <tr>
                                            <td colspan="5"> No record found </td>
                                        </tr>

                                        <?php }  ?>

                                    </tbody>
                                    
                                </table>  
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
/*DOC READY*/
$(document).ready(function(){
    tableinitial();
});
/*MATERIAL RESOURCE PLANNING FUNCTIONS*/
function resetForm(){
  $('#searchform')[0].reset();
  searchFilter();
}

function searchFilter() {
    var job_list = $('#searchform #job_list').val();
    var grass_type = $('#searchform #grass_type').val();
    $('.loading').css("display", "block");
   $('#postList').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/ajaxMaterialResourcePlanningData/', ///// CHECK URL
        data:'job_list='+job_list+'&grass_type='+grass_type,
        
        success: function (html) {
            console.log(html);
            $(".loading").css("display", "none");
            $('#postList').html(html);
            tableinitial(); ///CHECK FUNCTION
        }
    });
}

function tableinitial(argument){
    $('#material-resource-table').DataTable({
        buttons: [
            {
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon'
            }
        ],
        stateSave: true,
        columnDefs: [
            {
                targets: -1,
                visible: true
            }
        ], 
    });
}

</script>
<script>
    $('[data-popup=tooltip-custom]').tooltip({
        template: '<div class="tooltip"><div class="bg-teal"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div></div>'
    });
</script>