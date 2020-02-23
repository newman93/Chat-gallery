<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
    require_once '../vendor/autoload.php';
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

	$sql = "SELECT u.username, u.name, u.surname, u.avatar, u.active, 
          IF((SELECT COUNT(message) FROM messages WHERE to_username = :username AND from_username = u.username AND to_read = 1) > 0, 'to_read', '') as to_read
        FROM contacts as c
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
			$data[$iterator]['name'] = $row['name'];
			$data[$iterator]['surname'] = $row['surname'];
			$data[$iterator]['avatar'] = $row['avatar'];
			$data[$iterator]['active'] = $row['active'] == 1 ? 'online' : 'offline';
      $data[$iterator]['to_read'] = $row['to_read'];
			$iterator += 1;
		}
		echo json_encode($data);
    } else {
		$data[0]['empty'] = 'empty';
        echo json_encode($data);
    }
?>