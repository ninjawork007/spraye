<script src="<?= base_url() ?>assets/popup/js/sweetalert2.all.js"></script>

<style type="text/css">
	#items_processing{
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

  .alert-dismissable, .alert-success, .alert-danger, .alert {
    text-align: center;
  }
</style>

<div class="content invoicessss">
    <div id="loading" >
        <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
    </div>
    <div class="panel-body">
        <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

        <div id="itemtablediv">
            <div  class="table-responsive table-spraye">
                <table class="table" id="items">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Item #</th>
                            <th>Item Description</th>
                            <th>Item Type</th>
                            <th>Unit Definition</th>
                            <th>Products Associated</th>
                            <th># of Units on Hand</th>
                            <th>Average Cost Per Unit</th>
                            <th>Available Vendors</th>
                            <th>Preferred Vendor</th>
                            <th>Ideal Ordering Timeframe</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
        <input type="hidden" id="item_prods"/>
        <input type="hidden" id="product_arr" name="product_arr" value="<?php echo $products_str; ?>"/>
    </div>
</div>
<!-- /form horizontal -->

<!-- Edit Item Products Modal -->
<div id="modal_edit_products" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Edit Products</h6>
            </div>

            <form action="" id="edit_product_form" method="post" name="editproducts">

                <div class="modal-body">
                
                <div class="row">
                        <div class="col-sm-12">
                            <label>Unit Conversion Type</label>
                                <select class="form-control" name="edit_unit_conversion_type" id="edit_unit_conversion_type">
                                    <option value="0">Choose a Unit Conversion Type</option>
                                    <option value="1">Volume</option>
                                    <option value="2">Weight</option>
                                </select>
                            </div>
                        </div>

                    <div class="row">
                    <div class="multi-select-full col-sm-12" id="edit_prods_drop">
                            <label>Products</label>
                            <select class="form-control multiselect-select-all" name="edit_item_prods" id="edit_item_prods" multiple="multiple">
                                <option value="" selected>Choose a Unit Conversion Type first</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="edit_product_close">Close</button>
                        <button type="submit" id="saveeditproducts" class="btn btn-success" >Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Edit Item Products Modal -->

<!-- Edit Item Modal -->
<div id="modal_edit_item" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Edit Item</h6>
      </div>

      <form action="<?= base_url('inventory/Frontend/Items/editItem') ?>" method="post" name="edititem" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">


          <div class="form-group">
          <div class="row">
                <input type="hidden" class="form-control" name="item_id" placeholder="Item ID" id="item_id">
            </div>
            <div class="row">
              <div class="col-sm-6">
                <label>Item Name</label>
                <input type="text" class="form-control" name="item_name" placeholder="Item Name" id="item_name">
              </div>
              <div class="col-sm-6">
                <label>Item #</label>
                <input type="text" class="form-control" name="item_number" placeholder="Item #" id="edit_item_number" readonly>
              </div>
            </div>
            <div class="row" id="edit_unique_check"></div>
            <div class="row">
              <div class="col-sm-12">
                <label>Item Description</label>
                <textarea class="form-control" name="item_description" id="item_description" placeholder="Item Description"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div id="label_btn">

                </div>
                <select class="form-control" name="item_type" id="item_type" >
                  <option value="1">Product</option>
                </select>
              </div>
            </div>
            <input type="hidden"  id="options_checked_editprods" name="options_checked_editprods"/>
            <input type="hidden"  id="options_unchecked_editprods" name="options_unchecked_editprods"/>
            <input type="hidden"  id="edit_prices_per_unit" name="edit_prices_per_unit"/>
            <input type="hidden"  id="edit_vendor_notes_input" name="edit_vendor_notes_input"/>
            <div class="row">
              <div class="col-sm-12">
                <label>Item Brand</label>
                <select class="form-control" name="brand_id" id="brand_id" >
                  <option value="0">Choose a Brand</option>
                </select>
              </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12" id="units">
                    
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6" style="width: 50%;">
                    <label>Average Cost Per Unit</label>
                    <div class="input-group">  
                        <span class="input-group-btn">
                              <span class="btn btn-success">$</span>
                        </span>
                    <input type="number" step="0.01" min="0.00" class="form-control" name="average_cost_per_unit" placeholder="Average Cost Per Unit" id="average_cost_per_unit">
                  </div>
                  </div>
            </div>
            <hr>
            <div class="row">
              <div id="avail" class="multi-select-full col-lg-12">
                
              </div>
            </div>
            <div class="row" id="edit_prices">
                
            </div>
            <input type="hidden" value="" id="options_checked" name="options_checked"/>
            <input type="hidden" value="" id="prices_checked" name="prices_checked"/>
            <input type="hidden" value="" id="prices_unchecked" name="prices_unchecked"/>
            <input type="hidden" value="" id="options_unchecked" name="options_unchecked"/>
            <div class="row">
              <div id="prefer" class="col-lg-12">
                
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Ideal Ordering Timeframe</label>
                <input type="text" class="form-control" name="ideal_ordering_timeframe" placeholder="Ideal Ordering Timeframe" id="ideal_ordering_timeframe">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Item Notes</label>
                <textarea class="form-control" name="item_notes" id="item_notes" placeholder="Item Notes"></textarea>
              </div>
            </div>
            <hr>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="saveitemedit" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /Edit Item Modal -->

<?php $product_dd = $all_products; ?>
<!-- New Item Products Modal -->
<div id="modal_new_products" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Choose Products</h6>
            </div>

            <form action="" id="new_product_form" method="post" name="newproducts">

                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <label>Unit Conversion Type</label>
                            <select class="form-control" name="unit_conversion_type" id="unit_conversion_type">
                                <option value="0">Choose a Unit Conversion Type</option>
                                <option value="1">Volume</option>
                                <option value="2">Weight</option>
                            </select>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="multi-select-full col-sm-12">
                            <label>Products</label>
                            <select class="form-control multiselect-select-all" name="new_products" id="new_products" multiple="multiple">
                                <option value="" selected>Choose a Unit Conversion Type first</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="product_close">Close</button>
                        <button type="submit" id="savenewproducts" class="btn btn-success" >Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /New Item Products Modal -->

<?php 
$type_dd = $all_types;
$brand_dd = $all_brands;
$vendor_dd = $all_vendors;
?>

<!-- New Item Modal -->
<div id="modal_new_item" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Add Item</h6>
      </div>

      <form action="<?= base_url('inventory/Frontend/Items/newItem') ?>" method="post" name="newItem" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">


          <div class="form-group">
          <div class="row">
                <input type="hidden" class="form-control" name="item_id" placeholder="Item ID" id="item_id">
                <input type="hidden" class="form-control" name="vendors_list" id="vendors_list" value="<?= $vendor_str ?>">
            </div>
            <div class="row">
              <div class="col-sm-6">
                <label>Item Name</label>
                <input type="text" class="form-control" name="item_name" placeholder="Item Name" id="item_name" required>
              </div>
              <div class="col-sm-6">
                <label>Item #</label>
                <input type="text" class="form-control" name="item_number" placeholder="Item #" id="item_number" required>
              </div>
              
            </div>
            <div class="row" id="unique_check">
                
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Item Description</label>
                <textarea class="form-control" name="item_description" id="item_description" placeholder="Item Description"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Item Type</label>
                <select class="form-control" name="new_item_type" id="new_item_type" required>
                  <option value="">Choose an option</option>
                  <?php foreach($type_dd as $dd){?>
                      <option value="<?php echo $dd->item_type_id; ?>"><?php echo $dd->item_type_name; ?></option>
                    <?php } ?>
                </select>
              </div>
            </div>
            <input type="hidden" value="" id="options_checked_prods" name="options_checked_prods"/>
            <div class="row">
              <div class="col-sm-12">
                <label>Item Brand</label>
                <select class="form-control" name="brand_id" id="brand_id" >
                <option value="">Choose an option</option>
                  <?php foreach($brand_dd as $dd){?>
                      <option value="<?php echo $dd->brand_id; ?>"><?php echo $dd->brand_name; ?></option>
                    <?php } ?>
                </select>
              </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12" id="new_units">
                    
                </div>
            </div>
            
            <div class="row" style="margin-top: 8px;">
                <div class="col-sm-6" style="width: 50%">
                    <label>Average Cost Per Unit</label>
                    <div class="input-group">  
                        <span class="input-group-btn">
                              <span class="btn btn-success">$</span>
                        </span>
                    <input type="number" min="0.00" step="0.01" class="form-control" name="average_cost_per_unit" placeholder="Average Cost Per Unit" id="average_cost_per_unit" required>
                  </div>
                  </div>
            </div>
            <hr>
            <div class="row">
              <div class="multi-select-full col-lg-12">
                <label>Available Vendors</label>
                <select class="form-control multiselect-select-all-filtering2" name="available_vendors_new" id="available_vendors_new" multiple="multiple">
                  <?php foreach($vendor_dd as $dd){?>
                      <option value="<?php echo $dd->vendor_id; ?>"><?php echo $dd->vendor_name; ?></option>
                    <?php } ?>
                </select>
              </div>
            </div>
            <div class="row" id="new_prices">
                
            </div>
            <input type="hidden" value="" id="options_checked_new" name="options_checked_new"/>
            <input type="hidden" value="" id="options_unchecked_new" name="options_unchecked_new"/>
            <input type="hidden" value="" id="new_prices_per_unit" name="new_prices_per_unit"/>
            <input type="hidden" value="" id="new_vendor_notes" name="new_vendor_notes"/>
            <input type="hidden" value="" id="unit_type_conversion" name="unit_type_conversion"/>
            <div class="row">
              <div id="new_prefer" class="col-lg-12">
                
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Ideal Ordering Timeframe</label>
                <input type="text" class="form-control" name="ideal_ordering_timeframe" placeholder="Ideal Ordering Timeframe" id="ideal_ordering_timeframe">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Item Notes</label>
                <textarea class="form-control" name="item_notes" id="item_notes" placeholder="Item Notes"></textarea>
              </div>
            </div>
            <hr>
          </div>
          
          

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="savenewitem" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /New Item Modal -->

