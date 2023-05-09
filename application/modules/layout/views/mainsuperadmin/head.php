<?php 
if (isset($this->session->userdata['superadmin'])) {
  
}else {
    
redirect('superadmin');
}
?>


<head>
	<meta charset="utf-8">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SPRAYE</title>
    <!-- mainsuperadmin  head -->
    <link rel="icon" href="<?=base_url()?>/favicon.ico" type="image/ico">

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/admin') ?>/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/admin') ?>/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/admin') ?>/assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/admin') ?>/assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?= base_url('assets/admin') ?>/assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->



	<!-- Core JS files -->
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/loaders/blockui.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->

	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/notifications/pnotify.min.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/uploaders/fileinput/plugins/purify.min.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/uploaders/fileinput/plugins/sortable.min.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/uploaders/fileinput/fileinput.min.js"></script>


    <script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/ui/fullcalendar/fullcalendar.min.js"></script>



	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/pages/extra_fullcalendar.js"></script>
	
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/pages/form_multiselect.js"></script>
	
	<script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/pages/uploader_bootstrap.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>/assets/superadmin/validation/form-validation.js"></script>
	<script src="<?= base_url() ?>/assets/popup/js/sweetalert2.all.js"></script>
	<!-- /theme JS files -->
</head>

