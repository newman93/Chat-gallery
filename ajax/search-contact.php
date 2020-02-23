<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
    require_once '../vendor/autoload.php';
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

	$user = explode(" ", $_POST['user']);
	
	if (count($user) > 1) {
		$sql = "SELECT u.username, u.name, u.surname, u.avatar, u.active FROM users as u
					INNER JOIN contacts as c ON c.username = :username AND c.contact = u.username
				WHERE c.username = :username AND ((u.name = :name AND u.surname = :surname) OR (u.name = :surname AND u.surname = :name))";
		$query = $conn->prepare($sql);

		$username = $antiXss->xss_clean($_SESSION['username']);
        $name = $antiXss->xss_clean($user[0]);
        $surname = $antiXss->xss_clean($user[1]);

		$query->bindParam(':username', $usernme);
		$query->bindParam(':name', $name);
		$query->bindParam(':surname', $surname );
	} else {
		$sql = "SELECT u.username, u.name, u.surname, u.avatar, u.active FROM users as u
					INNER JOIN contacts as c ON c.username = :username AND c.contact = u.username
			WHERE c.username = :username AND (u.name = :name OR u.surname = :name)";
		$query = $conn->prepare($sql);

		$username = $antiXss->xss_clean($_SESSION['username']);
        $name = $antiXss->xss_clean($user[0]);

		$query->bindParam(':username', $username);
		$query->bindParam(':name', $name);
	}
	
	$query->execute();
	$row_count = $query->rowCount();
	
    if ($row_count > 0) {
		$iterator = 0;
		while ($row = $query->fetch()) {
			$data[$iterator]['username'] = $row['username'];
			$data[$iterator]['name'] = $row['name'];
			$data[$iterator]['surname'] = $row['surname'];
			$data[$iterator]['avatar'] = $row['avatar'];
			$data[$iterator]['active'] = $row['active'] == 1 ? "online" : "offline";
			$iterator += 1;
		}
		echo json_encode($data);
    } else {
		$data[0]['empty'] = 'empty';
        echo json_encode($data);
    }
?>