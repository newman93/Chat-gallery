<?php 
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	require_once '../vendor/autoload.php';
    use \Gumlet\ImageResize;
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

    $username = $antiXss->xss_clean($_SESSION["username"]);

	array_map('unlink', array_filter((array) glob("../img/avatars/".$username."/*")));
	$sql = "UPDATE users SET avatar = :avatar WHERE username = :username";
    $query = $conn->prepare($sql); 

    $name = $antiXss->xss_clean($_FILES["avatar-file"]["name"]);

	$file =  "../img/avatars/".$username."/".$name;
	
	$image = new ImageResize($_FILES['avatar-file']['tmp_name']);
	$image->resize(100, 100, $allow_enlarge = True);
	$image->save($file);
	$query->bindParam(':username', $username);
	$query->bindValue(':avatar', substr($file,3), PDO::PARAM_STR);
	$query->execute();
	$_SESSION["avatar"] = substr($file,3);;
?>