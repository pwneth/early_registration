<?php 

	$dbh = new PDO('mysql:host=localhost;dbname=mytest', 'root', '');

	function randomLetter() {
	    $int = rand(0,51);
	    $a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $rand_letter = $a_z[$int];
	    return $rand_letter;
	}

	$mili = (microtime(true)*10000);
	$unique_id = "id";

	for ($i = 1; $i <= strlen($mili)-1; $i++) {
		$rand_letter = randomLetter();
	    $unique_id.="-".(substr($mili, $i, 1).$rand_letter);
	}

	$email = $_POST["email"];
	$referer = $_POST["referer"];
	$link = $unique_id;
	$position = 0;

	$user_exists = $dbh->prepare("SELECT email FROM register WHERE email = :email");
	$user_exists->bindParam(':email', $email);
	$user_exists->execute();

	$referer_exists = $dbh->prepare("SELECT * FROM register WHERE link = :referer");
	$referer_exists->bindParam(':referer', $referer);
	$referer_exists->execute();
	$referer_data = $referer_exists->fetch();


	if ($user_exists->rowCount() > 0) {
		$arr = array('error' => "Email has already been registered");
	} elseif (filter_var($email, FILTER_VALIDATE_EMAIL) != true) {
		$arr = array('error' => "Must enter an email address");
	} else {
		if ($referer_data) {
			$referer = $referer_data['email'];

			$referer_position_update = $dbh->prepare("UPDATE register SET position = position + 1 WHERE email = :referer");
			$referer_position_update->bindParam(':referer', $referer);
			$referer_position_update->execute();

		} else {
			$referer = 'Nobody';
		}

		$query = $dbh->prepare('INSERT INTO register (email, position, link) VALUES (:email, :position, :link)');
		
		$query->execute(array(':email'=>$email,
								':position'=>$position,
								':link'=>$link
								));

		$rank_query = $dbh->prepare("SELECT z.rank FROM ( SELECT t.email, t.position, @rownum := @rownum + 1 AS rank FROM register t, (SELECT @rownum := 0) r ORDER BY position DESC ) as z WHERE email = :email");
		$rank_query->bindParam(':email', $email);
		$rank_query->execute();
		$rank_data = $rank_query->fetch();

		$rank = $rank_data['rank'];

		$arr = array('email' => $email, 'unique_id' => $unique_id, 'position' => $position, 'referer' => $referer, 'rank' => $rank);
	}	

	// $subject = "Thank you for registering early with us!" ;
	// $message = "
	// <html>
	// <body>
	// Thank you ".$email." for registering early with us.<br><br>
	// Your unique link is <a href=\"http://whatever.com?id=".$link."\">http://whatever.com?id=".$link."</a><br><br>
	// Best,<br>
	// The Team
	// </body>
	// </hmtl>
	// ";
	// $headers  = 'MIME-Version: 1.0' . "\r\n";
	// $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// $headers .= "From: Someone <m.n@localhost>";
	// mail($email, $subject, $message, $headers);

	echo json_encode($arr);

?>