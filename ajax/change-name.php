<?php 
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
    require_once '../vendor/autoload.php';
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

	$sql = "UPDATE users SET name = :name, surname = :surname WHERE username = :username";
    $query = $conn->prepare($sql);

    $name = $antiXss->xss_clean($_POST['name']);
    $surname = $antiXss->xss_clean($_POST['surname']);
    $username = $antiXss->xss_clean($_SESSION['username']);

    $query->bindParam(':name', $name);
    $query->bindParam(':surname', $surname);
	$query->bindParam(':username', $username);
	$query->execute();
	$_SESSION["name"] = $_POST["name"];
	$_SESSION["surname"] = $_POST["surname"];
?>