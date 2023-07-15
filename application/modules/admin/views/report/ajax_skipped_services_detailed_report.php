<div  class="table-responsive table-spraye">
    <table  class="table datatable-colvis-state " id="detailed_tbl" >
        <thead>
        <tr>
            <th>Service</th>
            <th>Customer</th>
            <th>Property Address</th>
            <th>Service Type</th>
            <th>Service Area</th>
            <th>Skip reason</th>
            <th>Date of Skip</th>
            <th>Person Responsible</th>
            <th>Lost revenue</th>
        </tr>
        </thead>
        <tbody id="accepted_estimates_tbody">
        <?php
        if (!empty($services)) {
            $total_lost_revenue = 0;
            $total_services = 0;

            foreach ($services as $value) {
                ?>

                <tr>
                    <td ><?= $value['service'] ?></td>
                    <td ><?= $value['customer'] ?></td>
                    <td ><?= $value['property_address'] ?></td>
                    <td ><?= $value['service_type'] ?></td>
                    <td ><?= $value['service_area'] ?></td>
                    <td ><?= $value['skip_reason'] ?></td>
                    <td ><?= $value['skipped_at'] ?></td>
                    <td ><?= $value['responsible'] ?></td>
                    <td><?= number_format($value['lost_revenue'],2) ?></td>
                </tr>
                <?php

                $total_lost_revenue += $value['lost_revenue'];
                $total_services++;
            }

        } else {
            ?>

            <tr>
                <td> No record found </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

        <?php }  ?>

        </tbody>
        <tfoot>
        <tr>
            <td><b>TOTALS</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><b><?= (isset($total_lost_revenue)?number_format($total_lost_revenue,2):0) ?></b></td>

        </tr>
        </tfoot>
    </table>
</div>
<script>
    // Basic initialization
    $('#detailed_tbl').DataTable({
        "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
        "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
        buttons: [
            {
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn bg-blue'
            },
            {
                extend: 'csv',
                text: '<i class="icon-printer position-left"></i> Download CSV',
                className: 'btn bg-green detailed',
                footer: true,
                filename: 'SkippedDetails.csv',
                exportOptions: {
                    modifier: {
                        search: 'none'
                    }
                }
            }
        ]
    });
</script>