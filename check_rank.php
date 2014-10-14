<?php $dbh = new PDO('mysql:host=localhost;dbname=mytest', 'root', ''); ?>

<html>
<head>
	<meta charset="utf-8">
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<link rel="stylesheet" href="main.css" />
	<title>Check Registration Status</title>
</head>
<body>
	<?php
		$my_id = $_GET["id"];

		$rank_query = $dbh->prepare("SELECT z.rank, z.position FROM ( SELECT t.link, t.position, @rownum := @rownum + 1 AS rank FROM register t, (SELECT @rownum := 0) r ORDER BY position DESC ) as z WHERE link = :link");
		$rank_query->bindParam(':link', $my_id);
		$rank_query->execute();
		$rank_data = $rank_query->fetch();

		$rank = $rank_data['rank'];
		$position = $rank_data['position'];
	?>
	<div id="success" style="display: block;">
		<h1>Your rank is <?php echo $rank; ?>!</h1>
		<h2>You've invited <?php echo $position; ?>person(s).</h2>
		<h2>To climb the ranks send <a href="http://localhost/test/?referer=<?php echo $my_id; ?>">this link</a> to your friends.</h2>
	</div>
</body>
</html>