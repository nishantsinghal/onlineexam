<?php require('functions.php'); 
			require('views.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome to online exam portal</title>
	<link rel="stylesheet" href="css/style.css"/>
	<script type="text/javascript" src="js/jquery-2.2.4.js"></script>
	<script type="text/javascript" src="js/disable.js"></script>
	<script type="text/javascript" src="js/counter.js"></script>
</head>
<body>
	<div id="container">
		<?php echo head();
					echo main_content();
					echo footer();
		?>
	</div>
</body>
</html>