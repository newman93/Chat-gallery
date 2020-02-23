<?php 
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
	require_once '../vendor/autoload.php';
	use \voku\helper\AntiXSS;

	$antiXss = new AntiXSS();

	$sql = "UPDATE users SET active = :status WHERE username = :username";
    $query = $conn->prepare($sql);

    $status = $antiXss->xss_clean($_POST['status']);
	$username = $antiXss->xss_clean($_SESSION['username']);

    $query->bindParam(':status', $status);
	$query->bindParam(':username', $username);
	$query->execute();
?>
