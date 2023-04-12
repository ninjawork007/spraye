
<head>
	<meta charset="utf-8">
	<!-- <meta http-equiv="refresh" content="60"/> -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SPRAYE</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?= base_url("assets/admin")?>/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?= base_url("assets/admin")?>/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?= base_url("assets/admin")?>/assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?= base_url("assets/admin")?>/assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?= base_url("assets/admin")?>/assets/css/colors.css" rel="stylesheet" type="text/css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<!-- Core JS files -->
	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/plugins/loaders/pace.min.js"></script>
	
 	 <script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/core/libraries/jquery.min.js"></script>
 
	

	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/plugins/loaders/blockui.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.js"></script>

	<!-- /core JS files -->
   <!-- /theme JS files -->



	<!-- Theme JS files -->
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/forms/styling/uniform.min.js"></script>
	

	<script type="text/javascript">
	 	var default_display_length = <?= $this->session->userdata('compny_details')->default_display_length  ?>; 
	</script>
	
 	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>


	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/plugins/forms/selects/select2.min.js"></script>

	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>

	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>

	 
 
	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/core/app.js"></script>
	
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/pages/form_multiselect.js"></script>

	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/pages/form_bootstrap_select.js"></script>
    
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/pages/datatables_extension_row_reorder.js"></script>


    <script src="<?= base_url() ?>/assets/popup/js/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/validation/form-validation.js"></script>
                
</head>
