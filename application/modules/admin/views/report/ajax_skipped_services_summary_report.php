<div class="table-responsive table-spraye">
    <table class="table datatable-colvis-state " id="summary_tbl">
        <thead>
        <tr>
            <th>Skip Reason</th>
            <th># Skipped Services</th>
            <th>Lost Revenue</th>
        </tr>
        </thead>
        <tbody id="new_estimates_tbody">
        <?php
        if (!empty($summary['summary'])) {
            $total_lost_revenue = 0;
            $total_services = 0;

            foreach ($summary['summary'] as $key => $value) {
                ?>

                <tr>
                    <td ><?= $key ?></td>
                    <td><?= $value['count'] ?></td>
                    <td><?= number_format($value['value'] ,2) ?></td>

                </tr>
                <?php
                $total_lost_revenue += $value['value'];
                $total_services += $value['count'];
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
            <td><b><?= isset($total_services)? $total_services: 0 ?></b></td>
            <td><b><?= (isset($total_lost_revenue)?number_format($total_lost_revenue,2):0) ?></b></td>

        </tr>
        </tfoot>
    </table>
</div>
<script>
    // Basic initialization
    $('#summary_tbl').DataTable({
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
                className: 'btn bg-green summary',
                footer: true,
                filename: 'SkippedSummary.csv',
                exportOptions: {
                    modifier: {
                        search: 'none'
                    }
                }
            }
        ]
    });
</script>