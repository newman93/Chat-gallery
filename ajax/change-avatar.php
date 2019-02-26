<?php 
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	require_once '../vendor/autoload.php';
  use \Gumlet\ImageResize;

	
	array_map('unlink', array_filter((array) glob("../img/avatars/".$_SESSION["username"]."/*")));
	$sql = "UPDATE users SET avatar = :avatar WHERE username = :username";
    $query = $conn->prepare($sql); 
    
	$file =  "../img/avatars/".$_SESSION["username"]."/".$_FILES["avatar-file"]["name"];
	
	$image = new ImageResize($_FILES['avatar-file']['tmp_name']);
	$image->resize(100, 100, $allow_enlarge = True);
	$image->save($file);
	$query->bindParam(':username', $_SESSION['username']);
	$query->bindValue(':avatar', substr($file,3), PDO::PARAM_STR);
	$query->execute();
	$_SESSION["avatar"] = substr($file,3);;
?>