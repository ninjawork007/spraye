

        <!-- Content area -->
        <div class="content">

          <!-- Form horizontal -->
          <div class="panel panel-flat">
          <div class="panel-heading">
              <h5 class="panel-title">Add Payment Mode</h5>
            </div>

            
            <div class="panel-body">
              

              <form class="form-horizontal" action="<?= base_url('admin/addPaymentData') ?>" method="post" name="addpayment" enctype="multipart/form-data" >
                <fieldset class="content-group">
                  
                  <div class="form-group">
                    <label class="control-label col-lg-2">Payment Mode Name</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" name="payment_name" placeholder="Payment Mode Name">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-lg-2">Country Name</label>
                    <div class="col-lg-10">


                      <select class="form-control" name="country">
                        <option value="">Select country</option>
                      <?php foreach ($country as $value): ?>
                          <option value="<?= $value->country_id ?>"><?= $value->country_name ?></option>  

                      <?php endforeach ?>


                      </select>
                      
                    </div>
                  </div>

                  <div class="form-group">
                  <label class="col-lg-2 control-label text-semibold">Selcect Payment Icon </label>
                  <div class="col-lg-10">
                    <input type="file" name="payment_icon"> 
                  </div>
                </div>

                
                </fieldset>

                <div class="text-right">
                  <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>
              </form>
            </div>
          </div>
          <!-- /form horizontal -->

        </div>
        <!-- /content area -->