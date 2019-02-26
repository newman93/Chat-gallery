<?php 
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	
	$sql = "SELECT name, surname, avatar FROM users
          WHERE username = :username";
  $query = $conn->prepare($sql);
  $query->bindParam(':username', $_POST['username']);
  $query->execute();
  
  $row = $query->fetch();
  $data['user']['name'] = $row['name']; 
  $data['user']['surname'] = $row['surname']; 
  $data['user']['avatar'] = $row['avatar']; 
  $data['user']['username'] = $_POST['username']; 

  
  $sql = "SELECT  from_u.username as from_username, from_u.name as from_name, from_u.surname as from_surname, to_u.name as to_name, to_u.surname as to_surname, message, DATE_FORMAT(date, '%Y-%m-%d') as date_d, 
       DATE_FORMAT(date,'%H:%i') as time  FROM messages as m
				INNER JOIN users as from_u ON m.from_username = from_u.username
        INNER JOIN users as to_u ON m.to_username = to_u.username
			WHERE (m.from_username = :from AND m.to_username = :to) OR (m.from_username = :to AND m.to_username = :from)
      ORDER BY date ASC";
  $query = $conn->prepare($sql); 
  $query->bindParam(':from', $_SESSION['username']);
  $query->bindParam(':to', $_POST['username']);

	$query->execute();
	$row_count = $query->rowCount();
	
    if ($row_count > 0) {
		$iterator = 0;
		while ($row = $query->fetch()) {
			$data['messages'][$iterator]['from_name'] = $row['from_name'];
			$data['messages'][$iterator]['from_surname'] = $row['from_surname'];
			$data['messages'][$iterator]['to_name'] = $row['to_name'];
			$data['messages'][$iterator]['to_name'] = $row['to_name'];
      $data['messages'][$iterator]['message'] = $row['message'];
      $data['messages'][$iterator]['time'] = $row['time'];
      $data['messages'][$iterator]['date'] = $row['date_d'];
      $data['messages'][$iterator]['author'] = $row['from_username'] == $data['user']['username']? 0 : 1; 
			$iterator += 1;
		}
    
    $sql = "UPDATE messages SET to_read = 0 WHERE from_username = :from AND to_username = :to";
    $query = $conn->prepare($sql);
    $query->bindParam(':from', $_POST['username']);
    $query->bindParam(':to', $_SESSION['username']);
    $query->execute();
    
		echo json_encode($data);
    } else {
     $data['messages'][0]['empty'] = 'empty';
     echo json_encode($data);
    }
?>

