<?php 
if (isset($this->session->userdata['superadmin'])) {
  
}else {
    
redirect('superadmin');
}
?>


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

	<!-- Theme JS files -->
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/forms/styling/uniform.min.js"></script>
	
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/notifications/pnotify.min.js"></script>
	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/plugins/editors/summernote/summernote.min.js"></script>
	<script src="<?= base_url("assets/admin")?>/assets/js/status/bootstrap-toggle.min.js"></script>
	
	<!--added by Chris 8-6-21-->
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/forms/styling/switch.min.js"></script>
	<!--End added by Chris 8-6-21-->

	<script type="text/javascript">
 		var default_display_length = 50; 
 	</script>	

	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/plugins/forms/selects/select2.min.js"></script>

	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>

	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>

	  <script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/plugins/pickers/color/spectrum.js"></script>
 
	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/core/app.js"></script>
	
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/pages/form_multiselect.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/pages/form_bootstrap_select.js"></script>

	 <script type="text/javascript" src="<?= base_url() ?>assets/superadmin/validation/form-validation.js"></script>
	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/pages/datatables_basic.js"></script>
	 <script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/pages/editor_summernote.js"></script> 
		<script src="<?= base_url() ?>/assets/popup/js/sweetalert2.all.js"></script>

		 	<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/pages/picker_color.js"></script>
 
                
</head>
