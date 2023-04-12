<style type="text/css">
	#items{
		top:85%!important;
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
   /*text-align: center !important;*/

   margin-left: 60px !important;
   }
   #invoicetablediv{
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
</style>


<div class="content invoicessss">
   <div id="loading" >
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
   </div>
   <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

      <div id="invoicetablediv">
         <div  class="table-responsive table-spraye">
            <table class="table" id="items">
               <thead>
                  <tr>
                        <th><input type="checkbox" id="select_all"/></th>
                        <th>Item Name</th>
                        <th>Item #</th>
                        <th>Item Description</th>
                        <th>Item Type</th>
                        <th>Unit Definition</th>
                        <th># of Units on Hand</th>
					    <th>Average Cost Per Unit</th>
                        <th>Available Vendors</th>
                        <th>Preferred Vendors</th>
                        <th>Ideal Ordering Timeframe</th>
                        <th>Action</th>
                  </tr>
               </thead>

            </table>
         </div>
      </div>
   </div>
</div>
<!-- /form horizontal -->


<!-- /content area -->
<script type="text/javascript">
   $(document).ready(function(){

	 var table =  $('#items').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
		 	
		   "ajax":{
		     "url": "<?= base_url('inventory/controllers/frontend/items')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		   },
		   	 "deferRender":false,


        	"columnDefs": [
				{"targets": [0], "checkboxes":{"selectRow":true,"stateSave": true}},


			],

		   "select":"multi",
		   "columns": [
			   	  {"data": "checkbox", "checkboxes":true, "stateSave":true, "searchable":false, "orderable":false},
		          {"data": "item_name", "name":"Item Name", "searchable":true, "orderable": true },
			   	  {"data": "item_number", "name":"Item #", "searchable":true, "orderable": true },
		          {"data": "item_description", "name":"Item Description", "searchable":true, "orderable": true },
			   	  {"data": "item_type", "name":"Item Type", "orderable": true },
			   	  {"data": "unit_definition", "name":"Unit Definition", "orderable": true },
                     {"data": "total_units_on_hand", "name":"# of Units on Hand", "orderable": true },
                     {"data": "average_cost_per_unit", "name":"Average Cost Per Unit", "orderable": true },
                     {"data": "available_vendors", "name":"Available Vendors", "orderable": true },
                     {"data": "preferred_vendor", "name":"Preferred Vendor", "orderable": true },
                     {"data": "ideal_ordering_timeframe", "name":"Ideal Ordering Timeframe",},
			   	  {"data": "actions", "name":"Actions","class":"table-action"},
		       ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
		   dom: '<"toolbar">fBlrtip',
		    buttons:[
				{
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon',
				columns: [1,2,3,4,5,6,7,8,9,10,11,12],


            },
				],
		   initComplete: function(){

           $("div.toolbar")
              .html('<div class="btn-group"><button type="button" class="btn btn-success">Export CSV</button><button type="submit"  class="btn btn-success" id="newitemsbutton" disabled >New Items</button>');
        },

	});

});

</script>