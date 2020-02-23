<?php 
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
    require_once '../vendor/autoload.php';
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

	$sql = "UPDATE users SET e_mail = :email WHERE username = :username";
    $query = $conn->prepare($sql);

    $email = $antiXss->xss_clean($_POST['email']);
    $username = $antiXss->xss_clean($_SESSION['username']);

    $query->bindParam(':email', $email);
	$query->bindParam(':username', $username);
	$query->execute();
	$_SESSION["email"] = $_POST["email"];
?>