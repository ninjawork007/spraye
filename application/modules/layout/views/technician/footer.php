</div>
<!-- /content area -->

</div>
<!-- /main content -->

</div>
<!-- /page content -->

</div>
<!-- Theme JS files -->

<script type="text/javascript">
    $(document).ready(function () {
        $('#example').DataTable({
            dom: 'Bfrtp',
            buttons: [
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                }
            ],
            columnDefs: [
                {
                    targets: [0, 1, 2],
                    className: 'mdl-data-table__cell--non-numeric'
                }
            ]
        });
    });</script>


</body>  

<!--<script type="text/javascript" src="<?php echo base_url() ?>assets/emp/js/plugins/forms/selects/select2.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url() ?>assets/emp/js/core/app.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/plugins/loaders/pace.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/core/libraries/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/plugins/loaders/blockui.min.js"></script>
     

<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/plugins/pickers/daterangepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/pages/dashboard.js"></script>

<!-- /theme JS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/plugins/forms/selects/bootstrap_select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/pages/form_bootstrap_select.js"></script>

<!-- Global stylesheets -->

<script type="text/javascript" src="<?php echo base_url() ?>assets/emp/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/emp/js/plugins/forms/inputs/touchspin.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/emp/js/plugins/forms/styling/switch.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/emp/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/emp/js/plugins/forms/styling/uniform.min.js"></script>

<script type="text/javascript" src="<?php echo base_url() ?>assets/emp/js/pages/form_validation.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/plugins/uploaders/fileinput.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/pages/uploader_bootstrap.js"></script>

<script type="text/javascript">
        var default_display_length = 50; 
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/plugins/tables/datatables/extensions/responsive.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/plugins/notifications/sweet_alert.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/emp/js/plugins/notifications/pnotify.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/emp/js/pages/components_modals.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/emp/js/plugins/buttons/spin.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/emp/js/plugins/buttons/ladda.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/emp/js/pages/components_buttons.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/emp/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/emp/js/plugins/forms/inputs/autosize.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/emp/js/plugins/forms/inputs/formatter.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/emp/js/plugins/forms/inputs/passy.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/emp/js/plugins/forms/inputs/maxlength.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

<script>

    var app = angular.module('myapp', ['ngRoute']);

</script>
<script type="text/javascript">
  setTimeout(function() {
  location.reload();
}, 60   000);

</script>


</html>
