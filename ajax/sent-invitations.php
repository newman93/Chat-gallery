<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	
	$sql = "SELECT i.contact, u.name, u.surname, u.avatar  FROM invitations as i 
			INNER JOIN users as u On u.username = i.contact
			WHERE i.username = :username";
    $query = $conn->prepare($sql); 
    $query->bindParam(':username', $_SESSION['username']);
	$query->execute();
	$row_count = $query->rowCount();
	
    if ($row_count > 0) {
		$iterator = 0;
		while ($row = $query->fetch()) {
			$data[$iterator]['name'] = $row['name'];
			$data[$iterator]['surname'] = $row['surname'];
			$data[$iterator]['avatar'] = $row['avatar'];
			$iterator += 1;
		}
		echo json_encode($data);
    } else {
		$data[0]['empty'] = 'empty';
        echo json_encode($data);
    }
?>