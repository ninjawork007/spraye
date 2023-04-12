<!-- Content area -->
  <div class="content form-pg">

          <!-- Form horizontal -->
          <div class="panel panel-flat">
            <div class="panel-heading">
              <h5 class="panel-title">
                <div class="form-group">
                  <a href="<?= base_url('customers/dashboard/').$_SESSION['customer_id'] ?>"  id="save" class="btn btn-primary" > <i class=" icon-arrow-left7"> </i> Back to Dashboard</a>
                </div>
              </h5>
            </div>

            <br>
            <div class="panel-body">              
                <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
              <form class="form-horizontal" action="<?= base_url('customers/updateAccountData ') ?>" method="post" name="addcustomer" enctype="multipart/form-data" style="min-height: 396px;" >
                <fieldset class="content-group">                  
                 
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">First Name</label>
                      <div class="col-lg-9">
                          <!-- <input type="text" class="form-control" name="first_name" placeholder="First Name"> -->
                          <input type="text" class="form-control" name="first_name" value="<?php echo set_value('first_name')?set_value('first_name'):$_SESSION['first_name']?>" placeholder="First Name" readonly>
                                  <span style="color:red;"><?php echo form_error('first_name'); ?></span>
                      </div>
                    </div>
                  </div> 
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Last Name</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" name="last_name" value="<?php echo set_value('last_name')?set_value('last_name'):$_SESSION['last_name']?>" placeholder="Last Name" readonly>
                        <span style="color:red;"><?php echo form_error('last_name'); ?></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group mg-bt">
                        <label class="control-label col-lg-3">Email</label>
                      <div class="col-lg-9">
                          <!-- <input type="text" class="form-control" name="email" placeholder="Email"> -->
                          <input type="text" class="form-control" name="email" value="<?php echo set_value('email')?set_value('email'):$_SESSION['email']?>" placeholder="Email" readonly="readonly">
                          <span style="color:red;"><?php echo form_error('email'); ?></span>
                      </div>
                    </div>
                 </div> 
                  <div class="col-md-6">
                    <div class="form-group mg-bt">
                      <label class="control-label col-lg-3">Phone Number</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" name="phone" value="<?php echo set_value('phone')?set_value('phone'):$_SESSION['phone']?>" placeholder="Phone" readonly>
                        <!-- <span>Please do not use dashes</span> -->
                        <span style="color:red;"><?php echo form_error('phone'); ?></span>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">New Password</label>
                        <div class="col-lg-9">
                           <input type="password" id="password" class="form-control" name="password" placeholder="Password">
                           <span style="color:red;"><?php echo form_error('password'); ?></span>
                        </div>
                    </div>
                 </div> 
                  
                  <div class="col-md-6">
                    <div class="form-group" style="margin-bottom: 7px;">
                      <label class="control-label col-lg-3">Confirm Password</label>
                      <div class="col-lg-9">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                        <span style="color:red;"><?php echo form_error('confirm_password'); ?></span>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="text-right">
                  <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>
                </div>


                </fieldset>

              </form>
            </div>
          </div>
          <!-- /form horizontal -->
</div>
</div>
  
        <!-- /content area -->
