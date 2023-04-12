<script src="<?= base_url() ?>assets/popup/js/sweetalert2.all.js"></script>

<style type="text/css">
	#items_processing{
		top:85%!important;
	}
   .sorting_asc:after {
      content: '';
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
   .btn-group {
   margin-left: -4px !important;
   margin-top: -1px !important;
   padding: 2px 2px;
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
   .label-refunded, .bg-refunded {
      background-color : #fd7e14;
      border-color : #fd7e14;
   }
   .toolbar {
   float: left;
   padding-left: 5px;
	margin-bottom: 5px;
   }
   .dataTables_filter {
   margin-left: 60px !important;
   }
   #itemtablediv{
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
#mytbl {
    border: 1px solid
    #6eb1fd;
    border-radius: 4px;
}
	.dt-buttons {
		display: inline-block;
		margin: 0 10px 20px 10px;
	}
  .margin-dyn {
    margin-top: 8px;
    margin-bottom: 8px;
  }
</style>


<div class="content invoicessss">
   <div id="loading" >
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
   </div>
   <div id="toolbar" style="margin-left: 32px;">

   </div>
   <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

      <div id="itemtablediv">
         <div  class="table-responsive table-spraye">
            <table class="table" id="item_quantity_by_sublocation">
               <thead>
                  <tr>
                        <th>Item Name</th>
                        <th>Total Units at <?php echo $sub_location_name; ?></th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
   </div>
  <input type="hidden" id="loc_id" name="loc_id" value="<?php echo $location_id; ?>"/>
  <input type="hidden" id="sub_id" name="sub_id" value="<?php echo $sub_location_id; ?>"/>
  <input type="hidden" id="loc_name" name="loc_name" value="<?php echo $location_name; ?>"/>
  <input type="hidden" id="sub_name" name="sub_name" value="<?php echo $sub_location_name; ?>"/>
</div>
<!-- /form horizontal -->

<!-- Choose Location Modal -->
<div id="modal_choose_loc" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Choose Location</h6>
      </div>

      <form action="<?= base_url('inventory/Frontend/Items/chooseLocation') ?>" method="post" name="chooseloc" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">


          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label>Locations</label>
                <select class="form-control" name="choose_loc" id="choose_loc">
                  <?php foreach($all_locations as $loc){?>
                     <option value="<?php echo $loc->location_id;?>"><?php echo $loc->location_name; ?></option>
                  <?php } ?>
               </select>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="savechooseloc" class="btn btn-success">Filter</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /Choose Location Modal -->

<!-- Choose Sub Location Modal -->
<div id="modal_choose_subloc" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Choose Location</h6>
      </div>

      <form action="<?= base_url('inventory/Frontend/Items/chooseSublocation') ?>" method="post" name="choosesubloc" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">


          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label>Locations</label>
                <select class="form-control" name="choose_loca" id="choose_loca">
                <option value="">Choose a Location</option>
                  <?php foreach($all_locations as $loc){?>
                    
                     <option value="<?php echo $loc->location_id;?>"><?php echo $loc->location_name; ?></option>
                  <?php } ?>
               </select>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Sub-Locations</label>
                <select class="form-control" name="choose_subloca" id="choose_subloca" value="Choose Your Location First">
                  <option value="">Choose your Location First</option>
               </select>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="savechoosesubloc" class="btn btn-success">Filter</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /Choose Sub Location Modal -->

<script type="text/javascript">
   $(document).ready(function(){

    if ($('#choose_loca').val() == ''){
        $('#savechoosesubloc').attr('disabled', true);
    } else {
        $('#savechoosesubloc').attr('disabled', false);
    }

    $(document).on('change', '#choose_loca', function(){
        if ($('#choose_loca').val() == ''){
        $('#savechoosesubloc').attr('disabled', true);
    } else {
        $('#savechoosesubloc').attr('disabled', false);
    }
    });


	// console.log("Ajax Call");

	 var table =  $('#item_quantity_by_sublocation').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
			"order":[[0,'asc']],
		   "ajax":{
		     "url": "<?= base_url('inventory/Frontend/Items/ajaxGetItemQuantityBySubLocation/')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 'loc_id': $('#loc_id').val(), 'sub_id': $('#sub_id').val()},

		   },

		   "columns": [
		          {"data": "item_name", "name":"Item Name", 'orderable': false, 'searchable': true},
                {"data": "quantity", "name": "Total Units", "orderable": true, "searchable": false}
		       ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
		   dom: '<"toolbar">frtip',

		   initComplete: function(){

           $("div.toolbar")
           .html('<a href="<?php echo base_url('inventory/Frontend/Items/exportItemSubLocationCSV?'); ?>' + $('#loc_id').val() + ':' + $('#sub_id').val() +'"><button type="button" class="btn btn-success">Export CSV</button></a><a href="" data-toggle="modal" data-target="#modal_choose_loc" id="modal_location_btn" class="modal_trigger_item" ><button type="button" class="btn btn-primary" id="chooselocbtn">View Location</button></a><a href="" data-toggle="modal" data-target="#modal_choose_subloc" id="modal_sublocation_btn"><button type="button" class="btn btn-primary" id="choosesubbtn" class="modal_trigger_item">View New Sub-Location</button></a><a href="<?php echo base_url('inventory/Frontend/Items/overall_item_quantity'); ?>" ><button type="button" class="btn btn-info" id="resetbtn"><i class="icon-reset position-left"></i>Reset</button></a>');
        },

	});

   $(document).on('change', '#choose_loca', function(){    

      var locid = $('#choose_loca').val();

      var subsHTML = '';

      $.ajax({
         type: 'POST',
         url: '<?php echo base_url();?>/inventory/Frontend/Items/getSublocationListByLocationId',
         data: {location_id: locid},
         dataType: "JSON",
         success: function(result){
            console.log(result.data.subs);
            result.data.subs.forEach(sub => {
               console.log('Sub: ' + sub.sub_location_id);
               subsHTML += '<option value="'+ sub.sub_location_id +'">'+ sub.sub_location_name +'</option>';
            });
            $('#choose_subloca').html(subsHTML);
         },
         error: function(err) {
            console.log("Something went wrong! " + JSON.stringify(err));
         }
      });


   });

});
</script>

