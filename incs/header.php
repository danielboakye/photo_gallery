<!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Boakye Daniel Kojo">
 	<title>Photo Gallery</title>
 	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>css/bootstrap.min.css">
 	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>css/font-awesome.min.css">
 	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>css/lightbox.min.css">
 	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>css/fileinput.min.css" media="all">
 
 	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>css/master.min.css" media="all">
 	<link rel="icon" href="<?= BASE_URL ?>img/favicon.png" sizes="16x16" type="image/png">
 </head>
 <body data-spy="scroll" data-target="#main-navbar">
 <!-- view -->

	<nav class="navbar navbar-inverse navbar-fixed-top">
	<!-- navbar-static-top -->
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse" id="main-navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<!-- actual links -->
				<a class="navbar-brand" href="<?= BASE_URL ?>">
					<div>
						<img src="<?= BASE_URL ?>img/logo.png" class="img-responsive" style="margin-top: -.2em; float: left;">
						<span style="padding-left: .2em; float: right;">rk Inc!</span>
					</div>
				</a> <!-- logo -->
			</div>

			<!-- navigation -->
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?= BASE_URL ?>">Gallery</a></li>
					<li><a href="<?= BASE_URL ?>contact/">Contact</a></li>
				</ul> 	
			</div>
		</div>
	</nav>	