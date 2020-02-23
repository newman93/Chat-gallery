<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	require_once '../vendor/autoload.php';
	use \voku\helper\AntiXSS;

	$antiXss = new AntiXSS();
	
	$sql = "SELECT u.username FROM contacts as c
				INNER JOIN users as u ON u.username = c.contact
			WHERE c.username = :username";
  $query = $conn->prepare($sql);

  $username = $antiXss->xss_clean($_SESSION['username']);

  $query->bindParam(':username', $username);
	$query->execute();
	$row_count = $query->rowCount();
	
    if ($row_count > 0) {
		$iterator = 0;
		while ($row = $query->fetch()) {
			$data[$iterator]['username'] = $row['username'];
			$iterator += 1;
		}
		echo json_encode($data);
    } else {
		$data[0]['empty'] = 'empty';
        echo json_encode($data);
    }
?>

