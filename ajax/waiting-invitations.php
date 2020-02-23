<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
    require_once '../vendor/autoload.php';
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

	$sql = "SELECT i.contact, u.name, u.surname, u.username, u.avatar  FROM invitations as i
			INNER JOIN users as u ON u.username = i.username
			WHERE i.contact = :username";
    $query = $conn->prepare($sql);

    $username = $antiXss->xss_clean($_SESSION['username']);

    $query->bindParam(':username', $username);
	$query->execute();
	$row_count = $query->rowCount();
	
    if ($row_count > 0) {
		$iterator = 0;
		while ($row = $query->fetch()) {
			$data[$iterator]['name'] = $row['name'];
			$data[$iterator]['surname'] = $row['surname'];
      $data[$iterator]['username'] = $row['username'];
			$data[$iterator]['avatar'] = $row['avatar'];
			$iterator += 1;
		}
		echo json_encode($data);
    } else {
		$data[0]['empty'] = 'empty';
        echo json_encode($data);
    }
?>