<!-- /content area -->
<script type="text/javascript">
    $(document).ready(function(){

	    // console.log("Ajax Call");

	    var table =  $('#items').DataTable({
		    "processing": true,
		    "serverSide": true,
		    "paging":true,
		    "pageLength":100,
			"order":[[1,'asc']],
		    "ajax":{
		        "url": "<?= base_url('inventory/Frontend/Items/ajaxGetItems')?>",
		        "dataType": "json",
		        "type": "POST",
		        "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		    },
		   	"deferRender":false,


        	"columnDefs": [
				{"targets": [7], "orderable": false},


			],

		    "select":"multi",
		    "columns": [
		        {"data": "item_name", "name":"Item Name", "searchable":true, "orderable": true },
			   	{"data": "item_number", "name":"Item #", "searchable":true, "orderable": true },
		        {"data": "item_description", "name":"Item Description", "searchable":true, "orderable": true },
			   	{"data": "item_type_name", "name":"Item Type", "orderable": true, "searchable": true },
			   	{"data": "unit_definition", "name":"Unit Definition", "orderable": true,  "searchable":false },
                {"data": "products_associated", "name": "Products Associated", "orderable": false, "searchable": false },
                {"data": "total_units_on_hand", "name":"# of Units on Hand", "orderable": true,  "searchable":false },
                {"data": "average_cost_per_unit", "name":"Average Cost Per Unit", "orderable": true,  "searchable":false },
                {"data": "available_vendors", "name":"Available Vendors",  "searchable":false, "orderable":false },
                {"data": "preferred_vendor", "name":"Preferred Vendor", "orderable": true },
                {"data": "ideal_ordering_timeframe", "name":"Ideal Ordering Timeframe", "searchable":false, "orderable":false },
			   	{"data": "actions", "name":"Actions","class":"table-action", "searchable":false, "orderable":false}
		    ],
		    language: {
                search: '<span></span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            },
		    dom: '<"toolbar">frtip',
		    buttons:[
				{
                    extend: 'colvis',
                    text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                    className: 'btn bg-indigo-400 btn-icon',
				    columns: [0,1,2,3,4,5,6,7,8,9,10],


                },
			],
		    initComplete: function(){

                $("div.toolbar")
                    .html('<a href="<?php echo base_url('inventory/Frontend/Items/exportItemsCSV'); ?>"><button type="button" class="btn btn-success">Export CSV</button></a><a href="" data-toggle="modal" data-target="#modal_new_item" id="modal_new_item_btn"><button type="button" class="btn btn-primary" id="newitemsbtn">New Item</button></a>');
            },

	    });

    });

    $(document).on('click', '.confirm_item_delete', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
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
                window.location = url;
            }
        });


    });


    $(document).on('click', '#savenewitem', function(){
        var new_prices = [];
        var new_vendor_notes = [];
        $('.vendor_price').each(function(){
            var price_str = $(this).data('vendor') + ':' + $(this).val();
            if(!new_prices.includes(price_str)){
                new_prices.push(price_str);
            }
        });
        $('#new_prices_per_unit').val(new_prices.join("::"));

        $('.vendor_notes').each(function(){
            if($(this).val() == ''){
                $(this).val('N/A');
            }
            var notes_str = $(this).data('vendor_id') + ':' + $(this).val();
            if(!new_vendor_notes.includes(notes_str)){
                new_vendor_notes.push(notes_str);
            }
        });

        $('#new_vendor_notes').val(new_vendor_notes.join("::"));
    });

    $(document).on('click', '#saveitemedit', function(){
        var edited_prices = [];
        var edited_vendor_notes = [];
        $('.edit_vendor_price').each(function(){
            var price_str = $(this).data('vendor') + ':' + $(this).val();
            if(!edited_prices.includes(price_str)){
                edited_prices.push(price_str);
            }
        });

        $('#edit_prices_per_unit').val(edited_prices.join("::"));
        console.log( $('#edit_prices_per_unit').val());

        $('.edit_vendor_notes').each(function(){
            if($(this).val() == ''){
                $(this).val('N/A');
            }
            var notes_str = $(this).data('vendor_id') + ':' + $(this).val();
            if(!edited_vendor_notes.includes(notes_str)){
                edited_vendor_notes.push(notes_str);
            }
        });

        $('#edit_vendor_notes_input').val(edited_vendor_notes.join("::"));
    });



    $(document).on('click', '.modal_trigger_item', function(e){
        e.preventDefault();
        var types = $(this).data('types').split('::');
        var prods = $(this).data('prods').split('<::>');
        var item_prods = $(this).data('item_prods').split('<::>');
        var brands = $(this).data('brands').split('::');
        var item_vendors = $(this).data('item_vendors').split('::') != '' ? $(this).data('item_vendors').split('::') : [];
        var vendors = $(this).data('vendors').split('::');
        var id = $(this).data('id');
        var name = $(this).data('name');
        var num = $(this).data('num');
        var desc = $(this).data('desc');
        var typeid = $(this).data('typeid');
        var typename = $(this).data('typename');
        var brandid = $(this).data('brandid');
        var brandname = $(this).data('brandname');
        var unit_amount = $(this).data('unit_amount');
        var unit_type = $(this).data('unit_type');
        var aver = $(this).data('aver');
        var pref = $(this).data('pref');
        var ideal = $(this).data('ideal');
        var notes = $(this).data('notes');
        var min = $(this).data('min');
        var max = $(this).data('max');
        var contype = $(this).data('contype');
        var prodtypes = $(this).data('prodtypes').split('<::>');
        console.log(prodtypes);
        var edit_prices = $(this).data('prices').split('::') != '' ? $(this).data('prices').split('::') : [];
        $('#item_name').val(name);
        $('#item_id').val(id);
        $('#item_description').val(desc);
        $('#edit_item_number').val(num);
        $('#average_cost_per_unit').val(aver);
        $('#ideal_ordering_timeframe').val(ideal);
        $('#item_notes').val(notes);
        $('#min_alert').val(min);
        $('#max_alert').val(max);
        $('#item_prods').val($(this).data('item_prods'));

        var selectVendorsHTML = "";
        var selectItemVendorsHTML = "";
        var selectTypesHTML = "";
        var selectBrandsHTML = "";

        var selectProdsHTML = "";

        var unitsHTML = "";

        var pricesHTML = "";

        var editPricesHTML = "";

        var typeStr = typeid + ':' + typename;
        var brandStr = brandid + ':' + brandname;

        var measurements = [];

        if(typeid == 1){
        
            if(contype == 1){
                measurements = ['Gallon(s)', 'Fluid Ounce(s)', 'Liter(s)', 'Pint(s)', 'Quart(s)'];
            } else if(contype == 2){
                measurements = ['Pound(s)', 'Ton(s)', 'Ounce(s)', 'Gram(s)', 'Kilogram(s)'];
            } else {
                measurements = ['Gallon(s)', 'Fluid Ounce(s)', 'Liter(s)', 'Pint(s)', 'Quart(s)', 'Pound(s)', 'Ton(s)', 'Ounce(s)', 'Gram(s)', 'Kilogram(s)'];
            }

            unitsHTML = '<label>Unit Definition</label><br><div class="row"><div class="col-sm-4"><input type="number" step="0.01" value="' + unit_amount +'" class="form-control" name="unit_amount" id="unit_amount"></div><div class="col-sm-8"><select class="form-control" id="unit_type" name="unit_type">';
            measurements.forEach(meas => {
                if(meas == unit_type){
                    unitsHTML += '<option value="' + meas + '" selected>'+ meas +'</option>';
                } else {
                    unitsHTML += '<option value="' + meas + '">'+ meas +'</option>';
                }
            });
            unitsHTML += '</select></div></div>';
        } else {
            unitsHTML = '<label>Unit Definition</label><div class="row"><div class="col-sm-4"><input type="number" step="0.01" class="form-control" value="' + unit_amount +'" name="unit_amount" id="unit_amount"></div><div class="col-sm-8"><input type="text" class="form-control" id="unit_type" name="unit_type" placeholder="Unit Definition" value="' + unit_type  + '" ></div></div>';
        }

        $('#units').html(unitsHTML);

        
        if(!types.includes(typeStr)){
            selectTypesHTML += "<option value='" + typeStr.split(':')[0] + "' selected>" + typeStr.split(':')[1] + "</option>";
        }

        types.forEach(typ => {
            if(typ == typeStr){
                selectTypesHTML += "<option value='" + typ.split(':')[0] + "' selected>" + typ.split(':')[1] + "</option>";
            } else {
                selectTypesHTML += "<option value='" + typ.split(':')[0] + "'>" + typ.split(':')[1] + "</option>";
            }
        });

        brands.forEach(bran => {
            if(bran == brandStr){
                selectBrandsHTML += "<option value='" + bran.split(':')[0] + "' selected>" + bran.split(':')[1] + "</option>";
            } else {
                selectBrandsHTML += "<option value='" + bran.split(':')[0] + "'>" + bran.split(':')[1] + "</option>";
            }
        });

        selectVendorsHTML += '<label>Available Vendors</label><select class="multiselect-select-all-filtering2 form-control" name="available_vendors" id="available_vendors" multiple="multiple">';
        vendors.forEach(ven => {
            if(item_vendors.includes(ven)){
                selectVendorsHTML += "<option value='" + ven.split(':')[0] + "' selected>" + ven.split(':')[1] + "</option>";
            } else {
                selectVendorsHTML += "<option value='" + ven.split(':')[0] + "'>" + ven.split(':')[1] + "</option>";
            }
        });

        selectVendorsHTML += '</select>';

        if (item_vendors.length > 0 && item_vendors[0] != ''){
            selectItemVendorsHTML += "<label>Preferred Vendor</label><select class='form-control' name='preferred_vendor' id='preferred_vendor'>";
            item_vendors.forEach(item_ven => {
                if(item_ven == pref){
                    selectItemVendorsHTML += "<option value='" + item_ven + "' selected>" + item_ven.split(':')[1] + "</option>";
                } else {
                    selectItemVendorsHTML += "<option value='" + item_ven + "'>" + item_ven.split(':')[1] + "</option>";
                }
        
            });
            selectItemVendorsHTML += '</select>';
        } else {
            selectItemVendorsHTML += '<label>Preferred Vendor</label><input type="text" class="form-control" name="preferred_vendor" value="No Vendors Available" id="preferred_vendor">'
        }

        

        $('#prefer').html(selectItemVendorsHTML);


        if(typeid == 1){
            $('#label_btn').html('<label>Item Type</label>&nbsp;<button type="button" class="btn btn-success" id="prod_edit_btn">Edit Products</button>');
            $('#label_btn').addClass('margin-dyn');
        } else {
            $('#label_btn').html('<label>Item Type</label>');
            $('#label_btn').removeClass('margin-dyn');
        }

        if(contype){
            $('#edit_unit_conversion_type').val(contype);
        }

        $(document).on('change', '#edit_unit_conversion_type', function(){
            contype = $(this).val();

                if(prods.length > 0){
                    selectProdsHTML = '<label>Products</label><select class="form-control multiselect-select-all" name="edit_item_prods" id="edit_item_prods" multiple="multiple">';
                    prods.forEach(pr => {
                        if(item_prods.includes(pr)){
                            selectProdsHTML += '<option value="' + pr.split('::')[0] + '" selected data-cpu="' + pr.split('::')[2] + '">' + pr.split('::')[1] + '</option>';
                        } else {
                            prodtypes.forEach(prtype => {

                                if(prtype.split('::')[0] == pr.split('::')[0]){
                                    if(contype == 1){
                                        if(pr.split('::')[2].split(' ')[1] == 'Gallon' || pr.split('::')[2].split(' ')[1] == 'Quart' || pr.split('::')[2].split(' ')[1] == 'Pint' || pr.split('::')[2].split(' ')[1] == 'Fluid Ounce' || pr.split('::')[2].split(' ')[1] == 'Liter' || pr.split('::')[2].split(' ')[1] == 'Gallons' || pr.split('::')[2].split(' ')[1] == 'Quarts' || pr.split('::')[2].split(' ')[1] == 'Pints' || pr.split('::')[2].split(' ')[1] == 'Fluid Ounces' || pr.split('::')[2].split(' ')[1] == 'Liters' || (pr.split('::')[2].split(' ')[1]  == "Ounce" && (prtype.split('::')[1] == 4 || prtype.split('::')[1] == 8 || prtype.split('::')[1] == 9 || prtype.split('::')[1] == 10))  || (pr.split('::')[2].split(' ')[1] == "Ounces" && (prtype.split('::')[1] == 4 || prtype.split('::')[1] == 8 || prtype.split('::')[1] == 9 || prtype.split('::')[1] == 10)) || (pr.split('::')[2].split(' ')[1] == "Oz" && (prtype.split('::')[1] == 4 || prtype.split('::')[1] == 8 || prtype.split('::')[1] == 9 || prtype.split('::')[1] == 10)))
                                        {
                                            selectProdsHTML += '<option value="' + pr.split('::')[0] + '" data-cpu="' + pr.split('::')[2] + '">' + pr.split('::')[1] + '</option>';
                                        }
                                    } else if (contype == 2){
                                        if(pr.split('::')[2].split(' ')[1] == 'Ton' || pr.split('::')[2].split(' ')[1] == 'Pound' || pr.split('::')[2].split(' ')[1] == 'Kilogram' || pr.split('::')[2].split(' ')[1] == 'Ounce' || pr.split('::')[2].split(' ')[1] == 'Gram' || pr.split('::')[2].split(' ')[1] == 'Kilograms' || pr.split('::')[2].split(' ')[1] == 'Pounds' || pr.split('::')[2].split(' ')[1] == 'Tons' || pr.split('::')[2].split(' ')[1] == 'Ounces' || pr.split('::')[2].split(' ')[1] == 'Lb' || pr.split('::')[2].split(' ')[1] == 'Kg' || pr.split('::')[2].split(' ')[1] == 'Oz' || pr.split('::')[2].split(' ')[1] == 'Grams')
                                        {
                                            selectProdsHTML += '<option value="' + pr.split('::')[0] + '" data-cpu="' + pr.split('::')[2] + '">' + pr.split('::')[1] + '</option>';
                                        }
                                    }
                                }

                            });
                        }
                    });
                    selectProdsHTML += '</select>';
                }
                $('#label_btn').html('<label>Item Type</label>&nbsp;<button type="button" class="btn btn-success" id="prod_edit_btn">Edit Products</button>');
                $('#label_btn').addClass('margin-dyn');
                $('#edit_prods_drop').html(selectProdsHTML);

                $('#edit_item_prods').multiselect('destroy');

                $('#edit_item_prods').multiselect({
            
                    includeSelectAllOption: true,
                    onInitialized: function(select, container) {

                        console.log('Item Products: ' + item_prods);

                        $(".styled, .multiselect-container input").uniform({
                            radioClass: 'checker'
                        });
                    },
                    onSelectAll: function() {
                        $.uniform.update();
                    },
                    onChange: function(option, checked, select){

                        var unchecked_prods = [];
                        var checked_prods = [];

                        item_prods.forEach(it_pr => {
                            newStr = it_pr.split(':')[0] + ':' + it_pr.split(':')[1];
                            if(!checked_prods.includes(newStr)){
                                checked_prods.push(newStr);
                            }
                        });

                

                        var optionValue = $(option).val();
                        var optionText = $(option).text();
                        if(optionValue != ''){
                            var newOption = optionValue + ':' + optionText;
                        }


                    

                        if (checked) {  
                            if(optionValue != ''){
                                if (!checked_prods.includes(newOption)){
                                    checked_prods.push(newOption);
                                    if(unchecked_prods.includes(newOption)){
                                        var ind = unchecked_prods.indexOf(newOption);
                                        unchecked_prods.splice(ind, 1);
                                    }                
                                }
                            }                      
                        
                        } else {
                            if(optionValue != ''){
                                if(checked_prods.includes(newOption)){
                                    var index = checked_prods.indexOf(newOption);
                                    checked_prods.splice(index, 1);
                                    if(!unchecked_prods.includes(newOption)){
                                        unchecked_prods.push(newOption);
                                    }  
                                }
                                    
                            }
                        
                        }

                        if (checked_prods.length > 0) {
                            $('#options_checked_editprods').val(checked_prods.join('::'));
                        } else {
                            $('#options_checked_editprods').val('');
                        }

                        if(unchecked_prods.length > 0){
                            $('#options_unchecked_editprods').val(unchecked_prods.join('::'));
                        } else {
                            $('#options_unchecked_editprods').val('');
                        }       
                    
                    }        
                });
                if(contype == 1){
                    measurements = ['Gallon(s)', 'Fluid Ounce(s)', 'Liter(s)', 'Pint(s)', 'Quart(s)'];
                } else if(contype == 2){
                    measurements = ['Pound(s)', 'Ton(s)', 'Ounce(s)', 'Gram(s)', 'Kilogram(s)'];
                } else {
                    measurements = ['Gallon(s)', 'Fluid Ounce(s)', 'Liter(s)', 'Pint(s)', 'Quart(s)', 'Pound(s)', 'Ton(s)', 'Ounce(s)', 'Gram(s)', 'Kilogram(s)'];
                }


                unitsHTML = '<label>Unit Definition</label><br><div class="row"><div class="col-sm-4"><input type="number" value="' + unit_amount +'" class="form-control" name="unit_amount" id="unit_amount"></div><div class="col-sm-8"><select class="form-control" id="unit_type" name="unit_type">';
                measurements.forEach(meas => {
                    if(meas.split('(')[0] == unit_type.split('(')[0]){
                        unitsHTML += '<option value="' + meas + '" selected>'+ meas +'</option>';
                    } else {
                        unitsHTML += '<option value="' + meas + '">'+ meas +'</option>';
                    }
                });
                unitsHTML += '</select></div></div>';

                $('#units').html(unitsHTML);           

        });
        

        $('#item_type').on('change', function(){
            if($('#item_type').val() == 1){
                $('#modal_edit_products').show();
                $('#modal_edit_products').removeClass('fade');
                $('#modal_edit_item').hide();
                $('#modal_edit_item').addClass('fade');
            } else {
                $('#label_btn').html('<label>Item Type</label>');
                $('#label_btn').removeClass('margin-dyn');
                $('#units').html('<label>Unit Definition</label><div class="row"><div class="col-sm-4"><input type="number" class="form-control" name="unit_amount" id="unit_amount" value="' + unit_amount + '" ></div><div class="col-sm-8"><input type="text" class="form-control" id="unit_type" value="' + unit_type + '" name="unit_type"></div></div>');
                $('#modal_edit_products').hide();
                $('#modal_edit_products').addClass('fade');
                $('#modal_edit_item').show();
                $('#modal_edit_item').removeClass('fade');
            }
    
        });

        $('#prod_edit_btn').on('click', function(){
            if(prods.length > 0){
                selectProdsHTML = '<label>Products</label><select class="form-control multiselect-select-all" name="edit_item_prods" id="edit_item_prods" multiple="multiple">';
                prods.forEach(pr => {
                    if(item_prods.includes(pr)){
                        selectProdsHTML += '<option value="' + pr.split('::')[0] + '" selected data-cpu="' + pr.split('::')[2] + '">' + pr.split('::')[1] + '</option>';
                    } else {
                        prodtypes.forEach(prtype => {

                            if(prtype.split('::')[0] == pr.split('::')[0]){
                                if(contype == 1){
                                    if(pr.split('::')[2].split(' ')[1] == 'Gallon' || pr.split('::')[2].split(' ')[1] == 'Quart' || pr.split('::')[2].split(' ')[1] == 'Pint' || pr.split('::')[2].split(' ')[1] == 'Fluid Ounce' || pr.split('::')[2].split(' ')[1] == 'Liter' || pr.split('::')[2].split(' ')[1] == 'Gallons' || pr.split('::')[2].split(' ')[1] == 'Quarts' || pr.split('::')[2].split(' ')[1] == 'Pints' || pr.split('::')[2].split(' ')[1] == 'Fluid Ounces' || pr.split('::')[2].split(' ')[1] == 'Liters' || (pr.split('::')[2].split(' ')[1]  == "Ounce" && (prtype.split('::')[1] == 4 || prtype.split('::')[1] == 8 || prtype.split('::')[1] == 9 || prtype.split('::')[1] == 10))  || (pr.split('::')[2].split(' ')[1] == "Ounces" && (prtype.split('::')[1] == 4 || prtype.split('::')[1] == 8 || prtype.split('::')[1] == 9 || prtype.split('::')[1] == 10)) || (pr.split('::')[2].split(' ')[1] == "Oz" && (prtype.split('::')[1] == 4 || prtype.split('::')[1] == 8 || prtype.split('::')[1] == 9 || prtype.split('::')[1] == 10)))
                                    {
                                        selectProdsHTML += '<option value="' + pr.split('::')[0] + '" data-cpu="' + pr.split('::')[2] + '">' + pr.split('::')[1] + '</option>';
                                    }
                                } else if (contype == 2){
                                    if(pr.split('::')[2].split(' ')[1] == 'Ton' || pr.split('::')[2].split(' ')[1] == 'Pound' || pr.split('::')[2].split(' ')[1] == 'Kilogram' || pr.split('::')[2].split(' ')[1] == 'Ounce' || pr.split('::')[2].split(' ')[1] == 'Gram' || pr.split('::')[2].split(' ')[1] == 'Kilograms' || pr.split('::')[2].split(' ')[1] == 'Pounds' || pr.split('::')[2].split(' ')[1] == 'Tons' || pr.split('::')[2].split(' ')[1] == 'Ounces' || pr.split('::')[2].split(' ')[1] == 'Lb' || pr.split('::')[2].split(' ')[1] == 'Kg' || pr.split('::')[2].split(' ')[1] == 'Oz' || pr.split('::')[2].split(' ')[1] == 'Grams')
                                    {
                                        selectProdsHTML += '<option value="' + pr.split('::')[0] + '" data-cpu="' + pr.split('::')[2] + '">' + pr.split('::')[1] + '</option>';
                                    }
                                }
                            }

                        });                        
                    }
                });
                selectProdsHTML += '</select>';
            }
            $('#edit_prods_drop').html(selectProdsHTML);
            $('#edit_item_prods').multiselect('destroy');

            var edit_vendor_prices = [];

            $('#edit_item_prods').multiselect({
        
                includeSelectAllOption: true,
                onInitialized: function(select, container) {

                    $(".styled, .multiselect-container input").uniform({
                        radioClass: 'checker'
                    });
                },
                onSelectAll: function() {
                    $.uniform.update();
                },
                onChange: function(option, checked, select){
            
                    var unchecked_prods = [];
                    var checked_prods = [];

                    item_prods.forEach(it_pr => {
                        newStr = it_pr.split('::')[0] + ':' + it_pr.split('::')[1];
                        if(!checked_prods.includes(newStr)){
                            checked_prods.push(newStr);
                        }
                    });      

                    var optionValue = option.val();
                    var optionText = option.text();
                    if(optionValue != ''){
                        var newOption = optionValue + ':' + optionText;
                        var optionCPU = optionText + ' - ' + option.data('cpu');
                    }          

                    if (checked) {  
                        if(optionValue != ''){
                            if (!checked_prods.includes(newOption)){
                                checked_prods.push(newOption);
                                var checked_str = $('#item_prods').val();
                                checked_str += '<::>' + optionValue + '::' + optionText + '::' + option.data('cpu');
                                $('#item_prods').val(checked_str);
                                if(unchecked_prods.includes(newOption)){
                                    var ind = unchecked_prods.indexOf(newOption);
                                    unchecked_prods.splice(ind, 1);
                                }                
                            }
                        }                      
                    
                    } else {
                        if(optionValue != ''){
                            if(checked_prods.includes(newOption)){
                                var index = checked_prods.indexOf(newOption);
                                checked_prods.splice(index, 1);
                                if(!unchecked_prods.includes(newOption)){
                                    unchecked_prods.push(newOption);
                                }  
                            }
                                
                        }
                    
                    }


                    if(contype == 1){
                        measurements = ['Gallon(s)', 'Fluid Ounce(s)', 'Liter(s)', 'Pint(s)', 'Quart(s)'];
                    } else if(contype == 2){
                        measurements = ['Pound(s)', 'Ton(s)', 'Ounce(s)', 'Gram(s)', 'Kilogram(s)'];
                    } else {
                        measurements = ['Gallon(s)', 'Fluid Ounce(s)', 'Liter(s)', 'Pint(s)', 'Quart(s)', 'Pound(s)', 'Ton(s)', 'Ounce(s)', 'Gram(s)', 'Kilogram(s)'];
                    }


                    unitsHTML = '<label>Unit Definition</label><br><div class="row"><div class="col-sm-4"><input type="number" value="' + unit_amount +'" class="form-control" step="0.01" name="unit_amount" id="unit_amount"></div><div class="col-sm-8"><select class="form-control" id="unit_type" name="unit_type">';
                    measurements.forEach(meas => {
                        if(meas.split('(')[0] == unit_type.split('(')[0]){
                            unitsHTML += '<option value="' + meas + '" selected>'+ meas +'</option>';
                        } else {
                            unitsHTML += '<option value="' + meas + '">'+ meas +'</option>';
                        }
                    });
                    unitsHTML += '</select></div></div>';
                



                    if (checked_prods.length > 0) {
                        $('#options_checked_editprods').val(checked_prods.join('::'));
                    } else {
                        $('#options_checked_editprods').val('');
                    }

                    if(unchecked_prods.length > 0){
                        $('#options_unchecked_editprods').val(unchecked_prods.join('::'));
                    } else {
                        $('#options_unchecked_editprods').val('');
                    }       
                
                }        
            });
            $('#modal_edit_products').show();
            $('#modal_edit_products').removeClass('fade');
            $('#modal_edit_item').hide();
            $('#modal_edit_item').addClass('fade');
        });
        

        $('#avail').html(selectVendorsHTML);

        $('#item_type').html(selectTypesHTML);
        
        $('#brand_id').html(selectBrandsHTML);

        $("#available_vendors").multiselect('destroy');

        var edit_vendor_prices = [];
        var unchecked = [];
        var already_checked_full = [];
        var already_checked = [];
        var temp_checked = [];
        var deselected = false;

        $('#available_vendors').multiselect({
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            templates: {
            filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i><input class="form-control" type="text"/></li>'
        },
            onInitialized: function(select, container) {


                edit_prices.forEach(ep => {
                    if(!edit_vendor_prices.includes(ep)){
                        edit_vendor_prices.push(ep);
                    }
                });

                vendors.forEach(vendor => {
                    if(!item_vendors.includes(vendor)){
                        unchecked.push(vendor);
                    }
                });

                edit_vendor_prices.forEach(evp => {
                    var evp_str = evp.split(':')[0] + ':' + evp.split(':')[1];
                    if(!already_checked_full.includes(evp)){
                        already_checked.push(evp_str);
                        var index = already_checked.indexOf(evp_str);
                        already_checked_full[index] = evp;
                        temp_checked[index] = evp_str;                 
                    }
                    
                });

                if(vendors.length == already_checked.length){
                    deselected = false;
                } else if (already_checked.length == 0){
                    deselected = true;
                }

                console.log('Item Vendors: ' + item_vendors);

                if(item_vendors.length > 0){
                        $('#options_checked').val(item_vendors.join('::'));
                    }  else {
                        $('#options_checked').val('');
                    }   

                    if(unchecked.length > 0){
                    $('#options_unchecked').val(unchecked.join('::'));
                } else {
                    $('#options_unchecked').val('');
                }
                

                if(temp_checked.length > 0){
                    editPricesHTML = '<label style="padding: 6px 32px;">Vendor Info</label><br>';
                    temp_checked.forEach(temp_c => {
                        if(!unchecked.includes(temp_c)){
                            if(already_checked.includes(temp_c)){
                                
                                var index = already_checked.indexOf(temp_c);
                                var v_notes = already_checked_full[index].split(':')[3];
                                if(already_checked_full[index].split(':')[3] == 'N/A'){
                                    // console.log('Eureka we have a match');
                                    v_notes = '';
                                }
                                editPricesHTML += '<div class="col-sm-12" style="padding: 8px 32px !important; width: 50%;"><span style="color: #12689B;">' + already_checked_full[index].split(':')[1] + '  </span><div class="input-group"><span class="input-group-btn"><span class="btn btn-success">$</span></span><input class="form-control edit_vendor_price" value="' + already_checked_full[index].split(':')[2] + '" data-vendor="' + already_checked_full[index].split(':')[0] + '" type="number" min="0.00" step="0.01" name="edit_vendor_price" placeholder="Vendor Price Per Unit"></div><textarea class="form-control edit_vendor_notes"  style="margin-top: 8px;" name="edit_vendor_notes" data-vendor_id="' + already_checked_full[index].split(':')[0] + '" placeholder="Vendor Notes">' + v_notes + '</textarea></div>';
                            } else {
                                editPricesHTML += '<div class="col-sm-12" style="padding: 8px 32px !important; width: 50%;"><span style="color: #12689B;">' + temp_c.split(':')[1] + '  </span><div class="input-group"><span class="input-group-btn"><span class="btn btn-success">$</span></span><input class="form-control edit_vendor_price" data-vendor="' + temp_c.split(':')[0] + '" type="number" min="0.00" step="0.01" name="edit_vendor_price" placeholder="Vendor Price Per Unit"></div><textarea class="form-control edit_vendor_notes" style="margin-top: 8px;" name="edit_vendor_notes" data-vendor_id="' + temp_c.split(':')[0] + '" placeholder="Vendor Notes"></textarea></div>';
                            }    
                        }                
                    });
                } else {
                    editPricesHTML = '';
                }

                $('#edit_prices').html(editPricesHTML);

                $(".styled, .multiselect-container input").uniform({
                    radioClass: 'checker'
                });
        

            },
            onSelectAll: function() {
                
                $.uniform.update();
                var selected = $('#available_vendors').val();

                if(selected != null){
                    edit_prices.forEach(ep => {
                        if(!edit_vendor_prices.includes(ep)){
                            edit_vendor_prices.push(ep);
                        }
                    });

                    edit_vendor_prices.forEach(evp => {
                        var evp_str = evp.split(':')[0] + ':' + evp.split(':')[1];
                        if(!already_checked_full.includes(evp)){
                            already_checked.push(evp_str);
                            var index = already_checked.indexOf(evp_str);
                            already_checked_full[index] = evp;
                            temp_checked[index] = evp_str;                 
                        }
                    });

                    vendors.forEach(ven => {
                        if (!item_vendors.includes(ven)){
                            item_vendors.push(ven);
                            if(unchecked.includes(ven)){
                                var ind = unchecked.indexOf(ven);
                                unchecked.splice(ind, 1);
                        
                            }
                        
                        }

                        if(!temp_checked.includes(ven)){
                            temp_checked.push(ven);
                        }                   
                            
                    });

                    if(item_vendors.length > 0){
                        $('#options_checked').val(item_vendors.join('::'));
                    }  else {
                        $('#options_checked').val('');
                    }    

                    if(temp_checked.length > 0){
                        editPricesHTML = '<label style="padding: 6px 32px;">Vendor Info</label><br>';
                        temp_checked.forEach(temp_c => {
                            if(!unchecked.includes(temp_c)){
                                if(already_checked.includes(temp_c)){
                                    var index = already_checked.indexOf(temp_c);
                                    editPricesHTML += '<div class="col-sm-12" style="padding: 8px 32px !important; width: 50%;"><span style="color: #12689B;">' + already_checked_full[index].split(':')[1] + '  </span><div class="input-group"><span class="input-group-btn"><span class="btn btn-success">$</span></span><input class="form-control edit_vendor_price" value="' + already_checked_full[index].split(':')[2] + '" data-vendor="' + already_checked_full[index].split(':')[0] + '" type="number" min="0.00" step="0.01" name="edit_vendor_price" placeholder="Vendor Price Per Unit"></div><textarea class="form-control edit_vendor_notes"  style="margin-top: 8px;" name="edit_vendor_notes" data-vendor_id="' + already_checked_full[index].split(':')[0] + '" placeholder="Vendor Notes">' + already_checked_full[index].split(':')[3] + '</textarea></div>';
                                } else {
                                    editPricesHTML += '<div class="col-sm-12" style="padding: 8px 32px !important; width: 50%;"><span style="color: #12689B;">' + temp_c.split(':')[1] + '  </span><div class="input-group"><span class="input-group-btn"><span class="btn btn-success">$</span></span><input class="form-control edit_vendor_price" data-vendor="' + temp_c.split(':')[0] + '" type="number" min="0.00" step="0.01" name="edit_vendor_price" placeholder="Vendor Price Per Unit"></div><textarea class="form-control edit_vendor_notes" style="margin-top: 8px;" name="edit_vendor_notes" data-vendor_id="' + temp_c.split(':')[0] + '" placeholder="Vendor Notes"></textarea></div>';
                                }  
                            }                  
                        });
                    } else {
                        editPricesHTML = '';
                    }

                    $('#edit_prices').html(editPricesHTML);

                    if (item_vendors.length > 0){
                        selectItemVendorsHTML = '';
                        selectItemVendorsHTML += "<label>Preferred Vendor</label><select class='form-control' name='preferred_vendor' id='preferred_vendor'>";
                    if (item_vendors.length == 1){
                        item_vendors.forEach(item_ven => {
                            selectItemVendorsHTML += "<option value='" + item_ven + "' selected>" + item_ven.split(':')[1] + "</option>";
                        });
                    } else {
                        item_vendors.forEach(item_ven => {
                            if(item_ven == pref){
                                selectItemVendorsHTML += "<option value='" + item_ven + "' selected>" + item_ven.split(':')[1] + "</option>";
                            } else {
                                selectItemVendorsHTML += "<option value='" + item_ven + "'>" + item_ven.split(':')[1] + "</option>";
                            }
        
                        });
                    }
                    selectItemVendorsHTML += '</select>';
                } else {
                    selectItemVendorsHTML = '<label>Preferred Vendor</label><input type="text" class="form-control" name="preferred_vendor" value="No Vendors Available" id="preferred_vendor">'
                }

                $('#prefer').html(selectItemVendorsHTML);
            } else {
                item_vendors = [];
                if(item_vendors.length > 0){
                    $('#options_checked').val(item_vendors.join('::'));
                }  else {
                    $('#options_checked').val('');
                }
                vendors.forEach(vend => {
                    if(!unchecked.includes(vend)){
                        unchecked.push(vend);
                    } 
                });
                if(unchecked.length > 0){
                    $('#options_unchecked').val(unchecked.join('::'));
                } else {
                    $('#options_unchecked').val('');
                }
                $('#edit_prices').html('');
                selectItemVendorsHTML = '<label>Preferred Vendor</label><input type="text" class="form-control" name="preferred_vendor" value="No Vendors Available" id="preferred_vendor">';
                $('#prefer').html(selectItemVendorsHTML);
            }
                
            
        },
        onDeselectAll: function(){
            $.uniform.update();
        },
        
        onChange: function(option, checked, select){


            var optionValue = $(option).val();
            var optionText = $(option).text();
            if(optionValue != ''){
                var newOption = optionValue + ':' + optionText;
            }

            if (checked) {  

                if(!temp_checked.includes(newOption)){
                    temp_checked.push(newOption);
                }
                if (!item_vendors.includes(newOption)){
                    item_vendors.push(newOption);
                    if(unchecked.includes(newOption)){
                        var ind = unchecked.indexOf(newOption);
                        unchecked.splice(ind, 1);
                        
                    }                   
                }                    
                
            } else {
                    
                if(temp_checked.includes(newOption)){
                    var tind = temp_checked.indexOf(newOption);
                    temp_checked.splice(tind, 1);
                }

                if(item_vendors.includes(newOption)){
                    var index = item_vendors.indexOf(newOption);
                    item_vendors.splice(index, 1);
                    if(!unchecked.includes(newOption)){
                        unchecked.push(newOption);
                    }  
                }            
            }
            if(item_vendors.length > 0){
                $('#options_checked').val(item_vendors.join('::'));
            }  else {
                $('#options_checked').val('');
            }

                if(unchecked.length > 0){
                    $('#options_unchecked').val(unchecked.join('::'));
                } else {
                    $('#options_unchecked').val('');
                }
            
                if(temp_checked.length > 0){
                    editPricesHTML = '<label style="padding: 6px 32px;">Vendor Info</label><br>';
                    temp_checked.forEach(temp_c => {
                        if(!unchecked.includes(temp_c)){
                            if(already_checked.includes(temp_c)){
                                var index = already_checked.indexOf(temp_c);
                                editPricesHTML += '<div class="col-sm-12" style="padding: 8px 32px !important; width: 50%;"><span style="color: #12689B;">' + already_checked_full[index].split(':')[1] + '  </span><div class="input-group"><span class="input-group-btn"><span class="btn btn-success">$</span></span><input class="form-control edit_vendor_price" value="' + already_checked_full[index].split(':')[2] + '" data-vendor="' + already_checked_full[index].split(':')[0] + '" type="number" min="0.00" step="0.01" name="edit_vendor_price" placeholder="Vendor Price Per Unit"></div><textarea class="form-control edit_vendor_notes"  style="margin-top: 8px;" name="edit_vendor_notes" data-vendor_id="' + already_checked_full[index].split(':')[0] + '" placeholder="Vendor Notes">' + already_checked_full[index].split(':')[3] + '</textarea></div>';
                            } else {
                                editPricesHTML += '<div class="col-sm-12" style="padding: 8px 32px !important; width: 50%;"><span style="color: #12689B;">' + temp_c.split(':')[1] + '  </span><div class="input-group"><span class="input-group-btn"><span class="btn btn-success">$</span></span><input class="form-control edit_vendor_price" data-vendor="' + temp_c.split(':')[0] + '" type="number" min="0.00" step="0.01" name="edit_vendor_price" placeholder="Vendor Price Per Unit"></div><textarea class="form-control edit_vendor_notes" style="margin-top: 8px;" name="edit_vendor_notes" data-vendor_id="' + temp_c.split(':')[0] + '" placeholder="Vendor Notes"></textarea></div>';
                            }    
                        }              
                    });
                } else {
                    editPricesHTML = '';
                }

                $('#edit_prices').html(editPricesHTML);

                if (item_vendors.length > 0 && item_vendors[0] != ''){
                    selectItemVendorsHTML = '';
                    selectItemVendorsHTML += "<label>Preferred Vendor</label><select class='form-control' name='preferred_vendor' id='preferred_vendor'>";
                    if (item_vendors.length == 1){
                        item_vendors.forEach(item_ven => {
                            selectItemVendorsHTML += "<option value='" + item_ven + "' selected>" + item_ven.split(':')[1] + "</option>";
                        });
                    } else {
                        item_vendors.forEach(item_ven => {
                            if(item_ven == pref){
                                selectItemVendorsHTML += "<option value='" + item_ven + "' selected>" + item_ven.split(':')[1] + "</option>";
                            } else {
                                selectItemVendorsHTML += "<option value='" + item_ven + "'>" + item_ven.split(':')[1] + "</option>";
                            }
        
                        });
                    }
                    selectItemVendorsHTML += '</select>';
                } else {
                    selectItemVendorsHTML = '<label>Preferred Vendor</label><input type="text" class="form-control" name="preferred_vendor" value="No Vendors Available" id="preferred_vendor">'
                }

                $('#prefer').html(selectItemVendorsHTML);
            
            }        

        });
        

        

    });


    $(document).ready(function(){
        var vendor_prices_per = [];
        var pricesHTML = "";
        $('#new_units').html('<label>Unit Definition</label><div class="row"><div class="col-sm-4"><input type="number" step="0.01" min="0" class="form-control" name="unit_amount" id="unit_amount" placeholder="Measurement Amount" required></div><div class="col-sm-8"><input type="text" class="form-control" id="unit_type" name="unit_type" placeholder="Measurement Unit" required></div></div>');

        $("#available_vendors_new").multiselect('destroy');

        var new_unchecked = [];
        var new_item_vendors = [];
        
        $('#available_vendors_new').multiselect({
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            includeDeselectAllOption: true,
            templates: {
                filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i><input class="form-control" type="text"/></li>'
            },
            onInitialized: function(select, container) {
                $(".styled, .multiselect-container input").uniform({
                    radioClass: 'checker'
                });
            },
            onSelectAll: function() {
                $.uniform.update();
                var vendors = $('#vendors_list').val().split('::');
                var temp_checked = [];
                vendors.forEach(ven => {
                    if(!temp_checked.includes(ven)){
                        temp_checked.push(ven);
                    }                      
                });
                console.log(temp_checked);
                

                if(vendors.length > 0){
                    $('#options_checked_new').val(vendors.join('::'));
                }  else {
                    $('#options_checked_new').val('');
                }    

                if(temp_checked.length > 0){
                    editPricesHTML = '<label style="padding: 6px 32px;">Vendor Info</label><br>';
                    temp_checked.forEach(temp_c => {
                        editPricesHTML += '<div class="col-sm-12" style="padding: 8px 32px !important; width: 50%;"><span style="color: #12689B;">' + temp_c.split(':')[1] + '  </span><div class="input-group"><span class="input-group-btn"><span class="btn btn-success">$</span></span><input class="form-control vendor_price" data-vendor="' + temp_c.split(':')[0] + '" type="number" min="0.00" step="0.01" name="vendor_price" placeholder="Vendor Price Per Unit"></div><textarea class="form-control vendor_notes" style="margin-top: 8px;" name="vendor_notes" data-vendor_id="' + temp_c.split(':')[0] + '" placeholder="Vendor Notes"></textarea></div>';          
                    });
                } else {
                    editPricesHTML = '';
                }

                $('#new_prices').html(editPricesHTML);

                if (vendors.length > 0){
                    selectItemVendorsHTML = '';
                    selectItemVendorsHTML += "<label>Preferred Vendor</label><select class='form-control' name='preferred_vendor' id='preferred_vendor'>";
                    if (vendors.length == 1){
                        vendors.forEach(item_ven => {
                            selectItemVendorsHTML += "<option value='" + item_ven + "' selected>" + item_ven.split(':')[1] + "</option>";
                        });
                    } else {
                        vendors.forEach(item_ven => {
                            
                            selectItemVendorsHTML += "<option value='" + item_ven + "'>" + item_ven.split(':')[1] + "</option>";
        
                        });
                    }
                selectItemVendorsHTML += '</select>';
                } else {
                    selectItemVendorsHTML = '<label>Preferred Vendor</label><input type="text" class="form-control" name="preferred_vendor" value="No Vendors Available" id="preferred_vendor">'
                }

                $('#new_prefer').html(selectItemVendorsHTML);
            
            },
            onChange: function(option, checked, select){

                var optionValue = $(option).val();
                var optionText = $(option).text();
                if(optionValue != ''){
                    var newOption = optionValue + ':' + optionText;
                }

                if (checked) {  

                    if (!vendor_prices_per.includes(newOption)){
                        vendor_prices_per.push(newOption);
                    }
                    if(optionValue != ''){
                        if (!new_item_vendors.includes(newOption)){
                            new_item_vendors.push(newOption);
                            if(new_unchecked.includes(newOption)){
                                var ind = new_unchecked.indexOf(newOption);
                                new_unchecked.splice(ind, 1);
                            }                
                        }
                    }                      
                
                } else {
                    if(optionValue != ''){
                        if (vendor_prices_per.includes(newOption)){
                            v_ind = vendor_prices_per.indexOf(newOption);
                            vendor_prices_per.splice(v_ind, 1);
                        }
                        if(new_item_vendors.includes(newOption)){
                            var index = new_item_vendors.indexOf(newOption);
                            new_item_vendors.splice(index, 1);
                            if(!new_unchecked.includes(newOption)){
                                new_unchecked.push(newOption);
                            }  
                        }
                            
                    }
                
                }
                if(new_item_vendors.length > 0){
                    $('#options_checked_new').val(new_item_vendors.join('::'));
                } else {
                    $('#options_checked_new').val('');
                }

                if(new_unchecked.length > 0){
                    $('#options_unchecked_new').val(new_unchecked.join('::'));
                } else {
                    $('#options_unchecked_new').val('');
                }


                if(vendor_prices_per.length > 0){
                    pricesHTML = '<label style="padding: 6px 32px;">Vendor Info</label><br>';
                    vendor_prices_per.forEach(vpp => {
                        pricesHTML += '<div class="col-sm-12" style="padding: 8px 32px !important; width: 50%;"><span style="color: #12689B;">' + vpp.split(':')[1] + '  </span><div class="input-group"><span class="input-group-btn"><span class="btn btn-success">$</span></span><input class="form-control vendor_price" data-vendor="' + vpp.split(':')[0] + '" type="number" min="0.00" step="0.01" name="vendor_price" placeholder="Vendor Price Per Unit"></div><textarea class="form-control vendor_notes" style="margin-top: 8px;" name="vendor_notes" data-vendor_id="' + vpp.split(':')[0] + '" placeholder="Vendor Notes"></textarea></div>';
                    });
                } else {
                    pricesHTML = "";
                }

                $('#new_prices').html(pricesHTML);

                if (new_item_vendors.length > 0){
                    selectItemVendorsHTML = '';
                    selectItemVendorsHTML += "<label>Preferred Vendor</label><select class='form-control' name='preferred_vendor' id='preferred_vendor'>";
                    if (new_item_vendors.length == 1){
                        new_item_vendors.forEach(item_ven => {
                            selectItemVendorsHTML += "<option value='" + item_ven + "' selected>" + item_ven.split(':')[1] + "</option>";
                        });
                    } else {
                        new_item_vendors.forEach(item_ven => {
                            
                            selectItemVendorsHTML += "<option value='" + item_ven + "'>" + item_ven.split(':')[1] + "</option>";
        
                        });
                    }
                selectItemVendorsHTML += '</select>';
                } else {
                    selectItemVendorsHTML = '<label>Preferred Vendor</label><input type="text" class="form-control" name="preferred_vendor" value="No Vendors Available" id="preferred_vendor">'
                }

                $('#new_prefer').html(selectItemVendorsHTML);
            }
        });

    });

    $(document).on('change', '#new_item_type', function(){
        if($(this).val() == 1){
            measurements = ['Gallon(s)', 'Fluid Ounce(s)', 'Liter(s)', 'Pint(s)', 'Quart(s)', 'Pound(s)', 'Ton(s)', 'Ounce(s)', 'Gram(s)', 'Kilogram(s)'];


            unitsHTML = '<label>Unit Definition</label><br><div class="row"><div class="col-sm-4"><input type="number" step="0.01" class="form-control" name="unit_amount" id="unit_amount"></div><div class="col-sm-8"><select class="form-control" id="unit_type" name="unit_type">';
            measurements.forEach(meas => {        
                unitsHTML += '<option value="' + meas + '">'+ meas +'</option>';
            });
            unitsHTML += '</select></div></div>';

            $('#new_units').html(unitsHTML);
            $('#modal_new_products').show();
            $('#modal_new_products').removeClass('fade');
            $('#modal_new_item').hide();
            $('#modal_new_item').addClass('fade');
        } else {
            $('#new_units').html('<label>Unit Definition</label><div class="row"><div class="col-sm-4"><input type="number" step="0.01" class="form-control" name="unit_amount" id="unit_amount" placeholder="Measurement Amount"></div><div class="col-sm-8"><input type="text" class="form-control" id="unit_type" name="unit_type" placeholder="Measurement Unit"></div></div>');
            $('#modal_new_products').hide();
            $('#modal_new_products').addClass('fade');
            $('#modal_new_item').show();
            $('#modal_new_item').removeClass('fade');
        }
    });

    $(document).on('click', '#product_close', function(){
        $('#modal_new_products').hide();
        $('#modal_new_products').addClass('fade');
        $('#modal_new_item').show();
        $('#modal_new_item').removeClass('fade');
    });

    $(document).on('submit', '#new_product_form', function(e){
        e.preventDefault();
        $('#modal_new_products').hide();
        $('#modal_new_products').addClass('fade');
        $('#modal_new_item').show();
        $('#modal_new_item').removeClass('fade');
    });

    $(document).on('click', '#edit_product_close', function(){
        $('#modal_edit_products').hide();
        $('#modal_edit_products').addClass('fade');
        $('#modal_edit_item').show();
        $('#modal_edit_item').removeClass('fade');
    });

    $(document).on('submit', '#edit_product_form', function(e){
        e.preventDefault();
        $('#modal_edit_products').hide();
        $('#modal_edit_products').addClass('fade');
        $('#modal_edit_item').show();
        $('#modal_edit_item').removeClass('fade');
    });


