<div id="basys_payment_method" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary modal_head">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Payment Method</h6>
            </div>

            <form name="add_basys_payment" id="add_basys_payment" method="POST" enctype="multipart/form-data"
                  form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-9">
                                <label>Card Number</label>
                                <input type="text" class="form-control" name="card_number" placeholder="Card Number"
                                       required>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label>Card Exp</label>
                                <input type="text" class="form-control" name="card_exp" placeholder="MM/YY" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="submitPaymentMethod" class="btn btn-success"
                                data-customer="<?= $customerData['customer_id'] ?>">Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>