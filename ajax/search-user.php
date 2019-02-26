<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	
	$user = explode(" ", $_POST['user']);
	
	if (count($user) > 1) {
		$sql = "SELECT u.name, u.surname, u.username, u.avatar, c.contact as contact, i.contact as invitation FROM users as u
				LEFT JOIN contacts as c ON c.username = :username AND c.contact = u.username
				LEFT JOIN invitations as i ON (i.username = :username AND i.contact = u.username) OR (i.contact = :username AND  i.username = u.username)
			WHERE u.username != :username AND ((u.name = :name AND u.surname = :surname) OR (u.name = :surname AND u.surname = :name))";
		$query = $conn->prepare($sql); 
		$query->bindParam(':username', $_SESSION['username']);
		$query->bindParam(':name', $user[0]);
		$query->bindParam(':surname', $user[1]);
	} else {
		$sql = "SELECT u.name, u.surname, u.username, u.avatar, c.contact as contact, i.contact as invitation FROM users as u
				LEFT JOIN contacts as c ON c.username = :username AND c.contact = u.username
				LEFT JOIN invitations as i ON (i.username = :username AND i.contact = u.username) OR (i.contact = :username AND  i.username = u.username)
			WHERE u.username != :username AND (u.name = :name OR u.surname = :name)";
		$query = $conn->prepare($sql); 
		$query->bindParam(':username', $_SESSION['username']);
		$query->bindParam(':name', $user[0]);
	}
	
	$query->execute();
	$row_count = $query->rowCount();
	
    if ($row_count > 0) {
		$iterator = 0;
		while ($row = $query->fetch()) {
			$data[$iterator]['name'] = $row['name'];
			$data[$iterator]['surname'] = $row['surname'];
      $data[$iterator]['username'] = $row['username'];
			$data[$iterator]['avatar'] = $row['avatar'];
			$data[$iterator]['contact'] = $row['contact'];
			$data[$iterator]['invitation'] = $row['invitation'];
			$iterator += 1;
		}
		echo json_encode($data);
    } else {
		$data[0]['empty'] = 'empty';
        echo json_encode($data);
    }
?>