<?php
	if (session_status() === PHP_SESSION_NONE){session_start();}
	require_once("../database_connection.php");
    require_once '../vendor/autoload.php';
    use \voku\helper\AntiXSS;

    $antiXss = new AntiXSS();

  $date = date('Y-m-d H:i:s');
  $sql = "INSERT INTO messages (from_username, to_username, message, to_read, date) VALUES (:from, :to, :message, 1, :date)";
  
  $query = $conn->prepare($sql);

  $from = $antiXss->xss_clean($_SESSION['username']);
  $to = $antiXss->xss_clean($_POST['username']);
  $message = $antiXss->xss_clean($_POST['message']);
  $date = $antiXss->xss_clean($date);

  $query->bindParam(':from', $from);
  $query->bindParam(':to', $to);
  $query->bindParam(':message', $message);
  $query->bindParam(':date', $date);
  $query->execute();
  
  $data['name'] = $_SESSION['name'];
  $data['surname'] = $_SESSION['surname'];
  $data['time'] = date("H:i",strtotime($date));
  $data['date'] = date("Y-m-d",strtotime($date));
  
  echo json_encode($data);
?>

