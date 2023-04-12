<style type="text/css">
  /* Disabled Menu Items */
li.dropdown-menu-item.text-muted.dropdown-menu-item-icon.disabled > a {
    cursor: not-allowed;
    pointer-events: none;
}  
  /* End */
  .myspan {
  width: 55px;
}
.label-warning, .bg-warning {
  background-color :#A9A9A9;
  background-color: #A9A9AA;
  border-color: #A9A9A9;
}
.checkbox label, .radio label {
  padding-top : 0px  !important;
}

.checkbox-inline  .checker {
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
.table-spraye  table#editcustmerpropertytbl {
     border: 1px solid #6eb1fd;
    border-radius: 4px;
}
.label-till , .bg-till  {
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
	
.dash-tbl table#unassignedServices, 
.dash-tbl table#outstandingInvoices,
.dash-tbl table#assignedPrograms,
.dash-tbl table#scheduledServices,
.dash-tbl table#notes
	{
    border: 1px solid #6eb1fd !important;
    border-radius: 4px !important;
}
#customerName {color: #01669A;}
	.tabbable {padding: 30px 0;}

button#go_to_customer{border-radius: 3px;}

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
    font-size: 1.5rem;
    font-weight: bold;
  }
  li.dropdown-menu-item.text-muted.dropdown-menu-item-icon a {
    padding-left: 0;
  }
  /* .customer-name, .customer-address {
      border-left: 2px grey solid;
  } */
  .text-muted, a.text-muted:hover, a.text-muted:focus {
      color: #acacac;
  }
  div.note-footer {
    font-size: 1.5rem;
    font-weight: bold;
    color:#4a4a4a;
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
div#note-form-wrap {
  margin-bottom: 2em;
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
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
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

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
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
	
@media(max-width:1024px){
	div.go-to-customer div {
		margin-top:0px!important;
	} 
	button#go_to_customer{
		padding: 9px 17px;
	}
}	
@media (max-width: 768px){
.table-responsive {
    min-height: auto;
    margin-top: 10px;
    margin-bottom: 10px;
}
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
                           <div  class="col-md-12">
                             	
                                  <div class="btndiv col-md-8 ">
                                   
                                    <a href="<?= base_url('admin'); ?>"  id="save" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Back to Dashboard</a>

                                  </div>
					
								   
								 </div>  
								
							 </div>
                               
                            </div>
                           
                           </div>
                         </div>
                   </h5>
				
                <hr>

                <div class="panel-body">

                <div id="note-type-filter">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-8 col-lg-6">
                        <div class="btn-group btn-group-justified" role="group" aria-label="tab-select">
                          <div class="btn-group properties-tab-parent" role="group">
                            <button type="button" class="btn btn-default properties-tab-btn properties-tab-active" id="note-filter-all">All Notes</button>
                          </div>
                          <div class="btn-group properties-tab-parent" role="group">
                            <button type="button" class="btn btn-default properties-tab-btn" id="note-filter-customer">Customer Notes</button>
                          </div>
                          <div class="btn-group properties-tab-parent" role="group">
                            <button type="button" class="btn btn-default properties-tab-btn" id="note-filter-property">Property Notes</button>
                          </div>
                          <div class="btn-group properties-tab-parent" role="group">
                            <button type="button" class="btn btn-default properties-tab-btn" id="note-filter-technician">Technician Notes</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row" style="margin-top:15px;">
                      <div class="col-md-3 form-inline" style="padding-left:15px;">
                          <div class="form-group note-filter-selections" style="padding-left:15px;">
                            <label for="note_creator_filter">Note Creator Filter</label>
                            <select class="form-control" name="note_creator_filter" id="note_creator_filter" placeholder="None">
                              <option value="0" selected>None</option>
                              <?php 
                                foreach($userdata as $user)
                                {
                                ?>
                                  <option value="<?= $user->id; ?>"><?= $user->user_first_name.' '.$user->user_last_name; ?></option>
                                <?php
                                }
                              ?>
                            </select>
                          </div>
                      </div>
                      <div class="col-md-3 form-inline" style="padding-left:15px;">
                        <div class="form-group note-filter-selections" style="padding-left:15px;">
                          <label for="note_assignee_filter">Note Assignee</label>
                          <select class="form-control" name="note_assignee_filter" id="note_assignee_filter" placeholder="None">
                            <option value="0" selected>None</option>
                            <?php 
                              foreach($userdata as $user)
                              {
                              ?>
                                <option value="<?= $user->id; ?>"><?= $user->user_first_name.' '.$user->user_last_name; ?></option>
                              <?php
                              }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3 form-inline" style="padding-left:15px;">
                        <div class="form-group " style="padding-left:15px;">
                          <label for="note_assignee_filter">Sort On</label>
                          <select class="form-control" name="note_sort_filter" id="note_sort_filter">
                            <option value="0" selected>Newest</option>
                            <option value="1">Due Date</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3 form-inline" style="padding-left:15px;">
                        <div class="form-group " style="padding-left:15px;">
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
                <!-- Notes -->
                <?php 
                $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;
                $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();
                ?>                
                <div class="row">
                  <div class="col-md-12" id="notelist_container">
                  <?php if(!empty($combined_notes))
                      { 
                        foreach($combined_notes as $note)
                        {
                          if($note->note_category == 0) 
                          {
                            ?>
                            <div class="well note-element property-note" data-note-id="<?= $note->note_id; ?>" data-note-creator="<?= $note->note_user_id; ?>" data-note-assginee="<?= $note->note_assigned_user ?? ''; ?>" data-note-duedate="<?= ($note->note_due_date == '0000-00-00' || empty($note->note_due_date)) ? '': $note->note_due_date; ?>" data-note-status="<?= (!empty($note->note_assigned_user)) ? $note->note_status : '0'; ?>"> 
                            <?php
                          } elseif($note->note_category == 1)
                          {
                            ?>
                            <div class="well note-element customer-note" data-note-id="<?= $note->note_id; ?>" data-note-creator="<?= $note->note_user_id; ?>" data-note-assginee="<?= $note->note_assigned_user ?? ''; ?>" data-note-duedate="<?= ($note->note_due_date == '0000-00-00' || empty($note->note_due_date)) ? '': $note->note_due_date; ?>" data-note-status="<?= (!empty($note->note_assigned_user)) ? $note->note_status : '0'; ?>"> 
                            <?php
                          } else {
                            ?>
                            <div class="well note-element technician-note" data-note-id="<?= $note->note_id; ?>" data-note-creator="<?= $note->note_user_id; ?>" data-note-assginee="<?= $note->note_assigned_user ?? ''; ?>" data-note-duedate="<?= ($note->note_due_date == '0000-00-00' || empty($note->note_due_date)) ? '': $note->note_due_date; ?>" data-note-status="<?= (!empty($note->note_assigned_user)) ? $note->note_status : '0'; ?>">
                            <?php
                          }
                          ?>
                      <div class="row note-header">
                        <div class="col-md-8 user-info">
                          <div class="user-image">
                            <img src="<?= CLOUDFRONT_URL; ?>uploads/profile_image/<?= (empty($note->user_pic_resized)) ? $note->user_pic : $note->user_pic_resized; ?>" class="img-circle" height="50" width="50" style="object-fit:fill;">
                            <!-- <i class="fa fa-user-circle-o text-primary fa-4x" aria-hidden="true"></i> -->
                          </div>
                          <div class="note-details">
                            <h3 class="text-bold media-heading box-inline text-primary"><?= $note->user_first_name.' '.$note->user_last_name; ?></h3>
                            <p class="text-muted"><?= date_format(date_create($note->note_created_at),"H:i A | F j, Y"); ?></p>
                          </div>                          
                        </div>
                        <div id="note-header-right" class="col-md-4 pull-right text-right">
                          <?php if(!empty($note->note_assigned_user)) : ?>
                            <span id="note-assigned-user-wrap-<?= $note->note_id; ?>"><span>Assigned to&nbsp;</span><span class="text-success text-bold">
                              <?php 
                              for($i=0; $i<count($userdata); $i++)
                              {
                                if($note->note_assigned_user == $userdata[$i]->id)
                                {
                                  echo $userdata[$i]->user_first_name.' '.$userdata[$i]->user_last_name;
                                  break;
                                }
                              }
                              ?>
                              </span></span>
                          <?php endif; ?>
                            <div class="form-group hidden" id="update-assignuser-<?= $note->note_id; ?>">
                              <label class="control-label col-lg-3">Assign User</label>
                              <div class="col-lg-12">
                                <select class="form-control" name="note_assigned_user" id="note_assigned_user_<?= $note->note_id; ?>" data-note-id="<?= $note->note_id; ?>" data-note-userid="<?= $note->note_user_id; ?>" onchange="getNoteAssignUserUpdateVars(this)">
                                  <!-- Add Users available within company with Value = user_id / option shown user_name -->
                                  <option value="" <?= (empty($note->note_assigned_user)) ? 'selected':''; ?>>None</option>
                                  <?php 
                                    foreach($userdata as $user)
                                    {
                                      if($note->note_user_id)
                                      { ?>
                                      <option value="<?= $user->id; ?>" <?= ($user->id == $note->note_assigned_user) ? 'selected':''; ?>><?= $user->user_first_name.' '.$user->user_last_name; ?></option>
                                    <?php }
                                    }
                                  ?>
                                </select>
                                <span style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>
                              </div>
                            </div>
                            <div class="dropdown">
                              <span class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="fa fa-ellipsis-h fa-xl" aria-hidden="true"></i>
                              </span>
                              <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                <li class="dropdown-header text-bold text-uppercase">Actions</li>
                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>" id="note-assign-btn-<?= $note->note_id; ?>"><a href="javascript:showAssignUserSelect(<?= $note->note_id; ?>)"><i class="fa fa-user-circle-o" aria-hidden="true"></i>Assign Specific User</a></li>
                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>"><a href="javascript:showDueDateSelect(<?= $note->note_id; ?>)"><i class="fa fa-calendar" aria-hidden="true"></i>Edit Due Date</a></li>
                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>"><a href="javascript:showNoteTypeSelect(<?= $note->note_id; ?>)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Change Note Type</a></li>
                               <?php
                              if(isset($currentUser->role_id) && $currentUser->role_id < 3)
                              {
                              ?>
                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon"><a href="<?= base_url('admin/markNoteComplete/').$note->note_id; ?>"><i class="fa fa-check-square-o" aria-hidden="true"></i>Mark Complete</a></li>
                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon"><a href="<?= base_url('admin/deleteNote/').$note->note_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete Note</a></li>
                              <?php
                              } else {
                              ?>
                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2 || $currentUser->id !== $note->note_assigned_user && $currentUser->id !== $note->note_user_id) ? ' disabled' : ''; ?>"><a href="<?= base_url('admin/markNoteComplete/').$note->note_id; ?>"><i class="fa fa-check-square-o" aria-hidden="true"></i>Mark Complete</a></li>
                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($currentUser->id !== $note->note_assigned_user && $currentUser->id !== $note->note_user_id) ? ' disabled' : ''; ?>"><a href="<?= base_url('admin/deleteNote/').$note->note_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete Note</a></li>
                            
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
                        <div class="col-md-6" id="note-assigned-type-wrapper-div-<?= $note->note_id; ?>">
                        
                        <?php
                        if($note->note_type == 1)
                        {
                        ?>
                          <span id="note-assigned-type-wrap-<?= $note->note_id; ?>" class="text-bold text-success" style="font-size: 1.2em">Task</span>
                        <?php
                        } else
                        {
                          foreach($note_types as $type) 
                          {
                            if($note->note_type == $type->type_id)
                            {
                              $type_name = $type->type_name;
                            }   
                          } 
                          if(isset($type_name)) {
                          ?>
                          <span id="note-assigned-type-wrap-<?= $note->note_id; ?>" class="text-bold text-success" style="font-size: 1.2em"><?= (isset($type_name)) ? $type_name : ''; ?></span>
						<?php if($type_name == "Service-Specific"){
							  	$duration = "";
								if(isset($note->assigned_service_note_duration) && $note->assigned_service_note_duration == 1){
									$duration = " (Permanent)";
								}elseif(isset($note->assigned_service_note_duration) && $note->assigned_service_note_duration == 2){
									$duration = " (Next Service Only)";
								}
								if(isset($note->job_name) && !empty($note->job_name)){ ?>
									<br><span id="note-assigned-service-wrap-<?= $note->note_id; ?>" class="text-success" style="font-size: 1.2em"><?= $note->job_name ?><?= $duration ?></span>
						<?php 	} 
						  	}
                        } }
                        ?>
                        </div>
                        <?php 
                        if($note->note_category != 2)
                        {
                        ?> 
                        <div class="col-md-3 pull-right text-right">
                          Tech Visible<i class="fa fa-question" aria-hidden="true" style="margin-right: 5px;"></i> <input type="checkbox" onclick="techVisibleSwtch(this,<?= $note->note_id; ?>)"<?= ($note->include_in_tech_view == 1) ? 'checked':''; ?>>
                        </div>
                        <?php
                        }
                        ?>
                      </div>
					  <div class="row">
						   <div class="col-lg-4">
						 		<div class="form-group hidden" id="update-notetype-<?= $note->note_id; ?>">
									<label class="control-label">Note Type</label>
									  <select class="form-control" name="note_edit_type" id="note_edit_type_<?= $note->note_id; ?>" data-note-id="<?= $note->note_id; ?>" data-note-typeid="<?= $note->note_type; ?>" onchange="getNoteTypeUpdateVars(this)">
										<!-- Add types available within company with Value = type_id / option shown type_name -->
										<option value="" selected>None</option>
										<?php 
										$service_specific = 'false';
										foreach($note_types as $type) : 
										  if($type->type_id == $service_specific_note_type_id){
											  $service_specific = 'true';
										  }
										  ?>
										  <option value="<?= $type->type_id; ?>" data-servicespecific=<?=$service_specific?> ><?= $type->type_name; ?></option>
										<?php endforeach; ?>
									  </select>
									<span style="color:red;"><?php echo form_error('note_type'); ?></span>
                            	</div>
                          	</div>
						  	<div class="col-lg-4">
								<div class="form-group hidden" id="update-notetype-services<?= $note->note_id; ?>">
								   <label class="control-label">Assign Services</label>
									<select class="form-control" name="note_edit_assigned_services" id="note_edit_assigned_services<?= $note->note_id; ?>" data-note-id="<?= $note->note_id; ?>">
									   <option value="">None</option>
									   <?php 
										 foreach($servicelist as $service)
										 {
										 ?>
										   <option value="<?= $service->job_id; ?>"><?= $service->job_name; ?></option>
										 <?php
										 }
									   ?>
									 </select>
									 <span style="color:red;"><?php echo form_error('note_edit_assigned_user'); ?></span>
								</div>
							 </div>
							<div class="col-lg-4">
								<div class="form-group hidden" id="update-notetype-duration<?= $note->note_id; ?>">
								   <label class="control-label">Note Duration</label>
									 <select class="form-control" name="edit_assigned_service_note_duration" id="edit_assigned_service_note_duration<?= $note->note_id; ?>" data-note-typeid="<?= $note->note_type; ?>" data-note-id="<?= $note->note_id; ?>" onchange="getNoteTypeUpdateServiceSpecificVars(this)">
									   <option value="">None</option>
									   <option value=1>Permanent</option>
									   <option value=2>Next Service Only</option>
									 </select>
									 <span style="color:red;"><?php echo form_error('edit_assigned_service_note_duration'); ?></span>
								</div>
							</div>
					  </div>			
                      <hr>
                      <div class="row note-footer note-footer-flex">
                        <div class="col-md-7 note-footer-left">
                          <div class="row">
                            <div class="status col-sm-12 col-md-4 col-lg-3 text-bold">
                              <?php
                              if(!empty($note->note_status))
                              {
                              ?>
                              Status: <?= ($note->note_status == 1) ? '<span class="text-warning">OPEN</span>' : '<span class="text-success">CLOSED</span>'; ?>
                              <?php
                              }
                              else 
                              {
                              ?>
                              Status: <span class="text-muted">None</span>
                              <?php 
                              }
                              ?>
                            </div>
                            <?php 
                            if(isset($note->first_name) && isset($note->last_name))
                            { 
                            ?>                   
                              <div class="customer-name col-sm-12 col-md-4 col-lg-3 text-bold">
                                <?= $note->first_name; ?> <?= $note->last_name; ?>
                              </div>
                            <?php
                            }
                            ?>
                            <?php 
                            if(isset($note->property_address) && isset($note->property_city))
                            { 
                            ?>
                              <div class="customer-address col-sm-12 col-md-8 col-lg-6 text-bold text-muted">
                                <i class="fa fa-map-marker" aria-hidden="true"></i> <?= $note->property_address; ?>, <?= $note->property_city; ?>
                              </div>
                            <?php
                            }
                            ?>
                          </div>
                        </div>
                        <div class="col-md-4 note-footer-right pull-right">
                          <div class="row">
                            <div class="col-md-8 note-due-date text-warning text-uppercase text-bold text-center">
                                <i class="fa fa-flag" aria-hidden="true"></i> DUE: <span id="note-duedate-<?= $note->note_id; ?>"><?= (!empty($note->note_due_date) && $note->note_due_date != '0000-00-00') ? $note->note_due_date : 'None Set'; ?></span><input id="note_due_date_<?= $note->note_id; ?>" type="text" name="note_due_date" class="form-control pickaalldate hidden" placeholder="YYYY-MM-DD" data-noteid="<?= $note->note_id; ?>" onchange="updateNoteDueDate(this)">
                            </div>
                            <div class="col-md-3 pull-right">
                              <div class="row">
                                <div class="col-md-6 note-comments">
                                  <i class="fa fa-comment-o" aria-hidden="true text-muted" data-toggle="collapse" data-target="#note-comments-<?= $note->note_id; ?>" aria-expanded="false" aria-controls="note-comments-<?= $note->note_id; ?>"></i> <span id="comment-count-value-<?= $note->note_id; ?>"><?= count($note->comments); ?></span>
                                </div>
                                <div class="col-md-6 note-attachments">
                                  <i class="fa fa-paperclip text-muted" aria-hidden="true" data-toggle="collapse" data-target="#note-files-<?= $note->note_id; ?>" aria-expanded="false" aria-controls="note-files-<?= $note->note_id; ?>"></i> <?= count($note->files); ?>
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
                                  <small class="text-muted"><?= $comment->comment_created_at ?></small> <strong><?= $comment->user_first_name.' '.$comment->user_last_name; ?>: </strong><?= $comment->comment_body; ?>
                                </li>
                              <?php } ?>
                              <li class="list-group-item comment-list-item">
                                <form action="<?= base_url('admin/addNoteComment') ?>" method="post" name="add-note-comment-form" enctype="multipart/form-data" id="add-note-comment-form-<?= $note->note_id; ?>" onsubmit="addCommentAjax('<?= $note->note_id; ?>')">
                                  <input type="hidden" value="<?= $this->session->userdata('id'); ?>" name="comment-userid">
                                  <input type="hidden" value="<?= $note->note_id; ?>" name="comment-noteid">
                                  <div class="input-group">
                                    <input class="form-control" name="add-comment-input" id="add-comment-input" placeholder="Add Comment">
                                    <div class="input-group-btn">
                                      <!-- Buttons -->
                                      <button type="submit" class="btn btn-primary pull-right">Post Comment</button>
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
                                <strong>Attach Additional Files</strong>  <i class="icon-cloud-upload btn-ico" aria-hidden="true" data-toggle="collapse" data-target="#note-fileupload-<?= $note->note_id; ?>" aria-expanded="false" aria-controls="note-fileupload-<?= $note->note_id; ?>" style="font-size: 2em; padding-left: 10px;"></i>
                              </div>
                            </div>
                            <div class="col-md-4 col-lg-3 pull-right collapse" id="note-fileupload-<?= $note->note_id; ?>">
                              <form action="<?=  base_url('admin/addToNoteFiles'); ?>" method="post" enctype="multipart/form-data" onsubmit="formFileSizeValidate(this)">
                                <input type="hidden" value="<?= $this->session->userdata('id'); ?>" name="user_id">
                                <input type="hidden" value="<?= $note->note_id; ?>" name="note_id">
                                <div class="row row-extra-space">
                                  <div class="col-xs-12">
                                    <div class="form-group">
                                      <label class="control-label col-lg-4 text-right">Attach Documents</label>
                                      <div class="col-lg-8 text-left">
                                        <input id="files" type="file" name="files[]" class="form-control-file" multiple onchange="fileValidationCheck(this)">
                                        <span style="color:red;"><?php echo form_error('files'); ?></span>
                                      </div>
                                    </div>
                                  </div>
                                  <button type="submit" class="btn btn-primary pull-right">Save</button>
                                </div>                                
                              </form>
                            </div>
                            <div class="row">
                              <?php foreach ($note->files as $file) 
                              { 
                                $ext = pathinfo( CLOUDFRONT_URL.$file->file_key, PATHINFO_EXTENSION);
                                if($ext == 'pdf') 
                                { ?>
                                <div class="col-xs-4 col-md-2 text-center">
                                  <label><?= $file->file_name; ?></label><br>
                                  <a href="<?= CLOUDFRONT_URL.$file->file_key; ?>" target="_blank">
                                    <i class="fa fa-file-code-o file-attach-icon" aria-hidden="true"></i>
                                  </a>
                                </div>
                                <?php } else { ?>
                                <div class="col-xs-4 col-md-2 text-center">
                                  <label><?= $file->file_name; ?></label>
                                  <img src="<?= CLOUDFRONT_URL.$file->file_key; ?>" alt="<?= $file->file_name; ?>" class="img-responsive thumbnail files-thumbnail" onclick="displayFileModal(this)">
                                </div>
                              <?php } 
                              } ?>
                            </div>                            
                          </div>
                        </div>
                      </div>                         
                    </div>
                    <?php }
                      } else 
                      { ?>
                    <div class="well property-note">
                      <p>No Notes to Display Yet...</p>
                    </div>
                    <?php } ?>
                  </div>
                </div>                

                </div>

              </div>
