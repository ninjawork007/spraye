<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/pages/components_popups.js"></script>
<div class="table-responsive table-spraye">
    <table class="table datatable-colvis-state" style="border:1px solid #6eb1fd;">
        <thead>
            <tr>
                <th>Customer Number</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Second Email</th>
                <th>Address</th>
                <th>Cell Phone</th>
                <th>Phone</th>
                <th>Revenue by Program <span data-popup="tooltip-custom" data-container="body" title='This grabs the payments made on the invoices and adds them up.' data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></th>
                <th>YTD Revenue <span data-popup="tooltip-custom" data-container="body" title='Same calculation as Revenue by Program but this only includes from Jan 01 until today.' data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></th>
                <th>Projected Annual Revenue <span data-popup="tooltip-custom" data-container="body" title='Grabs all the invoice payments from the invoices for this customer going back exactly 1 year from today until today.' data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></th>
                <th>Lot Size</th>
                <th>Annual Revenue Per 1000 Sq Ft <span data-popup="tooltip-custom" data-container="body" title='Take the total lot size and divide that by 1000. We then take the total revenue and divide that by the new number we got from the first division.' data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if(!empty($report_details)){
                    foreach($report_details as $key=>$value) {
            ?>
            <tr>
                <td><?= $value['customer_number_link'] ?></td>
                <td><?= $value['first_name']; ?></td>
                <td><?= $value['last_name']; ?></td>
                <td><?= $value['email']; ?></td>
                <td><?= $value['second_email']; ?></td>
                <td><?= $value['address']; ?></td>
                <td><?= $value['cell_phone']; ?></td>
                <td><?= $value['phone']; ?></td>
                <td>$<?= number_format($value['revenue_by_product'],2); ?></td>
                <td>$<?= number_format($value['ytd_revenue'],2); ?></td>
                <td>$<?= number_format($value['projected_annual_revenue'],2); ?></td>
                <td><?= number_format($value['lot_size'],0); ?></td>
                <td>$<?= @number_format($value['annual_revenue_per_1000'],2); ?></td>
            </tr>
            <?php 
                    }
                }else{ 
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-center">No records found</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>