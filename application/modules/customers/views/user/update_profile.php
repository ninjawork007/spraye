        <!-- Content area -->
        <script src="<?= base_url('assets/admin/assets/js/imageresize/imageresize.js') ?>"></script>
        <script>
        $(document).ready(function(){
          $("#user_pic").change(function(){
            resizeImage({file:this.files[0],maxSize:70});
          });
        });
        </script>
        <div class="content">

          <!-- Form horizontal -->
          <div class="panel panel-flat">
         <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin/users') ?>"  id="save" class="btn btn-success" ><i class="icon-arrow-left7"></i>  Back to All Users</a>
                        </div>
                   </h5>
              </div>

            <br>
            <div class="panel-body">              
				<b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
              <form class="form-horizontal" action="<?= base_url('admin/users/updateProfileData') ?>" method="post" name="adduser" enctype="multipart/form-data" style="min-height: 396px;" >
                <fieldset class="content-group"> 
<?php 
if (!empty($user_details->user_pic)) { ?>
  
                <div class="row">
                  <div class="col-md-12">
                  <center>
                         <img style="width: 60px;height:60px;border-radius: 50%;"  src="<?= CLOUDFRONT_URL.'uploads/profile_image/'.$user_details->user_pic  ?>">
                    
                  </center>  
                  </div>

                </div>
<br>
<?php } ?>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <center>
                        <input type="file" id="user_pic" name="user_pic" class="myfile" >
                        <input type="hidden"  id="resized_image" name="resized_image"/>
                      </center>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Name</label>
                        <div class="col-lg-9">
                          <div class="row">
                            <div class="col-md-6">
                              
                                <input type="text" class="form-control" name="user_first_name" placeholder="First Name" value="<?= $user_details->user_first_name ?>" >
                            
                            </div>
                            <div class="col-md-6">

                               <input type="text" class="form-control" name="user_last_name" placeholder="Last Name" value="<?= $user_details->user_last_name ?>">
                              
                            </div>
                          </div>


                        </div>
                    </div>
                 </div> 
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Applicator Number</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" name="applicator_number" placeholder="Applicator Number" value="<?= $user_details->applicator_number ?>">
                      </div>
                    </div>
                  </div>
                  
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Email</label>
                        <div class="col-lg-9">
                           <input type="text" class="form-control" name="email" placeholder="Email" value="<?= $user_details->email ?>">
                        </div>
                    </div>
                 </div> 
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Phone Number</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" name="phone" placeholder="Phone" value="<?= $user_details->phone ?>">
                          <br>
                             <span>Please do not use dashes</span>
                      </div>
                    </div>
                  </div>
                  
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Password</label>
                        <div class="col-lg-9">
                           <input type="password" id="password" class="form-control" name="password" placeholder="Password" value="<?= $user_details->password ?>">
                        </div>
                    </div>
                 </div> 
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Confirm Password</label>
                      <div class="col-lg-9">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" value="<?= $user_details->password ?>">
                      </div>
                    </div>
                  </div>                  
                </div>
                
                <input type="hidden" name="old_password" value="<?= $user_details->password ?>">               
                

                <div class="text-center">
                  <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>

                </fieldset>

              </form>
            </div>
          </div>
          <!-- /form horizontal -->

        </div>
        <!-- /content area -->