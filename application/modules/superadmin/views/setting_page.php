   <style type="text/css">
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
</style>


        <!-- Content area -->
    <div class="content">

          <!-- Form horizontal -->
        <div class="panel panel-flat">
           <div class="panel-heading">
                     <h5 class="panel-title">
                          <div class="form-group">
                            <a href="<?= base_url('superadmin') ?>"  id="save" class="btn btn-success" ><i class="icon-arrow-left7"></i> Go Back</a>
                          </div>
                     </h5>
            </div>
<div id="loading" > 
    <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
</div>

            <br>
            <div class="panel-body">              
		      	
            	<b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
             
         

<form class="form-horizontal" action="<?= base_url('superadmin/updateSmtp') ?>" method="post" name="smtpcredential" enctype="multipart/form-data">
                <fieldset class="content-group">
                    <legend class="text-bold">Default SMTP Details <a href="#" data-toggle="modal" data-target="#modal_smtp_info"> <i class=" icon-info22 tooltip-icon"></i></a></legend>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3">SMTP Host</label>
                            <div class="col-lg-9">


                                 <?php 

                              $smtp_host_ex =  explode("://", $superadmin_detalis->smtp_host);

                               ?>

                              <div class="row" >
                                
                                <div class="col-lg-3" >
                                  <select class="form-control"  name="smtp_host_type">
                                    <option value="tls://" <?php if($smtp_host_ex[0].'://'=="tls://") { ?> selected <?php } ?>  >tls://</option>
                                    <option value="ssl://" <?php if($smtp_host_ex[0].'://'=="ssl://") { ?> selected <?php } ?>  >ssl://</option>
                                  </select>
                                  
                                </div>

                                <div class="col-lg-9" >
                                 <input type="text" class="form-control" name="smtp_host" placeholder="Host" value="<?= array_key_exists(1, $smtp_host_ex) ? $smtp_host_ex[1] : '' ?>" >
                                  
                                </div>

                              </div>                               
                            </div>
                        </div>
                      </div> 
                      
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">SMTP Port</label>
                          <div class="col-lg-9">
                            <input type="text" class="form-control" name="smtp_port" placeholder="Port" value="<?= $superadmin_detalis->smtp_port ?>">
                          </div>
                        </div>
                      </div>
                      
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3">SMTP Username</label>
                            <div class="col-lg-9">
                               <input type="text" class="form-control" name="smtp_username" placeholder="SMTP Username" value="<?= $superadmin_detalis->smtp_username ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label col-lg-3">SMTP Password</label>
                          <div class="col-lg-9">
                            <input type="password" class="form-control" name="smtp_password" placeholder="SMTP Password" value="<?= $superadmin_detalis->smtp_password ?>">
                          </div>
                        </div>
                      </div>
                    </div>                   
                </fieldset>             
   
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>

              </form>
            </div>
        </div>
          <!-- /form horizontal -->


        
    </div>


<div id="modal_smtp_info" class="modal fade" data-keyboard="false">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h5 class="modal-title">How To Enable Email Sending In Gmail?</h5>
                </div>

                <div class="modal-body">
                 
                  <p>1. Before sending emails using the Gmail's SMTP Server, you to make some of the security and permission level settings under your <a class="text-semibold" href="https://myaccount.google.com/security" target="_blank" >Google Account Security Settings.</a></p> 
                  <p>2. Make sure that <b>2-Step-Verification</b> is disabled.</p>
                  <p>3. Turn ON the "Less Secure App" access or click <a class="text-semibold" href="https://myaccount.google.com/u/0/lesssecureapps" target="_blank">here.</a><p>
                  <p>4. If 2-step-verification is enabled, then you will have to create app password for your application or device.<p>
                  <p>5. For security measures, Google may require you to complete this additional step while signing-in. Click here to allow access to your Google account using the new device/app. <p>

                  <hr>
                   <p>Note: It may take an hour or more to reflect any security changes</p> 

                
                </div>

              
              </div>
            </div>
          </div>
          <!-- /disabled keyboard interaction -->
