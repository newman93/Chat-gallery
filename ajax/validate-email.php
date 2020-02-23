<?php 
	require_once("../database_connection.php");
    require_once '../vendor/autoload.php';
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

	$sql ="SELECT * FROM users WHERE e_mail = :email";
    $query = $conn->prepare($sql);

    $email = $antiXss->xss_clean($_POST['email']);

    $query->bindParam(':email', $email);
    $query->execute();
    $row_count = $query->rowCount();
    if ($row_count > 0) {
       echo "0";
    } else {
        echo "1";
    }
?>