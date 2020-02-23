<?php 
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
    require_once '../vendor/autoload.php';
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

	$sql = "UPDATE users SET password = :password WHERE username = :username";
    $query = $conn->prepare($sql); 
	$password = md5($_POST['password']);

	$password = $antiXss->xss_clean($password);
	$username = $antiXss->xss_clean($_SESSION["username"]);

    $query->bindParam(':password', $password);
	$query->bindParam(':username', $username);
	$query->execute();
	
	session_unset();
	session_destroy();
?>