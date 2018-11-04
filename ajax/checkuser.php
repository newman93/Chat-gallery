<?php
    require_once("database_connection.php");
    $sql ="SELECT * FROM users WHERE username = :username OR e_mail = :email";
    $query = $conn->prepare($sql); 
    $query->bindParam(':username', $_POST['username']);
    $query->bindParam(':email', $_POST['email']);
    $query->execute();
    $row_count = $query->rowCount();
    if ($row_count > 0) {
       echo "0";
    } else {
        echo "1";
    }
?>