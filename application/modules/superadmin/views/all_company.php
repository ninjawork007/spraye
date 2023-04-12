<style>
.panel_row .panel {
    margin-bottom: 20px !important;
    border-radius: 5px !important;
    box-shadow: 1px 1px 1px 1px #cccccc8a;
}
.panel_row .text-size-small {
    font-size: 17px;
}
.table.dataTable >thead>tr>th {
    border-bottom: 1px solid #6eb1fd;
    font-size: 14px;
}
.table-spraye{
    border: 1px solid #6eb1fd;
    border-radius: 4px;
}
.navbar-collapse .navbar-text .icon-plus3:before, .navbar-collapse .navbar-text .icon-upload4:before, .navbar-collapse .navbar-text .icon-file-text2:before, .navbar-collapse .navbar-text .icon-trash:before {
    font-family: 'icomoon';
    padding-right: 4px;
    font-weight: 100;
}
</style>
<div class="content">
    <div class="panel panel-flat">
       <!--  <div class="panel-heading">
            <h5 class="panel-title">Users list</h5>
           
        </div> -->
         
        <div class="panel panel-flat">
         <!--     <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('superadmin/addCompany') ?>"  id="save" class="btn btn-success" > Add New Company</a>
                        </div>
                   </h5>
              </div> -->
      
        </div>
        
        <div class="panel-body">
                  <div class="row">
          <div class="col-md-12">
               <div class="row panel_row">
                  <div class="col-lg-3 col-md-3">
                     <!-- Members online -->
                   
                        <div class="panel">
                           <div class="panel-body">
                              <h5 class="no-margin">Total monthly revenue</h5>
                              <div class="text-muted text-size-small text-success text-center">$<?=  number_format( getMonthlyRevenue(),2); ?></div>
                           </div>
                        </div>
                  
                     <!-- /members online -->
                  </div>
                  <div class="col-lg-3 col-md-3">
                     <!-- Current server load -->
                   
                        <div class="panel">
                           <div class="panel-body">
                              <h5 class="no-margin">Total customers</h5>
                              <div class="text-muted text-size-small text-center" style="color: #2196F3!important"><?= getAllCompanyCount();  ?></div>
                           </div>
                        </div>
                   
                     <!-- /current server load -->
                  </div>
                  <div class="col-lg-3 col-md-3">
                     <!-- Today's revenue -->
                  
                        <div class="panel">
                           <div class="panel-body">
                              <h5 class="no-margin">Monthly subscriptions</h5>
                              <div class="text-muted text-size-small  text-center" style="color: #2196F3!important"><?= getSubScriptionCount(array('subscription_unique_id'=>monthly_sub_id));  ?></div>
                           </div>
                        </div>
                  
                     <!-- /today's revenue -->
                  </div>
                   <div class="col-lg-3 col-md-3">
                     <!-- Today's revenue -->
                    
                        <div class="panel">
                           <div class="panel-body">
                              <h5 class="no-margin">Annual subscriptions</h5>
                              <div class="text-muted text-size-small  text-center" style="color: #2196F3!important"><?= getSubScriptionCount(array('subscription_unique_id'=>yearly_sub_id));  ?></div>
                           </div>
                        </div>
                    
                     <!-- /today's revenue -->
                  </div>
               </div>
            </div>
        </div>
<b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
         


           <div  class="table-responsive">
             <table  class="table datatable-basic table-spraye">    
                  <thead>  
                      <tr>
                          <th>Name</th>
                          <th>Billing Address</th>
                          <th>Company Email</th>                          
                          <th># Of Active Users</th>
                          <th># Of Properties</th>
                          <th>Last Login Date</th>
                          <th>Action</th>
                      </tr>  
                  </thead>
                  <tbody>


                  <?php if (!empty($company)) {  foreach ($company as $companyValue) { 
                    /* properties */
                    $propertiescount = 0; 
                    $propertiestotal = array();

                    if (!empty($properties)) {  foreach ($properties as $propertiesValue) { 

                        
                        if($propertiesValue->company_id == $companyValue->company_id){
                            if($propertiesValue->property_status != 0){
                                array_push($propertiestotal, $propertiesValue->property_id);
                            }
                        }                       
                      
                            }
                            $propertiescount = count($propertiestotal);
                    }


                    /* active users */
                    $usercount = 0; 
                    $userstotal = array();
                    
                    if (!empty($users)) {  foreach ($users as $usersValue) { 

                        
                        if($usersValue->company_id == $companyValue->company_id){
                            array_push($userstotal, $usersValue->user_id);
                        }                       

                         
                            }
                            $usercount = count($userstotal);
                        }




                        /* last login */
                    
                    $lastLogin = NULL;
                    
                    if (!empty($logins)) {  foreach ($logins as $loginsValue) { 

                        
                            if($loginsValue->company_id == $companyValue->company_id){
                                if($lastLogin == NULL){
                                    $lastLogin = $loginsValue->last_login_date;
                                }else if($lastLogin < $loginsValue->last_login_date){
                                    $lastLogin = $loginsValue->last_login_date;
                                }
                                
                            }     
                            
                            }
                        }



                         ?>
                      <tr>
                          
                          <td><a href="<?= base_url('superadmin/editComapny/').$companyValue->company_id ?>"><?= $companyValue->company_name ?></td>
                          <td><?= $companyValue->company_address ?></td>
                          <td><?= $companyValue->company_email ?></td>
                          <td><?= $usercount ?></td>
                          <td><?= $propertiescount ?></td>
                          <td><?= $lastLogin ?></td>
                                                         
                         
                          <td>

                             <ul style="list-style-type: none; padding-left: 0px;">

                                <li style="display: inline; padding-right: 10px;" title="Edit company">
                                  <a href="<?= base_url('superadmin/editComapny/').$companyValue->company_id ?>" class="button-next"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                               </li>
                                 <li style="display: inline; padding-right: 10px;" title="Delete company">
                                     <a href="<?= base_url('superadmin/deleteCompany/').$companyValue->company_id ?>" class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                                 </li>
                                 <?php if (!$companyValue->deleted_at){ ?>
                                   <li style="display: inline; padding-right: 10px;" title="De-activate company">
                                     <a href="<?= base_url('superadmin/inactiveCompany/').$companyValue->company_id ?>" class="confirm_inactive button-next"><i class="icon-pause   position-center" style="color: #9a9797;"></i></a>
                                   </li>
                                <?php }
                                else { ?>
                                     <li style="display: inline; padding-right: 10px;" title="Activate company">
                                     <a href="<?= base_url('superadmin/recoverDeletedCompany/').$companyValue->company_id ?>" class="confirm_recover button-next"><i class="icon-play3   position-center" style="color: #9a9797;"></i></a>
                                    </li>
                                <?php } ?>

                            </ul>
                        </td>

                      </tr>
                  
                  <?php } }  ?>

                  </tbody>
              </table>
           </div>    
        </div>
        
        
    </div>
</div>



<script src="<?= base_url() ?>assets/popup/js/sweetalert2.all.js"></script>
<script type="text/javascript">

    $('.confirm_delete').click(function(e){
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
        })


    });

    $('.confirm_inactive').click(function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        swal({
        title: 'Are you sure',
        text: "You want to de-activate this company?",
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
        })
    });


    $('.confirm_recover').click(function(e){
      e.preventDefault();
      var url = $(this).attr('href');
      swal({
          title: 'Are you sure',
          text: "You want to activate this company?",
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
      })


    });
</script>   

