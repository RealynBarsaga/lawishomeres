<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="UTF-8">
        <title>Madridejos Home Residence Management System</title>
        <link rel="icon" type="x-icon" href="../img/lg.png">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
		<meta name="description" content="Backup my database is a free database backup software for any developer to use on your site to backup recent DATABASE." />
		<meta name="keywords" content="database, mysql, db, backup, localhost, username, user, password, phpmyadmin" />
		<meta name="author" content="Ritedev Technologies"/>
		
		<!-- vector map CSS -->
		<link href="vendors/bower_components/jasny-bootstrap/dist/css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css"/>
		
		<link href="vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.css" rel="stylesheet" type="text/css">
		
		<!-- switchery CSS -->
		<link href="vendors/bower_components/switchery/dist/switchery.min.css" rel="stylesheet" type="text/css"/>
		
		<!-- Custom CSS -->
		<link href="dist/css/style.css" rel="stylesheet" type="text/css">
	</head>
	<style>
		body {
            background-image: url('../img/received_1185064586170879.jpeg');
            background-attachment: fixed;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover; /* Ensures the background image covers the entire container */
            height: 100vh; /* Makes sure the body takes up the full height of the viewport */
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center; /* Vertically centers the content */
            justify-content: center; /* Horizontally centers the content */
        }
		.btn{
            background-image: url('../img/bg.jpg');
            color: #fff;
		}
		.btn:hover{
			background-image: url('../img/bg.jpg');
			color: #fff;
		}
	</style>
	<body>
		<!--Preloader-->
		<div class="preloader-it">
			<div class="la-anim-1"></div>
		</div>
		<!--/Preloader-->
		
		<div class="wrapper pa-0" style="border-radius: 8px;">
			<!-- Main Content -->
			<div class="col-sm-12 col-xs-12">
				<div class="mb-30">
					<h3 class="text-center txt-dark mb-10">Connect your settings</h3>
					<h6 class="text-center nonecase-font txt-grey">Enter your database details below</h6>
				</div>	
				<div class="form-wrap">
					<form action="database_backup.php" method="post" id="">
						<div class="form-group">
							<label class="control-label mb-10" >Host</label>
							<input type="text" class="form-control" placeholder="Enter Server Name EX: Localhost" name="server" id="server" required="" autocomplete="on">
						</div>
						<div class="form-group">
							<label class="control-label mb-10" >Database Username</label>
							<input type="text" class="form-control" placeholder="Enter Database Username EX: root" name="username" id="username" required="" autocomplete="on">
						</div>
						<div class="form-group">
							<label class="pull-left control-label mb-10" >Database Password</label>
							<input type="password" class="form-control" placeholder="Enter Database Password" name="password" id="password" >
						</div>
						<div class="form-group">
							<label class="pull-left control-label mb-10">Database Name</label>
							<input type="text" class="form-control" placeholder="Enter Database Name" name="dbname" id="dbname" required="" autocomplete="on">
						</div>
						<div class="form-group text-center">
							<button type="submit" name="backupnow" class="btn btn-rounded">Initiate Backup</button>
						</div>
					</form>
				</div>
			</div>	
			<!-- /Main Content -->
		
		</div>
		<!-- /#wrapper -->
		
		<!-- JavaScript -->
		<script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>
		
		<!-- Bootstrap Core JavaScript -->
		<script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js"></script>
	
		<!-- Fancy Dropdown JS -->
		<script src="dist/js/dropdown-bootstrap-extended.js"></script>
		
		<!-- Owl JavaScript -->
		<script src="vendors/bower_components/owl.carousel/dist/owl.carousel.min.js"></script>
	
		<!-- Switchery JavaScript -->
		<script src="vendors/bower_components/switchery/dist/switchery.min.js"></script>
		
		<!-- Bootstrap Core JavaScript -->
		<script src="vendors/bower_components/jasny-bootstrap/dist/js/jasny-bootstrap.min.js"></script>
		
		<!-- Slimscroll JavaScript -->
		<script src="dist/js/jquery.slimscroll.js"></script>
		
		<!-- Init JavaScript -->
		<script src="dist/js/init.js"></script>
<script>
/*Toast Init*/
$(document).ready(function() {
	"use strict";
	
	$.toast({
		heading: 'Welcome to HMRMS',
		text: 'Simple Database Backup for your website.',
		position: 'top-right',
		loaderBg:'#fec107',
		icon: 'success',
		hideAfter: 3500, 
		stack: 6
	});
	
	$('.tst1').on('click',function(e){
	    $.toast().reset('all'); 
		$("body").removeAttr('class');
		$.toast({
            heading: '2 new messages',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'top-right',
            loaderBg:'#fec107',
            icon: 'info',
            hideAfter: 3000, 
            stack: 6
        });
		return false;
    });

	$('.tst2').on('click',function(e){
        $.toast().reset('all');
		$("body").removeAttr('class');
		$.toast({
            heading: 'Server not responding',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'top-right',
            loaderBg:'#ff2a00',
            icon: 'warning',
            hideAfter: 3500, 
            stack: 6
        });
		return false;
	});
	
	$('.tst3').on('click',function(e){
        $.toast().reset('all');
		$("body").removeAttr('class');
		$.toast({
            heading: 'Welcome to Hound',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'top-right',
            loaderBg:'#fec107',
            icon: 'success',
            hideAfter: 3500, 
            stack: 6
          });
		return false;  
	});

	$('.tst4').on('click',function(e){
		$.toast().reset('all');
		$("body").removeAttr('class');
		$.toast({
            heading: 'Opps! somthing wents wrong',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'top-right',
            loaderBg:'#fec107',
            icon: 'error',
            hideAfter: 3500
        });
		return false;
    });
	
	$('.tst5').on('click',function(e){
	    $.toast().reset('all');   
		$("body").removeAttr('class');
		$.toast({
            heading: 'Top Left',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'top-left',
            loaderBg:'#878787',
            hideAfter: 3500
        });
		return false;
    });
	
	$('.tst6').on('click',function(e){
		$.toast().reset('all');
		$("body").removeAttr('class');
		$.toast({
            heading: 'Top Right',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'top-right',
            loaderBg:'#878787',
            hideAfter: 3500
        });
		return false;
    });
	
	$('.tst7').on('click',function(e){
		$.toast().reset('all');
		$("body").removeAttr('class');
		$.toast({
            heading: 'Bottom Left',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'bottom-left',
            loaderBg:'#878787',
            hideAfter: 3500
        });
		return false;
    });
	
	$('.tst8').on('click',function(e){
	    $.toast().reset('all');   
		$("body").removeAttr('class');
		$.toast({
            heading: 'Bottom Right',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'bottom-right',
            loaderBg:'#878787',
            hideAfter: 3500
        });
		return false;
	});
	
	$('.tst9').on('click',function(e){
	    $.toast().reset('all');   
		$("body").removeAttr('class').removeClass("bottom-center-fullwidth").addClass("top-center-fullwidth");
		$.toast({
            heading: 'Top Center',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'top-center',
            loaderBg:'#878787',
            hideAfter: 3500
        });
		return false;
	});
	
	$('.tst10').on('click',function(e){
	    $.toast().reset('all');
		$("body").removeAttr('class').addClass("bottom-center-fullwidth");
		$.toast({
            heading: 'Bottom Right',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'bottom-center',
            loaderBg:'#878787',
            hideAfter: 3500
        });
		return false;
	});
});
</script>
	</body>
</html>
