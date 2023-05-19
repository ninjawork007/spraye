<style type="text/css">
  .toolbar {
    float: left;
    padding-left: 5px;
}
.form-control[readonly] {
  background-color: #ededed;
}
#invoice-age-list .dropdown-menu {
    min-width: 80px !important;
}
</style>
<div class="loading" style="display:none;">
	<center><img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/></center>
</div>

<div id="invoice-age-list">
<div class="post-list" <?php if(empty($report_details)){ ?> style="padding-top:20px" <?php } ?> >
	<div class="table-responsive table-spraye">
		<table class="table datatable-button-print-basic">
			<thead>
				<tr>
					<th>Subject</th>
					<th>Sent Date</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($EmailList as $Index => $Data) {
				?>
				<tr>
					<td><?php echo $Data->email_subject?></td>
					<td>
						<?php 
						if($Data->send_date != "0000-00-00"){
							echo $Data->send_date;
						}
					?>
					</td>
					<td><?php echo $Data->status == 0 ? "Draft" : "Sent" ?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
</div>
</th>
<script>
$(document).ready(function() {
      tableInitialize();
});
function tableInitialize(argument) {
// Setting Datatable Defaults
      $.extend( $.fn.dataTable.defaults, {
          autoWidth: false,
          dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
          language: {
              search: '<span>Filter:</span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
      });
// Basic Initialization
      $('.datatable-button-print-basic').DataTable({
          "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
          "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
          buttons: [
              {
                  extend: 'print',
                  text: '<i class="icon-printer position-left"></i> Print table',
                  className: 'btn bg-blue'
              },
			  
          ]
      });
}	
function filterInterval(interval){
	$('input#interval').val(interval);
	searchFilter();
}
function searchFilter() {
	var customer = $('#customer').val();
	var start_date = $("#start_date").val();
	var end_date = $("#end_date").val();

	$('.loading').css("display", "block");
	
	$('#invoice-age-list').html('');
	
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxDataForTevenueServieType',
        data:'customer='+customer+"&start_date="+start_date+"&end_date="+end_date,
        success: function (html) {
            $(".loading").css("display", "none");
            $('#invoice-age-list').html(html);
            tableInitialize();
        }
    });
}
function resetform(){
	location.reload();
}
</script>