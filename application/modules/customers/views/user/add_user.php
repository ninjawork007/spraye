


        <!-- Content area -->
        <div class="content form-pg">

          <!-- Form horizontal -->
          <div class="panel panel-flat">
            <!-- <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin/users') ?>"  id="save" class="btn btn-success" > <i class=" icon-arrow-left7"> </i> Back to All Users</a>
                        </div>
                   </h5>
            </div> -->

            <br>
            <div class="panel-body">              
            <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
              <form class="form-horizontal" action="<?= base_url('customers/users/addUserData') ?>" method="post" name="addcustomer" enctype="multipart/form-data" style="min-height: 396px;" >
                <fieldset class="content-group">                  
                 
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">First Name</label>
                        <div class="col-lg-9">
                           <input type="text" class="form-control" name="first_name" placeholder="First Name">
                           
                        </div>
                    </div>
                  </div> 
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Last Name</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                      </div>
                    </div>
                  </div>
                  
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group mg-bt">
                        <label class="control-label col-lg-3">Email</label>
                        <div class="col-lg-9">
                           <input type="text" class="form-control" name="email" placeholder="Email">
                        </div>
                    </div>
                 </div> 
                  
                  <div class="col-md-6">
                    <div class="form-group mg-bt">
                      <label class="control-label col-lg-3">Phone Number</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" name="phone" placeholder="Phone">
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
                           <input type="password" id="password" class="form-control" name="password" placeholder="Password">
                        </div>
                    </div>
                 </div> 
                  
                  <div class="col-md-6">
                    <div class="form-group" style="margin-bottom: 7px;">
                      <label class="control-label col-lg-3">Confirm Password</label>
                      <div class="col-lg-9">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                      </div>
                    </div>
                  </div>
                  
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Role</label>
                           <div class="col-lg-9">
                              <select class="form-control" name="role_id">
                                <option value="">Select Role</option>
                                <option value="2">Account Owner</option>
                                <option value="3">Account Admin</option>
                                <!--<option value="4">Technician</option> -->
                              </select>
                          </div>
                      </div>
                 </div> 

                 <!-- <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Applicator Number</label>
                           <div class="col-lg-9">
                               <input type="text" class="form-control" name="applicator_number" placeholder="Applicator Number">
                          </div>
                      </div>
                 </div> -->
                </div>

                <div class="text-right">
                  <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>

                </fieldset>

              </form>
            </div>
          </div>
          <!-- /form horizontal -->

        </div>
        <!-- /content area -->
