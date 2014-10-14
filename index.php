<?php $dbh = new PDO('mysql:host=localhost;dbname=mytest', 'root', ''); ?>

<html>
<head>
	<meta charset="utf-8">
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<link rel="stylesheet" href="main.css" />
	<script src="main.js"></script>
	<title>Early Registration Form</title>
</head>
<body>
	<?php 
		// if (isset($_GET["referer"])) {
		// 	$referer = $_GET["referer"];
		// 	echo $referer;	
		// }

		// // foreach($dbh->query('SELECT * from register') as $row) {
	 // //        print_r($row['email']);
	 // //    }
	?>
	<div id="input_email_form">
		<h1>Please Enter Your Email</h1>
		<form>
			<input type="text" name="email" id="email_text">
			<input type="submit" id="submit_email">
		</form>
	</div>
	<div id="success"></div>
</body>
</html>