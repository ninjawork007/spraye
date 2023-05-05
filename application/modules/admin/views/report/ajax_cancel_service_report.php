<table class="table datatable-button-print-basic">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Customer Start Date</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Service Name</th>
            <th>Property Name</th>
            <th>Cost</th>
            <th>Reason</th>
            <th>Cancel Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($Services as $Invs){
            $CustomerData = $this->db->select('*')->from("customers")->where(array("customer_id" => $Invs->customer_id))->get()->row();
        ?>
        <tr>
            <td><a href="<?php echo base_url() ?>/admin/editCustomer/<?= $CustomerData->customer_id ?>" target="_blank"><?php echo $CustomerData->first_name. " " . $CustomerData->last_name ?></a></td>
            <td><?php echo date("d F, Y", strtotime($CustomerData->created_at)) ?></td>
            <td><?php echo $CustomerData->email ?></td>
            <td><?php echo $CustomerData->work_phone ?></td>
            <td><?php echo $Invs->job_name ?></td>
            <td><?php echo $Invs->property_title ?></td>
            <td>$<?php echo $Invs->job_cost ?></td>
            <td><?php echo $Invs->cancel_reason ?></td>
            <td><?php echo date("d F, Y", strtotime($Invs->created_at)) ?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>