<!-- Files Modal -->
  <div id="file-display-modal" class="modal-files">
    <span class="close" id="close-file-display">&times;</span>
    <img class="modal-content" id="modal-file-image">
    <div id="caption"></div>
  </div>
               
<script>
$(function() {
  // Array.from($('.nav-tabs')[0].children).forEach((tabBtn) => {
  //   $(tabBtn).on('click', function(e) {
  //     if($(tabBtn).hasClass('lifive')) {
  //       $("#addNoteBtn").show(100);
  //     } else {
  //       $("#addNoteBtn").hide(100);
  //     }
  //   });
  // });
  $('#note-filter-all').click(function(e) {
    $('#note_creator_filter').val(0);
    $('#note_assignee_filter').val(0);
    Array.from($('.properties-tab-btn')).forEach((btn) => $(btn).removeClass('properties-tab-active'));
    $(this).addClass('properties-tab-active');
    Array.from($('.note-element')).forEach((note) => $(note).show());
  });
  $('#note-filter-customer').click(function(e) {
    $('#note_creator_filter').val(0);
    $('#note_assignee_filter').val(0);
    Array.from($('.properties-tab-btn')).forEach((btn) => $(btn).removeClass('properties-tab-active'));
    $(this).addClass('properties-tab-active');
    Array.from($('.note-element')).forEach((note) => {
      if($(note).hasClass('customer-note')) {
        $(note).show();
      } else {
        $(note).hide();
      }
    });
  });
  $('#note-filter-property').click(function(e) {
    $('#note_creator_filter').val(0);
    $('#note_assignee_filter').val(0);
    Array.from($('.properties-tab-btn')).forEach((btn) => $(btn).removeClass('properties-tab-active'));
    $(this).addClass('properties-tab-active');
    Array.from($('.note-element')).forEach((note) => {
      if($(note).hasClass('property-note')) {
        $(note).show();
      } else {
        $(note).hide();
      }
    });
  });
  $('#note-filter-technician').click(function(e) {
    $('#note_creator_filter').val(0);
    $('#note_assignee_filter').val(0);
    Array.from($('.properties-tab-btn')).forEach((btn) => $(btn).removeClass('properties-tab-active'));
    $(this).addClass('properties-tab-active');
    Array.from($('.note-element')).forEach((note) => {
      if($(note).hasClass('technician-note')) {
        $(note).show();
      } else if($(note).hasClass('customer-note') || $(note).hasClass('property-note')) {
        $(note).hide();
      }
    });
  });    
  $('#note-form-wrap').on('show.bs.collapse', () => {
    $('#addNoteBtnIco').removeClass('icon-plus22')
      .addClass('fa fa-minus');
  });        
  $('#note-form-wrap').on('hide.bs.collapse', () => {
    $('#addNoteBtnIco').removeClass('fa fa-minus')
      .addClass('icon-plus22');
  });
  $('#note_sort_filter').on('change', function(e) {
    let notes = $('#notelist_container').children();
    console.log(e.target.value);
    console.log(notes);
    if(e.target.value == 0) {
      notes.detach().sort((a,b) => {
        return $(a).data('noteId') > $(b).data('noteId') ?  -1 : 1;
      });
    } else if(e.target.value == 1) {
      notes.detach().sort((a,b) => {
        if($(a).data('noteDuedate') == 0 && $(b).data('noteDuedate') != 0) {
          return 1;
        } else if($(a).data('noteDuedate') != 0 && $(b).data('noteDuedate') == 0) {
          return -1;          
        } else if($(a).data('noteDuedate') === $(b).data('noteDuedate')) {
          return $(a).data('noteId') > $(b).data('noteId') ?  -1 : 1;
        } else {
          return $(a).data('noteDuedate') > $(b).data('noteDuedate') ?  1 : -1;
        }
      });
    }
    // console.log(notes);
    $.each(notes, function(index, note) {
      
      $('#notelist_container').append(note);

    });
  });
});
// Note Files
function formFileSizeValidate(form) {
    let fileEl = $(form).find('input[type="file"]').get(0);
    console.log(fileEl);
    let totalMbSize = 0;
    if(fileEl.files.length > 0) {
        for (let i = 0; i <= fileEl.files.length - 1; i++) {
            let mbSize = bytesToMb(fileEl.files[i].size);
            console.log(mbSize);
            totalMbSize += mbSize;
        }
        console.log(totalMbSize);
        if(totalMbSize > 5) {
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
        if(totalMbSize > 5) {
            $(el).next().text('file(s) exceed the max 5MB limit');
        } else {
            $(el).next().text('');
        }
    } else {
        $(el).next().text('');
    }
}
function bytesToMb(bytes) {
    if(bytes === 0) return 0;
    let mb = (bytes / (1024*1024));
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
  span.onclick = function() { 
    modal.style.display = "none";
  }    
}
</script>
<script>
  function getNoteAssignUserUpdateVars(el) {
    let userId = $(el).val();
    let noteId = $(el).data('note-id');
    let noteOwnerId = $(el).data('note-userid');
    let userName = $(el.options[el.options.selectedIndex]).text();
	updateAssignUser(userId,noteId,userName);   
  }
  function getNoteTypeUpdateVars(el) {
    let typeId = $(el).val();
    let noteId = $(el).data('note-id');
    let currentTypeId = $(el).data('note-typeid');
    let typeName = $(el.options[el.options.selectedIndex]).text();
    let idMatch = (typeId == currentTypeId);
	let serviceSpec = $(el.options[el.options.selectedIndex]).data('servicespecific'); 
	if(serviceSpec == true){
		$('#update-notetype-services'+noteId).removeClass('hidden');
		$('#update-notetype-duration'+noteId).removeClass('hidden');
		$('#note_edit_assigned_services'+noteId).val('');
		$('#edit_assigned_service_note_duration'+noteId).val('');
	}else{
		$('#update-notetype-services'+noteId).addClass('hidden');
		$('#update-notetype-duration'+noteId).addClass('hidden');
		$('#note_edit_assigned_services'+noteId).val('');
		$('#edit_assigned_service_note_duration'+noteId).val('');
		updateAssignType(typeId,noteId,typeName,idMatch);
	} 
  }
 function getNoteTypeUpdateServiceSpecificVars(el){
	let noteId = $(el).data('note-id'); 
	let typeId = $('#note_edit_type_'+noteId).val();  
	var assignedService = $('#note_edit_assigned_services'+noteId).val();
	let serviceName = $('#note_edit_assigned_services'+noteId+' option:selected').text();
	var noteDuration = $('#edit_assigned_service_note_duration'+noteId).val();
	//console.log("Note ID: "+noteId+", Type ID: "+typeId+", Selected Service: "+assignedService+", Selected Service Name: "+serviceName+", Duration: "+noteDuration); 
	if(assignedService != "" && noteDuration != ""){
		updateAssignTypeServiceSpecific(typeId,noteId,assignedService,serviceName,noteDuration);
	}
 }
  function showAssignUserSelect(noteId) {
    $(`#update-assignuser-${noteId}`).removeClass('hidden');
  }
  function showNoteTypeSelect(noteId) {
    $(`#update-notetype-${noteId}`).removeClass('hidden');
  }    
  function updateAssignUser(userId, noteId, userName) {
    $.post("<?= base_url('admin/updateAssignUser'); ?>", {'noteId': noteId, 'userId': userId}, function(result){
      $(`#note-assigned-user-wrap-${noteId}`).remove();
      if(userId != '') {
        $(`<span id="note-assigned-user-wrap-${noteId}"><span>Assigned to&nbsp;</span><span class="text-success text-bold"> ${userName}</span></span>`).insertBefore(`#update-assignuser-${noteId}`);
      }
      $(`#update-assignuser-${noteId}`).addClass('hidden');
    });
  }
  function updateAssignType(typeId, noteId, typeName, match) {
    $.post("<?= base_url('admin/updateAssignType'); ?>", {'noteId': noteId, 'typeId': typeId}, function(result){
      $(`#note-assigned-type-wrap-${noteId}`).remove();
	  $(`#note-assigned-service-wrap-${noteId}`).remove();	
	  $('#note_edit_type_'+noteId).data('note-typeid',typeId);	
      if(!match) {
       	$(`#note-assigned-type-wrapper-div-${noteId}`).prepend(`<span id="note-assigned-type-wrap-${noteId}" class="text-bold text-success" style="font-size: 1.2em">${typeName}</span>`);
      }
      $(`#update-notetype-${noteId}`).addClass('hidden');
      
    });
  } 
  function updateAssignTypeServiceSpecific(typeId, noteId, assignedService, serviceName, noteDuration) {
	if(noteDuration == 1){
	  var durationName = "Permanent"; 
	}else{
	  var durationName = "Next Service Only";
	}
    $.post("<?= base_url('admin/updateAssignTypeForServiceSpecific'); ?>", {'noteId': noteId, 'typeId': typeId, 'assignedService':assignedService, 'noteDuration':noteDuration}, function(result){
      $(`#note-assigned-type-wrap-${noteId}`).remove();
	  $(`#note-assigned-service-wrap-${noteId}`).remove();
	  $('#note_edit_type_'+noteId).data('note-typeid',typeId);	
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
    if(dueDate != '') {
      $.post("<?= base_url('admin/updateNoteDueDate'); ?>", {'noteId': noteId, 'dueDate': dueDate}, function(result){
        $(`#note-duedate-${noteId}`).text(dueDate);
      });      
    }
    $(el).val('').addClass('hidden');
  }
  function addCommentAjax(noteId) {
    event.preventDefault();
    let comtVal = $(`#add-note-comment-form-${noteId} input[name="add-comment-input"]`).val().trim();
    if( comtVal != '') {
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
        success: function(data) {
          console.log("Success : ", data);
          let result = JSON.parse(data);
          console.log("Success : ", result);
          let comment = `<li class="list-group-item comment-list-item">
                            <small class="text-muted">${result.timestamp}</small> <strong>${result.user_first_name} ${result.user_last_name}: </strong>${result.comment_body}
                          </li>`;
          $(form).parent().before(comment);
          $(`#comment-count-value-${result.note_id}`).text(`${result.comment_count}`);
        },
        error: function(e) {
          console.log("ERROR : ", e);
        }
      });
    }
  }
  $(document).ready(function(){
    $('#note_creator_filter').on('change', function(e) {
      filterNotesByCreatorAndAssignee();
    });
    $('#note_assignee_filter').on('change', function(e) {
      filterNotesByCreatorAndAssignee();
    });
    $('#note_status_filter').on('change', function(e) {
      filterNotesByCreatorAndAssignee();
    });      
  });
  function filterNotesByCreatorAndAssignee() {
    let creatorIdFilter = $('#note_creator_filter').val();
    let assigneeIdFilter = $('#note_assignee_filter').val();
    let noteCategoryFilter = $('.properties-tab-active')[0].id.split('-')[2];
    let noteStatusFilter = $('#note_status_filter').val();
    
    Array.from($('.note-element')).forEach((note) => {
      if(creatorIdFilter == '0' && assigneeIdFilter == '0' && noteCategoryFilter == 'all' && noteStatusFilter == '0') {
        $(note).show();
      } else if(noteCategoryFilter != 'all') {
        if($(note).hasClass(`${noteCategoryFilter}-note`)) {
          if(creatorIdFilter == '0' && assigneeIdFilter == '0') {
            $(note).show()
          } else if(creatorIdFilter != '0' && assigneeIdFilter == '0') {
            if($(note).attr('data-note-creator') == creatorIdFilter) {
              $(note).show();
            } else {
              $(note).hide();
            }
          } else if(creatorIdFilter == '0' && assigneeIdFilter != '0') {
            if($(note).attr('data-note-assginee') == assigneeIdFilter) {
              $(note).show();
            } else {
              $(note).hide();
            }            
          } else if(creatorIdFilter != '0' && assigneeIdFilter != '0') {
            if($(note).attr('data-note-creator') == creatorIdFilter && $(note).attr('data-note-assginee') == assigneeIdFilter) {
              $(note).show();
            } else {
              $(note).hide();
            }
          }
        } else {
          $(note).hide();
        }
      } else if(noteCategoryFilter == 'all') {
        if(creatorIdFilter == '0' && assigneeIdFilter == '0') {
          $(note).show()
        } else if(creatorIdFilter != '0' && assigneeIdFilter == '0') {
          if($(note).attr('data-note-creator') == creatorIdFilter) {
            $(note).show();
          } else {
            $(note).hide();
          }
        } else if(creatorIdFilter == '0' && assigneeIdFilter != '0') {
          if($(note).attr('data-note-assginee') == assigneeIdFilter) {
            $(note).show();
          } else {
            $(note).hide();
          }            
        } else if(creatorIdFilter != '0' && assigneeIdFilter != '0') {
          if($(note).attr('data-note-creator') == creatorIdFilter && $(note).attr('data-note-assginee') == assigneeIdFilter) {
            $(note).show();
          } else {
            $(note).hide();
          }
        }
      }
    });
    Array.from($('.note-element')).forEach((note) => {
      if(noteStatusFilter != '0') {
        if($(note).attr('data-note-status') != noteStatusFilter) {
          $(note).hide();
        }
      }
    });
  }
  function techVisibleSwtch(el,id) {
    let num = (el.checked) ? 1 : 0;
      $.post("<?= base_url('admin/updateNoteTechView'); ?>", {'noteId': id, 'tech_view': num}, function(result){
        console.log(result);
      });      
  }  
</script>
<!-- Debug Var Dumps -->
<script>
var currentUser = <?= print_r(json_encode($currentUser), TRUE); ?>;
var notes = <?= print_r(json_encode($combined_notes), TRUE); ?>;

</script>