</script>

<script>
    $(document).ready(function(){
        
        var product_arr = $('#product_arr').val().split('<::>');
        
        $(document).on('change', '#unit_conversion_type', function(){

            $('#unit_type_conversion').val($(this).val());
            $('#new_products').multiselect('destroy');

            var prodsHTML = '';
  
            console.log($(this).val());
            if($(this).val() == 1){      
                
                var prods_arr = [];

                product_arr.forEach(prar => {
                    console.log(prar);
                    if(prar.split('::')[2] == 'Gallon' || prar.split('::')[2] == 'Quart' || prar.split('::')[2] == 'Pint' || prar.split('::')[2] == 'Fluid Ounce' || prar.split('::')[2] == 'Liter' || prar.split('::')[2] == 'Gallons' || prar.split('::')[2] == 'Quarts' || prar.split('::')[2] == 'Pints' || prar.split('::')[2] == 'Fluid Ounces' || prar.split('::')[2] == 'Liters' || (prar.split('::')[2]  == "Ounce" && (prar.split('::')[3] == 4 || prar.split('::')[3] == 8 || prar.split('::')[3] == 9 || prar.split('::')[3] == 10))  || (prar.split('::')[2] == "Ounces" && (prar.split('::')[3] == 4 || prar.split('::')[3] == 8 || prar.split('::')[3] == 9 || prar.split('::')[3] == 10)) || (prar.split('::')[2] == "Oz" && (prar.split('::')[3] == 4 || prar.split('::')[3] == 8 || prar.split('::')[3] == 9 || prar.split('::')[3] == 10)))
                    {
                        prodsHTML += '<option value="'+ prar.split('::')[0] +'">'+ prar.split('::')[1] +'</option>';
                    }
                });
                $('#new_products').html(prodsHTML);

                $('#new_products').multiselect({
      
                    includeSelectAllOption: true,
                    onInitialized: function(select, container) {
                        $(".styled, .multiselect-container input").uniform({
                            radioClass: 'checker'
                        });
                    },
                    onSelectAll: function() {
                        $.uniform.update();
                    },
                    onChange: function(option, checked, select){
                        if(!prods_arr.includes($(option).val())){
                            prods_arr.push($(option).val());
                        }
                        $('#options_checked_prods').val(prods_arr.join('::'));
                    }      
                });

                measurements = ['Gallon(s)', 'Fluid Ounce(s)', 'Liter(s)', 'Pint(s)', 'Quart(s)'];


                unitsHTML = '<label>Unit Definition</label><br><div class="row"><div class="col-sm-4"><input type="number" step="0.01" class="form-control" name="unit_amount" id="unit_amount"></div><div class="col-sm-8"><select class="form-control" id="unit_type" name="unit_type">';
                measurements.forEach(meas => {        
                    unitsHTML += '<option value="' + meas + '">'+ meas +'</option>';
                });
                unitsHTML += '</select></div></div>';

                $('#new_units').html(unitsHTML);
            } else if($(this).val() == 2){      
                
                var prods_arr = [];

                product_arr.forEach(prar => {
                    console.log(prar);
                    if(prar.split('::')[2] == 'Ton' || prar.split('::')[2] == 'Pound' || prar.split('::')[2] == 'Kilogram' || prar.split('::')[2] == 'Ounce' || prar.split('::')[2] == 'Gram' || prar.split('::')[2] == 'Kilograms' || prar.split('::')[2] == 'Pounds' || prar.split('::')[2] == 'Tons' || prar.split('::')[2] == 'Ounces' || prar.split('::')[2] == 'Lb' || prar.split('::')[2] == 'Kg' || prar.split('::')[2] == 'Oz' || prar.split('::')[2] == 'Grams')
                    {
                        prodsHTML += '<option value="'+ prar.split('::')[0] +'">'+ prar.split('::')[1] +'</option>';
                    }
                });
                $('#new_products').html(prodsHTML);

                $('#new_products').multiselect({
      
                    includeSelectAllOption: true,
                    onInitialized: function(select, container) {
                        $(".styled, .multiselect-container input").uniform({
                            radioClass: 'checker'
                        });
                    },
                    onSelectAll: function() {
                        $.uniform.update();
                    },
                    onChange: function(option, checked, select){
                        if(!prods_arr.includes($(option).val())){
                            prods_arr.push($(option).val());
                        }
                        $('#options_checked_prods').val(prods_arr.join('::'));
                    }      
                });

                measurements = ['Pound(s)', 'Ton(s)', 'Ounce(s)', 'Gram(s)', 'Kilogram(s)'];


                unitsHTML = '<label>Unit Definition</label><br><div class="row"><div class="col-sm-4"><input type="number" step="0.01" class="form-control" name="unit_amount" id="unit_amount"></div><div class="col-sm-8"><select class="form-control" id="unit_type" name="unit_type">';
                measurements.forEach(meas => {        
                    unitsHTML += '<option value="' + meas + '">'+ meas +'</option>';
                });
                unitsHTML += '</select></div></div>';

                $('#new_units').html(unitsHTML);
            }
        })
    });

    $(document).on('blur', '#item_number', function(){
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>inventory/Frontend/Items/checkItemNumberUniqueness',
            data: {item_number: $(this).val()},
            dataType: "JSON",
            success: function (result)
            {
                console.log("result is " + JSON.stringify(result));
                if(result.data > 0){
                    console.log('Not Unique');
                    $('#unique_check').html('<div class="col-md-12 alert-danger alert-dismissible" style="text-align: center;"><p>Item # is taken. Please choose another one.</p></div>');
                    $('#savenewitem').attr('disabled', true);
                } else {
                    console.log('Unique');
                    $('#unique_check').html('<div class="col-md-12 alert-success alert-dismissible" style="text-align: center;"><p>Item # is available.</p></div>');
                    $('#savenewitem').attr('disabled', false);
                    setTimeout(function(){$('#unique_check').html('')}, 3000);
                }
            }
        });
    });
                
</script>