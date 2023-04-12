<div class="table-responsive table-spraye">
	<table class="table datatable-button-print-basic">
        <thead>
            <tr>
                <th>Customer</th>
                <th>0-30 Days</th>
                <th>31-60 Days</th>
                <th>61-90 Days</th>
                <th>90+ Days</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($report_details)){ 
                $total_current = 0;
                $total_30 = 0;
                $total_60 = 0;
                $total_90 = 0;
                $total_all = 0;
            ?>
                <?php foreach($report_details as $report_detail) { ?>
                <tr>
                    <td><a href="<?=base_url("admin/editCustomer/").$report_detail['customer_id'] ?>" class="button-next"><?= $report_detail['first_name'] . " " . $report_detail['last_name']?></a></td>
                    <td>$<?= number_format($report_detail['current_total'],2) ?></td>
                    <td>$<?= number_format($report_detail['30_total'],2) ?></td>
                    <td>$<?= number_format($report_detail['60_total'],2) ?></td>
                    <td>$<?= number_format($report_detail['90_total'],2) ?></td>
                    <td>$<?= number_format($report_detail['customer_total_due'],2) ?></td>
                </tr>
                <?php 
                    $total_current += $report_detail['current_total'];
                    $total_30 += $report_detail['30_total'];
                    $total_60 += $report_detail['60_total'];
                    $total_90 += $report_detail['90_total'];
                    $total_all += $report_detail['customer_total_due'];
                } ?> 
                <tr>
                    <td><b>TOTALS</b></td>
                    <td><b>$<?= number_format($total_current,2) ?></b></td>
                    <td><b>$<?= number_format($total_30,2) ?></b></td>
                    <td><b>$<?= number_format($total_60,2) ?></b></td>
                    <td><b>$<?= number_format($total_90,2) ?></b></td>
                    <td><b>$<?= number_format($total_all,2) ?></b></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php if(isset($current_invoices)){ ?><a href="<?=base_url("admin/Invoices/printInvoice/").$current_invoices; ?>" target="_blank" class="button-next">View Invoices</a><?php }?></td>
                    <td><?php if(isset($aged30_invoices)){ ?><a href="<?=base_url("admin/Invoices/printInvoice/").$aged30_invoices ?>" target="_blank" class="button-next">View Invoices</a><?php }?></td>
                    <td><?php if(isset($aged60_invoices)){ ?><a href="<?=base_url("admin/Invoices/printInvoice/").$aged60_invoices ?>" target="_blank" class="button-next">View Invoices</a><?php }?></td>
                    <td><?php if(isset($aged90_invoices)){ ?><a href="<?=base_url("admin/Invoices/printInvoice/").$aged90_invoices ?>" target="_blank" class="button-next">View Invoices</a><?php }?></td>
                    <td><a href="<?=base_url("admin/Invoices?aging=1")?>" target="_blank" class="button-next">View Invoices</a></td>
                </tr>
            
            <?php }else{ ?>
            <tr>
                <td></td>
                <td></td>
                <td class="text-center">No records found</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?>
        </tbody>
	</table>
</div>