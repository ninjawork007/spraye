<div id="clover_payment_method" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Payment Method</h6>
            </div>

            <form name="add_clover_payment" id="add_clover_payment" method="POST" enctype="multipart/form-data"
                  form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-9">
                                <label>Card Number</label>
                                <input type="text" class="form-control" name="clover_card_number"
                                       placeholder="Card Number"
                                       required>
                            </div>
                            <div class="col-sm-6 col-md-3" width="50%">
                                <label>Expiration Month</label>
                                <select class="form-control" name="clover_card_exp_m"
                                        required>
                                    <option value="">Select Month</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label>Expiration Year</label>
                                <select class="form-control" name="clover_card_exp_y"
                                        required>
                                    <option value="">Select Year</option>
                                    <?php $cur_year = date('Y');
                                    for ($i = 0; $i <= 10; $i++) { ?>
                                        <option
                                            value="<?php echo $cur_year + $i; ?>"><?php echo $cur_year + $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label>CVV</label>
                                <input type="text" class="form-control number-only" name="clover_card_cvv"
                                       placeholder="CVV"
                                       maxlength="4" pattern="\d{4}"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="submitCloverPaymentMethod" class="btn btn-success"
                                data-customer="<?php echo $customerData['customer_id']; ?>">Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>