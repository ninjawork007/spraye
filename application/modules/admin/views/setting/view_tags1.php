<div  class="table-responsive spraye-table">
	 <table  class="table datatable-basic-tag tadatablerespons" id="DataTables_Table_0" style="border: 1px solid #6eb1fd; border-radius: 4px; border-bottom: unset;">    
		  <thead style="background: #3379b740;">  
			<b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
			<tr>
				<th>Name</th> 
				<th>Viewable by Technician</th>
				<th>Action</th>
			</tr>  
		</thead>
		<tbody class="scroll-section">
		<?php if (!empty($TagData)) { $iCtr=1; foreach ($TagData as $value) { ?>
			<tr>  
				<td><?php echo $value->tags_title;?></td> 
				<td><?php if($value->include_in_tech_view == 1){ echo "Yes"; }else{ echo "No"; } ?></td>
				<td class="table-action">
					<?php if($value->company_id != 0){ ?> 
					<ul style="list-style-type: none; padding-left: 0px;">
						<li style="display: inline; padding-right: 10px;">
							<a onclick="editTag(<?= $value->id ?>)" data-toggle="modal" data-target="#modal_edit_tag" ><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
						</li>
						<li style="display: inline; padding-right: 10px;">
							<a onclick="tagDelete(<?= $value->id ?>)" class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
						</li>
					</ul>
					<?php } else{ echo "N/A"; }?>
				</td>
			</tr>
		<?php $iCtr++;} }  ?>
		</tbody>
	</table>
</div>

<script src="<?= base_url() ?>assets/popup/js/sweetalert2.all.js"></script>
<script type="text/javascript">
function tagDelete(tag_id){
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
	   $("#loading").css("display", "block");
	  var _url = "<?= base_url('admin/setting/tagDelete/') ?>"+tag_id;
	  console.log("url:"+_url);

			  $.ajax({
				  url: _url,
				  type: "GET",
				  success: function(response) {
					$("#loading").css("display", "none");
					  getTagList();
					  swal(
							'Tag',
							'Deleted Successfully ',
							'success'
						)
				  },
				  error: function(e) {
					console.log("error");
				}
			  });
			}

	});
}
</script>